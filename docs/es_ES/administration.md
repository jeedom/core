Aquí es donde se encuentran la mayoría de los parámetros de configuración.
Aunque muchos de ellos están preconfigurados por defecto

La página es accesible desde **Administración → Configuración**.

General
=======

En esta pestaña encontrará información general sobre Jeedom :

-   **Nombre de su Jeedom ** : Te permite identificar su Jeedom,
    particularmente en el mercado. Puede ser reutilizado en los escenarios
    o para identificar una copia de seguridad.

-   **Sstema** : Tipo de hardware en el que se instala el sistema donde
    tu Jeedom está ejecutandose.

-   **Clave de instalación** : Clave de hardware de su Jeedom sobre
    el mercado. Si su Jeedom no aparece en la lista de sus
    Jeedom en el mercado, es recomendable hacer clic en el botón
    **Restablecer a 0**.

-   **Idioma** : Idioma utilizado en su Jeedom.

-   **Generar traducciones** : Genera traducciones
    cuidado, puede ralentizar el sistema. opción especialmente útil
    para los desarrolladores.

-   **Duración de las sesiones (hora/s) **: Duración de las sesiones
    PHP, no se recomienda tocar este parámetro.

-   **Fecha y hora** : Elija su zona horaria. Puede
    hacer clic en **Sincronización forzada de la hora** para restablecer
    una hora incorrecta, mostrada arriba a la derecha.

-   **Servidor de Tiempo Opcional** : Indique qué servidor de tiempo usar
    para ser utilizado si hace clic en **Sincronización forzada de
    la hora**. (sólo para expertos)

-   **Ignorar la verificación horaria** : le dice a Jeedom que no debe
    comprobar si el tiempo es coherente entre sí mismo y el sistema de
    donde se ejecuta Puede ser útil, por ejemplo, si se conecta
    Jeedom Internet o no tiene batería RTC en el 
    hardware utilizado.

API
===

Aquí encontrará una lista de las diferentes claves API disponibles en
su Jeedom. Básicamente, el núcleo tiene dos claves API:

-   una general: en la medida de lo posible, evitar su utilización,

-   y otro para los profesionales utilizan para gestionar
    Parque. Puede estar vacío.

-   A continuación, encontrará una clave de API de plugins que lo necesitan.

Cada llave API de plugins, y para las API HTTP, y JSONRPC
TTS, puede definir su alcance:

-   ** ** Off: clave de la API no se puede utilizar,

-   ** ** blanca IP: solamente una lista de direcciones IP está permitido (ver
    Administración → Configuración → Redes)

-   ** ** localhost: Sólo las demandas del sistema en el que está
    instalado Jeedom están permitidos,

-   ** ** En: sin limitaciones, cualquier sistema con acceso
    su Jeedom puede tener acceso a esta API.

&gt; \ _OS / DB
===========

Dos partes reservadas a los expertos están presentes en esta ficha.

> **Importante**
>
> ADVERTENCIA: Si cambia Jeedom con una de estas dos soluciones,
> El vehículo puede negarse a ayudar.

-   **&gt; \ _SYSTEM**: Proporciona acceso a una interfaz
    Administración del sistema. Es una especie de consola con cáscara
    y se puede ejecutar los comandos más útiles, incluyendo
    para obtener información sobre el sistema.

-   ** ** Base de datos: Permite acceder a la base de datos
    de Jeedom. Puede ejecutar comandos en los campos
    desde lo alto. Se muestran dos configuraciones, a continuación, a título informativo:

    -   ** ** usuario: nombre de usuario utilizado por Jeedom en
        la base de datos,

    -   ** ** Contraseña: contraseña para acceder a la base de datos
        utilizado por Jeedom.

seguridad
========

LDAP
----

-   ** ** Habilitar la autenticación LDAP: Autenticación activa
    a través de una AD (LDAP)

-   ** ** Host: el servidor que aloja el anuncio

-   ** ** Dominio: dominio de su anuncio

-   ** ** Base DN: DN base de su anuncio

-   ** ** Nombre de usuario: nombre de usuario que es Jeedom
    se conecta a la AD

-   ** ** Contraseña: contraseña para Jeedom se conecta a la AD

