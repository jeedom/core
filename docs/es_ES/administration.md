Aquí es donde se encuentran la mayoría de los parámetros de configuración..
Aunque muchos, la mayoría esán preconfigurados por defecto.

Se puede acceder a la página a través de  **Preferencias → sistema → Configuración**.

general
=======

En esa pesaña encontramos información general sobre Jeedom :

-   **número de tu Jeedom** : Vamos a identificar tu Jeedom,
    especialmente en el mercado. Se puede reutilizar en escenarios
    o identificar una copia de seguridad.

-   **Lengua** : Lenguaje usado en tu Jeedom.

-   **sistema** : puntao de hardware en el que esá instalado el sistema donde
    tu Jeedom esá girando.

-   **Generar traducciones** : Generar traducciones,
    tenga cuidado, eso puede ralentizar su sistema. Opción más útil
    para desarrolladores.

-   **Fecha y hora** : Elige tu zona horaria. Usted puede
    haga clic en **Forzar sincronización de tiempo** para resaurar
    se muesra un mal momento en la esquina superior derecha.

-   **Servidor horario opcional** : Indica qué servidor horario debe
    ser usado si haces clic **Forzar sincronización de tiempo**
    (para reservar para expertos).

-   **Omitir verificación de tiempo** : le dice a Jeedom que no
    verificar si el tiempo es consistente entre sí mismo y el sistema encendido
    que da vuelta. Puede ser útil, por ejemplo, si no se conecta
    no Jeedom a Internet y que no tiene batería PSTN en el
    material utilizado.

-   **sistema** : Indica el tipo de hardware en el que esá instalado Jeedom.   

-   **Clave de instalación** : Llave de hardware de su Jeedom en
    el mercado. si su Jeedom no aparece en la lista de su
    Jeedom on the Market, es recomendable hacer clic en el botón
    **Resablecer**.

-   **Última fecha conocida** : Fecha registrada por Jeedom, utilizada después
    un reinicio para sistemas sin una pila PSTN.

interfaz
=========

En esa pesaña encontrará los parámetros de personalización de la pantalla..

temas
------

-   **Escritorio claro y oscuro** : Le permite elegir un tema claro
    y uno oscuro para el escritorio.

-   **Móvil claro y oscuro** : igual que el anterior para la versión móvil.

-   **Borrar tema de / a** : Le permite definir un período de tiempo durante el cual
    se utilizará el tema claro elegido previamente. sin embargo, marque la opción
    **Cambiar el tema según el tiempo**.

-   **Sensor de brillo**   : Interfaz móvil solamente, requiere activación
    sensor adicional genérico en cromo, página de cromo:// banderas

-   **Ocultar imágenes de fondo** : Se usa para ocultar las imágenes de fondo encontradas
    en las páginas de escenarios, objetos, interacciones, etc..

embaldosado
------

-   **Azulejos no horizontales** : Resringe el ancho de los mosaicos cada x píxeles.

-   **Azulejos no verticales** : Resringe la altura de los mosaicos cada x píxeles.

-   **Azulejos de margen** : Espacio vertical y horizontal entre mosaicos, en píxeles..

personalización
----------------

redes
=======

Es absolutamente necesario configurar correctamente esa parte importante de
Jeedom, de lo contrario, muchos complementos pueden no funcionar. Él
Es posible acceder a Jeedom de dos maneras diferentes : la**acceso
interna** (de la misma red local que Jeedom) y**acceso
externo** (desde otra red, en particular desde Internet).

> **importante**
>
> Esta parte esá ahí para explicarle a Jeedom su entorno. :
> cambiar el puerto o la IP en esa pesaña no cambiará el
> Puerto Jeedom o IP en realidad. Para hacer eso, debe iniciar sesión
> SSH y edite el archivo / etc / network / interfaces para la IP y
> etc / apache2 / sites-available / archivos predeterminados y
> etc / apache2 / sites-available / default \ _ssl (para HTTPS). sin embargo, en
> si su Jeedom se maneja mal, el equipo de Jeedom no
> puede ser considerado responsable y puede rechazar cualquier solicitud de
> apoyo.

