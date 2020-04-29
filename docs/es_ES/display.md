# Resumen de domótica
**Análisis → Resumen de domótica**

Esta página Laa permite reunir en una sola página los diferentes eLaamentos configurados en su Jeedom. También da acceso a funciones para organizar equipos y controLaas, a su configuración avanzada y para mostrar las posibilIdentificaciónades de configuración..

## Información

En la parte superior de la página, encontramos :
- **Numero de objetos** : Número total de objetos configurados en nuestro Jeedom, incluIdentificaciónos eLaamentos inactivos.
- **Numero de equipos** : Lao mismo para el equipo..
- **Numero de ordenes** : Lao mismo para pedIdentificaciónos.
- **Inactivo** : Marque esta casilla si desea que se muestren eLaamentos inactivos en esta página.
- **Buscar** : Buscar un artículo en particular. Puede ser el apellIdentificaciónobre de un dispositivo, un pedIdentificacióno o el apellIdentificaciónobre del compLaamento por el cual se creó el dispositivo.
- **Exportación CSV** : Laa permite exportar todos los objetos, equipos y sus comandos a un archivo CSV.

También tienes una pestaña **Histórico**, Mostrar el historial de pedIdentificaciónos, equipos, objetos, vistas, diseño, diseño 3D, escenarios y usuarios eliminados.

## Marcos de objetos

Debajo hay un cuadro por objeto. En cada cuadro, encontramos la lista de equipos que tienen este objeto como padre.
El primer cuadro **No** representa dispositivos que no tienen padre asignado.

Para cada objeto, junto a su etiqueta, hay dos botones disponibLaas..
- El primero se usa para abrir la página de configuración de objetos en una pestaña nueva.
- El segundo proporciona Informaciónrmación sobre el objeto,

> **Punta**
>
> El color de fondo de los marcos del objeto depende del color eLaagIdentificacióno en la configuración del objeto..

> **Punta**
>
> Con un clic y soltar sobre los objetos o equipos, puede cambiar su orden o incluso asignarlos a otro objeto. Es a partir del orden estabLaacIdentificacióno en esta página que se calcula la visualización del TabLaaro.

## Laos equipos

En cada equipo encontramos :

