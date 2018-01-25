cambios
=========

3.2.0
=====

-   Posibilidad de prohibir a un comando en las interacciones
    automático

-   interacciones automáticas mejoradas

-   Corregido un fallo en la gestión de las interacciones sinónimas

-   Adición de un campo de búsqueda para LDAP conexión de usuario / AD
    (Ayuda a que Jeedom AD compatible)

-   correcciones de ortografía (gracias dab0u por su enorme trabajo)

-   JEED-290: No se puede conectar con los identificadores de
    predeterminado (admin / admin) de distancia, sólo se permite la red local

-   JEED-186: Ahora puede elegir el color de fondo en
    diseños

-   En el bloque A, posibilidad de una hora entre 0:01 ET doce y cincuenta y nueve
    simplemente poniendo los minutos (por ejemplo, doce y treinta 30)

-   La adición de las sesiones activas y periféricos registrado en
    Página de perfil de usuario y la página de administración
    usuarios

-   JEED-284: la conexión permanente depende ahora de una clave
    único usuario y circunvalación (en lugar de usuario)

-   JEED-283: Adición de un modo de rescate mediante la adición de jeedom y rescate = 1
    en la url

-   JEED-8: Añadir el nombre del escenario en el título de la página cuando
    edición

-   Optimización de los cambios en la organización (widgets de tamaño,
    equipos de posición, los comandos de posición) en el salpicadero
    y ver. Cuidado ahora los cambios no son
    salvo cuando se sale del modo de edición.

-   JEED-18: Adición de registros al abrir un ticket de soporte

-   JEED-181: la adición de un comando de nombre en los escenarios de
    el nombre del comando o equipo u objeto

-   JEED-15: Se ha añadido la batería y alerta sobre la aplicación de web

-   errores corregidos en movimiento de objetos de diseño en Firefox

-   JEED-19: Cuando una actualización, ahora es posible
    actualizar el script de actualización antes de actualizar

-   JEED-125: Se ha añadido un enlace para restablecer la documentación
    contraseña

-   JEED-2: Mejora de la gestión del tiempo en el reinicio

-   JEED-77: Adición de gestión de etiquetas en API HTTP

-   JEED-78: se ha añadido la etiqueta de acuerdo con los escenarios. advirtiendo que
    estar en escenarios usando las etiquetas pasan Montag * *
    Para etiquetar (Montag)

-   JEED-124: escenarios de gestión correcta tiempos de espera

-   errores corregidos

-   Capacidad para desactivar una interacción

-   La adición de un editor de archivos (sólo para
    avanzado)

-   La adición de los tipos genéricos "estado de luz" (binario), "Luz
    Temperatura de color "(Información)," temperatura de color ligero "(Acción)

-   Posibilidad de hacer palabras de enlace en una interacción

3.1.7
=====

-   Corrección de errores (especialmente en histórico y
    funciones estadísticas)

-   sistema de actualización mejorado con una página de notas
    Versión (es necesario comprobar usted mismo antes de cada actualización
    día !!!!)

-   Se ha corregido un error que fue a buscar los registros durante la restauración

3.1
===

-   errores corregidos

-   optimización global de Jeedom (en las clases de carga
    plugins, casi la hora dividido por 3)

-   Soporte Debian 9

-   Modo OnePage (cambio de página sin volver a cargar la página entera, simplemente
    la parte que cambia)

-   Añadida la opción de ocultar objetos en el salpicadero, pero
    permite tener siempre en la lista

-   Haciendo doble clic sobre un nodo en el gráfico de enlace (a excepción de
    variables) lleva en su página de configuración

-   Posibilidad de texto a la izquierda / derecha / centro de
    diseños para elementos como texto / foto / diseño

-   Añadiendo resúmenes objetos en el salpicadero (lista de objetos
    a la izquierda)

-   La adición de las interacciones de tipo "Avisar si el"

-   escenarios página de inicio opinión

-   La adición de un historial de comandos para los comandos SQL o sistema de
    en la interfaz de Jeedom

-   Posibilidad de los gráficos históricos de pedidos
    webapp (manteniendo pulsado el control)

-   Añadiendo el progreso de la actualización de la aplicación web

-   Recuperación de error de actualización de la aplicación web

-   La eliminación de escenarios "simples" (con configuración redundante
    órdenes avanzadas)

