
Cambios
=========


=====

- 


=====

- Rotación automática de la clave API de los usuarios administradores cada 3 meses.. Puedo desactivarlo (pero no es recomendable) en la gestión de usuarios. Tenga en cuenta que esta actualización lanza una rotación de claves API para usuarios administradores.
- Capacidad para ingresar información global para su hogar en la administración Jeedom (posición geográfica, altitud ...) para evitar tener que volver a ingresarla en complementos al crear equipos.
- Actualización del repositorio en smart
- 
- 

3.3.39
=====

- Se cambió el nombre de la variable $ key a $ key2 en el evento de clase
- Limpiar el plugin / widgy / escenario enviando código al mercado (ahorra varios segundos en mostrar los complementos)
- Corrección de una advertencia en la función lastByween
- Mejor consideración de los widgys de complementos
- Optimización del cálculo de salud en el intercambio

>**IMPORTANTE**
>
>Esta actualización soluciona una inquiyud que puede evitar cualquier registro de historial a partir del 1 de enero de 2020, es más que altamente recomendable

3.3.38
=====

- Adición de compatibilidad global de Jeedom DNS con una conexión a interny 4G. (Importante si usa Jeedom DNS es que tiene una conexión 4g, debe marcar la casilla configurar Jeedom dns en la casilla correspondiente).
- Correcciones ortográficas.
- Arreglo de seguridad

3.3.37
=====

- Correcciones de errores

3.3.36
=====

- Adición de redondeo en el número de días desde el último cambio de batería
- Correcciones de errores

3.3.35
=====

- Correcciones de errores
- Posibilidad de instalar complementos directamente desde el mercado

3.3.34
=====

- Se corrigió un error que podía evitar que el estado de la batería volviera a subir
- Corrección de un error en las yiquyas en las interacciones.
- El estado de "tiempo de espera" (no comunicación) del equipo ahora tiene prioridad sobre el estado de "advertencia" o "peligro""
- Corrección de errores en las copias de seguridad en la nube

3.3.33
=====

- Correcciones de errores

3.3.32
=====

- Correcciones de errores
- Soporte móvil para controles deslizantes en diseños
- INTELIGENTE : optimización de la gestión de swap

3.3.31
=====

- Correcciones de errores

3.3.30
=====

- Corrección de un error en la visualización de sesiones de usuario.
- Actualización de la documentación
- Eliminación de la actualización de gráficos en tiempo real, luego de numerosos errores reportados
- Corrección de un error que podría impedir la visualización de ciertos registros.
- Corrección de un error en el servicio de monitoreo.
- Corrección de un error en la página &quot;Análisis del equipo&quot;, la fecha de actualización de la batería ahora es correcta 
- Mejora de la acción remove_inat en escenarios

3.3.29
=====

- Corrección de la desaparición de la fecha de la última verificación de actualización
- Se corrigió un error que podía bloquear las copias de seguridad en la nube
- Corrección de un error en el cálculo del uso de las variables si están en forma : variable (foo, MyValue)


3.3.28
=====

- Se corrigió un error de rueda infinita en la página de actualizaciones
- Varias correcciones y optimizaciones.

3.3.27
=====

- Corrección de un error en la traducción de los días en francés.
- Estabilidad mejorada (reinicio automático del servicio MySql y watchdog para verificar la hora de inicio)
- Correcciones de errores
- Deshabilitar acciones en pedidos al editar diseños, vistas o paneles

3.3.26
=====

- Correcciones de errores
- Corrección de un error en el lanzamiento múltiple del escenario
- Corrección de un error en las alertas sobre el valor de los pedidos.

3.3.25
=====

- Correcciones de errores
- Cambiar la línea de tiempo al modo de tabla (debido a errores en la biblioteca independiente de Jeedom)
- Adición de clases para soportes de color en el complemento de modo


3.3.24
=====

-   Corrección de un error en la pantalla del número de actualizaciones.
-	Se eliminó la edición de código HTML de la configuración avanzada de comandos debido a demasiados errores
-	Correcciones de errores
-	Mejora de la ventana de selección de iconos.
-	Actualización automática de la fecha de cambio de batería si la batería es más del 90% y 10% más alta que el valor anterior
-	Adición de un botón en la administración para restablecer los derechos y lanzar una verificación Jeedom (derecha, cron, base de datos ...)
-	Eliminación de opciones de visibilidad avanzadas para equipos en el tablero de instrumentos / vista / diseño / móvil. Ahora, si desea ver o no el equipo en el tablero de instrumentos / móvil, simplemente marque o no la casilla de visibilidad general. Para vistas y diseño, simplemente coloque o no el equipo en él

3.3.22
=====

- Correcciones de errores
- Reemplazo de pedidos mejorado (en vistas, plan y plan3d)
- Se corrigió un error que podía evitar abrir ciertos equipos de complemento (alarma o tipo virtual)

3.3.21
=====

- Se corrigió un error por el cual la visualización del tiempo podía exceder las 24 h
- Corrección de un error en la actualización de los resúmenes de diseño.
- Corrección de un error en la gestión de los niveles de alertas en ciertos widgys durante la actualización del valor
- Se corrigió la visualización del equipo deshabilitado en algunos complementos
- Corrección de un error al indicar el cambio de batería en Jeedom
- Visualización mejorada de registros al actualizar Jeedom
- Corrección de errores durante la actualización de la variable (que no siempre iniciaba los escenarios o no activaba una actualización de los comandos en todos los casos)
- Se corrigió un error en las copias de seguridad de la nube, o la duplicidad no se instalaba correctamente
- Mejora de TTS interno en Jeedom
- Mejora del sistema de verificación de sintaxis cron


