Wichtiger Teil in der Software : der Historisierungsteil, real
Erinnerung daran. In Jeedom ist es möglich, jeden zu historisieren
welcher Informationstyp Befehl (binär oder digital). Das du
ermöglicht es daher beispielsweise, eine Temperaturkurve zu historisieren,
Verbrauch oder Türöffnungen usw.

Prinzip 
========

Hier wird das Prinzip der Historisierung von Jeedom beschrieben. Es ist nicht
notwendig, um das zu verstehen, wenn Sie irgendwelche Bedenken haben
oder möchten die Einstellungen für ändern
Historisierung. Die Standardeinstellungen sind für die meisten geeignet
Fall.

Archivierung 
---------

Durch die Datenarchivierung kann Jeedom die Datenmenge reduzieren
in Erinnerung behalten. Dies ermöglicht es, nicht zu viel Platz zu verbrauchen und
das System nicht zu verlangsamen. In der Tat, wenn Sie alle behalten
Maßnahmen, dies macht umso mehr Punkte anzuzeigen und kann daher
verlängern Sie die Zeiten dramatisch, um ein Diagramm zu erstellen. Für den Fall
zu viele Punkte, es kann sogar abstürzen
Grafikanzeige.

Die Archivierung ist eine Aufgabe, die nachts beginnt und komprimiert
Daten während des Tages wiederhergestellt. Standardmäßig stellt Jeedom alle wieder her
2h ältere Daten und macht 1h Pakete (eins
Durchschnitt, Minimum oder Maximum je nach Einstellung). Also haben wir
hier 2 Parameter, einer für die Paketgröße und einer für das Wissen
wann es zu tun ist (standardmäßig sind dies Pakete
1 Stunde mit Daten, die mehr als 2 Stunden Dienstalter haben).

> **Spitze**
>
> Wenn Sie gut gefolgt sind, sollten Sie eine hohe Präzision auf dem haben
> Nur die letzten 2 Stunden. Wenn ich mich jedoch um 17 Uhr anmelde,
> Ich habe eine Klarstellung zu den letzten 17 Stunden. warum ? In der Tat
> um unnötigen Ressourcenverbrauch zu vermeiden, ist die Aufgabe, die macht
> Die Archivierung erfolgt nur einmal am Tag, abends.

> **wichtig**
>
> Dieses Archivierungsprinzip gilt natürlich nur für Bestellungen von
> digitaler Typ; Bei Befehlen vom Typ Binär behält Jeedom nicht bei
> dass die Daten der Zustandsänderung.

Anzeigen eines Diagramms 
========================

Es gibt verschiedene Möglichkeiten, auf den Verlauf zuzugreifen :

-   indem Sie einen Grafikbereich in eine Ansicht einfügen (siehe unten),

-   durch Klicken auf den gewünschten Befehl in einem Widget,

-   indem Sie zur Verlaufsseite gehen, die das Überlagern ermöglicht
    verschiedene Kurven und kombinieren Stile (Fläche, Kurve, Balken)

-   auf dem Handy, während Sie auf dem betreffenden Widget gedrückt bleiben

Wenn Sie ein Diagramm auf der Verlaufsseite oder durch Klicken auf anzeigen
Über das Widget haben Sie Zugriff auf mehrere Anzeigeoptionen :

Oben rechts finden wir den Anzeigezeitraum (hier am letzten
Woche, weil ich standardmäßig nur eine Woche haben möchte - siehe
2 Absätze oben), dann kommen die Parameter der Kurve
(Diese Parameter werden von einer Anzeige zur anderen gehalten; Sie daher
nur einmal konfigurieren).

-   **Treppe** : zeigt die Kurve als an
    Treppe oder kontinuierliche Anzeige.

-   **Veränderung** : zeigt die Wertdifferenz von an
    vorheriger Punkt.

-   **Linie** : zeigt das Diagramm als Linien an.

-   **Bereich** : Zeigt das Diagramm als Fläche an.

