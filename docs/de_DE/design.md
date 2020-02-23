# Design
**Accueil → Design**

Cette page permet de configurer l'affichage de toute votre domotique de manière très fine.
Celle-ci demande du temps mais sa seule limite est votre imagination.

> **Spitze**
>
> Il est possible d'aller directement sur un design grâce au sous-menu.

> **wichtig**
>
> Toutes les actions se font par un clic droit sur cette page, attention à bien le faire dans le design. Lors de la création, il faut donc le faire au milieu de la page (pour être sûr d'être sur le design).

Dans le menu (clic droit), nous retrouvons les actions suivantes :

- **Designs** : Affiche la liste de vos designs et permet d'y accéder.
- **Edition** : Permet de passer en mode édition.
- **Plein écran** : Permet d'utiliser toute la page Internet, ce qui enlèvera le menu de Jeedom du haut.
- **Ajouter graphique** : Permet d'ajouter un graphique.
- **Ajouter texte/html** : Permet d'ajouter du texte ou du code html/JavaScript.
- **Ajouter scénario** : Permet d'ajouter un scénario.
- **Ajouter lien**
    - **Vers une vue** : Permet d'ajouter un lien vers une vue.
    - **Vers un design** : Permet d'ajouter un lien vers un autre design.
- **Ajouter équipement** : Permet d'ajouter un équipement.
- **Ajouter commande** : Permet d'ajouter une commande.
- **Ajouter image/caméra** : Permet d'ajouter une image ou le flux d'une caméra.
- **Ajouter zone** : Permet d'ajouter une zone transparente cliquable qui pourra exécuter une série d'actions lors d'un clic (en fonction ou non de l'état d'une autre commande).
- **Ajouter résumé** : Ajoute les informations d'un résumé d'objet ou général.
- **Anzeigen**
    - **Aucune** : N'affiche aucune grille.
    - **10x10** : Affiche une grille de 10 par 10.
    - **15x15** : Affiche une grille de 15 par 15.
    - **20x20** : Affiche une grille de 20 par 20.
    - **Aimanter les éléments** : Ajoute une aimantation entre les éléments pour permettre de les coller plus facilement.
    - **Aimanter à la grille** : Fügen Sie dem Raster eine Magnetisierung der Elemente hinzu (Aufmerksamkeit : Je nach Zoom des Elements kann diese Funktionalität mehr oder weniger funktionieren..
    - **Hervorheben von Elementen ausblenden** : Hervorheben von Elementen ausblenden.
- **Design löschen** : Design entfernen.
- **Erstellen Sie ein Design** : Ermöglicht das Hinzufügen eines neuen Designs.
- **Doppeltes Design** : Dupliziert das aktuelle Design.
- **Konfigurieren Sie das Design** : Zugriff auf die Konfiguration des Designs.
- **speichern** : Speichern Sie das Design (beachten Sie, dass bei bestimmten Aktionen auch automatische Sicherungen durchgeführt werden)..

> **wichtig**
>
> Die Konfiguration der Designelemente erfolgt durch Klicken.

## Designkonfiguration

Hier gefunden :

- **General**
    - **Name** : Der Name Ihres Designs.
    - **Position** : Die Position des Designs im Menü. Ermöglicht die Bestellung der Designs.
    - **Transparenter Hintergrund** : Macht den Hintergrund transparent. Achtung Wenn das Kontrollkästchen aktiviert ist, wird die Hintergrundfarbe nicht verwendet.
    - **Hintergrundfarbe** : Design Hintergrundfarbe.
    - **Zugangscode* : Zugriffscode für Ihr Design (falls leer, ist kein Code erforderlich).
    - **Symbol** : Ein Symbol dafür (erscheint im Designauswahlmenü).
    - **Bild**
        - **send** : Ermöglicht das Hinzufügen eines Hintergrundbilds zum Design.
        - **Bild löschen** : Bild löschen.
- **Größen**
    - **Größe (BxH)** : Ermöglicht das Festlegen der Größe Ihres Designs in Pixel.

## Allgemeine Konfiguration der Elemente

> **Notiz**
>
> Je nach Art des Elements können sich die Optionen ändern.

### Allgemeine Anzeigeeinstellungen

- **Tiefe** : Ermöglicht die Auswahl der Tiefenstufe
- **Position X (%)** : Horizontale Koordinate des Elements.
- **Position Y (%)** : Vertikale Koordinate des Elements.
- **Breite (px)** : Elementbreite in Pixel.
- **Höhe (px)** : Elementhöhe in Pixel.

### Entfernen

Gegenstand entfernen

### Duplikat

Ermöglicht das Duplizieren des Elements

### Schloss

Ermöglicht das Sperren des Elements, sodass es nicht mehr beweglich oder in der Größe veränderbar ist.

## Grafik

### Spezifische Anzeigeeinstellungen

- **Zeit** : Hier können Sie den Anzeigezeitraum auswählen
- **Bildunterschrift anzeigen** : Zeigt die Legende an.
- **Browser anzeigen** : Zeigt den Browser an (zweite hellere Grafik unter der ersten).
- **Periodenauswahl anzeigen** : Zeigt den Periodenwähler oben links an.
- **Bildlaufleiste anzeigen** : Zeigt die Bildlaufleiste an.
- **Transparenter Hintergrund** : Macht den Hintergrund transparent.
- **Grenze** : Ermöglicht das Hinzufügen eines Rahmens. Achten Sie darauf, dass die Syntax HTML ist (seien Sie vorsichtig, Sie müssen beispielsweise die CSS-Syntax verwenden : festes 1px schwarz).

### Erweiterte Konfiguration

Hier können Sie die Befehle auswählen, die erfasst werden sollen.

## Text / html

### Spezifische Anzeigeeinstellungen

- **Symbol** : Symbol vor dem Designnamen.
- **Hintergrundfarbe** : Mit dieser Option können Sie die Hintergrundfarbe ändern oder transparent machen. Vergessen Sie nicht, &quot;Standard&quot; auf NEIN zu ändern.
- **Textfarbe** : Mit dieser Option können Sie die Farbe von Symbolen und Textn ändern (achten Sie darauf, die Standardeinstellung auf Nein zu setzen)...
- **Rund um die Winkel** : erlaubt das Abrunden der Winkel (vergessen Sie nicht,%, ex 50% zu setzen).
- **Grenze** : Wenn Sie einen Rahmen hinzufügen möchten, achten Sie darauf, dass die Syntax HTML ist (Sie müssen beispielsweise die CSS-Syntax verwenden : festes 1px schwarz).
- **Schriftgröße** : ermöglicht es Ihnen, die Schriftgröße zu ändern (ab 50% müssen Sie das% -Zeichen setzen).
- **Textausrichtung** : ermöglicht die Auswahl der Ausrichtung des Texts (links / rechts / zentriert).
- **Fett** : fetter Text.
- **Text** : Text im HTML-Code, der sich im Element befindet.

> **wichtig**
>
> Wenn Sie HTML-Code (insbesondere Javascript) einfügen, überprüfen Sie diesen sorgfältig, da dies möglich ist, wenn ein Fehler vorliegt oder wenn eine Jeedom-Komponente überschrieben wird, das Design vollständig abstürzt und nur gelöscht werden muss direkt in die Datenbank.

## Szenario

*Keine spezifischen Anzeigeeinstellungen*

## Link

### Spezifische Anzeigeeinstellungen

- **Name** : Name des Links (angezeigter Text).
- **Link** : Link zum betreffenden Design oder zur betreffenden Ansicht.
- **Hintergrundfarbe** : Ermöglicht es Ihnen, die Hintergrundfarbe zu ändern oder transparent zu machen. Vergessen Sie nicht, &quot;Standard&quot; auf NEIN zu ändern.
- **Textfarbe** : Permet de changer la couleur des icônes et des textes (attention à bien passer Défaut sur Non).
- **Rund um die Winkel (ne pas oublier de mettre %, ex 50%)** : Permet d'arrondir les angles, ne pas oublier de mettre le %.
- **Grenze (attention syntaxe CSS, ex : festes 1px schwarz)** : Permet d'ajouter une bordure, attention la syntaxe est HTML.
- **Schriftgröße (ex 50%, il faut bien mettre le signe %)** : Permet de modifier la taille de la police.
- **Textausrichtung** : Permet de choisir l'alignement du texte (gauche/droit/centré).
- **Fett** : Met le texte en gras.

## Ausrüstung

### Spezifische Anzeigeeinstellungen

- **Afficher le nom de l'objet** : Cocher pour afficher le nom de l'objet parent de l'équipement.
- **Masquer le nom** : Cocher pour masquer le nom de l'équipement.
- **Hintergrundfarbe** : Permet de choisir une couleur de fond personnalisée, ou d'afficher l'équipement avec un fond transparent, ou d'utiliser la couleur par défaut.
- **Textfarbe** : Permet de choisir une couleur de fond personnalisée, ou d'utiliser la couleur par défaut.
- **Arrondis** : Valeur en pixels de l'arrondis des angles de la tuile de l'équipement.
- **Grenze** : Définition CSS d'une bordure de la tuile de l'équipement. ex : 1px solid black.
- **Opacité** : Opacité de la tuile de l'équipement, entre 0 et 1. Aufmerksamkeit : une couleur de fond doit être définie.
- **CSS personnalisé** : Règles CSS à appliquer sur l'équipement.
- **Appliqué le css personnalisé sur** : Sélecteur CSS sur lequel appliquer le CSS personnalisé.

### Befehle

La liste des commandes présentes sur l'équipement vous permet, pour chaque commande, de:
- Masquer le nom de la commande.
- Masquer la commande.
- Afficher la commande avec une fond transparent.

### Erweiterte Konfiguration

Affiche la fenêtre de configuration avancée de l'équipement (voir documentation **Zusammenfassung der Hausautomation**).

## bestellen

*Keine spezifischen Anzeigeeinstellungen*

### Erweiterte Konfiguration

Affiche la fenêtre de configuration avancée de l'équipement (voir documentation **Zusammenfassung der Hausautomation**).

## Bild/Caméra

### Spezifische Anzeigeeinstellungen

- **Afficher** : Définit ce que vous voulez afficher, image fixe ou flux d'une caméra.
- **Bild** : Permet d'envoyer l'image en question (si vous avez choisi une image).
- **Caméra** : Caméra à afficher (si vous avez choisi caméra).

## Zone

### Spezifische Anzeigeeinstellungen

- **Type de zone** : C'est ici que vous choisissez le type de la zone : Macro simple, Macro Binaire ou Widget au survol.

### Macro simple

Dans ce mode là, un clic sur la zone exécute une ou plusieurs actions.

Il vous suffit ici d'indiquer la liste des actions à faire lors du clic sur la zone.

### Macro binaire

Dans ce mode, Jeedom va exécuter la ou les actions On ou Off en fonction de l'état de la commande que vous donnez. ex : si la commande vaut 0 alors Jeedom exécutera la ou les actions On sinon il exécutera la ou les actions Off

- **Information binaire** : bestellen donnant l'état à vérifier pour décider de l'action à faire (On ou Off).

Il vous suffit en dessous de mettre les actions à faire pour le On et pour le Off.

### Widget au survol

Dans ce mode, lors du survol ou du clic dans la zone Jeedom, vous afficherez le widget en question.

- **Ausrüstung** : Widget à afficher lors du survol ou du clic.
- **Afficher au survol** : Si coché, affiche le widget au survol.
- **Afficher sur un clic** : Si coché, alors le widget est affiché au clic.
- **Position** : Permet de choisir l'emplacement d'apparition du widget (par défaut bas droite).

## Zusammenfassung

### Spezifische Anzeigeeinstellungen

- **Link** : Permet d'indiquer le résumé à afficher (General pour le global sinon indiquer l'objet).
- **Hintergrundfarbe** : Ermöglicht es Ihnen, die Hintergrundfarbe zu ändern oder transparent zu machen. Vergessen Sie nicht, &quot;Standard&quot; auf NEIN zu ändern.
- **Textfarbe** : Permet de changer la couleur des icônes et des textes (attention à bien passer Défaut sur Non).
- **Rund um die Winkel (ne pas oublier de mettre %, ex 50%)** : Permet d'arrondir les angles, ne pas oublier de mettre le %.
- **Grenze (attention syntaxe CSS, ex : festes 1px schwarz)** : Permet d'ajouter une bordure, attention la syntaxe est HTML.
- **Schriftgröße (ex 50%, il faut bien mettre le signe %)** : Permet de modifier la taille de la police.
- **Fett** : Met le texte en gras.

## FAQ

>**Je n'arrive plus à éditer mon design**
>Si vous avez mis un widget ou une image qui prend quasiment la totalité du design, il faut bien cliquer en dehors du widget ou de l'image pour avoir accès au menu par clic droit.

>**Entfernen un design qui ne marche plus**
>Dans la partie administration puis OS/DB, faire "select * from planHeader", récupérer l'id du design en question et faire un "delete from planHeader where id=#TODO#" et "delete from plan where planHeader_id=#todo#" en remplaçant bien #TODO# par l'id du design trouvé précédemment.