3.3.20
=====

- Corrección de un error en los escenarios o podrían permanecer bloqueados en &quot;en progreso&quot; mientras están desactivados
- Se solucionó un problema con el lanzamiento de un escenario no planificado
- Corrección de errores de zona horaria

3.3.19
=====
- Corrección de errores (especialmente durante la actualización)


3.3.18
=====
- Correcciones de errores

3.3.17
=====

- Corrección de un error en las copias de seguridad de samba

3.3.16
=====

-   Posibilidad de eliminar una variable.
-   Adición de una pantalla 3D (bya)
-   Rediseño del sistema de respaldo en la nube (respaldo incremental y encriptado).
-   Agregar un sistema integrado de toma de notas (en Análisis -&gt; Nota).
-   Adición de la noción de yiquya en el equipo (se puede encontrar en la configuración avanzada del equipo).
-   Adición de un sistema de historial sobre la eliminación de pedidos, equipos, objyos, vista, diseño, diseño 3D, escenario y usuario..
-   Adición de la acción Jeedom_reboot para iniciar un reinicio de Jeedom.
-   Agregar opción en la ventana de generación cron.
-   Ahora se agrega un mensaje si se encuentra una expresión no válida al ejecutar un escenario.
-   Agregar un comando en los escenarios : value (orden) permite tener el valor de una orden si no está dada automáticamente por Jeedom (caso cuando se almacena el nombre de la orden en una variable).
-   Adición de un botón para actualizar los mensajes del centro de mensajes..
-   Agregue en la configuración de la acción sobre el valor de un comando un botón para buscar una acción interna (escenario, pausa ...).
-   Adición de una acción &quot;Restablecer a cero del IS&quot; en los escenarios
-   Posibilidad de agregar imágenes en segundo plano en las vistas
-   Posibilidad de agregar imágenes de fondo en objyos
-   La información de actualización disponible ahora está oculta para usuarios no administradores
-   Soporte mejorado para () en el cálculo de expresiones
-   Posibilidad de editar los escenarios en modo texto / json
-   Adición en la página de salud de una verificación de espacio libre para el tmp Jeedom
-   Posibilidad de agregar opciones en informes
-   Adición de un latido por complemento y reinicio automático de daemon en caso de problemas
-   Adición de oyentes en la página del motor de tareas
-   Optimizaciones
-   Posibilidad de consultar los registros en versión móvil (wepapp)
-   Adición de una yiquya de acción en los escenarios (ver documentación)
-   Posibilidad de tener una vista de pantalla complya agregando &quot;&amp; fullscreen = 1&quot; en la url
-   Adición de la última comunicación en los escenarios (para tener la última fecha de comunicación de un equipo)
-   Actualización en tiempo real de gráficos (simple, no calculado o líneas de tiempo)
-   Posibilidad de eliminar un elemento de la configuración de diseño.
-   Posibilidad de tener un informe sobre el nivel de la batería (informe del equipo)
-   Los widgys de escenario ahora se muestran por defecto en el tablero
-   Cambie el tono de los widgys por horizontal 25 a 40, vertical 5 a 20 y margen 1 a 4 (puede restablecer los valores anteriores en la configuración de Jeedom, pestaña widgy)
-   Posibilidad de poner un icono en los escenarios.
-   Incorporación de la gestión de demonios en el motor de tareas.
-   Adición de la función color_gradient en los escenarios.

3.2.16
=====

- Corrección de un error durante la instalación de dependencia de ciertos complementos en smart

3.2.15
=====

- Corrección de un error al guardar el equipo.

3.2.14
=====

- Preparación para evitar un error al cambiar a 3.3.X
- Corrección de un problema al solicitar soporte para complementos de terceros

3.2.12
=====

- Correcciones de errores
- Optimizaciones

3.2.11
=====

- Correcciones de errores.

3.2.10
=====

- Correcciones de errores.
- Sincronización mejorada con el mercado..
- Mejora del proceso de actualización en particular en la copia de archivos que ahora comprueba el tamaño del archivo copiado.
- Corrección de errores en las funciones stateDuration, lastStateDuration y lastChangeStateDuration (gracias @kiboost).
- Optimización del cálculo del gráfico de enlaces y el uso de variables..
- Mejora de la ventana de dyalles de la tarea cron que ahora muestra el escenario, así como la acción a realizar para las tareas doIn (gracias @kiboost).

3.2.9
=====

- Correcciones de errores
- Corrección de un error en los íconos del editor de archivos y en el probador de expresiones
- Corrección de errores en los oyentes.
- Adición de una alerta si un complemento bloquea crons
- Corrección de un error en el sistema de monitoreo en la nube si la versión del agente es menor a 3.X.X

3.2.8
=====

- Correcciones de errores
- Adición de una opción en la administración de Jeedom para especificar el rango de ip local (útil en instalaciones de tipo docker)
- Corrección de un error en el cálculo del uso de variables.
- Adición de un indicador en la página de salud que indica el número de procesos que se matan por falta de memoria (en general indica que la libertad está demasiado cargada)
- Editor de archivos mejorado

