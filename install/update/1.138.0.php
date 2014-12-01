<?php

try {
    $sql = 'ALTER TABLE  `cache` ENGINE = MYISAM ;ALTER TABLE `jeedom`.`cache` 
            CHANGE COLUMN `value` `value` MEDIUMTEXT NULL DEFAULT NULL ;';
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
    echo "Failed on update DB : ";
    print_r($e);
}
?>
