# Tipos de equipo
**Herramientas → Tipos de equipos**

Los sensores y actuadores en Jeedom son administrados por complementos, que crean equipos con comandos *Información* (sensor) o *Acción* (solenoide). Esto luego permite activar acciones basadas en el cambio de ciertos sensores, como encender una luz en la detección de movimiento. Pero Jeedom Core y complementos como *Móvil*, *Homebridge*, *Google Smarthome*, *Alexa Smarthome* etc., no sé qué es este equipo : Un enchufe, una luz, una persiana, etc.

Para superar este problema, especialmente con asistentes de voz (*Enciende la luz de la habitación*), Core presentó el **Tipos genéricos**, utilizado por estos complementos.

Esto permite identificar un equipo mediante *La luz de la habitación* por ejemplo.

La mayoría de las veces, los tipos genéricos se establecen automáticamente al configurar su módulo (inclusión en Z-wave, por ejemplo). Pero puede haber ocasiones en las que necesite reconfigurarlos. La configuración de estos tipos genéricos se puede hacer directamente en ciertos complementos, o mediante comando en *Configuración avanzada* del mismo.

Esta página permite configurar estos Tipos Genéricos de una forma más directa y sencilla, e incluso ofrece asignación automática una vez que los dispositivos han sido correctamente asignados.

![Tipos de equipo](./images/coreGenerics.gif)

## Tipo de equipo

Esta página ofrece almacenamiento por tipo de equipo : Enchufe, luz, obturador, termostato, cámara, etc. Inicialmente, la mayor parte de su equipo se clasificará en **Equipo sin tipo**. Para asignarles un tipo, puede moverlos a otro tipo o hacer clic con el botón derecho en el equipo para moverlo directamente. El tipo de equipo no es realmente útil en sí mismo, siendo los más importantes los tipos de orden. Por tanto, puede tener un Equipo sin un Tipo, o un Tipo que no se corresponda necesariamente con sus comandos. Por supuesto, puede mezclar tipos de controles dentro del mismo equipo. Por ahora, es más un almacenamiento, una organización lógica, que quizás sirva en futuras versiones.

> **Propina**
>
> - Cuando mueves equipo en el juego **Equipo sin tipo**, Jeedom sugiere que elimines los tipos genéricos de sus órdenes.
> - Puede mover varios equipos a la vez marcando las casillas de verificación a la izquierda de ellos.

## Tipo de comando

Una vez que un elemento de equipo se coloca en la posición correcta *Escribe*, al hacer clic en él se accede a la lista de sus pedidos, coloreados de diferente color si es un *Información* (Azul) o un *Acción* (Orange).

Al hacer clic con el botón derecho en un pedido, puede asignarle un Tipo genérico correspondiente a las especificaciones de este pedido (Tipo de información / acción, Numérico, Subtipo binario, etc).

> **Propina**
>
> - El menú contextual de comandos muestra el tipo de equipo en negrita, pero aún permite asignar cualquier tipo genérico de cualquier tipo de equipo.

En cada dispositivo, tienes dos botones :

- **Tipos de auto** : Esta función abre una ventana que le ofrece los tipos genéricos adecuados según el tipo de equipo, los detalles del pedido y su nombre. Luego puede ajustar las propuestas y desmarcar la aplicación para ciertos comandos antes de aceptar o no. Esta función es compatible con la selección mediante casillas de verificación.

- **Tipos de reinicio** : Esta función elimina los tipos genéricos de todos los comandos del equipo.

> **Atención**
>
> No se realizan cambios antes de guardar, con el botón en la parte superior derecha de la página.

## Tipos y escenarios genéricos

En v4.2, el Core ha integrado los tipos genéricos en los escenarios. De esta forma se puede desencadenar un escenario si se enciende una lámpara en una habitación, si se detecta movimiento en la casa, apagar todas las luces o cerrar todas las persianas con una sola acción, etc. Además, si agrega un equipo, solo debe indicar los tipos correctos en sus pedidos, no será necesario editar dichos escenarios.

#### Desencadenar

Puede activar un escenario desde sensores. Por ejemplo, si tiene detectores de movimiento en la casa, puede crear un escenario de alarma con cada detector activado : ''#[Salón][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. En tal escenario, necesitará todos sus detectores de movimiento, y si agrega uno, tendrá que agregarlo a los disparadores. Lógica.

Los tipos genéricos le permiten usar un solo disparador : ''#genericType(PRESENCE)# == 1`. Aquí, no se indica ningún objeto, por lo que el más mínimo movimiento en toda la casa desencadenará el escenario. Si agrega un nuevo detector en la casa, no es necesario editar los escenarios).

