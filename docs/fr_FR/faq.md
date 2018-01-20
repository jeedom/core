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
