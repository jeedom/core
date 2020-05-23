Aquí es donde se encuentran la mayoría de los parámetros de configuración.
Aunque muchos, están preconfigurados por defecto.

La página es accesible por **Administración → Configuración**.

General 
=======

En esta pestaña encontramos información general sobre Jeedom :

-   **Nombre de tu Jeedom** : Identifica tu Jeedom,
    especialmente en el mercado. Se puede reutilizar en escenarios
    o identificar una copia de seguridad.

-   **Sistema** : Tipo de hardware en el que está instalado el sistema donde
    tu Jeedom está girando.

-   **Clave de instalación** : Llave de hardware de su Jeedom en
    el mercado. Si su Jeedom no aparece en la lista de su
    Jeedom en el mercado, es recomendable hacer clic en el botón
    **Restablecer**.

-   **Lengua** : Lenguaje usado en tu Jeedom.

-   **Generar traducciones** : Generar traducciones,
    tenga cuidado, esto puede ralentizar su sistema. Opción más útil
    para desarrolladores.

-   **Duración de las sesiones (hora)** : vida de sesiones
    PHP, no se recomienda tocar este parámetro.

-   **Fecha y hora** : Elige tu zona horaria. Usted puede
    haga clic en **Forzar sincronización de tiempo** para restaurar
    se muestra un mal momento en la esquina superior derecha.

-   **Servidor horario opcional** : Indica qué servidor horario debe
    ser usado si haces clic **Sincronización forzada de
    tiempo**. (ser reservado para expertos)

-   **Omitir verificación de tiempo** : le dice a Jeedom que no
    verificar si el tiempo es consistente entre sí mismo y el sistema encendido
    que da vuelta. Puede ser útil, por ejemplo, si no se está conectando
    no Jeedom a Internet y que no tiene batería PSTN en el
    material utilizado.

API 
===

Aquí está la lista de las diferentes claves API disponibles en
tu libertad. Core tiene dos claves API :

-   un general : tanto como sea posible, evite usarlo,

-   y otro para profesionales : utilizado para la gestión
    del parque. Puede estar vacio.

-   Luego encontrará una clave API por complemento que la necesita.

Para cada clave API de complemento, así como para HTTP, JsonRPC y API
TTS, puedes definir su alcance :

-   **Discapacitado** : La clave API no se puede usar,

-   **IP blanca** : solo se autoriza una lista de IP (ver
    Administración → Configuración → Redes),

-   **Localhost** : solo solicitudes del sistema en el que está
    Jeedom instalado están permitidos,

-   **Activado** : sin restricciones, cualquier sistema con acceso
    su Jeedom podrá acceder a esta API.

&gt;\_OS / DB 
===========

Dos partes reservadas para expertos están presentes en esta pestaña.

> **Importante**
>
> ATENCIÓN : Si modifica Jeedom con una de estas dos soluciones,
> el apoyo puede negarse a ayudarlo.

-   **&gt;\_SYSTEM** : Permite el acceso a una interfaz
    administración del sistema. Es una especie de consola de shell en
    que puede ejecutar los comandos más útiles, incluidos
    para obtener información sobre el sistema.

-   **Base de datos** : Permite el acceso a la base de datos
    de Jeedom. Luego puede ejecutar comandos en el campo
    desde lo alto. A continuación se muestran dos parámetros para obtener información :

    -   **Usuario** : Nombre de usuario utilizado por Jeedom en
        la base de datos,

    -   **Contraseña** : contraseña de acceso a la base de datos
        utilizado por Jeedom.

Seguridad 
========

LDAP 
----

-   **Habilitar autenticación LDAP** : habilitar autenticación para
    a través de un AD (LDAP)

-   **Anfitrión** : servidor que aloja el AD

-   **Dominio** : dominio de su AD

-   **DN base** : DN base de su AD

-   **Nombre del usuario** : nombre de usuario para que Jeedom
    conectarse a AD

