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
INSTALLER=$(PHP) install/install.php

help: ## Show this help
	 @echo -e "$$(grep -hE '^\S+:.*##' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' -e 's/^\(.\+\):\(.*\)/\\x1b[36m\1\\x1b[m:\2/' | column -c2 -t -s :)"

server: ## Run php internal server
	$(PHP) -S $(SERVER_HOST):$(SERVER_PORT) -t $(PUBLIC_PATH)

install: vendor create-database $(CONFIGURATION_FILE) ## Installe Jeedom
	$(INSTALLER)

test: phpunit.xml vendor
	vendor/bin/phpunit --configuration $<

vendor: composer.lock
	composer install

composer.lock: composer.json
	composer update

phpunit.xml: phpunit.xml.dist
	cp $< $@

create-database:
	$(SQL_EXEC) --execute='DROP IF EXISTS DATABASE $(DBNAME);'
	$(SQL_EXEC) --execute='CREATE DATABASE $(DBNAME);'