-   **Acceso interno** : información para unirse a Jeedom desde un
    mismo equipo de red que Jeedom (LAN)

    -   **OK / NOK** : indica si la configuración de red interna es
        correcto

    -   **protocolo** : el protocolo a usar, a menudo HTTP

    -   **URL o dirección IP** : Jeedom IP para entrar

    -   **puerto** : el puerto de la interfaz web de Jeedom, generalmente 80.
        Tenga en cuenta que cambiar el puerto aquí no cambia el puerto real de
        Jeedom que seguirá siendo el mismo

    -   **complementar** : el fragmento de URL adicional (ejemplo
        : / jeedom) para acceder a Jeedom.

-   **Acceso externo** : información para llegar a Jeedom desde afuera
    red local. Para completar solo si no esá utilizando DNS
    Jeedom

    -   **OK / NOK** : indica si la configuración de red externa es
        correcto

    -   **protocolo** : protocolo utilizado para acceso al exterior

    -   **URL o dirección IP** : IP externa, si es fija. de lo contrario,
        proporcione la URL que apunta a la dirección IP externa de su red.

    -   **complementar** : el fragmento de URL adicional (ejemplo
        : / jeedom) para acceder a Jeedom.

-   **Proxy para el mercado** : activación proxy.

    - Marque la casilla habilitar proxy

    - **Dirección proxy** : Ingrese la dirección del proxy,

    - **Puerto proxy** : Ingrese el puerto proxy,

    - **login** : Ingrese el inicio de sesión proxy,

    - **Contraseña** : Ingrese la contraseña.

> **punta**
>
> si esá en HTTPS, el puerto es 443 (por defecto) y en HTTP el
> el puerto es 80 (predeterminado). Para usar HTTPS desde afuera,
> un complemento de letsencrypt ya esá disponible en el mercado.

> **punta**
>
> Para saber si necesita esablecer un valor en el campo
> **complementar**, mira, cuando inicias sesión en Jeedom en
> su navegador de Internet, si necesita agregar / jeedom (u otro
> cosa) después de la IP.

-   **Gesión avanzada** : Esta parte puede no aparecer, en
    dependiendo de la compatibilidad con su hardware. Encontraras alli
    la lista de sus interfaces de red. Puedes decirle a Jeedom
    no monitorear la red haciendo clic en **desactivar el
    gesión de red por Jeedom** (compruebe si Jeedom no esá conectado a
    sin red). También puede especificar el rango de ip local en la forma 192.168.1.* (para usarse solo en instalaciones de tipo acoplable)

-   **Mercado proxy** : permite el acceso remoto a su Jeedom sin tener
    necesita un DNS, una IP fija o para abrir los puertos de su caja
    Internet

    -   **Usando Jeedom DNS** : activa Jeedom DNS (atención
        eso requiere al menos un paquete de servicio)

    -   **Estado DNS** : Estado HTTP HTTP

    -   **administración** : permite detener y reiniciar el servicio DNS de Jeedom

> **importante**
>
> si no puede hacer que funcione Jeedom DNS, consulte el
> configuración del cortafuegos y filtro parental de su caja de Internet
> (en livebox necesita, por ejemplo, el firewall en nivel medio).
-   **Duración de las sesiones (hora)** : vida de sesiones
    PHP, no se recomienda tocar ese parámetro.

troncos
====

línea de tiempo
--------

-   **Numero maximo de eventos** : Establece el número máximo de eventos en
    mostrar en la línea de tiempo.

-   **Eliminar todos los eventos** : Vaciar la línea de tiempo de
    todos sus eventos grabados.

mensajes
--------