-   **Contraseña** : contraseña para que Jeedom se conecte a AD

-   **Campos de busqueda de usuario** : campos de búsqueda de
    inicio de sesión de usuario. Por lo general, uid para LDAP, samaccountname para
    Windows AD

-   **Filtro (opcional)** : filtro en el AD (para administrar
    grupos por ejemplo)

-   **Permitir REMOTO\_USER** : Active REMOTE\_USER (usado en SSO
    Por ejemplo)

Acceder 
---------

-   **Número de fallas toleradas** : establece el número de intentos
    permitido antes de prohibir la IP

-   **Tiempo máximo entre fallas (en segundos)** : tiempo maximo
    para que 2 intentos se consideren sucesivos

-   **Duración del destierro (en segundos), -1 por infinito** : tiempo de
    Prohibición de IP

-   **IP "blanco"** : lista de IP que nunca se pueden prohibir

-   **Eliminar IP prohibidas** : Borrar la lista de IP
    actualmente prohibido

La lista de IP prohibidas se encuentra al final de esta página. Encontraras alli
IP, fecha de prohibición y fecha de finalización de prohibición
programado.

Redes 
=======

Es absolutamente necesario configurar correctamente esta parte importante de
Jeedom, de lo contrario, muchos complementos pueden no funcionar. Él
Es posible acceder a Jeedom de dos maneras diferentes : L'**acceso
interne** (de la misma red local que Jeedom) y l'**acceso
externe** (de otra red, especialmente de Internet).

> **Importante**
>
> Esta parte está ahí para explicarle a Jeedom su entorno :
> cambiar el puerto o la IP en esta pestaña no cambiará el
> Puerto Jeedom o IP en realidad. Para hacer esto, debe iniciar sesión
> SSH y edite el archivo / etc / network / interfaces para la IP y
> etc / apache2 / sites-available / archivos predeterminados y
> etc / apache2 / sites-available / default\_ssl (para HTTPS).Sin embargo, en
> Si su Jeedom se maneja mal, el equipo de Jeedom no
> puede ser considerado responsable y puede rechazar cualquier solicitud de
> apoyo.

-   **Acceso interno** : información para unirse a Jeedom desde un
    mismo equipo de red que Jeedom (LAN)

    -   **OK / NOK** : indica si la configuración de red interna es
        correcte

    -   **Protocolo** : el protocolo a usar, a menudo HTTP

    -   **URL o dirección IP** : Jeedom IP para entrar

    -   **Puerto** : el puerto de la interfaz web de Jeedom, generalmente 80.
        Tenga en cuenta que cambiar el puerto aquí no cambia el puerto real de
        Jeedom que seguirá siendo el mismo

    -   **Complementar** : el fragmento de URL adicional (ejemplo
        : / jeedom) para acceder a Jeedom.

-   **Acceso externo** : información para llegar a Jeedom desde afuera
    red local. Para completar solo si no está utilizando DNS
    Jeedom

    -   **OK / NOK** : indica si la configuración de red externa es
        correcte

    -   **Protocolo** : protocolo utilizado para acceso al exterior

    -   **URL o dirección IP** : IP externa, si es fija. por lo demás,
        proporcione la URL que apunta a la dirección IP externa de su red.

    -   **Complementar** : el fragmento de URL adicional (ejemplo
        : / jeedom) para acceder a Jeedom.

> **Punta**
>
> Si está en HTTPS, el puerto es 443 (por defecto) y en HTTP el
> el puerto es 80 (predeterminado). Para usar HTTPS desde afuera,
> un complemento de letsencrypt ya está disponible en el mercado.

> **Punta**
>
> Para saber si necesita establecer un valor en el campo
> **Complementar**, mira, cuando inicias sesión en Jeedom en
> su navegador de Internet, si necesita agregar / jeedom (u otro
> cosa) después de la IP.

