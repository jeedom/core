ALTER TABLE `cmd`
DROP `cache`,
DROP `eventOnly`;

ALTER TABLE `cmd`
ADD `html` mediumtext NULL AFTER `eqLogic_id`;