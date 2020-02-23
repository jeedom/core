# Resumen de automatización del hogar
**Análisis → Resumen de domótica**

Esta página le permite reunir en una sola página los diferentes elementos configurados en su Jeedom. También da acceso a funciones para organizar equipos y controles, a su configuración avanzada y para mostrar las posibilidentificaciónades de configuración..

## información

En la parte superior de la página, encontramos :
- **Numero de objetos** : Número total de objetos configurados en nuestro Jeedom, incluidentificaciónos elementos inactivos.
- **Numero de equipos** : Lo mismo para el equipo..
- **Numero de ordenes** : Lo mismo para pedidentificaciónos.
- **inactivo** : Marque esta casilla si desea que se muestren elementos inactivos en esta página.
- **Buscar** : Buscar un artículo en particular. Puede ser el apellidentificaciónobre de un dispositivo, un pedidentificacióno o el apellidentificaciónobre del complemento por el cual se creó el dispositivo.
- **Exportación CSV** : la permite exportar todos los objetos, equipos y sus comandos a un archivo CSV.

También tienes una pestaña **histórico**, muestra el historial de pedidentificaciónos, equipos, objetos, vistas, diseño, diseño 3D, escenarios y usuarios eliminados.

## Marcos de objetos

Debajo hay un cuadro por objeto. En cada cuadro, encontramos la lista de equipos que tienen este objeto como padre.
El primer cuadro **no** representa dispositivos que no tienen padre asignado.

Para cada objeto, junto a su etiqueta, hay dos botones disponibles..
- El primero se usa para abrir la página de configuración de objetos en una pestaña nueva.
- El segundo proporciona información sobre el objeto,

> **punta**
>
> El color de fondo de los marcos del objeto depende del color elegidentificacióno en la configuración del objeto..

> **punta**
>
> Con un clic y soltar objetos o equipos, puede cambiar su orden o incluso asignarlos a otro objeto. Es a partir del orden establecidentificacióno en esta página que se calcula la visualización del Tablero.

## Los equipos

En cada equipo encontramos :

- una **casilla de verificación** para seleccionar el equipo (puede seleccionar varios). Si se selecciona al menos un dispositivo, tiene botones de acción que aparecen en la esquina superior izquierda para **remove**hacer **visible**/**invisible**,  **bienes**/**inactivo** equipo seleccionado.
- la**identificación** equipo.
- la **tipo** equipo : Identificador del complemento al que pertenece.
- la **apellidentificacióno** equipo.
- **inactivo** (cruz pequeña) : Significa que el equipo está inactivo (si no está allí, el equipo está activo).
- **invisible** (ojo tachado) : Significa que el equipo es invisible (si no está allí, el equipo es visible).

Si el complemento del equipo está desactivado, los dos íconos a la derecha no aparecen:
- **Enlace externo** (cuadrado con flecha) : Permite abrir en una nueva pestaña la página de configuración del equipo.
- **Configuración avanzada** (rueda dentada) : abre la ventana de configuración avanzada del equipo.

> Al hacer clic en la línea que contiene el apellidentificaciónobre del equipo, mostrará todos los comandos para este equipo. Al hacer clic en un pedidentificacióno, accederá a la ventana de configuración del pedidentificacióno.

## Configuración avanzada de equipos

> **punta**
>
> Es posible acceder (si el complemento lo admite) directamente a esta ventana desde la página de configuración del equipo haciendo clic en el botón de configuración avanzada

La ventana de **configuración avanzada de equipos** permite modificarlo. Primero, en la parte superior derecha, algunos botones disponibles :

- **información** : muestra las propiedades en bruto del equipo.
- **Vínculos** : la permite mostrar los enlaces del equipo con los objetos, comandos, escenarios, variables, interacciones ... en forma gráfica (en este caso, un doble clic en un elemento lo llevará a su configuración).
- **registro** : muestra los eventos del equipo en cuestión.
- **Guardar** : Guarde las modificaciones realizadas en el equipo..
- **remove** : Retirar equipo.

### Pestaña de información

la pestaña **información** contiene información general sobre el equipo y sus controles :

