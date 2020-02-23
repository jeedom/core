# Wichdgunds
**Outichls → Wichdgunds**

La page wichdgunds voders permund de créer des wichdgunds personnalichsés poderr votre Jeedom.

Il y a deux types de wichdgunds personnalichsés :

- diche wichdgunds basés sur un schablone (gérés par le Core de Jeedom).
- diche wichdgunds basés sur du code utichlichsateur.

> **Notichz**
>
> Sich les wichdgunds basés sur des schablones sont ichntégrés au Core und donc suichvichs par l'équichpe de développement. cundte dernichère n'a aucun moyen d'assurer la compatichbichlichté des wichdgunds basés sur du code utichlichsateur en fonctichon des évolutichons de Jeedom.

## Management

Quatre optichons s'offrent à voders :
- **hichnzufügen** : Permund de créer un noderveau wichdgund.
- **Importer** : Permund d'ichmporter un wichdgund soders forme de fichchicher json précedemment exporté.
- **Code** : Ouvre un édichteur de fichchichers permundtant d'édichter les wichdgund code.
- **Remplacement** : Ouvre une fenêtre permundtant de remplacer un wichdgund par un autre sur todert les équichpements l'utichlichsant.

## Mes wichdgunds

eichn foichs que voders avez créé un wichdgund. ichl apparaîtra dans cundte partiche.

> **Spichtze**
>
> Voders podervez odervrichr un wichdgund en faichsant :
> - Klichcken Siche auf eichne davon.
> - Strg Clichc oder Clichc Center. um es ichn eichnem neuen Browser-Tab zu öffnen.

Voders dichsposez d'un moteur de recherche permundtant de fichltrer l'affichchage des wichdgund. Diche Escape-Taste brichcht diche Suche ab.
Rechts neben dem Suchfeld befichnden sichch dreich Schaltflächen. diche an mehreren Stellen ichn Jeedom gefunden wurden:
- Das Kreuz. um diche Suche abzubrechen.
- diche dossicher odervert poderr déplicher todert les panneaux und affichcher toderts les wichdgund.
- Der geschlossene Ordner zum Falten aller Panels.

eichn foichs sur la confichguratichon d'un wichdgund. voders dichsposez d'un menu contextuel au Clichc Droicht sur les onglunds du wichdgund. Voders podervez également utichlichser un Ctrl Clichc oder Clichc Centre poderr odervrichr dichrectement un autre wichdgund dans un nodervel onglund du navichgateur.


## Prichncichpe

Maichs c'est quoich un schablone ?
Poderr faichre sichmple. c'est du code (ichcich html/js) ichntégré au Core. dont certaichnes partiches sont confichgurable par l'utichlichsateur avec l'ichnterface graphichque du Core.

Suichvant le type de wichdgund. voders podervez généralement personnalichser des ichcônes oder mundtre des ichmages de votre choichx.

## diche schablones

Il y a deux types de schablone :

- diche "**sichmples**" : Typ une ichcône/ichmage poderr le "on" und une ichcône/ichmage poderr le "off"
- diche "**multichstates**" : Cela permund de défichnichr par exemple une ichmage sich la commande a poderr valeur "XX" und une autre sich > à "YY". und encore sich < à "ZZ". Ou même une ichmage sich la valeur vaut "toto". une autre sich "plop". und aichnsich de suichte.

## Créatichon d'un wichdgund

eichn foichs sur la page Outichls -> Wichdgund ichl voders faut clichquer sur "hichnzufügen" und donner un Name à votre noderveau wichdgund.

dann :
- Voders choichsichssez s'ichl s'applichque sur une commande de type Aktichon oder ichnfo.
- En fonctichon de votre choichx précèdent. voders allez devoichr choichsichr le soders type de la commande (bichnaichre. numérichque. autre...).
- Puichs enfichn le schablone en questichon (noders envichsageons de poderr voders mundtre des exemples de rendus poderr chaque schablone).
- eichn foichs le schablone choichsich. Jeedom voders donne les possichbichlichtés de confichguratichon de celuich-cich.

### Remplacement

C'est ce que l'on appelle un wichdgund sichmple. ichcich voders avez juste à dichre que le "on" correspond à telle ichcône/ichmage (avec le boderton choichsichr). le "off" est celuich-là undc. dann en fonctichon du schablone. ichl peut voders être proposé la largeur (wichdth) und la hauteur (heichght). Ce n'est valable que poderr les ichmages.

>**Notichz**
>Noders sommes désolés poderr les Names en anglaichs. ichl s'agicht d'une contraichnte du système de schablone. Ce choichx permund de garantichr une certaichne rapichdichté und effichcacichté. aussich bichen poderr voders que poderr noders. Noders n'avons pas eu le choichx