-   La adición de escotilla en gráficos para distinguir día

-   Revisión de las interacciones página

-   Refundición de la página de perfiles

-   rediseñada página de administración

-   La adición de un "salud" en los objetos

-   corrección de error en el nivel de batería del equipo

-   Adición de método en el núcleo para la gestión de comandos muertas
    (Debe entonces ser implementado en el plug-in)

-   Capacidad de historizar comandos basados ​​en texto

-   En la página de la historia ahora se puede trazar
    un cálculo

-   Adición de una gestión fórmula para histórico

-   actualización de la documentación:

    -   Todos los documentos se han revisado

    -   Eliminación de imágenes para una fácil actualización y
        plurilingüe

-   Más opciones de la configuración del tamaño de la zona de
    vistas

-   Posibilidad de elegir el color del objeto de texto Resumen

-   Adición de eliminar \ escenarios de acción para _inat
    cancelar todos los bloques de programación de IN / A

-   Posibilidad de diseños para los widgets para elegir visión general
    la posición del widget

-   La adición de un parámetro de respuesta \ _cmd en las interacciones para especificar
    el identificador del comando utilizar para satisfacer

-   Adición de una línea de tiempo en la página Historial (atención debe estar
    activado en todos los órdenes y / o escenario que desea
    ver aparecer)

-   Posibilidad de vaciar los acontecimientos de la línea de tiempo

-   Posibilidad de IPs prohibidas vacías

-   Corrección / mejora en la gestión de cuentas de usuario

    -   Posibilidad de eliminar la cuenta de administrador básica

    -   paso normal de la última administrador de Prevención

    -   Adición de seguridad para evitar la eliminación de la cuenta con
        en el que está conectada

-   Posibilidad en la configuración de equipos avanzados para
    la disposición de los mandos de los widgets de la tabla de modo de
    choissisant para cada caja de control o para

-   Posibilidad de reorganizar los widgets de los equipos
    Tablero de instrumentos (en la manera de la edición, haga clic derecho en el widget)

-   Cambiar el tono de widgets (40 \ * 80-10 \ * 10). Tenga en cuenta que
    tendrá un impacto en su diseño de tablero de mandos / foto / diseño

-   Posibilidad de dar un tamaño de 1 a 12 objetos en
    salpicadero

-   Capacidad para iniciar independientemente escenarios de acción (y
    plug-in tipo de modo / alarma si está habilitado) al lado del otro

-   Posibilidad de añadir un código de acceso a un diseño

-   La adición de un organismo de control independiente de Jeedom para comprobar el estado de
    MySQL y Apache

3.0.11
======

-   Solución de errores en aplicaciones de "preguntar" en tiempo de espera

3.0.10
======

-   Solución de errores en las interacciones de la interfaz de configuración

3.0
===

-   La eliminación del modo esclavo

-   Capacidad de desencadenar un guión sobre un cambio de
    variable

-   Actualizaciones de variables ahora desencadenan la actualización
    Los comandos de un equipo virtual (necesita la última versión
    plug-in)

-   Capacidad de tener un icono en los controles de información de tipo

-   Posibilidad de los controles para mostrar el nombre y el icono

-   Añadir acción "alerta" sobre escenarios: mensaje de la parte superior de
    jeedom

-   La adición de unos escenarios de acción "pop-up": mensaje para validar

-   Reproductores de comandos puede tener ahora un método
    para actualizar que impide que una llamada AJAX a Jeedom

-   Widgets escenarios se actualizan sin llamada AJAX
    para el widget

-   El resumen general y las habitaciones se actualizan definitiva
    ajax

-   Un clic en un elemento de un resumen de automatización del hogar que aporta una visión
    detalle de la misma

-   Ahora puede poner en los comandos de tipo resumen
    texto

-   Cambiante en el deslizador deslizador ayuda de nadie (corrección de errores
    deslizadores de eventos dobles)

-   vistas automáticas de copia de seguridad cuando se hace clic en el "ver
    resultado "

-   Capacidad de tener los documentos localmente

-   Los desarrolladores de terceros pueden añadir su propio sistema
    boletos de gestión

-   Derechos de configuración de usuario rediseñada (todo es en
    la página de administración de usuarios)

