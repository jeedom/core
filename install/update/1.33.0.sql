ALTER TABLE `config` 
CHANGE COLUMN `module` `plugin` VARCHAR(127) NOT NULL DEFAULT 'core' ;

ALTER TABLE `jeedom`.`message` 
CHANGE COLUMN `module` `plugin` VARCHAR(127) NOT NULL ;

UPDATE `eqLogic` SET `eqType_name`= "zwave" WHERE `eqType_name`= "razberry";

UPDATE `config` SET `plugin` = 'zwave' WHERE `plugin` = 'razberry';

UPDATE `cron` SET `class` = 'zwave' WHERE `class` = 'razberry';

UPDATE `cache` SET `key` = 'zwave::lastUpdate' WHERE `key` = 'razberry::lastUpdate';

UPDATE `jeedom`.`config` SET `key` = 'zwaveAddr' WHERE `config`.`plugin` = 'zwave' AND `config`.`key` = 'razberryAddr';

UPDATE `jeedom`.`config` SET `key` = 'enableZwaveDeamon' WHERE `config`.`plugin` = 'zwave' AND `config`.`key` = 'enableRazberryDeamon';