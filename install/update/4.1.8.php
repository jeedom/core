<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
exec("(ps ax || ps w) | grep -ie 'tunnel-linux-".system::getArch()."' | grep -v grep | awk '{print $1}' | xargs sudo kill -9 > /dev/null 2>&1");
if(file_exists(__DIR__.'/../../script/tunnel')){
  rrmdir(__DIR__.'/../../script/tunnel');
}
?>