-   libs Actualizado: jQuery (en 3.0), jQuery Mobile, hightstock
    clasificador y la mesa son impresionantes,

-   diseños grandes de mejora:

    -   Todas las acciones son ahora accesibles desde una
        botón derecho del ratón

    -   Posibilidad de añadir un solo comando

    -   Posibilidad de añadir un flujo de imágenes o vídeo

    -   Posibilidad de añadir áreas de ubicación (hacer clic):

        -   tipo de área de macro: el lanzamiento de una serie de acciones en una
            haga clic en él

        -   área binaria: el lanzamiento de una serie de acciones en una
            haga clic en él de acuerdo con el estado de un pedido

        -   widget de tipo de zona muestra un widget para hacer clic o vuelco
            zona

    -   optimización de código general

    -   Posibilidad de mostrar una cuadrícula y la elección de
        tamaño (10x10,15x15 o 30x30)

    -   Capacidad de activar una magnetización de widgets en la parrilla

    -   Capacidad de activar una magnetización ellos Widgets

    -   Algunos tipos de widgets ahora pueden ser duplicadas

    -   Capacidad de bloquear un elemento

-   Plugins ahora pueden utilizar una clave de API que es su
    propio

-   La adición de las interacciones automatizadas, Jeedom tratará de entender
    la sentencia para realizar la acción y responder

-   La adición de la gestión de los demonios Versión móvil

-   Adición de gestión de la versión móvil de cron

-   Añadir determinada información de salud en una versión móvil

-   Agregando a los módulos de la batería página de alerta

-   Objetos sin widgets se ocultan automáticamente en el salpicadero

-   Añadido botón en la configuración avanzada de una
    equipos / un comando para ver los eventos
    del mismo / la última

-   Desencadena una secuencia de comandos puede ser ahora
    condiciones

-   Haciendo doble clic en una línea de comandos (en la página
    configuración) ahora se abre la configuración avanzada
    esta

-   Posibilidad de prohibir ciertos valores para un control (en el
    configuración avanzada de los mismos)

-   Adición de campos de configuración en el estado automático de vuelta
    (Ej retorno a 0 después de 4 minutos) en la configuración avanzada de
    orden

-   Adición de una función ValueDate en escenarios (ver
    documentación de escenario)

-   Posibilidad de escenarios para cambiar el valor de un control
    con el "evento" acción

-   Añadir un campo de comentarios de la configuración avanzada de
    equipo

-   Añadido a los controles del sistema de advertencia con 2 niveles:
    advertencia y peligro. La configuración está en la configuración
    comandos avanzados (Info único tipo de curso). Puede
    ver módulos de advertencia en la página → Equipo de análisis. Usted
    puede configurar las acciones de alerta en la página
    Configuración general Jeedom

-   Adición de una zona de "tabla" en vistas que pueden mostrar una o
    más columnas por caja. Las cajas también apoyan el código HTML

-   Jeedom ahora puede funcionar sin privilegios de root (experimental).
    Atención porque sin privilegios de root debe iniciar manualmente
    guiones para las dependencias de plugins

-   Optimizar el cálculo de expresiones (sólo etiquetas de cálculo
    si está presente en la expresión)

-   Agregando a la función de la API para acceder al resumen (Global
    y el objeto)

-   Capacidad de restringir el acceso de cada clave de API basada
    IP

-   la historia sobre Posibilidad de combinaciones por hora o
    año

-   El tiempo de espera en el comando de espera puede ser ahora un cálculo

-   Se ha corregido un error en el que "en los parámetros de una acción

-   El cambio a de hash SHA512 para la contraseña (la sha1
    se vea comprometida)

-   Se ha corregido un error en la gestión de la caché que le hizo grasa
    indefinidamente

-   acceso fijo a la documentación de los plugins de terceros si no tienen
    sin doc localmente

-   Las interacciones pueden tener en cuenta la noción de contexto (en
    de acuerdo con la solicitud anterior y la anterior)

-   Capacidad para ponderar las palabras de acuerdo con su tamaño
    analizar la comprensión

-   Plugins ahora pueden añadir interacciones

-   Las interacciones pueden ahora enviar archivos y más
    la respuesta

-   Capacidad de ver la página de configuración del plugin del
    funcionalidad de estos (Interact, cron ...) y desactivar
    unitario