-   **Gestión avanzada** : Esta parte puede no aparecer, en
    dependiendo de la compatibilidad con su hardware. Encontraras alli
    la lista de sus interfaces de red. Puedes decirle a Jeedom
    no monitorear la red haciendo clic en **desactivar el
    gestión de red por Jeedom** (comprobar si Jeedom no está conectado a
    sin red). También puede especificar el rango de ip local en la forma 192.168.1.* (para ser utilizado solo en instalaciones de tipo acoplable)

-   **Mercado proxy** : permite el acceso remoto a su Jeedom sin tener
    necesita un DNS, una IP fija o para abrir los puertos de su caja
    Internet

    -   **Usando Jeedom DNS** : activa Jeedom DNS (atención
        esto requiere al menos un paquete de servicio)

    -   **Estado DNS** : Estado HTTP HTTP

    -   **Administración** : permite detener y reiniciar el servicio DNS

> **Importante**
>
> Si no puede hacer que funcione Jeedom DNS, consulte el
> configuración del cortafuegos y filtro parental de su caja de Internet
> (en livebox necesita, por ejemplo, el firewall en medio).

Colores 
========

La coloración de los widgets se realiza según la categoría a
qué equipo pertenece. Entre las categorías encontramos el
calefacción, seguridad, energía, luz, automatización, multimedia, otros

Para cada categoría, podemos diferenciar los colores de la versión
versión de escritorio y móvil. Entonces podemos cambiar :

-   el color de fondo de los widgets,

-   el color del comando cuando el widget es del tipo gradual (para
    ejemplo luces, persianas, temperaturas).

Al hacer clic en el color, se abre una ventana que le permite elegir su
color. La cruz al lado del color vuelve al parámetro
por defecto.

En la parte superior de la página, también puede configurar la transparencia de
widgets a nivel mundial (este será el valor predeterminado. El es
entonces es posible modificar este valor widget por widget). Para no
no ponga transparencia, deje 1.0 .

> **Punta**
>
> No olvide guardar después de cualquier modificación.

Comandos 
=========

Se pueden registrar muchos pedidos. Entonces en
Análisis → Historia, obtienes gráficos que representan sus
utilizar. Esta pestaña le permite establecer parámetros globales para
historial de pedidos.

Histórico 
----------

-   **Ver estadísticas de widgets** : Muestra
    estadísticas del widget. El widget debe ser
    compatible, que es el caso para la mayoría. También es necesario que el
    comando ya sea digital.

-   **Período de cálculo para min, max, promedio (en horas)** : Período
    cálculo de estadísticas (24h por defecto). No es possible
    poner menos de una hora.

-   **Periodo de cálculo de la tendencia (en horas)** : Periodo de
    cálculo de tendencia (2h por defecto). No es posible
    poner menos de una hora.

-   **Retraso antes de archivar (en horas)** : Indica el retraso antes
    Jeedom no archiva datos (24 horas por defecto). Es decir, el
    los datos históricos deben tener más de 24 horas para ser archivados
    (como recordatorio, el archivo será promedio o tomará el máximo
    o el mínimo de los datos durante un período que corresponde a la
    tamaño del paquete).

-   **Archivar por paquete desde (en horas)** : Este parámetro da
    Precisamente el tamaño de los paquetes (1 hora por defecto). Significa por
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

