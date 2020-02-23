C'est sur cette page que se trouvent la plupart des paramètres de configuration.
Bien que nombreux, une majorité sont pré-configurés par défaut.

La page est accessible via  **Réglages → Système → Configuration**.

General
=======

Dans cet onglet on retrouve des informations générales sur Jeedom :

-   **Name de deine Freiheit** : Permet d'identifier deine Freiheit,
    notamment dans der Markt. Il peut être réutilisé dans les scénarios
    ou permettre d'identifier une sauvegarde.

-   **Sprache** : Sprache utilisée dans deine Freiheit.

-   **Système** : Type de matériel sur lequel est installé le système où
    deine Freiheit tourne.

-   **Générer les traductions** : Permet de générer les traductions,
    attention, cela peut ralentir votre système. Option surtout utile
    pour les développeurs.

-   **Date et heure** : Choix de votre fuseau horaire. Sie pouvez
    cliquer sur **Forcer la synchronisation de l'heure** pour rétablir
    une mauvaise heure affichée en haut à droite.

-   **Serveur de temps optionnel** : Indique quel serveur de temps doit
    être utilisé si vous cliquez sur **Forcer la synchronisation de l'heure**
    (à réserver aux experts).

-   **Ignorer la vérification de l'heure** : indique à Jeedom de ne pas
    vérifier si l'heure est cohérente entre lui-même et le système sur
    lequel il tourne. Peut être utile par exemple, si vous ne connectez
    pas Jeedom à Internet et qu'il n'a pas de pile RTC sur le
    matériel utilisé.

-   **Système** : Indique le type de matériel sur lequel Jeedom est installé.   

-   **Installationsschlüssel** : Clef matérielle de deine Freiheit sur
    der Markt. Si deine Freiheit n'apparaît pas dans la liste de vos
    Jeedom sur der Markt, il est conseillé de cliquer sur le bouton
    **Remise à zéro**.

-   **Dernière date connue** : Date enregistrée par Jeedom, utilisée après
    un redémarrage pour des systèmes n'ayant pas de pile RTC.

Schnittstelle
=========

Sie trouverez dans cet onglet les paramètres de personnalisation de l'affichage.

Themen
------

-   **Desktop clair et sombre** : Sie lass uns choisir un thème clair
    et un sombre pour le Desktop.

-   **Mobile clair et sombre** : idem que précédement pour la version Mobile.

-   **Thème clair de / à** : Sie lass uns définir une plage horaire durant laquelle
    le thème clair choisit précédement sera utilisé. Il faut cependant cocher l'option
    **Bascule du thème en fonction de l'heure**.

-   **Capteur de luminosité**   : Uniquement en interface mobile, nécessite d'activer
    generic extra sensor dans chrome, page chrome://flags

-   **Masquer les images de fonds** : Permet de masquer les images de fonds que l'on trouve
    dans les pages scénarios, Objekte, interactions, etc.

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
Jeedom sinon beaucoup de Plugins risquent de ne pas fonctionner. Il
est possible d'accéder à Jeedom de deux manières différentes : L'**accès
interne** (depuis le même réseau local que Jeedom) et l'**accès
externe** (depuis un autre réseau notamment depuis Internet).

> **wichtig**
>
> Cette partie est juste là pour expliquer à Jeedom son environnement :
> une modification du port ou de l'IP dans cet onglet ne changera pas le
> port ou l'IP von Jeedom réellement. Pour cela il faut se connecter en
> SSH et éditer le fichier /etc/network/interfaces pour l'IP et les
> fichiers etc/apache2/sites-available/default et
> etc/apache2/sites-available/default\_ssl (pour le HTTPS). Cependant, en
> cas de mauvaise manipulation de deine Freiheit, l'équipe Jeedom ne
> pourra être tenue pour responsable et pourra refuser toute demande de
> support.

