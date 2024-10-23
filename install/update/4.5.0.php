<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

try {
    echo 'Empty old cache system...';
    cache::flush();
    echo "OK\n";
    echo 'Empty DB cache table...';
    DB::prepare('TRUNCATE TABLE `cache`',array(),DB::FETCH_TYPE_ALL);
    echo "OK\n";
} catch (\Throwable $th) {
    echo 'Error on empty cache : '.$th->getMessage();
}
try {
    echo "Save cache state of cmd and eqLogic...";
    $data = array('cmd' => array(),'eqLogic' => array());
    foreach(cmd::all() as  $cmd){
      $data['cmd'][$cmd->getId()] = $cmd->getCache();
    }
    foreach(eqLogic::all() as  $eqLogic){
      $data['eqLogic'][$eqLogic->getId()] = $eqLogic->getCache();
    }
    file_put_contents('/tmp/jeedom/cache.json',json_encode($data));
    echo "OK\n";
} catch (Exception $e) {
    echo '***WARNING***' . $e->getMessage();
}
echo shell_exec('php '.__DIR__.'/reloadCache.php');