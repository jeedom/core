#!/usr/bin/env python3
"""Validation Docker réelle avant publication ; volumes et conteneurs jetables."""
import json
from pathlib import Path
import subprocess
import sys
import time
import uuid


def docker(*args, check=True, timeout=120, stdin=None):
    result = subprocess.run(['docker', *args], input=stdin, capture_output=True, text=True, timeout=timeout)
    if check and result.returncode:
        raise RuntimeError(f'Docker {args[0]} a échoué : {result.stderr or result.stdout}')
    return (result.stdout + (result.stderr if args[0] == 'logs' else '')).strip()


def state(name):
    return json.loads(docker('inspect', name))[0]['State']


def wait_healthy(name):
    deadline = time.monotonic() + 420
    while time.monotonic() < deadline:
        current = state(name)
        if not current['Running']:
            raise RuntimeError('Le conteneur est arrêté avant disponibilité.')
        if current.get('Health', {}).get('Status') == 'healthy':
            return
        time.sleep(2)
    raise RuntimeError('Jeedom ne devient pas disponible dans le délai prévu.')


def wait_stopped(name):
    deadline = time.monotonic() + 100
    while time.monotonic() < deadline:
        if not state(name)['Running']:
            return
        time.sleep(1)
    raise RuntimeError('Le conteneur ne s’arrête pas après une erreur critique.')


def php(name, code):
    return docker('exec', name, 'php', '-r', code)


DB = '''require '/var/www/html/core/config/common.config.php';
$d=$CONFIG['db']; $p=new PDO('mysql:host='.$d['host'].';port='.$d['port'].';dbname='.$d['dbname'],
$d['username'],$d['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);'''


def main(image):
    prefix = 'jeedom-image-test-' + uuid.uuid4().hex[:10]
    volumes = {key: prefix + '-' + key for key in ('html', 'database', 'empty-html', 'empty-database')}
    containers = []

    def start(label, html='html', database='database', environment=()):
        name = prefix + '-' + label
        containers.append(name)
        docker('run', '-d', '--name', name,
               '--mount', f'type=volume,src={volumes[html]},dst=/var/www/html',
               '--mount', f'type=volume,src={volumes[database]},dst=/var/lib/mysql',
               *[argument for value in environment for argument in ('-e', value)], image)
        return name

    try:
        configuration_test = Path(__file__).with_name('test_configuration.php').resolve()
        print(docker('run', '--rm', '-i', image, 'php', stdin=configuration_test.read_text()), flush=True)
        for volume in volumes.values():
            docker('volume', 'create', volume)
        first = start('fresh')
        wait_healthy(first)
        expected_version = Path(__file__).resolve().parents[4] / 'core/config/version'
        actual_version = docker('exec', first, 'cat', '/var/www/html/core/config/version')
        if actual_version != expected_version.read_text().strip():
            raise RuntimeError('La version exécutée ne correspond pas à la source du build.')
        php(first, DB + "$p->exec(\"INSERT INTO config(plugin,`key`,value) VALUES('core','docker_persistence_test','preserved')\");")
        docker('exec', first, 'sh', '-ec',
               "printf 'user content' > /var/www/html/data/docker-test.txt; "
               "chmod 640 /var/www/html/data/docker-test.txt; "
               "printf '\n/* docker persistence test */\n' >> /var/www/html/index.php")
        config_hash = docker('exec', first, 'sha256sum', '/var/www/html/core/config/common.config.php')
        index_hash = docker('exec', first, 'sha256sum', '/var/www/html/index.php')
        # Le heartbeat doit réellement être rafraîchi par cron, pas seulement par le bootstrap.
        before = int(docker('exec', first, 'stat', '-c', '%Y', '/run/jeedom/cron-heartbeat'))
        deadline = time.monotonic() + 90
        while int(docker('exec', first, 'stat', '-c', '%Y', '/run/jeedom/cron-heartbeat')) <= before:
            if time.monotonic() >= deadline:
                raise RuntimeError('Le service cron ne déclenche pas ses tâches.')
            time.sleep(2)
        docker('stop', '--time', '90', first)
        if state(first)['ExitCode'] != 0 or state(first).get('OOMKilled'):
            raise RuntimeError('Arrêt normal du conteneur incorrect.')

        second = start('recreated')
        wait_healthy(second)
        assert docker('exec', second, 'cat', '/var/www/html/data/docker-test.txt') == 'user content'
        assert docker('exec', second, 'stat', '-c', '%a', '/var/www/html/data/docker-test.txt') == '640'
        assert docker('exec', second, 'sha256sum', '/var/www/html/core/config/common.config.php') == config_hash
        assert docker('exec', second, 'sha256sum', '/var/www/html/index.php') == index_hash
        assert php(second, DB + "echo $p->query(\"SELECT value FROM config WHERE plugin='core' AND `key`='docker_persistence_test'\")->fetchColumn();") == 'preserved'
        docker('stop', '--time', '90', second)

        missing_html = start('missing-config', html='empty-html')
        wait_stopped(missing_html)
        assert 'Base Jeedom existante sans configuration' in docker('logs', missing_html)
        missing_db = start('missing-database', database='empty-database')
        wait_stopped(missing_db)
        assert 'Stockage MariaDB manquant pour la configuration existante' in docker('logs', missing_db)

        invalid_password = start('invalid-password', environment=('DB_PASSWORD=invalid-migration-password',))
        wait_stopped(invalid_password)
        assert 'Authentification MariaDB refusée' in docker('logs', invalid_password)

        recovered = start('recovered')
        wait_healthy(recovered)
        assert php(recovered, DB + "echo $p->query(\"SELECT value FROM config WHERE plugin='core' AND `key`='docker_persistence_test'\")->fetchColumn();") == 'preserved'
        assert docker('exec', recovered, 'sha256sum', '/var/www/html/core/config/common.config.php') == config_hash
        pid = docker('exec', recovered, 'supervisorctl', '-c', '/etc/supervisor/jeedom.conf', 'pid', 'runtime:apache')
        docker('exec', recovered, 'kill', '-KILL', pid)
        wait_stopped(recovered)
        print(f'Image {image} validée : installation, cron, arrêt, recréation, persistance et erreurs non destructives.')
    except Exception:
        for name in containers:
            result = subprocess.run(['docker', 'logs', '--tail', '120', name], capture_output=True, text=True)
            print(f'--- {name} ---\n{result.stdout}\n{result.stderr}', file=sys.stderr)
        raise
    finally:
        for name in containers:
            docker('rm', '-f', name, check=False)
        for volume in volumes.values():
            docker('volume', 'rm', volume, check=False)


if __name__ == '__main__':
    if len(sys.argv) != 2:
        raise SystemExit('Usage : test_runtime.py IMAGE')
    main(sys.argv[1])