-   **Accès interne** : informations pour joindre Jeedom à partir d'un
    équipement du même réseau que Jeedom (LAN)

    -   **OK/NOK** : indique si la configuration réseau interne est
        correcte

    -   **Protocole** : le protocole à utiliser, souvent HTTP

    -   **Adresse URLs ou IP** : IP von Jeedom à renseigner

    -   **Port** : le port de l'interface web von Jeedom, en général 80.
        Aufmerksamkeit changer le port ici ne change pas le port réel de
        Jeedom qui restera le même

    -   **Complément** : le fragment d'URLs complémentaire (zBemple
        : /jeedom) pour accéder à Jeedom.

-   **Accès externe** : informations pour joindre Jeedom de l'extérieur
    du réseau local. À ne remplir que si vous n'utilisez pas le DNS
    Jeedom

    -   **OK/NOK** : indique si la configuration réseau externe est
        correcte

    -   **Protocole** : protocole utilisé pour l'accès extérieur

    -   **Adresse URLs ou IP** : IP externe, si elle est fixe. Sinon,
        donnez l'URLs pointant sur l'adresse IP externe de votre réseau.

    -   **Complément** : le fragment d'URLs complémentaire (zBemple
        : /jeedom) pour accéder à Jeedom.

-   **Proxy pour market** : activation du proxy.

    - Cocher la case activer le proxy

    - **Adresse Proxy** : Renseigner l'adresse du proxy,

    - **Port du Proxy** : Renseigner le port du proxy,

    - **Login** : Renseigner le login du proxy,

    - **Passwort** : Renseigner le mot de passe.

> **Spitze**
>
> Si vous êtes en HTTPS le port est le 443 (par défaut) et en HTTP le
> port est le 80 (par défaut). Pour utiliser HTTPS depuis l'extérieur,
> un Plugin letsencrypt est maintenant disponible sur le market.

> **Spitze**
>
> Pour savoir si vous avez besoin de définir une valeur dans le champs
> **complément**, regardez, quand vous vous connectez à Jeedom dans
> votre navigateur Internet, si vous devez ajouter /jeedom (ou autre
> chose) après l'IP.

-   **Management avancée** : Cette partie peut ne pas apparaitre, en
    fonction de la compatibilité avec votre matériel. Sie y trouverez
    la liste de vos interfaces réseaux. Sie pourrez indiquer à Jeedom
    de ne pas monitorer le réseau en cliquant sur **désactiver la
    gestion du réseau par Jeedom** (à cocher si Jeedom n'est connecté à
    aucun réseau). Sie pouvez aussi y préciser la plage d'ip locale sous la forme 192.168.1.* (à n'utiliser que dans des installations de type docker)

-   **Proxy market** : permet un accès distant à deine Freiheit sans avoir
    besoin d'un DNS, d'une IP fixe ou d'ouvrir les ports de votre box
    Internet

    -   **Utiliser les DNS Jeedom** : active les DNS Jeedom (attention
        cela nécessite au moins un service pack)

    -   **Statut DNS** : statut du DNS HTTP

    -   **Management** : permet d'arrêter et relancer le service DNS Jeedom

> **wichtig**
>
> Si vous n'arrivez pas à faire fonctionner le DNS Jeedom, regardez la
> configuration du pare-feu et du filtre parental de votre box Internet
> (sur livebox il faut par exemple le pare-feu en niveau moyen).
-   **Durée de vie des sessions (heure)** : durée de vie des sessions
    PHP, il est déconseillé de toucher à ce paramètre.

logs
====

Timeline
--------

-   **Anzahl maximum d'évènements** : Définit le nombre maximum d'évènements à
    afficher dans la timeline.

-   **Supprimer tous les évènements** : Permet de vider la timeline de
    tous ses évènements enregistrés.

Messages
--------

-   **Ajouter un Nachricht à chaque erreur dans les logs** : si un Plugin
    ou Jeedom écrit un Nachricht d'erreur dans un log, Jeedom ajoute
    automatiquement un Nachricht dans le centre des Nachrichts (au moins
    vous êtes sûr de ne pas le manquer).

