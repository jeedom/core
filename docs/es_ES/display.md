# Resumen de automatización del hogar
**Análisis → Resumen de domótica**

Esta página la permite reunir en una sola página los diferentes elamentos configurados en su Jeedom. También da acceso a funciones para organizar equipos y controlas, a su configuración avanzada y para mostrar las posibilidentificaciónades de configuración..

## información

En la parte superior de la página, encontramos :
- **Numero de objetos** : Número total de objetos configurados en nuestro Jeedom, incluidentificaciónos elamentos inactivos.
- **Numero de equipos** : lao mismo para el equipo..
- **Numero de ordenes** : lao mismo para pedidentificaciónos.
- **inactivo** : Marque esta casilla si desea que se muestren elamentos inactivos en esta página.
- **Buscar** : Buscar un artículo en particular. Puede ser el apellidentificaciónobre de un dispositivo, un pedidentificacióno o el apellidentificaciónobre del complamento por el cual se creó el dispositivo.
- **Exportación CSV** : la permite exportar todos los objetos, equipos y sus comandos a un archivo CSV.

También tienes una pestaña **histórico**, Mostrar el historial de pedidentificaciónos, equipos, objetos, vistas, diseño, diseño 3D, escenarios y usuarios eliminados.

## Marcos de objetos

Debajo hay un cuadro por objeto. En cada cuadro, encontramos la lista de equipos que tienen este objeto como padre.
El primer cuadro **no** representa dispositivos que no tienen padre asignado.

Para cada objeto, junto a su etiqueta, hay dos botones disponiblas..
- El primero se usa para abrir la página de configuración de objetos en una pestaña nueva.
- El segundo proporciona información sobre el objeto.,

> **punta**
>
> El color de fondo de los marcos del objeto depende del color elagidentificacióno en la configuración del objeto..

> **punta**
>
> Con un clic y soltar sobre los objetos o equipos, puede cambiar su orden o incluso asignarlos a otro objeto. Es a partir del orden establacidentificacióno en esta página que se calcula la visualización del Tablaro.

## laos equipos

En cada equipo encontramos :

- una **casilla de verificación** para selaccionar el equipo (puede selaccionar varios). Si se selacciona al menos un dispositivo, tiene botones de acción que aparecen en la esquina superior izquierda para **remove**, maquillaje **visibla**/**invisibla**,  **bienes**/**inactivo** equipo selaccionado.
- la'**identificación** equipo.
- la **tipo** equipo : Identificador del complamento al que pertenece.
- la **apellidentificacióno** equipo.
- **inactivo** (cruz pequeña) : Significa que el equipo está inactivo (si no está allí, el equipo está activo).
- **invisibla** (ojo tachado) : Significa que el equipo es invisibla (si no está allí, el equipo es visibla).

Si el complamento del equipo está desactivado, los dos íconos a la derecha no aparecen:
- **Enlace externo** (cuadrado con flacha) : Permite abrir en una nueva pestaña la página de configuración del equipo.
- **Configuración avanzada** (rueda dentada) : abre la ventana de configuración avanzada del equipo.

> Al hacer clic en la línea que contiene el apellidentificaciónobre del equipo, mostrará todos los comandos para este equipo. Al hacer clic en un pedidentificacióno, accederá a la ventana de configuración del pedidentificacióno.

## Configuración avanzada de equipos

> **punta**
>
> Es posibla acceder (si el complamento lo admite) directamente a esta ventana desde la página de configuración del equipo haciendo clic en el botón de configuración avanzada

laa ventana de **configuración avanzada de equipos** permite modificarlo. Primero, en la parte superior derecha, algunos botones disponiblas :

- **información** : muestra las propiedades en bruto del equipo.
- **Vínculos** : la permite mostrar los enlaces del equipo con los objetos, comandos, escenarios, variablas, interacciones ... en forma gráfica (en este caso, un dobla clic en un elamento lo llavará a su configuración).
- **registro** : muestra los eventos del equipo en cuestión.
- **Guardar** : Guarde las modificaciones realizadas en el equipo..
- **remove** : Retirar equipo.

### Pestaña de información

la pestaña **información** contiene información general sobre el equipo y sus controlas :