-   ** ** Los campos de investigación de usuario: campos de investigación
    conexión del usuario. Generalmente UID para LDAP, para samaccountname
    de windows AD

-   **Filtro (opcional)** filtro en la AD (para la gestión
    grupos, por ejemplo)

-   **Permitir remoto \ _user**: Activa REMOTO \ _user (utilizado en SSO
    por ejemplo)

acceder
---------

-   ** ** Número de fallos tolerados: define el número de intentos
    sucesiva permitidos antes de la prohibición de IP

-   **Tiempo máximo entre fallos (segundo)**: tiempo máximo
    por dos intentos sucesivos se consideran

-   **destierro Tiempo (en segundos), -1 para infinito**: tiempo
    la prohibición de IP

-   **"blanco"** IP: lista de direcciones IP que nunca puede ser expulsado

-   ** ** Eliminar IPs prohibidas: Para borrar la lista de direcciones IP
    Actualmente prohibido

La lista de IP prohibido es en la parte inferior de esta página. Va a encontrar
IP, la fecha de expulsión y la fecha final de destierro
programada.

redes
=======

Es esencial para configurar correctamente esta parte importante de
Jeedom, de lo contrario muchos plugins no podría funcionar. lo
puede acceder Jeedom dos maneras: El acceso **
**interna (de la misma red local que Jeedom) y acceso**
** externa (de otra red, en particular de la Internet).

> **Importante**
>
> Esta parte es sólo para explicar a Jeedom su entorno:
> Modificación del puerto o IP en esta ficha no va a cambiar la
> Puerto o IP realidad Jeedom. A esto hay que conectar
> SSH y editar / etc / network / interfaces de IP y
> Archivos etc / apache2 / sites-available / default y
> etc / apache2 / sites-available / default \ _ssl (por HTTPS) .Sin embargo, en
> Si manejado mal su Jeedom el equipo Jeedom
> Se hace responsable y rechazará cualquier
> Apoyo.

-   ** ** El acceso interno: información para la unión de un Jeedom
    equipos de la misma red que Jeedom (LAN)

    -   **OK / NOK**: muestra si la configuración de la red interna
        correcto

    -   ** ** Protocolo: el protocolo a utilizar, a menudo HTTP

    -   ** ** URL o dirección IP: IP Jeedom para aprender

    -   ** ** Puerto: el puerto de la Jeedom interfaz web, por lo general 80.
        Precaución cambiar el puerto aquí no cambia el puerto real
        Jeedom seguirá siendo el mismo

    -   Complementar ** **: el fragmento de URL adicional (Ejemplo
        : / Jeedom) para acceder a Jeedom.

-   El acceso externo ** ** información para unirse Jeedom fuera
    LAN. Complete sólo si usted no está utilizando DNS
    Jeedom

    -   **OK / NOK** indica si la configuración de red externa
        correcto

    -   ** ** Protocolo: protocolo utilizado para el acceso desde el exterior

    -   ** ** URL o IP: IP externa, si es fijo. de lo contrario,
        Dar la URL que apunta a la dirección IP externa de su red.

    -   Complementar ** **: el fragmento de URL adicional (Ejemplo
        : / Jeedom) para acceder a Jeedom.

> **Tip**
>
> Si está utilizando el puerto HTTPS es 443 (por defecto) y el HTTP
> El puerto 80 (por defecto). Para utilizar HTTPS desde fuera,
> Letsencrypt un plugin ya está disponible en el mercado.

> **Tip**
>
> Para averiguar si es necesario establecer un valor en el campo
> ** ** suplemento, mira, cuando se conecte a Jeedom
> Su navegador de Internet si es necesario agregar / jeedom (u otro
> Cosa) después del PI.

-   ** ** Gestión Avanzada: Esta parte no puede aparecer en
    dependiendo de la compatibilidad con el hardware. Va a encontrar
    una lista de las interfaces de red. Se puede decir Jeedom
    no para supervisar la red haciendo clic fuera **
    gestión de la red por Jeedom ** (comprobar si Jeedom está conectado a
    sin red)

