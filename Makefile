.PHONY: server test install create-database
.DEFAULT_GOAL=help

-include .env

DB_HOST?=localhost
DB_PORT?=3306
DB_USER?=root
DB_PASSWORD?=root
DB_NAME?=jeedom

SQL_EXEC?=mysql \
--user=$(DB_USER) \
--password=$(DB_PASSWORD) \
--host $(DB_HOST)

PHP?=php
PHPUNIT?=vendor/bin/phpunit
SERVER_HOST?=localhost
SERVER_PORT?=80
PUBLIC_PATH=./

CONFIGURATION_FILE=core/config/common.config.php
CONFIGURATION_FILE_SAMPLE=core/config/common.config.sample.php
INSTALLER=$(PHP) install/install.php

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

server: ## Run php internal server
	$(PHP) -S $(SERVER_HOST):$(SERVER_PORT) -t $(PUBLIC_PATH)

install: vendor create-database ## Install Jeedom
	$(INSTALLER)

test: phpunit.xml vendor
	vendor/bin/phpunit --configuration $<

vendor: composer.lock
	composer install

composer.lock: composer.json
	composer update

phpunit.xml: phpunit.xml.dist
	cp $< $@

create-database: $(CONFIGURATION_FILE) ## Create database and user (must be super-user)
	sudo mysql --execute="DROP DATABASE IF EXISTS $(DB_NAME);"
	sudo mysql --execute="DROP USER IF EXISTS '$(DB_USER)'@'$(DB_HOST)';"
	sudo mysql --execute="CREATE DATABASE $(DB_NAME);"
	sudo mysql --execute="CREATE USER '$(DB_USER)'@'$(DB_HOST)' IDENTIFIED BY '$(DB_PASSWORD)';"
	sudo mysql --execute="GRANT ALL PRIVILEGES ON $(DB_NAME).* TO '$(DB_USER)'@'$(DB_HOST)' IDENTIFIED BY '$(DB_PASSWORD)';"

$(CONFIGURATION_FILE): $(CONFIGURATION_FILE_SAMPLE)
	cp $< $@
	sed -i "s/#PASSWORD#/$(DB_PASSWORD)/g" $@
	sed -i "s/#DBNAME#/$(DB_NAME)/g" $@
	sed -i "s/#USERNAME#/$(DB_USER)/g" $@
	sed -i "s/#PORT#/$(DB_PORT)/g" $@
	sed -i "s/#HOST#/$(DB_HOST)/g" $@