<?php
shell_exec('sudo apt-get -y php7.0-mbstring');
shell_exec('sudo apt-get -y php5-mbstring');
shell_exec('sudo apt-get -y php-mbstring');
shell_exec('sudo systemctl restart apache2');