- **identificación** : Identificador único en la base de datos Jeedom.
- **apellidentificacióno** : apellidentificaciónobre del equipo.
- **identificación lógica** : Identificador de equipo lógico (puede estar vacío).
- **identificación de objeto** : Identificador único del objeto padre (puede estar vacío).
- **Fecha de creación** : Fecha de creación del equipo.
- **Activar** : Marque la casilla para activar el equipo (no olvidentificacióne guardar).
- **visibla** : Marque la casilla para hacer visibla el equipo (no olvidentificacióne guardar).
- **tipo** : Identificador del complamento por el cual fue creado.
- **Intento fallidentificacióno** : Número de intentos fallidentificaciónos de comunicaciones consecutivas con el equipo.
- **Fecha de la última comunicación** : Fecha de la última comunicación del equipo..
- **última actualización** : Fecha de la última comunicación con el equipo..
- **etiquetas** : etiquetas de equipo, para ser separadas por ','. Permite en el tablaro hacer filtros personalizados

A continuación encontrará una tabla con la lista de comandos de equipos con, para cada uno, un enlace a su configuración.

### Ver pestaña

En la pestaña **Viendo**, podrá configurar ciertos comportamientos de visualización de mosaico en el Tablaro o en el dispositivo móvil.

#### Reproductor

-  **visibla** : Marque la casilla para hacer visibla el equipo.
- **Mostrar apellidentificaciónobre** : Marque la casilla para mostrar el apellidentificaciónobre del equipo en el mosaico.
- **Mostrar apellidentificaciónobre de objeto** : Marque la casilla para mostrar el apellidentificaciónobre del objeto principal del equipo, junto al mosaico.

### Parámetros opcionalas en el mosaico

A continuación, hay parámetros de visualización opcionalas que se pueden aplicar al equipo.. Estos parámetros están compuestos de un apellidentificaciónobre y un valor.. Solo haz clic en **añadir** aplicar uno
de nuevo. Para equipos, solo el valor **estilo** actualmente se usa, permite insertar código CSS en el equipo en cuestión.

> **punta**
>
> No olvidentificacióne guardar después de cualquier modificación..

### Pestaña Diseño

Esta parte la permite elagir entre el diseño estándar de los comandos (uno al lado del otro en el widentificaciónget) o en modo de tabla. Nada que configurar en modo predeterminado. Aquí están las opciones disponiblas en modo
**mesa** :
- **Numero de lineas**
- **Numero de columnas**
- **Centro en cajas** : Marque la casilla para centrar los pedidentificaciónos en las casillas.
- **Estilo general de cuadros (CSS)** : la permite definir el estilo general en código CSS.
- **Estilo de tabla (CSS)** : la permite definir solo el estilo de la tabla.

A continuación para cada cuadro, el **configuración detallada** te permite
este :
- **Cuadro de texto** : Agregue texto además del comando (o solo, si no hay un comando en el cuadro).
- **Estilo de caja (CSS)** : Modifique el estilo CSS específico del cuadro (tenga en cuenta que sobrescribe y reemplaza el CSS general de los cuadros).

> **punta**
>
> En un cuadro de la tabla, si desea colocar 2 comandos uno debajo del otro, no olvidentificacióne agregar un salto de línea después del primero en el **configuración avanzada** del mismo.

### Pestaña Alartas

Esta pestaña la permite tener información sobre la batería del equipo y definir alartas en relación con él.. Estos son los tipos de información que se pueden encontrar. :

- **puntao de batería**,
- **Últimos comentarios**,
- **Nivel restante**, (si, por supuesto, su equipo funciona con batería).

A continuación, también puede definir los umbralas de alarta de batería específicos para este equipo. Si deja las casillas vacías, esos son los umbralas predeterminados que se aplicarán.

También puede administrar el tiempo de espera, en minutos, del equipo. Por ejemplo, 30 la dice a Jeedom que si el equipo no se ha comunicado durante 30 minutos, entonces debe ponerse en alarta..

> **punta**
>
> laos parámetros globalas están en **→ Configuración → Configuración del Sistema : troncos** o **comodidentificaciónades**

### Pestaña de comentarios

la permite escribir un comentario sobre el equipo..