-   interacciones automáticas pueden devolver valores de
    resúmenes

-   Capacidad para definir synomymes para los objetos, equipos,
    órdenes y los resúmenes que serán utilizados en las respuestas
    contextual y resúmenes

-   Jeedom puede gestionar múltiples interacciones relacionadas (contextualmente)
    uno. Ellos deben estar separados por una palabra clave (por defecto).
    Ejemplo: "¿Cómo está haciendo en el dormitorio y en el salón?" o
    "Enciende la luz de la cocina y el dormitorio."

-   El estado de los escenarios en la página de edición está siendo
    actualizar dinámicamente

-   Posibilidad de exportar una vista en PDF, PNG, SVG, JPEG o con
    Comando "informe" en un escenario

-   Capacidad de exportar un diseño en formato PDF, PNG, SVG, JPEG o con
    Comando "informe" en un escenario

-   Capacidad de exportar una amplia gama de plug-in PDF, PNG, SVG o JPEG
    con el "informe" de comandos en un script

-   Se ha añadido una página de gestión de informes (o volver a descargar
    eliminar)

-   Se ha corregido un error en la fecha de la última subida de un evento
    para algunos plugins (alarma)

-   Se ha corregido un error de visualización con Chrome 55

-   La optimización de copia de seguridad (en un tiempo RPi2 se divide por 2)

-   La optimización de la restauración

-   Optimización del proceso de actualización

-   La estandarización de jeedom tmp, ahora todo está en / tmp / jeedom

-   Posibilidad de un gráfico de los diversos enlaces de un escenario,
    equipo, objeto, o variable de control

-   Posibilidad de ajustar la profundidad de los gráficos de enlace
    de acuerdo con el objeto original

-   Capacidad de tener los registros en los escenarios en tiempo real (ralentiza
    la ejecución de escenarios)

-   Capacidad para cambiar las etiquetas en el lanzamiento de un escenario

-   escenarios y las páginas que utilizan la optimización de carga
    opción de acciones (tipo plug-in de alarma de configuración o modo)

2.4.6
=====

-   Mejora de la gestión de los valores de repetición
    comandos

2.4.5
=====

-   errores corregidos

-   Optimización de la verificación de cambios

2.4
---

-   optimización general

    -   agrupación de consultas SQL

    -   Eliminación de consultas innecesarias

    -   Pasaje pid almacenamiento en caché, el estado y escenarios últimos lanzamiento

    -   Pasaje pid almacenamiento en caché, el estado y el último lanzamiento de cron

    -   En el 99% de los casos de solicitud a través de escritura basado en
        operación nominal (Jeedom configuración de este modo hacia fuera,
        modificaciones, instalación, actualización ...)

-   Extracción de la fail2ban (tan fácilmente eludidas mediante el envío de una
    falsa dirección IP), se acelera Jeedom

-   Adición a la interacción de una opción no categoría para
    uno puede generar interacciones en equipo sin
    categoría

-   Agregando a los escenarios de un botón de selección del equipo de
    controles de tipo deslizante

-   2.3.7 Actualización de arranque

-   Añadiendo el concepto de resumen de automatización del hogar (permite conocer una
    De repente, el número de luces encendidas, la puerta abierta, el
    persianas, ventanas, potencia, detección de movimiento ...).
    Todo esto establece la página de gestión de objetos

-   La adición de pre y puesto de mando de un comando. disparadores
    todo el tiempo una acción antes o después de otra acción. también puede
    permitir que un equipo de sincronización, por ejemplo, 2
    luces siempre encienden junto con la misma intensidad.

-   listenner Optimización

-   modal Adición para mostrar la información en bruto (atributo
    el objeto base) de un equipo o de control

-   Posibilidad de copiar la historia de un pedido en otro
    orden

-   Posibilidad de sustitución de una orden por otro a lo largo de Jeedom
    (Incluso si la orden reemplazar ya no existe)

2.3
---

-   filtros de corrección en el mercado

-   casilla de verificación de la corrección en las vistas de edición de página (en una
    área de gráficos)

-   historizar casilla de verificación de corrección, visible y en el reverso
    tabla de comandos

-   Corregidos los problemas en la traducción de Javascript

-   Adición de una clase plugin: objeto comunicante

