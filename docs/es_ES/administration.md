C’est sur cette page que se trouvent la plupart des paramètres de configuration.
Bien que nombreux, une majorité sont pré-configurés par défaut.

La page est accessible via  **Réglages → Système → Configuration**.

Général
=======

En esta pestaña encontrará información general sobre Jeedom :

-   **Nombre de su Jeedom ** : Te permite identificar su Jeedom,
    notamment dans le Market. Il peut être réutilisé dans les scénarios
    o para identificar una copia de seguridad.

-   **Langue** : Langue utilisée dans votre Jeedom.

-   **Système** : Type de matériel sur lequel est installé le système où
    votre Jeedom tourne.

-   **Générer les traductions** : Permet de générer les traductions,
    attention, cela peut ralentir votre système. Option surtout utile
    pour les développeurs.

-   **Date et heure** : Choix de votre fuseau horaire. Vous pouvez
    cliquer sur **Forcer la synchronisation de l’heure** pour rétablir
    une mauvaise heure affichée en haut à droite.

-   **Serveur de temps optionnel** : Indique quel serveur de temps doit
    être utilisé si vous cliquez sur **Forcer la synchronisation de l’heure**
    (à réserver aux experts).

-   **Ignorer la vérification de l’heure** : indique à Jeedom de ne pas
    vérifier si l’heure est cohérente entre lui-même et le système sur
    lequel il tourne. Peut être utile par exemple, si vous ne connectez
    pas Jeedom à Internet et qu’il n’a pas de pile RTC sur le
    matériel utilisé.

-   **Système** : Indique le type de matériel sur lequel Jeedom est installé.   

-   **Clef d’installation** : Clef matérielle de votre Jeedom sur
    le Market. Si votre Jeedom n’apparaît pas dans la liste de vos
    Jeedom sur le Market, il est conseillé de cliquer sur le bouton
    **Remise à zéro**.

-   **Dernière date connue** : Date enregistrée par Jeedom, utilisée après
    un redémarrage pour des systèmes n'ayant pas de pile RTC.

Interface
=========

Vous trouverez dans cet onglet les paramètres de personnalisation de l'affichage.

Thèmes
------

-   **Desktop clair et sombre** : Vous permet de choisir un thème clair
    et un sombre pour le Desktop.

-   **Mobile clair et sombre** : idem que précédement pour la version Mobile.

-   **Thème clair de / à** : Vous permet de définir une plage horaire durant laquelle
    le thème clair choisit précédement sera utilisé. Il faut cependant cocher l'option
    **Bascule du thème en fonction de l'heure**.

-   **Capteur de luminosité**   : Uniquement en interface mobile, nécessite d'activer
    generic extra sensor dans chrome, page chrome://flags

-   **Masquer les images de fonds** : Permet de masquer les images de fonds que l'on trouve
    dans les pages scénarios, objets, interactions, etc.

Tuiles
------

-   **Tuiles Pas horizontal** : Contraint la largeur des tuiles tous les x pixels.

-   **Tuiles Pas vertical** : Contraint la hauteur des tuiles tous les x pixels.

-   **Tuiles Marge** : Espace vertical et horizontal entre les tuiles, en pixels.

Personnalisation
----------------

Réseaux
=======

Il faut absolument configurer correctement cette partie importante de
Jeedom sinon beaucoup de plugins risquent de ne pas fonctionner. Il
est possible d’accéder à Jeedom de deux manières différentes : L'**accès
interne** (depuis le même réseau local que Jeedom) et l'**accès
externe** (depuis un autre réseau notamment depuis Internet).

> **Important**
>
> Cette partie est juste là pour expliquer à Jeedom son environnement :
> une modification du port ou de l’IP dans cet onglet ne changera pas le
> port ou l’IP de Jeedom réellement. Pour cela il faut se connecter en
> SSH et éditer le fichier /etc/network/interfaces pour l’IP et les
> fichiers etc/apache2/sites-available/default et
> etc/apache2/sites-available/default\_ssl (pour le HTTPS). Cependant, en
> cas de mauvaise manipulation de votre Jeedom, l’équipe Jeedom ne
> pourra être tenue pour responsable et pourra refuser toute demande de
> support.

