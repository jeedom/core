Obtengo una página en blanco  
Debe conectarse a jeedom por SSH y lanzar un script de autodiagnóstico:

<!-- -->

    sudo chmod +x /usr/share/nginx/www/jeedom/health.sh;sudo /usr/share/nginx/www/jeedom/health.sh

Este es el resultado si todo es correcto:

![](../images/depannage.png)

Si hay un problema en el script lo intentará corregir, si no se lo indicará.

También puede ver el registro de usr/share/nginx/www/jeedom/log/nginx.error, muy a menudo esto le indica los problemas

Tengo un problema de identificación de la BDD: Necesidad de reajustarlos

    bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
    echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p
    echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p
    echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p
    cd /usr/share/nginx/www/jeedom
    sudo cp core/config/common.config.sample.php core/config/common.config.php
    sudo sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php
    sudo chown www-data:www-data core/config/common.config.php

Tengo {…} en todas partes. La cause la plus fréquente est l’utilisation d’un plugin en version beta, et Jeedom en stable, ou l’inverse

Al lanzar un comando tengo una rueda que gira sin parar  
Sin embargo, esto ocurre amenudo debido al uso de un plugin en versión beta, mientras que Jeedom es estable

Ya no tengo acceso a Jeedom ni por ssh:. Este error es debido a un problema con el sistema pero no de Jeedom. Si persiste después de la reinstalación es recomendable comprobar con el SAT si se trata de un problema de hardware

Mi escena no funciona  
Se recomienda ver los comandos ejecutadas por la escena, a menudo esto es una orden que no termina (Bucle loop)

Mon scénario est lent à se déclencher  
Si la escena no tiene pausas es aconsejable chequear "ejecutar tan pronto como sea posible" (disponible en modo experto)

No tengo ningún error en el registro nginx.error a pesar de los errores 500  
ssh : Editez le fichier /etc/php5/fpm/pool.d/www.conf et décommantez la ligne ";catch\_worket\_process=yes" (supression du ;) Reiniciar el php "sudo service php5-fpm restart"

Tengo inestabilidades o errores 504  
Vérifiez si votre système de fichiers n’est pas corrompu, en ssh la commande est : "sudo dmesg | grep error"

No veo todos mis dispositivos en el dashboard  
A menudo esto es debido a que los dispositivos se asignan a un objeto del que no es hijo l’objet lui-même du premier objet sélectionné à gauche dans l’arbre (vous pouvez dans votre profil configurer celui-ci)

Al conectar al maestro con los esclavos obtengo errores  
Compruebe que ha puesto el /jeedom correctamente Si lo utilizas debe ir justo después de la IP jeedom (a menudo en caso de instalación DIY)

Tengo el siguiente error : SQLSTATE[HY000] [2002] Can’t connect to local MySQL server through socket */var/run/mysqld/mysqld.sock*  
Esto se debe a que MySQL se detiene , no es normal, las posibles causas son :

-   souci de place sur le système de fichiers (peut être vérifié en faisant la commande "df -h", en ssh)

-   problème de corruption de fichier(s), arrive souvent suite à un arrêt non propre de Jeedom (coupure de courant)

Por desgracia no hay solución en el segundo caso, Lo mejor sería recuperar una copia de seguridad (disponibles en /usr/share/nginx/www/jeedom/backup por defecto), de réinstaller Jeedom et de restaurer le backup. También puedes comprobar desde SSH porque no se ejecuta mysql:

    sudo su -
    service mysql stop
    mysqld --verbose

O consultar el registro: /var/log/mysql/error.log

Les boutons Eteindre/Redémarrer ne fonctionnent pas  
En una instalación DIY es normal hacer uso del comando visudo en SSH Debe añadir: www-data ALL=(ALL) NOPASSWD: ALL.

A menudo obtengo un "502 bad gateway": Il faut aller (en mode expert) dans la configuration de Jeedom, puis Système OS et cliquer sur Lancer. Là vous cliquez sur "PHP log", si vous avez le message "server reached pm.max\_clidren", c’est qu’il manque des processus php fpm, il faut donc en autoriser plus. Pour cela faire :

    sudo su -
    cd /etc/php5/fpm/pool.d/
    vim www.conf

Editar la linea "pm.max\_children" en mettant "pm.max\_children = 20"

Simplemente reinicie php5-fpm

    sudo service php5-fpm restart

Je ne vois pas certains plugins du Market  
Ce genre de cas arrive si votre Jeedom n’est pas compatible avec le plugin, en général une mise à jour de jeedom corrige le souci


