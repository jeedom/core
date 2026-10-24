#!/usr/bin/env python3
"""Arrêter l'ensemble si un service critique ne démarre pas ou tombe."""
import os
import signal
import sys

while True:
    sys.stdout.write('READY\n')
    sys.stdout.flush()
    header = sys.stdin.readline()
    if not header:
        break
    fields = dict(item.split(':', 1) for item in header.split())
    body = sys.stdin.read(int(fields['len']))
    event = dict(item.split(':', 1) for item in body.split() if ':' in item)
    process = event.get('processname')
    fatal = fields['eventname'] == 'PROCESS_STATE_FATAL'
    unexpected = event.get('expected') == '0'
    if process in {'mariadb', 'bootstrap', 'apache', 'cron', 'atd'} and (fatal or unexpected or process != 'bootstrap'):
        print(f'Échec du service critique {process}, arrêt du conteneur.', file=sys.stderr, flush=True)
        os.kill(os.getppid(), signal.SIGTERM)
    sys.stdout.write('RESULT 2\nOK')
    sys.stdout.flush()