> **Nota**
>
> El primer parámetro **Ver estadísticas de widgets** est
> posible pero deshabilitado por defecto porque alarga significativamente el
> tiempo de visualización del tablero. Si activa esta opción, por ejemplo
> por defecto, Jeedom se basa en datos de las últimas 24 horas para
> calcular estas estadísticas. El método de cálculo de tendencia se basa
> en el cálculo de mínimos cuadrados (ver
> [aquí](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> para el detalle).

Empuje 
----

**URL de inserción global** : permite agregar una URL para llamar en caso de
orden de actualización. Puedes usar las siguientes etiquetas :
**\#value\#** por el valor del pedido, **\#cmd\_name\#** para el
nombre del comando, **\#cmd\_id\#** para el identificador único de la
commande, **\#humanname\#** para el nombre completo de la orden (ej :
\#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#), ``#eq_name#`para el nombre del equipo

Cubierta 
=====

Permite monitorear y actuar en el caché Jeedom :

-   **Estadística** : Número de objetos actualmente en caché

-   **Limpiar la tapa** : Forzar la eliminación de objetos que no son
    mas util. Jeedom hace esto automáticamente todas las noches.

-   **Borrar todos los datos en caché** : Vacíe la tapa completamente.
    Tenga en cuenta que esto puede causar pérdida de datos !

-   **Tiempo de pausa para encuestas largas** : Con que frecuencia
    Jeedom comprueba si hay eventos pendientes para los clientes
    (interfaz web, aplicación móvil). Cuanto más corto esta vez, más
    la interfaz se actualizará rápidamente, a cambio esto
    usa más recursos y, por lo tanto, puede ralentizar a Jeedom.

Interacciones 
============

Esta pestaña le permite establecer parámetros globales relacionados con
interacciones que encontrarás en Herramientas → Interacciones.

> **Punta**
>
> Para activar el registro de interacción, vaya a la pestaña
> Administración → Configuración → Registros, luego marque **Depurar** en la lista
> de abajo. ATENCIÓN : los registros serán muy detallados !

General 
-------

Aquí tienes tres parámetros :

-   **Sensibilidad** : Hay 4 niveles de correspondencia (sensibilidad
    varía de 1 (coincide exactamente) a 99)

    -   por 1 palabra : el nivel de correspondencia para las interacciones en
        una palabra

    -   2 palabras : el nivel de correspondencia para las interacciones en
        dos palabras

    -   3 palabras : el nivel de correspondencia para las interacciones en
        tres palabras

    -   más de 3 palabras : el nivel de correspondencia para las interacciones
        mas de tres palabras

-   **No responda si no se entiende la interacción** : por defecto
    Jeedom responde &quot;No entendí&quot; si no hay interacción
    no coincide. Es posible desactivar esta función para
    que Jeedom no responde nada. Marque la casilla para desactivar
    la respuesta.

-   **Regex de exclusión general para interacciones** : permite
    definir una expresión regular que, si corresponde a una interacción,
    eliminará automáticamente esta oración de la generación (reservado
    a expertos). Para obtener más información, consulte las explicaciones en
    capítulo **Exclusión de expresiones regulares** documentación sobre
    interacciones.

Interacción automática, contextual y advertencia 
-----------------------------------------------------

-   La **interacciones automáticas** permitir que Jeedom intente
    entender una solicitud de interacción incluso si no hay ninguna
    de definido. Luego buscará un objeto y / o nombre del equipo
    y / o para tratar de responder lo mejor posible.

-   La **interacciones contextuales** permitirte encadenar
    múltiples solicitudes sin repetir todo, por ejemplo :

    -   *Jeedom manteniendo el contexto :*

        -   *Vosotras* : Cuanto esta el en el cuarto ?

        -   *Jeedom* : Temperatura 25.2 ° C

        -   *Vosotras* : y en la sala de estar ?

        -   *Jeedom* : Temperatura 27.2 ° C

    -   *Haz dos preguntas en una :*

        -   *Vosotras* : ¿Cómo es en el dormitorio y en la sala de estar? ?

        -   *Jeedom* : Temperatura 23.6 ° C, temperatura 27.2 ° C

-   Interacciones de tipo **Avísame** preguntemos
    Jeedom para notificarle si un pedido excede / desciende o vale la pena
    cierto valor.

    -   *Vosotras* : Notificarme si la temperatura de la sala supera los 25 ° C ?

    -   *Jeedom* : OK (* Tan pronto como la temperatura de la sala supere los 25 ° C,
        Jeedom te dirá, solo una vez*)

> **Nota**
>
> Por defecto, Jeedom te responderá por el mismo canal que el que tú
> solía pedirle que te notificara. Si no encuentra uno
> no, usará el comando predeterminado especificado en este
> pestaña : **Comando de retorno predeterminado**.

Aquí están las diferentes opciones disponibles :

-   **Habilitar interacciones automáticas** : Marque para activar
    interacciones automáticas.

-   **Habilitar respuestas contextuales** : Marque para activar
    interacciones contextuales.

-   **Respuesta contextual prioritaria si la oración comienza con** : Si
    la oración comienza con la palabra que ingresas aquí, Jeedom lo hará
    luego priorice una respuesta contextual (puede poner
    varias palabras separadas por **;** ).

-   **Cortar una interacción a la mitad si contiene** : Lo mismo para
    El desglose de una interacción que contiene varias preguntas. Vosotras
    da aquí las palabras que separan las diferentes preguntas.

-   **Activa las interacciones "Notificarme""** : Marque para activar
    Interacciones de tipo **Avísame**.

-   **Respuesta &quot;Dime&quot; si la oración comienza con** : Si la
    la oración comienza con esta (s) palabra (s) y luego Jeedom intentará hacer una
    tipo de interacción **Avísame** (puedes poner multiples
    palabras separadas por **;** ).

-   **Comando de retorno predeterminado** : Comando de retorno predeterminado
    para una interacción tipo **Avísame** (utilizado, en particular,
    si ha programado la alerta a través de la interfaz móvil)

-   **Sinónimo de objetos** : Lista de sinónimos para objetos
    (ex : rdc|planta baja|bajo tierra|bajo; sdb|Cuarto de baño).

-   **Sinónimo de equipamiento** : Lista de sinónimos para
    los equipos.

-   **Sinónimo de pedidos** : Lista de sinónimos para
    las órdenes.

-   **Sinónimo de resúmenes** : Lista de sinónimos para resúmenes.

-   **Sinónimo de comando de control deslizante máximo** : Sinónimo de poner un
    comando de tipo de control deslizante máximo (por ejemplo, se abre para abrir el obturador
    la habitación ⇒ 100% de persiana).

-   **Sinónimo de comando mínimo de control deslizante** : Sinónimo de poner un
    comando de tipo deslizador como mínimo (por ejemplo, se cierra para cerrar el obturador
    la sala ⇒ componente de sala al 0%).

Colores 
--------

Esta parte le permite definir los colores con los que Jeedom asociará
palabras rojo / azul / negro ... Para agregar un color :

-   Haga clic en el botón **+**, a la derecha,

-   Dale un nombre a tu color,

-   Elija el color asociado haciendo clic en el cuadro de la derecha.

Relaciones 
========

Configurar la generación y gestión de informes

-   **Tiempo de espera después de la generación de la página (en ms)** : Aviso
    esperando después de cargar el informe para tomar la &quot;foto&quot;, en
    cambiar si su informe está incompleto, por ejemplo.

-   **Limpiar informes anteriores de (días)** : Define el
    Número de días antes de eliminar un informe (los informes tardan
    un poco de espacio, así que ten cuidado de no poner demasiado
    conservación).

Vínculos 
=====

Configurar gráficos de enlace. Estos enlaces te permiten
ver, en forma de gráfico, las relaciones entre objetos,
equipos, objetos, etc.

-   **Profundidad para escenarios** : Se usa para definir cuándo
    mostrando un gráfico de enlaces de un escenario, el número
    Número máximo de elementos para mostrar (cuantos más elementos, mayor será
    el gráfico será lento de generar y cuanto más difícil será leer).

-   **Profundidad para objetos** : Lo mismo para los objetos.

-   **Profundidad para equipamiento** : Lo mismo para el equipo.

-   **Profundidad para controles** : Lo mismo para pedidos.

-   **Profundidad para variables** : Lo mismo para las variables.

-   **Parámetro de prerender** : Vamos a actuar sobre el diseño
    del gráfico.

-   **Parámetro de procesamiento** : ídem.

Resúmenes 
=======

Agregar resúmenes de objetos. Esta información se muestra
en la parte superior derecha, en la barra de menú de Jeedom, o al lado de
objetos :

-   **Clave** : Clave para el resumen, especialmente para no tocar.

-   **Apellido** : Nombre abstracto.

-   **Cálculo** : Método de cálculo, puede ser de tipo :

    -   **Suma** : suma los diferentes valores,

    -   **Promedio** : valores promedio,

    -   **Texto** : mostrar el valor literalmente (especialmente para aquellos
        de tipo de cadena).

-   **Icono** : Ícono de resumen.

-   **Unidad** : Unidad de resumen.

-   **Método de conteo** : Si cuenta datos binarios, entonces
    debe establecer este valor en binario, por ejemplo, si cuenta
    cantidad de luces encendidas pero solo tienes el valor de
    dimmer (0 a 100), entonces tienes que poner binario, como este Jeedom
    considere que si el valor es mayor que 1, entonces la lámpara
    está encendido.

-   **Mostrar si el valor es 0** : Marque esta casilla para mostrar el
    valor, incluso cuando es 0.

-   **Enlace a un virtual** : Comienza a crear pedidos virtuales
    teniendo por valor los del resumen.

-   **Eliminar resumen** : El último botón, en el extremo derecho, permite
    para eliminar el resumen de la línea.

Troncos 
====

Línea de tiempo 
--------

-   **Numero maximo de eventos** : Establece el número máximo en
    mostrar en la línea de tiempo.

-   **Eliminar todos los eventos** : Vaciar la línea de tiempo de
    todos sus eventos grabados.

Mensajes 
--------

-   **Agregue un mensaje a cada error en los registros** : si un complemento
    o Jeedom escribe un mensaje de error en un registro, agrega Jeedom
    automáticamente un mensaje en el centro de mensajes (al menos
    seguro que no te lo perderás).

-   **Acción sobre mensaje** : Le permite realizar una acción al agregar un mensaje al centro de mensajes. Tienes 2 etiquetas para estas acciones : 
        - #message# : mensaje en cuestión
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
    mensaje en el centro de mensajes si un dispositivo tiene su nivel de
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

Registro 
---

-   **Motor de registro** : Le permite cambiar el motor de registro para
    ejemplo, envíalos a un demonio syslog (d).

-   **Formato de registro** : Formato de registro a utilizar (Precaución : ça
    no afecta los registros de daemon).

-   **Número máximo de líneas en un archivo de registro** : Define el
    número máximo de líneas en un archivo de registro. Se recomienda
    no tocar este valor, porque un valor demasiado grande podría
    llenar el sistema de archivos y / o dejar a Jeedom incapaz
    para mostrar el registro.

-   **Nivel de registro predeterminado** : Cuando selecciona "Predeterminado",
    para el nivel de un registro en Jeedom, este es el que será
    luego usado.

A continuación encontrará una tabla para administrar finamente el
nivel de registro de los elementos esenciales de Jeedom, así como el de
plugins.

Comodidades 
===========

-   **Número de fallas antes de la desactivación del equipo** : Nombre
    falla de comunicación con el equipo antes de la desactivación de
    este (un mensaje te avisará si esto sucede).

-   **Umbrales de la batería** : Le permite administrar umbrales de alerta global
    en las baterías.

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
Jeedom, etc.

### Expediente 

Depósito utilizado para activar el envío de complementos por archivos.

### Github 

Depósito utilizado para conectar Jeedom a Github.

-   **Simbólico** : Token para acceder al depósito privado.

-   **Usuario u organización del repositorio principal de Jeedom** : Nom
    el usuario o la organización en github para el núcleo.

-   **Nombre del repositorio para el núcleo Jeedom** : Nombre del repositorio para core.

-   **Industria central de Jeedom** : Rama del repositorio central.

### Mercado 

Depósito utilizado para conectar Jeedom al mercado, es muy recomendable
para usar este repositorio. ATENCIÓN : cualquier solicitud de soporte puede ser
rechazado si utiliza un depósito diferente a este.

-   **Dirección** : Dirección del mercado.

-   **Nombre del usuario** : Su nombre de usuario en el mercado.

-   **Contraseña** : Tu contraseña de mercado.

-   **Nombre [nube de respaldo]** : Nombre de su copia de seguridad en la nube (la atención debe ser única para cada Jeedom en riesgo de que se estrelle entre ellos))