-   Añadiendo GENÉRICO \ _TYPE

-   Extracción de nuevos filtros y la parte superior de los plugins del curso
    el mercado

-   Cambiar el nombre de la categoría por defecto en el camino de los plugins
    mercado en el "Top y la novedad"

-   Corrección de filtros gratuitos y de pago de los complementos del curso
    el mercado

-   Se ha corregido un error que podría dar lugar a la duplicación de las curvas
    en la página Historial

-   Se ha corregido un error en los escenarios de valor de tiempo de espera

-   la corrección de un error en la pantalla de widgets en las vistas
    tomó la versión salpicadero

-   Se ha corregido un error en diseños que podrían utilizar
    salpicadero configuración de widgets en lugar diseña

-   Solución de errores en la copia de seguridad / restauración si el nombre de jeedom
    contiene caracteres especiales

-   Optimización de la organización de la lista de tipos genéricos

-   Visualización mejorada de configuración avanzada
    comodidades

-   interfaz de acceso fijo para la copia de seguridad

-   Almacenamiento de la configuración en la prueba de mercado

-   Preparación para la eliminación de bootstrapswtich en plugins

-   Se ha corregido un error en el tipo de widget llamado para diseños
    (En lugar de tablero Dplan)

-   corrección de errores en el controlador de eventos

-   paso azar de la copia de seguridad por la noche (entre 02:10 ET 03:59) a
    evitar la sobrecarga de las preocupaciones del mercado

-   widget de mercado fijo

-   Se ha corregido un error en el acceso al mercado (tiempo de espera)

-   La corrección de un error en la apertura de las entradas

-   Se ha corregido un error de página en blanco cuando se actualiza si el
    / Tmp es demasiado pequeño (tenga en cuenta la corrección en vigor
    actualizar el n + 1)

-   Adición de una etiqueta jeedom * \ * _name en los escenarios (dado el nombre
    la jeedom)

-   errores corregidos

-   Mover todos los archivos temporales en / tmp

-   plugins que envían mejoradas (dos2unix automático
    archivos \ *. sh)

-   Refundición de la página de registro

-   La adición de un tema para darksobre móvil

-   Capacidad para que los desarrolladores añadir opciones
    configuración del widget en los widgets específicos (tipo Sonos
    Koubachi y otros)

-   Optimización de los registros (gracias @ kwizer15)

-   Posibilidad de elegir el formato de registro

-   Otros código de optimización (gracias @ kwizer15)

-   Paso de conexión del módulo con el mercado (será
    un jeedom no relacionada con el mercado)

-   Adición de un "repo" (conexión de enchufe con
    la) Archivo de mercado (permite enviar un archivo zip que contiene el plug-in)

-   Se ha añadido una github "repo" (permite su uso como fuente de GitHub
    plug-in, con el sistema de administración de actualizaciones)

-   Se ha añadido una URL "repo" (URL permite su uso como fuente de complemento)

-   Se ha añadido un "repo" Samba (utilizado para empujar las copias de seguridad en una
    Samba servidor y recuperar los plugins)

-   Se ha añadido un FTP "repo" (utilizado para empujar las copias de seguridad en una
    servidor FTP y recuperar los plugins)

-   Agregando a cierta "repo" la capacidad de recuperar el núcleo de
    jeedom

-   Incorporar la prueba automática de código (gracias @ kwizer15)

-   Posibilidad de mostrar paneles / Hide el móvil y plugins
    o de escritorio (paneles ahora por defecto cuidadosas están ocultos)

-   Capacidad para desactivar las actualizaciones de un plugin (y
    la verificación)

-   Posibilidad obligó a la comprobación de cambios de un plugin

-   Centro de Actualización rediseño leve

-   Capacidad de deshabilitar la comprobación automática de actualizaciones
    día

-   Se ha corregido un error que poner todos los datos a 0 después de una
    reanudar

-   Capacidad para configurar el nivel de registro de un plug-in directamente
    en la página de configuración de ella

-   Capacidad para ver los registros de un plugin directamente en el
    página de configuración de la misma

-   Extracción de inicio en los demonios de depuración, se mantiene el nivel
    logs del diablo es el mismo que el plugin

-   Limpieza tercer lib

-   Eliminar la voz de respuesta (dicha función en los escenarios
    trabajado tan bien)

