# Types d'équipement
**Outils → Types d'équipement**

Les capteurs et actionneurs dans Jeedom sont gérés par des plugins, qui créent des équipements avec des commandes *Info* (capteur) ou *Action* (actionneur). Ce qui permet ensuite de déclencher des actions en fonctions du changement de certains capteurs, comme allumer une lumière sur une détection de mouvement. Mais le Core de Jeedom, et des plugins comme *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa* etc., ne savent pas ce que sont ces équipements : Une prise, une lumière, un volet, etc.

Pour palier à ce problème, notamment avec les assistants vocaux (*Allume la lumière de la salle*), le Core a introduit il y a quelques années les **Types Génériques**, utilisés par ces plugins.

Cela permet ainsi d'identifier un équipement par *La lumière de la salle* par exemple.

Les Types Génériques sont également intégrés dans les scénarios. Vous pouvez ainsi déclencher un scénario si une lampe s'allume dans une pièce, si un mouvement est détecté dans la maison, éteindre toutes les lumières ou fermer tous les volets avec une seule action, etc. De plus, si vous ajoutez un équipement, vous n'avez qu'à indiquer ces types, il ne sera pas nécessaire de retoucher de tels scénarios.

Le paramétrage des ces Types Génériques peut se faire directement dans certains plugins, ou par commande dans *Configuration avancée* de celle-ci.

Cette page permet de paramétrer ces Types Génériques directement, de manière plus directe et plus simple, et propose même une assignation automatique une fois les équipements assignés correctement.