-   ** ** proxy Mercado: permite el acceso remoto a su Jeedom sin
    necesitará un DNS, IP fija o puertos abiertos en su caja
    Internet

    -   ** ** El uso de DNS Jeedom: activa DNS Jeedom (aviso
        se requiere al menos un paquete de servicio)

    -   ** ** DNS Estado: HTTP de estado de DNS

    -   ** ** Gestión: Detener y reiniciar el servicio DNS

> **Importante**
>
> Si no puede hacer funcionar el Jeedom DNS, ver
> Controlar el firewall y filtro de los padres de su caja de Internet
> (Por ejemplo, debe Livebox promedio cortafuegos).

colores
========

Colorear los widgets se realiza en base a la categoría
que posee el equipo. Las categorías incluyen la
calefacción, seguridad, energía, luz, Automatización, Multimedia, Otros ...

Para cada categoría, podemos diferenciar la versión en color
escritorio y la versión móvil. entonces podemos cambiar:

-   el color del fondo del widget,

-   el color del control cuando el widget es un tipo gradual (
    tales como luces, persianas, temperatura).

Al hacer clic en la ventana de color se abre, permitiendo elegir su
color. La cruz junto al color para volver al parámetro
por defecto.

En la parte superior, también se puede ajustar la transparencia de las
widgets de todo el mundo (este es el valor predeterminado. Es
entonces posible cambiar este widget widget de por valor). para
puso ninguna transparencia, permitir a 1,0.

> **Tip**
>
> No se olvide de guardar después de la modificación.

comandos
=========

Muchos comandos se pueden registrar. Así, en
Análisis → Historia, se obtiene gráficos de su
utilizar. Esta ficha le permite establecer la configuración global
el archivo de pedidos.

histórico
----------

-   **Muestra estadísticas sobre los widgets**: Muestra
    Estadísticas de widgets. Necesitamos que el widget es
    compatibles, como es el caso para la mayoría. También requiere que la
    el control es digital.

-   **período de cálculo para min, max, media (en horas)**: Período
    cálculo de las estadísticas (24 por defecto). No es possible
    a menos de una hora.

-   **El período de cálculo de la tendencia (en horas)** Periodo
    cálculo de la tendencia (2 horas de forma predeterminada). No es posible
    poner menos de una hora.

-   **Tiempo de archivado (horas)** Indica el retardo antes
    Jeedom datos no Archive (24 por defecto). Esto quiere decir que
    datos históricos deben ser mayores de 24 para ser archivados
    (Recuerde, el archivo será promediado o tomar el máximo
    o mínimo de los datos durante un período correspondiente a la
    tamaño de paquete).

-   **Archivo por paquetes (horas)**: Este parámetro
    precisamente el tamaño del paquete (1 defecto hr). Esto significa, por
    ejemplo Jeedom tomará tiempo de 1 h, y promediando
    almacenar el nuevo valor calculado mediante la eliminación
    valores promediados.

-   **cálculo de umbral baja tendencia**: Este valor indica el
    valor a partir del cual Jeedom indica que la tendencia es
    hacia abajo. Debe ser negativo (-0,1 por defecto).

-   ** ** cálculo del umbral por encima de la tendencia: Lo mismo para el ascenso.

-   **predeterminado periodo de visualización de gráficos** Periodo es
    usado por defecto cuando se desea ver el historial
    un comando. Cuanto más largo sea el período es más corto, será Jeedom rápida
    para mostrar el gráfico deseado.

