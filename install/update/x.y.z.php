<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Migration: replace/remove packages for Debian 13 compatibility
shell_exec(system::getCmdSudo() . 'apt-get -y install chrony espeak-ng libcurl4 plocate');
shell_exec(system::getCmdSudo() . 'apt-get -y remove ntp ntpdate espeak mbrola libcurl3-gnutls locate 2>/dev/null');
