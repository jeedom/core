ALTER TABLE history ADD INDEX date_time (datetime ASC);
ALTER TABLE historyArch ADD INDEX date_time (datetime ASC);
ALTER TABLE history ADD INDEX history5min_commands1_idx (cmd_id ASC, datetime ASC);
ALTER TABLE historyArch ADD INDEX history5min_commands1_idx (cmd_id ASC, datetime ASC);
ALTER TABLE scenario ADD INDEX isActive (isActive ASC);
ALTER TABLE eqLogic ADD INDEX isEnable (isEnable ASC);
