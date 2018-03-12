CREATE TABLE IF NOT EXISTS `plan3dHeader` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(127) NULL,
  `configuration` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `plan3d` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `plan3dHeader_id` INT NOT NULL,
  `link_type` VARCHAR(127) NULL,
  `link_id` VARCHAR(127) NULL,
  `position` TEXT NULL,
  `display` TEXT NULL,
  `css` TEXT NULL,
  `configuration` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `name` (`name` ASC),
  INDEX `link_type_link_id` (`link_type` ASC, `link_id` ASC),
  INDEX `fk_plan3d_plan3dHeader1_idx` (`plan3dHeader_id` ASC),
  CONSTRAINT `fk_plan3d_plan3dHeader1`
    FOREIGN KEY (`plan3dHeader_id`)
    REFERENCES `plan3dHeader` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;