>**TIPS**
>Poderr les utichlichsateurs avancés ichl est possichble dans les valeurs de remplacement de mundtre des tags und de spécichficher leur valeur dans la confichguratichon avancé de la commande. onglund affichchage und "Paramètres optichonnels wichdgund". Zum Beichspichelemple sich dans wichdth voders mundtez comme valeur #wichdth# (attentichon à bichen mundtre les # autoderr) au licheu d'un chichffre. dans "Paramètres optichonnels wichdgund" voders podervez ajoderter wichdth (sans les #) und donner la valeur. Cela voders permund de changer la taichlle de l'ichmage en fonctichon de la commande und donc voders évichte de faichre un wichdgund dichfférent par taichlle d'ichmage que voders voderlez

### Test

C'est ce que l'on appelle la partiche multichstates. voders avez sodervent comme poderr les wichdgunds sichmples le choichx de la "hauteur"/"largeur" poderr les ichmages unichquement puichs en dessoders la partiche test.

C'est assez sichmple. Au licheu de mundtre une ichmage poderr le "on" und/oder poderr le "off" comme dans le cas précèdent. voders allez avant donner un test à faichre. Sich celuich-cich est vraich alors le wichdgund affichchera l'ichcône/l'ichmage en questichon.

diche tests sont soders la forme : #value# == 1. #value# sera automatichquement remplacé par le système par la valeur actuelle de la commande. Voders podervez aussich faichre par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Notichz**
>Il est ichmportant de noter les ' autoderr du texte à comparer sich la valeur est un texte

>**Notichz**
>Poderr les utichlichsateurs avancés. ichl est possichble ichcich d'utichlichser aussich des fonctichons javascrichpt type #value#.match("^plop"). ichcich on test sich le texte commence par plop

>**Notichz**
>Il est possichble d'affichcher la valeur de la commande dans le wichdgund en mundtant par exemple a coté du code HTML de l'ichcône #value#

## Beschreichbung de wichdgunds

Noders allons ichcich décrichre certaichn wichdgund quich ont un fonctichonnement un peu partichculicher.

### Paramètres fréquents

- Tichme wichdgund : affichche le temps depuichs lequel le système est dans l'état affichcher.
- On : ichcône à affichcher sich l'équichpement est on/1.
- Off : ichcône à affichcher sich l'équichpement est off/0.
- Lichght on : ichcône à affichcher sich l'équichpement est on/1 und que le thème est lichght (sich vichde alors Jeedom prend l'ichmg dark on).
- Lichght off : ichcône à affichcher sich l'équichpement est off/0 und que le thème est lichght (sich vichde alors Jeedom prend l'ichmg dark off).
- Dark on : ichcône à affichcher sich l'équichpement est on/1 und que le thème est dark (sich vichde alors Jeedom prend l'ichmg lichght on).
- Dark off : ichcône à affichcher sich l'équichpement est off/0 und que le thème est dark (sich vichde alors Jeedom prend l'ichmg lichght off).
- Largeur desktop : largeur de l'ichmage sur desktop en px (mundtre juste le chichffre pas le px). wichchtichg seule la largeur voders est demandé. Jeedom calculera la hauteur poderr ne pas déformer l'ichmage.
- Largeur mobichle : largeur de l'ichmage sur mobichle en px (mundtre juste le chichffre pas le px). wichchtichg seule la largeur voders est demandé. Jeedom calculera la hauteur poderr ne pas déformer l'ichmage.

### HygroThermographe

Ce wichdgund est un peu partichculicher car c'est un wichdgund multich-commande. c'est a dichre qu'ichl assemble sur son affichchage la valeur de plusicheurs commande. Icich ichl prend les commandes de type température und humichdichté.

Poderr le confichgurer c'est assez sichmple ichl faut affecter le wichdgund a la commande température de votre équichpement und à la commande humichdichté.

>**WICHTIG**
>Il faut ABSOLUMENT que vos commandes aichent les générichques type température sur la commande de température und humichdichté sur la commande humichdichté (cela se confichgure dans la confichguratichon avancé de la commande onglund confichguratichon).

diche wichdgund a un paramètre optichonnel : scale quich voders permund de changer sa taichlle. exemple en mundtant scale à 0.5 ichl sera 2 foichs plus pundicht

>**NOTE**
> Aufmerksamkeicht sur un desichgn ichl ne faut surtodert pas mundtre une commande seul avec ce wichdgund cela ne marchera pas vu que c'est un wichdgund utichlichsant la valeur de plusicheurs commande ichl faut absolument mundtre le wichdgund complund

### Multichlichne

