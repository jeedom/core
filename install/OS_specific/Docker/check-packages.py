#!/usr/bin/env python3
"""Comparer l'image Debian 13 aux dépendances de sa propre révision Jeedom."""
import json
from pathlib import Path
import platform
import re
import shlex
import shutil
import subprocess
import sys


# Noms historiques de l'installeur, fournis autrement dans Debian 13.
REPLACEMENTS = {
    'usermod': 'passwd',
    'visudo': 'sudo',
    'libav-tools': 'ffmpeg',
    'libcurl3-gnutls': 'libcurl3t64-gnutls',
    'libcurl4': 'libcurl4t64',
    'ntpdate': 'ntpsec-ntpdate',
}
EXCEPTIONS = {
    'ntp': 'Horloge partagée avec le nœud Kubernetes ; aucun démon NTP dans le conteneur.',
    'python': 'Python 2 retiré de Debian 13 ; Python 3 est installé.',
    'python-pip': 'pip Python 2 retiré ; python3-pip est installé.',
    'python-dev': 'Développement Python 2 retiré ; le catalogue Jeedom l’exclut depuis Debian 11.',
    'software-properties-common': 'Paquet absent de Debian 13 ; aucun dépôt PPA ajouté par cette image.',
    'php-imap': 'Extension IMAP absente des dépôts Debian 13/PHP 8.4 : fonctionnalité non fournie.',
}
COMMANDS = (
    'mysql', 'mysqladmin', 'mysqlcheck', 'mysqldump', 'mariadb',
    'ffmpeg', 'cutycapt', 'xvfb-run', 'xauth', 'espeak', 'mbrola',
    'ntpdate', 'locate', 'telnet', 'dos2unix', 'htop', 'iotop', 'vim', 'iftop',
    'usermod', 'visudo', 'ping', 'node', 'nodejs', 'npm', 'npx', 'composer',
)
PHP_EXTENSIONS = {
    'curl', 'gd', 'json', 'ldap', 'mbstring', 'mysqli', 'pdo_mysql',
    'soap', 'snmp', 'ssh2', 'dom', 'simplexml', 'xml', 'xmlreader', 'xmlwriter',
    'xmlrpc', 'yaml', 'zip', 'zend opcache',
}


def installer_packages(source):
    """Lire les commandes APT littérales, sans exécuter l'installeur système."""
    packages = set()
    for line in source.replace('\\\n', ' ').splitlines():
        # Ne pas analyser les echo, SQL, sed et autres chaînes de l'installeur.
        if not re.match(r'^\s*(?:apt_install|apt-get|apt)\s', line):
            continue
        tokens = shlex.split(line, comments=True)
        if tokens[0] == 'apt_install':
            arguments = tokens[1:]
        elif 'install' in tokens:
            arguments = tokens[tokens.index('install') + 1:]
        else:
            continue
        # Corps du wrapper apt_install ; ses appels sont traités séparément.
        if arguments == ['$@']:
            continue
        for token in arguments:
            if token.startswith('-'):
                continue
            if not re.fullmatch(r'[a-z0-9][a-z0-9+.-]*', token):
                raise ValueError('Commande APT à examiner manuellement : ' + line.strip())
            packages.add(token)
    if not {'apache2', 'mariadb-client', 'php-mysql'} <= packages:
        raise ValueError('Format de l’installeur Jeedom non reconnu : audit APT à mettre à jour.')
    return packages


def requirements(core):
    packages = installer_packages((core / 'install/install.sh').read_text())
    catalogue = json.loads((core / 'install/packages.json').read_text())['apt']
    for name, info in catalogue.items():
        limit = info.get('denyDebianHigherEqual')
        if limit is None or 13 < int(limit):
            packages.add(name)
    # Ne pas dépendre de la présence des alias MySQL dans le seul client MariaDB.
    expected = {REPLACEMENTS.get(name, name) for name in packages if name not in EXCEPTIONS}
    expected.add('mariadb-client-compat')
    return expected, {name: EXCEPTIONS[name] for name in sorted(packages & EXCEPTIONS.keys())}


def installed_packages(output):
    installed = set()
    for line in output.splitlines():
        name, status, provides = line.split('\t', 2)
        if status != 'installed':
            continue
        installed.add(name.split(':')[0])
        # Les paquets virtuels (notamment php-json) peuvent être fournis par PHP CLI.
        for provider in provides.split(','):
            if provider.strip():
                installed.add(provider.strip().split()[0].split(':')[0])
    return installed


def check_packages(expected):
    output = subprocess.check_output(
        ['dpkg-query', '-W', '-f=${binary:Package}\t${db:Status-Status}\t${Provides}\n'], text=True)
    missing = expected - installed_packages(output)
    if missing:
        raise ValueError('Paquets Jeedom absents de l’image : ' + ', '.join(sorted(missing)))


def check_tools():
    missing = [name for name in COMMANDS if shutil.which(name) is None]
    if missing:
        raise ValueError('Commandes Jeedom absentes ou non exécutables : ' + ', '.join(missing))
    # Exécuter les alias historiques : leur paquet seul ne garantit pas leur fonctionnement.
    for name in ('mysql', 'mysqladmin', 'mysqlcheck', 'mysqldump', 'node', 'npm', 'composer'):
        subprocess.run([name, '--version'], check=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE,
                       text=True, timeout=30)
    extensions = json.loads(subprocess.check_output(
        ['php', '-r', 'echo json_encode(get_loaded_extensions());'], text=True))
    missing_extensions = PHP_EXTENSIONS - {name.lower() for name in extensions}
    if missing_extensions:
        raise ValueError('Extensions PHP non chargées : ' + ', '.join(sorted(missing_extensions)))


def main(core=Path('/usr/src/jeedom')):
    release = platform.freedesktop_os_release()
    if release.get('ID') != 'debian' or release.get('VERSION_ID', '').split('.')[0] != '13':
        raise ValueError('Les équivalences de paquets doivent être revues pour cette distribution.')
    expected, exceptions = requirements(core)
    for name, reason in exceptions.items():
        print('Exception documentée : ' + name + ' — ' + reason, flush=True)
    check_packages(expected)
    check_tools()
    print(f'{len(expected)} paquets présents ; outils MySQL, Node, npm, Composer et extensions PHP vérifiés.')


if __name__ == '__main__':
    try:
        main(Path(sys.argv[1]) if len(sys.argv) > 1 else Path('/usr/src/jeedom'))
    except subprocess.CalledProcessError as error:
        details = (error.stderr or error.output or '').strip()
        raise SystemExit(str(error) + (' : ' + details if details else '')) from error
    except (OSError, ValueError, KeyError, subprocess.SubprocessError) as error:
        raise SystemExit(str(error)) from error
