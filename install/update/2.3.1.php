<?php
shell_exec("find /etc/apache2/conf-available/* -exec sed -i 's/#*[Cc]ustom[Ll]og/#CustomLog/g' {} \;");
?>