# Types d'équipement
**Outils → Types d'équipement**

Les capteurs et actionneurs dans Jeedom sont gérés par des plugins, qui créent des équipements avec des commandes *Info* (capteur) ou *Action* (actionneur). Ce qui permet ensuite de déclencher des actions en fonctions du changement de certains capteurs, comme allumer une lumière sur une détection de mouvement. Mais le Core de Jeedom, et des plugins comme *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa Smarthome* etc., ne savent pas ce que sont ces équipements : Une prise, une lumière, un volet, etc.

Pour palier à ce problème, notamment avec les assistants vocaux (*Allume la lumière de la salle*), le Core a introduit il y a quelques années les **Types Génériques**, utilisés par ces plugins.

Cela permet ainsi d'identifier un équipement par *La lumière de la salle* par exemple.

La pluspart du temps les types génériques sont mis automatiquement lors la configuration de votre module (inclusion sous Z-wave par exemple). Mais il peut arriver que vous deviez les reconfigurer. Le paramétrage des ces Types Génériques peut se faire directement dans certains plugins, ou par commande dans *Configuration avancée* de celle-ci.

Cette page permet de paramétrer ces Types Génériques, de manière plus directe et plus simple, et propose même une assignation automatique une fois les équipements assignés correctement.

![Types d'équipement](./images/coreGenerics.gif)

## Type d'équipement

Cette page propose un rangement par type d'équipement : Prise, Lumière, Volet, Thermostat, Camera, etc. Au départ, la plupart de vos équipements seront classés dans **Equipements sans type**. Pour leur assigner un type, vous pouvez soit les déplacer dans un autre type, soit faire un clic droit sur l'équipement pour le déplacer directement. Le Type d'équipement n'est pas vraiment utile en soit, le plus important étant le Types des commandes. Vous pouvez ainsi avoir un Equipement sans Type, ou d'un Type ne correspondant pas forcément à ses commandes. Vous pouvez bien sûr mixer des types de commandes au sein d'un même équipement. Pour l'instant, il s'agit plus d'un rangement, d'une organisation logique, qui servira peu-être dans de futures versions.

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

## Types Génériques et scénarios

En v4.2, le Core a intégré les types génériques dans les scénarios. Vous pouvez ainsi déclencher un scénario si une lampe s'allume dans une pièce, si un mouvement est détecté dans la maison, éteindre toutes les lumières ou fermer tous les volets avec une seule action, etc. De plus, si vous ajoutez un équipement, vous n'avez qu'à indiquer les bons types sur ses commandes, il ne sera pas nécessaire de retoucher de tels scénarios.

#### Déclencheur

Vous pouvez déclencher un scénario à partir de capteurs. Par exemple, si vous avez des détecteurs de mouvements dans la maison, vous pouvez créer un scénario d'alarme avec chaque détecteur en déclencheur : `#[Salon][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc. Dans un tel scénario, il vous faudra donc tous vos détecteurs de mouvement, et si vous en ajoutez un il faudra le rajouter dans les déclencheurs. Logique.

Grâce aux types génériques, vous pourrez utiliser un seul déclencheur : `#genericType(PRESENCE)# == 1`. Ici, aucun objet n'est indiqué, donc le moindre mouvement dans toute la maison déclenchera le scénario. Si vous ajoutez un nouveau détecteur dans la maison, inutile de retoucher au(x) scénario(s).

Ici, un déclencheur sur l'allumage d'une lumière dans le Salon : `#genericType(LIGHT_STATE,#[Salon]#)# > 0`

#### Expression

Si vous souhaitez, dans un scénario, savoir si une lumière est allumée dans le Salon, vous pouvez faire :

SI `#[Salon][Lumiere Canapé][Etat]# == 1 OU #[Salon][Lumiere Salon][Etat]# == 1 OU #[Salon][Lumiere Angle][Etat]# == 1`

Ou plus simplement : SI `genericType(LIGHT_STATE,#[Salon]#) > 0` soit si une ou plusieurs lumiere(s) sont allumée dans le Salon.

Si demain vous ajoutez une lumière dans votre Salon, inutile de retoucher vos scénarios !


#### Action

Si vous souhaitez allumez toutes les lumières dans le Salon, vous pouvez créer une action par lumière:

```
#[Salon][Lumiere Canapé][On]#
#[Salon][Lumiere Salon][On]#
#[Salon][Lumiere Angle][On]#
```

Ou plus simplement, créer une action `genericType` avec `LIGHT_ON` dans `Salon`. Si demain vous ajoutez une lumière dans votre Salon, inutile de retoucher vos scénarios !


## Liste des Types génériques du Core

> **Tip**
>
> - Vous pouvez retrouver cette liste directement dans Jeedom, sur cette même page, avec le bouton **Liste** en haut à droite.

| **Autre (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Minuteur Etat | Info | numeric
| TIMER_STATE | Minuteur Etat (pause ou non) | Info | binary, numeric
| SET_TIMER | Minuteur | Action | slider
| TIMER_PAUSE | Minuteur pause | Action | other
| TIMER_RESUME | Minuteur reprendre | Action | other

| **Batterie (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERY | Batterie | Info | numeric
| BATTERY_CHARGING | Batterie en charge | Info | binary

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
| CONSUMPTION | Consommation Electrique | Info | numeric
| VOLTAGE | Tension | Info | numeric
| REBOOT | Redémarrage | Action | other

| **Environnement (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURE | Température | Info | numeric
| AIR_QUALITY | Qualité de l'air | Info | numeric
| BRIGHTNESS | Luminosité | Info | numeric
| PRESENCE | Présence | Info | binary
| SMOKE | Détection de fumée | Info | binary
| HUMIDITY | Humidité | Info | numeric
| UV | UV | Info | numeric
| CO2 | CO2 (ppm) | Info | numeric
| CO | CO (ppm) | Info | numeric
| NOISE | Son (dB) | Info | numeric
| PRESSURE | Pression | Info | numeric
| WATER_LEAK | Fuite d'eau | Info |
| FILTER_CLEAN_STATE | Etat du filtre | Info | binary

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
| LIGHT_BRIGHTNESS | Lumière Luminosité | Info | numeric
| LIGHT_COLOR | Lumière Couleur | Info | string
| LIGHT_STATE_BOOL | Lumière Etat (Binaire) | Info | binary
| LIGHT_COLOR_TEMP | Lumière Température Couleur | Info | numeric
| LIGHT_TOGGLE | Lumière Toggle | Action | other
| LIGHT_ON | Lumière Bouton On | Action | other
| LIGHT_OFF | Lumière Bouton Off | Action | other
| LIGHT_SLIDER | Lumière Slider | Action | slider
| LIGHT_SET_COLOR | Lumière Couleur | Action | color
| LIGHT_MODE | Lumière Mode | Action | other
| LIGHT_SET_COLOR_TEMP | Lumière Température Couleur | Action |

| **Mode (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Mode Etat | Info | string
| MODE_SET_STATE | Changer Mode | Action | other

| **Multimédia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume | Info | numeric
| MEDIA_STATUS | Status | Info | string
| MEDIA_ALBUM | Album | Info | string
| MEDIA_ARTIST | Artiste | Info | string
| MEDIA_TITLE | Titre | Info | string
| MEDIA_POWER | Power | Info | string
| CHANNEL | Chaine | Info | numeric, string
| MEDIA_STATE | Etat | Info | binary
| SET_VOLUME | Volume | Action | slider
| SET_CHANNEL | Chaine | Action | other, slider
| MEDIA_PAUSE | Pause | Action | other
| MEDIA_RESUME | Lecture | Action | other
| MEDIA_STOP | Stop | Action | other
| MEDIA_NEXT | Suivant | Action | other
| MEDIA_PREVIOUS | Précedent | Action | other
| MEDIA_ON | On | Action | other
| MEDIA_OFF | Off | Action | other
| MEDIA_MUTE | Muet | Action | other
| MEDIA_UNMUTE | Non Muet | Action | other

| **Météo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Météo Température | Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Météo condition j+1 max j+2 | Info | numeric
| WIND_SPEED | Vent (vitesse) | Info | numeric
| RAIN_TOTAL | Pluie (accumulation) | Info | numeric
| RAIN_CURRENT | Pluie (mm/h) | Info | numeric
| WEATHER_CONDITION_ID_4 | Météo condition (id) j+4 | Info | numeric
| WEATHER_CONDITION_4 | Météo condition j+4 | Info | string
| WEATHER_TEMPERATURE_MAX_4 | Météo Température max j+4 | Info | numeric
| WEATHER_TEMPERATURE_MIN_4 | Météo Température min j+4 | Info | numeric
| WEATHER_CONDITION_ID_3 | Météo condition (id) j+3 | Info | numeric
| WEATHER_CONDITION_3 | Météo condition j+3 | Info | string
| WEATHER_TEMPERATURE_MAX_3 | Météo Température max j+3 | Info | numeric
| WEATHER_TEMPERATURE_MIN_3 | Météo Température min j+3 | Info | numeric
| WEATHER_CONDITION_ID_2 | Météo condition (id) j+2 | Info | numeric
| WEATHER_CONDITION_2 | Météo condition j+2 | Info | string
| WEATHER_TEMPERATURE_MIN_2 | Météo Température min j+2 | Info | numeric
| WEATHER_HUMIDITY | Météo Humidité | Info | numeric
| WEATHER_CONDITION_ID_1 | Météo condition (id) j+1 | Info | numeric
| WEATHER_CONDITION_1 | Météo condition j+1 | Info | string
| WEATHER_TEMPERATURE_MAX_1 | Météo Température max j+1 | Info | numeric
| WEATHER_TEMPERATURE_MIN_1 | Météo Température min j+1 | Info | numeric
| WEATHER_CONDITION_ID | Météo condition (id) | Info | numeric
| WEATHER_CONDITION | Météo condition | Info | string
| WEATHER_TEMPERATURE_MAX | Météo Température max | Info | numeric
| WEATHER_TEMPERATURE_MIN | Météo Température min | Info | numeric
| WEATHER_SUNRISE | Météo coucher de soleil | Info | numeric
| WEATHER_SUNSET | Météo lever de soleil | Info | numeric
| WEATHER_WIND_DIRECTION | Météo direction du vent | Info | numeric
| WEATHER_WIND_SPEED | Météo vitesse du vent | Info | numeric
| WEATHER_PRESSURE | Météo Pression | Info | numeric
| WIND_DIRECTION | Vent (direction) | Info | numeric

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
| DOCK_STATE | Base Etat | Info | binary
| DOCK | Retour base | Action | other

| **Sécurité (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Sirène Etat | Info | binary
| ALARM_STATE | Alarme Etat | Info | binary, string
| ALARM_MODE | Alarme mode | Info | string
| ALARM_ENABLE_STATE | Alarme Etat activée | Info | binary
| FLOOD | Inondation | Info | binary
| SABOTAGE | Sabotage | Info | binary
| SHOCK | Choc | Info | binary, numeric
| SIREN_OFF | Sirène Bouton Off | Action | other
| SIREN_ON | Sirène Bouton On | Action | other
| ALARM_ARMED | Alarme armée | Action | other
| ALARM_RELEASED | Alarme libérée | Action | other
| ALARM_SET_MODE | Alarme Mode | Action | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostat Etat (BINAIRE) (pour Plugin Thermostat uniquement) | Info |
| THERMOSTAT_TEMPERATURE | Thermostat Température ambiante | Info | numeric
| THERMOSTAT_SETPOINT | Thermostat consigne | Info | numeric
| THERMOSTAT_MODE | Thermostat Mode (pour Plugin Thermostat uniquement) | Info | string
| THERMOSTAT_LOCK | Thermostat Verrouillage (pour Plugin Thermostat uniquement) | Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Thermostat Température Exterieur (pour Plugin Thermostat uniquement) | Info | numeric
| THERMOSTAT_STATE_NAME | Thermostat Etat (HUMAIN) (pour Plugin Thermostat uniquement) | Info | string
| THERMOSTAT_HUMIDITY | Thermostat humidité ambiante | Info | numeric
| HUMIDITY_SETPOINT | Humidité consigne | Info | slider
| THERMOSTAT_SET_SETPOINT | Thermostat consigne | Action | slider
| THERMOSTAT_SET_MODE | Thermostat Mode (pour Plugin Thermostat uniquement) | Action | other
| THERMOSTAT_SET_LOCK | Thermostat Verrouillage (pour Plugin Thermostat uniquement) | Action | other
| THERMOSTAT_SET_UNLOCK | Thermostat Déverrouillage (pour Plugin Thermostat uniquement) | Action | other
| HUMIDITY_SET_SETPOINT | Humidité consigne | Action | slider

| **Ventilateur (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Vitesse ventilateur Etat | Info | numeric
| ROTATION_STATE | Rotation Etat | Info | numeric
| FAN_SPEED | Vitesse ventilateur | Action | slider
| ROTATION | Rotation | Action | slider

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