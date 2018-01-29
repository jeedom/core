descripción
===========

Esta página reúne en una sola página los diferentes
elementos configurados en su Jeedom. También da acceso a
funciones de organización de los equipos y controles, su
configuración avanzada, así como opciones de configuración
pantalla.

Esta página es accesible desde Herramientas → ** ** Resumen de automatización.

el Top
------------------

En la parte superior, encontramos: \ * ** ** número de objetos: Número
objetos totales configurados en nuestra Jeedom, contando los elementos
Inactivo. \ * ** ** Número de dispositivos: Lo mismo para el equipo. \ *
**** Número de órdenes: Lo mismo para los pedidos. \ *** ** No:
Marque esta casilla si desea que los elementos inactivos están bien
que aparece en esta página. \ * ** ** Buscar: Buscar una
tema en particular. Este puede ser el nombre de un dispositivo, un control
o el nombre del plugin mediante el cual se creó el equipo.

Los cuadros de objetos
----------------

A continuación nos encontramos con un marco de objeto. En cada trama, hay
lista de equipo (en azul) que tienen un objeto padre. la
** ** No primer cuadro representa un equipo que no tiene
padre afectado. Para cada objeto, junto a su redacción, tres botones
están disponibles. De izquierda a derecha :

-   El primero se utiliza para abrir la página de configuración del objeto en una
    nueva pestaña,

-   el segundo proporciona alguna información sobre el tema,

-   el último para mostrar u ocultar la lista de equipos
    asignado a la misma.

> **Tip**
>
> El color de fondo de los objetos depende marcos en el color seleccionado en
> Configuración del objeto.

> **Tip**
>
> Haga clic / depositar en el equipo, puede cambiar su
> Orden o incluso asignar a otro objeto. Es a partir de la orden
> Establecido en esta página, se calcula la pantalla del salpicadero.

Los equipos
---------------

En cada dispositivo incluir:

-   ** ** Una casilla de verificación para seleccionar el equipo (se puede
    seleccionar varios). Si se selecciona un dispositivo
    Tiene botones de acción que aparecen en la parte superior izquierda
    **** borrar, hacer visible** **/** ** invisible,
    **activo**/** ** equipo seleccionado inactivo.

-   ** ** El nombre del equipo.

-   ** ** Los tipos de equipos: Plugin Nombre cual
    pertenece.

-   ** ** Inactivo (cruz pequeña) significa que el equipo está inactivo
    (Si no está allí, el equipo está activo).

-   **Ocultos** (ojo cruzado): significa que el equipo es invisible
    (Si no lo hay, el dispositivo es visible).

-   ** ** URL (cuadrado con una flecha) Se abre en una
    nueva pestaña la página de configuración del equipo.

-   ** ** Configuración avanzada (rueda dentada): Se abre la
    ventana de configuración avanzada de los equipos.

-   ** ** Lista de comandos (flecha): para desplegar la lista
    comandos (fondo naranja).

Si se despliegue la lista de comandos, cada bloque es de color naranja
un comando de su equipo (un nuevo clic en la flecha pequeña
Equipo ocultará).

Si hace doble clic en el control o haga clic en la pequeña
rueda dentada que se abre la ventana de configuración.

La configuración avanzada de los equipos
=====================================

> **Tip**
>
> Es posible el acceso (si el plugin soporta) directamente
> Ventana de la página de configuración del equipo
> Haga clic en el botón Configuración avanzada

**La ventana de configuración de equipos avanzados permite que el**
Editar. En primer lugar, arriba a la derecha, algunos botones
disponibles:

-   ** ** Enlaces: Muestra enlaces a equipos con
    Objetos, comandos, guiones, las variables, las interacciones ... formulario
    gráfico (en esto, hacer doble clic en un elemento que se llevará a
    su configuración).

-   ** ** Conectarse: Muestra los eventos del equipo en cuestión.

-   ** ** Información: muestra las propiedades sin procesar de los equipos.

-   ** ** Guardar: Guarda los cambios realizados
    en el equipo.

-   ** ** Eliminar: Eliminar el equipo.

ficha información
-------------------

El ** ** ficha Información contiene información general
equipos y sus controles:

-   ** ** ID: identificador único en la base de datos Jeedom.

-   ** ** Nombre: Nombre del equipo.

-   ** ** Logical ID: identificador lógico Equipo (puede
    estar vacío).

-   ** ** Artículo ID: Steam ID del objeto padre (mayo
    estar vacío).