-   **Accès interne** : informations pour joindre Jeedom à partir d’un
    équipement du même réseau que Jeedom (LAN)

    -   **OK/NOK** : indique si la configuration réseau interne est
        correcte

    -   **Protocole** : le protocole à utiliser, souvent HTTP

    -   **Adresse URL ou IP** : IP de Jeedom à renseigner

    -   **Port** : le port de l’interface web de Jeedom, en général 80.
        Attention changer le port ici ne change pas le port réel de
        Jeedom qui restera le même

    -   **Complément** : le fragment d’URL complémentaire (exemple
        : /jeedom) pour accéder à Jeedom.

-   **Accès externe** : informations pour joindre Jeedom de l’extérieur
    du réseau local. À ne remplir que si vous n’utilisez pas le DNS
    Jeedom

    -   **OK/NOK** : indique si la configuration réseau externe est
        correcte

    -   **Protocole** : protocole utilisé pour l’accès extérieur

    -   **Adresse URL ou IP** : IP externe, si elle est fixe. Sinon,
        donnez l’URL pointant sur l’adresse IP externe de votre réseau.

    -   **Complément** : le fragment d’URL complémentaire (exemple
        : /jeedom) pour accéder à Jeedom.

-   **Proxy pour market** : activation du proxy.

    - Cocher la case activer le proxy

    - **Adresse Proxy** : Renseigner l'adresse du proxy,

    - **Port du Proxy** : Renseigner le port du proxy,

    - **Login** : Renseigner le login du proxy,

    - **Mot de passe** : Renseigner le mot de passe.

> **Tip**
>
> Si vous êtes en HTTPS le port est le 443 (par défaut) et en HTTP le
> port est le 80 (par défaut). Pour utiliser HTTPS depuis l’extérieur,
> un plugin letsencrypt est maintenant disponible sur le market.

> **Tip**
>
> Pour savoir si vous avez besoin de définir une valeur dans le champs
> **complément**, regardez, quand vous vous connectez à Jeedom dans
> votre navigateur Internet, si vous devez ajouter /jeedom (ou autre
> chose) après l’IP.

-   **Gestion avancée** : Cette partie peut ne pas apparaitre, en
    fonction de la compatibilité avec votre matériel. Vous y trouverez
    la liste de vos interfaces réseaux. Vous pourrez indiquer à Jeedom
    de ne pas monitorer le réseau en cliquant sur **désactiver la
    gestion du réseau par Jeedom** (à cocher si Jeedom n’est connecté à
    aucun réseau). Vous pouvez aussi y préciser la plage d'ip locale sous la forme 192.168.1.* (à n'utiliser que dans des installations de type docker)

-   **Proxy market** : permet un accès distant à votre Jeedom sans avoir
    besoin d’un DNS, d’une IP fixe ou d’ouvrir les ports de votre box
    Internet

    -   **Utiliser les DNS Jeedom** : active les DNS Jeedom (attention
        cela nécessite au moins un service pack)

    -   **Statut DNS** : statut du DNS HTTP

    -   **Gestion** : permet d’arrêter et relancer le service DNS Jeedom

> **Important**
>
> Si vous n’arrivez pas à faire fonctionner le DNS Jeedom, regardez la
> configuration du pare-feu et du filtre parental de votre box Internet
> (sur livebox il faut par exemple le pare-feu en niveau moyen).
-   **Durée de vie des sessions (heure)** : durée de vie des sessions
    PHP, il est déconseillé de toucher à ce paramètre.

Logs
====

Timeline
--------

-   **Nombre maximum d’évènements** : Définit le nombre maximum d'évènements à
    afficher dans la timeline.