-   **Agregue un mensaje a cada error en los registros** : si un complemento
    o Jeedom escribe un mensaje de error en un registro, agrega Jeedom
    automáticamente un mensaje en el centro de mensajes (al menos
    seguro que no te lo perderás).

-   **Acción sobre mensaje** : Le permite realizar una acción al agregar un mensaje al centro de mensajes. Tienes 2 etiquetas para esas acciones :
        - #sujeto# : mensaje en cuesión
        - #plugin# : complemento que activó el mensaje

Notificaciones
-------

-   **Agregar un mensaje a cada tiempo de espera** : Agregue un mensaje en el
    centro de mensajes si el equipo cae en **tiempo de espera**.

-   **Orden de tiempo de espera** : Comando de tipo **mensaje** a utilizar
    si hay un equipo en **tiempo de espera**.

-   **Agregue un mensaje a cada batería en Advertencia** : Añadir un
    mensaje en el centro de mensajes si un dispositivo tiene su nivel de
    batería en **advertencia**.

-   **Comando de batería en Advertencia** : Comando de tipo **mensaje**
    para ser usado si el equipo tiene el nivel de batería **advertencia**.

-   **Agregue un mensaje a cada batería en peligro** : Añadir un
    mensaje en el centro de mensajes si un dispositivo en su nivel de
    batería en **peligro**.

-   **Comando con batería en peligro** : Comando de tipo **mensaje** para
    úselo si el equipo tiene el nivel de batería **peligro**.

-   **Agregue un mensaje a cada Advertencia** : Agregue un mensaje en el
    centro de mensajes si un pedido entra en alerta **advertencia**.

-   **Comando de advertencia** : Comando de tipo **mensaje** a utilizar
    si un pedido se pone en alerta **advertencia**.

-   **Agregue un mensaje a cada peligro** : Agregue un mensaje en el
    centro de mensajes si un pedido entra en alerta **peligro**.

-   **Comando en peligro** : Comando de tipo **mensaje** usar si
    una orden se pone en alerta **peligro**.

troncos
----

-   **Motor de registro** : Le permite cambiar el motor de registro para
    ejemplo, envíelos a un demonio syslog (d).

-   **Formato de registro** : Formato de registro a utilizar (Precaución : lo
    no afecta los registros de demonios).

-   **Número máximo de líneas en un archivo de registro** : Define el
    número máximo de líneas en un archivo de registro. Se recomienda
    no tocar ese valor, porque un valor demasiado grande podría
    llenar el sistema de archivos y / o dejar a Jeedom incapaz
    para mostrar el registro.

-   **Nivel de registro predeterminado** : Cuando selecciona &quot;Predeterminado&quot;,
    para el nivel de un registro en Jeedom, ese es el que será
    luego usado.

A continuación encontrará una tabla para administrar finamente el
nivel de registro de los elementos esenciales de Jeedom, así como el de
plugins.

comandos
=========

Se pueden registrar muchos pedidos. Entonces en
Análisis → Historia, obtienes gráficos que representan sus
utilizar. Esta pesaña le permite esablecer parámetros globales para
historial de pedidos.

histórico
----------

-   **Ver esadísticas de widgets** : Muesra
    esadísticas del widget. El widget debe ser
    compatible, que es el caso para la mayoría. También es necesario que el
    comando ya sea digital.

-   **Período de cálculo para min, max, promedio (en horas)** : período
    cálculo de esadísticas (24h por defecto). No es possible
    poner menos de una hora.

-   **Periodo de cálculo de la tendencia (en horas)** : Periodo de
    cálculo de tendencia (2h por defecto). No es posible
    poner menos de una hora.

-   **Retraso antes de archivar (en horas)** : Indica el retraso antes
    Jeedom no archiva datos (24h por defecto). Es decir, el
    los datos históricos deben tener más de 24 horas para ser archivados
    (como recordatorio, el archivo promediará o tomará el máximo
    o el mínimo de los datos durante un período que corresponde a la
    tamaño del paquete).

