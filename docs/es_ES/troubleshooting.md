Tengo una página en blanco
=====================

Tienes que SSH en Jeedom y ejecutar el script
Autodiagnóstico:

``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```

Si hay una preocupación, el guión va a tratar de solucionarlo. Si sucede
no, que se lo dirá.

También puede ver el registro de /var/www/html/http.error. muy
A menudo, esto indica una preocupación.

Tengo un problema de identificación de BDD
==================================

Tenemos que restablecer los siguientes:

``` {.bash}
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p
echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p
cd /usr/share/nginx/www/jeedom
sudo cp core/config/common.config.sample.php core/config/common.config.php
sudo sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php
sudo chown www-data:www-data core/config/common.config.php
```

Tengo {{...}} en todas partes
=======================

La causa más común es el uso de una beta plug-in
y Jeedom estable, o viceversa. La lista completa del error,
debe mirar el http.error registro (en / var / www / html / log).

Al realizar el pedido Tengo una rueda que gira sin parar
================================================== =========

De nuevo, esto es a menudo debido a un plugin en fase beta, mientras que Jeedom
es estable. Para ver el error, hay que F12 y la consola.

Ya no tengo acceso a Jeedom oa través de la interfaz web o mediante SSH consola
================================================== =======================

Este error no se debe a Jeedom, pero un problema con el sistema.
Si persiste después de la reinstalación, se recomienda
ver con el servicio de una preocupación de hardware.

Mi escenario no se detiene / no
=================================

Es recomendable mirar los comandos ejecutados por el guión,
a menudo se trata de un comando que no termina.

I inestabilidades o errores 504
========================================

Compruebe si su sistema de archivos no está dañado por la SSH
comando "sudo dmesg | grep error".

No veo todo mi equipo en el salpicadero
================================================== ==

A menudo esto se debe al hecho de que las instalaciones son asignados a un objeto
no el hijo o el propio objeto del primer objeto seleccionado
la izquierda en el árbol (se puede establecer en su perfil).

Tengo el siguiente error: SQLSTATE \ [HY000 \] \ [2002 \] No se puede conectar al servidor MySQL local a través de socket '/var/run/mysqld/mysqld.sock'
================================================== ================================================== ================================

Esto se debe a MySQL que se detuvo, no es normal, caso
comunes son:

-   La falta de espacio en el sistema de archivos (se puede comprobar
    haciendo que el comando "df -h" SSH)

-   tema (s) la corrupción de archivos, que a menudo sucede después de
    un cierre no está correctamente Jeedom (corte de corriente)

Por desgracia, no hay muchos solución si se trata de la segunda
caso lo mejor es restaurar una copia de seguridad (disponible
/ Usr / share / nginx / www / jeedom / default copia de seguridad), y volver a instalar Jeedom
restaurar la copia de seguridad. También puede analizar por qué MySQL
querer arrancar desde una consola SSH:

``` {.bash}
sudo su -
service mysql stop
mysqld --verbose
```

O consulte el registro de: /var/log/mysql/error.log

Los botones de apagado / reinicio no funciona
================================================== =

En un sistema de bricolaje es normal. SSH, usted tiene que controlar
visudo y al final del archivo debe añadir: www-data ALL = (ALL)
NOPASSWD: TODOS.

``` {.bash}
sudo service apache2 restart
```

No veo algunos plugins Mercado
=========================================

Tales casos ocurren si su Jeedom no es compatible con el
plugin. En general, una actualización corrige preocupación jeedom.