-   **Spalte**\* : Zeigt das Diagramm als Balken an.

Grafik zu Ansichten und Designs 
=====================================

Sie können die Grafiken auch in den Ansichten anzeigen (wir werden hier sehen
die Konfigurationsoptionen und nicht wie es geht, dafür muss man
Ansichten oder Designs basierend auf der Dokumentation rendern). hier ist
die Optionen :

Sobald eine Daten aktiviert sind, können Sie auswählen :

-   **Farbe** : die Farbe der Kurve.

-   **Typ** : die Art des Diagramms (Fläche, Linie oder Spalte).

-   **Maßstab** : da kann man mehrere kurven (daten) setzen
    Im selben Diagramm ist es möglich, die Skalen zu unterscheiden
    (droite ou gauche).

-   **Treppe** : zeigt die Kurve als an
    Treppe oder kontinuierliche Anzeige

-   **Empiler** : permet d'empiler les valeurs des courbes (voir en
    dessous pour le résultat).

-   **Veränderung** : zeigt die Wertdifferenz von an
    vorheriger Punkt.

Option sur la page d'historique 
===============================

La page d'historique donne accès à quelques options supplémentaires

Historique calculé 
------------------

Permet d'afficher une courbe en fonction d'un calcul sur plusieurs
commande (vous pouvez à peu prêt tout faire, +-/\* valeur absolue…​ voir
documentation PHP pour certaines fonctions). ex :
abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de
vie\]\[Hygrométrie\]\[Température\]*)

Vous avez aussi accès à un gestion de formules de calcul qui vous permet
de les sauvegarder pour les réafficher plus facilement

> **Spitze**
>
> Il suffit de cliquer sur le nom de l'objet pour le déplier ;
> apparaissent les commandes historisées qui peuvent être graphées.

Historique de commande 
----------------------

Devant chaque donnée pouvant être graphée, vous retrouvez deux icônes :

-   **Poubelle** : permet de supprimer les données enregistrées ; lors
    du clic, Jeedom demande s'il faut supprimer les données avant une
    certaine date ou toutes les données.

-   **Flèche** : permet d'avoir un export CSV des données historisées.

Suppression de valeur incohérente 
=================================

Parfois, il se peut que vous ayez des valeurs incohérentes sur les
graphiques. Cela est souvent dû à un souci d'interprétation de la
valeur. Il est possible de supprimer ou changer la valeur du point en
question, en cliquant sur celui-ci directement sur le graphique ; de
plus, vous pouvez régler le minimum et le maximum autorisés afin
d'éviter des problèmes futurs.

Timeline 
========

La timeline affiche certains événements de votre domotique sous forme
chronologique.

Pour les voir, il vous faut d'abord activer le suivi sur la timeline des
commandes ou scénarios voulus :

-   **Scenario** : soit directement sur la page de scénario, soit sur la
    page de résumé des scénarios pour le faire en "masse"

-   **bestellen** : soit dans la configuration avancée de la commande,
    soit dans la configuration de l'historique pour le faire en "masse"

> **Spitze**
>
> Vous avez accès aux fenêtres de résumé des scénarios ou de la
> configuration de l'historique directement à partir de la page de
> timeline.

Une fois que vous avez activé le suivi dans la timeline des commandes et
scénarios voulus, vous pourrez voir apparaître ceux-ci sur la timeline.

> **wichtig**
>
> Il faut attendre de nouveaux événements après avoir activé le suivi
> sur la timeline avant de les voir apparaître.

Les cartes sur la timeline affichent :

-   **bestellen action** : en fond rouge, une icône à droite vous permet
    d'afficher la fenêtre de configuration avancée de la commande

-   **bestellen info** : en fond bleu, une icône à droite vous permet
    d'afficher la fenêtre de configuration avancée de la commande

-   **Szenario** : en fond gris, vous avez 2 icônes : une pour afficher
    le log du scénario et une pour aller sur le scénario


