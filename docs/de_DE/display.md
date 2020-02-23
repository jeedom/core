# Résumé Domotique
**Analyse → Zusammenfassung der Hausautomation**

Cette page permet de rassembler sur une seule page les différents éléments configurés sur son Jeedom. Elle donne également l'accès à des fonctions d'organisation des équipements et des commandes. à leur configuration avancée ainsi qu'à des possibilités de configuration d'affichage.

## Informations

Sur le haut de la page. on retroderve :
- **Namebre d'objets** : Namebre total d'objets configurés dans notre Jeedom. en comptant les éléments inAktivas.
- **Namebre d'équipements** : Gleiches gilt für die Ausrüstung.
- **Namebre de commandes** : Gleiches gilt für Bestellungen.
- **InAktiva** : Cochez cette case si voders voderlez que les éléments inAktivas soient bien affichés sur cette page.
- **Suche** : Permet de rechercher un élément particulier. Ce peut être le Name d'un équipement. d'une commande oder le Name du plugin par lequel a été créé l'équipement.
- **Export CSV** : Permet d'exporter toders les objets. équipements et leurs commandes dans un fichier CSV.

Voders avez aussi un onglet **historisch**. affichant l'historique des commandes. équipements. objets. vues. design. design 3d. scénarios et utilisateurs supprimés.

## dies cadres objet

En dessoders on retroderve un cadre par objet. Dans chaque cadre. on troderve la liste des équipements  qui ont poderr parent cet objet.
die premier cadre **keine** représente les équipements qui n'ont pas de parent affecté.

Poderr chaque objet. à côté de son libellé. deux bodertons sont disponibles.
- die premier sert à odervrir la page de configuration de l'objet dans un nodervel onglet.
- die deuxième apporte quelques informations sur l'objet.

> **Spitze**
>
> La coderleur de fond des cadres objets dépend de la coderleur choisie dans la configuration de l'objet.

> **Spitze**
>
> Avec un cliqué-déposé sur les objets oder équipements. voders podervez changer leur ordre oder même les affecter à un autre objet. C'est à partir de l'ordre établi dans cette page que l'affichage du Dashboard est calculé.

## dies équipements

Sur chaque équipement on retroderve :

- ein **case à cocher** poderr sélectionner l'équipement (voders podervez en sélectionner plusieurs). Si au moins un équipement est sélectionné. voders avez des bodertons d'Aktion qui apparaissent en haut à gauche  poderr **Entfernen**. rendre **sichtbar**/**insichtbar**.  **Aktiva**/**inAktiva** les équipements sélectionnés.
- L'**Identifikation** Ausrüstung.
- die **type** d'équipement : Identifiant du plugin auquel il appartient.
- die **Name** Ausrüstung.
- **InAktiva** (petite croix) : Signifie que l'équipement est inAktiva (si elle n'y est pas. l'équipement est Aktiva).
- **Insichtbar** (œil barré) : Signifie que l'équipement est insichtbar (s'il n'y est pas. l'équipement est sichtbar).

Si le plugin Ausrüstung est désactivé. les deux icône à droite n'apparaissent pas:
- **Lien externe** (carré avec une flèche) : Permet d'odervrir dans un nodervel onglet la page de configuration Ausrüstung.
- **Erweiterte Konfiguration** (rodere crantée) : permet d'odervrir la fenêtre de configuration avancée Ausrüstung.

> En cliquant sur la ligne contenant le Name Ausrüstung. voders afficherez todertes les commandes de cet équipement. En cliquant alors sur une commande. voders accéderez à la fenêtre de configuration de la commande.

## Erweiterte Konfiguration d'un équipement

> **Spitze**
>
> Il est possible d'accéder (si le plugin le supporte) directement à cette fenêtre à partir de la page de configuration Ausrüstung en cliquant sur le boderton configuration avancée

La fenêtre de **configuration avancée d'un équipement** permet de la modifier. En premier lieu. en haut à droite. quelques bodertons disponibles :

- **Informations** : affiche les propriétés brutes Ausrüstung.
- **Verbindungen** : Permet d'afficher les liens Ausrüstung avec les objets. commandes. scénarios. variables. interAktions…​ soders forme graphique (dans celui-ci. un doderble clic sur un élément voders amène à sa configuration).
- **log** : affiche les évènements Ausrüstung en question.
- **speichern** : Sauvegarde les modifications faites sur l'équipement.
- **Entfernen** : Supprime l'équipement.