-   ** ** Fecha de Inicio: Fecha de creación del equipo.

-   ** ** Activar: Marque la casilla para activar el equipo (no olvidar
    guardar).

-   Visible ** ** Consulte a hacer visible el equipo (sin
    se olvide de guardar).

-   ** ** Tipo: Name Complemento por la que fue creada.

-   ** ** intento fallido: Número de intentos de comunicación
    consecutiva con el equipo que ha fallado.

-   **Fecha de la última comunicación**: Fecha de la última
    equipos de comunicaciones.

-   ** ** Última actualización: Fecha de la última llamada
    con el equipo.

A continuación encontrará una tabla con la lista de comandos
equipo con, para cada uno, un enlace a su configuración.

ficha pantalla
----------------

En el ** ** pestaña de visualización, puede configurar algunos
comportamiento de presentación del azulejo en el salpicadero, las vistas, la
diseño, así como móvil.

### Reproductor

-   ** ** visible: Compruebe para hacer visible el equipo.

-   ** ** Mostrar Nombre: Marque la casilla para mostrar el nombre de
    equipos en el azulejo.

-   ** ** nombre Listados de objeto: Marque la casilla para mostrar el nombre
    el objeto principal del equipo al lado de la baldosa.

-   ** ** Color del fondo: seleccione la casilla para mantener el color de fondo
    predeterminado (dependiendo de la categoría ** ** de su equipo, consulte
    **Administración → Configuración → Colores**). Si desactiva esta
    caja, se puede elegir un color diferente. También puede
    marcar una casilla **nueva** Transparente para que el
    fondo transparente.

-   ** ** Opacidad: Opacidad del color de fondo de la baldosa.

-   ** ** El color del texto: Marque la casilla para mantener el color de
    Texto por defecto.

-   ** ** fronteras: Marque la casilla para mantener el borde predeterminado.
    De lo contrario, usted tiene que poner el código CSS, `propiedad border` (por ejemplo,
    `Dashed` 3px para el azul borde discontinuo de 3px azul).

-   ** ** fronteras de redondeo (en píxeles): Marque la casilla para mantener
    el redondeo por defecto. De lo contrario, usted tiene que poner el código CSS, propiedad
    `Border-radius` (eg` 10px`)

### Los parámetros opcionales en el azulejo

A continuación, hay parámetros opcionales que pueden mostrar
puede solicitar al equipo. Estos ajustes consisten en un nombre y
de un valor. Simplemente haga clic en ** ** Añadir a aplicar
de nuevo. Para el equipo, sólo el estilo de valor ** ** es
cuando se usa, se usa para insertar el código CSS en el equipo
pregunta.

> **Tip**
>
> No se olvide de guardar después de la modificación.

ficha de presentación
------------------

Esta sección le permite elegir entre estándar disponibles
comandos (lado a lado en el widget) o en modo de mesa. No hay
nada ajustado en el modo por defecto. Las opciones disponibles en el modo de
** ** Tabla:

-   ** ** Número de líneas

-   ** ** El número de columnas

-   ** ** Centro de las cajas: Seleccione la casilla de verificación para el centro
    Los comandos de las cajas.

-   **cajas de General Estilo (CSS)**: Conjunto estilo
    CSS general.

-   **Tabla de estilo (CSS)**: Establecer el estilo de
    tabla sólo.

Debajo de cada cuadro, la configuración detallada ** ** le permite
esto:

-   ** ** Cuadro de texto: Añadir texto en la parte superior de la orden (o
    solo, si no hay un control en el cuadro).

-   **cuadro de estilo (CSS)**: Cambiar el estilo específico CSS
    cuadro (ver sobrescribe y sustituye general CSS
    cajas).

> **Tip**
>
> En una celda de la tabla, si usted quiere pedir uno de cada 2
> Por debajo de la otra, no se olvide de añadir un retorno a
> Línea después de la primera avanzada ** ** en la configuración de la misma.

pestaña de alertas
--------------

Esta ficha le permite tener la información sobre la batería
equipos y establecer alertas sobre él. He aquí los
tipos de información se pueden encontrar:

-   ** ** Tipo de batería

-   **Última retroalimentación de la información**

-   ** **, el nivel remanente (si, por supuesto, su equipo funciona
    en la pila).

A continuación, también se pueden definir los umbrales de alerta específicos
la batería de este equipo. Si deja las cajas vacías, esos son
se aplicarán los umbrales predeterminados.

