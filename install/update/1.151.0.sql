ALTER TABLE `history` 
DROP PRIMARY KEY;

ALTER TABLE `historyArch` 
DROP PRIMARY KEY;

ALTER TABLE `history` 
DROP FOREIGN KEY `fk_history_cmd1`;

ALTER TABLE `historyArch` 
DROP FOREIGN KEY `fk_historyArch_cmd1`;