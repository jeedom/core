#!/usr/bin/env python3

try:
    import zhaquirks
    # zhaquirks is installed

    try:
        from zhaquirks.xiaomi.aqara.remote_h1 import RemoteH1DoubleRocker3

    except ImportError:
        # but RemoteH1DoubleRocker3 is not here: PATCH needed

        from subprocess import check_call
        import pip

        check_call('python3 -m pip install -U git+https://github.com/Alcolo47/zha-device-handlers.git@fix/xaomi_aqara_h1'.split())
        # Restart zigbee daemon carefully :gun:
        check_call('pkill -f .*/zigbeed.py'.split())

except ImportError:
    pass