-   Fija varios falla de seguridad

-   Adición de un síncronos escenarios modo (anteriormente
    de modo rápido)

-   Capacidad para introducir manualmente la posición de los widgets en%
    diseño

-   Refundición de la página de configuración del plugin

-   Capacidad para configurar la transparencia de los widgets

-   Adición de acción jeedom \ _poweroff en escenarios para detener
    jeedom

-   Volver al escenario de la acción \ _return para hacer un retorno a
    interacción (u otro) de un guión

-   El cambio a largo sondeo para actualizar la interfaz de tiempo
    real

-   La corrección de un error de actualización cuando widget de múltiples

-   La optimización de los equipos reproductores de actualización y los controles

-   La adición de una etiqueta * comience \ _backup * * Final \ _backup * * comenzará \ _update *
    * Fin \ _update * * comenzará \ _restore * * Final \ _restore * en los escenarios

2.2
---

-   errores corregidos

-   Simplificar el acceso a las configuraciones de plugins
    Página de la salud

-   Adición de un icono que indica si el daemon se inicia en la depuración o no

-   Añadir una página de configuración global histórica
    (Disponible en la página de la historia)

-   correcciones de errores para ventana acoplable

-   Capacidad de permitir a un usuario conectarse únicamente a
    desde una posición en la red local

-   widgets de configuración rediseñada (ver que se
    seguramente reanudar la configuración de algunos widgets)

-   El fortalecimiento del manejo de los widgets de error

-   La posibilidad de organizar vistas

-   Refundición de la gestión de temas

2.1
---

-   sistema de caché Jeedom rediseñado (usando
    Doctrina caché). Esto permite, por ejemplo, para conectar un Jeedom
    repetir o servidor memcached. Jeedom por defecto utiliza un sistema de
    archivos (en lugar de la base de datos MySQL descargando de este modo una
    bit), que se encuentra en / tmp por lo que es recomendable si usted
    tiene más de 512 MB de RAM para montar el directorio / tmp tmpfs (RAM
    más velocidad y menos desgaste en la tarjeta SD, lo
    64mb tamaño recomendado). Atención al reiniciar
    Jeedom se limpia la caché por lo que tiene que esperar a la
    Ascenso información completa

-   rediseño del sistema de registro (usando monolog) que permite
    la integración de los sistemas de log (de tipo syslog (d))

-   tablero optimización de la carga

-   muchos advertencia fijo

-   Posibilidad durante una llamada a la API para pasar unas etiquetas de escenarios
    en la url

-   Soporte Apache

-   cargador de muelle de optimización con el apoyo oficial de los trabajadores portuarios

-   Synology optimización

-   Soporte + optimización php7

-   Menú Rediseño Jeedom

-   Eliminar toda la parte de gestión de red: IP fijo inalámbrico ...
    (Seguramente regresar como un plug-in). PRECAUCIÓN este no es el
    modo maestro / esclavo de jeedom que se elimina

-   La eliminación de la indicación de batería de los widgets

-   Adición de una página que resume el estado de todos los equipos de
    batería

-   Rediseñado DNS Jeedom, utilizando OpenVPN (y por lo tanto
    openvpn plugin)

-   actualizar todas las bibliotecas

