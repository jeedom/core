# Resumen sobre domótica
**Análisis → Resumen sobre domótica**

Esta página permite reunir en una sola página los distintos elementos configurados en Jeedom. También ofrece acceso a funciones para organizar los dispositivos y los comandos, a su configuración avanzada y a las opciones de configuración de la visualización.

{% include lightbox.html src="../images/doc-display_01.jpg" data="Display" title="Display" imgstyle="width:450px;display: block;margin: 0 auto;" %}

## Información

En la parte superior de la página encontramos:
- **Número de objetos**: Número total de objetos configurados en nuestro Jeedom, incluyendo los inactivos.
- **Número de dispositivos**: Lo mismo ocurre con los dispositivos.
- **Número de pedidos**: Lo mismo ocurre con los pedidos.
- **Inactivo**: Marca esta casilla si quieres que los elementos inactivos se muestren correctamente en esta página.
- **Buscar**: Permite buscar un elemento concreto. Puede ser el nombre de un dispositivo, de un comando o el nombre del complemento con el que se ha creado el dispositivo.
- **Exportar CSV**: Permite exportar todos los objetos, equipos y sus comandos a un archivo CSV.

También dispone de una pestaña **Historial**, en la que se muestra el historial de pedidos, equipos, objetos, vistas, diseños, diseños en 3D, escenarios y usuarios eliminados.

## Los marcos de objetos

Debajo hay un recuadro por objeto. En cada recuadro aparece la lista de equipos que tienen ese objeto como padre.
El primer cuadro **Ninguno** representa los dispositivos que no tienen ningún dispositivo principal asignado.

Para cada objeto, junto a su nombre, hay dos botones disponibles.
- El primero sirve para abrir la página de configuración del objeto en una nueva pestaña.
- El segundo aporta algo de información sobre el objeto,

> **Consejo**
>
> El color de fondo de los marcos de los objetos depende del color elegido en la configuración del objeto.

> **Consejo**
>
> Con solo arrastrar y soltar los objetos o equipos, puedes cambiar su orden o incluso asignarlos a otro objeto. La visualización del panel de control se calcula a partir del orden establecido en esta página.

## Los equipos

En cada dispositivo encontramos:

- Una **casilla de selección** para elegir el dispositivo (puedes seleccionar varios). Si hay al menos un dispositivo seleccionado, aparecerán unos botones de acción en la parte superior izquierda para **eliminar**, hacer **visible**/**invisible** o **activar**/**desactivar** los dispositivos seleccionados.
- El **id** del equipo.
- El **tipo** de equipo: identificador del complemento al que pertenece.
- El **nombre** del equipo.
- **Inactivo** (cruziña): Significa que el equipo está inactivo (si no aparece, el equipo está activo).
- **Invisible** (ojo tachado): Significa que el equipo es invisible (si no aparece, el equipo es visible).

Si el complemento del dispositivo está desactivado, los dos iconos de la derecha no aparecen:
- **Enlace externo** (cuadrado con una flecha): permite abrir en una nueva pestaña la página de configuración del dispositivo.
- **Configuración avanzada** (rueda dentada): permite abrir la ventana de configuración avanzada del equipo.

> Al hacer clic en la línea que contiene el nombre del dispositivo, se mostrarán todos los comandos de dicho dispositivo. A continuación, al hacer clic en un comando, se accederá a la ventana de configuración del mismo.

## Configuración avanzada de un dispositivo

> **Consejo**
>
> Es posible acceder (si el complemento lo admite) directamente a esta ventana desde la página de configuración del equipo haciendo clic en el botón «Configuración avanzada».

La ventana de **configuración avanzada de un dispositivo** permite modificarla. En primer lugar, en la parte superior derecha, hay varios botones disponibles:

- **Información**: muestra las propiedades básicas del equipo.
- **Enlaces**: Permite mostrar los enlaces del equipo con los objetos, comandos, escenarios, variables, interacciones…​ en formato gráfico (en este caso, al hacer doble clic en un elemento se accede a su configuración).
- **Registro**: muestra los eventos del equipo en cuestión.
- **Guardar**: Guarda los cambios realizados en el equipo.
- **Eliminar**: Elimina el equipo.

