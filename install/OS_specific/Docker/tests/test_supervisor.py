"""Contrat du protocole événementiel Supervisor et arrêt des services critiques."""
import io
from pathlib import Path
import runpy
import signal
import unittest
from unittest.mock import patch


EVENTS = Path(__file__).resolve().parents[1] / 'rootfs/usr/local/lib/jeedom/supervisor-events.py'


class JeedomSupervisorTests(unittest.TestCase):
    def process(self, name, event='PROCESS_STATE_EXITED', expected='1'):
        body = f'processname:{name} groupname:runtime from_state:RUNNING expected:{expected} pid:12'
        packet = f'ver:3.0 server:supervisor serial:1 pool:listener poolserial:1 eventname:{event} len:{len(body)}\n{body}'
        output = io.StringIO()
        with patch('sys.stdin', io.StringIO(packet)), patch('sys.stdout', output), \
                patch('sys.stderr', io.StringIO()), patch('os.getppid', return_value=123), patch('os.kill') as kill:
            runpy.run_path(str(EVENTS))
        self.assertIn('RESULT 2\nOK', output.getvalue())
        return kill

    def test_successful_bootstrap_does_not_stop_services(self):
        self.process('bootstrap').assert_not_called()

    def test_failed_bootstrap_or_service_stops_the_supervisor(self):
        for name, event, expected in [('bootstrap', 'PROCESS_STATE_EXITED', '0'),
                                      ('bootstrap', 'PROCESS_STATE_FATAL', '1'),
                                      ('apache', 'PROCESS_STATE_EXITED', '1'),
                                      ('cron', 'PROCESS_STATE_EXITED', '0'),
                                      ('mariadb', 'PROCESS_STATE_FATAL', '1')]:
            with self.subTest(name=name, event=event, expected=expected):
                self.process(name, event, expected).assert_called_once_with(123, signal.SIGTERM)