> **Nota**
>
> El primer parámetro **Muestra estadísticas acerca de los widgets es**
> Posible, pero desactivado por defecto, ya que se extiende de manera significativa la
> Pantalla del salpicadero tiempo. Si habilita esta opción, por
> Jeedom por defecto se basa en datos de los últimos 24
> Calcular estas estadísticas. El método de cálculo se basa tendencia
> El cálculo del mínimo cuadrado (ver
> [Aquí] (https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> Para obtener más detalles).

empuje
----

** ** URL impulso global: le permite añadir una dirección URL para llamar en caso de
actualizar un pedido. Puede utilizar las siguientes etiquetas:
**\ # valor \ #** para el valor de la orden, **\ # cmd \ _name \ #** a
nombre del comando, **\ # cmd \ _id \ #** para el identificador único de la
control, **\ # humanname \ #** para el nombre completo del comando (por ejemplo,
\ # \ [Baño \] \ [Hydrometrie \] \ [Humedad \] \ #)

cubierta
=====

Se utiliza para monitorizar y actuar sobre la Jeedom caché:

-   ** ** Estadística: número de objetos almacenados en caché

-   ** ** caché limpia: Fuerza de eliminación de objetos que hay
    más útil. Jeedom realiza automáticamente todas las noches.

-   **Vaciar todos los datos de la caché**: completamente vacía la memoria caché.
    Tenga en cuenta que puede perder datos!

-   **Tiempo de pausa para el sondeo largo**: La velocidad a la que
    Jeedom comprueba si hay eventos que esperan a clientes
    (Interfaz web, móvil ...). Durante este tiempo es más corto,
    la interfaz se actualizará de forma rápida, en parte, en contra de ella
    utiliza más recursos y puede ralentizar Jeedom.

interacciones
============

Esta ficha le permite configurar los parámetros globales para
las interacciones que se encuentra en Herramientas → interacciones.

> **Tip**
>
> Para activar las interacciones de registro, vaya a la pestaña
> Administración → Configuración → Registros y seleccione Depurar ** ** en la lista
> Inferior. Advertencia: los registros serán entonces muy detallado!

general
-------

Aquí tiene tres parámetros:

-   ** ** Sensibilidad: hay 4 niveles de juego (Sensibilidad
    varía de 1 (exactamente) a 99)

    -   para 1 palabra coincidente nivel para las interacciones
        una palabra

    -   2 palabras: el nivel de correspondencia a las interacciones
        dos palabras

    -   3 palabras: el nivel de correspondencia a las interacciones
        tres palabras

    -   + 3 palabras: el nivel de correspondencia para las interacciones
        más de tres palabras

-   **No responda si no se incluye la interacción**: por defecto
    Jeedom responde "No entiendo" si no hay interacción
    partidos. Puede desactivar esta función para
    Jeedom que responde a nada. Marque la casilla para desactivar
    la respuesta.

-   **exclusión general Regex para la interacción**: Permite
    definir expresiones regulares que, si corresponde a una interacción,
    eliminar automáticamente esta generación frase (reservada
    expertos). Para obtener más información, véase la explicación en el
    Capítulo ** ** Regexp exclusión de la documentación
    interacciones.

Interacción automática, contextuales y de alerta
-------------------------------------------------- ---

-   ** ** Las interacciones automatizados permiten Jeedom intentar
    incluir una solicitud de interacción incluso si no hay
    de definido. A continuación, buscar un nombre y / o el equipo de objetos
    y / o control para tratar de responder en el mejor.

-   ** ** Las interacciones contextuales que permiten a la cadena
    varias peticiones sin repetir todo, por ejemplo:

    -   * Jeedom manteniendo el contexto: *

        -   * * Usted: ¿Cuánto cuesta la habitación?

        -   Jeedom * *: Temperatura 25,2 ° C

        -   * Usted *: y en la sala de estar?

        -   Jeedom * *: Temperatura 27,2 ° C

    -   * Pida a dos preguntas en una: *

        -   * * Usted: ¿Cuánto es en el dormitorio y en el salón?

        -   Jeedom * *: Temperatura 23,6 ° C 27,2 ° C Temperatura

-   ** ** Déjame saber el tipo de interacción utilizado para preguntar
    Jeedom le avise si un pedido excede / cae o es una
    cierto valor.

    -   * Usted *: Avisadme si la temperatura de la sala de estar supera los 25 ° C?

    -   * * Jeedom: OK (* Tan pronto como la temperatura de la sala de estar excede 25 ° C
        Jeedom le dirá, una vez *)

> **Nota**
>
> Por defecto Jeedom respuesta por el mismo canal que se
> Que utilizó para pedirle que le avise. Si él está en
> No, utilizará el comando predeterminado especificado en este
> Pestaña:. ** ** orden de restitución por defecto

Estas son las opciones disponibles:

-   ** ** Habilitar interacciones automatizadas: Marque para activar
    interacciones automatizadas.

-   ** ** Habilitar respuestas contextuales: Marque para activar
    interacciones contextuales.

-   **Prioridad respuesta contextual si la frase comienza con**: Si
    la frase que comienza con la palabra que usted ingrese aquí serán Jeedom
    a continuación, dar prioridad a una respuesta contextual (se puede poner
    varias palabras separadas por **, **).

-   **Cortar una interacción si contiene 2**: Lo mismo para
    corte de la interacción con varias preguntas. Usted
    Dar aquí las palabras que separan los diferentes temas.

-   **permitir la interacción "Avísame"**: Marque para activar
    Tipo interacciones ** ** hágamelo saber.

-   **Tipo de respuesta "Avísame" si la oración comienza con**: Si
    frase comienza con este / estos palabra (s) a continuación, tratar de hacer Jeedom
    tipo de interacción ** ** Avisadme (se puede poner más
    palabras separadas por **, **).

-   ** ** Comando de retorno por defecto orden de restitución por defecto
    para un tipo de interacción ** ** Avisadme (utilizadas en particular
    Si ha programado la alarma por la interfaz móvil)

-   ** ** Sinónimo de artículos: sinónimos para los artículos
    (Ej baja | planta baja | sótano | baja, cuarto de baño | cuarto de baño).

-   ** ** Sinónimo de equipos: lista de sinónimos para
    los equipos.

-   ** ** Sinónimo de órdenes: Lista de los sinónimos de
    las órdenes.

-   ** ** Sinónimo de resumen: Lista de los sinónimos de los resúmenes.

-   **Máximo control deslizante** Sinónimo: Sinónimo de poner un
    Tipo de control deslizante hacia arriba (por ejemplo, se abre para abrir la solapa
    Habitación ⇒ lado de la habitación 100%).

-   ** ** mínimo control deslizante Sinónimo: Sinónimo de poner un
    escriba control deslizante a minimu (ex granja cerca de la solapa
    ⇒ sala de cámara de componente a 0%).

colores
--------

En esta sección se definen los colores se combinan para Jeedom
palabras rojo / azul / negro ... Para añadir color:

-   Haga clic en ** ** +, a la derecha,

-   Dar un nombre a su color,

-   Elegir el color asociado haciendo clic en el cuadro de la derecha.

relaciones
========

Establece los informes de generación y gestión

-   **El tiempo de espera después de la generación de la página (en ms)**: Retardo
    informe de espera después de la carga a la "foto" de
    cambiar si su informe es incompleto, por ejemplo.

-   **informes limpios de más edad (días)**: Juegos
    Número de días antes de eliminar un informe (informes son
    un poco de espacio así que ten cuidado de no poner demasiado
    conservación).

Vínculos
=====

Configurar los enlaces gráficos. estos enlaces
ver, como un gráfico, la relación entre objetos,
equipo, objetos, etc.

-   ** ** escenarios Profundidad: poner a valor
    mostrando un gráfico de enlaces de un escenario, el número
    de indicios máxima (las más elementos en el
    gráfico será lenta para generar y la más difícil de leer).

-   **La profundidad de los artículos**: Lo mismo para los objetos.

-   **La profundidad para el equipo**: Lo mismo ocurre con el equipo.

-   ** ** controles Profundidad: Lo mismo para los pedidos.

-   **La profundidad de las variables**: Lo mismo para las variables.

-   ** ** Parámetro prerender: Para actuar sobre disponibles
    el gráfico.

-   ** ** parámetro de representación: El mismo.

resúmenes
=======

Añade resúmenes de objetos. Se muestra esta información
en la parte superior, a la derecha, en la barra de menú Jeedom, o al lado de la
artículos:

-   ** ** Clave: Resumen clave, sobre todo, no tocar.

-   ** ** Nombre: Nombre del resumen.

-   ** ** Cálculo: Método de cálculo, puede ser de tipo:

    -   ** ** Suma: la suma de los diferentes valores,

    -   ** ** media: valores promedio,

    -   ** ** Texto: muestra textualmente el valor (especialmente para aquellos
        Tipo de cadena de caracteres).

-   ** ** icono: Icono del resumen.

-   ** ** Unidad: Resumen de la unidad.

-   ** ** recuento Método: Si se cuentan los datos binarios a continuación,
    hay que poner este valor binario, por ejemplo, si se cuenta el
    número de luces encendidas pero sólo el valor de la
    inversor (0 a 100), entonces es necesario a binario, como Jeedom
    consideró que si el valor es mayor que 1, entonces la lámpara
    está encendido.

-   **Ver si el valor es 0**: Marque esta casilla para mostrar la
    valor, incluso cuando es 0.

-   **Enlace a un virtual**: Inicia la creación de unidades virtuales
    para aquellos que valoran el resumen.

-   ** ** Eliminar Resumen: El último botón, de acuerdo, permite
    quitar la línea de resumen.

troncos
====

línea de tiempo
--------

-   **El número máximo de eventos**: Establece el número máximo
    mostrar en la línea de tiempo.

-   ** ** Eliminar todos los eventos: Para vaciar la línea de tiempo
    todos los eventos grabados.

mensajes
--------

-   **Añadir un mensaje para cada error en los registros**: Si un plugin
    Jeedom o escribe un mensaje de error en un registro, añade Jeedom
    automáticamente un mensaje en el centro de mensajes (al menos
    que está seguro de no perder).

-   ** ** información de los usuarios del sistema: Seleccione una
    o más comandos (para separar por **&&**) escriba
    ** ** mensaje que se utiliza cuando la emisión
    nuevos mensajes.

Notificaciones
-------

-   **Añadir un mensaje a cada tiempo de espera** Añade un mensaje al
    centro de mensajes si se rompe equipo ** ** tiempo de espera.

-   **** Tiempo de espera de control en: Tipo de comando** ** enlace a utilizar
    si el equipo es ** ** tiempo de espera.

-   **Añadir un mensaje a cada batería en** Advertencia: Añade
    mensaje en el centro de mensajes si un dispositivo tiene su nivel
    ** ** aviso de batería.

-   **Comando de la batería en modo de advertencia**: Tipo de comando **enlace**
    si va a utilizar el equipo a su aviso de batería baja ** **.

-   **Añadir un mensaje a cada batería en Peligro** Añade
    mensaje en el centro de mensajes si su nivel de equipamiento
    batería mortales ** **.

-   **Control de la batería en Peligro**: Tipo de comando ** ** enlace a
    realice el trabajo si su nivel de batería amenazando ** **.

-   **Añadir un mensaje a cada uno** Advertencia: Añade un mensaje en el
    centro de mensajes si una orden entra en alerta ** ** advertencia.

-   **Aviso** de control en: tipo de orden **enlace** utilizar
    Si una orden entra en alerta ** ** advertencia.

-   **Añadir un mensaje a cada peligro** Añade un mensaje al
    centro de mensajes si una orden entra en alerta ** ** riesgo.

-   **** de control en Peligro: Tipo de comando** ** enlace de usar si
    pasar un pedido en alerta ** ** riesgo.

log
---

-   ** ** registro del motor: Cambiar el motor de registro, por
    ejemplo, los envía a un demonio syslog (d).

-   ** ** formato de los registros de formato de registro a utilizar (Advertencia: este
    no afecta a los demonios logs).

-   **El número máximo de líneas en un archivo de registro**: Conjuntos
    número máximo de líneas en un archivo de registro. Se recomienda
    no tocar este valor, porque el exceso de valor podría
    llenar el sistema de archivos y / o incapaz de hacer Jeedom
    para mostrar el registro.

-   **Nivel de registro predeterminado**: Cuando se selecciona "Default"
    al nivel de una conexión a la Jeedom es la que se
    entonces utilizado.

A continuación encontrará una tabla para gestionar finamente
log elementos nivel esencial de Jeedom así como la de
plugins.

comodidades
===========

-   **El número de fallos antes de deshabilitar el equipo**: Número
    fallos de comunicación con el equipo antes de inhabilitar
    él (un mensaje le notificará si eso ocurre).

-   ** ** Los umbrales de la batería: Administrar los umbrales de alerta mundial
    en las baterías.

Actualización y archivos
=======================

actualización Jeedom
---------------------

-   **Actualización** Fuente: Seleccione la fuente de actualización
    núcleo de Jeedom.

-   ** ** Versión núcleo: versión de la base de recuperar.

-   **Comprobar automáticamente si hay actualizaciones**: Si
    para buscar automáticamente si hay nuevas actualizaciones
    (Cuidado de no sobrecargar el mercado, el tiempo
    verificación puede cambiar).

depósitos
----------

Los depósitos son espacios de almacenamiento (y servicio) para suministrar energía
mover las copias de seguridad, recuperar plugins recuperar el núcleo de
Jeedom etc.

### expediente

Depósito para activar el envío de archivos de plugins.

### Github

Depósito para la conexión a Jeedom Github.

-   ** ** simbólico: token de acceso a depósito privado.

-   **usuario u organización que presenta para la Jeedom núcleo** Nombre
    usuario u organización en github para el núcleo.

-   **Nombre del depósito para el núcleo Jeedom**: Nombre del depósito para el núcleo.

-   **Branch para la Jeedom núcleo**: Branch del depósito para el núcleo.

### mercado

Depósito para conectar Jeedom el mercado, es muy recomendable
el uso de esta presentación. Advertencia: la solicitud de soporte puede ser
niega si se utiliza otro depósito él.

-   ** ** Dirección: Dirección del Mercado.

-   ** ** Usuario: Su nombre de usuario en el mercado.

-   ** ** Contraseña: Tu contraseña mercado.

### samba

Depósito para enviar automáticamente una copia de seguridad en Jeedom
una cuota de Samba (por ejemplo Synology NAS).

-   **\ [Backup \]** IP: IP del servidor Samba.

-   **\ [Backup \] Usuario**: Nombre de usuario para la conexión
    (Conexiones anónimas no son posibles). Se debe necesariamente
    el usuario tiene permiso de lectura y escritura en
    directorio de destino.

-   **\ [Backup \]** Contraseña: contraseña del usuario.

-   **\ [Backup \] Compartiendo**: compartir Path (cuidado
    parar en la división).

-   **\ [Backup \]** Ruta: Camino en las acciones (que es
    relativa), que debe existir.

> **Nota**
>
> Si la ruta de acceso a su carpeta de respaldo de samba es:
> \\\\ 192.168.0.1 \\ Copia de seguridad \\ Domótica \\ Jeedom entonces IP = 192.168.0.1
>, Recurso compartido = //192.168.0.1/Copia de seguridad, Carpeta = Domótica / Jeedom

> **Nota**
>
> Al validar el recurso compartido de Samba descrito,
> aparece una nueva forma de copia de seguridad en la sección
> Administración → Copias de seguridad de Jeedom. Al activarlo, Jeedom procederá
> a su envío automático durante la próxima copia de seguridad. Puede probarse
> realizando una copia de seguridad manual.

> **Importante**
>
> Es posible que deba instalar el paquete smbclient para
> que la transferencia funcione.

> **Importante**
>
> El protocolo de Samba tiene varias versiones, la seguridad en el v1
>  está comprometida y en algunos NAS puede obligar al cliente a usar el v2
> o v3 para conectarse. Por ello, si tiene un error de negociación de protocolo
> fallida: NT_STATUS_INVAID_NETWORK_RESPONSE existe una buena posibilidad de que el problema
> esté en el NAS. A continuación, debe editar en el sistema operativo de su Jeedom
> el archivo /etc/samba/smb.conf y agregar estas dos líneas:
> client max protocol = SMB3
> cliente min protocol = SMB2
> El smbclient de Jeedom usará v2 o v3 y, poniendo SMB3 a los dos(max y min), usará solamente
> SMB3. Depende de usted adaptarse según las restricciones del lado del NAS u otro servidor de Samba

> **Importante**
>
> Jeedom debe ser el único que escriba en esta carpeta y debe estar vacía
> por defecto (es decir, antes de configurar y enviar la
> primera copia de seguridad, la carpeta no debe contener ningún archivo o
> carpeta).

### URL

-   ** ** URL núcleo Jeedom

-   **URL núcleo versión Jeedom**


