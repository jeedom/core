<?php
if(strpos( $_SERVER['SERVER_SOFTWARE'], 'Apache') === 'TRUE'){
  shell_exec('sudo rm /etc/apache2/conf-available/other-vhosts-access-log.conf > /dev/null;sudo rm /etc/apache2/conf-enabled/other-vhosts-access-log.conf > /dev/null');
  shell_exec('sudo rm /etc/apache2/sites-enabled/000-default.conf  > /dev/null;sudo mv ../apache_default /etc/apache2/sites-enabled/000-default.conf > /dev/null; sudo systemctl restart apache2 > /dev/null');
}
if(function_exists('ssh2_connect')) {} else { shell_exec('sudo apt-get install -y php5-ssh2 > /dev/null'); }
?>
