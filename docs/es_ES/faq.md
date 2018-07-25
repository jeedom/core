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

Oui : Soit vous avez un pack power ou plus, dans ce cas il vous
suffit d’utiliser le [DNS Jeedom](https://jeedom.github.io/documentation/howto/fr_FR/mise_en_place_dns_jeedom). Soit avec un DNS et vous savez mettre en place un certificat valide, dans ce cas c’est une installation standard d’un certificat.

Comment se connecter en SSH ?
=============================

Voila une [documentation](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), partie "Sous Windows : Putty". Le "hostname" étant l'ip de votre Jeedom, les identifiants étant :

- Username : "root", password : "Mjeedom96"
- Username : "jeedom", password : "Mjeedom96"
- Ou ce que vous avez mis à l'installation si vous êtes en DIY

A noter que lorsque vous ecrirez le mot de passe vous ne verrez rien s'ecrire à l'écran c'est normal.

Comment remettre à plat les droits ? 
====================================

En SSH faites :

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

Où se trouvent les sauvegardes de Jeedom ? 
==========================================

Elles sont dans le dossier /var/www/html/backup

Comment mettre à jour Jeedom en SSH ? 
=====================================

En SSH faites :

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

La Webapp est-elle compatible Symbian ? 
=======================================

La webapp nécessite un smartphone supportant le HTML5 et le CSS3. Elle n’est donc malheureusement pas compatible Symbian.

Sur quelles plateformes Jeedom peut-il fonctionner ? 
====================================================

Pour que Jeedom fonctionne, il faut une plateforme linux avec les droits
root ou un système type docker. Il ne fonctionne donc pas sur une
plateforme android pure.

Je ne peux mettre à jour certain plugin "Echec lors du téléchargement du fichier. Veuillez réessayer plus tard (taille inférieure à 100 octets)..." ? 
====================================================

Cela peut etre du à plusieurs chose, il faut : 

- Vérifier que votre Jeedom est toujours connecté au market (dans la page d'administration de jeedom, partie mise à jour vous avez un bouton de test)
- Vérifier que le compte market à bien acheté le plugin en question
- Vérifier que vous avez bien de la place sur Jeedom (la page santé vous l'indiquera)
- Vérifier que votre version de Jeedom est bien compatible avec le plugin
- Vérifiez que votre Jeedom est toujours correctement connecté au market (Dans la configuration de Jeedom, onglet mise à jour)

J’ai une page blanche 
=====================

Il faut se connecter en SSH à Jeedom et lancer le script
d’auto-diagnostic :

``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```

S’il y a un souci, le script essaiera de le corriger. S’il n’y arrive
pas, il vous l’indiquera.

Vous pouvez aussi regarder le log /var/www/html/log/http.error. Très
souvent, celui-ci indique le souci.

J’ai un problème d’identifiant BDD 
==================================

Il faut réinitialiser ceux-ci :

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

J’ai des \{\{…​\}\} partout 
=======================

La cause la plus fréquente est l’utilisation d’un plugin en version beta
et Jeedom en stable, ou l’inverse. Pour avoir le détail de l’erreur, il
faut regarder le log http.error (dans /var/www/html/log).

Lors d’une commande j’ai une roue qui tourne sans s’arrêter 
===========================================================

Encore une fois cela est souvent dû à un plugin en beta alors que Jeedom
est en stable. Pour voir l’erreur, il faut faire F12 puis console.

Je n’ai plus accès à Jeedom, ni par l’interface web ni en console par SSH 
=========================================================================

Cette erreur n’est pas due à Jeedom, mais à un problème avec le système.
Si celui-ci persiste suite à une réinstallation, il est conseillé de
voir avec le SAV pour un souci hardware. Voici la [documentation](https://jeedom.github.io/documentation/howto/fr_FR/recovery_mode_jeedom_smart) pour la Smart

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

-   Soucis mémoire, le systeme manque de mémoire et tue le process le plus consommateur (souvent la base de données). Cela peut se voir dans l'administration de l'OS puis dmesg, vous devez voir un kill par "oom". Si c'est le cas il faut réduire la consommation de jeedom en désactivant des plugins.

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

Mon Jeedom affiche en permanance "En cours de démarrage" même après 1h ? 
=====================================

Si vous etes en DIY et sous Debian 9 ou plus, vérifiez qu'il n'y a pas eu une mise à jour d'Apache et donc le retour du privateTmp (visible en faisant `ls /tmp` et voir si il y a un dossier private\*Apache). Si c'est le cas il faut faire :

``` 
mkdir /etc/systemd/system/apache2.service.d
echo "[Service]" > /etc/systemd/system/apache2.service.d/privatetmp.conf
echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf
``` 

J'ai un soucis d'heure sur mes historiques
=========================================

Essayez de vider le cache de chrome, l'affichage des historique est calculé par rapport à l'heure du navigateur.

J'ai l'erreur "Soucis réseaux detecté, redemarrage du réseaux"
=========================================

Jeedom ne trouve pas ou n'arrive pas a un pinguer la gateway. En général ca arrive si la box adsl redémarre (en particulier les livebox) et que Jeeodm n'a pas redemarré ou a redemarré plus vite que la box. Par sécurité il vous dit donc qu'il a trouvé un soucis et relance le processus de connection réseaux. Vous pouvez désactiver ce mécanisme en allant dans la configuration de Jeedom et en désactivant la gestion du réseaux par Jeedom.

J'ai le message "Echec durant la sauvegarde de la base de données. Vérifiez que mysqldump est présent."
=========================================
Ca veut dire que Jeedom n'arrive pas a backuper la base de donnée ce qu'i peut laisser penser a un soucis de corrumption de base de données et de filesystem. Il n'y a malheureusement pas de commande miracle pour corriger. Le mieux est de lancer un backup et d'analyser la log de celui-ci. Dans les cas connu de soucis nous avons

- une table de la base corrompu => la c'est mal partie il faut voir pour essayer de réparer et si ca marche pas repartir du dernier bon backup (si vous etês sur garde SD c'est le bon moment pour la changer)
- pas assez de place sur le filesystem => regarder la page santé celle-ci peut vous l'indiquer


Je n'arrive plus a me connecter a mon Jeedom
=========================================
Depuis Jeedom 3.2 il n'est plus possible de se connecter avec admin/admin à distance pour des raison evidente de sécurité. Les identifiants admin/admin ne marche plus que en local. Attention si vous passer par le DNS même en local vous êtes forcement identifié comme à distance. Autre point par defaut seul les ip sur 192.168.*.* ou 127.0.0.1 sont reconnu comme local. Cela se configure dans l'administration de Jeedom partie sécurité puis IP "blanche". Si malgrès tout ca vous n'arrivez toujours pas à vous connecter il faut utiliser la procedure de remise à zéro de mot de passe (voir dans les tuto/how to)

J'ai des erreurs de type "Class 'eqLogic' not found", des fichiers semblent etre manquant ou j'ai une page blanche
=========================================
C'est une erreur assez grave le plus simple est de faire 

``` 
mkdir -p /root/tmp/
cd /root/tmp
wget https://github.com/jeedom/core/archive/master.zip
unzip master.zip
cp -R /root/tmp/core-master/* /var/www/html
rm -rf /root/tmp/core-master
```