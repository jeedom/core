ALTER TABLE `config`
CHANGE `value` `value` text COLLATE 'utf8_general_ci' NULL AFTER `key`;