-   **[Copia de seguridad de la nube] Contraseña** : Contraseña de respaldo en la nube. IMPORTANTE no debes perderlo, no hay forma de recuperarlo. Sin ella no podrás restaurar tu Jeedom

-   **[Nube de respaldo] Frecuencia de respaldo completo** : Frecuencia de copia de seguridad en la nube completa. Una copia de seguridad completa es más larga que una incremental (que solo envía las diferencias). Se recomienda hacer 1 por mes

### Samba 

Deposite para enviar automáticamente una copia de seguridad de Jeedom a
una parte de Samba (ex : NAS Synology).

-   **\ [Copia de seguridad \] IP** : IP del servidor Samba.

-   **\ [Copia de seguridad \] Usuario** : Nombre de usuario para iniciar sesión
    (las conexiones anónimas no son posibles). Debe haber
    que el usuario tiene derechos de lectura y escritura en el
    directorio de destino.

-   **\ [Copia de seguridad \] Contraseña** : Contraseña de usuario.

-   **\ [Copia de seguridad \] Compartir** : Forma de compartir (ten cuidado
    detenerse en el nivel de compartir).

-   **\ [Copia de seguridad \] Ruta** : Ruta en compartir (para establecer
    relativo), debe existir.

> **Nota**
>
> Si la ruta a su carpeta de copia de seguridad samba es :
> \\\\ 192.168.0.1 \\ Copias de seguridad \\ Automatización del hogar \\ Jeedom Entonces IP = 192.168.0.1
> , Compartir = //192.168.0.1 / Copias de seguridad, Ruta = Domótica / Jeedom

