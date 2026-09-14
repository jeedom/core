# Tipos de equipos
**Herramientas → Tipos de equipos**

Los sensores y actuadores en Jeedom se gestionan mediante plugins, que crean dispositivos con comandos *Info* (sensor) o *Action* (actuador). Esto permite, a su vez, activar acciones en función de los cambios detectados por determinados sensores, como encender una luz al detectar movimiento. Sin embargo, el núcleo de Jeedom y los complementos como *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa Smarthome*, etc., no reconocen qué son estos dispositivos: un enchufe, una luz, una persiana, etc.

Para solucionar este problema, sobre todo con los asistentes de voz (*Enciende la luz del salón*), Core introdujo hace unos años los **tipos genéricos**, que utilizan estos complementos.

Esto permite identificar un dispositivo mediante, por ejemplo, *la luz de la habitación*.

La mayoría de las veces, los tipos genéricos se establecen automáticamente al configurar tu módulo (por ejemplo, al incluirlo en Z-Wave). Sin embargo, puede ocurrir que tengas que volver a configurarlos. La configuración de estos tipos genéricos puede realizarse directamente en algunos complementos o mediante un comando en la sección *Configuración avanzada* de los mismos.

Esta página permite configurar estos «Tipos genéricos» de una forma más directa y sencilla, e incluso ofrece una asignación automática una vez que los dispositivos se han asignado correctamente.

![Tipos de equipos](../images/coreGenerics.gif)

## Tipo de equipo

Esta página ofrece una clasificación por tipo de dispositivo: enchufe, luz, persiana, termostato, cámara, etc. Al principio, la mayoría de tus dispositivos aparecerán en **Dispositivos sin tipo**. Para asignarles un tipo, puedes moverlos a otra categoría o hacer clic con el botón derecho del ratón sobre el dispositivo para moverlo directamente. El «Tipo de equipo» no es realmente útil en sí mismo, ya que lo más importante es el «Tipo de comando». Así, puedes tener un equipo sin tipo o de un tipo que no se corresponda necesariamente con sus comandos. Por supuesto, puedes mezclar tipos de comandos dentro de un mismo equipo. Por ahora, se trata más bien de una clasificación, de una organización lógica, que quizá resulte útil en futuras versiones.

> **Consejo**
>
> - Cuando trasladas un dispositivo a la sección **Dispositivos sin tipo**, Jeedom te propone eliminar los tipos genéricos de sus comandos.
> - Puedes seleccionar varios dispositivos a la vez marcando las casillas situadas a la izquierda de cada uno de ellos.

## Tipo de control

Una vez que hayas asignado un dispositivo al *Tipo* correcto, al hacer clic en él accederás a la lista de sus comandos, que aparecen en diferentes colores según se trate de una *Información* (azul) o una *Acción* (naranja).

Al hacer clic con el botón derecho del ratón sobre un comando, puedes asignarle un tipo genérico que se corresponda con las especificaciones de dicho comando (tipo «Info/Acción», subtipo «Numérico», «Binario», etc.).

> **Consejo**
>
> - El menú contextual de los comandos muestra el tipo de dispositivo en negrita, pero permite asignar cualquier «Tipo genérico» a cualquier tipo de dispositivo.

En cada dispositivo hay dos botones:

- **Tipos automáticos**: Esta función abre una ventana en la que se sugieren los tipos genéricos adecuados en función del tipo de equipo, las características específicas del comando y su nombre. A continuación, puedes ajustar las sugerencias y desmarcar la aplicación de determinados comandos antes de aceptar o rechazar la selección. Esta función es compatible con la selección mediante casillas de verificación.

- **Restablecer tipos**: Esta función elimina los tipos genéricos de todos los comandos del equipo.

> **Atención**
>
> No se aplica ningún cambio hasta que se guarde, pulsando el botón situado en la parte superior derecha de la página.

## Tipos genéricos y escenarios

En la versión 4.2, el Core ha incorporado los tipos genéricos en los escenarios. De este modo, puedes activar un escenario si se enciende una luz en una habitación, si se detecta movimiento en la casa, apagar todas las luces o cerrar todas las persianas con una sola acción, etc. Además, si añades un dispositivo, solo tienes que indicar los tipos correctos en sus comandos; no será necesario modificar dichos escenarios.

#### Activador

Puedes activar un escenario a partir de sensores. Por ejemplo, si tienes detectores de movimiento en casa, puedes crear un escenario de alarma con cada detector como activador: `#[Salon][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc. En un caso así, necesitarás todos tus detectores de movimiento, y si añades uno más, tendrás que incluirlo en los activadores. Es lógico.

Gracias a los tipos genéricos, podrás utilizar un único desencadenador: `#genericType(PRESENCE)# == 1`. En este caso, no se ha indicado ningún objeto, por lo que el más mínimo movimiento en toda la casa activará el escenario. Si añades un nuevo detector en la casa, no es necesario modificar el escenario o escenarios.

