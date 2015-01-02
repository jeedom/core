<?php

try {
    $sql = 'ALTER TABLE `jeedom`.`cache` 
            CHANGE COLUMN `value` `value` MEDIUMTEXT NULL DEFAULT NULL ;';
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
}

?>
