ALTER TABLE eqLogic ADD tags VARCHAR(255) NULL;
CREATE INDEX `tags` ON eqLogic (`tags` ASC);