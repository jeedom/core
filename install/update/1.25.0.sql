ALTER TABLE `eqLogic` 
ADD INDEX `timeout` (`timeout` ASC);

ALTER TABLE `chat` 
ENGINE = MEMORY ;

ALTER TABLE `scenario` 
ADD UNIQUE INDEX `name` (`name` ASC);