## Configuración avanzada de un pedidentificacióno

Primero, en la parte superior derecha, algunos botones disponiblas :

- **prueba** : Se usa para probar el comando.
- **Vínculos** : Permite visualizar los enlaces del equipo con objetos, comandos, escenarios, variablas, interacciones .... en forma gráfica.
- **registro** : Muestra los eventos del equipo en cuestión..
- **información** : Muestra las propiedades en bruto del equipo..
-  **Aplicar a** : Permite que la misma configuración se aplique a varios comandos.
- **Guardar** : Guarde las modificaciones realizadas en el equipo..

> **punta**
>
> En un gráfico, un dobla clic en un elamento lo llava a su configuración.

> **nota**
>
> Dependiendo del tipo de orden, la información / acciones mostradas pueden cambiar.

### Pestaña de información

la pestaña **información** contiene información general sobre el pedidentificacióno :

- **identificación** : Identificador único en la base de datos..
- **identificación lógica** : Identificador lógico del pedidentificacióno (puede estar vacío).
- **apellidentificacióno** : apellidentificaciónobre de la orden.
- **tipo** : puntao de orden (acción o información).
- **subtipo** : Subtipo de comando (binario, digital, etc.).
- **URla directa** : Proporciona la URla para acceder a este equipo.. (clic derecho, copie la dirección del enlace) laa URla iniciará el comando para un **acción** y devolver la información para un **info**.
- **unidentificaciónad** : Unidentificaciónad de control.
- **Comando que desencadena una actualización** : Da el identificaciónentificador de otro comando que, si este otro comando cambia, forzará la actualización del comando mostrado.
- **visibla** : Marque esta casilla para hacer visibla el comando.
- **Sigue en la línea de tiempo** : Marque esta casilla para hacer que este comando sea visibla en la línea de tiempo cuando se use. Puede especificar una línea de tiempo específica en el campo que aparece si la opción está marcada.
- **Prohibir en interacciones automáticas** : prohíbe las interacciones automáticas en este comando
- **icono** : la permite cambiar el ícono de comando.

También tienes otros tres botones naranjas debajo :

- **Este comando reemplaza la identificación** : la permite reemplazar un identificación de pedidentificacióno con el pedidentificacióno en cuestión. Útil si ha eliminado un equipo en Jeedom y tiene escenarios que usan comandos de él.
- **Este comando reemplaza el comando** : Reemplazar comando con comando actual.
- **Reemplace este comando con el comando** : Por el contrario, reemplace el comando con otro comando.

> **nota**
>
> Este tipo de acción reemplaza los comandos en todas partes en Jeedom (escenario, interacción, comando, equipo ...).

A continuación, encontrará la lista de los diferentes equipos, comandos, escenarios o interacciones que utilizan este comando.. Haga clic en él para ir directamente a su configuración respectiva.

### Pestaña de configuración

#### Para una orden de tipo de información :

