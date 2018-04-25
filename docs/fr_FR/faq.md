Jeedom nécessite-t-il un abonnement ? 
=====================================

Non, Jeedom est pleinement utilisable sans aucune nécessité de quelque
abonnement que ce soit. Cependant, il existe des services proposés pour
les sauvegardes ou les appel/SMS, mais qui restent réellement
optionnels.

Jeedom utilise-t-il des serveurs extérieurs pour fonctionner ? 
==============================================================

Non, Jeedom n’utilise pas d’infrastructure type "Cloud". Tout se fait en
local et vous n’avez pas besoin de nos serveurs pour que votre
installation fonctionne. Seuls les services comme le Market, la
sauvegarde en ligne ou le DNS Jeedom nécessitent l’utilisation de nos
serveurs.

Peut-on réordonner les commandes d’un équipement ? 
==================================================

Oui c’est possible, il suffit de glisser/déposer les commandes de votre
objet sur sa configuration.

Peut-on éditer le style des widgets ? 
=====================================

Oui c’est possible, soit en passant par le plugin widget, ou en
utilisant la page Général → Affichage

Peut-on mettre plusieurs fois le même équipement sur un design ? 
================================================================

Non ce n’est pas possible, mais vous pouvez dupliquer celui-ci grâce au
plugin virtuel.

Comment changer une donnée erronée de l’historique ? 
====================================================

Il suffit, sur une courbe historique de la commande, de cliquer sur le
point en question. Si vous laissez le champs vierge, alors la valeur
sera supprimée.

Combien de temps dure une sauvegarde ? 
======================================

Il n’y a pas de durée standard, cela dépend du système et du volume de
données à sauvegarder, mais il peut prendre plus de 5 minutes, c’est
normal.

Y a-t-il une application mobile dédiée ? 
========================================

Jeedom possède une version mobile adaptée à l’utilisation sur mobile et
tablette. Il existe aussi une application native pour Android et iOS.

Quels sont les identifiants pour me connecter la première fois ? 
================================================================

Lors de votre première connexion à Jeedom (et même après si vous ne les
avez pas modifiés), le nom d’utilisateur et le mot de passe par défaut
sont admin/admin. A la première connexion, il vous est fortement
recommandé de modifier ces identifiants pour plus de sécurité.

Peut-on mettre Jeedom en https ? 
================================

Oui : \* Soit vous avez un pack power ou plus, dans ce cas il vous
suffit d’utiliser le DNS Jeedom. \* Soit avez un DNS et vous savez
mettre en place un certificat valide, dans ce cas c’est une installation
standard d’un certificat.

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

La webapp nécessite un smartphone supportant le HTML5 et le CSS3. Elle
n’est donc malheureusement pas compatible Symbian.

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

J’ai des {{…​}} partout 
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

Jeedom ne trouve pas ou n'arrive pas a un pinguer la gateway. En général ca arrive si la box adsl redémarre (en particulier les livebox) et que Jeeodm n'a pas redemarré ou a redemarré plus vite que la box. Par sécurité il vous dit donc qu'il a trouvé un soucis et relance le processus de connection réseaux. Vous pouvez désactiver ce mécanisme en allant dans la configuration de Jeedom et en désactivant la gestion du réqseaux par Jeedom.

J'ai le message "Echec durant la sauvegarde de la base de données. Vérifiez que mysqldump est présent."
=========================================
Ca veut dire que Jeedom n'arrive pas a backuper la base de donnée ce qu'i peut laisser penser a un soucis de corrumption de base de données et de filesystem. Il n'y a malheureusement pas de commande miracle pour corriger. Le mieux est de lancer un backup et d'analyser la log de celui-ci. Dans les cas connu de soucis nous avons

- une table de la base corrompu => la c'est mal partie il faut voir pour essayer de réparer et si ca marche pas repartir du dernier bon backup (si vous etês sur garde SD c'est le bon moment pour la changer)
- pas assez de place sur le filesystem => regarder la page santé celle-ci peut vous l'indiquer
