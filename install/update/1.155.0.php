<?php
try {
    $sql = 'ALTER TABLE history DROP CONSTRAINT fk_history_cmd1;';
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
    $sql = 'ALTER TABLE history 
			ADD CONSTRAINT fk_history_cmd1
			FOREIGN KEY (`cmd_id`)
		    REFERENCES `cmd` (`id`)
		    ON DELETE CASCADE
		    ON UPDATE CASCADE';
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
   
}
?>