Aquí, un activador para encender una luz en el salón: `#genericType(LIGHT_STATE,#[Salon]#)# > 0`

#### Expresión

Si, en un escenario, quieres saber si hay una luz encendida en el salón, puedes hacer lo siguiente:

SI `#[Salon][Lumiere Canapé][Etat]# == 1 OU #[Salon][Lumiere Salon][Etat]# == 1 OU #[Salon][Lumiere Angle][Etat]# == 1`

O, dicho de forma más sencilla: SI `genericType(LIGHT_STATE,#[Salon]#) > 0` es decir, si hay una o varias luces encendidas en el salón.

Si mañana añades una luz en tu salón, ¡no hace falta que modifiques tus escenarios!


#### Acción

Si quieres encender todas las luces del salón, puedes crear una acción para cada luz:

```
#[Salon][Lumiere Canapé][On]#
#[Salon][Lumiere Salon][On]#
#[Salon][Lumiere Angle][On]#
```

O, dicho de forma más sencilla, crear una acción `genericType` con `LIGHT_ON` en `Salon`. Si mañana añades una luz en tu salón, ¡no hace falta que modifiques tus escenarios!


## Lista de tipos genéricos del núcleo

> **Consejo**
>
> - Puedes consultar esta lista directamente en Jeedom, en esta misma página, pulsando el botón **Lista** situado en la esquina superior derecha.