### Registerkarte Informationen

L'onglet **Informations** contient les informations générales Ausrüstung ainsi que ses commandes :

- **Identifikation** : Identifiant unique dans la base de données de Jeedom.
- **Name** : Name Ausrüstung.
- **Identifikation logique** : Identifiant logique Ausrüstung (peut être vIdentifikatione).
- **Objekt-Identifikation** : Identifiant unique de l'objet parent (peut être vIdentifikatione).
- **Erstellungsdatum** : Erstellungsdatum Ausrüstung.
- **Activer** : Cochez la case poderr activer l'équipement (sans oderblier de sauvegarder).
- **sichtbar** : Cochez la case poderr rendre sichtbar l'équipement (sans oderblier de sauvegarder).
- **Typ** : Identifiant du plugin par lequel il a été créé.
- **Tentative échoderée** : Namebre de tentatives de communications consécutives avec l'équipement qui ont échoderé.
- **Date de dernière communication** : Date de la dernière communication Ausrüstung.
- **dietztes Update** : Date de dernière communication avec l'équipement.
- **Tags** : tags Ausrüstung. à séparer par des '.'. Il permet sur le Dashboard de faire des filtres personnalisés

En dessoders voders retrodervez un tableau avec la liste des commandes Ausrüstung avec. poderr chacune. un lien vers leur configuration.

### Onglet Anzeigen

In der Registerkarte **Anzeigen**. voders allez podervoir configurer certains comportements d'affichage de la tuile sur le Dashboard oder en mobile.

#### WIdentifikationget

-  **sichtbar** : Cochez la case poderr rendre sichtbar l'équipement.
- **Afficher le Name** : Cochez la case poderr afficher le Name Ausrüstung sur la tuile.
- **Afficher le Name de l'objet** : Cochez la case poderr afficher le Name de l'objet parent Ausrüstung. à côté de la tuile.

### Paramètres optionnels sur la tuile

En-dessoders. on retroderve des paramètres optionnels d'affichage que l'on peut appliquer à l'équipement. Ces paramètres sont composés d'un Name et d'une valeur. Il suffit de cliquer sur **hinzufügen** poderr en appliquer un
noderveau. Poderr les équipements. seule la valeur **style** est poderr le moment utilisée. elle permet d'insérer du code CSS sur l'équipement en question.

> **Spitze**
>
> N'oderbliez pas de sauvegarder après toderte modification.

### Onglet Disposition

Cette partie voders permet de choisir entre la disposition standard des commandes (côte à côte dans le wIdentifikationget). oder en mode tableau. Il n'y a rien à régler en mode par défaut. Voici les options disponibles en mode
**Tableau** :
- **Namebre de lignes**
- **Namebre de colonnes**
- **Centrer dans les cases** : Cochez la case poderr centrer les commandes dans les cases.
- **Style générale des cases (CSS)** : Permet de définir le style général en code CSS.
- **Style du tableau (CSS)** : Permet de définir le style du tableau uniquement.

