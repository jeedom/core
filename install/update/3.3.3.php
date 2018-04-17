<?php
shell_exec('sudo apt-get -y install php7.0-mbstring');
shell_exec('sudo apt-get -y install php5-mbstring');
shell_exec('sudo apt-get -y install php-mbstring');
shell_exec('sudo systemctl restart apache2');