# Tipos de equipo
**Herramientas → Tipos de equipos**

Los sensores y actuadores en Jeedom son administrados por complementos, que crean equipos con comandos ** (sensor) o *Valores* (solenoide). Esto luego permite activar acciones basadas en el cambio de ciertos sensores, como encender una luz en la detección de movimiento. Pero Jeedom Core y complementos como **, **, *Hogar inteligente de Google*, *Hogar inteligente de Alexa* etc., no sé qué es este equipo : Un enchufe, una luz, una persiana, etc.

Para superar este problema, especialmente con asistentes de voz (*Enciende la luz de la habitación*), Core presentó el **Tipos genéricos**, utilizado por estos complementos.

Esto permite identificar un equipo mediante *La luz de la habitación* por ejemplo.

La mayoría de las veces, los tipos genéricos se establecen automáticamente al configurar su módulo (inclusión en Z-wave, por ejemplo). Pero puede haber ocasiones en las que necesite reconfigurarlos. La configuración de estos tipos genéricos se puede hacer directamente en ciertos complementos, o mediante comando en *Configuración avanzada* del mismo.

Esta página permite configurar estos Tipos Genéricos de una forma más directa y sencilla, e incluso ofrece asignación automática una vez que los dispositivos han sido correctamente asignados.

![Tipos de equipo](./images/coreGenerics.gif)

## Tipo de equipo

Esta página ofrece almacenamiento por tipo de equipo : Enchufe, luz, obturador, termostato, cámara, etc. Inicialmente, la mayor parte de su equipo se clasificará en **Equipo sin tipo**. Para asignarles un tipo, puede moverlos a otro tipo o hacer clic con el botón derecho en el equipo para moverlo directamente. El tipo de equipo no es realmente útil en sí mismo, siendo los más importantes los tipos de orden. Por tanto, puede tener un Equipo sin un Tipo, o un Tipo que no se corresponda necesariamente con sus comandos. Por supuesto, puede mezclar tipos de controles dentro del mismo equipo. Por ahora, es más un almacenamiento, una organización lógica, que quizás sirva en futuras versiones.

> **Consejo**
>
> - Cuando mueves equipo en el juego **Equipo sin tipo**, Jeedom sugiere que elimines los tipos genéricos de sus órdenes.
> - Puede mover varios equipos a la vez marcando las casillas de verificación a la izquierda de ellos.

## Tipo de comando

Una vez que un elemento de equipo se coloca en la posición correcta **, Pulsando sobre él accedes a la lista de sus pedidos, coloreados de diferente color si es un ** (Azul) o un *Valores* (Orange).

Al hacer clic con el botón derecho en un pedido, puede asignarle un Tipo genérico correspondiente a las especificaciones de este pedido (Tipo de información / acción, Numérico, Subtipo binario, etc).

> **Consejo**
>
> - El menú contextual de comandos muestra el tipo de equipo en negrita, pero aún permite asignar cualquier tipo genérico de cualquier tipo de equipo.

En cada dispositivo, tienes dos botones :

- **Tipos de auto** : Esta función abre una ventana que le ofrece los tipos genéricos adecuados según el tipo de equipo, los detalles del pedido y su nombre. Luego puede ajustar las propuestas y desmarcar la aplicación para ciertos comandos antes de aceptar o no. Esta función es compatible con la selección mediante casillas de verificación.

- **Tipos de reinicio** : Esta función elimina los tipos genéricos de todos los comandos del equipo.

> **Atención**
>
> No se realizan cambios antes de guardar, con el botón en la parte superior derecha de la página.

## Tipos y escenarios genéricos

.2, el Core ha integrado los tipos genéricos en los escenarios. De esta forma se puede desencadenar un escenario si se enciende una lámpara en una habitación, si se detecta movimiento en la casa, apagar todas las luces o cerrar todas las persianas con una sola acción, etc. Además, si agrega un equipo, solo debe indicar los tipos correctos en sus pedidos, no será necesario editar dichos escenarios.

#### Desencadenar

Puede activar un escenario desde sensores. Por ejemplo, si tiene detectores de movimiento en la casa, puede crear un escenario de alarma con cada detector activado : ''#[Salón][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. En tal escenario, necesitará todos sus detectores de movimiento, y si agrega uno, tendrá que agregarlo a los disparadores. Lógica.

