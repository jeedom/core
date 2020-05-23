Descripción 
===========

Esta página le permite reunir en una sola página los diferentes
elementos configurados en su Jeedom. También da acceso a
funciones de organización de equipos y controles, en su
configuración avanzada, así como posibilidades de configuración
d'affichage.

Esta página es accesible por **Herramientas → Resumen de domótica**.

La parte superior de la página 
------------------

En la parte superior de la página, encontramos : \* **Numero de objetos** : Nombre
total de objetos configurados en nuestro Jeedom, contando los elementos
Inactivo. \* **Numero de equipos** : Lo mismo para el equipo. \*
**Numero de ordenes** : Lo mismo para los pedidos. \* **Inactivo** :
Marque esta casilla si desea que los elementos inactivos estén bien
se muestra en esta página. \* **Buscar** : Busque un
elemento particular. Puede ser el nombre de un equipo, un pedido
o el nombre del complemento por el cual se creó el equipo.

También tiene un botón &quot;Historial de eliminaciones&quot; que le permitirá mostrar el historial de pedidos, equipos, objetos, vista, diseño, diseño 3d, escenario y usuario eliminado.

Marcos de objetos 
----------------

Debajo hay un cuadro por objeto. En cada cuadro, encontramos
la lista de equipos (en azul) que tienen este objeto como padre. la
primer cuadro **No** representa equipos que no tienen
padre afectado. Para cada objeto, junto a su etiqueta, tres botones
están disponibles. De izquierda a derecha :

-   El primero se utiliza para abrir la página de configuración de objetos en un
    nueva pestaña,

-   el segundo proporciona información sobre el objeto,

-   el último le permite mostrar u ocultar la lista de equipos
    atribuido a él.

> **Punta**
>
> El color de fondo de los marcos de los objetos depende del color elegido en
> configuración de objeto.

> **Punta**
>
> Al hacer clic / soltar en el equipo, puede cambiar su
> ordenar o incluso asignarlos a otro objeto. Es de orden
> estableció en esta página que se calcula la visualización del tablero.

Los equipos 
---------------

En cada equipo encontramos :