- **identificación** : Identificador único en la base de datos Jeedom.
- **apellidentificacióno** : apellidentificaciónobre del equipo.
- **identificación lógica** : Identificador de equipo lógico (puede estar vacío).
- **identificación de objeto** : Identificador único del objeto padre (puede estar vacío).
- **Fecha de creación** : Fecha de creación del equipo.
- **Activar** : Marque la casilla para activar el equipo (no olvidentificacióne guardar).
- **visible** : Marque la casilla para hacer visible el equipo (no olvidentificacióne guardar).
- **tipo** : Identificador del complemento por el cual fue creado.
- **Intento fallidentificacióno** : Número de intentos fallidentificaciónos de comunicaciones consecutivas con el equipo.
- **Fecha de la última comunicación** : Fecha de la última comunicación del equipo..
- **última actualización** : Fecha de la última comunicación con el equipo..
- **etiquetas** : etiquetas de equipo, separadas por &#39;,&#39;. Permite en el tablero hacer filtros personalizados

A continuación encontrará una tabla con la lista de comandos de equipos con, para cada uno, un enlace a su configuración.

### Ver pestaña

En la pestaña **Viendo**, podrá configurar ciertos comportamientos de visualización de mosaico en el Tablero o en el dispositivo móvil.

#### Reproductor

-  **visible** : Marque la casilla para hacer visible el equipo.
- **Mostrar apellidentificaciónobre** : Marque la casilla para mostrar el apellidentificaciónobre del equipo en el mosaico.
- **Mostrar apellidentificaciónobre de objeto** : Marque la casilla para mostrar el apellidentificaciónobre del objeto principal del equipo, junto al mosaico.

### Parámetros opcionales en el mosaico

A continuación, hay parámetros de visualización opcionales que se pueden aplicar al equipo.. Estos parámetros están compuestos de un apellidentificaciónobre y un valor.. Il suffit de cliquer sur **añadir** por en appliquer un
noveau. Por les équipements, seule la valeur **style** est por le moment utilisée, elle permet d'insérer du code CSS sur l'équipement en question.

> **punta**
>
> N'obliez pas de sauvegarder après tote modification.

### Onglet Disposition

Cette partie vos permet de choisir entre la disposition standard des commandes (côte à côte dans le widentificaciónget), o en mode tableau. Il n'y a rien à régler en mode par défaut. Voici les options disponibles en mode
**mesa** :
- **apellidentificaciónobre de lignes**
- **apellidentificaciónobre de colonnes**
- **Centrer dans les cases** : Cochez la case por centrer les commandes dans les cases.
- **Style générale des cases (CSS)** : Permet de définir le style général en code CSS.
- **Style du tableau (CSS)** : Permet de définir le style du tableau uniquement.

