# Gestión de complementos
**Plugins → Gestión de plugins**

Esta página permite acceder a la configuración de los complementos.
También puedes gestionar los complementos, es decir: descargarlos, actualizarlos y activarlos, …​

Aquí encontrarás la lista de complementos ordenados alfabéticamente y un enlace a la tienda.
- Los complementos desactivados aparecen en gris.
- Los complementos que no están en versión *estable* tienen un punto naranja delante de su nombre.

Al hacer clic en un complemento, accederás a su configuración. En la parte superior encontrarás el nombre del complemento, seguido, entre paréntesis, de su nombre en Jeedom (ID) y, por último, el tipo de versión instalada (estable, beta).

> **Importante**
>
> Al descargar un complemento, este viene desactivado por defecto. Por lo tanto, debes activarlo tú mismo.

## Gestión

Aquí tienes tres botones:

- **Sincronizar Market**: Si instalas un complemento desde un navegador web en tu cuenta de Market (fuera de Jeedom), puedes forzar una sincronización para instalarlo.
- **Market**: Abre el Market de Jeedom para seleccionar un complemento e instalarlo en tu Jeedom.
- **Complementos**: Aquí puedes instalar un complemento desde una fuente de GitHub, Samba, etc.

### Sincronizar Market

Desde un navegador, entra en la página [Mercado](https://market.jeedom.com).
Inicia sesión en tu cuenta.
Haz clic en un complemento y, a continuación, selecciona *Instalar versión estable* o *Instalar versión beta* (si tu cuenta de Market lo permite).

Si tu cuenta de Market está correctamente configurada en tu Jeedom (Configuración → Actualizaciones/Market → pestaña Market), puedes hacer clic en *Sincronizar Market* o esperar a que se instale automáticamente.

### Mercado

Para instalar un nuevo complemento, basta con hacer clic en el botón «Market» (siempre que Jeedom esté conectado a Internet). Tras un breve tiempo de carga, aparecerá la página.

> **Consejo**
>
> Debes haber introducido los datos de tu cuenta del Market en el panel de administración (Configuración → Actualizaciones/Market → Pestaña Market) para poder encontrar, por ejemplo, los complementos que ya has comprado.

En la parte superior de la ventana hay unos filtros:
- **Gratuito/De pago**: permite mostrar solo los gratuitos o solo los de pago.
- **Oficial/Recomendado**: permite mostrar únicamente los complementos oficiales o los recomendados.
- **Menú desplegable «Categoría»**: permite mostrar únicamente determinadas categorías de plugins.
- **Buscar**: permite buscar un complemento (por su nombre o su descripción).
- **Nombre de usuario**: muestra el nombre de usuario utilizado para iniciar sesión en el Market, así como el estado de la conexión.

> **Consejo**
>
> La pequeña cruz permite restablecer el filtro en cuestión

Una vez que hayas encontrado el complemento que buscas, solo tienes que hacer clic en él para que aparezca su ficha. Esta ficha te ofrece mucha información sobre el complemento, entre otras cosas:

- Si es oficial/recomendado o si está obsoleto (hay que evitar a toda costa instalar complementos obsoletos).
- 4 pasos:
    - **Instalar versión estable**: permite instalar el complemento en su versión estable.
    - **Instalar versión beta**: permite instalar el complemento en su versión beta (solo para participantes en la prueba beta).
    - **Instalar versión Pro**: permite instalar la versión Pro (se utiliza muy poco).
    - **Eliminar**: si el complemento está instalado actualmente, este botón permite eliminarlo.

A continuación, encontrarás la descripción del complemento, la compatibilidad (si Jeedom detecta alguna incompatibilidad, te lo indicará), las opiniones sobre el complemento (aquí puedes puntuarlo) e información adicional (el autor, la persona que realizó la última actualización, un enlace a la documentación y el número de descargas). A la derecha encontrarás un botón «Changelog» que te permite consultar todo el historial de modificaciones, y un botón «Documentación» que te lleva a la documentación del complemento. A continuación, aparecen los idiomas disponibles y diversa información sobre la fecha de la última versión estable.

> **Importante**
>
> Realmente no es recomendable instalar un complemento beta en un Jeedom que no sea beta, ya que esto puede provocar muchos problemas de funcionamiento.

> **Importante**
>
> Algunos complementos son de pago; en ese caso, la ficha del complemento te ofrecerá la opción de comprarlo. Una vez hecho esto, hay que esperar unos diez minutos (tiempo necesario para validar el pago) y, a continuación, volver a la ficha del complemento para instalarlo normalmente.

### Complementos

Puedes añadir un complemento a Jeedom desde un archivo o desde un repositorio de GitHub. Para ello, debes activar la función correspondiente en la sección «Actualizaciones/Market» de la configuración de Jeedom.

Atención: si se añade mediante un archivo zip, el nombre del zip debe coincidir con el ID del plugin y, al abrir el ZIP, debe aparecer una carpeta llamada plugin\_info.

## Mis complementos

Al hacer clic en el icono de un complemento, se abre su página de configuración.

> **Consejo**
>
> Puedes pulsar Ctrl + clic o hacer clic con el botón central del ratón para abrir su configuración en una nueva pestaña del navegador.

### En la parte superior derecha, hay algunos botones:

- **Detalles**: Permite acceder a la página del complemento en la tienda.
- **Documentación**: Permite acceder directamente a la página de documentación del complemento.
- **Registro de cambios**: Permite ver el registro de cambios del complemento, si existe.
- **Asistencia**: Permite crear automáticamente una solicitud de ayuda en el foro.
- **Eliminar**: Elimina el complemento de tu Jeedom. Atención: esto también elimina de forma definitiva todos los dispositivos de este complemento.

### En la parte inferior izquierda hay un área de **estado** que incluye:

- **Estado**: Permite ver el estado del complemento (activo/inactivo).
- **Categoría**: la categoría del complemento, que indica en qué submenú se encuentra.
- **Autor**: El autor del complemento, enlace a la tienda y a los complementos de este autor.
- **Licencia**: Indica la licencia del complemento, que suele ser AGPL.

- **Acción**: permite activar o desactivar el complemento. El botón **Abrir** te lleva directamente a la página del complemento.
- **Versión**: La versión del complemento instalada.
- **Requisitos previos**: Indica la versión mínima de Jeedom necesaria para el complemento.


### A la derecha se encuentra la sección **Registro y supervisión**, que permite definir:

- El nivel de registros específico del complemento (esta misma opción se encuentra en Administración → Configuración, en la pestaña «Registros», al final de la página).
- Ver los registros del complemento.
- Heartbeat: Cada 5 minutos, Jeedom comprueba si al menos un dispositivo del complemento se ha comunicado en los últimos X minutos (si quieres desactivar esta función, basta con poner 0).
- Reiniciar el demonio: si el heartbeat falla, Jeedom reiniciará el demonio.

Si el complemento tiene dependencias y/o un servicio en segundo plano, estos campos adicionales aparecen debajo de los mencionados anteriormente.

### Dependencias:

- **Nombre**: Por lo general, será local.
- **Estado**: Estado de las dependencias, OK o NOK.
- **Instalación**: Permite instalar o reinstalar las dependencias (si no lo haces manualmente y están en estado NOK, Jeedom se encargará de ello automáticamente al cabo de un rato).
- **Última instalación**: Fecha de la última instalación de los elementos dependientes.

### Demonio:

- **Nombre**: Por lo general, será local.
- **Estado**: Estado del demonio, OK o NOK.
- **Configuración**: Indica «OK» si se cumplen todos los requisitos para que el demonio se ejecute, o bien muestra el motivo del bloqueo.
- **(Re)iniciar**: Permite iniciar o reiniciar el demonio.
- **Detener**: Permite detener el demonio (solo en caso de que la gestión automática esté desactivada).
- **Gestión automática**: Permite activar o desactivar la gestión automática (lo que permite a Jeedom gestionar por sí mismo el demonio y reiniciarlo si es necesario. Salvo que se indique lo contrario, se recomienda mantener activa la gestión automática).
- **Último inicio**: Fecha del último inicio del demonio.

> **Consejo**
>
> Algunos complementos tienen una sección de configuración. Si es así, aparecerá debajo de las secciones «dependencias» y «demonio» descritas anteriormente.
> En este caso, hay que consultar la documentación del complemento en cuestión para saber cómo configurarlo.

### Debajo hay una sección de funcionalidades. En ella se puede comprobar si el complemento utiliza alguna de las funciones básicas de Jeedom, como:

- **Interact**: Interacciones específicas.
- **Cron**: Un cron cada minuto.
- **Cron5**: Una tarea programada cada 5 minutos.
- **Cron10**: Una tarea programada cada 10 minutos.
- **Cron15**: Una tarea programada cada 15 minutos.
- **Cron30**: Una tarea programada cada 30 minutos.
- **CronHourly**: una tarea cron cada hora.
- **CronDaily**: una tarea cron diaria.
- **deadcmd**: Un cron para los comandos inactivos.
- **salud**: Un cron de salud.

> **Consejo**
>
> Si el complemento utiliza alguna de estas funciones, podrás desactivarla específicamente desmarcando la casilla «activar» que aparecerá junto a ella.

### Panel

Hay una sección llamada «Panel» que permite activar o desactivar la visualización del panel en el panel de control o en el móvil, si el complemento ofrece esta opción.