-   **Action sur Nachricht** : Permet de faire une action lors de l'ajout d'un Nachricht dans le centre des Nachrichts. Sie avez 2 tags pour ces actions :
        - #subject# : Nachricht en question
        - #Plugin# : Plugin qui a déclenché le Nachricht

Alertes
-------

-   **Ajouter un Nachricht à chaque Timeout** : Ajoute un Nachricht dans le
    centre de Nachricht si un équipement tombe en **timeout**.

-   **Commande sur Timeout** : Commande de type **Nachricht** à utiliser
    si un équipement est en **timeout**.

-   **Ajouter un Nachricht à chaque Batterie en Warning** : Ajoute un
    Nachricht dans le centre de Nachrichts si un équipement a son niveau de
    batterie en **Warnung**.

-   **Commande sur Batterie en Warning** : Commande de type **Nachricht**
    à utiliser si un équipement à son niveau de batterie en **Warnung**.

-   **Ajouter un Nachricht à chaque Batterie en Danger** : Ajoute un
    Nachricht dans le centre de Nachrichts si un équipement à son niveau de
    batterie en **Gefahr**.

-   **Commande sur Batterie en Danger** : Commande de type **Nachricht** à
    utiliser si un équipement à son niveau de batterie en **Gefahr**.

-   **Ajouter un Nachricht à chaque Warning** : Ajoute un Nachricht dans le
    centre de Nachrichts si une commande passe en alerte **Warnung**.

-   **Commande sur Warning** : Commande de type **Nachricht** à utiliser
    si une commande passe en alerte **Warnung**.

-   **Ajouter un Nachricht à chaque Danger** : Ajoute un Nachricht dans le
    centre de Nachrichts si une commande passe en alerte **Gefahr**.

-   **Commande sur Danger** : Commande de type **Nachricht** à utiliser si
    une commande passe en alerte **Gefahr**.

logs
----

-   **Moteur de log** : Permet de changer le moteur de log pour, par
    exemple, les envoyer à un demon syslog(d).

