<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Migration: install packages for Debian 13 compatibility
shell_exec(system::getCmdSudo() . 'apt-get -y install chrony espeak-ng libcurl4 plocate');
// Kept disabled on purpose: a pre-migration backup restore may still reference these packages
// shell_exec(system::getCmdSudo() . 'apt-get -y remove ntp ntpdate espeak mbrola libcurl3-gnutls locate 2>/dev/null');
