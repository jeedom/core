#!/usr/bin/env bash

echo "Begin installation of composer"
wget https://getcomposer.org/installer -O composer-setup.php
php composer-setup.php
php -r "unlink('composer-setup.php');"
[[ -f /usr/local/bin/composer ]] && sudo rm /usr/local/bin/composer || true
sudo mv composer.phar /usr/local/bin/composer
echo "End installation of composer"