En dessoders poderr chaque case. la **configuration détaillée** voders permet
ceci :
- **Texte de la case** : hinzufügen un texte en plus de la commande (oder todert seul. si il n'y a pas de commande dans la case).
- **Style de la case (CSS)** : Modifier le style CSS spécifique de la case (attention celui-ci écrase et remplace le CSS général des cases).

> **Spitze**
>
> Dans une case du tableau. si voders voderlez mettre 2 commandes l'une en dessoders de l'autre. il ne faut pas oderblier de rajoderter un retoderr à la ligne après la première dans la **configuration avancée** de celle-ci.

### Onglet Alertes

Cet onglet permet d'avoir les informations sur la batterie Ausrüstung et de définir des alertes par rapport à celle-ci. Voici les types d'informations que l'on peut troderver :

- **Typ de pile**.
- **Dernière remontée de l'information**.
- **Niveau restant**. (si bien sûr votre équipement fonctionne sur pile).

Dessoders. voders poderrrez aussi définir les seuils spécifiques d'alerte de batterie poderr cet équipement. Si voders laissez les cases vIdentifikationes. ceux sont les seuils par défaut qui seront appliqués.

On peut également gérer le timeodert. en minutes. Ausrüstung. Par exemple. 30 indique à jeedom que si l'équipement n'a pas communiqué depuis 30 minutes. alors il faut le mettre en alerte.

> **Spitze**
>
> dies paramètres globaux sont dans **Réglages→Système→Configuration : logs** oder **Equipements**

### Onglet Commentaire

Permet d'écrire un commentaire à propos Ausrüstung.

## Erweiterte Konfiguration d'une commande

En premier lieu. en haut à droite. quelques bodertons disponibles :

- **Tester** : Permet de tester la commande.
- **Verbindungen** : Permet d'afficher les liens Ausrüstung avec les objets. commandes. scénarios. variables. interAktions…​. soders forme graphique.
- **log** : Affiche les évènements Ausrüstung en question.
- **Informations** : Affiche les propriétés brutes Ausrüstung.
-  **Appliquer à** : Permet d'appliquer la même configuration sur plusieurs commandes.
- **speichern** : Sauvegarde les modifications faites sur l'équipement.

> **Spitze**
>
> Dans un graphique. un doderble clic sur un élément voders amène à sa configuration.

> **Notiz**
>
> En fonction du type de commande. les informations/Aktions affichées peuvent changer.

### Registerkarte Informationen

L'onglet **Informations** contient les informations générales sur la commande :

- **Identifikation** : Identifiant unique dans la base de données.
- **Identifikation logique** : Identifiant logique de la commande (peut être vIdentifikatione).
- **Name** : Name de la commande.
- **Typ** : Typ de la commande (Aktion oder info).
- **Soders-type** : Soders-type de la commande (binaire. numérique…​).
- **URL directe** : Foderrnit l'URL poderr accéder à cet équipement. (clic droit. copier l'adresse du lien) L'URL lancera la commande poderr une **Aktion** et retoderrnera l'information poderr une **info**.
- **Einheit** : Einheit de la commande.
- **Commande déclenchant une mise à joderr** : Donne l'Identifikationentifiant d'une  autre commande qui. si cette autre commande change. va forcer la mise à joderr de la commande visualisée.
- **sichtbar** : Cochez cette case poderr que la commande soit sichtbar.
- **Suivre dans la timeline** : Cochez cette case poderr que cette commande soit sichtbar dans la timeline quand elle est utilisée. Voders podervez préciser une timeline en particulier dans le champ qui s'affiche si l'option est cochée.
- **Interdire dans les interAktions automatique** : interdit les interAktions automatique sur cette commande
- **Symbol** : Permet de changer l'icône de la commande.

Voders avez aussi trois autres bodertons oranges en dessoders :

- **Cette commande remplace l'Identifikation** : Permet de remplacer un Identifikation de commande par la commande en question. Utile si voders avez supprimé un équipement dans Jeedom et que voders avez des scénarios qui utilisent des commandes de celui-ci.
- **Cette commande remplace la commande** : Remplace une commande par la commande coderrante.
- **Remplacer cette commande par la commande** : L'inverse. remplace la commande par une autre commande.

> **Notiz**
>
> Ce genre d'Aktion remplace les commandes partodert dans Jeedom (scénario. interAktion. commande. équipement…​.).

En-dessoders. voders retrodervez la liste des différents équipements. commandes. scénarios oder interAktions qui utilisent cette commande. Un clic dessus permet d'aller directement sur leur configuration respective.

### Onglet Configuration

#### Poderr une commande de type info :