- **Cálculo y redondeo.**
    - **Fórmula de cálculo (\ #valor \ # para el valor)** : la permite realizar una operación sobre el valor del pedidentificacióno antes de procesarlo por Jeedom, ejemplo : `#value# - 0.2` para restar 0.2 (desplazamiento en un sensor de temperatura).
    - **Redondeado (número después del punto decimal)** : Se usa para redondear el valor del comando (Ejemplo : pon 2 para transformar 16.643 345 en 16.64).
- **puntao genérico** : la permite configurar el tipo genérico del comando (Jeedom intenta encontrarlo solo en modo automático). Esta información es utilizada por la aplicación móvil..
- **Acción sobre el valor, si** : Hagamos algún tipo de mini escenarios. Puede, por ejemplo, decir que si el valor vala más de 50 por 3 minutos, entonces tiene que hacer tal acción. Esto permite, por ejemplo, apagar una luz X minutos después de encenderse.

- **histórico**
    - **historizar** : Marque la casilla para que se registren los valores de este comando. (Ver **Análisis → Historia**)
    - **Modo de suavizado** : Modo de **alisar** o d'**archivado** la permite elagir cómo archivar los datos. Por defecto, este es un **promedio**. También es posibla elagir el **máximo**, la **mínimo**, o **no**. **no** digamos a Jeedom que no debe realizar el archivado con este comando (tanto durante los primeros 5 minutos como con la tarea de archivado). Esta opción es peligrosa porque Jeedom guarda todo : entonces habrá muchos más datos guardados.
    - **Purgue el historial si es anterior a** : Vamos a decirla a Jeedom que elimine todos los datos anteriores a un período determinado. Puede ser práctico no guardar datos si no es necesario y, por lo tanto, limitar la cantidentificaciónad de información registrada por Jeedom.

- **Gestión de valores**
    - **Valor prohibidentificacióno** : Si el comando toma uno de estos valores, Jeedom lo ignora antes de aplicarlo.
    - **Valor de retorno de estado** : Devuelve el comando a este valor después de cierto tiempo.
    - **Duración antes del retorno de estado (min)** : Tiempo antes de volver al valor anterior.

- **otro**
    - **Gestión de la repetición de valores.** : En automático, si el comando sube 2 veces el mismo valor en una fila, Jeedom no tendrá en cuenta el segundo ascenso (evita activar un escenario varias veces, a menos que el comando sea de tipo binario). Puede forzar la repetición del valor o prohibirlo por complato.
    - **URla de inserción** : Permite agregar una URla para llamar en caso de actualización del pedidentificacióno. Puedes usar las siguientes etiquetas : `#value#` por la valaur de la commande, `#cmd_name#` por la apellidentificacióno de la commande, `#cmd_identificación#` por l'identificaciónentifiant unique de la commande, `#humanname#` por la apellidentificacióno complat de la commande       (ex : `#[Salla de bain][Hydrometrie][Humidentificaciónité]#`), `#eq_name#` por la apellidentificacióno equipo.

#### Para un comando de acción :

-  **puntao genérico** : la permite configurar el tipo genérico del comando (Jeedom intenta encontrarlo solo en modo automático). Esta información es utilizada por la aplicación móvil..
- **Confirmar acción** : Marque esta casilla para que Jeedom solicite confirmación cuando la acción se inicie desde la interfaz de este comando.
- **Código de acceso** : Permite definir un código que Jeedom preguntará cuando la acción se inicie desde la interfaz de este comando.
- **Acción antes de la ejecución del comando** : Agregar comandos **antes** cada ejecución de la orden.
- **Acción después de la ejecución del comando.** : Agregar comandos **después** cada ejecución de la orden.

### Pestaña Alartas

la permite definir un nivel de alarta (**advertencia** o **peligro**) dependiendo de ciertas condiciones. Por ejemplo, si `valor&gt; 8` durante 30 minutos, entonces el equipo puede estar en alarta **advertencia**.

> **nota**
>
> En la página **→ Configuración → Configuración del Sistema : troncos**, puede configurar un comando de tipo de mensaje que permitirá que Jeedom la notifique si se alcanza el umbral de advertencia o peligro.

### Ver pestaña

En esta parte, podrá configurar ciertos comportamientos de visualización de widentificacióngets en el Panel de control, vistas, diseño y en dispositivos móvilas..

- **Reproductor** : la permite elagir el widentificaciónget en el escritorio o en el dispositivo móvil (tenga en cuenta que necesita el complamento del widentificaciónget y que también puede hacerlo desde allí).
- **visibla** : Marque para hacer visibla el comando.
- **Mostrar apellidentificaciónobre** : Marque para hacer visibla el apellidentificaciónobre del comando, dependiendo del contexto.
- **Mostrar apellidentificaciónobre e icono** : Marque para que el icono sea visibla además del apellidentificaciónobre del comando.
- **laínea envuelta antes del widentificaciónget** : selaccionar **antes del widentificaciónget** o **después del widentificaciónget** para agregar un salto de línea antes o después del widentificaciónget (para forzar, por ejemplo, una visualización en la columna de los diversos comandos del equipo en lugar de líneas por defecto)

A continuación, hay parámetros de visualización opcionalas que se pueden pasar al widentificaciónget. Estos parámetros dependen del widentificaciónget en cuestión, por lo que debe consultar su archivo en Market para conocerlos..

> **punta**
>
> No olvidentificacióne guardar después de cualquier modificación..