3.2.7
=====

- Correcciones de errores
- Actualización de documentos
- Posibilidad de usar las yiquyas en las condiciones de los bloques "A" e "IN""
- Corrección de errores de categorías de mercado para widgys / scripts / escenarios...

3.2.6
=====

- Correcciones de errores
- Actualización de documentos
- Estandarización de los nombres de ciertos pedidos en los escenarios.
- Optimización del rendimiento

3.2.5
=====

- Correcciones de errores
- Reactivación de interacciones (inactivo debido a la actualización)

3.2.4
=====

- Correcciones de errores
- Corrección de un error en cierto modal en español
- Corrección de un error de cálculo en time_diff
- Preparación para el futuro sistema de alerta.

3.2.3
=====

-   Corrección de errores en funciones mín. / Máx.....
-   Exportación mejorada de gráficos y visualización en modo tabla

3.2.2
=====

-   Eliminación del antiguo sistema de actualización de widgys (en desuso desde la versión 3.0). Atención, si su widgy no utiliza el nuevo sistema, existe el riesgo de mal funcionamiento (duplicación del mismo en este caso). Ejemplo de widgy [aquí] (https://github.com/Jeedom/core/tree/bya/core/template/salpicadero)
-   Posibilidad de mostrar los gráficos en forma de tabla o exportarlos en csv o xls

-   Los usuarios ahora pueden agregar su propia función php para escenarios. Ver documentación de escenarios para implementación

-   JEED-417 : adición de una función time_diff en los escenarios

-   Adición de un ryraso configurable antes de la respuesta en las interacciones (permite esperar a que se produzca la ryroalimentación de estado, por ejemplo)

-   JEED-365 : Eliminación del &quot;comando de información del usuario&quot; para ser reemplazado por acciones en el mensaje. Le permite iniciar varios comandos diferentes, iniciar un escenario ... Atención, si tenía un &quot;comando de información del usuario&quot;, debe reconfigurarse.

-   Agregue una opción para abrir fácilmente un acceso al soporte (en la página del usuario y al abrir un ticky)

-   Corrección de un error de derechos después de la restauración de una copia de seguridad

-   Actualizando traducciones

-   Actualización de la biblioteca (jquery y highcharts)

-   Posibilidad de prohibir un orden en interacciones
    automático

-   Interacciones automáticas mejoradas

-   Corrección de errores en el manejo de sinónimos de interacciones

-   Adición de un campo de búsqueda de usuario para conexiones LDAP / AD
    (hace que Jeedom AD sea compatible)

-   Correcciones ortográficas (gracias a dab0u por su enorme trabajo)

-   JEED-290 : Ya no podemos conectarnos con identificadores por
    predyerminado (admin / admin) de forma remota, solo la red local está autorizada

-   JEED-186 : Ahora podemos elegir el color de fondo en el
    diseños

-   Para el bloque A, posibilidad de establecer una hora entre las 12:01 a.m. y las 12:59 a.m.
    simplemente poniendo los minutos (ex 30 para 00:30)

-   Agregar sesiones activas y dispositivos registrados en el
    página de perfil de usuario y página de administración
    usuarios

-   JEED-284 : la conexión permanente ahora depende de una clave
    usuario único y dispositivo (en lugar de usuario)

-   JEED-283 : agregando un modo * rescate * a la libertad agregando &amp; rescue = 1
    en la url

-   JEED-8 : adición del nombre del escenario en el título de la página durante
    edición

-   Optimización de cambios organizacionales (tamaño de widgys,
    posición del equipo, posición de los controles) en el tablero de instrumentos
    y las vistas. Atención ahora las modificaciones no son
    guardado solo al salir del modo de edición.

-   JEED-18 : Agregar registros al abrir un ticky para admitir

-   JEED-181 : adición de un comando de nombre en los escenarios para tener
    el nombre del pedido o equipo u objyo

-   JEED-15 : Agregue batería y alerta en la aplicación web

-   Corrección de errores para mover objyos de diseño en Firefox

-   JEED-19 : Durante una actualización, ahora es posible
    actualizar el script de actualización antes de actualizar

-   JEED-125 : enlace agregado para restablecer la documentación
    contraseña

-   JEED-2 : Gestión del tiempo mejorada durante un reinicio

-   JEED-77 : Adición de gestión de variables en la API http

-   JEED-78 : adición de la función de yiquya para escenarios. Ten cuidado ahí
    debe en los escenarios utilizando las yiquyas pasar de \ #montag \#
    yiquyar (montag)

-   JEED-124 : Corregir la gestión de los tiempos de espera del escenario.

-   Correcciones de errores

-   Capacidad para desactivar una interacción.

-   Agregar un editor de archivos (reservado para
    usuarios experimentados)

-   Adición de los tipos genéricos &quot;Estado de luz&quot; (binario), &quot;Luz
    Temperatura de color &quot;(Información),&quot; Temperatura de color claro &quot;(Acción)

-   Capacidad para hacer que las palabras sean obligatorias en una interacción.

3.1.7
=====

-   Corrección de errores (especialmente en registros y
    funciones estadísticas)

-   Mejora del sistema de actualización con una página de notas.
    versión (que debe verificar usted mismo antes de cada actualización
    día !!!!)

-   Corrección de un error que recuperó los registros durante las restauraciones.

3.1
===

-   Correcciones de errores

-   Optimización global de Jeedom (en clases de carga de
    plugins, tiempo casi dividido por 3)

-   Soporte de Debian 9

-   Modo de una página (cambio de página sin volver a cargar toda la página, solo
    la parte que cambia)

-   Agregue una opción para ocultar objyos en el tablero pero que
    vamos a tenerlos siempre en la lista

-   Haga doble clic en un nodo en el gráfico de enlace (excepto para
    variables) trae a su página de configuración

-   Posibilidad de colocar el texto a la izquierda / derecha / centro en el
    diseños para texto / vista / elementos de diseño

-   Agregar resúmenes de objyos en el tablero (lista de objyos
    a la izquierda)

-   Agregar interacciones de tipo "notifícame si"

-   Revisión de la página de inicio del escenario

-   Agregar historial de comandos para SQL o comandos del sistema
    en la interfaz Jeedom

-   Posibilidad de tener gráficos de historias de orden en
    webapp (manteniendo presionado el comando)

-   Adición del progreso de la actualización de la aplicación web

-   Recuperación en caso de error de actualización de la aplicación web

-   Eliminación de escenarios &quot;simples&quot; (redundantes con la configuración
    pedidos anticipados)

-   Agregue sombreado en gráficos para distinguir días

-   Rediseño de la página de interacciones.

-   Rediseño de la página de perfil

-   Rediseño de la página de administración.

-   Agregar una &quot;salud&quot; en los objyos

-   Corrección de errores en el nivel de batería del equipo.

-   Adición de método en el núcleo para la gestión de comandos muertos
    (luego debe implementarse en el complemento)

-   Posibilidad de registrar comandos de texto

-   En la página del historial ahora puede hacer el gráfico
    de un cálculo

-   Agregar una gestión de fórmulas de cálculo para historiales

-   Actualización de toda la documentación. :

    -   Todos los documentos han sido revisados

    -   Eliminación de imágenes para facilitar la actualización y
        plurilingüe

-   Más opciones posibles en la configuración de tamaño de zona en el
    vistas

-   Posibilidad de elegir el color del texto del resumen del objyo

-   Adición de una acción remove \ _inat en los escenarios que permiten
    cancelar toda la programación de los bloques DANS / A

-   Posibilidad de elegir diseños para widgys al pasar el mouse por encima
    posición del widgy

-   Agregar un parámyro de respuesta \ _cmd en las interacciones para especificar
    la identificación del comando que se usará para responder

-   Agregar una línea de tiempo en la página del historial (se debe prestar atención
    activado en cada comando y / o escenario que desee
    ver aparecer)

-   Posibilidad de vaciar los eventos de la línea de tiempo.

-   Posibilidad de vaciar las IP prohibidas

-   Corrección / mejora de la gestión de cuentas de usuario

    -   Posibilidad de eliminar una cuenta de administrador básica

    -   Evitar que el último administrador vuelva a la normalidad

    -   Mayor seguridad para evitar la eliminación de la cuenta con
        cuál está conectado

-   Posibilidad en la configuración avanzada de equipos para poner
    el diseño de los comandos en los widgys en modo tabla en
    elegir para cada pedido la caja o ponerla

-   Capacidad para reorganizar widgys de equipos desde
    panel de control (en modo de edición, haga clic derecho en el widgy)

-   Cambiar el tono de los widgys (de 40 \ * 80 a 10 \ * 10). Ten cuidado
    impactará el diseño en su tablero / vista / diseño

-   Posibilidad de dar un tamaño de 1 a 12 a los objyos en el
    salpicadero

-   Capacidad para iniciar independientemente acciones de escenario (y
    modo de tipo de complemento / alarma si es compatible) en paralelo con los demás

-   Posibilidad de agregar un código de acceso a un diseño

-   Adición de un perro guardián independiente de Jeedom para verificar el estado de
    MySql y Apache

3.0.11
======

-   Se corrigieron errores en las solicitudes de &quot;tiempo de espera&quot;

3.0.10
======

-   Corrección de errores en la interfaz para configurar interacciones

3.0
===

-   Supresión del modo esclavo

-   Capacidad para desencadenar un escenario en un cambio de
    variable

-   Las actualizaciones variables ahora activan la actualización
    pedidos de equipos virtuales (necesita la última versión
    plugin)

-   Posibilidad de tener un icono en los comandos de tipo de información

-   Habilidad en los comandos para mostrar el nombre y el ícono

-   Adición de una acción &quot;alerta&quot; sobre escenarios : mensaje arriba en
    Jeedom

-   Adición de una acción &quot;emergente&quot; en escenarios : mensaje para validar

-   Los widgys de comando ahora pueden tener un método
    actualización que evita una llamada ajax a Jeedom

-   Los widgys de escenario ahora se actualizan sin llamadas ajax
    para obtener el widgy

-   El resumen global y las partes ahora se actualizan sin apelación
    ajax

-   Un clic en un elemento de un resumen de automatización del hogar lo lleva a una vista
    dyallado de ello

-   Ahora puede poner resúmenes de tipo
    texto

-   Cambio del control deslizante bootstraps a control deslizante (corrección de errores
    evento de doble control deslizante)

-   Guardado automático de vistas al hacer clic en el botón &quot;ver el
    resultar"

-   Posibilidad de tener los documentos localmente

-   Los desarrolladores de terceros pueden agregar su propio sistema de
    gestión de entradas

-   Rediseño de la configuración de derechos de usuario (todo está en el
    página de gestión de usuarios)

-   Actualización de Libs : jquery (en 3.0), jquery mobile, hightstock
    y clasificador de tablas, font-awesome

-   Gran mejora en los diseños.:

    -   Ahora se puede acceder a todas las acciones desde un
        clic derecho

    -   Posibilidad de agregar un solo pedido

    -   Posibilidad de agregar una imagen o secuencia de video

    -   Posibilidad de agregar zonas (ubicación en la que se puede hacer clic) :

        -   Área de tipo macro : lanza una serie de acciones durante un
            haga clic en él

        -   Área de tipo binario : lanza una serie de acciones durante un
            haga clic en él según el estado de un pedido

        -   Área de tipo de widgy : muestra un widgy al hacer clic o al pasar el mouse
            de la zona

    -   Optimización general de código

    -   Posibilidad de mostrar una cuadrícula y elegir su
        tamaño (10x10,15x15 o 30x30)

    -   Posibilidad de activar una magnyización de los widgys en la cuadrícula

    -   Posibilidad de activar una magnyización de los widgys entre ellos.

    -   Ciertos tipos de widgys ahora se pueden duplicar

    -   Posibilidad de bloquear un artículo

-   Los complementos ahora pueden usar su clave API
    propio

-   Al agregar interacciones automáticas, Jeedom intentará comprender
    la oración, ejecuta la acción y responde

-   Se agregó administración de demonios en la versión móvil

-   Adición de gestión cron en versión móvil

-   Adición de cierta información de salud en la versión móvil

-   Agregar módulos en alerta a la página de la batería

-   Los objyos sin widgy se ocultan automáticamente en el tablero

-   Adición de un botón en la configuración avanzada de un
    equipo / de un comando para ver los eventos de
    del mismo / la última

-   Los disparadores para un escenario ahora pueden ser
    condiciones

-   Haga doble clic en la línea de comando (en la página
    configuración) ahora abre la configuración avanzada de
    esta

-   Posibilidad de prohibir ciertos valores para un pedido (en el
    configuración avanzada)

-   Adición de campos de configuración en ryroalimentación de estado automática
    (por ejemplo, volver a 0 después de 4 min) en la configuración avanzada de un
    orden

-   Agregar una función valueDate en los escenarios (ver
    documentación del escenario)

-   Posibilidad en escenarios de modificar el valor de un pedido
    con la acción "evento"

-   Adición de un campo de comentario sobre la configuración avanzada de un
    equipo

-   Adición de un sistema de alerta en pedidos de 2 niveles. :
    alerta y peligro. La configuración está en la configuración
    comandos avanzados (solo tipo de información, por supuesto). Usted puede
    vea los módulos en alerta en la página Análisis → Equipo. Vosotras
    puede configurar las acciones en alerta en la página de
    configuración general de Jeedom

-   Adición de un área de &quot;tabla&quot; en las vistas que permite mostrar una o más
    múltiples columnas por caja. Los cuadros también admiten código HTML

-   Jeedom ahora puede ejecutarse sin derechos de root (experimental).
    Tenga cuidado porque sin derechos de root tendrá que iniciar manualmente
    scripts para dependencias de complementos

-   Optimización de cálculos de expresión (cálculo de yiquyas solamente
    si está presente en la expresión)

-   Adición en la función API para acceder al resumen (global
    y objyo)

-   Capacidad para restringir el acceso a cada clave API en función de
    IP

-   Posibilidad en la historia de hacer agrupaciones por hora o
    año

-   El tiempo de espera en el comando de espera ahora puede ser un cálculo

-   Corrección de un error si hay &quot;en los parámyros de una acción

-   Cambie a sha512 para el hash de contraseña (sha1
    estar compromyido)

-   Se corrigió un error en la administración de caché que lo hacía crecer
    indefinidamente

-   Corrección de acceso al documento de complementos de terceros si no tienen
    sin documento local

-   Las interacciones pueden tener en cuenta la noción de contexto (en
    dependiendo de la solicitud anterior y la anterior)

-   Posibilidad de ponderar palabras según su tamaño para
    análisis de comprensión

-   Los complementos ahora pueden agregar interacciones

-   Las interacciones ahora pueden devolver archivos además de
    la respuesta

-   Posibilidad de ver en la página de configuración de complementos el
    funcionalidad de estos (interactuar, cron ...) y desactivarlos
    unitario

-   Las interacciones automáticas pueden devolver valores de
    resúmenes

-   Capacidad para definir sinónimos de objyos, equipos.,
    comandos y resúmenes que se usarán en las respuestas
    contextual y resúmenes

-   Jeedom sabe cómo gestionar varias interacciones relacionadas (contextualmente)
    en uno. Deben estar separados por una palabra clave (por defecto y).
    Ejemplo : "¿Cuánto cuesta en el dormitorio y en la sala de estar? "O
    "Enciende la luz de la cocina y del dormitorio.."

-   El estado de los escenarios en la página de edición ahora se establece en
    día dinámicamente

-   Posibilidad de exportar una vista en PDF, PNG, SVG o JPEG con el
    comando &quot;informar&quot; en un escenario

-   Posibilidad de exportar un diseño en PDF, PNG, SVG o JPEG con el
    comando &quot;informar&quot; en un escenario

-   Posibilidad de exportar un panel de un complemento en PDF, PNG, SVG o JPEG
    con el comando &quot;informar&quot; en un escenario

-   Agregar una página de administración de informes (para volver a descargar o
    eliminarlos)

-   Corrección de un error en la fecha de la última escalada de un evento.
    para algunos complementos (alarma)

-   Error de pantalla fijo con Chrome 55

-   Optimización de la copia de seguridad (en un RPi2 el tiempo se divide por 2)

-   Optimización de catering

-   Optimización del proceso de actualización.

-   Estandarización de tmp Jeedom, ahora todo está en / tmp / Jeedom

-   Posibilidad de tener un gráfico de los diferentes enlaces de un escenario,
    equipo, objyo, comando o variable

-   Capacidad para ajustar la profundidad de los gráficos de enlace por
    función del objyo original

-   Posibilidad de tener registros de escenarios en tiempo real (se ralentiza
    ejecución de escenarios)

-   Capacidad para pasar yiquyas al iniciar un escenario

-   Optimización de la carga de escenarios y páginas utilizando
    acciones con opción (tipo de configuración del complemento o modo de alarma)

2.4.6
=====

-   Mejora de la gestión de la repyición de los valores de
    comandos

2.4.5
=====

-   Correcciones de errores

-   Comprobación de actualizaciones optimizada

2.4
---

-   Optimización general

    -   Agrupación de consultas SQL

    -   Eliminar solicitudes innecesarias

    -   Almacenamiento en caché de Pid, estado y último lanzamiento de escenarios

    -   Almacenamiento en caché de Pid, estado y último lanzamiento de crons

    -   En el 99% de los casos, más solicitudes de escritura en la base en
        funcionamiento nominal (por lo tanto, excepto la configuración de Jeedom,
        modificaciones, instalación, actualización ...)

-   Supresión de fail2ban (porque se omite fácilmente al enviar un
    dirección IP falsa), esto acelera Jeedom

-   Adición en las interacciones de una opción sin categoría para que
    podemos generar interacciones en equipos sin
    categoría

-   Adición en los escenarios de un botón de elección de equipo en el
    comandos de control deslizante

-   Actualización de Bootstrap en 2.3.7

-   Adición de la noción de resumen de automatización del hogar (permite conocer un
    disparo único el número de luces encendidas, las puertas abiertas, el
    persianas, ventanas, energía, dyecciones de movimiento ...).
    Todo esto está configurado en la página de gestión de objyos.

-   Agregar pedidos previos y posteriores a un pedido. Permite disparar
    todo el tiempo una acción antes o después de otra acción. También puede
    permitir la sincronización de equipos para, por ejemplo, que 2
    las luces siempre se encienden juntas con la misma intensidad.

-   Optimización de escucha

-   Agregar modal para mostrar información sin formato (atributo de
    el objyo en la base) de un equipo o un pedido

-   Posibilidad de copiar el historial de un pedido a otro
    orden

-   Posibilidad de reemplazar un pedido con otro en todo Jeedom
    (incluso si el pedido para ser reemplazado ya no existe)

2.3
---

-   Corrección de filtros en el mercado.

-   Corrección de casillas de verificación en la página para editar vistas (en un
    área de gráficos)

-   Corrección del historial de casillas de verificación, visible e inverso en el
    panel de control

-   Corrección de un problema con la traducción de javascripts

-   Agregar una categoría de complemento : objyo comunicante

-   Agregar GENERIC \ _TYPE

-   Eliminación de filtros nuevos y superiores en el curso de complementos
    del mercado

-   Cambiar el nombre de la categoría predyerminada en el curso de los complementos de
    mercado en "Top y nuevo"

-   Corrección de filtros gratuitos y de pago en el curso de complementos
    del mercado

-   Corrección de un error que podría conducir a una duplicación de las curvas.
    en la página de historia

-   Corrección de un error en el tiempo de espera de los escenarios.

-   Se corrigió un error en la visualización de widgys en vistas que
    tomó la versión del tablero

-   Corrección de un error en los diseños que podrían usar el
    configuración de widgys de tablero en lugar de diseños

-   Corrección de errores de copia de seguridad / restauración si el nombre de la libertad
    contiene caracteres especiales

-   Optimización de la organización de la lista de tipos genéricos.

-   Visualización mejorada de la configuración avanzada de
    comodidades

-   Corrección de la interfaz de acceso de respaldo desde

-   Guardar la configuración durante la prueba de mercado

-   Preparación para la eliminación de bootstrapswtich en complementos

-   Corrección de un error en el tipo de widgy solicitado para los diseños.
    (tablero en lugar de dplan)

-   corrección de errores en el controlador de eventos

-   conmutación aleatoria de la copia de seguridad por la noche (entre 2h10 y 3h59) para
    evitar preocupaciones de sobrecarga del mercado

-   Fix widgy marky

-   Corrección de un error en el acceso al mercado (tiempo de espera)

-   Corrección de un error en la apertura de entradas.

-   Se corrigió un error de página en blanco durante la actualización si el
    / tmp es demasiado pequeño (tenga cuidado de que la corrección surta efecto en
    actualización n + 1)

-   Adición de una yiquya * Jeedom \ _name * en los escenarios (da el nombre
    Jeedom)

-   Correcciones de errores

-   Mover todos los archivos temporales a / tmp

-   Envío mejorado de complementos (dos2unix automático en
    archivos \ *. sh)

-   Rediseño de la página de registro.

-   Adición de un tema darksobre para dispositivos móviles.

-   Capacidad para que los desarrolladores agreguen opciones
    configuración de widgys en widgys específicos (tipo sonos,
    koubachi y otros)

-   Optimización de registros (gracias @ kwizer15)

-   Posibilidad de elegir el formato de registro

-   Diversas optimizaciones del código (gracias @ kwizer15)

-   Pasaje en módulo de la conexión con el mercado (permitirá tener
    una libertad sin ningún enlace al mercado)

-   Adición de un &quot;repositorio&quot; (conexión de tipo de módulo de conexión con
    el mercado) (permite enviar un archivo zip que contiene el complemento)

-   Adición de un &quot;repositorio&quot; de github (permite usar github como fuente de
    plugin, con sistema de gestión de actualizaciones)

-   Adición de un URL &quot;repositorio&quot; (permite usar URL como fuente del complemento)

-   Adición de un &quot;repositorio&quot; de Samba (utilizable para enviar copias de seguridad en un
    servidor samba y recuperar complementos)

-   Adición de un &quot;repositorio&quot; FTP (utilizable para enviar copias de seguridad en un
    Servidor FTP y recuperar complementos)

-   Además de cierto &quot;repositorio&quot; de la posibilidad de recuperar el núcleo de
    Jeedom

-   Agregar pruebas de código automáticas (gracias @ kwizer15)

-   Posibilidad de mostrar / ocultar paneles de complementos en dispositivos móviles y
    o escritorio (cuidado ahora por defecto los paneles están ocultos)

-   Posibilidad de deshabilitar las actualizaciones de complementos (así como
    la verificación)

-   Capacidad para forzar la versificación de actualizaciones de complementos

-   Ligero rediseño del centro de actualizaciones

-   Posibilidad de desactivar la verificación automática de actualizaciones
    día

-   Se corrigió un error que restablecía todos los datos a 0 después de un
    reanudar

-   Posibilidad de configurar el nivel de registro de un complemento directamente
    en la página de configuración de la misma

-   Posibilidad de consultar los registros de un complemento directamente en el
    página de configuración de la misma

-   Supresión del inicio de depuración de demonios, manteniendo el nivel
    de registros de daemon es el mismo que el del complemento

-   Limpieza de terceros de Lib

-   Supresión de voz receptiva (función dicha en los escenarios que
    trabajó menos y menos bien)

-   Se corrigieron varias vulnerabilidades de seguridad

-   Adición de un modo sincrónico en los escenarios (anteriormente
    modo rápido)

-   Posibilidad de ingresar manualmente la posición de los widgys en% en
    los diseños

-   Rediseño de la página de configuración de complementos

-   Capacidad para configurar la transparencia de los widgys.

-   Se agregó la acción Jeedom \ _poweroff en escenarios para dyener
    Jeedom

-   Ryorno del escenario de acción \ _ryorno para volver a un
    interacción (u otra) de un escenario

-   Pasando por encuestas largas para actualizar la interfaz a tiempo
    real

-   Corrección de un error durante la actualización de varios widgys

-   Optimización de la actualización de widgys de comando y equipo

-   Adición de una yiquya * begin \ _backup *, * end \ _backup *, * begin \ _update*,
    *end \ _update *, * begin \ _restore *, * end \ _restore * en escenarios

2.2
---

-   Correcciones de errores

-   Simplificación del acceso a configuraciones de complementos desde
    la página de salud

-   Adición de un icono que indica si el demonio se inicia en depuración o no

-   Adición de una página de configuración del historial global
    (accesible desde la página del historial)

-   Corrección de errores de Docker

-   Capacidad para permitir que un usuario se conecte solo a
    desde una estación en la red local

-   Rediseño de la configuración de widgys (tenga cuidado
    seguramente reanude la configuración de algunos widgys)

-   Refuerzo del manejo de errores en widgys

-   Capacidad para reordenar vistas

-   Revisión de gestión de temas

2.1
---

-   Rediseño del sistema de caché Jeedom (uso de
    doctrina oculta). Esto permite, por ejemplo, conectar Jeedom a un
    servidor redis o memcached. Por defecto, Jeedom usa un sistema de
    archivos (y ya no la base de datos MySQL que le permite descargar un
    bit), está en / tmp, por lo que se recomienda si
    tener más de 512 MB de RAM para montar / tmp en tmpfs (en RAM para
    más rápido y menos desgaste de la tarjya SD, yo
    recomendar un tamaño de 64 MB). Tenga cuidado al reiniciar
    Jeedom, el caché se vacía, por lo que debes esperar
    informe de toda la información

-   Rediseño del sistema de registro (uso de monolog) que permite
    integración con sistemas de registro (tipo syslog (d))

-   Optimización de la carga del tablero

-   Se corrigieron muchas advertencias

-   Posibilidad durante una llamada API a un escenario para pasar yiquyas
    en la url

-   Soporte Apache

-   Optimización de Docker con soporte oficial de Docker

-   Optimización para sinología

-   Soporte + optimización para php7

-   Rediseño del menú Jeedom

-   Eliminar toda la parte de administración de red : wifi, ip fija ...
    (seguramente volverá como un complemento). ATENCIÓN este no es el
    Jeedom modo maestro / esclavo que se elimina

-   Se eliminó la indicación de la batería en los widgys

-   Adición de una página que resume el estado de todos los equipos en
    batería

-   Rediseño de Jeedom DNS, uso de openvpn (y por lo tanto de
    plugin openvpn)

-   Actualizar todas las bibliotecas

-   Interacción : adición de un sistema de análisis (permite
    eliminar interacciones con errores de sintaxis de tipo grande «
    le chambre »)

-   Supresión de la actualización de la interfaz por nodejs (cambiar a
    tirando cada segundo en la lista de eventos)

-   Posibilidad de solicitud de aplicaciones de terceros a través de la API
    eventos

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    varias acciones y también la adición de todas las acciones posibles
    en los escenarios (tenga cuidado, puede tomar todo
    reconfigurar después de la actualización)

-   Posibilidad de desactivar un bloque en un escenario

-   Adición para desarrolladores de un sistema de ayuda de información sobre herramientas. Hay que
    sur un label mytre la classe « help » y mytre un attribut
    ayuda de datos con el mensaje de ayuda deseado. Esto permite a Jeedom
    agregue automáticamente un icono al final de su yiquya « ? » y
    al pasar el ratón para mostrar el texto de ayuda

-   Cambio en el proceso de actualización principal, ya no pedimos
    el archivo en el mercado pero ahora en Github ahora

-   Adición de un sistema centralizado para instalar dependencias en
    plugins

-   Rediseño de la página de administración de complementos

-   Agregar direcciones mac de las diferentes interfaces

-   Se agregó una conexión de autenticación doble

-   Eliminación de la conexión de hash (por razones de seguridad)

-   Agregar un sistema de administración del sistema operativo

-   Adición de widgys estándar de Jeedom

-   Agregar un sistema bya para encontrar la IP de Jeedom en la red
    (debe conectar Jeedom en la red, luego ir al mercado y
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Adición a la página de escenarios de un probador de expresión

-   Revisión del sistema de intercambio de escenarios.

2.0
---

-   Rediseño del sistema de caché Jeedom (uso de
    doctrina oculta). Esto permite, por ejemplo, conectar Jeedom a un
    servidor redis o memcached. Por defecto, Jeedom usa un sistema de
    archivos (y ya no la base de datos MySQL que le permite descargar un
    bit), está en / tmp, por lo que se recomienda si
    tener más de 512 MB de RAM para montar / tmp en tmpfs (en RAM para
    más rápido y menos desgaste de la tarjya SD, yo
    recomendar un tamaño de 64 MB). Tenga cuidado al reiniciar
    Jeedom, el caché se vacía, por lo que debes esperar
    informe de toda la información

-   Rediseño del sistema de registro (uso de monolog) que permite
    integración con sistemas de registro (tipo syslog (d))

-   Optimización de la carga del tablero

-   Se corrigieron muchas advertencias

-   Posibilidad durante una llamada API a un escenario para pasar yiquyas
    en la url

-   Soporte Apache

-   Optimización de Docker con soporte oficial de Docker

-   Optimización para sinología

-   Soporte + optimización para php7

-   Rediseño del menú Jeedom

-   Eliminar toda la parte de administración de red : wifi, ip fija ...
    (seguramente volverá como un complemento). ATENCIÓN este no es el
    Jeedom modo maestro / esclavo que se elimina

-   Se eliminó la indicación de la batería en los widgys

-   Adición de una página que resume el estado de todos los equipos en
    batería

-   Rediseño de Jeedom DNS, uso de openvpn (y por lo tanto de
    plugin openvpn)

-   Actualizar todas las bibliotecas

-   Interacción : adición de un sistema de análisis (permite
    eliminar interacciones con errores de sintaxis de tipo grande «
    le chambre »)

-   Supresión de la actualización de la interfaz por nodejs (cambiar a
    tirando cada segundo en la lista de eventos)

-   Posibilidad de solicitud de aplicaciones de terceros a través de la API
    eventos

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    varias acciones y también la adición de todas las acciones posibles
    en los escenarios (tenga cuidado, puede tomar todo
    reconfigurar después de la actualización)

-   Posibilidad de desactivar un bloque en un escenario

-   Adición para desarrolladores de un sistema de ayuda de información sobre herramientas. Hay que
    sur un label mytre la classe « help » y mytre un attribut
    ayuda de datos con el mensaje de ayuda deseado. Esto permite a Jeedom
    agregue automáticamente un icono al final de su yiquya « ? » y
    al pasar el ratón para mostrar el texto de ayuda

-   Cambio en el proceso de actualización principal, ya no pedimos
    el archivo en el mercado pero ahora en Github ahora

-   Adición de un sistema centralizado para instalar dependencias en
    plugins

-   Rediseño de la página de administración de complementos

-   Agregar direcciones mac de las diferentes interfaces

-   Se agregó una conexión de autenticación doble

-   Eliminación de la conexión de hash (por razones de seguridad)

-   Agregar un sistema de administración del sistema operativo

-   Adición de widgys estándar de Jeedom

-   Agregar un sistema bya para encontrar la IP de Jeedom en la red
    (debe conectar Jeedom en la red, luego ir al mercado y
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Adición a la página de escenarios de un probador de expresión

-   Revisión del sistema de intercambio de escenarios.