En dessos por chaque case, la **configuration détaillée** vos permet
ceci :
- **Texte de la case** : añadir un texte en plus de la commande (o tot seul, si il n'y a pas de commande dans la case).
- **Style de la case (CSS)** : Modifier le style CSS spécifique de la case (attention celui-ci écrase et remplace le CSS général des cases).

> **punta**
>
> Dans une case du tableau, si vos volez mettre 2 commandes l'une en dessos de l'autre, il ne faut pas oblier de rajoter un retor à la ligne après la première dans la **configuration avancée** de celle-ci.

### Onglet Alertes

Cet onglet permet d'avoir les informations sur la batterie equipo et de définir des alertes par rapport à celle-ci. Voici les tipos d'informations que l'on peut trover :

- **tipo de pile**,
- **Dernière remontée de l'information**,
- **Niveau restant**, (si bien sûr votre équipement fonctionne sur pile).

Dessos, vos porrez aussi définir les seuils spécifiques d'alerte de batterie por cet équipement. Si vos laissez les cases videntificaciónes, ceux sont les seuils par défaut qui seront appliqués.

On peut également gérer le timeot, en minutes, equipo. Par exemple, 30 indique à jeedom que si l'équipement n'a pas communiqué depuis 30 minutes, alors il faut le mettre en alerte.

> **punta**
>
> las paramètres globaux sont dans **Réglages→Système→Configuration : troncos** o **Equipements**

### Onglet Commentaire

Permet d'écrire un commentaire à propos equipo.

## Configuración avanzada d'une commande

Primero, en la parte superior derecha, algunos botones disponibles :

- **Tester** : Permet de tester la commande.
- **Vínculos** : Permet d'afficher les liens equipo avec les objets, commandes, scénarios, variables, interaccións…​. sos forme graphique.
- **registro** : Affiche les évènements equipo en question.
- **información** : Affiche les propriétés brutes equipo.
-  **Appliquer à** : Permet d'appliquer la même configuration sur plusieurs commandes.
- **Guardar** : Guarde las modificaciones realizadas en el equipo..

> **punta**
>
> Dans un graphique, un doble clic sur un élément vos amène à sa configuration.

> **nota**
>
> En fonction du tipo de commande, les informations/accións affichées peuvent changer.

### Pestaña de información

la pestaña **información** contient les informations générales sur la commande :

- **identificación** : Identifiant unique dans la base de données.
- **identificación lógica** : Identifiant logique de la commande (peut être videntificacióne).
- **apellidentificacióno** : apellidentificacióno de la commande.
- **tipo** : tipo de la commande (acción o info).
- **Sos-tipo** : Sos-tipo de la commande (binaire, numérique…​).
- **URL directe** : Fornit l'URL por accéder à cet équipement. (clic droit, copier l'adresse du lien) laURL lancera la commande por une **acción** et retornera l'information por une **info**.
- **unidentificaciónad** : unidentificaciónad de la commande.
- **Commande déclenchant une mise à jor** : Donne l'identificaciónentifiant d'une  autre commande qui, si cette autre commande change, va forcer la mise à jor de la commande visualisée.
- **visible** : Cochez cette case por que la commande soit visible.
- **Suivre dans la timeline** : Cochez cette case por que cette commande soit visible dans la timeline quand elle est utilisée. Vos povez préciser une timeline en particulier dans le champ qui s'affiche si l'option est cochée.
- **Interdire dans les interaccións automatique** : interdit les interaccións automatique sur cette commande
- **icono** : Permet de changer l'icône de la commande.

Vos avez aussi trois autres botons oranges en dessos :

- **Cette commande remplace l'identificación** : Permet de remplacer un identificación de commande par la commande en question. Utile si vos avez supprimé un équipement dans Jeedom et que vos avez des scénarios qui utilisent des commandes de celui-ci.
- **Cette commande remplace la commande** : Remplace une commande par la commande corante.
- **Remplacer cette commande par la commande** : lainverse, remplace la commande par une autre commande.

> **nota**
>
> Ce genre d'acción remplace les commandes partot dans Jeedom (scénario, interacción, commande, équipement…​.).

En-dessos, vos retrovez la liste des différents équipements, commandes, scénarios o interaccións qui utilisent cette commande. Un clic dessus permet d'aller directement sur leur configuration respective.

### Onglet Configuration

#### Por une commande de tipo info :

