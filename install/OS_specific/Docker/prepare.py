#!/usr/bin/env python3
"""Décrire le checkout du core utilisé comme contexte de construction."""
import json
from pathlib import Path
import re
import subprocess

ROOT = Path(__file__).resolve().parent
CORE = ROOT.parents[2]


def main():
    revision = subprocess.check_output(
        ['git', '-C', str(CORE), 'rev-parse', 'HEAD'], text=True).strip()
    ref = subprocess.check_output(
        ['git', '-C', str(CORE), 'rev-parse', '--abbrev-ref', 'HEAD'], text=True).strip()
    version = (CORE / 'core/config/version').read_text().strip()
    if not re.fullmatch(r'\d+\.\d+\.\d+(?:[.-][A-Za-z0-9.-]+)?', version):
        raise SystemExit('Version du core invalide pour la publication Docker.')
    (ROOT / 'source.json').write_text(json.dumps({
        'repository': 'https://github.com/jeedom/core', 'ref': ref,
        'revision': revision, 'version': version,
    }, indent=2) + '\n')
    print(f'Core Jeedom {version}, révision {revision}')


if __name__ == '__main__':
    main()