| **Otros (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPORIZADOR | Estado del temporizador | Información | numérico
| TIMER_STATE | Estado del temporizador (en pausa o no) | Información | binario, numérico
| SET_TIMER | Temporizador | Acción | control deslizante
| TIMER_PAUSE | Temporizador de pausa | Acción | otro
| TIMER_RESUME | Temporizador de reanudación | Acción | otro

| **Batería (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERÍA | Batería | Información | numérico
| BATTERY_CHARGING | Batería en carga | Información | binario

| **Cámara (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | URL de la cámara | Información | cadena
| CAMERA_RECORD_STATE | Estado de grabación de la cámara | Información | binario
| CAMERA_UP | Movimiento de la cámara hacia arriba | Acción | otro
| CAMERA_DOWN | Movimiento de la cámara hacia abajo | Acción | otro
| CAMERA_LEFT | Movimiento de la cámara hacia la izquierda | Acción | other
| CAMERA_RIGHT | Movimiento de la cámara hacia la derecha | Acción | otro
| CAMERA_ZOOM | Zoom de la cámara hacia delante | Acción | otro
| CAMERA_DEZOOM | Alejar la cámara | Acción | otro
| CAMERA_STOP | Detener cámara | Acción | otro
| CAMERA_PRESET | Preajuste de cámara | Acción | otro
| CAMERA_RECORD | Grabación de cámara | Acción |
| CAMERA_TAKE | Instantánea de la cámara | Acción |

| **Calefacción (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Estado de la calefacción por hilo piloto | Información | binario
| HEATING_ON | Calefacción con cable de control. Botón ON | Acción | otro
| HEATING_OFF | Calefacción con cable piloto. Botón OFF | Acción | otro
| HEATING_OTHER | Calefacción con cable de control Botón | Acción | otro

| **Electricidad (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POTENCIA | Potencia eléctrica | Información | numérico
| CONSUMO | Consumo eléctrico | Información | numérico
| VOLTAJE | Tensión | Información | numérico
| REBOOT | Reinicio | Acción | otros

| **Medio ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | Temperatura | Información | numérico
| AIR_QUALITY | Calidad del aire | Información | numérico
| BRIGHTNESS | Luminosidad | Información | numérico
| PRESENCE | Presencia | Información | binario
| SMOKE | Detección de humo | Información | binario
| HUMEDAD | Humedad | Información | numérico
| UV | UV | Información | numérico
| CO₂ | CO₂ (ppm) | Información | numérico
| CO | CO (ppm) | Información | numérico
| RUIDO | Sonido (dB) | Información | numérico
| PRESIÓN | Presión | Información | numérico
| WATER_LEAK | Fuga de agua | Información |
| FILTER_CLEAN_STATE | Estado del filtro | Información | binario

| **Genérico (id: Genérico)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDAD | Profundidad | Información | numérico
| DISTANCIA | Distancia | Información | numérico
| BUTTON | Botón | Información | binario, numérico
| GENERIC_INFO |  Genérico | Información |
| GENERIC_ACTION |  Genérico | Acción | other

| **Iluminación (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado de la luz | Información | binario, numérico
| LIGHT_BRIGHTNESS | Luz - Brillo | Información | numérico
| LIGHT_COLOR | Luz de color | Información | cadena
| LIGHT_STATE_BOOL | Estado de la luz (binario) | Información | binario
| LIGHT_COLOR_TEMP | Temperatura de color de la luz | Información | numérico
| LIGHT_TOGGLE | Interruptor de luz | Acción | otro
| LIGHT_ON | Luz: botón «On» | Acción | otro
| LIGHT_OFF | Botón de apagado de la luz | Acción | otro
| LIGHT_SLIDER | Control deslizante de luz | Acción | control deslizante
| LIGHT_SET_COLOR | Luz de color | Acción | color
| LIGHT_MODE | Modo de iluminación | Acción | otro
| LIGHT_SET_COLOR_TEMP | Luz - Temperatura de color | Acción |

| **Modo (id: Modo)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo Estado | Información | cadena
| MODE_SET_STATE | Cambiar modo | Acción | otro

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUMEN | Volumen | Información | numérico
| MEDIA_STATUS | Estado | Información | cadena
| MEDIA_ALBUM | Álbum | Información | cadena
| MEDIA_ARTIST | Artista | Información | cadena
| MEDIA_TITLE | Título | Información | cadena
| MEDIA_POWER | Energía | Información | cadena
| CHANNEL | Canal | Información | número, cadena
| MEDIA_STATE | Estado | Información | binario
| SET_VOLUME | Volumen | Acción | control deslizante
| SET_CHANNEL | Canal | Acción | otro, control deslizante
| MEDIA_PAUSE | Pausa | Acción | otro
| MEDIA_RESUME | Reproducción | Acción | otros
| MEDIA_STOP | Parar | Acción | otro
| MEDIA_NEXT | Siguiente | Acción | otros
| MEDIA_PREVIOUS | Anterior | Acción | otros
| MEDIA_ON | Activado | Acción | otro
| MEDIA_OFF | Desactivado | Acción | otro
| MEDIA_MUTE | Silenciar | Acción | otro
| MEDIA_UNMUTE | No silenciado | Acción | otro

| **El tiempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Tiempo - Temperatura | Información | numérico
| WEATHER_TEMPERATURE_MAX_2 | Condiciones meteorológicas del día +1, máxima del día +2 | Información | numérico
| WIND_SPEED | Viento (velocidad) | Información | numérico
| RAIN_TOTAL | Lluvia (acumulada) | Información | numérico
| RAIN_CURRENT | Lluvia (mm/h) | Información | numérico
| WEATHER_CONDITION_ID_4 | Condición meteorológica (id) día +4 | Información | numérico
| WEATHER_CONDITION_4 | Condiciones meteorológicas del día +4 | Información | cadena
| WEATHER_TEMPERATURE_MAX_4 | Tiempo: temperatura máxima del día +4 | Información | numérico
| WEATHER_TEMPERATURE_MIN_4 | Tiempo: temperatura mínima del día +4 | Información | numérico
| WEATHER_CONDITION_ID_3 | Condición meteorológica (id) día +3 | Información | numérico
| WEATHER_CONDITION_3 | Condiciones meteorológicas para el día 3 | Información | cadena
| WEATHER_TEMPERATURE_MAX_3 | Tiempo: temperatura máxima en 3 días | Información | numérico
| WEATHER_TEMPERATURE_MIN_3 | Tiempo: temperatura mínima en j+3 | Información | numérico
| WEATHER_CONDITION_ID_2 | Condición meteorológica (id) día +2 | Información | numérico
| WEATHER_CONDITION_2 | Condiciones meteorológicas para el día +2 | Información | cadena
| WEATHER_TEMPERATURE_MIN_2 | Tiempo: temperatura mínima del día +2 | Información | numérico
| WEATHER_HUMIDITY | Tiempo y humedad | Información | numérico
| WEATHER_CONDITION_ID_1 | Condición meteorológica (id) j+1 | Información | numérico
| WEATHER_CONDITION_1 | Condiciones meteorológicas del día siguiente | Información | cadena
| WEATHER_TEMPERATURE_MAX_1 | Tiempo: Temperatura máxima del día siguiente | Información | numérico
| WEATHER_TEMPERATURE_MIN_1 | Tiempo: Temperatura mínima del día +1 | Información | numérico
| WEATHER_CONDITION_ID | Condición meteorológica (ID) | Información | numérico
| WEATHER_CONDITION | Condiciones meteorológicas | Información | cadena
| WEATHER_TEMPERATURE_MAX | Tiempo: Temperatura máxima | Información | numérico
| WEATHER_TEMPERATURE_MIN | Tiempo: Temperatura mínima | Información | numérico
| WEATHER_SUNRISE | El tiempo al amanecer | Información | numérico
| WEATHER_SUNSET | El tiempo y la salida del sol | Información | numérico
| WEATHER_WIND_DIRECTION | El tiempo: dirección del viento | Información | numérico
| WEATHER_WIND_SPEED | Tiempo: velocidad del viento | Información | numérico
| WEATHER_PRESSURE | Tiempo - Presión | Información | numérico
| WIND_DIRECTION | Viento (dirección) | Información | numérico

| **Apertura (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Estado de la cerradura | Información | binario
| BARRIER_STATE | Portal (apertura) Estado | Información | binario
| GARAGE_STATE | Estado de la puerta del garaje | Información | binario
| APERTURA | Puerta | Información | binario
| OPENING_WINDOW | Ventana | Información | binario
| LOCK_OPEN | Cerradura con botón de apertura | Acción | otro
| LOCK_CLOSE | Cerrar cerradura | Acción | otro
| GB_OPEN | Botón de apertura de portón o garaje | Acción | otro
| GB_CLOSE | Botón de cierre de la puerta o el garaje | Acción | otro
| GB_TOGGLE | Pulsador de portal o garaje | Acción | otro

| **Toma de corriente (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Estado de la toma | Información | numérico, binario
| ENERGY_ON | Enchufe con botón de encendido | Acción | otros
| ENERGY_OFF | Enchufe con botón de apagado | Acción | otro
| ENERGY_SLIDER | Enchufe Slider | Acción |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Estado de la base | Información | binario
| DOCK | Volver a la base | Acción | Otros

| **Seguridad (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Estado de la sirena | Información | binario
| ALARM_STATE | Estado de la alarma | Información | binario, cadena
| ALARM_MODE | Modo de alarma | Información | cadena
| ALARM_ENABLE_STATE | Estado de la alarma activada | Información | binario
| INUNDACIÓN | Inundación | Información | binario
| SABOTAJE | Sabotaje | Información | binario
| SHOCK | Impacto | Noticias | binario, numérico
| SIREN_OFF | Botón de apagado de la sirena | Acción | otro
| SIREN_ON | Botón de activación de la sirena | Acción | otro
| ALARM_ARMED | Alarma activada | Acción | otro
| ALARM_RELEASED | Alarma desactivada | Acción | other
| ALARM_SET_MODE | Modo de alarma | Acción | otro

| **Termostato (id: Termostato)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Estado del termostato (BINARIO) (solo para el complemento «Termostato») | Información |
| THERMOSTAT_TEMPERATURE | Termostato: temperatura ambiente | Información | numérico
| THERMOSTAT_SETPOINT | Punto de consigna del termostato | Información | numérico
| THERMOSTAT_MODE | Modo del termostato (solo para el complemento «Termostato») | Información | cadena
| THERMOSTAT_LOCK | Bloqueo del termostato (solo para el complemento «Termostato») | Información | binario
| THERMOSTAT_TEMPERATURE_OUTDOOR | Termostato: temperatura exterior (solo para el complemento «Termostato») | Información | numérico
| THERMOSTAT_STATE_NAME | Estado del termostato (HUMAIN) (solo para el plugin del termostato) | Información | cadena
| THERMOSTAT_HUMIDITY | Termostato de humedad ambiental | Información | numérico
| HUMIDITY_SETPOINT | Punto de consigna de humedad | Información | control deslizante
| THERMOSTAT_SET_SETPOINT | Punto de consigna del termostato | Acción | control deslizante
| THERMOSTAT_SET_MODE | Modo del termostato (solo para el complemento «Termostato») | Acción | otro
| THERMOSTAT_SET_LOCK | Bloqueo del termostato (solo para el complemento «Termostato») | Acción | otro
| THERMOSTAT_SET_UNLOCK | Desbloqueo del termostato (solo para el complemento «Termostato») | Acción | other
| HUMIDITY_SET_SETPOINT | Punto de consigna de humedad | Acción | control deslizante

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Velocidad del ventilador - Estado | Información | numérico
| ROTATION_STATE | Estado de rotación | Información | numérico
| FAN_SPEED | Velocidad del ventilador | Acción | control deslizante
| ROTACIÓN | Rotación | Acción | control deslizante

| **Persiana (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Estado de la persiana | Información | binario, numérico
| FLAP_BSO_STATE | Estado de la persiana BSO | Información | binario, numérico
| FLAP_UP | Persiana: botón «Subir» | Acción | otro
| FLAP_DOWN | Botón para bajar la persiana | Acción | otro
| FLAP_STOP | Persiana: botón de parada | Acción |
| FLAP_SLIDER | Persiana con botón deslizante | Acción | deslizador
| FLAP_BSO_UP | Persiana BSO Botón Subir | Acción | otros
| FLAP_BSO_DOWN | Persiana BSO: botón «Bajar» | Acción | otro