Los tipos genéricos le permiten usar un solo disparador : ''#genericType(PRESENCE)# == 1`. Aquí, no se indica ningún objeto, por lo que el más mínimo movimiento en toda la casa desencadenará el escenario. Si agrega un nuevo detector en la casa, no es necesario editar los escenarios).

Aquí, un disparador al encender una luz en la sala de estar : ''#genericType(LUZ_ESTADO,#[Salón]#)# > 

#### Expression

Si, en un escenario, desea saber si hay una luz encendida en la sala de estar, puede hacer :

#[Salón][Lumiere Canapé][Estado]# == 1 O #[Salón][Lumiere Salon][Estado]# == 1 O #[Salón][Lumiere Angle][Estado]# == 1`

O mas simplemente : IF `genericType (LIGHT_STATE,#[Salón]#) > 0` o si una o más luces están encendidas en la sala de estar.

Si mañana añades luz en tu salón, no es necesario que retoques tus escenarios !


#### Action

Si desea encender todas las luces en la sala de estar, puede crear una acción de luz:

`` ``
#[Salón][Lumiere Canapé][]#
#[Salón][Lumiere Salon][]#
#[Salón][Lumiere Angle][]#
`` ``

O más simplemente, cree una acción `genericType` con` LIGHT_ON` en` Salon`. Si mañana añades luz en tu salón, no es necesario que retoques tus escenarios !


## Lista de tipos de núcleos genéricos

> **Consejo**
>
> - Puede encontrar esta lista directamente en Jeedom, en esta misma página, con el botón **** arriba a la derecha.

| **Otro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Temporizador de estado |  | numeric
| TIMER_ESTADO | Estado del temporizador (pausar o no) |  | binario, numérico
| ESTABLECER_TIMER |  | Valores | slider
|  | Pausar temporizador | Valores | other
|  | Reanudar el temporizador | Valores | other