-   Una **casilla de verificación** para seleccionar el equipo (puedes
    seleccione múltiple). Si se selecciona al menos un dispositivo
    tienes botones de acción que aparecen en la esquina superior izquierda
    para **remove**, maquillaje **visible**/ /**invisible**,
    **bienes**/ /**Inactivo** equipo seleccionado.

-   La **apellido** equipo.

-   La **tipo** equipo : Identificador del complemento al que
    pertenece.

-   **Inactivo** (pequeña cruz) : Significa que el equipo está inactivo
    (si no está allí, el equipo está activo).

-   **Invisible** (ojo cruzado) : Significa que el equipo es invisible
    (si no está allí, el equipo es visible).

-   **Enlace externo** (cuadrado con flecha) : Vamos a abrir en un
    nueva pestaña la página de configuración del equipo.

-   **Configuración avanzada** (rueda dentada) : abre el
    ventana de configuración avanzada del equipo.

-   **Lista de comandos** (la flecha) : le permite expandir la lista de
    comandos (sobre fondo naranja).

Si expande la lista de comandos, cada bloque naranja corresponde a
un pedido de su equipo (un nuevo clic en la flecha pequeña
el equipo puede ocultarlos).

Si hace doble clic en el pedido o hace clic en el pequeño
rueda con muesca esto abrirá su ventana de configuración.

Configuración avanzada de equipos 
=====================================

> **Punta**
>
> Es posible acceder (si el complemento lo admite) directamente a
> esta ventana desde la página de configuración del equipo en
> haciendo clic en el botón de configuración avanzada

La ventana de **Configuración avanzada de equipos** permite el
Editar. Primero, arriba a la derecha, algunos botones
disponible :

-   **Vínculos** : Muestra los enlaces del equipo con el
    objetos, comandos, escenarios, variables, interacciones ... en la forma
    gráfico (en este caso, un doble clic en un elemento lo lleva a
    su configuración).

-   **Registro** : muestra los eventos del equipo en cuestión.

-   **Información** : muestra las propiedades en bruto del equipo.

-   **Registro** : Guarda las modificaciones realizadas
    en equipo.

-   **Remove** : Retirar equipo.

Pestaña de información 
-------------------

La pestaña **Información** contiene la información general de
el equipo y sus controles :

-   **Identificación** : Identificador único en la base de datos Jeedom.

-   **Apellido** : Nombre del equipo.

-   **ID lógica** : Identificador de equipo lógico (puede
    estar vacío).

-   **ID de objeto** : Identificador único del objeto padre (puede
    estar vacío).

-   **Fecha de creación** : Fecha de creación del equipo.

-   **Activar** : Marque la casilla para activar el equipo (no olvide
    ahorrar).

-   **Visible** : Marque la casilla para hacer visible el equipo (sin
    olvida guardar).

-   **Tipo** : Identificador del complemento por el cual fue creado.

-   **Intento fallido** : Número de intentos de comunicación
    consecutiva con equipo fallido.

-   **Fecha de la última comunicación** : Fecha de último
    equipo de comunicación.

-   **última actualización** : Fecha de la última comunicación
    con equipo.

-   **Etiquetas** : etiquetas de equipo, para ser separadas por ','. Permite en el tablero hacer filtros personalizados

A continuación encontrará una tabla con la lista de comandos para
el equipo con, para cada uno, un enlace a su configuración.

Ver pestaña 
----------------

En la pestaña **Viendo**, podrás configurar algunos
mostrar el comportamiento del mosaico en el tablero, las vistas, el
diseño tan bien como móvil.

### Reproductor 

-   **Visible** : Marque la casilla para hacer visible el equipo.

-   **Mostrar nombre** : Marque la casilla para mostrar el nombre de
    equipo en el azulejo.

-   **Mostrar nombre de objeto** : Marque la casilla para mostrar el nombre
    del objeto padre del equipo, al lado del azulejo.

-   **Color de fondo** : Marque la casilla para mantener el color de fondo
    por defecto (dependiendo de la **categoría** de su equipo, vea
    **Administración → Configuración → Colores**). Si desmarca esto
    caja, puedes elegir otro color. Tambien puedes
    marque una nueva casilla **Transparente** para hacer el
    fondo transparente.

-   **Opacidad** : Opacidad del color de fondo del mosaico.

-   **Color del texto** : Marque la casilla para mantener el color del
    texto predeterminado.

-   **Fronteras** : Marque la casilla para mantener el borde predeterminado.
    De lo contrario, debe poner el código CSS, propiedad `border` (ex :
    `3px blue punhed` para un borde punteado de 3px en azul).

-   **Bordes redondeados** (en px) : Marque la casilla para guardar
    el redondeo predeterminado. De lo contrario, debe poner el código CSS, propiedad
    `border-radius` (ex : ``10px``)

### Parámetros opcionales en el mosaico 

A continuación, encontramos parámetros de visualización opcionales que
puede aplicarse al equipo. Estos parámetros están compuestos de un nombre y
vale la pena. Solo haz clic en **Añadir** aplicar uno
de nuevo. Para equipos, solo el valor **estilo** es para el
momento utilizado, permite insertar código CSS en el equipo en
question.

> **Punta**
>
> No olvide guardar después de cualquier modificación.

Pestaña Diseño 
------------------

Esta parte le permite elegir entre la disposición estándar de
comandos (uno al lado del otro) o en modo tabla. No hay
nada que configurar en modo predeterminado. Aquí están las opciones disponibles en modo
**Mesa** :

-   **Numero de lineas**

-   **Numero de columnas**

-   **Centro en cajas** : Marque la casilla para centrar el
    comandos en las cajas.

-   **Estilo general de cuadros (CSS)** : Le permite definir el estilo
    general en código CSS.

-   **Estilo de tabla (CSS)** : Le permite definir el estilo de
    solo mesa.

A continuación para cada cuadro, el **configuración detallada** te permite
este :

-   **Cuadro de texto** : Agregue texto además del comando (o
    solo, si no hay orden en la caja).

-   **Estilo de caso (CSS)** : Cambiar el estilo CSS específico de la
    cuadro (cuidado, esto sobrescribe y reemplaza el CSS general
    cajas).

> **Punta**
>
> En un cuadro en la tabla, si desea poner 2 comandos uno en
> debajo del otro, no olvide agregar un retorno al
> línea después del estreno en el **Configuración avanzada** del mismo.

Pestaña Alertas 
--------------

Esta pestaña proporciona información sobre la batería de
el equipo y definir alertas en relación con él. He aquí los
tipos de información que se pueden encontrar :

-   **Tipo de batería**,

-   **Últimos comentarios**,

-   **Nivel restante**, (si por supuesto su equipo funciona
    en la batería).

A continuación, también puede definir umbrales de alerta específicos para
batería para este equipo. Si dejas las cajas vacías, esas son
los umbrales predeterminados que se aplicarán.

También puede administrar el tiempo de espera, en minutos, del equipo. por
Por ejemplo, 30 le dice a Jeedom que si el equipo no se ha comunicado
durante 30 minutos, luego debes ponerlo en alerta.

> **Punta**
>
> Los parámetros globales están en **Administración → Configuración → Registros**
> (o **Comodidades**)

Pestaña de comentarios 
------------------

Le permite escribir un comentario sobre el equipo (fecha de
cambiar la batería, por ejemplo).

Configuración avanzada de un pedido 
====================================

Primero, en la parte superior derecha, algunos botones disponibles :

-   **Prueba** : Se usa para probar el comando.

-   **Vínculos** : Muestra los enlaces del equipo con el
    objetos, comandos, escenarios, variables, interacciones ... bajo
    forma grafica.

-   **Registro** : Muestra los eventos del equipo en cuestión.

-   **Información** : Muestra las propiedades en bruto del equipo.

-   Aplicar a \* : Aplicar la misma configuración en
    pedidos múltiples.

-   **Registro** : Guardar los cambios realizados en
    equipo

> **Punta**
>
> En un gráfico, un doble clic en un elemento lo lleva a su
> configuración.

> **Nota**
>
> Dependiendo del tipo de orden, la información / acciones mostradas
> puede cambiar.

Pestaña de información 
-------------------

La pestaña **Información** contiene información general sobre el
orden :

-   **Identificación** : Identificador único en la base de datos.

-   **ID lógica** : Identificador lógico del comando (puede
    estar vacío).

-   **Apellido** : Nombre de la orden.

-   **Tipo** : Tipo de comando (acción o información).

-   **Subtipo** : Subtipo de comando (binario, digital, etc.)).

-   **URL directa** : Proporciona la URL para acceder a este equipo. (haga clic
    derecha, copie la dirección del enlace) La URL iniciará el comando para un
    **acción** y devolver la información para un **Información**.

-   **Unidad** : Unidad de control.

-   **Comando que desencadena una actualización** : Da el identificador de un
    otro comando que, si ese otro comando cambia, forzará el
    actualización del orden mostrado.

-   **Visible** : Marque esta casilla para hacer visible el comando.

-   **Sigue en la línea de tiempo** : Marque esta casilla para tener esto
    el comando es visible en la línea de tiempo cuando se usa.

-   **Prohibir en interacciones automáticas** : les prohíbe
    interacciones automáticas en este comando

-   **Icono** : Le permite cambiar el ícono de comando.

También tienes otros tres botones naranjas debajo :

-   **Este comando reemplaza la ID** : Reemplazar una ID de
    ordenar por el orden en cuestión. Útil si ha eliminado un
    equipo en Jeedom y tienes escenarios que usan
    comandos de ella.

-   **Este comando reemplaza el comando** : Reemplazar un pedido con
    el comando actual.

-   **Reemplace este comando con el comando** : El reverso reemplaza
    el orden por otro orden.

> **Nota**
>
> Este tipo de acción reemplaza los comandos en todo Jeedom
> (escenario, interacción, comando, equipamiento.)

A continuación, encontrará la lista de diferentes equipos,
comandos, escenarios o interacciones que usan este comando. una
haga clic en él para ir directamente a su configuración
respective.

Pestaña de configuración 
--------------------

### Para una orden de tipo de información : 

-   **Cálculo y redondeo**

    -   **Fórmula de cálculo (\#value\# por valor)** : Permite
        realizar una operación sobre el valor del pedido antes
        Tratamiento de Jeedom, ejemplo : ``#value# - 0.2` para afianzar
        0.2 (compensación en un sensor de temperatura).

    -   **Redondeado (número después del punto decimal)** : Vamos a rodear el
        valor de pedido (Ejemplo : poner 2 para transformar
        16.643345 en 16.64).

-   **Tipo genérico** : Le permite configurar el tipo genérico de
    comando (Jeedom intenta encontrarlo solo en modo automático).
    Esta información es utilizada por la aplicación móvil.

-   **Acción sobre el valor, si** : Hagamos tipos de
    mini escenarios. Puede, por ejemplo, decir que si el valor vale
    más de 50 por 3 minutos, entonces tienes que hacer tal acción. lo
    permite, por ejemplo, apagar una luz X minutos después
    esta encendido.

-   **Histórico**

    -   **Historizar** : Marque la casilla para tener los valores para esto
        orden se registrará. (Ver **Análisis → Historia**)

    -   **Modo de suavizado** : Modo de **alisar** o d'**archivado**
        le permite elegir cómo archivar los datos. Por defecto,
        es una **promedio**. También es posible elegir el
        **máximo**, La **mínimo**, o **No**. **No** Permite
        dile a Jeedom que no debe archivar en esto
        orden (tanto durante los primeros 5 minutos como con el
        tarea de archivo). Esta opción es peligrosa porque Jeedom
        mantener todo : entonces habrá mucho más
        datos almacenados.

    -   **Purgue el historial si es anterior a** : Digamos a
        Jeedom para eliminar todos los datos anteriores a uno
        cierto periodo. Puede ser útil para no guardar
        datos si no son necesarios y, por lo tanto, limitan la cantidad
        de información registrada por Jeedom.

-   **Gestión de valores**

    -   **Valor prohibido** : Si el comando toma uno de estos valores,
        Jeedom lo ignora antes de aplicarlo.

    -   **Valor de retorno de estado** : Devuelve el comando a
        este valor después de un tiempo.

    -   **Duración antes del retorno del estado (min)** : Tiempo antes de regresar a
        valor por encima.

-   **Otro**

    -   **Gestión de la repetición de valores** : En automático si el
        el comando sube 2 veces el mismo valor en una fila, luego Jeedom
        no tendrá en cuenta el segundo ascenso (evite disparar
        varias veces un escenario, a menos que el comando sea
        tipo binario). Puede forzar la repetición del valor o
        prohibirlo por completo.

    -   **URL de inserción** : Le permite agregar una URL para llamar en caso de
        orden de actualización. Puedes usar etiquetas
        Próximo : ``#value#`por el valor de la orden,`#cmd_name#``
        para el nombre del comando, `#cmd_id#`para el identificador único
        del comando, `#humanname#`para el nombre completo del comando
        (ex : ``#[SalLa de bain][Hydrometrie][Humidité]#`),`#eq_name#`para el nombre del equipo

### Para un comando de acción : 

-   **Tipo genérico** : Le permite configurar el tipo genérico de
    comando (Jeedom intenta encontrarlo solo en modo automático).
    Esta información es utilizada por la aplicación móvil.

-   **Confirmar acción** : Marque esta casilla para solicitar Jeedom
    confirmación cuando la acción se inicia desde la interfaz
    de este comando.

-   **Código de acceso** : Permite definir un código que Jeedom preguntará
    cuando la acción se inicia desde la interfaz de este comando.

-   **Acción antes de la ejecución del comando** : Le permite agregar
    comandos **antes** cada ejecución de la orden.

-   **Acción después de la ejecución de la orden** : Le permite agregar
    comandos **después** cada ejecución de la orden.

Pestaña Alertas 
--------------

Le permite definir un nivel de alerta (**advertencia** o **peligro**) en
dependiendo de ciertas condiciones. Por ejemplo, si `valor&gt; 8` para 30
minutos, entonces el equipo puede ponerse en alerta **advertencia**.

> **Nota**
>
> En la página **Administración → Configuración → Registros**, usted puede
> configurar un comando de tipo de mensaje que permitirá que Jeedom lo atrape
> advertir si se alcanza el umbral de advertencia o peligro.

Ver pestaña 
----------------

En esta parte, podrá configurar ciertos comportamientos
visualización del widget en el tablero, vistas, diseño y
mobile.

-   **Reproductor** : Le permite elegir el widget en el escritorio o en el móvil (en
    tenga en cuenta que necesita el complemento del widget y también puede hacerlo
    de eso).

-   **Visible** : Marque para hacer visible el comando.

-   **Mostrar nombre** : Marque para hacer el nombre de la
    comando, dependiendo del contexto.

-   **Mostrar nombre e icono** : Marque para hacer visible el ícono
    además del nombre del comando.

-   **Línea envuelta antes del widget** : Seleccionar **Antes de
    Reproductor** o **después del widget** para agregar un salto de línea
    antes o después del widget (para forzar, por ejemplo, una visualización en
    columna de diferentes comandos de equipo en lugar de líneas
    Por defecto)

A continuación, encontramos parámetros de visualización opcionales que
puede cambiar a widget. Estos parámetros dependen del widget en cuestión,
así que tienes que mirar su tarjeta en el mercado para conocerlos.

> **Punta**
>
> No olvide guardar después de cualquier modificación.

Pestaña Código 
-----------

Le permite modificar el código del widget solo para el comando actual.

> **Nota**
>
> Si desea modificar el código, no olvide marcar la casilla
> **Habilitar la personalización del widget**