![Types d'équipement](./images/coreGenerics.gif)

## Type d'équipement

Cette page propose un rangement par type d'équipement : Prise, Lumière, Volet, Thermostat, Camera, etc. Au départ, la plupart de vos équipements seront classés dans **Equipements sans type**. Pour leur assigner un type, vous pouvez soit les déplacer dans un autre type, soit faire un clic droit sur l'équipement pour le déplacer directement.

> **Tip**
>
> - Quand vous déplacer un équipement dans la partie **Equipements sans type**, Jeedom vous propose de supprimer les Types Génériques sur ses commandes.
> - Vous pouvez déplacer plusieurs équipements d'un coup en cochant les cases à cocher à gauche de ceux-ci.

## Type de commande

Une fois un équipement positionné dans le bon *Type*, en cliquant dessus vous accédez à la liste de ses commandes, colorées différemment si c'est une *Info* (Bleue) ou une *Action* (Orange).

Au clic droit sur une commande, vous pouvez alors lui attribuer un Type Générique correspond aux spécifiées de cette commande (type Info/Action, sous-type Numérique, Binaire, etc).

> **Tip**
>
> - Le menu contextuel des commandes affiche le type de l'équipement en caractères gras, mais permet tout de même d'attribuer n'importe quel Type Générique de n'importe quel type d'équipement.

Sur chaque équipement, vous avez deux boutons :

- **Types Auto** : Cette fonction ouvre une fenêtre vous proposant les Types Génériques appropriés en fonction du type de l'équipement, des spécificités de la commande, et de son nom. Vous pouvez alors ajuster les propositions et décocher l'application à certaines commandes avant d'accepter ou pas. Cette fonction est compatible avec la sélection par les cases à cocher.

- **Reset types** : Cette fonction supprime les Types Génériques de toutes les commandes de l'équipement.

> **Attention**
>
> Aucun changement n'est effectué avant de sauvegarder, avec le bouton en haut à droite de la page.


## Liste des Types génériques du Core

| **Autre (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Minuteur Etat (Non géré par Application Mobile) | Info | numeric
| TIMER_STATE | Minuteur Etat (pause ou non) (Non géré par Application Mobile) | Info | binary, numeric
| SET_TIMER | Minuteur (Non géré par Application Mobile) | Action | slider
| TIMER_PAUSE | Minuteur pause (Non géré par Application Mobile) | Action | other
| TIMER_RESUME | Minuteur reprendre (Non géré par Application Mobile) | Action | other

| **Batterie (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERY | Batterie (Non géré par Application Mobile) | Info | numeric
| BATTERY_CHARGING | Batterie en charge (Non géré par Application Mobile) | Info | binary

| **Caméra (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | URL caméra | Info | string
| CAMERA_RECORD_STATE | État enregistrement caméra | Info | binary
| CAMERA_UP | Mouvement caméra vers le haut | Action | other
| CAMERA_DOWN | Mouvement caméra vers le bas | Action | other
| CAMERA_LEFT | Mouvement caméra vers la gauche | Action | other
| CAMERA_RIGHT | Mouvement caméra vers la droite | Action | other
| CAMERA_ZOOM | Zoom caméra vers l'avant | Action | other
| CAMERA_DEZOOM | Zoom caméra vers l'arrière | Action | other
| CAMERA_STOP | Stop caméra | Action | other
| CAMERA_PRESET | Preset caméra | Action | other
| CAMERA_RECORD | Enregistrement caméra | Action |
| CAMERA_TAKE | Snapshot caméra | Action |

| **Chauffage (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Chauffage fil pilote Etat | Info | binary
| HEATING_ON | Chauffage fil pilote Bouton ON | Action | other
| HEATING_OFF | Chauffage fil pilote Bouton OFF | Action | other
| HEATING_OTHER | Chauffage fil pilote Bouton | Action | other

| **Electricité (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POWER | Puissance Electrique | Info | numeric
| CONSUMPTION | Consommation Electrique (Non géré par Application Mobile) | Info | numeric
| VOLTAGE | Tension (Non géré par Application Mobile) | Info | numeric
| REBOOT | Redémarrage (Non géré par Application Mobile) | Action | other

| **Environnement (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURE | Température | Info | numeric
| AIR_QUALITY | Qualité de l'air | Info | numeric
| BRIGHTNESS | Luminosité | Info | numeric
| PRESENCE | Présence | Info | binary
| SMOKE | Détection de fumée | Info | binary
| HUMIDITY | Humidité | Info | numeric
| UV | UV (Non géré par Application Mobile) | Info | numeric
| CO2 | CO2 (ppm) (Non géré par Application Mobile) | Info | numeric
| CO | CO (ppm) (Non géré par Application Mobile) | Info | numeric
| NOISE | Son (dB) (Non géré par Application Mobile) | Info | numeric
| PRESSURE | Pression (Non géré par Application Mobile) | Info | numeric
| WATER_LEAK | Fuite d'eau (Non géré par Application Mobile) | Info |
| FILTER_CLEAN_STATE | Etat du filtre (Non géré par Application Mobile) | Info | binary

| **Generic (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DEPTH | Profondeur | Info | numeric
| DISTANCE | Distance | Info | numeric
| BUTTON | Bouton | Info | binary, numeric
| GENERIC_INFO |  Générique | Info |
| GENERIC_ACTION |  Générique | Action | other

| **Lumière (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Lumière Etat | Info | binary, numeric
| LIGHT_BRIGHTNESS | Lumière Luminosité (Non géré par Application Mobile) | Info | numeric
| LIGHT_COLOR | Lumière Couleur | Info | string
| LIGHT_STATE_BOOL | Lumière Etat (Binaire) (Non géré par Application Mobile) | Info | binary
| LIGHT_COLOR_TEMP | Lumière Température Couleur (Non géré par Application Mobile) | Info | numeric
| LIGHT_TOGGLE | Lumière Toggle | Action | other
| LIGHT_ON | Lumière Bouton On | Action | other
| LIGHT_OFF | Lumière Bouton Off | Action | other
| LIGHT_SLIDER | Lumière Slider | Action | slider
| LIGHT_SET_COLOR | Lumière Couleur | Action | color
| LIGHT_MODE | Lumière Mode | Action | other
| LIGHT_SET_COLOR_TEMP | Lumière Température Couleur (Non géré par Application Mobile) | Action |

| **Mode (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Mode Etat | Info | string
| MODE_SET_STATE | Changer Mode | Action | other

| **Multimédia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume | Info | numeric
| MEDIA_STATUS | Status (Non géré par Application Mobile) | Info | string
| MEDIA_ALBUM | Album (Non géré par Application Mobile) | Info | string
| MEDIA_ARTIST | Artiste (Non géré par Application Mobile) | Info | string
| MEDIA_TITLE | Titre (Non géré par Application Mobile) | Info | string
| MEDIA_POWER | Power (Non géré par Application Mobile) | Info | string
| CHANNEL | Chaine | Info | numeric, string
| MEDIA_STATE | Etat (Non géré par Application Mobile) | Info | binary
| SET_VOLUME | Volume | Action | slider
| SET_CHANNEL | Chaine | Action | other, slider
| MEDIA_PAUSE | Pause | Action | other
| MEDIA_RESUME | Lecture | Action | other
| MEDIA_STOP | Stop | Action | other
| MEDIA_NEXT | Suivant | Action | other
| MEDIA_PREVIOUS | Précedent | Action | other
| MEDIA_ON | On (Non géré par Application Mobile) | Action | other
| MEDIA_OFF | Off (Non géré par Application Mobile) | Action | other
| MEDIA_MUTE | Muet (Non géré par Application Mobile) | Action | other
| MEDIA_UNMUTE | Non Muet (Non géré par Application Mobile) | Action | other

| **Météo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Météo Température (Non géré par Application Mobile) | Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Météo condition j+1 max j+2 (Non géré par Application Mobile) | Info | numeric
| WIND_SPEED | Vent (vitesse) (Non géré par Application Mobile) | Info | numeric
| RAIN_TOTAL | Pluie (accumulation) (Non géré par Application Mobile) | Info | numeric
| RAIN_CURRENT | Pluie (mm/h) (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_ID_4 | Météo condition (id) j+4 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_4 | Météo condition j+4 (Non géré par Application Mobile) | Info | string
| WEATHER_TEMPERATURE_MAX_4 | Météo Température max j+4 (Non géré par Application Mobile) | Info | numeric
| WEATHER_TEMPERATURE_MIN_4 | Météo Température min j+4 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_ID_3 | Météo condition (id) j+3 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_3 | Météo condition j+3 (Non géré par Application Mobile) | Info | string
| WEATHER_TEMPERATURE_MAX_3 | Météo Température max j+3 (Non géré par Application Mobile) | Info | numeric
| WEATHER_TEMPERATURE_MIN_3 | Météo Température min j+3 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_ID_2 | Météo condition (id) j+2 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_2 | Météo condition j+2 (Non géré par Application Mobile) | Info | string
| WEATHER_TEMPERATURE_MIN_2 | Météo Température min j+2 (Non géré par Application Mobile) | Info | numeric
| WEATHER_HUMIDITY | Météo Humidité (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_ID_1 | Météo condition (id) j+1 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_1 | Météo condition j+1 (Non géré par Application Mobile) | Info | string
| WEATHER_TEMPERATURE_MAX_1 | Météo Température max j+1 (Non géré par Application Mobile) | Info | numeric
| WEATHER_TEMPERATURE_MIN_1 | Météo Température min j+1 (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION_ID | Météo condition (id) (Non géré par Application Mobile) | Info | numeric
| WEATHER_CONDITION | Météo condition (Non géré par Application Mobile) | Info | string
| WEATHER_TEMPERATURE_MAX | Météo Température max (Non géré par Application Mobile) | Info | numeric
| WEATHER_TEMPERATURE_MIN | Météo Température min (Non géré par Application Mobile) | Info | numeric
| WEATHER_SUNRISE | Météo coucher de soleil (Non géré par Application Mobile) | Info | numeric
| WEATHER_SUNSET | Météo lever de soleil (Non géré par Application Mobile) | Info | numeric
| WEATHER_WIND_DIRECTION | Météo direction du vent (Non géré par Application Mobile) | Info | numeric
| WEATHER_WIND_SPEED | Météo vitesse du vent (Non géré par Application Mobile) | Info | numeric
| WEATHER_PRESSURE | Météo Pression (Non géré par Application Mobile) | Info | numeric
| WIND_DIRECTION | Vent (direction) (Non géré par Application Mobile) | Info | numeric

| **Ouvrant (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Serrure Etat | Info | binary
| BARRIER_STATE | Portail (ouvrant) Etat | Info | binary
| GARAGE_STATE | Garage (ouvrant) Etat | Info | binary
| OPENING | Porte | Info | binary
| OPENING_WINDOW | Fenêtre | Info | binary
| LOCK_OPEN | Serrure Bouton Ouvrir | Action | other
| LOCK_CLOSE | Serrure Bouton Fermer | Action | other
| GB_OPEN | Portail ou garage bouton d'ouverture | Action | other
| GB_CLOSE | Portail ou garage bouton de fermeture | Action | other
| GB_TOGGLE | Portail ou garage bouton toggle | Action | other

| **Prise (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Prise Etat | Info | numeric, binary
| ENERGY_ON | Prise Bouton On | Action | other
| ENERGY_OFF | Prise Bouton Off | Action | other
| ENERGY_SLIDER | Prise Slider | Action |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base Etat (Non géré par Application Mobile) | Info | binary
| DOCK | Retour base (Non géré par Application Mobile) | Action | other

| **Sécurité (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Sirène Etat | Info | binary
| ALARM_STATE | Alarme Etat (Non géré par Application Mobile) | Info | binary, string
| ALARM_MODE | Alarme mode (Non géré par Application Mobile) | Info | string
| ALARM_ENABLE_STATE | Alarme Etat activée (Non géré par Application Mobile) | Info | binary
| FLOOD | Inondation | Info | binary
| SABOTAGE | Sabotage | Info | binary
| SHOCK | Choc | Info | binary, numeric
| SIREN_OFF | Sirène Bouton Off | Action | other
| SIREN_ON | Sirène Bouton On | Action | other
| ALARM_ARMED | Alarme armée (Non géré par Application Mobile) | Action | other
| ALARM_RELEASED | Alarme libérée (Non géré par Application Mobile) | Action | other
| ALARM_SET_MODE | Alarme Mode (Non géré par Application Mobile) | Action | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostat Etat (BINAIRE) (pour Plugin Thermostat uniquement) | Info |
| THERMOSTAT_TEMPERATURE | Thermostat Température ambiante | Info | numeric
| THERMOSTAT_SETPOINT | Thermostat consigne | Info | numeric
| THERMOSTAT_MODE | Thermostat Mode (pour Plugin Thermostat uniquement) | Info | string
| THERMOSTAT_LOCK | Thermostat Verrouillage (pour Plugin Thermostat uniquement) | Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Thermostat Température Exterieur (pour Plugin Thermostat uniquement) | Info | numeric
| THERMOSTAT_STATE_NAME | Thermostat Etat (HUMAIN) (pour Plugin Thermostat uniquement) | Info | string
| THERMOSTAT_HUMIDITY | Thermostat humidité ambiante (Non géré par Application Mobile) | Info | numeric
| HUMIDITY_SETPOINT | Humidité consigne (Non géré par Application Mobile) | Info | slider
| THERMOSTAT_SET_SETPOINT | Thermostat consigne | Action | slider
| THERMOSTAT_SET_MODE | Thermostat Mode (pour Plugin Thermostat uniquement) | Action | other
| THERMOSTAT_SET_LOCK | Thermostat Verrouillage (pour Plugin Thermostat uniquement) | Action | other
| THERMOSTAT_SET_UNLOCK | Thermostat Déverrouillage (pour Plugin Thermostat uniquement) | Action | other
| HUMIDITY_SET_SETPOINT | Humidité consigne (Non géré par Application Mobile) | Action | slider

| **Ventilateur (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Vitesse ventilateur Etat (Non géré par Application Mobile) | Info | numeric
| ROTATION_STATE | Rotation Etat (Non géré par Application Mobile) | Info | numeric
| FAN_SPEED | Vitesse ventilateur (Non géré par Application Mobile) | Action | slider
| ROTATION | Rotation (Non géré par Application Mobile) | Action | slider

| **Volet (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Volet Etat | Info | binary, numeric
| FLAP_BSO_STATE | Volet BSO Etat | Info | binary, numeric
| FLAP_UP | Volet Bouton Monter | Action | other
| FLAP_DOWN | Volet Bouton Descendre | Action | other
| FLAP_STOP | Volet Bouton Stop | Action |
| FLAP_SLIDER | Volet Bouton Slider | Action | slider
| FLAP_BSO_UP | Volet BSO Bouton Monter | Action | other
| FLAP_BSO_DOWN | Volet BSO Bouton Descendre | Action | other