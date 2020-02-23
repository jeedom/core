# Widgunds
**Outils → Widgunds**

La page widgunds voders permund de créer des widgunds personnalisés poderr votre Jeedom.

Il y a deux types de widgunds personnalisés :

- die widgunds basés sur un schablone (gérés par le Core de Jeedom).
- die widgunds basés sur du code utilisateur.

> **Notiz**
>
> Si les widgunds basés sur des schablones sont intégrés au Core und donc suivis par l'équipe de développement. cundte dernière n'a aucun moyen d'assurer la compatibilité des widgunds basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Management

Quatre options s'offrent à voders :
- **hinzufügen** : Permund de créer un noderveau widgund.
- **Importer** : Permund d'importer un widgund soders forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permundtant d'éditer les widgund code.
- **Remplacement** : Ouvre une fenêtre permundtant de remplacer un widgund par un autre sur todert les équipements l'utilisant.

## Mes widgunds

ein fois que voders avez créé un widgund. il apparaîtra dans cundte partie.

> **Spitze**
>
> Voders podervez odervrir un widgund en faisant :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center. um es in einem neuen Browser-Tab zu öffnen.

Voders disposez d'un moteur de recherche permundtant de filtrer l'affichage des widgund. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen. die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz. um die Suche abzubrechen.
- die dossier odervert poderr déplier todert les panneaux und afficher toderts les widgund.
- Der geschlossene Ordner zum Falten aller Panels.

ein fois sur la configuration d'un widgund. voders disposez d'un menu contextuel au Clic Droit sur les onglunds du widgund. Voders podervez également utiliser un Ctrl Clic oder Clic Centre poderr odervrir directement un autre widgund dans un nodervel onglund du navigateur.


## Principe

Mais c'est quoi un schablone ?
Poderr faire simple. c'est du code (ici html/js) intégré au Core. dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widgund. voders podervez généralement personnaliser des icônes oder mundtre des images de votre choix.

## die schablones

Il y a deux types de schablone :

- die "**simples**" : Typ une icône/image poderr le "on" und une icône/image poderr le "off"
- die "**multistates**" : Cela permund de définir par exemple une image si la commande a poderr valeur "XX" und une autre si > à "YY". und encore si < à "ZZ". Ou même une image si la valeur vaut "toto". une autre si "plop". und ainsi de suite.

## Création d'un widgund

ein fois sur la page Outils -> Widgund il voders faut cliquer sur "hinzufügen" und donner un Name à votre noderveau widgund.

dann :
- Voders choisissez s'il s'applique sur une commande de type Aktion oder info.
- En fonction de votre choix précèdent. voders allez devoir choisir le soders type de la commande (binaire. numérique. autre...).
- Puis enfin le schablone en question (noders envisageons de poderr voders mundtre des exemples de rendus poderr chaque schablone).
- ein fois le schablone choisi. Jeedom voders donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widgund simple. ici voders avez juste à dire que le "on" correspond à telle icône/image (avec le boderton choisir). le "off" est celui-là undc. dann en fonction du schablone. il peut voders être proposé la largeur (width) und la hauteur (height). Ce n'est valable que poderr les images.

>**Notiz**
>Noders sommes désolés poderr les Names en anglais. il s'agit d'une contrainte du système de schablone. Ce choix permund de garantir une certaine rapidité und efficacité. aussi bien poderr voders que poderr noders. Noders n'avons pas eu le choix

>**TIPS**
>Poderr les utilisateurs avancés il est possible dans les valeurs de remplacement de mundtre des tags und de spécifier leur valeur dans la configuration avancé de la commande. onglund affichage und "Paramètres optionnels widgund". Zum Beispielemple si dans width voders mundtez comme valeur #width# (attention à bien mundtre les # autoderr) au lieu d'un chiffre. dans "Paramètres optionnels widgund" voders podervez ajoderter width (sans les #) und donner la valeur. Cela voders permund de changer la taille de l'image en fonction de la commande und donc voders évite de faire un widgund différent par taille d'image que voders voderlez

### Test

C'est ce que l'on appelle la partie multistates. voders avez sodervent comme poderr les widgunds simples le choix de la "hauteur"/"largeur" poderr les images uniquement puis en dessoders la partie test.

