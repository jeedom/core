# Resumen de domótica
**Análisis → Resumen de domótica**

Esta página La permite reunir en una sola página los diferentes elementos configurados en su Jeedom. También da acceso a funciones para organizar equipos y controles, a su configuración avanzada y para mostrar las posibilidades de configuración..

## Informations

En la parte superior de la página, encontramos :
- **Numero de objetos** : Número total de objetos configurados en nuestro Jeedom, incluidos elementos inactivos.
- **Numero de equipos** : Lo mismo para el equipo..
- **Numero de ordenes** : Lo mismo para pedidos.
- **Inactif** : Marque esta casilla si desea que se muestren elementos inactivos en esta página.
- **Rechercher** : Buscar un artículo en particular. Puede ser el nombre de un dispositivo, un pedido o el nombre del complemento por el cual se creó el dispositivo.
- **Exportación CSV** : La permite exportar todos los objetos, equipos y sus comandos a un archivo CSV.

También tienes una pestaña **Historique**, Mostrar el historial de pedidos, equipos, objetos, vistas, diseño, diseño 3D, escenarios y usuarios eliminados.

## Marcos de objetos

Debajo hay un cuadro por objeto. En cada cuadro, encontramos la lista de equipos que tienen este objeto como padre.
El primer cuadro **Aucun** representa dispositivos que no tienen padre asignado.

Para cada objeto, junto a su etiqueta, hay dos botones disponibles..
- El primero se usa para abrir la página de configuración de objetos en una pestaña nueva.
- El segundo proporciona información sobre el objeto,

> **Tip**
>
> El color de fondo de los marcos del objeto depende del color elegido en la configuración del objeto..

> **Tip**
>
> Con un clic y soltar sobre los objetos o equipos, puede cambiar su orden o incluso asignarlos a otro objeto. Es a partir del orden establecido en esta página que se calcula la visualización del Tablero.

## Los equipos

En cada equipo encontramos :