-   **Supprimer tous les évènements** : Permet de vider la timeline de
    tous ses évènements enregistrés.

Messages
--------

-   **Ajouter un message à chaque erreur dans les logs** : si un plugin
    ou Jeedom écrit un message d’erreur dans un log, Jeedom ajoute
    automatiquement un message dans le centre des messages (au moins
    vous êtes sûr de ne pas le manquer).

-   **Action sur message** : Permet de faire une action lors de l'ajout d'un message dans le centre des messages. Vous avez 2 tags pour ces actions :
        - #subject# : message en question
        - #plugin# : plugin qui a déclenché le message

Alertes
-------

-   **Ajouter un message à chaque Timeout** : Ajoute un message dans le
    centre de message si un équipement tombe en **timeout**.

-   **Commande sur Timeout**: Commande de type**message** à utiliser
    si un équipement est en **timeout**.

-   **Ajouter un message à chaque Batterie en Warning** : Ajoute un
    message dans le centre de messages si un équipement a son niveau de
    batterie en **warning**.

-   **Commande sur Batterie en Warning**: Commande de type**message**
    à utiliser si un équipement à son niveau de batterie en **warning**.

-   **Ajouter un message à chaque Batterie en Danger** : Ajoute un
    message dans le centre de messages si un équipement à son niveau de
    batterie en **danger**.

-   **Commande sur Batterie en Danger**: Commande de type**message** à
    utiliser si un équipement à son niveau de batterie en **danger**.

-   **Ajouter un message à chaque Warning** : Ajoute un message dans le
    centre de messages si une commande passe en alerte **warning**.

-   **Commande sur Warning**: Commande de type**message** à utiliser
    si une commande passe en alerte **warning**.

-   **Ajouter un message à chaque Danger** : Ajoute un message dans le
    centre de messages si une commande passe en alerte **danger**.

-   **Commande sur Danger**: Commande de type**message** à utiliser si
    une commande passe en alerte **danger**.

Logs
----

-   **Moteur de log** : Permet de changer le moteur de log pour, par
    exemple, les envoyer à un demon syslog(d).

-   **Format des logs** : Format de log à utiliser (Attention : ça
    n’affecte pas les logs des démons).

-   **Nombre de lignes maximum dans un fichier de log** : Définit le
    nombre maximum de lignes dans un fichier de log. Il est recommandé
    de ne pas toucher cette valeur, car une valeur trop grande pourrait
    remplir le système de fichiers et/ou rendre Jeedom incapable
    d’afficher le log.

-   **Niveau de log par défaut** : Quand vous sélectionnez "Défaut",
    pour le niveau d’un log dans Jeedom, c’est celui-ci qui sera
    alors utilisé.

En dessous vous retrouvez un tableau permettant de gérer finement le
niveau de log des éléments essentiels de Jeedom ainsi que celui des
plugins.

Commandes
=========

De nombreuses commandes peuvent être historisées. Ainsi, dans
Analyse→Historique, vous obtenez des graphiques représentant leur
utilisation. Cet onglet permet de fixer des paramètres globaux à
l’historisation des commandes.

Historique
----------

-   **Afficher les statistiques sur les widgets** : Permet d’afficher
    les statistiques sur les widgets. Il faut que le widget soit
    compatible, ce qui est le cas pour la plupart. Il faut aussi que la
    commande soit de type numérique.

-   **Période de calcul pour min, max, moyenne (en heures)** : Période
    de calcul des statistiques (24h par défaut). Il n’est pas possible
    de mettre moins d’une heure.

-   **Période de calcul pour la tendance (en heures)** : Période de
    calcul des tendances (2h par défaut). Il n’est pas possible de
    mettre moins d’une heure.

-   **Délai avant archivage (en heures)** : Indique le délai avant que
    Jeedom n’archive une donnée (24h par défaut). C’est-à-dire que les
    données historisées doivent avoir plus de 24h pour être archivées
    (pour rappel, l’archivage va soit moyenner, soit prendre le maximum
    ou le minimum de la donnée sur une période qui correspond à la
    taille des paquets).