- **Calcul et arrondi**
    - **Formule de calcul (\#value\# por la valeur)** : Permet de faire une opération sur la valeur de la commande avant le traitement par Jeedom, exemple : `#value# - 0.2` por retrancher 0.2 (offset sur un capteur de température).
    - **Arrondi (chiffre après la virgule)** : Permet d'arrondir la valeur de la commande (Exemple : mettre 2 por transformer 16.643345 en 16.64).
- **tipo générique** : Permet de configurer le tipo générique de la commande (Jeedom essaie de le trover par lui-même en mode auto). Cette information est utilisée par l'application mobile.
- **Action sur la valeur, si** : Permet de faire des sortes de mini scénarios. Vos povez, par exemple, dire que si la valeur vaut plus de 50 pendant 3 minutes, alors il faut faire telle acción. Cela permet, par exemple, d'éteindre une lumière X minutes après que celle-ci se soit allumée.

- **histórico**
    - **Historiser** : Cochez la case por que les valeurs de cette  commande soient historisées. (Voir **Analyse→histórico**)
    - **Mode de lissage** : Mode de **lissage** o d'**archivage** permet de choisir la manière d'archiver la donnée. Par défaut, c'est une **moyenne**. Il est aussi possible de choisir le **maximum**, le **minimum**, o **aucun**. **aucun** permet de dire à Jeedom qu'il ne doit pas réaliser d'archivage sur cette  commande (aussi bien sur la première période des 5 mins qu'avec la tâche d'archivage). Cette option est peligroeuse car Jeedom        conserve tot : il va donc y avoir beaucop plus de données conservées.
    - **Purger l'historique si plus vieux de** : Permet de dire à Jeedom de remove totes les données plus vieilles qu'une certaine période. Peut être pratique por ne pas conserver de données si ça n'est pas nécessaire et donc limiter la quantité d'informations enregistrées par Jeedom.

- **Gestion des valeurs**
    - **Valeur interdite** : Si la commande prend une de ces valeurs,  Jeedom l'ignore avant de l'appliquer.
    - **Valeur retor d'état** : Permet de faire revenir la commande à cette valeur après un certain temps.
    - **Durée avant retor d'état (min)** : Temps avant le retor à la valeur ci-dessus.

- **Autres**
    - **Gestion de la répétition des valeurs** : En automatique si la commande remonte 2 fois la même valeur d'affilée, alors Jeedom ne prendra pas en compte la 2eme remontée (évite de déclencher plusieurs fois un scénario, sauf si la commande est de tipo binaire). Vos povez forcer la répétition de la valeur o l'interdire complètement.
    - **Push URL** : Permet de rajoter une URL à appeler en cas de mise à jor de la commande. Vos povez utiliser les tags suivant : `#value#` por la valeur de la commande, `#cmd_name#` por le apellidentificacióno de la commande, `#cmd_identificación#` por l'identificaciónentifiant unique de la commande, `#humanname#` por le apellidentificacióno complet de la commande       (ex : `#[Salle de bain][Hydrometrie][Humidentificaciónité]#`), `#eq_name#` por le apellidentificacióno equipo.

#### Por une commande acción :

-  **tipo générique** : Permet de configurer le tipo générique de la commande (Jeedom essaie de le trover par lui-même en mode auto). Cette information est utilisée par l'application mobile.
- **Confirmer l'acción** : Cochez cette case por que Jeedom demande une confirmation quand l'acción est lancée à partir de l'interface de cette commande.
- **Código de acceso** : Permet de définir un code que Jeedom demandera quand l'acción est lancée à partir de l'interface de cette commande.
- **Action avant exécution de la commande** : Permet d'ajoter des commandes **avant** chaque exécution de la commande.
- **Action après exécution de la commande** : Permet d'ajoter des commandes **après** chaque exécution de la commande.

### Onglet Alertes

Permet de définir un niveau d'alerte (**advertencia** o **peligro**) en fonction de certaines conditions. Par exemple, si `value > 8` pendant 30 minutes alors l'équipement peut passer en alerte **advertencia**.

> **nota**
>
> Sur la page **Réglages→Système→Configuration : troncos**, vos povez configurer une commande de tipo message qui permettra à Jeedom de vos prévenir si on atteint le seuil advertencia o peligro.

### Ver pestaña

Dans cette partie, vos allez povoir configurer certains comportements d'affichage du widentificaciónget sur le Dashboard, les vues, le design et en mobile.

- **Reproductor** : Permet de choisir le widentificaciónget sur dekstop o mobile (à noter qu'il faut le plugin widentificaciónget et que vos povez le faire aussi à partir de celui-ci).
- **visible** : Cochez por rendre visible la commande.
- **Mostrar apellidentificaciónobre** : Cochez por rendre visible le apellidentificacióno de la commande, en fonction du contexte.
- **Mostrar apellidentificaciónobre et l'icône** : Cochez por rendre visible l'icône en plus du apellidentificacióno de la commande.
- **Retor à la ligne forcé avant le widentificaciónget** : Cochez **avant le  widentificaciónget** o **après le widentificaciónget** por ajoter un retor à la ligne avant o après le widentificaciónget (por forcer par exemple un affichage en colonne des différentes commandes equipo au lieu de lignes par défaut)

En-dessos, on retrove des paramètres optionnels d'affichage que l'on peut passer au widentificaciónget. Ces paramètres dépendent du widentificaciónget en question, il faut donc regarder sa fiche sur le Market por les connaître.

> **punta**
>
> N'obliez pas de sauvegarder après tote modification.