También puede manejar el tiempo de espera, en minutos, de los equipos. por
por ejemplo, 30 indica jeedom si el equipo no ha establecido
durante 30 minutos, por lo que debe ser puesto en estado de alerta.

> **Tip**
>
> Configuración general están en Administración → Configuración → ** ** Registros
> (o Instalaciones ** **)

ficha comentario
------------------

Le permite escribir un comentario sobre el equipo (fecha
cambiar la batería, por ejemplo).

configuración avanzada de un control
====================================

En primer lugar, arriba a la derecha, algunos botones disponibles:

-   ** ** Prueba: Para probar el comando.

-   ** ** Enlaces: Muestra enlaces a equipos con
    objetos, comandos, guiones, las variables, la interacción .... bajo
    forma gráfica.

-   ** ** Conectarse: Mostrar eventos del equipo en cuestión.

-   ** ** Información: Muestra las propiedades primas de los equipos.

-   Aplicar \ *: le permite aplicar la misma configuración en
    varios comandos.

-   ** ** Guardar: Guarda los cambios realizados en
    equipo

> **Tip**
>
> En un gráfico, haga doble clic en un elemento que se llevará a su
> Configuración.

> **Nota**
>
> Dependiendo del tipo de mando, información / acciones muestran
> Puede cambiar.

ficha información
-------------------

El ** ** ficha Información contiene información general sobre la
comando:

-   ** ** ID: identificador único en la base de datos.

-   ** ** ID lógico: identificador lógico del control (mayo
    estar vacío).

-   ** ** Nombre: Nombre del comando.

-   ** ** Tipo: Escriba el comando (acción o Info).

-   ** ** subtipo: control de subtipo (binario, digital ...).

-   ** ** URL directa: Proporciona la URL para acceder al equipo. (haga clic
    derecho, copiar dirección del enlace) URL se ejecutará el comando de
    **** Compartir y devolver información para obtener información** **.

-   ** ** unidad: Unidad de mando.

-   **Comando desencadenar una actualización** Dar el identificador de una
    Otro comando que, si los otros cambios de mando, obligará a la
    actualizar el orden que se muestra.

-   ** ** visible: Compruebe para el fin de ser visible.

-   ** ** Siga la línea de tiempo: Marque esta casilla para hacer que este
    con el fin de ser visible en la línea de tiempo cuando se utiliza.

-   **Prohibir en las interacciones automáticas** prohibido
    interacciones automáticas en este comando

-   ** ** icono: Cambiar el icono de la orden.

También tiene otros tres botones de color naranja a continuación:

-   **Este comando reemplaza el ID**: Para reemplazar un ID
    control por parte del comando en cuestión. Es útil si se ha eliminado una
    Jeedom equipos y tiene secuencias de comandos que utilizan
    comandos de la misma.

-   **Este comando reemplaza el comando** Reemplaza una orden
    el comando actual.

-   **Cambie este comando por comando** El revés reemplaza
    el orden de otro comando.

> **Nota**
>
> Este tipo de acción reemplaza pedidos en cualquier parte Jeedom
> (Escenario, interacción, control, equipo ....)

A continuación encontrará una lista de los diferentes equipos,
comandos, scripts o interacciones utilizando este comando. una
clic que le permite ir directamente a su configuración
respectivamente.

pestaña de configuración
--------------------

### Para un tipo de información de control:

