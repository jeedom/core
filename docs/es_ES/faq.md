Jeedom que requiere una suscripción?
=====================================

No, Jeedom es plenamente utilizable sin necesidad de ningún
suscripción de ningún tipo. Sin embargo, hay servicios disponibles para
copias de seguridad o de llamada / SMS, pero sigue siendo muy
opcional.

Jeedom que utiliza servidores externos para operar?
================================================== ============

No, Jeedom no utiliza este tipo de infraestructura "nube". Todo se hace
local y no necesita nuestros servidores a su
La instalación funciona. Sólo los servicios como el mercado, la
copia de seguridad en línea o DNS Jeedom requieren el uso de nuestra
servidores.

Podemos cambiar el orden de los equipos?
==================================================

Sí es posible, basta con arrastrar / soltar los controles de su
objeto en su configuración.

Puede editar el estilo de los widgets?
=====================================

Sí es posible, ya sea a través del plugin de flash, o
utilizando la página de visualización general →

Podemos poner varias veces el mismo equipo en un diseño?
================================================== ==============

No, esto no es posible, pero se puede duplicar a través
plug-in virtual.

Cómo cambiar un historial de datos erróneos?
================================================== ==

Un poco más de una curva histórico de la orden, haga clic en el
pregunta punta en. Si deja los campos en blanco, entonces el valor
serán eliminados.

¿Cuánto tiempo hace una copia de seguridad?
======================================

No hay una duración estándar, dependiendo del volumen del sistema
los datos de copia de seguridad, pero puede tomar más de 5 minutos se
normal.

¿Hay una aplicación móvil dedicado?
========================================

Jeedom tiene una versión móvil para su uso en el móvil y
tableta. También hay una aplicación nativa para Android y iOS.

¿Cuáles son las credenciales para conectar la primera vez?
================================================== ==============

Cuando se conecta por primera vez a Jeedom (y más allá si lo hace
no han cambiado), el nombre de usuario y la contraseña predeterminada
son admin / admin. En la primera conexión, es fuertemente
recomendadas para cambiar estos identificadores de seguridad.

Podemos poner Jeedom https?
================================

Sí: \ * O tienes una fuente de alimentación o más, en cuyo caso se
Sólo tiene que utilizar el Jeedom DNS. \ * O bien tienen un DNS y sabes
establecer un certificado válido, en este caso se trata de una instalación
certificado estándar.

Cómo revisar los derechos?
====================================

SSH hizo:

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

¿Dónde están las copias de seguridad Jeedom?
==========================================

Ellos están en el directorio / var / www / html / copia de seguridad

Cómo actualizar Jeedom SSH?
=====================================

SSH hizo:

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

Webapp es compatible Symbian?
=======================================

La aplicación web requiere un teléfono inteligente que soporta HTML5 y CSS3. Ella
Así que por desgracia no es compatible con Symbian.

¿En qué plataformas Jeedom puede funcionar?
================================================== ==

Para el trabajo Jeedom, necesita una plataforma Linux con los derechos
raíz o un sistema típico ventana acoplable. Por lo que no funciona en una
plataforma Android puro.

No puedo actualizar algún plugin "Error al descargar el archivo. Vuelva a intentarlo más tarde (tamaño menos de 100 bytes)..."? 
====================================================

Esto puede ser debido a varias cosas, es necesario : 

- Comprueba que tu Jeedom sigue conectado al market (en la página de administración de jeedom, Centro de actualizaciónes, tienes un botón de prueba)
- Verifique que la cuenta de mercado ha adquirido el plugin en cuestión.
- Comprueba que tienes espacio en Jeedom (la página de salud te lo dirá)
- Comprueba que tu versión de Jeedom es compatible con el plugin

Tengo una página en blanco 
===========================

Necesitas conectar por SSH a Jeedom y ejecutar el script
de autodiagnóstico :

``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```

Si hay un problema, el script intentará solucionarlo. Si no puede hacerlo
el te lo informará.

También puede ver el error en el registro /var/www/html/log/http.error. Muy
a menudo indica el problema.

Tengo un problema con el identificador BDD 
===========================================

Estos deben ser reseteados :

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

Tengo {{…​}} por todas partes 
==============================

La causa más frecuente es el uso de un plugin en versión beta.
y Jeedom en estable, o viceversa. Para obtener los detalles del error,
ver el registro de http.error. (en /var/www/html/log).

Lors d’une commande j’ai une roue qui tourne sans s’arrêter 
===========================================================

Encore une fois cela est souvent dû à un plugin en beta alors que Jeedom
est en stable. Pour voir l’erreur, il faut faire F12 puis console.

Je n’ai plus accès à Jeedom, ni par l’interface web ni en console par SSH 
=========================================================================

Cette erreur n’est pas due à Jeedom, mais à un problème avec le système.
Si celui-ci persiste suite à une réinstallation, il est conseillé de
voir avec le SAV pour un souci hardware.

Mon scénario ne s’arrête plus/pas 
=================================

Il est conseillé de regarder les commandes exécutées par le scénario,
souvent cela vient d’une commande qui ne se termine pas.

J’ai des instabilités ou des erreurs 504 
========================================

Vérifiez si votre système de fichiers n’est pas corrompu, en SSH la
commande est : "sudo dmesg | grep error" .

Je ne vois pas tous mes équipements sur le dashboard 
====================================================

Souvent cela est dû au fait que les équipements sont affectés à un objet
qui n’est pas le fils ou l’objet lui-même du premier objet sélectionné à
gauche dans l’arbre (vous pouvez configurer celui-ci dans votre profil).

J’ai l’erreur suivante : SQLSTATE\[HY000\] \[2002\] Can’t connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' 
====================================================================================================================================

Cela est dû à MySQL qui s’est arrêté, ce n’est pas normal, les cas
courants sont :

-   Manque de place sur le système de fichiers (peut être vérifié en
    faisant la commande "df -h", en SSH)

-   Problème de corruption de fichier(s), ce qui arrive souvent suite à
    un arrêt non propre de Jeedom (coupure de courant)

Malheureusement, il n’y a pas beaucoup de solution si c’est le deuxième
cas, le mieux étant de récupérer une sauvegarde (disponible dans
/var/www/html/backup par défaut), de réinstaller Jeedom et
de restaurer la sauvegarde. Vous pouvez aussi regarder pourquoi MySQL ne
veut pas démarrer depuis une console SSH :

``` {.bash}
sudo su -
service mysql stop
mysqld --verbose
```

Ou consulter le log : /var/log/mysql/error.log

Les boutons Eteindre/Redémarrer ne fonctionnent pas 
===================================================

Sur une installation DIY c’est normal. En SSH, il faut faire la commande
visudo et à la fin du fichier vous devez ajouter : www-data ALL=(ALL)
NOPASSWD: ALL.

``` {.bash}
sudo service apache2 restart
```

Je ne vois pas certains plugins du Market 
=========================================

Ce genre de cas arrive si votre Jeedom n’est pas compatible avec le
plugin. En général, une mise à jour de jeedom corrige le souci.

J'ai un équipement en timeout mais je ne le vois pas sur le dashboard
=========================================

Les alerte sont classé par priorité, de la moins importante à la plus importante : timeout, batterie warning, batterie danger, alerte warning, alerte danger
