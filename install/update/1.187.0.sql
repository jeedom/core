UPDATE `cron` SET `lastrun`="2015-01-01 00:00:00" WHERE `lastrun`="0000-00-00 00:00:00";
ALTER TABLE `cron` CHANGE `deamonSleepTime` `deamonSleepTime` float NULL;