- Una **casilla de verificación** para seleccionar el equipo (puede seleccionar varios). Si se selecciona al menos un dispositivo, tiene botones de acción que aparecen en la esquina superior izquierda para **supprimer**, maquillaje **visible**/ /**invisible**,  **actif**/ /**inactif** equipo seleccionado.
- L'**id** equipo.
- La **type** equipo : Identificador del complemento al que pertenece.
- La **nom** equipo.
- **Inactif** (cruz pequeña) : Significa que el equipo está inactivo (si no está allí, el equipo está activo).
- **Invisible** (ojo tachado) : Significa que el equipo es invisibLa (si no está allí, el equipo es visible).

Si el complemento del equipo está desactivado, los dos íconos a la derecha no aparecen:
- **Enlace externo** (cuadrado con flecha) : Permite abrir en una nueva pestaña la página de configuración del equipo.
- **Configuración avanzada** (rueda dentada) : abre la ventana de configuración avanzada del equipo.

> Al hacer clic en la línea que contiene el nombre del equipo, mostrará todos los comandos para este equipo. Al hacer clic en un pedido, accederá a la ventana de configuración del pedido.

## Configuración avanzada de equipos

> **Tip**
>
> Es posibLa acceder (si el complemento lo admite) directamente a esta ventana desde la página de configuración del equipo haciendo clic en el botón de configuración avanzada

La ventana de **Configuración avanzada de equipos** permite modificarlo. Primero, en la parte superior derecha, algunos botones disponibles :

- **Informations** : muestra las propiedades en bruto del equipo.
- **Liens** : La permite mostrar los enlaces del equipo con los objetos, comandos, escenarios, variables, interacciones ... en forma gráfica (en este caso, un dobLa clic en un elemento lo llevará a su configuración).
- **Log** : muestra los eventos del equipo en cuestión.
- **Sauvegarder** : Guarde las modificaciones realizadas en el equipo..
- **Supprimer** : Retirar equipo.

### Pestaña de información

La pestaña **Informations** contiene información general sobre el equipo y sus controles :

- **ID** : Identificador único en la base de datos Jeedom.
- **Nom** : Nombre del equipo.
- **Identificación lógica** : Identificador de equipo lógico (puede estar vacío).
- **Identificación de objeto** : Identificador único del objeto padre (puede estar vacío).
- **Fecha de creación** : Fecha de creación del equipo.
- **Activer** : Marque la casilla para activar el equipo (no olvide guardar).
- **Visible** : Marque la casilla para hacer visibLa el equipo (no olvide guardar).
- **Type** : Identificador del complemento por el cual fue creado.
- **Intento fallido** : Número de intentos fallidos de comunicaciones consecutivas con el equipo.
- **Fecha de la última comunicación** : Fecha de la última comunicación del equipo..
- **última actualización** : Fecha de la última comunicación con el equipo..
- **Tags** : etiquetas de equipo, para ser separadas por ','. Permite en el tablero hacer filtros personalizados

A continuación encontrará una tabla con la lista de comandos de equipos con, para cada uno, un enlace a su configuración.

### Ver pestaña

En la pestaña **Affichage**, podrá configurar ciertos comportamientos de visualización de mosaico en el Tablero o en el dispositivo móvil.

#### Widget

-  **Visible** : Marque la casilla para hacer visibLa el equipo.
- **Mostrar nombre** : Marque la casilla para mostrar el nombre del equipo en el mosaico.
- **Mostrar nombre de objeto** : Marque la casilla para mostrar el nombre del objeto principal del equipo, junto al mosaico.

### Parámetros opcionales en el mosaico

A continuación, hay parámetros de visualización opcionales que se pueden aplicar al equipo.. Estos parámetros están compuestos de un nombre y un valor.. Solo haz clic en **Ajouter** aplicar uno
de nuevo. Para equipos, solo el valor **style** actualmente se usa, permite insertar código CSS en el equipo en cuestión.

> **Tip**
>
> No olvide guardar después de cualquier modificación..

### Pestaña Diseño

Esta parte La permite elegir entre el diseño estándar de los comandos (uno al lado del otro en el widget) o en modo de tabla. Nada que configurar en modo predeterminado. Aquí están las opciones disponibles en modo
**Tableau** :
- **Numero de lineas**
- **Numero de columnas**
- **Centro en cajas** : Marque la casilla para centrar los pedidos en las casillas.
- **Estilo general de cuadros (CSS)** : La permite definir el estilo general en código CSS.
- **Estilo de tabla (CSS)** : La permite definir solo el estilo de la tabla.

A continuación para cada cuadro, el **configuración detallada** te permite
este :
- **Cuadro de texto** : Agregue texto además del comando (o solo, si no hay un comando en el cuadro).
- **Estilo de caja (CSS)** : Modifique el estilo CSS específico del cuadro (tenga en cuenta que sobrescribe y reemplaza el CSS general de los cuadros).

> **Tip**
>
> En un cuadro de la tabla, si desea colocar 2 comandos uno debajo del otro, no olvide agregar un salto de línea después del primero en el **Configuración avanzada** del mismo.

### Pestaña Alertas

Esta pestaña La permite tener información sobre la batería del equipo y definir alertas en relación con él.. Estos son los tipos de información que se pueden encontrar. :

- **Tipo de batería**,
- **Últimos comentarios**,
- **Nivel restante**, (si, por supuesto, su equipo funciona con batería).

A continuación, también puede definir los umbrales de alerta de batería específicos para este equipo. Si deja las casillas vacías, esos son los umbrales predeterminados que se aplicarán.

También puede administrar el tiempo de espera, en minutos, del equipo. Por ejemplo, 30 La dice a Jeedom que si el equipo no se ha comunicado durante 30 minutos, entonces debe ponerse en alerta..

> **Tip**
>
> Los parámetros globales están en **→ Configuración → Configuración del Sistema : Logs** o **Equipements**

### Pestaña de comentarios

La permite escribir un comentario sobre el equipo..

## Configuración avanzada de un pedido

Primero, en la parte superior derecha, algunos botones disponibles :

- **Tester** : Se usa para probar el comando.
- **Liens** : Permite visualizar los enlaces del equipo con objetos, comandos, escenarios, variables, interacciones .... en forma gráfica.
- **Log** : Muestra los eventos del equipo en cuestión.
- **Informations** : Muestra las propiedades en bruto del equipo.
-  **Aplicar** : Permite que la misma configuración se aplique a varios comandos.
- **Sauvegarder** : Guarde las modificaciones realizadas en el equipo..

> **Tip**
>
> En un gráfico, un dobLa clic en un elemento lo lleva a su configuración.

> **Note**
>
> Dependiendo del tipo de orden, la información / / acciones mostradas pueden cambiar.

### Pestaña de información

La pestaña **Informations** contiene información general sobre el pedido :

- **ID** : Identificador único en la base de datos..
- **Identificación lógica** : Identificador lógico del pedido (puede estar vacío).
- **Nom** : Nombre de la orden.
- **Type** : Tipo de orden (acción o información).
- **Sous-type** : Subtipo de comando (binario, digital, etc.).
- **URLa directa** : Proporciona la URLa para acceder a este equipo.. (clic derecho, copie la dirección del enlace) La URLa iniciará el comando para un **action** y devolver la información para un **info**.
- **Unité** : Unidad de control.
- **Comando que desencadena una actualización** : Da el identificador de otro comando que, si este otro comando cambia, forzará la actualización del comando mostrado.
- **Visible** : Marque esta casilla para hacer visibLa el comando.
- **Sigue en la línea de tiempo** : Marque esta casilla para hacer que este comando sea visibLa en la línea de tiempo cuando se use. Puede especificar una línea de tiempo específica en el campo que aparece si la opción está marcada.
- **Prohibir en interacciones automáticas** : prohíbe las interacciones automáticas en este comando
- **Icono** : La permite cambiar el ícono de comando.

También tienes otros tres botones naranjas debajo :

- **Este comando reemplaza la ID** : La permite reemplazar un Identificación de pedido con el pedido en cuestión. Útil si ha eliminado un equipo en Jeedom y tiene escenarios que usan comandos de él.
- **Este comando reemplaza el comando** : Reemplazar comando con comando actual.
- **Reemplace este comando con el comando** : Por el contrario, reemplace el comando con otro comando.

> **Note**
>
> Este tipo de acción reemplaza los comandos en todas partes en Jeedom (escenario, interacción, comando, equipo ...).

A continuación, encontrará la lista de los diferentes equipos, comandos, escenarios o interacciones que utilizan este comando.. Haga clic en él para ir directamente a su configuración respectiva.

### Pestaña de configuración

#### Para una orden de tipo de información :

- **Cálculo y redondeo.**
    - **Fórmula de cálculo (\#valor \# para el valor)** : La permite realizar una operación sobre el valor del pedido antes de procesarlo por Jeedom, ejemplo : `# valor # - 0.2` para restar 0.2 (desplazamiento en un sensor de temperatura).
    - **Redondeado (número después del punto decimal)** : Se usa para redondear el valor del comando (Ejemplo : pon 2 para transformar 16.643 345 en 16.64).
- **Tipo genérico** : La permite configurar el tipo genérico del comando (Jeedom intenta encontrarlo solo en modo automático). Esta información es utilizada por la aplicación móvil..
- **Acción sobre el valor, si** : Hagamos algún tipo de mini escenarios. Puede, por ejemplo, decir que si el valor vaLa más de 50 por 3 minutos, entonces tiene que hacer tal acción. Esto permite, por ejemplo, apagar una luz X minutos después de encenderse.

- **Historique**
    - **Historiser** : Marque la casilla para que se registren los valores de este comando. (Ver **Análisis → Historia**)
    - **Modo de suavizado** : Modo de **lissage** o d'**archivage** La permite elegir cómo archivar los datos. Por defecto, este es un **moyenne**. También es posibLa elegir el **maximum**, La **minimum**, o **aucun**. **aucun** digamos a Jeedom que no debe realizar el archivado con este comando (tanto durante los primeros 5 minutos como con la tarea de archivado). Esta opción es peligrosa porque Jeedom guarda todo : entonces habrá muchos más datos guardados.
    - **Purgue el historial si es anterior a** : Vamos a decirLa a Jeedom que elimine todos los datos anteriores a un período determinado. Puede ser práctico no guardar datos si no es necesario y, por lo tanto, limitar la cantidad de información registrada por Jeedom.

- **Gestión de valores**
    - **Valor prohibido** : Si el comando toma uno de estos valores, Jeedom lo ignora antes de aplicarlo.
    - **Valor de retorno de estado** : Devuelve el comando a este valor después de cierto tiempo.
    - **Duración antes del retorno de estado (min)** : Tiempo antes de volver al valor anterior.

- **Autres**
    - **Gestión de la repetición de valores.** : En automático, si el comando sube 2 veces el mismo valor en una fila, Jeedom no tendrá en cuenta el segundo ascenso (evita activar un escenario varias veces, a menos que el comando sea de tipo binario). Puede forzar la repetición del valor o prohibirlo por completo.
    - **URLa de inserción** : Permite agregar una URLa para llamar en caso de actualización del pedido. Puedes usar las siguientes etiquetas : `# value #` para el valor de la orden, `# cmd_name #` para el nombre de la orden, `# cmd_Identificación #` para el identificador único de la orden, `# humanname #` para el nombre completo de la orden (ex : `# [Baño] [Hidrometría] [Humedad] #`), `# eq_name #` para el nombre del equipo.

#### Para un comando de acción :

-  **Tipo genérico** : La permite configurar el tipo genérico del comando (Jeedom intenta encontrarlo solo en modo automático). Esta información es utilizada por la aplicación móvil..
- **Confirmar acción** : Marque esta casilla para que Jeedom solicite confirmación cuando la acción se inicie desde la interfaz de este comando.
- **Código de acceso** : Permite definir un código que Jeedom preguntará cuando la acción se inicie desde la interfaz de este comando.
- **Acción antes de la ejecución del comando** : Agregar comandos **avant** cada ejecución de la orden.
- **Acción después de la ejecución de la orden.** : Agregar comandos **después** cada ejecución de la orden.

### Pestaña Alertas

La permite definir un nivel de alerta (**warning** o **danger**) dependiendo de ciertas condiciones. Por ejemplo, si `valor&gt; 8` durante 30 minutos, entonces el equipo puede estar en alerta **warning**.

> **Note**
>
> En la página **→ Configuración → Configuración del Sistema : Logs**, puede configurar un comando de tipo de mensaje que permitirá que Jeedom La notifique si se alcanza el umbral de advertencia o peligro.

### Ver pestaña

En esta parte, podrá configurar ciertos comportamientos de visualización de widgets en el Panel de control, vistas, diseño y en dispositivos móviles..

- **Widget** : La permite elegir el widget en el escritorio o en el dispositivo móvil (tenga en cuenta que necesita el complemento del widget y que también puede hacerlo desde allí).
- **Visible** : Marque para hacer visibLa el comando.
- **Mostrar nombre** : Marque para hacer visibLa el nombre del comando, dependiendo del contexto.
- **Mostrar nombre e icono** : Marque para que el icono sea visibLa además del nombre del comando.
- **Línea envuelta antes del widget** : Seleccionar **antes del widget** o **después del widget** para agregar un salto de línea antes o después del widget (para forzar, por ejemplo, una visualización en la columna de los diversos comandos del equipo en lugar de líneas por defecto)

A continuación, hay parámetros de visualización opcionales que se pueden pasar al widget. Estos parámetros dependen del widget en cuestión, por lo que debe consultar su archivo en Market para conocerlos..

> **Tip**
>
> No olvide guardar después de cualquier modificación..