### Pestaña «Información»

La pestaña **Información** contiene datos generales del equipo, así como sus controles:

- **ID**: Identificador único en la base de datos de Jeedom.
- **Nombre**: Nombre del equipo.
- **ID lógico**: Identificador lógico del equipo (puede estar vacío).
- **ID del objeto**: Identificador único del objeto padre (puede estar vacío).
- **Fecha de creación**: Fecha de creación del equipo.
- **Activar**: Marca la casilla para activar el dispositivo (sin olvidar guardar los cambios).
- **Visible**: Marca la casilla para que el equipo sea visible (sin olvidar guardar los cambios).
- **Tipo**: Identificador del complemento con el que se ha creado.
- **Intento fallido**: Número de intentos consecutivos de comunicación con el equipo que han fallado.
- **Fecha de la última comunicación**: Fecha de la última comunicación del equipo.
- **Última actualización**: Fecha de la última comunicación con el equipo.
- **Etiquetas**: etiquetas del equipo, separadas por «,». Permiten crear filtros personalizados en el panel de control.

A continuación encontrarás una tabla con la lista de comandos del equipo y, para cada uno de ellos, un enlace a su configuración.

### Pestaña «Visualización»

En la pestaña **Visualización**, podrás configurar algunos aspectos relacionados con la visualización del mosaico en el panel de control o en el móvil.

#### Widget

-  **Visible**: Marca la casilla para que el equipo sea visible.
- **Mostrar el nombre**: Marca la casilla para mostrar el nombre del dispositivo en el mosaico.
- **Mostrar el nombre del objeto**: Marca la casilla para mostrar el nombre del objeto principal del equipo, junto al mosaico.

### Parámetros opcionales en el mosaico

Debajo, se encuentran los parámetros opcionales de visualización que se pueden aplicar al equipo. Estos parámetros constan de un nombre y un valor. Basta con hacer clic en **Añadir** para aplicar uno.
novedad. En cuanto a los dispositivos, por el momento solo se utiliza el valor **style**, que permite insertar código CSS en el dispositivo en cuestión.

> **Consejo**
>
> No olvides guardar los cambios después de realizar cualquier modificación.

### Pestaña «Disposición»

En esta sección puedes elegir entre la disposición estándar de los controles (uno al lado del otro en el widget) o el modo tabla. En el modo predeterminado no hay que configurar nada. Estas son las opciones disponibles en el modo
**Tabla**:
- **Número de líneas**
- **Número de columnas**
- **Centrar en las casillas**: Marca la casilla para centrar los controles en las casillas.
- **Estilo general de los recuadros (CSS)**: Permite definir el estilo general mediante código CSS.
- **Estilo de la tabla (CSS)**: Permite definir únicamente el estilo de la tabla.

Debajo de cada casilla, la **configuración detallada** te permite
esto:
- **Texto del cuadro**: Añadir un texto además del comando (o por sí solo, si no hay ningún comando en el cuadro).
- **Estilo de la casilla (CSS)**: Modifica el estilo CSS específico de la casilla (atención: este anula y sustituye el CSS general de las casillas).

> **Consejo**
>
> En una casilla de la tabla, si quieres colocar dos comandos uno debajo del otro, no olvides añadir un salto de línea después del primero en la **configuración avanzada** de este.

### Pestaña «Alertas»

Esta pestaña permite consultar la información sobre la batería del equipo y configurar alertas relacionadas con ella. Estos son los tipos de información que se pueden encontrar:

- **Tipo de pila**,
- **Última actualización de la información**,
- **Nivel restante** (siempre y cuando, claro está, tu dispositivo funcione con pilas).

A continuación, también podrás definir los umbrales específicos de alerta de batería para este dispositivo. Si dejas los campos en blanco, se aplicarán los umbrales predeterminados.

También se puede configurar el tiempo de espera, en minutos, del dispositivo. Por ejemplo, si se introduce 30, se indica a Jeedom que, si el dispositivo no se ha comunicado en los últimos 30 minutos, debe activarse una alerta.

> **Consejo**
>
> Los parámetros generales se encuentran en **Ajustes→Sistema→Configuración: Registros** o **Dispositivos**