-   Interacción: la adición de un sistema de análisis sintáctico (permite
    eliminar las interacciones con grandes errores de sintaxis como "
    la recamara ")

-   Eliminación de la actualización de la interfaz por nodejs (pasaje
    tirando cada segundo en la lista de eventos)

-   Posibilidad para aplicaciones de terceros para solicitar en la API
    eventos

-   La revisión del sistema de "valor de acción" con la posibilidad
    varias acciones y también el complemento de todas las acciones posibles
    en los escenarios (tenga en cuenta que puede tomar todo
    reconfigurar después de la actualización)

-   Capacidad de desactivar un bloque en un escenario

-   Agregando a los desarrolladores de sistema de información sobre herramientas de ayuda. Hay que
    en una etiqueta a la clase de "ayuda" y poner un atributo
    ayuda-ayuda de datos con el mensaje deseado. Esto permite Jeedom
    añade automáticamente al final de la etiqueta de un icono "? "y
    paso elevado que aparezca el texto de ayuda

-   Cambio de la actualización de los procesos centrales, ya no exigen
    el archivo comprimido en el mercado, pero ahora directamente a Github

-   instalación Adición de dependencias centralizado en
    plugins

-   Refundición de la página de administración de plugins

-   Adición de la dirección MAC de las diferentes interfaces

-   Adición de conexión con doble autenticación

-   La eliminación de la picadillo de conexión (por razones de seguridad)

-   Adición de un sistema de distribución de SO

-   Adición de widgets de normas Jeedom

-   Adición de una beta en el sistema para encontrar Jeedom IP en la red
    (Debe ser conectado Jeedom en la red, y luego ir al mercado y
    haga clic en "Mis Jeedoms" en su perfil)

-   Agregando a otros escenarios de un probador de expresión

-   Revisión del sistema de intercambio de escenario

2.0
---

-   sistema de caché Jeedom rediseñado (usando
    Doctrina caché). Esto permite, por ejemplo, para conectar un Jeedom
    repetir o servidor memcached. Jeedom por defecto utiliza un sistema de
    archivos (en lugar de la base de datos MySQL descargando de este modo una
    bit), que se encuentra en / tmp por lo que es recomendable si usted
    tiene más de 512 MB de RAM para montar el directorio / tmp tmpfs (RAM
    más velocidad y menos desgaste en la tarjeta SD, lo
    64mb tamaño recomendado). Atención al reiniciar
    Jeedom se limpia la caché por lo que tiene que esperar a la
    Ascenso información completa

-   rediseño del sistema de registro (usando monolog) que permite
    la integración de los sistemas de log (de tipo syslog (d))

-   tablero optimización de la carga

-   muchos advertencia fijo

-   Posibilidad durante una llamada a la API para pasar unas etiquetas de escenarios
    en la url

-   Soporte Apache

-   cargador de muelle de optimización con el apoyo oficial de los trabajadores portuarios

-   Synology optimización

-   Soporte + optimización php7

-   Menú Rediseño Jeedom

-   Eliminar toda la parte de gestión de red: IP fijo inalámbrico ...
    (Seguramente regresar como un plug-in). PRECAUCIÓN este no es el
    modo maestro / esclavo de jeedom que se elimina

-   La eliminación de la indicación de batería de los widgets

-   Adición de una página que resume el estado de todos los equipos de
    batería

-   Rediseñado DNS Jeedom, utilizando OpenVPN (y por lo tanto
    openvpn plugin)

-   actualizar todas las bibliotecas

-   Interacción: la adición de un sistema de análisis sintáctico (permite
    eliminar las interacciones con grandes errores de sintaxis como "
    la recamara ")

-   Eliminación de la actualización de la interfaz por nodejs (pasaje
    tirando cada segundo en la lista de eventos)

-   Posibilidad para aplicaciones de terceros para solicitar en la API
    eventos

-   La revisión del sistema de "valor de acción" con la posibilidad
    varias acciones y también el complemento de todas las acciones posibles
    en los escenarios (tenga en cuenta que puede tomar todo
    reconfigurar después de la actualización)

-   Capacidad de desactivar un bloque en un escenario

-   Agregando a los desarrolladores de sistema de información sobre herramientas de ayuda. Hay que
    en una etiqueta a la clase de "ayuda" y poner un atributo
    ayuda-ayuda de datos con el mensaje deseado. Esto permite Jeedom
    añade automáticamente al final de la etiqueta de un icono "? "y
    paso elevado que aparezca el texto de ayuda

-   Cambio de la actualización de los procesos centrales, ya no exigen
    el archivo comprimido en el mercado, pero ahora directamente a Github

-   instalación Adición de dependencias centralizado en
    plugins

-   Refundición de la página de administración de plugins

-   Adición de la dirección MAC de las diferentes interfaces

-   Adición de conexión con doble autenticación

-   La eliminación de la picadillo de conexión (por razones de seguridad)

-   Adición de un sistema de distribución de SO

-   Adición de widgets de normas Jeedom

-   Adición de una beta en el sistema para encontrar Jeedom IP en la red
    (Debe ser conectado Jeedom en la red, y luego ir al mercado y
    haga clic en "Mis Jeedoms" en su perfil)

-   Agregando a otros escenarios de un probador de expresión

-   Revisión del sistema de intercambio de escenario