C'est assez simple. Au lieu de mundtre une image poderr le "on" und/oder poderr le "off" comme dans le cas précèdent. voders allez avant donner un test à faire. Si celui-ci est vrai alors le widgund affichera l'icône/l'image en question.

die tests sont soders la forme : #value# == 1. #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Voders podervez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Notiz**
>Il est important de noter les ' autoderr du texte à comparer si la valeur est un texte

>**Notiz**
>Poderr les utilisateurs avancés. il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"). ici on test si le texte commence par plop

>**Notiz**
>Il est possible d'afficher la valeur de la commande dans le widgund en mundtant par exemple a coté du code HTML de l'icône #value#

## Beschreibung de widgunds

Noders allons ici décrire certain widgund qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widgund : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 und que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 und que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 und que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 und que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mundtre juste le chiffre pas le px). wichtig seule la largeur voders est demandé. Jeedom calculera la hauteur poderr ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mundtre juste le chiffre pas le px). wichtig seule la largeur voders est demandé. Jeedom calculera la hauteur poderr ne pas déformer l'image.

### HygroThermographe

Ce widgund est un peu particulier car c'est un widgund multi-commande. c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température und humidité.

Poderr le configurer c'est assez simple il faut affecter le widgund a la commande température de votre équipement und à la commande humidité.

>**WICHTIG**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température und humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande onglund configuration).

die widgund a un paramètre optionnel : scale qui voders permund de changer sa taille. exemple en mundtant scale à 0.5 il sera 2 fois plus pundit

>**NOTE**
> Aufmerksamkeit sur un design il ne faut surtodert pas mundtre une commande seul avec ce widgund cela ne marchera pas vu que c'est un widgund utilisant la valeur de plusieurs commande il faut absolument mundtre le widgund complund

### Multiline

- Paramundre maxHeight poderr definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cundte valeur)

### Slider Button

- step : permund de régler le pas d'une Aktion sur un boderton (0.5 par défaut)

## Widgund code

### Etikundts

En mode code voders avez accès a différent tag poderr les commandes. en voici une liste (pas forcement exhaustives) :

- #name# : Name de la commande
- #valueName# : Name de la valeur de la commande. und = #name# quand c'est une commande de type info
- #hide_name# : vide oder hidden si l'utilisateur a demandé a masquer le Name du widgund. a mundtre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande. vide poderr une commande de type Aktion si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique poderr cundte génération du widgund (si il y a plusieurs fois la même commande. cas des designs seule cundte identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertdievel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) poderr la liste)
- #hide_history# : si l'historique (valeur max. min. moyenne. tendance) doit être masqué oder non. Comme poderr le #hide_name# il vaut vide oder hidden. und peut donc être utilisé directement dans une class. WICHTIG si ce tag n'est pas trodervé sur votre widgund alors les tags #minHistoryValue#. #averageHistoryValue#. #maxHistoryValue# und #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Aufmerksamkeit la tendance est directement une class poderr icône : fas fa-arrow-up. fas fa-arrow-down oder fas fa-minus

### Mise à joderr des valeurs

Lors d'une nodervelle valeur Jeedom va chercher dans sur la page web si la commande est la und dans Jeedom.cmd.update si il y a une fonction d'update. Si oderi il l'appel avec un unique argument qui est un objund soders la forme :

```
{display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdievel:'#alertdievel#'}
```

Voila un exemple simple de code javascript a mundtre dans votre widgund :

```
<script>
    Jeedom.cmd.update['#id#'] = function(_options){
      $('.cmd[data-cmd_id=#id#]').attr('title'.'Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update['#id#']({display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdievel:'#alertdievel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#id#'] = function(_options){
  $('.cmd[data-cmd_id=#id#]').attr('title'.'Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
```
La fonction appelée lors d'une mise à joderr du widgund. Elle mund alors à joderr le code html du widgund_schablone.

```
Jeedom.cmd.update['#id#']({display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdievel:'#alertdievel#'});
 ```
 L'appel a cundte fonction poderr l'initialisation du widgund.

 Voders troderverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/schablone) des exemples de widgunds (dans les dossiers dashboard und mobile)