### Pestaña «Comentarios»

Te permite escribir un comentario sobre el equipo.

## Configuración avanzada de un comando

En primer lugar, en la parte superior derecha, hay varios botones disponibles:

- **Probar**: Permite probar el comando.
- **Enlaces**: Permite visualizar los enlaces del equipo con los objetos, comandos, escenarios, variables, interacciones…​ de forma gráfica.
- **Registro**: Muestra los eventos del equipo en cuestión.
- **Información**: Muestra las propiedades básicas del equipo.
-  **Aplicar a**: Permite aplicar la misma configuración a varios comandos.
- **Guardar**: Guarda los cambios realizados en el equipo.

> **Consejo**
>
> En un gráfico, al hacer doble clic en un elemento, se accede a su configuración.

> **Nota**
>
> Dependiendo del tipo de comando, la información o las acciones mostradas pueden variar.

### Pestaña «Información»

La pestaña **Información** contiene información general sobre el pedido:

- **ID**: Identificador único en la base de datos.
- **ID lógico**: Identificador lógico del comando (puede estar vacío).
- **Nombre**: Nombre del comando.
- **Tipo**: Tipo de comando (acción o información).
- **Subtipo**: Subtipo del comando (binario, numérico…​).
- **URL directa**: Proporciona la URL para acceder a este dispositivo. (clic con el botón derecho, copiar la dirección del enlace) La URL ejecutará el comando para una **acción** y devolverá la información para una **consulta**.
- **Unidad**: Unidad del pedido.
- **Comando que activa una actualización**: Indica el identificador de otro comando que, si cambia, forzará la actualización del comando que se está visualizando.
- **Visible**: Marca esta casilla para que el comando sea visible.
- **Mostrar en la línea de tiempo**: Marca esta casilla para que este comando sea visible en la línea de tiempo cuando se utilice. Puedes especificar una línea de tiempo concreta en el campo que aparece si se marca esta opción.
- **Prohibir en las interacciones automáticas**: prohíbe las interacciones automáticas en este comando
- **Icono**: Permite cambiar el icono del comando.

También hay otros tres botones naranjas debajo:

- **Este comando sustituye el ID**: Permite sustituir el ID de un comando por el comando en cuestión. Resulta útil si has eliminado un dispositivo en Jeedom y tienes escenarios que utilizan comandos de dicho dispositivo.
- **Este comando sustituye al comando**: Sustituye un comando por el comando actual.
- **Sustituir este comando por el comando**: Lo contrario, sustituye el comando por otro comando.

> **Nota**
>
> Este tipo de acción sustituye a los comandos en todo Jeedom (escenario, interacción, comando, equipo…​.).

A continuación encontrarás la lista de los distintos dispositivos, comandos, escenarios o interacciones que utilizan este comando. Al hacer clic en ellos, accederás directamente a su configuración respectiva.

### Pestaña «Configuración»

#### Para realizar un pedido de información:

