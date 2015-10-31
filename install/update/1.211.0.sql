ALTER TABLE `interactQuery`
ADD `actions` text COLLATE 'utf8_general_ci' NULL;

ALTER TABLE `interactDef`
ADD `actions` text COLLATE 'utf8_general_ci' NULL;

ALTER TABLE `interactDef`
DROP `link_type`,
DROP `link_id`;

ALTER TABLE `interactQuery`
DROP `link_type`,
DROP `link_id`;

ALTER TABLE `interactQuery`
DROP `enable`;