-   **Archiver par paquet de (en heures)** : Ce paramètre donne
    justement la taille des paquets (1h par défaut). Cela signifie par
    exemple que Jeedom va prendre des périodes de 1h, moyenner et
    stocker la nouvelle valeur calculée en supprimant les
    valeurs moyennées.

-   **Seuil de calcul de tendance basse** : Cette valeur indique la
    valeur à partir de laquelle Jeedom indique que la tendance est à
    la baisse. Il doit être négatif (par défaut -0.1).

-   **Seuil de calcul de tendance haut** : Même chose pour la hausse.

-   **Période d’affichage des graphiques par défaut** : Période qui est
    utilisée par défaut lorsque vous voulez afficher l’historique
    d’une commande. Plus la période est courte, plus Jeedom sera rapide
    pour afficher le graphique demandé.

> **Note**
>
> Le premier paramètre **Afficher les statistiques sur les widgets** est
> possible mais désactivé par défaut car il rallonge sensiblement le
> temps d’affichage du dashboard. Si vous activez cette option, par
> défaut, Jeedom se fonde sur les données des dernières 24h pour
> calculer ces statistiques. La méthode de calcul de tendance est fondée
> sur le calcul des moindres carrés (voir
> [ici](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> pour le détail).

Push
----

**URL de push globale** : permet de rajouter une URL à appeler en cas de
mise à jour d’une commande. Vous pouvez utiliser les tags suivants :
**\#value\#**pour la valeur de la commande,**\#cmd\_name\#** pour le
nom de la commande, **\#cmd\_id\#** pour l’identifiant unique de la
commande, **\#humanname\#** pour le nom complet de la commande (ex :
\#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#), `#eq_name#` pour le nom de l'équipement

Résumés
=======

Permet d’ajouter des résumés d’objets. Cette information est affichée
tout en haut, à droite, dans la barre de menu Jeedom, ou à côté des
objets :

-   **Clef** : Clé du résumé, à ne surtout pas toucher.

-   **Nom** : Nom du résumé.

-   **Calcul** : Méthode de calcul, peut être de type :

    -   **Somme** : fait la somme des différentes valeurs,

    -   **Moyenne** : fait la moyenne des valeurs,

    -   **Texte** : affiche textuellement la valeur (surtout pour celles
        de type chaine de caractères).

-   **Icone** : Icône du résumé.

-   **Unité** : Unité du résumé.

-   **Méthode de comptage** : Si vous comptez une donnée binaire alors
    il faut mettre cette valeur à binaire, exemple si vous comptez le
    nombre de lampes allumées mais que vous avez juste la valeur du
    variateur (0 à 100), alors il faut mettre binaire, comme cela Jeedom
    considéra que si la valeur est supérieure à 1, alors la lampe
    est allumée.

-   **Afficher si valeur égale 0** : Cochez cette case pour afficher la
    valeur, même quand elle vaut 0.

-   **Lier à un virtuel** : Lance la création de commandes virtuelles
    ayant pour valeur celles du résumé.

-   **Supprimer le résumé** : Le dernier bouton, tout à droite, permet
    de supprimer le résumé de la ligne.

Equipements
===========

-   **Nombre d’échecs avant désactivation de l’équipement** : Nombre
    d’échecs de communication avec l’équipement avant désactivation de
    celui-ci (un message vous préviendra si cela arrive).

-   **Seuils des piles** : Permet de gérer les seuils d’alertes globaux
    sur les piles.

Rapports
========

Permet de configurer la génération et la gestion des rapports

-   **Délai d’attente après génération de la page (en ms)** : Délai
    d’attente après chargement du rapport pour faire la "photo", à
    changer si votre rapport est incomplet par exemple.

-   **Nettoyer les rapports plus anciens de (jours)** : Définit le
    nombre de jours avant de supprimer un rapport (les rapports prennent
    un peu de place donc attention à ne pas mettre trop
    de conservation).

Liens
=====

Permet de configurer les graphiques de liens. Ces liens permettent de
voir, sous forme d’un graphique, les relations entre les objets, les
équipements, les objets, etc.

-   **Profondeur pour les scénarios** : Permet de définir, lors de
    l’affichage d’un graphique de liens d’un scénario, le nombre
    d’éléments maximum à afficher (plus il y a d’éléments plus le
    graphique sera lent à générer et plus il sera difficile à lire).

-   **Profondeur pour les objets** : Idem pour les objets.

-   **Profondeur pour les équipements** : Idem pour les équipements.

-   **Profondeur pour les commandes** : Idem pour les commandes.

-   **Profondeur pour les variables** : Idem pour les variables.

-   **Paramètre de prerender** : Permet d’agir sur la disposition
    du graphique.

-   **Paramètre de render** : Idem.

Interactions
============

Cet onglet permet de fixer des paramètres globaux concernant les
interactions que vous trouverez dans Outils→Interactions.

> **Tip**
>
> Pour activer le log des interactions, il faut aller dans l’onglet
> Administration→Configuration→Logs, puis cocher **Debug** dans la liste
> du bas. Attention : les logs seront alors très verbeux !

Général
-------

Vous avez ici trois paramètres :

-   **Sensibilité** : il y a 4 niveaux de correspondance (La sensibilité
    va de 1 (correspond exactement) à 99)

    -   pour 1 mot : le niveau de correspondance pour les interactions à
        un seul mot

    -   2 mots : le niveau de correspondance pour les interactions à
        deux mots

    -   3 mots : le niveau de correspondance pour les interactions à
        trois mots

    -   + de 3 mots : le niveau de correspondance pour les interactions
        à plus de trois mots

-   **Ne pas répondre si l’interaction n’est pas comprise** : par défaut
    Jeedom répond "je n’ai pas compris" si aucune interaction
    ne correspond. Il est possible de désactiver ce fonctionnement pour
    que Jeedom ne réponde rien. Cochez la case pour désactiver
    la réponse.

-   **Regex général d’exclusion pour les interactions** : permet de
    définir une regexp qui, si elle correspond à une interaction,
    supprimera automatiquement cette phrase de la génération (réservé
    aux experts). Pour plus d’informations voir les explications dans le
    chapitre **Regexp d’exclusion** de la documentation sur
    les interactions.

Interaction automatique, contextuelle & avertissement
-----------------------------------------------------

-   Les **interactions automatiques** permettent à Jeedom de tenter de
    comprendre une demande d’interaction même si il n’y en a aucune
    de définie. Il va alors chercher un nom d’objet et/ou d’équipement
    et/ou de commande pour essayer de répondre au mieux.

-   Les **interactions contextuelles** vous permettent d’enchainer
    plusieurs demandes sans tout répéter, par exemple :

    -   *Jeedom gardant le contexte :*

        -   *Vous* : Combien fait-il dans la chambre ?

        -   *Jeedom* : Température 25.2 °C

        -   *Vous* : et dans le salon ?

        -   *Jeedom* : Température 27.2 °C

    -   *Poser deux questions en une :*

        -   *Vous* : Combien fait-il dans la chambre et dans le salon ?

        -   *Jeedom* : Température 23.6 °C, Température 27.2 °C

-   Les interactions de type **Préviens-moi** permettent de demander à
    Jeedom de vous prévenir si une commande dépasse/descend ou vaut une
    certaine valeur.

    -   *Vous* : Préviens-moi si la température du salon dépasse 25°C ?

    -   *Jeedom* : OK (*Dès que la température du salon dépassera 25°C,
        Jeedom vous le dira, une seule fois*)

> **Note**
>
> Par défaut Jeedom vous répondra par le même canal que celui que vous
> avez utilisé pour lui demander de vous prévenir. Si il n’en trouve
> pas, il utilisera alors la commande par défaut spécifiée dans cet
> onglet : **Commande de retour par défaut**.

Voici donc les différentes options disponibles :

-   **Activer les interactions automatiques** : Cochez pour activer les
    interactions automatiques.

-   **Activer les réponses contextuelles** : Cochez pour activer les
    interactions contextuelles.

-   **Réponse contextuelle prioritaire si la phrase commence par** : Si
    la phrase commence par le mot que vous renseignez ici, Jeedom va
    alors prioritiser une réponse contextuelle (vous pouvez mettre
    plusieurs mots en les séparant par des **;** ).

-   **Découper une interaction en 2 si elle contient** : Même chose pour
    le découpage d’une interaction contenant plusieurs questions. Vous
    donnez ici les mots qui séparent les différentes questions.

-   **Activer les interactions "Préviens-moi"** : Cochez pour activer
    les interactions de type **Préviens-moi**.

-   **Réponse de type "Préviens-moi" si la phrase commence par** : Si la
    phrase commence par ce/ces mot(s) alors Jeedom cherchera à faire une
    interaction de type **Préviens-moi** (vous pouvez mettre plusieurs
    mots en les séparant par des **;** ).

-   **Commande de retour par défaut** : Commande de retour par défaut
    pour une interaction de type **Préviens-moi** (utilisée, notamment,
    si vous avez programmé l’alerte par l’interface mobile)

-   **Synonyme pour les objets** : Liste des synonymes pour les objets
    (ex : rdc|rez de chaussé|sous sol|bas;sdb|salle de bain).

-   **Synonyme pour les équipements** : Liste des synonymes pour
    les équipements.

-   **Synonyme pour les commandes** : Liste des synonymes pour
    les commandes.

-   **Synonyme pour les résumé** : Liste des synonymes pour les résumés.

-   **Synonyme commande slider maximum** : Synonyme pour mettre une
    commande de type slider au maximum (ex ouvre pour ouvre le volet de
    la chambre ⇒ volet chambre à 100%).

-   **Synonyme commande slider minimum** : Synonyme pour mettre une
    commande de type slider au minimu (ex ferme pour fermer le volet de
    la chambre ⇒ volet chambre à 0%).

Sécurité
========

LDAP
----

-   **Activer l’authentification LDAP** : active l’authentification à
    travers un AD (LDAP)

-   **Hôte** : serveur hébergeant l’AD

-   **Domaine** : domaine de votre AD

-   **Base DN** : base DN de votre AD

-   **Nom d’utilisateur** : nom d’utilisateur pour que Jeedom se
    connecte à l’AD

-   **Mot de passe** : mot de passe pour que Jeedom se connecte à l’AD

-   **Champs recherche utilisateur** : champs de recherche du
    login utilisateur. En général uid pour LDAP, SamAccountName pour
    Windows AD

-   **Filtre (optionnel)** : filtre sur l’AD (pour la gestion des
    groupes par exemple)

-   **Autoriser REMOTE\_USER** : Active le REMOTE\_USER (utilisé en SSO
    par exemple)

Connexion
---------

-   **Nombre d’échecs tolérés** : définit le nombre de tentatives
    successives autorisées avant de bannir l’IP

-   **Temps maximum entre les échecs (en secondes)** : temps maximum
    pour que 2 tentatives soient considérées comme successives

-   **Durée du bannissement (en secondes), -1 pour infini** : temps de
    bannissement de l’IP

-   **IP "blanche"** : liste des IP qui ne peuvent jamais être bannies

-   **Supprimer les IPs bannies** : Permet de vider la liste des IP
    actuellement bannies

La liste des IP bannies se trouve au bas de cette page. Vous y trouverez
l’IP, la date de bannissement et la date de fin de bannissement
programmée.

Mise à jour et fichiers
=======================

Mise à jour de Jeedom
---------------------

-   **Source de mise à jour** : Choisissez la source de mise à jour du
    core de Jeedom.

-   **Version du core** : Version du core à récupérer.

-   **Vérifier automatiquement s’il y a des mises à jour** : Indique si
    il faut chercher automatiquement si il y a de nouvelles mises à jour
    (attention pour éviter de surcharger le market, l’heure de
    vérification peut changer).

Les dépôts
----------

Les dépôts sont des espaces de stockage (et de service) pour pouvoir
déplacer des sauvegardes, récupérer des plugins, récupérer le core de
Jeedom, etc.

### Fichier

Dépôt servant à activer l’envoi de plugins par des fichiers.

### Github

Dépôt servant à relier Jeedom à Github.

-   **Token** : Token pour l’accès au dépôt privé.

-   **Utilisateur ou organisation du dépôt pour le core Jeedom** : Nom
    de l’utilisateur ou de l’organisation sur github pour le core.

-   **Nom du dépôt pour le core Jeedom** : Nom du dépôt pour le core.

-   **Branche pour le core Jeedom** : Branche du dépôt pour le core.

### Market

Dépôt servant à relier Jeedom au market, il est vivement conseillé
d’utiliser ce dépôt. Attention : toute demande de support pourra être
refusée si vous utilisez un autre dépôt que celui-ci.

-   **Adresse** : Adresse du Market.(https://www.jeedom.com/market)

-   **Nom d’utilisateur** : Votre nom d’utilisateur sur le Market.

-   **Mot de passe** : Votre mot de passe du Market.

-   **[Backup cloud] Nom** : Nom de votre backup cloud (attention doit etre unique pour chaque Jeedom sous risque qu'il s'écrase entre eux)

-   **[Backup cloud] Mot de passe** : Mot de passe du backup cloud. IMPORTANT vous ne devez surtout pas le perdre, il n'y a aucun moyen de le récuperer. Sans celui-ci vous ne pourrez plus restaurer votre Jeedom.

-   **[Backup cloud] Fréquence backup full** : Fréquence du backup cloud full. Un backup full est plus long qu'un incrémental (qui n'envoie que les différences). Il est recommandé d'en faire 1 par mois

### Samba

Dépôt permettant d’envoyer automatiquement une sauvegarde de Jeedom sur
un partage Samba (ex : NAS Synology).

-   **\[Backup\] IP** : IP du serveur Samba.

-   **\[Backup\] Utilisateur** : Nom d’utilisateur pour la connexion
    (les connexions anonymes ne sont pas possibles). Il faut forcément
    que l’utilisateur ait les droits en lecture ET en écriture sur le
    répertoire de destination.

-   **\[Backup\] Mot de passe** : Mot de passe de l’utilisateur.

-   **\[Backup\] Partage** : Chemin du partage (attention à bien
    s’arrêter au niveau du partage).

-   **\[Backup\] Chemin** : Chemin dans le partage (à mettre en
    relatif), celui-ci doit exister.

> **Note**
>
> Si le chemin d’accès à votre dossier de sauvegarde samba est :
> \\\\192.168.0.1\\Sauvegardes\\Domotique\\Jeedom Alors IP = 192.168.0.1
> , Partage = //192.168.0.1/Sauvegardes , Chemin = Domotique/Jeedom

> **Note**
>
> Lors de la validation du partage Samba, tel que décrit précédemment,
> une nouvelle forme de sauvegarde apparait dans la partie
> Administration→Sauvegardes de Jeedom. En l’activant, Jeedom procèdera
> à son envoi automatique lors de la prochaine sauvegarde. Un test est
> possible en effectuant une sauvegarde manuelle.

> **Important**
>
> Il vous faudra peut-être installer le package smbclient pour que le
> dépôt fonctionne.

> **Important**
>
> Le protocole Samba comporte plusieurs versions, la v1 est compromise niveau
> sécurité et sur certains NAS vous pouvez obliger le client à utiliser la v2
> ou la v3 pour se connecter. Donc si vous avez une erreur protocol negotiation
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE il y a de forte chance que coté NAS
> la restriction soit en place. Vous devez alors modifier sur l'OS de votre Jeedom
> le fichier /etc/samba/smb.conf et y ajouter ces deux lignes :
> client max protocol = SMB3
> client min protocol = SMB2
> Le smbclient coté Jeedom utilisera alors v2 où v3 et en mettant SMB3 aux 2 uniquement
> SMB3. A vous donc d'adapter en fonction des restrictions côté NAS ou autre serveur Samba

> **Important**
>
> Jeedom doit être le seul à écrire dans ce dossier et il doit être vide
> par défaut (c’est-à-dire qu’avant la configuration et l’envoi de la
> première sauvegarde, le dossier ne doit contenir aucun fichier ou
> dossier).

### URL

-   **URL core Jeedom**

-   **URL version core Jeedom**

Cache
=====

Permet de surveiller et d’agir sur le cache de Jeedom :

-   **Statistiques** : Nombre d’objets actuellement en cache

-   **Nettoyer le cache** : Force la suppression des objets qui ne sont
    plus utiles. Jeedom le fait automatiquement toutes les nuits.

-   **Vider toutes les données en cache** : Vide complètement le cache.
    Attention cela peut faire perdre des données !

-   **Vider le cache des widgets** : Vide le cache dédié aux widgets

-   **Désactiver le cache des widgets** : Cocher la case pour désactiver
    le caches des widgets

-   **Temps de pause pour le long polling** : Fréquence à laquelle
    Jeedom vérifie si il y a des événements en attente pour les clients
    (interface web, application mobile…​). Plus ce temps est court, plus
    l’interface se mettra à jour rapidement, en contre partie cela
    utilise plus de ressources et peut donc ralentir Jeedom.

API
===

Vous trouvez ici la liste des différentes clés API disponibles dans
votre Jeedom. De base, le core a deux clés API :

-   une générale : autant que possible, il faut éviter de l’utiliser,

-   et une autre pour les professionnels : utilisée pour la gestion
    de parc. Elle peut être vide.

-   Puis, vous trouverez une clé API par plugin en ayant besoin.

Pour chaque clé API de plugin, ainsi que pour les APIs HTTP, JsonRPC et
TTS, vous pouvez définir leur portée :

-   **Désactivée** : la clé API ne peut être utilisée,

-   **IP blanche** : seule une liste d’IPs est autorisée (voir
    Administration→Configuration→Réseaux),

-   **Localhost** : seules les requêtes venant du système sur lequel est
    installé Jeedom sont autorisées,

-   **Activé** : aucune restriction, n’importe quel système ayant accès
    à votre Jeedom pourra accéder à cette API.

&gt;\_OS/DB
===========

Deux parties réservées aux experts sont présentes dans cet onglet.

> **Important**
>
> ATTENTION : Si vous modifiez Jeedom avec l’une de ces deux solutions,
> le support peut refuser de vous aider.

-   **&gt;\_Système** : Permet d’accéder à une interface
    d’administration système. C’est une sorte de console shell dans
    laquelle vous pouvez lancer les commandes les plus utiles, notamment
    pour obtenir des informations sur le système.

-   **Editeur de fichiers** : Permet d'accéder aux différents fichiers du système
    d'exploitation et de les éditer ou supprimer ou d'en créer.

-   **Base de données** : Administration / Lancer : Permet d’accéder à la base de données
    de Jeedom. Vous pouvez alors lancer des commandes dans le champs
    du haut.
    Vérification / Lancer : Permet de lancer une vérification sur la base de données
    de Jeedom et de corriger si nécessaire les erreurs

    Deux paramètres s’affichent, en dessous, pour information :

    -   **Utilisateur** : Nom de l’utilisateur utilisé par Jeedom dans
        la base de données,

    -   **Mot de passe** : mot de passe d’accès à la base de données
        utilisé par Jeedom.