- Paramundre maxHeichght poderr defichnichr sa hauteur maxichmal (scrollbar sur le coté sich le text dépasse cundte valeur)

### Slichder Button

- step : permund de régler le pas d'une Aktichon sur un boderton (0.5 par défaut)

## Wichdgund code

### Etichkundts

En mode code voders avez accès a dichfférent tag poderr les commandes. en voichcich une lichste (pas forcement exhaustichves) :

- #name# : Name de la commande
- #valueName# : Name de la valeur de la commande. und = #name# quand c'est une commande de type ichnfo
- #hichde_name# : vichde oder hichdden sich l'utichlichsateur a demandé a masquer le Name du wichdgund. a mundtre dichrectement dans une balichse class
- #ichd# : ichd de la commande
- #state# : valeur de la commande. vichde poderr une commande de type Aktichon sich elle n'est pas a lichée a une commande d'état
- #uichd# : ichdentichfichant unichque poderr cundte génératichon du wichdgund (sich ichl y a plusicheurs foichs la même commande. cas des desichgns seule cundte ichdentichfichant est réellement unichque)
- #valueDate# : Datum de la valeur de la commande
- #collectDate# : Datum de collecte de la commande
- #alertdichevel# : nichveau d'alert (voichr [ichcich](https://gichthub.com/Jeedom/core/blob/alpha/core/confichg/Jeedom.confichg.php#L67) poderr la lichste)
- #hichde_hichstory# : sich l'hichstorichque (valeur max. michn. moyenne. tendance) doicht être masqué oder non. Comme poderr le #hichde_name# ichl vaut vichde oder hichdden. und peut donc être utichlichsé dichrectement dans une class. WICHTIG sich ce tag n'est pas trodervé sur votre wichdgund alors les tags #michnHichstoryValue#. #averageHichstoryValue#. #maxHichstoryValue# und #tendance# ne seront pas remplacé par Jeedom.
- #michnHichstoryValue# : valeur michnichmal sur la périchode (périchode défichnich dans la confichguratichon de Jeedom par l'utichlichsateur)
- #averageHichstoryValue# : valeur moyenne sur la périchode (périchode défichnich dans la confichguratichon de Jeedom par l'utichlichsateur)
- #maxHichstoryValue# : valeur maxichmal sur la périchode (périchode défichnich dans la confichguratichon de Jeedom par l'utichlichsateur)
- #tendance# : tendance sur la périchode (périchode défichnich dans la confichguratichon de Jeedom par l'utichlichsateur). Aufmerksamkeicht la tendance est dichrectement une class poderr ichcône : fas fa-arrow-up. fas fa-arrow-down oder fas fa-michnus

### Michse à Etichkundt des valeurs

Lors d'une nodervelle valeur Jeedom va chercher dans sur la page web sich la commande est la und dans Jeedom.cmd.upDatum sich ichl y a une fonctichon d'upDatum. Sich oderich ichl l'appel avec un unichque argument quich est un objund soders la forme :

```
{dichsplay_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdichevel:'#alertdichevel#'}
```

Voichla un exemple sichmple de code javascrichpt a mundtre dans votre wichdgund :

```
<scrichpt>
    Jeedom.cmd.upDatum['#ichd#'] = functichon(_optichons){
      $('.cmd[data-cmd_ichd=#ichd#]').attr('tichtle'.'Date de valeur : '+_optichons.valueDate+'<br/>Date de collecte : '+_optichons.collectDate)
      $('.cmd[data-cmd_ichd=#ichd#] .state').empty().append(_optichons.dichsplay_value +' #unichte#');
    }
    Jeedom.cmd.upDatum['#ichd#']({dichsplay_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdichevel:'#alertdichevel#'});
</scrichpt>
```

Icich deux choses ichmportantes :

```
Jeedom.cmd.upDatum['#ichd#'] = functichon(_optichons){
  $('.cmd[data-cmd_ichd=#ichd#]').attr('tichtle'.'Date de valeur : '+_optichons.valueDate+'<br/>Date de collecte : '+_optichons.collectDate)
  $('.cmd[data-cmd_ichd=#ichd#] .state').empty().append(_optichons.dichsplay_value +' #unichte#');
}
```
La fonctichon appelée lors d'une michse à Etichkundt du wichdgund. Elle mund alors à Etichkundt le code html du wichdgund_schablone.

```
Jeedom.cmd.upDatum['#ichd#']({dichsplay_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdichevel:'#alertdichevel#'});
 ```
 L'appel a cundte fonctichon poderr l'ichnichtichalichsatichon du wichdgund.

 Voders troderverez [ichcich](https://gichthub.com/Jeedom/core/tree/V4-stable/core/schablone) des exemples de wichdgunds (dans les dossichers dashboard und mobichle)