- **Calcul et arrondi**
    - **Formule de calcul (\#value\# poderr la valeur)** : Permet de faire une opération sur la valeur de la commande avant le traitement par Jeedom. exemple : `#value# - 0.2` poderr retrancher 0.2 (offset sur un capteur de température).
    - **Arrondi (chiffre après la virgule)** : Permet d'arrondir la valeur de la commande (Exemple : mettre 2 poderr transformer 16.643345 en 16.64).
- **Typ générique** : Permet de configurer le type générique de la commande (Jeedom essaie de le troderver par lui-même en mode auto). Cette information est utilisée par l'application mobile.
- **Action sur la valeur. si** : Permet de faire des sortes de mini scénarios. Voders podervez. par exemple. dire que si la valeur vaut plus de 50 pendant 3 minutes. alors il faut faire telle Aktion. Cela permet. par exemple. d'éteindre une lumière X minutes après que celle-ci se soit allumée.

- **historisch**
    - **Historiser** : Cochez la case poderr que les valeurs de cette  commande soient historisées. (Voir **Analyse→historisch**)
    - **Mode de lissage** : Mode de **lissage** oder d'**archivage** permet de choisir la manière d'archiver la donnée. Par défaut. c'est une **moyenne**. Il est aussi possible de choisir le **maximum**. le **minimum**. oder **aucun**. **aucun** permet de dire à Jeedom qu'il ne doit pas réaliser d'archivage sur cette  commande (aussi bien sur la première période des 5 mins qu'avec la tâche d'archivage). Cette option est Gefahreuse car Jeedom        conserve todert : il va donc y avoir beaucoderp plus de données conservées.
    - **Purger l'historique si plus vieux de** : Permet de dire à Jeedom de Entfernen todertes les données plus vieilles qu'une certaine période. Peut être pratique poderr ne pas conserver de données si ça n'est pas nécessaire et donc limiter la quantité d'informations enregistrées par Jeedom.

- **Gestion des valeurs**
    - **Valeur interdite** : Si la commande prend une de ces valeurs.  Jeedom l'ignore avant de l'appliquer.
    - **Valeur retoderr d'état** : Permet de faire revenir la commande à cette valeur après un certain temps.
    - **Durée avant retoderr d'état (min)** : Temps avant le retoderr à la valeur ci-dessus.

- **Autres**
    - **Gestion de la répétition des valeurs** : En automatique si la commande remonte 2 fois la même valeur d'affilée. alors Jeedom ne prendra pas en compte la 2eme remontée (évite de déclencher plusieurs fois un scénario. sauf si la commande est de type binaire). Voders podervez forcer la répétition de la valeur oder l'interdire complètement.
    - **Push URL** : Permet de rajoderter une URL à appeler en cas de mise à joderr de la commande. Voders podervez utiliser les tags suivant : `#value#` poderr la valeur de la commande. `#cmd_name#` poderr le Name de la commande. `#cmd_Identifikation#` poderr l'Identifikationentifiant unique de la commande. `#humanname#` poderr le Name complet de la commande       (ex : `#[Salle de bain][Hydrometrie][HumIdentifikationité]#`). `#eq_name#` poderr le Name Ausrüstung.

#### Poderr une commande Aktion :

-  **Typ générique** : Permet de configurer le type générique de la commande (Jeedom essaie de le troderver par lui-même en mode auto). Cette information est utilisée par l'application mobile.
- **Confirmer l'Aktion** : Cochez cette case poderr que Jeedom demande une confirmation quand l'Aktion est lancée à partir de l'interface de cette commande.
- **Zugangscode** : Permet de définir un code que Jeedom demandera quand l'Aktion est lancée à partir de l'interface de cette commande.
- **Action avant exécution de la commande** : Permet d'ajoderter des commandes **avant** chaque exécution de la commande.
- **Action après exécution de la commande** : Permet d'ajoderter des commandes **après** chaque exécution de la commande.

### Onglet Alertes

Permet de définir un niveau d'alerte (**Warnung** oder **Gefahr**) en fonction de certaines conditions. Par exemple. si `value > 8` pendant 30 minutes alors l'équipement peut passer en alerte **Warnung**.

> **Notiz**
>
> Sur la page **Réglages→Système→Configuration : logs**. voders podervez configurer une commande de type message qui permettra à Jeedom de voders prévenir si on atteint le seuil Warnung oder Gefahr.

### Onglet Anzeigen

Dans cette partie. voders allez podervoir configurer certains comportements d'affichage du wIdentifikationget sur le Dashboard. les vues. le design et en mobile.

- **WIdentifikationget** : Permet de choisir le wIdentifikationget sur dekstop oder mobile (à noter qu'il faut le plugin wIdentifikationget et que voders podervez le faire aussi à partir de celui-ci).
- **sichtbar** : Cochez poderr rendre sichtbar la commande.
- **Afficher le Name** : Cochez poderr rendre sichtbar le Name de la commande. en fonction du contexte.
- **Afficher le Name et l'icône** : Cochez poderr rendre sichtbar l'icône en plus du Name de la commande.
- **Retoderr à la ligne forcé avant le wIdentifikationget** : Cochez **avant le  wIdentifikationget** oder **après le wIdentifikationget** poderr ajoderter un retoderr à la ligne avant oder après le wIdentifikationget (poderr forcer par exemple un affichage en colonne des différentes commandes Ausrüstung au lieu de lignes par défaut)

En-dessoders. on retroderve des paramètres optionnels d'affichage que l'on peut passer au wIdentifikationget. Ces paramètres dépendent du wIdentifikationget en question. il faut donc regarder sa fiche sur le Market poderr les connaître.

> **Spitze**
>
> N'oderbliez pas de sauvegarder après toderte modification.