| **Batería (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
| BATERIA CARGANDO | Bateria cargando |  | binary

| **Cámara (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CÁMARA_URL | URL de la cámara |  | string
| CAMERA_RECORD_ESTADO | Estado de grabación de la cámara |  | binary
| CÁMARA_ARRIBA | Movimiento de la cámara hacia arriba | Valores | other
| CÁMARA HACIA ABAJO | Movimiento de la cámara hacia abajo | Valores | other
| CÁMARA_IZQUIERDA | Movimiento de la cámara hacia la izquierda | Valores | other
| CÁMARA_DERECHA | Movimiento de la cámara hacia la derecha | Valores | other
| CÁMARA_ZOOM | Acercar la cámara hacia adelante | Valores | other
| CÁMARA_DEZOOM | Zoom de la cámara hacia atrás | Valores | other
| CÁMARA_DETENER | Detener la cámara | Valores | other
| CÁMARA_PRESET | Preajuste de la cámara | Valores | other
|  | Grabación de cámara | Valores |
| CÁMARA_TOMAR | Cámara de instantáneas | Valores |

| **Calefacción (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CALEFACCIÓN_ESTADO | Estado de calentamiento del hilo piloto |  | binary
| CALEFACCIÓN_ENCENDIDO | Botón de encendido del calentamiento del hilo piloto | Valores | other
| CALEFACCIÓN_APAGADO | Botón de apagado del calentamiento del hilo piloto | Valores | other
|  | Botón de hilo piloto de calentamiento | Valores | other

| **Electricidad (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Energia electrica |  | numeric
|  | El consumo de energía |  | numeric
|  |  |  | numeric
|  | Reiniciar | Valores | other

| **Entorno (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | TEMPERATURA |  | numeric
| CALIDAD DEL AIRE | Calidad del aire |  | numeric
|  |  |  | numeric
|  | PRESENCIA |  | binary
|  | Deteccion de humo |  | binary
|  |  |  | numeric
|  |  |  | numeric
|  | ) |  | numeric
|  | CO2 (ppm) |  | numeric
|  | Sonido (dB) |  | numeric
|  |  |  | numeric
| GOTERA DE AGUA | Fuga de agua |  |
| FILTRO_LIMPIAR_ESTADO | Estado de filtro |  | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Profundidad |  | numeric
|  |  |  | numeric
|  |  |  | binario, numérico
| INFORMACIÓN_GENÉRICA |  Genérico |  |
| ACCIÓN_GENÉRICA |  Genérico | Valores | other

| **Luz (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LUZ_ESTADO | Estado de luz |  | binario, numérico
| LUZ_BRILLO | Brillo de luz |  | numeric
| COLOR CLARO | Color claro |  | string
| LUZ_ESTADO_BOOL | Estado de luz (binario) |  | binary
| LUZ_COLOR_TEMP | Color de temperatura de luz |  | numeric
| LUZ_TOGGLE | Alternar luz | Valores | other
| LUCES ENCENDIDAS | Botón de luz encendido | Valores | other
| LUZ APAGADA | Botón de luz apagado | Valores | other
| LUZ_SLIDER | Luz deslizante | Valores | slider
| LUZ_SET_COLOR | Color claro | Valores | color
| MODO_LUZ | Modo de luz | Valores | other
| LUZ_SET_COLOR_TEMP | Color de temperatura de luz | Valores |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODO_ESTADO | Modo de estado |  | string
|  | Modo de cambio | Valores | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
| MEDIO_ESTADO |  |  | string
|  |  |  | string
| ARTISTA_MEDIA |  |  | string
| MEDIO_TITLE |  |  | string
|  |  |  | string
|  |  |  | numérico, cadena
| MEDIO_ESTADO | Estado |  | binary
| SET_VOLUMEN |  | Valores | slider
|  |  | Valores | otro control deslizante
| MEDIOS_PAUSA |  | Valores | other
| MEDIOS_RESUME |  | Valores | other
|  |  | Valores | other
| MEDIOS_SIGUIENTE |  | Valores | other
| MEDIOS_ANTERIORES | Anterior | Valores | other
|  |  | Valores | other
| MEDIOS_DESACTIVADOS |  | Valores | other
| MEDIOS_MUTE |  | Valores | other
|  | Sin silencio | Valores | other

| **Clima (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIEMPO_TEMPERATURA | Temperatura del tiempo |  | numeric
| TIEMPO_TEMPERATURA_MAX_2 | Condición meteorológica d + 1 máx d + 2 |  | numeric
| VELOCIDAD DEL VIENTO | Velocidad del viento) |  | numeric
| LLUVIA_TOTAL | Lluvia (acumulación) |  | numeric
| LLUVIA_ACTUAL | Lluvia (mm / h) |  | numeric
| CLIMA_CONDICIÓN_ID_4 | Condición meteorológica (id) d + 4 |  | numeric
| CLIMA_CONDICIÓN_4 | Condición meteorológica d + 4 |  | string
| TIEMPO_TEMPERATURA_MAX_4 | Clima Temperatura máxima d + 4 |  | numeric
| TIEMPO_TEMPERATURA_MIN_4 | Tiempo Temperatura min d + 4 |  | numeric
| CLIMA_CONDICIÓN_ID_3 | Condición meteorológica (id) d + 3 |  | numeric
| CLIMA_CONDICIÓN_3 | Condición meteorológica d + 3 |  | string
| TIEMPO_TEMPERATURA_MAX_3 | Clima Temperatura máxima d + 3 |  | numeric
| TIEMPO_TEMPERATURA_MIN_3 | Tiempo Temperatura min d + 3 |  | numeric
| CLIMA_CONDICIÓN_ID_2 | Condición meteorológica (id) d + 2 |  | numeric
| CLIMA_CONDICIÓN_2 | Condición meteorológica d + 2 |  | string
| TIEMPO_TEMPERATURA_MIN_2 | Tiempo Temperatura min d + 2 |  | numeric
| CLIMA_HUMEDAD | Humedad del tiempo |  | numeric
| CLIMA_CONDICIÓN_ID_1 | Condición meteorológica (id) d + 1 |  | numeric
| CLIMA_CONDICIÓN_1 | Condición meteorológica d + 1 |  | string
| CLIMA_TEMPERATURA_MAX_1 | Clima Temperatura máxima d + 1 |  | numeric
| TIEMPO_TEMPERATURA_MIN_1 | Clima Temperatura min d + 1 |  | numeric
| TIEMPO_CONDICIÓN_ID | Condición climática (id) |  | numeric
| CONDICIÓN CLIMÁTICA | Condición climática |  | string
| TIEMPO_TEMPERATURA_MAX | Clima Temperatura máxima |  | numeric
| TIEMPO_TEMPERATURA_MIN | Clima Temperatura min |  | numeric
| CLIMA_AMANECER | Tiempo al atardecer |  | numeric
| TIEMPO_PUESTA DEL SOL | Tiempo del amanecer |  | numeric
| CLIMA_VIENTO_DIRECCIÓN | Dirección del viento tiempo |  | numeric
| TIEMPO_VIENTO_VELOCIDAD | Tiempo de velocidad del viento |  | numeric
| CLIMA_PRESIÓN | Presión meteorológica |  | numeric
| DIRECCIÓN DEL VIENTO | Dirección del viento) |  | numeric

| **Apertura (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BLOQUEO_ESTADO | Bloqueo de estado |  | binary
| BARRERA_ESTADO | Estado del portal (apertura) |  | binary
| GARAJE_ESTADO | Estado del garaje (apertura) |  | binary
|  |  |  | binary
| ABRIENDO_VENTANA | Ventana |  | binary
| BLOQUEAR_ABRIR | Botón de bloqueo abierto | Valores | other
|  | Cerrar el botón de bloqueo | Valores | other
|  | Botón de apertura de puerta o garaje | Valores | other
|  | Botón de cierre de puerta o garaje | Valores | other
|  | Interruptor de botón de puerta o garaje | Valores | other

| **Zócalo (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ESTADO_ENERGÍA | Toma de estado |  | numérico, binario
| ENERGÍA_ON | En el enchufe del botón | Valores | other
| ENERGÍA_APAGADA | Botón de enchufe desactivado | Valores | other
| DESLIZADOR DE ENERGÍA | Toma deslizante | Valores |

| **Robot (identificación: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MUELLE_ESTADO | Base estatal |  | binary
|  | De regreso a la base | Valores | other

| **Seguridad (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_ESTADO | Estado de la sirena |  | binary
| ALARMA_ESTADO | Estado de alarma |  | binario, cadena
| MODO_ALARMA | Modo de alarma |  | string
| ALARMA_ENABLE_ESTADO | Estado de alarma activado |  | binary
|  |  |  | binary
|  |  |  | binary
|  |  |  | binario, numérico
|  | Botón de sirena apagado | Valores | other
| SIREN_EN | Botón de sirena encendido | Valores | other
| ALARMA_ARMADO | Alarma armada | Valores | other
| ALARMA_LIBERADA | Alarma lanzada | Valores | other
| ALARMA_ESTABLECER_MODO | Modo de alarma | Valores | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TERMOSTATO_ESTADO | Estado del termostato (BINARIO) (solo para termostato de complemento) |  |
| TERMOSTATO_TEMPERATURA | Termostato de temperatura ambiente |  | numeric
| TERMOSTATO_CONSIGNA | Termostato de consigna |  | numeric
| MODO_TERMOSTATO | Modo de termostato (solo para termostato de complemento) |  | string
| TERMOSTATO_BLOQUEO | Termostato de bloqueo (solo para termostato de complemento) |  | binary
| TERMOSTATO_TEMPERATURA_EXTERIOR | Termostato de temperatura exterior (solo para termostato de complemento) |  | numeric
| TERMOSTATO_ESTADO_NOMBRE | Estado del termostato (HUMAN) (solo para termostato de complemento) |  | string
| TERMOSTATO_HUMEDAD | Termostato de humedad ambiental |  | numeric
| HUMEDAD_CONSIGNA | Establecer humedad |  | slider
| TERMOSTATO_SET_SETPOINT | Termostato de consigna | Valores | slider
| TERMOSTATO_FIJAR_MODO | Modo de termostato (solo para termostato de complemento) | Valores | other
| TERMOSTATO_SET_LOCK | Termostato de bloqueo (solo para termostato de complemento) | Valores | other
| TERMOSTATO_SET_UNLOCK | Desbloquear el termostato (solo para el termostato enchufable)) | Valores | other
| HUMEDAD_SET_SETPOINT | Establecer humedad | Valores | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Estado de la velocidad del ventilador |  | numeric
| ROTACIÓN_ESTADO | Rotación de estado |  | numeric
| VELOCIDAD DEL VENTILADOR | Velocidad del ventilador | Valores | slider
|  | Girar | Valores | slider

| **Panel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_ESTADO | Panel de estado |  | binario, numérico
| FLAP_BSO_ESTADO | Panel de estado de BSO |  | binario, numérico
| FLAP_ARRIBA | Botón de panel hacia arriba | Valores | other
| FLAP_ABAJO | Botón de panel hacia abajo | Valores | other
| FLAP_DETENER | Botón de parada del obturador | Valores |
|  | Panel de botones deslizantes | Valores | slider
| FLAP_BSO_ARRIBA | Botón arriba del panel BSO | Valores | other
| FLAP_BSO_ABAJO | Botón Abajo del panel BSO | Valores | other