Aquí, un disparador al encender una luz en la sala de estar : ''#genericType(LIGHT_STATE,#[Salón]#)# > 0`

#### Expression

Si, en un escenario, desea saber si hay una luz encendida en la sala de estar, puede hacer :

SI `#[Salón][Lumiere Canapé][Estado]# == 1 O #[Salón][Lumiere Salon][Estado]# == 1 O #[Salón][Lumiere Angle][Estado]# == 1`

O mas simplemente : IF `genericType (LIGHT_STATE,#[Salón]#) > 0` o si una o más luces están encendidas en la sala de estar.

Si mañana añades luz en tu salón, no es necesario que retoques tus escenarios !


#### Action

Si desea encender todas las luces en la sala de estar, puede crear una acción de luz:

`` ``
#[Salón][Lumiere Canapé][Nosotros]#
#[Salón][Lumiere Salon][Nosotros]#
#[Salón][Lumiere Angle][Nosotros]#
`` ``

O más simplemente, cree una acción `genericType` con` LIGHT_ON` en` Salon`. Si mañana añades luz en tu salón, no es necesario que retoques tus escenarios !


## Lista de tipos de núcleos genéricos

> **Propina**
>
> - Puede encontrar esta lista directamente en Jeedom, en esta misma página, con el botón **Listado** arriba a la derecha.

| **Otro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPORIZADOR | Temporizador de estado | Información | numeric
| TIMER_STATE | Estado del temporizador (pausar o no) | Información | binario, numérico
| SET_TIMER | Temporizador | Acción | slider
| TIMER_PAUSE | Pausar temporizador | Acción | other
| TIMER_RESUME | Reanudar el temporizador | Acción | other