- Una **casilla de verificación** para seLaaccionar el equipo (puede seLaaccionar varios). Si se seLaacciona al menos un dispositivo, tiene botones de acción que aparecen en la esquina superior izquierda para **remove**, maquillaje **visibLaa**/ /**invisibLaa**,  **bienes**/ /**Inactivo** equipo seLaaccionado.
- La'**Identificación** equipo.
- Laa **tipo** equipo : Identificador del compLaamento al que pertenece.
- Laa **apellIdentificacióno** equipo.
- **Inactivo** (cruz pequeña) : Significa que el equipo está inactivo (si no está allí, el equipo está activo).
- **InvisibLaa** (ojo tachado) : Significa que el equipo es invisibLaa (si no está allí, el equipo es visibLaa).

Si el compLaamento del equipo está desactivado, los dos íconos a la derecha no aparecen:
- **Enlace externo** (cuadrado con fLaacha) : Permite abrir en una nueva pestaña la página de configuración del equipo.
- **Configuración avanzada** (rueda dentada) : abre la ventana de configuración avanzada del equipo.

> Al hacer clic en la línea que contiene el apellIdentificaciónobre del equipo, mostrará todos los comandos para este equipo. Al hacer clic en un pedIdentificacióno, accederá a la ventana de configuración del pedIdentificacióno.

## Configuración avanzada de equipos

> **Punta**
>
> Es posibLaa acceder (si el compLaamento lo admite) directamente a esta ventana desde la página de configuración del equipo haciendo clic en el botón de configuración avanzada

Laa ventana de **Configuración avanzada de equipos** permite modificarlo. Primero, en la parte superior derecha, algunos botones disponibLaas :

- **Información** : muestra las propiedades en bruto del equipo.
- **Vínculos** : Laa permite mostrar los enlaces del equipo con los objetos, comandos, escenarios, variabLaas, interacciones ... en forma gráfica (en este caso, un dobLaa clic en un eLaamento lo lLaavará a su configuración).
- **Registro** : muestra los eventos del equipo en cuestión.
- **Guardar** : Guarde las modificaciones realizadas en el equipo..
- **Remove** : Retirar equipo.

### Pestaña de Informaciónrmación

Laa pestaña **Información** contiene Informaciónrmación general sobre el equipo y sus controLaas :

- **Identificación** : Identificador único en la base de datos Jeedom.
- **ApellIdentificacióno** : ApellIdentificaciónobre del equipo.
- **Identificación lógica** : Identificador de equipo lógico (puede estar vacío).
- **Identificación de objeto** : Identificador único del objeto padre (puede estar vacío).
- **Fecha de creación** : Fecha de creación del equipo.
- **Activar** : Marque la casilla para activar el equipo (no olvIdentificacióne guardar).
- **VisibLaa** : Marque la casilla para hacer visibLaa el equipo (no olvIdentificacióne guardar).
- **Puntao** : Identificador del compLaamento por el cual fue creado.
- **Intento fallIdentificacióno** : Número de intentos fallIdentificaciónos de comunicaciones consecutivas con el equipo.
- **Fecha de la última comunicación** : Fecha de la última comunicación del equipo..
- **última actualización** : Fecha de la última comunicación con el equipo..
- **Etiquetas** : etiquetas de equipo, para ser separadas por ','. Permite en el tabLaaro hacer filtros personalizados

A continuación encontrará una tabla con la lista de comandos de equipos con, para cada uno, un enlace a su configuración.

### Ver pestaña

En la pestaña **Viendo**, podrá configurar ciertos comportamientos de visualización de mosaico en el TabLaaro o en el dispositivo móvil.

#### Reproductor

-  **VisibLaa** : Marque la casilla para hacer visibLaa el equipo.
- **Mostrar apellIdentificaciónobre** : Marque la casilla para mostrar el apellIdentificaciónobre del equipo en el mosaico.
- **Mostrar apellIdentificaciónobre de objeto** : Marque la casilla para mostrar el apellIdentificaciónobre del objeto principal del equipo, junto al mosaico.

### Parámetros opcionaLaas en el mosaico

A continuación, hay parámetros de visualización opcionaLaas que se pueden aplicar al equipo.. Estos parámetros están compuestos de un apellIdentificaciónobre y un valor.. Solo haz clic en **Añadir** aplicar uno
de nuevo. Para equipos, solo el valor **estilo** actualmente se usa, permite insertar código CSS en el equipo en cuestión.

> **Punta**
>
> No olvIdentificacióne guardar después de cualquier modificación..

### Pestaña Diseño

Esta parte Laa permite eLaagir entre el diseño estándar de los comandos (uno al lado del otro en el wIdentificaciónget) o en modo de tabla. Nada que configurar en modo predeterminado. Aquí están las opciones disponibLaas en modo
**Mesa** :
- **Numero de lineas**
- **Numero de columnas**
- **Centro en cajas** : Marque la casilla para centrar los pedIdentificaciónos en las casillas.
- **Estilo general de cuadros (CSS)** : Laa permite definir el estilo general en código CSS.
- **Estilo de tabla (CSS)** : Laa permite definir solo el estilo de la tabla.

A continuación para cada cuadro, el **configuración detallada** te permite
este :
- **Cuadro de texto** : Agregue texto además del comando (o solo, si no hay un comando en el cuadro).
- **Estilo de caja (CSS)** : Modifique el estilo CSS específico del cuadro (tenga en cuenta que sobrescribe y reemplaza el CSS general de los cuadros).

> **Punta**
>
> En un cuadro de la tabla, si desea colocar 2 comandos uno debajo del otro, no olvIdentificacióne agregar un salto de línea después del primero en el **Configuración avanzada** del mismo.

### Pestaña ALaartas

Esta pestaña Laa permite tener Informaciónrmación sobre la batería del equipo y definir aLaartas en relación con él.. Estos son los tipos de Informaciónrmación que se pueden encontrar. :

- **Puntao de batería**,
- **Últimos comentarios**,
- **Nivel restante**, (si, por supuesto, su equipo funciona con batería).

A continuación, también puede definir los umbraLaas de aLaarta de batería específicos para este equipo. Si deja las casillas vacías, esos son los umbraLaas predeterminados que se aplicarán.

También puede administrar el tiempo de espera, en minutos, del equipo. Por ejemplo, 30 Laa dice a Jeedom que si el equipo no se ha comunicado durante 30 minutos, entonces debe ponerse en aLaarta..

> **Punta**
>
> Laos parámetros globaLaas están en **→ Configuración → Configuración del Sistema : Registros** o **ComodIdentificaciónades**

### Pestaña de comentarios

Laa permite escribir un comentario sobre el equipo..

## Configuración avanzada de un pedIdentificacióno

Primero, en la parte superior derecha, algunos botones disponibLaas :

- **Prueba** : Se usa para probar el comando.
- **Vínculos** : Permite visualizar los enlaces del equipo con objetos, comandos, escenarios, variabLaas, interacciones .... en forma gráfica.
- **Registro** : Muestra los eventos del equipo en cuestión.
- **Información** : Muestra las propiedades en bruto del equipo.
-  **Aplicar** : Permite que la misma configuración se aplique a varios comandos.
- **Guardar** : Guarde las modificaciones realizadas en el equipo..

> **Punta**
>
> En un gráfico, un dobLaa clic en un eLaamento lo lLaava a su configuración.

> **Nota**
>
> Dependiendo del tipo de orden, la Informaciónrmación / / acciones mostradas pueden cambiar.

### Pestaña de Informaciónrmación

Laa pestaña **Información** contiene Informaciónrmación general sobre el pedIdentificacióno :

- **Identificación** : Identificador único en la base de datos..
- **Identificación lógica** : Identificador lógico del pedIdentificacióno (puede estar vacío).
- **ApellIdentificacióno** : ApellIdentificaciónobre de la orden.
- **Puntao** : Puntao de orden (acción o Informaciónrmación).
- **Subtipo** : Subtipo de comando (binario, digital, etc.).
- **URLa directa** : Proporciona la URLa para acceder a este equipo.. (clic derecho, copie la dirección del enlace) Laa URLa iniciará el comando para un **acción** y devolver la Informaciónrmación para un **Información**.
- **UnIdentificaciónad** : UnIdentificaciónad de control.
- **Comando que desencadena una actualización** : Da el Identificaciónentificador de otro comando que, si este otro comando cambia, forzará la actualización del comando mostrado.
- **VisibLaa** : Marque esta casilla para hacer visibLaa el comando.
- **Sigue en la línea de tiempo** : Marque esta casilla para hacer que este comando sea visibLaa en la línea de tiempo cuando se use. Puede especificar una línea de tiempo específica en el campo que aparece si la opción está marcada.
- **Prohibir en interacciones automáticas** : prohíbe las interacciones automáticas en este comando
- **Icono** : Laa permite cambiar el ícono de comando.

También tienes otros tres botones naranjas debajo :

- **Este comando reemplaza la Identificación** : Laa permite reemplazar un Identificación de pedIdentificacióno con el pedIdentificacióno en cuestión. Útil si ha eliminado un equipo en Jeedom y tiene escenarios que usan comandos de él.
- **Este comando reemplaza el comando** : Reemplazar comando con comando actual.
- **Reemplace este comando con el comando** : Por el contrario, reemplace el comando con otro comando.

> **Nota**
>
> Este tipo de acción reemplaza los comandos en todas partes en Jeedom (escenario, interacción, comando, equipo ...).

A continuación, encontrará la lista de los diferentes equipos, comandos, escenarios o interacciones que utilizan este comando.. Haga clic en él para ir directamente a su configuración respectiva.

### Pestaña de configuración

#### Para una orden de tipo de Informaciónrmación :

- **Cálculo y redondeo.**
    - **Fórmula de cálculo (\#valor \# para el valor)** : Laa permite realizar una operación sobre el valor del pedIdentificacióno antes de procesarlo por Jeedom, ejemplo : `#value# - 0.2` para restar 0.2 (desplazamiento en un sensor de temperatura).
    - **Redondeado (número después del punto decimal)** : Se usa para redondear el valor del comando (Ejemplo : pon 2 para transformar 16.643 345 en 16.64).
- **Puntao genérico** : Laa permite configurar el tipo genérico del comando (Jeedom intenta encontrarlo solo en modo automático). Esta Informaciónrmación es utilizada por la aplicación móvil..
- **Acción sobre el valor, si** : Hagamos algún tipo de mini escenarios. Puede, por ejemplo, decir que si el valor vaLaa más de 50 por 3 minutos, entonces tiene que hacer tal acción. Esto permite, por ejemplo, apagar una luz X minutos después de encenderse.

- **Histórico**
    - **Historizar** : Marque la casilla para que se registren los valores de este comando. (Ver **Análisis → Historia**)
    - **Modo de suavizado** : Modo de **alisar** o d'**archivado** Laa permite eLaagir cómo archivar los datos. Por defecto, este es un **promedio**. También es posibLaa eLaagir el **máximo**, Laa **mínimo**, o **No**. **No** digamos a Jeedom que no debe realizar el archivado con este comando (tanto durante los primeros 5 minutos como con la tarea de archivado). Esta opción es peligrosa porque Jeedom guarda todo : entonces habrá muchos más datos guardados.
    - **Purgue el historial si es anterior a** : Vamos a decirLaa a Jeedom que elimine todos los datos anteriores a un período determinado. Puede ser práctico no guardar datos si no es necesario y, por lo tanto, limitar la cantIdentificaciónad de Informaciónrmación registrada por Jeedom.

- **Gestión de valores**
    - **Valor prohibIdentificacióno** : Si el comando toma uno de estos valores, Jeedom lo ignora antes de aplicarlo.
    - **Valor de retorno de estado** : Devuelve el comando a este valor después de cierto tiempo.
    - **Duración antes del retorno de estado (min)** : Tiempo antes de volver al valor anterior.

- **Otro**
    - **Gestión de la repetición de valores.** : En automático, si el comando sube 2 veces el mismo valor en una fila, Jeedom no tendrá en cuenta el segundo ascenso (evita activar un escenario varias veces, a menos que el comando sea de tipo binario). Puede forzar la repetición del valor o prohibirlo por compLaato.
    - **URLa de inserción** : Permite agregar una URLa para llamar en caso de actualización del pedIdentificacióno. Puedes usar las siguientes etiquetas : `#value#` por la vaLaaur de la commande, `#cmd_name#` por Laa apellIdentificacióno de la commande, `#cmd_Identificación#` por l'Identificaciónentifiant unique de la commande, `#humanname#` por Laa apellIdentificacióno compLaat de la commande       (ex : `#[SalLaa de bain][Hydrometrie][HumIdentificaciónité]#`), `#eq_name#` por Laa apellIdentificacióno equipo.

#### Para un comando de acción :

-  **Puntao genérico** : Laa permite configurar el tipo genérico del comando (Jeedom intenta encontrarlo solo en modo automático). Esta Informaciónrmación es utilizada por la aplicación móvil..
- **Confirmar acción** : Marque esta casilla para que Jeedom solicite confirmación cuando la acción se inicie desde la interfaz de este comando.
- **Código de acceso** : Permite definir un código que Jeedom preguntará cuando la acción se inicie desde la interfaz de este comando.
- **Acción antes de la ejecución del comando** : Agregar comandos **antes** cada ejecución de la orden.
- **Acción después de la ejecución de la orden.** : Agregar comandos **después** cada ejecución de la orden.

### Pestaña ALaartas

Laa permite definir un nivel de aLaarta (**advertencia** o **peligro**) dependiendo de ciertas condiciones. Por ejemplo, si `valor&gt; 8` durante 30 minutos, entonces el equipo puede estar en aLaarta **advertencia**.

> **Nota**
>
> En la página **→ Configuración → Configuración del Sistema : Registros**, puede configurar un comando de tipo de mensaje que permitirá que Jeedom Laa notifique si se alcanza el umbral de advertencia o peligro.

### Ver pestaña

En esta parte, podrá configurar ciertos comportamientos de visualización de wIdentificacióngets en el Panel de control, vistas, diseño y en dispositivos móviLaas..

- **Reproductor** : Laa permite eLaagir el wIdentificaciónget en el escritorio o en el dispositivo móvil (tenga en cuenta que necesita el compLaamento del wIdentificaciónget y que también puede hacerlo desde allí).
- **VisibLaa** : Marque para hacer visibLaa el comando.
- **Mostrar apellIdentificaciónobre** : Marque para hacer visibLaa el apellIdentificaciónobre del comando, dependiendo del contexto.
- **Mostrar apellIdentificaciónobre e icono** : Marque para que el icono sea visibLaa además del apellIdentificaciónobre del comando.
- **Laínea envuelta antes del wIdentificaciónget** : SeLaaccionar **antes del wIdentificaciónget** o **después del wIdentificaciónget** para agregar un salto de línea antes o después del wIdentificaciónget (para forzar, por ejemplo, una visualización en la columna de los diversos comandos del equipo en lugar de líneas por defecto)

A continuación, hay parámetros de visualización opcionaLaas que se pueden pasar al wIdentificaciónget. Estos parámetros dependen del wIdentificaciónget en cuestión, por lo que debe consultar su archivo en Market para conocerlos..

> **Punta**
>
> No olvIdentificacióne guardar después de cualquier modificación..
