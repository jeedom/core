#!/bin/bash

echo "Begin installation of composer"
wget https://getcomposer.org/installer -O composer-setup.php
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo rm /usr/local/bin/composer
sudo mv composer.phar /usr/local/bin/composer
echo "End installation of composer"