> **Nota**
>
> Al validar el recurso compartido Samba, como se describió anteriormente,
> aparece una nueva forma de respaldo en la sección
> Administración → copias de seguridad de Jeedom. Al activarlo, Jeedom procederá
> cuando se envía automáticamente en la próxima copia de seguridad. Una prueba es
> posible realizando una copia de seguridad manual.

> **Importante**
>
> Es posible que deba instalar el paquete smbclient para
> obras de depósito.

> **Importante**
>
> El protocolo Samba tiene varias versiones, el v1 está comprometido a nivel 
> seguridad y en algunos NAS puede obligar al cliente a usar v2
> o v3 para conectar. Entonces, si tiene un error de negociación de protocolo
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE hay una buena posibilidad de que aparezca NAS
> la restricción esté en su lugar. Luego debe modificar el sistema operativo de su Jeedom
> el archivo / etc / samba / smb.conf y agregue estas dos líneas :
> protocolo max del cliente = SMB3
> protocolo min del cliente = SMB2
> El smbclient del lado de Jeedom usará v2 donde v3 y al poner SMB3 solo en ambos
> SMB3. Entonces, depende de usted adaptarse según las restricciones en el NAS u otro servidor Samba

> **Importante**
>
> Jeedom debería ser el único en escribir en esta carpeta y debería estar vacío
> de forma predeterminada (es decir, antes de configurar y enviar el
> primera copia de seguridad, la carpeta no debe contener ningún archivo o
> dossier).

### URL 

-   **URL central de Jeedom**

-   **URL de la versión principal de Jeedom**


