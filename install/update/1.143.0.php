<?php

try {
    $sql = "ALTER TABLE `scenario` 
            ADD COLUMN `type` VARCHAR(127) NULL DEFAULT 'expert' AFTER `configuration`;";
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
    
}
?>