-   **Calcular y redondeada**

    -   **Fórmula (\ #value \ # para el valor)**: Permite
        realizar una operación sobre el valor de la orden antes
        Jeedom tratamiento, por ejemplo, el valor `# # - 0.2` a replegarse
        0,2 (offset en un sensor de temperatura).

    -   **redondeado (decimal)**: Permite redondeo la
        valor del control (ejemplo: para 2 tranformer
        16.643345 en 16.64).

-   ** ** Tipo genérico: Establece el tipo genérico de
    comando (Jeedom tratando de encontrarse a sí misma en el modo automático).
    Esta información es utilizada por la aplicación móvil.

-   **Acción en el valor, si**: Para hacer clases de
    las mini escenarios. Puede, por ejemplo, que si el valor es
    más del 50 por 3 minutos, así que hay que dicha acción. lo
    permite, por ejemplo, para apagar una luz X minutos después de
    que estará encendida.

-   **Historia**

    -   Historizar ** ** Marque la casilla para los valores de este
        mando está en el sistema. (Ver ** ** Análisis → Historia)

    -   **modo de suavizado**: **modo de suavizado**o** ** archivado
        le permite elegir cómo archivar los datos. Por defecto,
        es un promedio ** **. También es posible elegir el
        **** Máximo, mínimo** **** o cualquier**. ** ** no permite
        Jeedom decir que no debe hacer que este archivo
        comando (tanto en el primer período de 5 minutos con el
        La tarea de archivar). Esto es peligroso porque Jeedom
        conserva todo: que va a ser mucho más
        retenido de datos.

    -   **la historia más antigua de purga si**: contar
        Jeedom eliminar todos los datos mayores de
        período. Puede ser útil para mantener sin
        datos si no es necesario y por lo tanto limitar la cantidad
        información registrada por Jeedom.

-   ** ** Los valores de gestión

    -   ** ** Valor prohibida: Si el comando tiene uno de estos valores,
        Jeedom no sabía antes de aplicar.

    -   ** ** valor de retorno de estado: permite volver al comando
        este valor después de un tiempo.

    -   Tiempo para volver a: **Tiempo de estado (min)** regreso
        valor anterior.

-   ** ** Otros

    -   ** ** Gestión repitiendo valores: En automática si el
        el control pasa de nuevo 2 veces el mismo valor en una fila, a continuación, Jeedom
        no tienen en cuenta la 2ª ascensión (evita la activación
        varias veces al escenario, a menos que el orden es
        binario). Puede forzar la repetición del valor o
        prohibición total.

    -   ** ** Enviar URL: Permite agregar una dirección URL para llamar en caso de
        actualizar la orden. Puede utilizar etiquetas
        siguiente: `` valor # # para el valor del comando, `` # # cmd_name
        para el nombre del comando, `` # # cmd_id para el identificador único
        mando, `` # # humanname para el nombre completo del comando
        (Por ejemplo, `# [baño] [Hydrometrie] [humedad] #`)

### Para un comando de acción:

-   ** ** Tipo genérico: Establece el tipo genérico de
    comando (Jeedom tratando de encontrarse a sí misma en el modo automático).
    Esta información es utilizada por la aplicación móvil.

-   ** ** Confirmar Acción: Marque esta casilla para solicitar que Jeedom
    una confirmación cuando se inicia la acción desde la interfaz
    este comando.

-   ** ** Código de acceso: Permite definir un código que solicitará Jeedom
    cuando la acción se inicia desde la interfaz de este comando.

-   **Acción antes de ejecutar el comando** Añade
    ** ** comandos antes de cada ejecución de la orden.

-   **Acción después de la ejecución de la orden** Añade
    ** ** comandos después de cada ejecución de la orden.

pestaña de alertas
--------------

Establece un nivel de alerta (**advertencia**o** ** riesgo) en
dependiendo de ciertas condiciones. Por ejemplo, si el valor `> 8` de 30
minutos, mientras que el equipo puede ir en alerta ** ** advertencia.

> **Nota**
>
> En las **Administración → Configuración → Registro**, consulta
> Configurar un tipo de puesto de mando que permite Jeedom
> Se notifica cuando se alcanza el umbral de aviso o seguro.

ficha pantalla
----------------

En esta parte agarre, usted será capaz de configurar un determinado comportamiento
widget de pantalla en el salpicadero, vistas, diseño y
móvil.

-   ** ** Widget: Elija widget en dekstop o móvil (en
    en cuenta que se necesita el plugin widget y usted también puede hacerlo
    de los mismos).

-   Visible ** **: Hora de hacer visible el comando.

-   ** ** Mostrar Nombre: Hora de hacer visible el nombre de la
    ordenar, dependiendo del contexto.

-   **Muestra el nombre y el icono**: Hora de hacer visible el icono
    además del nombre del comando.

-   **Envuelva forzado por widget de** ** El registro antes
    Reproductor **o** ** después de widget para añadir un salto de línea
    antes o después el widget (por ejemplo, para forzar una pantalla
    columna de los controles del equipo en lugar de líneas
    por defecto)

A continuación, hay parámetros opcionales que pueden mostrar
puede ir al widget. Estos parámetros dependen del widget en cuestión
por lo que tiene que ver su cotización en el mercado para conocerlos.

> **Tip**
>
> No se olvide de guardar después de la modificación.

ficha código
-----------

Cambia el código sólo Widget para el comando actual.

> **Nota**
>
> Si desea cambiar el código recuerde revisar la caja
> **Habilitar la personalización el widget**
