<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Migration: replace ntp/espeak packages for Debian 13 compatibility
shell_exec(system::getCmdSudo() . 'apt-get -y install chrony espeak-ng libcurl4');
shell_exec(system::getCmdSudo() . 'apt-get -y remove ntp ntpdate espeak mbrola libcurl3-gnutls 2>/dev/null');
