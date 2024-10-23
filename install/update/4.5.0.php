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
    if(file_exists('/tmp/jeedom/cache.json')){
        echo "Save state cache found, load it....";
        $data = json_decode(file_get_contents('/tmp/jeedom/cache.json'),true);
        foreach ($data['cmd'] as $id => $value) {
            $cmd = cmd::byId($id);
            if(is_object($cmd)){
                $cmd->setCache($value);
            }
        }
        foreach ($data['eqLogic'] as $id => $value) {
            $eqLogic = eqLogic::byId($id);
            if(is_object($cmd)){
                $eqLogic->setCache($value);
            }
        }
        unlink('/tmp/jeedom/cache.json');
        echo "OK\n";
    } 
} catch (\Throwable $th) {
    echo 'Error on reload cache : '.$th->getMessage();
}