<?php

$install_dir = dirname(__FILE__) . '/../';
exec('cd ' . $install_dir . '; git config remote.origin.url http://git.jeedom.fr/jeedom/core.git');
