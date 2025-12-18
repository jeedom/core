#!/usr/bin/env bash

echo "Begin installation of composer"
wget --tries=3 --timeout=60 https://getcomposer.org/installer -O composer-setup.php 2>&1
php composer-setup.php
php -r "unlink('composer-setup.php');"
[[ -f /usr/local/bin/composer ]] && sudo rm /usr/local/bin/composer || true
sudo mv composer.phar /usr/local/bin/composer
echo "End installation of composer"