| **Batería (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERÍA | Tambores | Información | numeric
| BATERIA CARGANDO | Bateria cargando | Información | binary

| **Cámara (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | URL de la cámara | Información | string
| CAMERA_RECORD_STATE | Estado de grabación de la cámara | Información | binary
| CAMERA_UP | Movimiento de la cámara hacia arriba | Acción | other
| CÁMARA HACIA ABAJO | Movimiento de la cámara hacia abajo | Acción | other
| CAMERA_LEFT | Movimiento de la cámara hacia la izquierda | Acción | other
| CAMERA_RIGHT | Movimiento de la cámara hacia la derecha | Acción | other
| CAMERA_ZOOM | Acercar la cámara hacia adelante | Acción | other
| CAMERA_DEZOOM | Zoom de la cámara hacia atrás | Acción | other
| CAMERA_STOP | Detener la cámara | Acción | other
| CAMERA_PRESET | Preajuste de la cámara | Acción | other
| CAMERA_RECORD | Grabación de cámara | Acción |
| CAMERA_TAKE | Cámara de instantáneas | Acción |

| **Calefacción (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Estado de calentamiento del hilo piloto | Información | binary
| HEATING_ON | Botón de encendido del calentamiento del hilo piloto | Acción | other
| HEATING_OFF | Botón de apagado del calentamiento del hilo piloto | Acción | other
| HEATING_OTHER | Botón de hilo piloto de calentamiento | Acción | other

| **Electricidad (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PODER | Energia electrica | Información | numeric
| CONSUMO | El consumo de energía | Información | numeric
| VOLTAJE | Voltaje | Información | numeric
| REINICIAR | Reiniciar | Acción | other

| **Entorno (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | TEMPERATURA | Información | numeric
| CALIDAD DEL AIRE | Calidad del aire | Información | numeric
| BRILLO | Brillo | Información | numeric
| PRESENCIA | PRESENCIA | Información | binary
| FUMAR | Deteccion de humo | Información | binary
| HUMEDAD | Humedad | Información | numeric
| UV | UV | Información | numeric
| CO2 | CO2 (ppm) | Información | numeric
| CO | CO (ppm) | Información | numeric
| RUIDO | Sonido (dB) | Información | numeric
| PRESIÓN | Presión | Información | numeric
| GOTERA DE AGUA | Fuga de agua | Información |
| FILTER_CLEAN_STATE | Estado de filtro | Información | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDAD | Profundidad | Información | numeric
| DISTANCIA | DISTANCIA | Información | numeric
| BOTÓN | Botón | Información | binario, numérico
| GENERIC_INFO |  Genérico | Información |
| GENERIC_ACTION |  Genérico | Acción | other

| **Luz (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado de luz | Información | binario, numérico
| LIGHT_BRIGHTNESS | Brillo de luz | Información | numeric
| COLOR CLARO | Color claro | Información | string
| LIGHT_STATE_BOOL | Estado de luz (binario) | Información | binary
| LIGHT_COLOR_TEMP | Color de temperatura de luz | Información | numeric
| LIGHT_TOGGLE | Alternar luz | Acción | other
| LUCES ENCENDIDAS | Botón de luz encendido | Acción | other
| LUZ APAGADA | Botón de luz apagado | Acción | other
| LIGHT_SLIDER | Luz deslizante | Acción | slider
| LIGHT_SET_COLOR | Color claro | Acción | color
| LIGHT_MODE | Modo de luz | Acción | other
| LIGHT_SET_COLOR_TEMP | Color de temperatura de luz | Acción |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo de estado | Información | string
| MODE_SET_STATE | Modo de cambio | Acción | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Volumen | Volumen | Información | numeric
| MEDIA_STATUS | Estado | Información | string
| MEDIA_ALBUM | Álbum | Información | string
| MEDIA_ARTIST | Artista | Información | string
| MEDIA_TITLE | Título | Información | string
| MEDIA_POWER | PODER | Información | string
| CANAL | Cadena | Información | numérico, cadena
| MEDIA_STATE | Estado | Información | binary
| SET_VOLUME | Volumen | Acción | slider
| SET_CHANNEL | Cadena | Acción | otro control deslizante
| MEDIA_PAUSE | Pausa | Acción | other
| MEDIA_RESUME | Leer | Acción | other
| MEDIA_STOP | Parada | Acción | other
| MEDIA_NEXT | Próximo | Acción | other
| MEDIA_PREVIOUS | Anterior | Acción | other
| MEDIA_ON | Nosotros | Acción | other
| MEDIA_OFF | Apagado | Acción | other
| MEDIA_MUTE | Silencio | Acción | other
| MEDIA_UNMUTE | Sin silencio | Acción | other

| **Clima (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Temperatura del tiempo | Información | numeric
| WEATHER_TEMPERATURE_MAX_2 | Condición meteorológica d + 1 máx d + 2 | Información | numeric
| VELOCIDAD DEL VIENTO | Velocidad del viento) | Información | numeric
| RAIN_TOTAL | Lluvia (acumulación) | Información | numeric
| RAIN_CURRENT | Lluvia (mm / h) | Información | numeric
| WEATHER_CONDITION_ID_4 | Condición meteorológica (id) d + 4 | Información | numeric
| WEATHER_CONDITION_4 | Condición meteorológica d + 4 | Información | string
| WEATHER_TEMPERATURE_MAX_4 | Clima Temperatura máxima d + 4 | Información | numeric
| WEATHER_TEMPERATURE_MIN_4 | Tiempo Temperatura min d + 4 | Información | numeric
| WEATHER_CONDITION_ID_3 | Condición meteorológica (id) d + 3 | Información | numeric
| WEATHER_CONDITION_3 | Condición meteorológica d + 3 | Información | string
| WEATHER_TEMPERATURE_MAX_3 | Clima Temperatura máxima d + 3 | Información | numeric
| WEATHER_TEMPERATURE_MIN_3 | Tiempo Temperatura min d + 3 | Información | numeric
| WEATHER_CONDITION_ID_2 | Condición meteorológica (id) d + 2 | Información | numeric
| WEATHER_CONDITION_2 | Condición meteorológica d + 2 | Información | string
| WEATHER_TEMPERATURE_MIN_2 | Tiempo Temperatura min d + 2 | Información | numeric
| WEATHER_HUMIDITY | Humedad del tiempo | Información | numeric
| WEATHER_CONDITION_ID_1 | Condición meteorológica (id) d + 1 | Información | numeric
| WEATHER_CONDITION_1 | Condición meteorológica d + 1 | Información | string
| WEATHER_TEMPERATURE_MAX_1 | Clima Temperatura máxima d + 1 | Información | numeric
| WEATHER_TEMPERATURE_MIN_1 | Clima Temperatura min d + 1 | Información | numeric
| WEATHER_CONDITION_ID | Condición climática (id) | Información | numeric
| CONDICIÓN CLIMÁTICA | Condición climática | Información | string
| WEATHER_TEMPERATURE_MAX | Clima Temperatura máxima | Información | numeric
| WEATHER_TEMPERATURE_MIN | Clima Temperatura min | Información | numeric
| WEATHER_SUNRISE | Tiempo al atardecer | Información | numeric
| WEATHER_SUNSET | Tiempo del amanecer | Información | numeric
| WEATHER_WIND_DIRECTION | Dirección del viento tiempo | Información | numeric
| WEATHER_WIND_SPEED | Tiempo de velocidad del viento | Información | numeric
| WEATHER_PRESSURE | Presión meteorológica | Información | numeric
| DIRECCIÓN DEL VIENTO | Dirección del viento) | Información | numeric

| **Apertura (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Bloqueo de estado | Información | binary
| BARRIER_STATE | Estado del portal (apertura) | Información | binary
| GARAGE_STATE | Estado del garaje (apertura) | Información | binary
| APERTURA | Puerta | Información | binary
| OPENING_WINDOW | Ventana | Información | binary
| LOCK_OPEN | Botón de bloqueo abierto | Acción | other
| LOCK_CLOSE | Cerrar el botón de bloqueo | Acción | other
| GB_OPEN | Botón de apertura de puerta o garaje | Acción | other
| GB_CLOSE | Botón de cierre de puerta o garaje | Acción | other
| GB_TOGGLE | Interruptor de botón de puerta o garaje | Acción | other

| **Zócalo (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Toma de estado | Información | numérico, binario
| ENERGY_ON | En el enchufe del botón | Acción | other
| ENERGY_OFF | Botón de enchufe desactivado | Acción | other
| ENERGY_SLIDER | Toma deslizante | Acción |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base estatal | Información | binary
| MUELLE | De regreso a la base | Acción | other

| **Seguridad (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Estado de la sirena | Información | binary
| ALARM_STATE | Estado de alarma | Información | binario, cadena
| ALARM_MODE | Modo de alarma | Información | string
| ALARM_ENABLE_STATE | Estado de alarma activado | Información | binary
| INUNDACIÓN | Inundación | Información | binary
| SABOTAJE | SABOTAJE | Información | binary
| CHOQUE | Choque | Información | binario, numérico
| SIREN_OFF | Botón de sirena apagado | Acción | other
| SIREN_ON | Botón de sirena encendido | Acción | other
| ALARM_ARMED | Alarma armada | Acción | other
| ALARM_RELEASED | Alarma lanzada | Acción | other
| ALARM_SET_MODE | Modo de alarma | Acción | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Estado del termostato (BINARIO) (solo para termostato de complemento) | Información |
| THERMOSTAT_TEMPERATURE | Termostato de temperatura ambiente | Información | numeric
| THERMOSTAT_SETPOINT | Termostato de consigna | Información | numeric
| THERMOSTAT_MODE | Modo de termostato (solo para termostato de complemento) | Información | string
| THERMOSTAT_LOCK | Termostato de bloqueo (solo para termostato de complemento) | Información | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Termostato de temperatura exterior (solo para termostato de complemento) | Información | numeric
| THERMOSTAT_STATE_NAME | Estado del termostato (HUMAN) (solo para termostato de complemento) | Información | string
| THERMOSTAT_HUMIDITY | Termostato de humedad ambiental | Información | numeric
| HUMIDITY_SETPOINT | Establecer humedad | Información | slider
| THERMOSTAT_SET_SETPOINT | Termostato de consigna | Acción | slider
| THERMOSTAT_SET_MODE | Modo de termostato (solo para termostato de complemento) | Acción | other
| THERMOSTAT_SET_LOCK | Termostato de bloqueo (solo para termostato de complemento) | Acción | other
| THERMOSTAT_SET_UNLOCK | Desbloquear el termostato (solo para el termostato enchufable)) | Acción | other
| HUMIDITY_SET_SETPOINT | Establecer humedad | Acción | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Estado de la velocidad del ventilador | Información | numeric
| ROTATION_STATE | Rotación de estado | Información | numeric
| VELOCIDAD DEL VENTILADOR | Velocidad del ventilador | Acción | slider
| ROTACIÓN | ROTACIÓN | Acción | slider

| **Panel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Panel de estado | Información | binario, numérico
| FLAP_BSO_STATE | Panel de estado de BSO | Información | binario, numérico
| FLAP_UP | Botón de panel hacia arriba | Acción | other
| FLAP_DOWN | Botón de panel hacia abajo | Acción | other
| FLAP_STOP | Botón de parada del obturador | Acción |
| FLAP_SLIDER | Panel de botones deslizantes | Acción | slider
| FLAP_BSO_UP | Botón arriba del panel BSO | Acción | other
| FLAP_BSO_DOWN | Botón Abajo del panel BSO | Acción | other