- **Cálculo y redondeo**
    - **Fórmula de cálculo (\#value\# para el valor)**: Permite realizar una operación sobre el valor del comando antes de que Jeedom lo procese, por ejemplo: `#value# - 0.2` para restar 0,2 (desviación en un sensor de temperatura).
    - **Redondeo (cifra decimal)**: Permite redondear el valor del pedido (ejemplo: introducir 2 para convertir 16,643345 en 16,64).
- **Tipo genérico**: Permite configurar el tipo genérico del comando (Jeedom intenta detectarlo automáticamente en modo automático). La aplicación móvil utiliza esta información.
- **Acción en función del valor, si**: Permite crear una especie de miniescenarios. Por ejemplo, puedes indicar que, si el valor es superior a 50 durante 3 minutos, se realice una acción determinada. Esto permite, por ejemplo, apagar una luz X minutos después de que se haya encendido.

- **Historia**
    - **Registrar en el historial**: Marca la casilla para que los valores de este comando se registren en el historial. (Véase **Análisis→Historial**)
    - **Modo de suavizado**: El modo de **suavizado** o de **archivo** permite elegir la forma de archivar los datos. Por defecto, se utiliza la **media**. También es posible elegir el **máximo**, el **mínimo** o **ninguno**. **Ninguno** permite indicar a Jeedom que no debe realizar ningún archivado de este comando (ni en el primer periodo de 5 minutos ni con la tarea de archivado). Esta opción es peligrosa, ya que Jeedom lo conserva todo: por lo tanto, se almacenarán muchos más datos.
    - **Borrar el historial anterior a**: Permite indicar a Jeedom que elimine todos los datos anteriores a un periodo determinado. Puede resultar útil para no conservar datos que no sean necesarios y, de este modo, limitar la cantidad de información que almacena Jeedom. Atención: la purga se activa por la noche, por lo que hay que esperar a que termine la noche para que la acción surta efecto.

- **Gestión de valores**
    - **Valor prohibido**: si el comando toma uno de estos valores, Jeedom lo ignora antes de aplicarlo.
    - **Valor de retorno de estado**: Permite que el comando vuelva a este valor tras un tiempo determinado.
    - **Tiempo hasta el retorno al estado anterior (min)**: Tiempo que transcurre hasta volver al valor anterior.

- **Otros**
    - **Gestión de la repetición de valores**: De forma automática, si el comando envía dos veces seguidas el mismo valor, Jeedom no tendrá en cuenta el segundo envío (lo que evita que se active varias veces un escenario, salvo que el comando sea de tipo binario). Puedes forzar la repetición del valor o prohibirla por completo.
    - **Push URL**: Permite añadir una URL a la que se debe acceder en caso de que se actualice el comando. Puedes utilizar las siguientes etiquetas: `#value#` por el importe del pedido, `#cmd_name#` para el nombre del comando, `#cmd_id#` para el identificador único del pedido, `#humanname#` para el nombre completo del comando (p. ej.: `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` como nombre del equipo.

#### Para una orden de acción:

-  **Tipo genérico**: Permite configurar el tipo genérico del comando (Jeedom intenta detectarlo automáticamente en modo automático). La aplicación móvil utiliza esta información.
- **Confirmar la acción**: Marca esta casilla para que Jeedom solicite una confirmación cuando se inicie la acción desde la interfaz de este comando.
- **Código de acceso**: Permite definir un código que Jeedom solicitará cuando se inicie la acción desde la interfaz de este comando.
- **Acción antes de ejecutar el comando**: Permite añadir comandos **antes** de cada ejecución del comando.
- **Acción tras la ejecución del comando**: Permite añadir comandos **después** de cada ejecución del comando.

### Pestaña «Alertas»

Permite definir un nivel de alerta (**advertencia** o **peligro**) en función de determinadas condiciones. Por ejemplo, si `value > 8` durante 30 minutos, el equipo puede pasar al estado de alerta **warning**.

> **Nota**
>
> En la página **Ajustes→Sistema→Configuración: Registros**, puedes configurar un comando de tipo «mensaje» que permitirá a Jeedom avisarte si se alcanza el umbral de advertencia o de peligro.

### Pestaña «Visualización»

En esta sección, podrás configurar determinados comportamientos de visualización del widget en el panel de control, las vistas, el diseño y en dispositivos móviles.

- **Widget**: Permite elegir el widget para ordenador o móvil (ten en cuenta que necesitas el plugin de widgets y que también puedes hacerlo desde allí).
- **Visible**: Marca esta casilla para que el comando sea visible.
- **Mostrar el nombre**: Marca esta casilla para que se vea el nombre del comando, según el contexto.
- **Mostrar el nombre y el icono**: Marca esta casilla para que se muestre el icono además del nombre del comando.
- **Salto de línea forzado antes del widget**: Marca **antes del widget** o **después del widget** para añadir un salto de línea antes o después del widget (por ejemplo, para forzar que se muestren en columnas los distintos comandos del equipo en lugar de en líneas, como es el caso por defecto)

Debajo se encuentran los parámetros opcionales de visualización que se pueden aplicar al widget. Estos parámetros dependen del widget en cuestión, por lo que hay que consultar su ficha en el Market para conocerlos.

> **Consejo**
>
> No olvides guardar los cambios después de realizar cualquier modificación.