-   **Format des logs** : Format de log à utiliser (Aufmerksamkeit : ça
    n'affecte pas les logs des démons).

-   **Anzahl de lignes maximum dans un fichier de log** : Définit le
    nombre maximum de lignes dans un fichier de log. Il est recommandé
    de ne pas toucher cette valeur, car une valeur trop grande pourrait
    remplir le système de fichiers et/ou rendre Jeedom incapable
    d'afficher le log.

-   **Niveau de log par défaut** : Quand vous sélectionnez "Défaut",
    pour le niveau d'un log dans Jeedom, c'est celui-ci qui sera
    alors utilisé.

En dessous vous retrouvez un tableau permettant de gérer finement le
niveau de log des éléments essentiels von Jeedom ainsi que celui des
Plugins.

Befehle
=========

De nombreuses commandes peuvent être historisées. Ainsi, dans
Analyse→historisch, vous obtenez des graphiques représentant leur
utilisation. Cet onglet lass uns fixer des paramètres globaux à
l'historisation des commandes.

historisch
----------

-   **Afficher les statistiques sur les widgets** : Permet d'afficher
    les statistiques sur les widgets. Il faut que le widget soit
    compatible, ce qui est le cas pour la plupart. Il faut aussi que la
    commande soit de type numérique.

-   **Zeit de calcul pour min, max, moyenne (en heures)** : Zeit
    de calcul des statistiques (24h par défaut). Il n'est pas possible
    de mettre moins d'une heure.

-   **Zeit de calcul pour la tendance (en heures)** : Zeit de
    calcul des tendances (2h par défaut). Il n'est pas possible de
    mettre moins d'une heure.

-   **Délai avant archivage (en heures)** : Indique le délai avant que
    Jeedom n'archive une donnée (24h par défaut). C'est-à-dire que les
    données historisées doivent avoir plus de 24h pour être archivées
    (pour rappel, l'archivage va soit moyenner, soit prendre le maximum
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

-   **Zeit d'affichage des graphiques par défaut** : Zeit qui est
    utilisée par défaut lorsque vous voulez afficher l'historique
    d'une commande. Plus la période est courte, plus Jeedom sera rapide
    pour afficher le graphique demandé.

> **Notiz**
>
> Le premier paramètre **Afficher les statistiques sur les widgets** est
> possible mais désactivé par défaut car il rallonge sensiblement le
> temps d'affichage du dashboard. Si vous activez cette option, par
> défaut, Jeedom se fonde sur les données des dernières 24h pour
> calculer ces statistiques. La méthode de calcul de tendance est fondée
> sur le calcul des moindres carrés (voir
> [ici](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> pour le détail).

Push
----

**URLs de push globale** : lass uns rajouter une URLs à appeler en cas de
mise à jour d'une commande. Sie pouvez utiliser les tags suivants :
**\#value\#** pour la valeur de la commande, **\#cmd\_name\#** pour le
nom de la commande, **\#cmd\_id\#** pour l'identifiant unique de la
commande, **\#humanname\#** pour le nom complet de la commande (zB :
\#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#), `#eq_name#` pour le nom de l'équipement

Résumés
=======

Permet d'ajouter des résumés d'Objekte. Cette information est affichée
tout en haut, à droite, dans la barre de menu Jeedom, ou à côté des
Objekte :

-   **Clef** : Clé du résumé, à ne surtout pas toucher.

-   **Name** : Name du résumé.

-   **Calcul** : Méthode de calcul, peut être de type :

    -   **Somme** : fait la somme des différentes valeurs,

    -   **Moyenne** : fait la moyenne des valeurs,

    -   **Text** : affiche textuellement la valeur (surtout pour celles
        de type chaine de caractères).

-   **Symbol** : Icône du résumé.

-   **Einheit** : Einheit du résumé.

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

Einrichtungen
===========

-   **Anzahl d'échecs avant désactivation de l'équipement** : Anzahl
    d'échecs de communication avec l'équipement avant désactivation de
    celui-ci (un Nachricht vous préviendra si cela arrive).

-   **Seuils des piles** : Permet de gérer les seuils d'alertes globaux
    sur les piles.

Rapports
========

Permet de configurer la génération et la gestion des rapports

-   **Délai d'attente après génération de la page (en ms)** : Délai
    d'attente après chargement du rapport pour faire la "photo", à
    changer si votre rapport est incomplet par exemple.

-   **Nettoyer les rapports plus anciens de (jours)** : Définit le
    nombre de jours avant de supprimer un rapport (les rapports prennent
    un peu de place donc attention à ne pas mettre trop
    de conservation).

Verbindungen
=====

Permet de configurer les graphiques de liens. Ces liens permettent de
voir, sous forme d'un graphique, les relations entre les Objekte, les
équipements, les Objekte, etc.

-   **Profondeur pour les scénarios** : Permet de définir, lors de
    l'affichage d'un graphique de liens d'un scénario, le nombre
    d'éléments maximum à afficher (plus il y a d'éléments plus le
    graphique sera lent à générer et plus il sera difficile à lire).

-   **Profondeur pour les Objekte** : Idem pour les Objekte.

-   **Profondeur pour Ausrüstung** : Idem pour Ausrüstung.

-   **Profondeur pour Bestellungen** : Gleiches gilt für Bestellungen.

-   **Profondeur pour les variables** : Idem pour les variables.

-   **Paramètre de prerender** : Permet d'agir sur la disposition
    du graphique.

-   **Paramètre de render** : Idem.

Wechselwirkungen
============

Cet onglet lass uns fixer des paramètres globaux concernant les
interactions que vous trouverez dans Outils→Wechselwirkungen.

> **Spitze**
>
> Pour activer le log des interactions, il faut aller dans l'onglet
> Administration→Configuration→logs, puis cocher **Debug** dans la liste
> du bas. Aufmerksamkeit : les logs seront alors très verbeux !

General
-------

Sie avez ici trois paramètres :

-   **Sensibilité** : il y a 4 niveaux de correspondance (La sensibilité
    va de 1 (correspond exactement) à 99)

    -   pour 1 mot : le niveau de correspondance pour Wechselwirkungen à
        un seul mot

    -   2 mots : le niveau de correspondance pour Wechselwirkungen à
        deux mots

    -   3 mots : le niveau de correspondance pour Wechselwirkungen à
        trois mots

    -   + de 3 mots : le niveau de correspondance pour Wechselwirkungen
        à plus de trois mots

-   **Ne pas répondre si l'interaction n'est pas comprise** : par défaut
    Jeedom répond "je n'ai pas compris" si aucune interaction
    ne correspond. Il est possible de désactiver ce fonctionnement pour
    que Jeedom ne réponde rien. Cochez la case pour désactiver
    la réponse.

-   **Regex général d'exclusion pour Wechselwirkungen** : lass uns
    définir une regexp qui, si elle correspond à une interaction,
    supprimera automatiquement cette phrase de la génération (réservé
    aux experts). Pour plus d'informations voir les explications dans le
    chapitre **Regexp d'exclusion** de la documentation sur
    Wechselwirkungen.

Interaction automatique, contextuelle & avertissement
-----------------------------------------------------

-   die **interactions automatiques** permettent à Jeedom de tenter de
    comprendre une demande d'interaction même si il n'y en a aucune
    de définie. Il va alors chercher un nom d'objet et/ou d'équipement
    et/ou de commande pour essayer de répondre au mieux.

-   die **interactions contextuelles** vous permettent d'enchainer
    plusieurs demandes sans tout répéter, par exemple :

    -   *Jeedom gardant le contexte :*

        -   *Sie* : Combien fait-il dans la chambre ?

        -   *Jeedom* : Température 25.2 °C

        -   *Sie* : et dans le salon ?

        -   *Jeedom* : Température 27.2 °C

    -   *Poser deux questions en une :*

        -   *Sie* : Combien fait-il dans la chambre et dans le salon ?

        -   *Jeedom* : Température 23.6 °C, Température 27.2 °C

-   die interactions de type **Préviens-moi** permettent de demander à
    Jeedom de vous prévenir si une commande dépasse/descend ou vaut une
    certaine valeur.

    -   *Sie* : Préviens-moi si la température du salon dépasse 25°C ?

    -   *Jeedom* : OK (*Dès que la température du salon dépassera 25°C,
        Jeedom vous le dira, une seule fois*)

> **Notiz**
>
> Par défaut Jeedom vous répondra par le même canal que celui que vous
> avez utilisé pour lui demander de vous prévenir. Si il n'en trouve
> pas, il utilisera alors la commande par défaut spécifiée dans cet
> onglet : **Commande de retour par défaut**.

Voici donc les différentes options disponibles :

-   **Activer Wechselwirkungen automatiques** : Zum Aktivieren aktivieren les
    interactions automatiques.

-   **Activer les réponses contextuelles** : Zum Aktivieren aktivieren les
    interactions contextuelles.

-   **Réponse contextuelle prioritaire si la phrase commence par** : Si
    la phrase commence par le mot que vous renseignez ici, Jeedom va
    alors prioritiser une réponse contextuelle (vous pouvez mettre
    plusieurs Wörter getrennt durch **;** ).

-   **Découper une interaction en 2 si elle contient** : Même chose pour
    le découpage d'une interaction contenant plusieurs questions. Sie
    Geben Sie hier die Wörter an, die die verschiedenen Fragen trennen.

-   **Aktivieren Sie die Interaktionen &quot;Benachrichtigen&quot;** : Zum Aktivieren aktivieren
    Typ Interaktionen **Préviens-moi**.

-   **Antwort &quot;Sag es mir&quot;, wenn der Satz mit beginnt** : Wenn die
    Satz beginnt mit diesen Wörtern, dann wird Jeedom versuchen, ein zu machen
    Typ Interaktion **Préviens-moi** (Sie können mehrere setzen
    Wörter getrennt durch **;** ).

-   **Commande de retour par défaut** : Commande de retour par défaut
    für eine Typinteraktion **Préviens-moi** (verwendet insbesondere
    wenn Sie den Alarm über die mobile Schnittstelle programmiert haben)

-   **Synonym für Objekte** : Liste der Synonyme für Objekte
    (zB : Erdgeschoss | Erdgeschoss | Keller | Erdgeschoss; Bad | Bad).

-   **Synonym für Ausrüstung** : Synonymliste für
    Ausrüstung.

-   **Synonym für Bestellungen** : Synonymliste für
    Bestellungen.

-   **Synonym für Abstracts** : Liste der Synonyme für Zusammenfassungen.

-   **Synonym für maximalen Schiebereglerbefehl** : Synonym für setzen a
    Befehl für den maximalen Schiebereglertyp (zB öffnet sich, um den Verschluss zu öffnen
    der Raum ⇒ 100% Raumverschluss).

-   **Synonym für minimalen Schiebereglerbefehl** : Synonym für setzen a
    Schieberegler-Befehl mindestens (zB schließt, um den Verschluss zu schließen
    der Raum ⇒ Raumkomponente bei 0%).

Sicherheit
========

LDAP
----

-   **Aktivieren Sie die LDAP-Authentifizierung** : Aktivieren Sie die Authentifizierung für
    durch eine AD (LDAP)

-   **Gastgeber** : Server, der die AD hostet

-   **Domain** : Domain Ihrer AD

-   **Basis-DN** : DN Basis Ihrer AD

-   **Benutzername** : Benutzername für Jeedom zu
    Verbindung zu AD herstellen

-   **Passwort** : Passwort für Jeedom, um eine Verbindung zu AD herzustellen

-   **Benutzersuchfelder** : Suchfelder von
    Benutzer Login. Normalerweise uid für LDAP, SamAccountName für
    Windows AD

-   **Filter (optional)** : Filter auf dem AD (zum Verwalten
    Gruppen zum Beispiel)

-   **REMOTE \ _USER zulassen** : Aktivieren Sie REMOTE \ _USER (wird in SSO verwendet
    zum Beispiel)

einloggen
---------

-   **Anzahl der tolerierten Fehler** : Legt die Anzahl der Versuche fest
    erlaubt vor dem Verbot der IP

-   **Maximale Zeit zwischen Ausfällen (in Sekunden)** : maximale Zeit
    so dass 2 Versuche als aufeinanderfolgend betrachtet werden

-   **Verbannungsdauer (in Sekunden), -1 für unendlich** : Zeit von
    IP-Verbot

-   **&quot;Weiße&quot; IP** : Liste der IPs, die niemals gesperrt werden können

-   **Entfernen Sie gesperrte IPs** : Löschen Sie die Liste der IPs
    derzeit verboten

Die Liste der gesperrten IPs befindet sich am Ende dieser Seite. Sie y trouverez
IP, Sperrdatum und Sperrenddatum
geplant.

Update und Dateien
=======================

Jeedom Update
---------------------

-   **Quelle aktualisieren** : Wählen Sie die Quelle für die Aktualisierung der
    Jeedom Kern.

-   **Kernversion** : Kernversion zum Wiederherstellen.

-   **Automatisch nach Updates suchen** : Geben Sie an, ob
    Sie müssen automatisch suchen, wenn es neue Updates gibt
    (Achten Sie darauf, eine Überlastung des Marktes zu vermeiden
    Überprüfung kann sich ändern).

Einlagen
----------

Die Depots sind Speicher- (und Service-) Räume, um in der Lage zu sein
Backups verschieben, Plugins wiederherstellen, Core wiederherstellen
Jeedom usw..

### Datei

Einzahlung verwendet, um das Senden von Plugins durch Dateien zu aktivieren.

### Github

Kaution verwendet, um Jeedom mit Github zu verbinden.

-   **Zeichen** : Zeichen für den Zugang zur privaten Kaution.

-   **Jeedom Core Repository Benutzer oder Organisation** : Name
    der Benutzer oder die Organisation auf Github für den Kern.

-   **Repository-Name für den Jeedom-Kern** : Repository-Name für den Kern.

-   **Jeedom Kernindustrie** : Kern-Repository-Zweig.

### Markt

Eine Kaution, die verwendet wird, um Jeedom mit dem Markt zu verbinden, wird dringend empfohlen
um dieses Repository zu verwenden. Aufmerksamkeit : Jede Supportanfrage kann sein
abgelehnt, wenn Sie eine andere Einzahlung als diese verwenden.

-   **Adresse** : Marktadresse (Https://www.jeedom.com/market)

-   **Benutzername** : Ihr Benutzername auf dem Markt.

-   **Passwort** : Ihr Marktpasswort.

-   **[Backup cloud] Name** : Name Ihres Cloud-Backups (die Aufmerksamkeit muss für jedes Jeedom eindeutig sein, bei dem die Gefahr eines Absturzes besteht)

-   **[Backup cloud] Passwort** : Cloud-Backup-Passwort. WICHTIG Sie dürfen es nicht verlieren, es gibt keine Möglichkeit, es wiederherzustellen. Ohne sie können Sie Ihre Freiheit nicht mehr wiederherstellen.

-   **[Backup cloud] Fréquence backup full** : Häufigkeit der vollständigen Cloud-Sicherung. Eine vollständige Sicherung ist länger als eine inkrementelle (die nur die Unterschiede sendet).. Es wird empfohlen, 1 pro Monat zu tun

### Samba

Zahlen Sie ein, um automatisch ein Jeedom-Backup an zu senden
eine Samba-Aktie (z : NAS-Synologie).

-   **\ [Backup \] IP** : Samba Server IP.

-   **\ [Backup \] Benutzer** : Benutzername für die Anmeldung
    (anonyme Verbindungen sind nicht möglich). Es muss geben
    dass der Benutzer diee- und Schreibrechte an der hat
    Zielverzeichnis.

-   **\ [Backup \] Passwort** : Benutzerpasswort.

-   **\ [Backup \] Freigabe** : Art des Teilens (sei vorsichtig
    auf der Freigabeebene anhalten).

-   **\ [Backup \] Pfad** : Pfad beim Teilen (festlegen
    relativ) muss es existieren.

> **Notiz**
>
> Wenn der Pfad zu Ihrem Samba-Sicherungsordner lautet :
> \\\\ 192.168.0.1 \\ Backups \\ Hausautomation \\ Jeedom Dann IP = 192.168.0.1
> , Sharing = //192.168.0.1 / Backups, Path = Home Automation / Jeedom

> **Notiz**
>
> Bei der Validierung der Samba-Freigabe, wie oben beschrieben,
> In diesem Abschnitt wird eine neue Form der Sicherung angezeigt
> Administration → Jeedom-Backups. Durch Aktivieren wird Jeedom fortfahren
> wenn es beim nächsten Backup automatisch gesendet wird. Ein Test ist
> möglich durch manuelle Sicherung.

> **wichtig**
>
> Möglicherweise müssen Sie das smbclient-Paket für das installieren
> Einzahlung funktioniert.

> **wichtig**
>
> Das Samba-Protokoll hat mehrere Versionen, die v1 ist kompromittiert
> Sicherheit und auf einigen NAS können Sie den Client zur Verwendung von v2 zwingen
> oder v3 zum Verbinden. Wenn Sie also einen Protokollverhandlungsfehler haben
> fehlgeschlagen: NT_STATUS_INVAID_NETWORK_RESPONSE Es besteht eine gute Chance, dass NAS aufgelistet wird
> die Einschränkung besteht. Sie müssen dann das Betriebssystem Ihres Jeedom ändern
> die Datei / etc / samba / smb.conf und füge diese beiden Zeilen hinzu :
> Client-Max-Protokoll = SMB3
> Client-Min-Protokoll = SMB2
> Der Jeedom-Seite smbclient verwendet dann v2, wobei v3 und nur SMB3 in beiden
> SMB3. Es liegt also an Ihnen, sich an die Einschränkungen des NAS oder eines anderen Samba-Servers anzupassen

> **wichtig**
>
> Jeedom sollte der einzige sein, der in diesen Ordner schreibt, und er sollte leer sein
> standardmäßig (d. h. vor dem Konfigurieren und Senden des
> Bei der ersten Sicherung darf der Ordner keine Datei oder enthalten
> Ordner).

### URLs

-   **Jeedom-Kern-URLs**

-   **URLs der Jeedom-Kernversion**

Abdeckung
=====

Ermöglicht die Überwachung und Bearbeitung des Jeedom-Abdeckung :

-   **Statistiken** : Anzahl der aktuell zwischengespeicherten Objekte

-   **Reinigen Sie die Abdeckung** : Erzwingen Sie das Löschen von Objekten, die dies nicht sind
    nützlicher. Jeedom macht das automatisch jede Nacht.

-   **Löschen Sie alle zwischengespeicherten Daten** : Leeren Sie den Deckel vollständig.
    Bitte beachten Sie, dass dies zu Datenverlust führen kann !

-   **Leeren Sie den Widget-Abdeckung** : Leeren Sie den Abdeckung für Widgets

-   **Deaktivieren Sie den Widget-Abdeckung** : Aktivieren Sie das Kontrollkästchen zum Deaktivieren
    Das Widget deckt ab

-   **Pause für lange Abfragen** : Wie oft
    Jeedom prüft, ob Ereignisse für Kunden ausstehen
    (Weboberfläche, mobile Anwendung usw.). Je kürzer diesmal, desto mehr
    Die Schnittstelle wird im Gegenzug schnell aktualisiert
    verbraucht mehr Ressourcen und kann daher Jeedom verlangsamen.

API
===

Hier ist die Liste der verschiedenen API-Schlüssel, die in verfügbar sind
deine Freiheit. Core verfügt über zwei API-Schlüssel :

-   ein General : Vermeiden Sie es so weit wie möglich.

-   und eine andere für Profis : für das Management verwendet
    des Parks. Es kann leer sein.

-   Dann finden Sie einen API-Schlüssel pro Plugin, der ihn benötigt.

Für jeden Plugin-API-Schlüssel sowie für HTTP, JsonRPC und APIs
TTS können Sie deren Umfang definieren :

-   **untauglich** : Der API-Schlüssel kann nicht verwendet werden.

-   **Weiße IP** : Es ist nur eine Liste von IPs autorisiert (siehe
    Administration → Einstellungen → Netzwerke)

-   **localhost** : nur Anfragen vom System, auf dem sich befindet
    installierte Jeedom sind erlaubt,

-   **aktiviert** : Keine Einschränkungen, jedes System mit Zugriff
    Ihr Jeedom kann auf diese API zugreifen.

&gt;\ _OS / DB
===========

Auf dieser Registerkarte befinden sich zwei Teile, die Experten vorbehalten sind.

> **wichtig**
>
> VORSICHT : Wenn Sie Jeedom mit einer dieser beiden Lösungen ändern,
> Der Support kann sich weigern, Ihnen zu helfen.

-   **&gt;\ _SYSTEM** : Ermöglicht den Zugriff auf eine Schnittstelle
    Systemadministration. Es ist eine Art Shell-Konsole in
    Hier können Sie die nützlichsten Befehle ausführen, einschließlich
    um Informationen über das System zu erhalten.

-   **Datei-Editor** : Ermöglicht den Zugriff auf verschiedene Systemdateien
    und bearbeiten oder löschen oder erstellen Sie sie.

-   **Datenbank** : Administration / Start : Ermöglicht den Zugriff auf die Datenbank
    von Jeedom. Sie können dann Befehle in das Feld starten
    von oben.
    Überprüfen / Starten : Ermöglicht das Starten einer Überprüfung in der Datenbank
    Jeedom und korrigieren Sie gegebenenfalls Fehler

    Zur Information werden unten zwei Parameter angezeigt :

    -   **Benutzer** : Benutzername von Jeedom in
        die Datenbank,

    -   **Passwort** : Datenbankzugriffskennwort
        von Jeedom verwendet.
