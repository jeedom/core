ALTER TABLE eqLogic ADD generic_type VARCHAR(255) NULL;
ALTER TABLE cmd ADD generic_type VARCHAR(255) NULL;
CREATE INDEX `generic_type` ON eqLogic (`generic_type` ASC);
CREATE INDEX `genericType_eqLogicID` ON cmd (`logicalId` ASC, `generic_type` ASC);