-   **Archivar por paquete desde (en horas)** : Este parámetro da
    Precisamente el tamaño de los paquetes (1 hora por defecto). significa por
    ejemplo que Jeedom tomará períodos de 1 hora, promedio y
    almacenar el nuevo valor calculado eliminando el
    valores promediados.

-   **Umbral de cálculo de tendencia baja** : Este valor indica el
    valor a partir del cual Jeedom indica que la tendencia es hacia
    hacia abajo. Debe ser negativo (predeterminado -0.1).

-   **Alto umbral de cálculo de tendencia** : Lo mismo para el ascenso.

-   **Período predeterminado de visualización de gráficos** : Período que es
    se usa de manera predeterminada cuando desea mostrar el historial
    de una orden. Cuanto más corto sea el período, más rápido será Jeedom
    para mostrar el gráfico solicitado.

> **nota**
>
> El primer parámetro **Ver esadísticas de widgets** es
> posible pero deshabilitado por defecto porque alarga significativamente el
> tiempo de visualización del tablero. si activa esa opción, por ejemplo
> por defecto, Jeedom se basa en datos de las últimas 24 horas para
> calcular esas esadísticas. El método de cálculo de tendencia se basa
> en el cálculo de mínimos cuadrados (ver
> [ici](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> para más detalles).

empuje
----

**URL de inserción global** : permite agregar una URL para llamar en caso de
orden de actualización. Puedes usar las siguientes etiquetas :
**\ #Value \#** por el valor del pedido, **\ #Cmd \ _name \#** para el
nombre del comando **\ #Cmd \ _id \#** para el identificador único de la
mando, **\ #Humanname \#** para el nombre completo de la orden (ej. :
\ # \ [Baño \] \ [Hidrometría \] \ [Humedad \] \ #), `# eq_name #` para el nombre del equipo

resúmenes
=======

Agregar resúmenes de objetos. Esta información se muesra
en la parte superior, a la derecha, en la barra de menú de Jeedom, o al lado del
objetos :

-   **clave** : Clave para el resumen, especialmente para no tocar.

-   **apellido** : número abstracto.

-   **cálculo** : Método de cálculo, puede ser de tipo :

    -   **suma** : suma los diferentes valores,

    -   **promedio** : promedia los valores,

    -   **texto** : mostrar el valor literalmente (especialmente para aquellos
        tipo de cadena).

-   **icono** : Ícono de resumen.

-   **unidad** : Unidad de resumen.

-   **Método de conteo** : si cuenta datos binarios, entonces
    debe esablecer ese valor en binario, por ejemplo, si cuenta
    cantidad de luces encendidas pero solo tienes el valor de
    dimmer (0 a 100), entonces tienes que poner binario, como ese Jeedom
    considere que si el valor es mayor que 1, entonces la lámpara
    esá encendido.

-   **Mostrar si el valor es 0** : Marque esa casilla para mostrar el
    valor, incluso cuando es 0.

-   **Enlace a un virtual** : Comienza a crear pedidos virtuales
    teniendo por valor los del resumen.

-   **Eliminar resumen** : El último botón, en el extremo derecho, permite
    para eliminar el resumen de la línea.

comodidades
===========

-   **Número de fallas antes de la desactivación del equipo.** : número
    falla de comunicación con el equipo antes de la desactivación de
    ese (un mensaje te avisará si eso sucede).

-   **Umbrales de la batería** : Le permite administrar umbrales de alerta global
    en las baterías.

relaciones
========

Configurar la generación y gesión de informes.

-   **Tiempo de espera después de la generación de la página (en ms)** : aviso
    esperando después de cargar el informe para tomar la &quot;foto&quot;, en
    cambiar si su informe esá incompleto, por ejemplo.

-   **Limpiar informes anteriores de (días)** : Define el
    Número de días antes de eliminar un informe (los informes tardan
    un poco de espacio, así que ten cuidado de no poner demasiado
    conservación).

Vínculos
=====

Configurar gráficos de enlace. Estos enlaces te permiten
ver, en forma de gráfico, las relaciones entre objetos,
equipos, objetos, etc..

-   **Profundidad para escenarios** : Se usa para definir cuándo
    mostrando un gráfico de enlaces de un escenario, el número
    Número máximo de elementos para mostrar (cuantos más elementos, mayor será
    será más lento de generar y más difícil será leer).

-   **Profundidad para objetos** : Lo mismo para los objetos..

-   **Profundidad para equipamiento** : Lo mismo para el equipo..

-   **Profundidad para controles** : Lo mismo para pedidos.

-   **Profundidad para variables** : Lo mismo para las variables..

-   **Parámetro de prerender** : Vamos a actuar sobre el diseño
    del gráfico.

-   **Parámetro de procesamiento** : ídem.

interacciones
============

Esta pesaña le permite esablecer parámetros globales relacionados con
interacciones que encontrarás en Herramientas → Interacciones.

> **punta**
>
> Para activar el registro de interacción, vaya a la pesaña
> Administración → Configuración → Registros, luego marque **depurar** en la lista
> de abajo. Atención : los registros serán muy detallados !

general
-------

Aquí tienes tres parámetros :

-   **sensibilidad** : Hay 4 niveles de correspondencia (sensibilidad
    varía de 1 (coincide exactamente) a 99)

    -   por 1 palabra : el nivel de correspondencia para las interacciones en
        una palabra

    -   2 palabras : el nivel de correspondencia para las interacciones en
        dos palabras

    -   3 palabras : el nivel de correspondencia para las interacciones en
        tres palabras

    -   más de 3 palabras : el nivel de correspondencia para las interacciones
        mas de tres palabras

-   **No responda si no se entiende la interacción.** : por defecto
    Jeedom responde &quot;No entendí&quot; si no hay interacción
    no coincide. Es posible desactivar esa función para
    que Jeedom no responde nada. Marque la casilla para desactivar
    la respuesa.

-   **Regex de exclusión general para interacciones** : permite
    definir una expresión regular que, si corresponde a una interacción,
    eliminará automáticamente esa oración de la generación (reservado
    a expertos). Para obtener más información, consulte las explicaciones en
    capítulo **Exclusión de expresiones regulares** documentación sobre
    interacciones.

Interacción automática, contextual y advertencia
-----------------------------------------------------

-   la **interacciones automáticas** permitir que Jeedom intente
    entender una solicitud de interacción incluso si no hay ninguna
    de definido. Luego buscará un objeto y / o nombre del equipo
    y / o para tratar de responder lo mejor posible.

-   la **interacciones contextuales** permitirte encadenar
    múltiples solicitudes sin repetir todo, por ejemplo :

    -   *Jeedom manteniendo el contexto :*

        -   *Vosotras* : Cuanto esa el en el cuarto ?

        -   *Jeedom* : Temperatura 25.2 ° C

        -   *Vosotras* : y en la sala de esar ?

        -   *Jeedom* : Temperatura 27.2 ° C

    -   *Haz dos preguntas en una :*

        -   *Vosotras* : ¿Cómo es en el dormitorio y en la sala de esar? ?

        -   *Jeedom* : Temperatura 23.6 ° C, temperatura 27.2 ° C

-   Interacciones de tipo **Avísame** preguntemos
    Jeedom para notificarle si un pedido excede / desciende o vale la pena
    cierto valor.

    -   *Vosotras* : Notificarme si la temperatura de la sala supera los 25 ° C ?

    -   *Jeedom* : OK (* Tan pronto como la temperatura de la sala supere los 25 ° C,
        Jeedom te lo dirá, solo una vez *)

> **nota**
>
> Por defecto, Jeedom te responderá por el mismo canal que el que tú
> solía pedirle que te notificara. si no encuentra uno
> no, usará el comando predeterminado especificado en ese
> pesaña : **Comando de retorno predeterminado**.

Aquí esán las diferentes opciones disponibles. :

-   **Habilitar interacciones automáticas** : Marque para activar
    interacciones automáticas.

-   **Habilitar respuesas contextuales** : Marque para activar
    interacciones contextuales.

-   **Respuesa contextual prioritaria si la oración comienza con** : si
    la oración comienza con la palabra que ingresas aquí, Jeedom lo hará
    luego priorice una respuesa contextual (puede poner
    varias palabras separadas por **;** ).

-   **Cortar una interacción a la mitad si contiene** : Lo mismo para
    El desglose de una interacción que contiene varias preguntas. Vosotras
    da aquí las palabras que separan las diferentes preguntas.

-   **Activa las interacciones &quot;Notificarme&quot;** : Marque para activar
    interacciones de tipo **Avísame**.

-   **Respuesa &quot;Dime&quot; si la oración comienza con** : si la
    la oración comienza con esa (s) palabra (s) y luego Jeedom intentará hacer una
    tipo de interacción **Avísame** (puedes poner múltiples
    palabras separadas por **;** ).

-   **Comando de retorno predeterminado** : Comando de retorno predeterminado
    para una interacción tipo **Avísame** (utilizado, en particular,
    si ha programado la alerta a través de la interfaz móvil)

-   **sinónimo de objetos** : Lista de sinónimos para objetos
    (por ejemplo, : planta baja | planta baja | sótano | planta baja; baño | baño).

-   **sinónimo de equipamiento** : Lista de sinónimos para
    los equipos.

-   **sinónimo de pedidos** : Lista de sinónimos para
    las órdenes.

-   **sinónimo de resúmenes** : Lista de sinónimos para resúmenes.

-   **sinónimo de comando de control deslizante máximo** : sinónimo de poner un
    comando de tipo de control deslizante máximo (por ejemplo, se abre para abrir el obturador
    la habitación ⇒ 100% de la persiana de la habitación).

-   **sinónimo de comando mínimo de control deslizante** : sinónimo de poner un
    comando de tipo deslizador como mínimo (por ejemplo, se cierra para cerrar el obturador
    la sala ⇒ componente de sala al 0%).

seguridad
========

LDAP
----

-   **Habilitar autenticación LDAP** : habilitar autenticación para
    a través de un AD (LDAP)

-   **anfitrión** : servidor que aloja el AD

-   **dominio** : dominio de su AD

-   **DN base** : DN base de su AD

-   **nombre del usuario** : nombre de usuario para que Jeedom
    conectarse a AD

-   **Contraseña** : contraseña para que Jeedom se conecte a AD

-   **Campos de busqueda de usuario** : campos de búsqueda de
    inicio de sesión de usuario. Por lo general, uid para LDAP, SamAccountName para
    Windows AD

-   **Filtro (opcional)** : filtro en el AD (para administrar
    grupos por ejemplo)

-   **Permitir REMOTO \ _USER** : Active REMOTE \ _USER (usado en SSO
    por ejemplo)

acceder
---------

-   **Número de fallas toleradas** : esablece el número de intentos
    permitido antes de prohibir la IP

-   **Tiempo máximo entre fallas (en segundos)** : tiempo maximo
    para que 2 intentos se consideren sucesivos

-   **Duración del desierro (en segundos), -1 por infinito** : tiempo de
    Prohibición de IP

-   **IP &quot;blanco&quot;** : lista de IP que nunca se pueden prohibir

-   **Eliminar IP prohibidas** : Borrar la lista de IP
    actualmente prohibido

La lista de IP prohibidas se encuentra al final de esa página.. Encontraras alli
IP, fecha de prohibición y fecha de finalización de prohibición
programado.

Actualización y archivos
=======================

Actualización de Jeedom
---------------------

-   **Fuente de actualización** : Elija la fuente para actualizar el
    Núcleo de Jeedom.

-   **Versión Core** : Versión principal para recuperar.

-   **Buscar actualizaciones automáticamente** : Indicar si
    tienes que buscar automáticamente si hay nuevas actualizaciones
    (tenga cuidado de evitar sobrecargar el mercado, el tiempo de
    la verificación puede cambiar).

Depósitos
----------

Los depósitos son espacios de almacenamiento (y servicio) para poder
mover copias de seguridad, recuperar complementos, recuperar núcleo
Jeedom, etc..

### expediente

Depósito utilizado para activar el envío de complementos por archivos.

### Github

Depósito utilizado para conectar Jeedom a Github.

-   **simbólico** : simbólico para acceder al depósito privado.

-   **Usuario u organización del repositorio principal de Jeedom** : apellido
    el usuario o la organización en github para el núcleo.

-   **número del repositorio para el núcleo Jeedom** : apellido du dépôt para el core.

-   **Branche para el core Jeedom** : Branche du dépôt para el core.

### Market

Dépôt servant para relier Jeedom au market, il es vivement conseillé
d'utiliser ce dépôt. Atención : toute demande de apoyo pourra être
refusée si vous utilisez un autre dépôt que celui-ci.

-   **Adresse** : Adresse du Market.(https://www.jeedom.com/market)

-   **nombre del usuario** : Votre nom d'utilisateur sur el mercado.

-   **Contraseña** : Votre mot de passe du Market.

-   **[Backup cloud] apellido** : apellido de votre backup cloud (attention doit etre unique pour chaque Jeedom sous risque qu'il s'écrase entre eux)

-   **[Backup cloud] Contraseña** : Contraseña du backup cloud. IMPORTANT vous ne devez surtout pas le perdre, il n'y a aucun moyen de le récuperer. Sans celui-ci vous ne pourrez plus resaurer votre Jeedom.

-   **[Backup cloud] Fréquence backup full** : Fréquence du backup cloud full. Un backup full es plus long qu'un incrémental (qui n'envoie que les différences). Se recomienda d'en faire 1 par mois

### Samba

Dépôt permettant d'envoyer automatiquement une sauvegarde de Jeedom sur
un partage Samba (por ejemplo, : NAS Synology).

-   **\[Backup\] IP** : IP du serveur Samba.

-   **\[Backup\] Utilisateur** : nombre del usuario pour la connexion
    (les connexions anonymes ne sont pas possibles). Il faut forcément
    que l'utilisateur ait les droits en lecture ET en écriture sur le
    répertoire de desination.

-   **\[Backup\] Contraseña** : Contraseña de l'utilisateur.

-   **\[Backup\] Partage** : Chemin du partage (attention para bien
    s'arrêter au niveau du partage).

-   **\[Backup\] Chemin** : Chemin dans le partage (para mettre en
    relatif), celui-ci doit exister.

> **nota**
>
> si le chemin d'acceso para votre dossier de sauvegarde samba es :
> \\\\192.168.0.1\\Sauvegardes\\Domotique\\Jeedom Alors IP = 192.168.0.1
> , Partage = //192.168.0.1/Sauvegardes , Chemin = Domotique/Jeedom

> **nota**
>
> Lors de la validation du partage Samba, tel que décrit précédemment,
> une nouvelle forme de sauvegarde apparait dans la partie
> Administration→Sauvegardes de Jeedom. En l'activant, Jeedom procèdera
> para son envoi automatique lors de la prochaine sauvegarde. Un tes es
> possible en effectuant une sauvegarde manuelle.

> **importante**
>
> Il vous faudra peut-être installer le package smbclient pour que le
> dépôt fonctionne.

> **importante**
>
> Le protocole Samba comporte plusieurs versions, la v1 es compromise niveau
> sécurité et sur certains NAS vous pouvez obliger le client a utilizar la v2
> ou la v3 pour se connecter. Donc si vous avez une erreur protocol negotiation
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE il y a de forte chance que coté NAS
> la resriction soit en place. Vosotras devez alors modifier sur l'OS de votre Jeedom
> le fichier /etc/samba/smb.conf et y ajouter ces deux lignes :
> client max protocol = SMB3
> client min protocol = SMB2
> Le smbclient coté Jeedom utilisera alors v2 où v3 et en mettant SMB3 aux 2 uniquement
> SMB3. A vous donc d'adapter en fonction des resrictions côté NAS ou autre serveur Samba

> **importante**
>
> Jeedom doit être le seul para écrire dans ce dossier et il doit être vide
> por defecto (c'es-para-dire qu'avant la configuration et l'envoi de la
> première sauvegarde, le dossier ne doit contenir aucun fichier ou
> dossier).

### URL

-   **URL core Jeedom**

-   **URL version core Jeedom**

Cache
=====

Permet de surveiller et d'agir sur le cache de Jeedom :

-   **Statistiques** : número d'objetos actuellement en cache

-   **Nettoyer le cache** : Force la suppression des objetos qui ne sont
    plus utiles. Jeedom le fait automatiquement toutes les nuits.

-   **Vider toutes les données en cache** : Vide complètement le cache.
    Atención cela peut faire perdre des données !

-   **Vider le cache des widgets** : Vide le cache dédié aux widgets

-   **Désactiver le cache des widgets** : Cocher la case pour désactiver
    le caches des widgets

-   **Temps de pause para el long polling** : Fréquence para laquelle
    Jeedom vérifie si il y a des événements en attente para els clients
    (interface web, application mobile…​). Plus ce temps es court, plus
    l'interface se mettra para jour rapidement, en contre partie cela
    utilise plus de ressources et peut donc ralentir Jeedom.

API
===

Vosotras trouvez ici la liste des différentes clés API disponibles dans
votre Jeedom. De base, le core a deux clés API :

-   une générale : autant que possible, il faut éviter de l'utiliser,

-   et une autre para els professionnels : utilisée pour la gesion
    de parc. Elle peut être vide.

-   Puis, vous trouverez une clé API par plugin en ayant besoin.

Pour chaque clé API de plugin, ainsi que para els APIs HTTP, JsonRPC et
TTS, vous pouvez définir leur portée :

-   **Désactivée** : la clé API ne peut être utilisée,

-   **IP blanche** : seule une liste d'IPs es autorisée (voir
    Administration→Configuration→redes),

-   **Localhost** : seules les requêtes venant du système sur lequel es
    installé Jeedom sont autorisées,

-   **Activé** : aucune resriction, n'importe quel système ayant acceso
    para votre Jeedom pourra accéder para cette API.

&gt;\_OS/DB
===========

Deux parties réservées aux experts sont présentes dans cet pesaña.

> **importante**
>
> ATTENTION : si vous modifiez Jeedom avec l'une de ces deux solutions,
> le apoyo peut refuser de vous aider.

-   **&gt;\_sistema** : Permet d'accéder para une interface
    d'administration système. C'es une sorte de console shell dans
    laquelle vous pouvez lancer las órdenes les plus utiles, notamment
    pour obtenir des informations sur le système.

-   **Editeur de fichiers** : Permet d'accéder aux différents fichiers du système
    d'exploitation et de les éditer ou supprimer ou d'en créer.

-   **Base de données** : Administration / Lancer : Permet d'accéder para la base de données
    de Jeedom. Usted puede alors lancer des commandes dans le champs
    du haut.
    Vérification / Lancer : Permet de lancer une vérification sur la base de données
    de Jeedom et de corriger si nécessaire les erreurs

    Deux paramètres s'affichent, en dessous, pour information :

    -   **Utilisateur** : apellido de l'utilisateur utilisé par Jeedom dans
        la base de données,

    -   **Contraseña** : mot de passe d'acceso para la base de données
        utilisé par Jeedom.
