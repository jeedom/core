# Gestión de complementos
Complementos → Gestión de complementos

Esta página proporciona acceso a configuraciones de complementos.
También puede manipular los complementos, a saber : descárguelos, actualícelos y actívelos,

Por lo tanto, hay una lista de complementos en orden alfabético y un enlace al mercado.
- Los complementos deshabilitados están atenuados.
- Complementos que no están en la versión *Estable* tenemos un punto naranja delante de su nombre.

Al hacer clic en un complemento, accede a su configuración. En la parte superior, encontrará el nombre del complemento, luego entre paréntesis, su nombre en Jeedom (ID) y, finalmente, el tipo de versión instalada (estable, beta).

> **Importante**
>
> Al descargar un complemento, está deshabilitado de forma predeterminada. Entonces tienes que activarlo tú mismo.

> **Punta**
>
> Puede hacer Ctrl-clic o hacer clic en Centro para abrir su configuración en una nueva pestaña del navegador.

## Configuración del complemento

### Arriba a la derecha, algunos botones :

- **Documentación** : Permite el acceso directo a la página de documentación del complemento.
- **Cambios** : Permite ver el registro de cambios del complemento si existe.
- **Detalles** : Permite encontrar la página de complementos en el mercado.
- **Remove** : Elimina el complemento de tu Jeedom. Tenga en cuenta que esto también elimina permanentemente todo el equipo de este complemento.

### Abajo a la izquierda, hay un área **Estado** con :

- **Estatus** : Le permite ver el estado del complemento (activo / inactivo).
- **Versión** : La versión del complemento instalado.
- **Autor** : Autor del complemento.
- **Acción** : Le permite habilitar o deshabilitar el complemento.
- **Versión Jeedom** : Indica la versión mínima de Jeedom requerida para el complemento.
- **Licencia** : Indica la licencia del complemento que generalmente será AGPL.

### A la derecha, encontramos el área **Registro y monitoreo** que permite definir :

- El nivel de registros específicos del complemento (encontramos esta misma posibilidad en Administración → Configuración en la pestaña de registros, en la parte inferior de la página).
- Ver registros de complementos.
- Latido del corazón : Cada 5 minutos, Jeedom comprueba si al menos un dispositivo de complemento se ha comunicado en los últimos X minutos (si desea desactivar la funcionalidad, simplemente ponga 0).
- Reiniciar demonio : Si el latido sale mal, entonces Jeedom reiniciará el demonio.

Si el complemento tiene dependencias y / o un demonio, estas áreas adicionales se muestran debajo de las áreas mencionadas anteriormente.

### Dependencias :

- **Apellido** : Generalmente será local.
- **Estatus** : Estado de dependencia, OK o NOK.
- **Instalación** : Permite instalar o reinstalar dependencias (si no lo hace manualmente y son NOK, Jeedom se encargará de sí mismo después de un tiempo).
- **Última instalación** : Fecha de la última instalación de dependencia.

### Demonio :

- **Apellido** : Generalmente será local.
- **Estatus** : Estado del demonio, OK o NOK.
- **Configuración** : OK si se cumplen todos los criterios para que el demonio corra, o si da la causa del bloqueo.
- **(Para reiniciar** : Permite lanzar o relanzar al demonio.
- **Parar** : Se usa para detener el demonio (solo en el caso en que la administración automática esté deshabilitada).
- **Gestión automática** : Activa o desactiva la administración automática (que permite a Jeedom administrar el demonio y reiniciarlo si es necesario. A menos que se indique lo contrario, es aconsejable dejar activa la gestión automática).
- **Último lanzamiento** : Fecha del último lanzamiento del demonio.

> **Punta**
>
> Algunos complementos tienen una parte de configuración. Si este es el caso, aparecerá bajo las zonas de dependencia y demonio descritas anteriormente.
> En este caso, consulte la documentación del complemento en cuestión para saber cómo configurarlo.

### A continuación, hay un área de funcionalidad. Esto le permite ver si el complemento utiliza una de las funciones principales de Jeedom, como :

- **Interact** : Interacciones específicas.
- **Cron** : Un cron por minuto.
- **Cron5** : Un cron cada 5 minutos.
- **Cron10** : Un cron cada 10 minutos.
- **Cron15** : Un cron cada 15 minutos.
- **Cron30** : Un cron cada 30 minutos.
- **CronHourly** : Un cron cada hora.
- **CronDaily** : Un cron diario.
- **deadcmd** : Un cron para comandantes muertos.
- **salud** : Una salud cron.

> **Punta**
>
> Si el complemento utiliza una de estas funciones, puede prohibirlo específicamente desmarcando la casilla &quot;activar&quot; que estará presente al lado.

### Panel

Podemos encontrar una sección de Panel que habilitará o deshabilitará la visualización del panel en el tablero o en el dispositivo móvil si el complemento ofrece uno.

## Instalación de complementos

Para instalar un nuevo complemento, simplemente haga clic en el botón "Market" (y Jeedom está conectado a Internet). Después de un breve tiempo de carga, obtendrá la página.

> **Punta**
>
> Debe haber ingresado la información de su cuenta Market en la administración (Configuración → Actualizaciones → pestaña Market) para encontrar los complementos que ya compró, por ejemplo.

En la parte superior de la ventana tienes filtros :
- **Abierto / De pago** : muestra solo gratis o de pago.
- **Oficial / Recomendado** : muestra solo complementos oficiales o recomendados.
- **Menú desplegable de categoría** : muestra solo ciertas categorías de complementos.
- **Buscar** : le permite buscar un complemento (en el nombre o la descripción del mismo).
- **Nombre del usuario** : muestra el nombre de usuario utilizado para conectarse a Market, así como el estado de la conexión.

> **Punta**
>
> La pequeña cruz restablece el filtro en cuestión

Una vez que haya encontrado el complemento que desea, simplemente haga clic en él para que aparezca su archivo. Esta hoja le brinda mucha información sobre el complemento, en particular :

- Si es oficial / recomendado o si está obsoleto (definitivamente debe evitar instalar complementos obsoletos).
- 4 acciones :
    - **Instalar estable** : permite instalar el complemento en su versión estable.
    - **Instalar beta** : permite instalar el complemento en su versión beta (solo para betatesters).
    - **Instalar pro** : permite instalar la versión pro (muy poco utilizada).
    - **Remove** : Si el complemento está instalado actualmente, este botón le permite eliminarlo.

A continuación, encontrará la descripción del complemento, la compatibilidad (si Jeedom detecta una incompatibilidad, se lo notificará), las opiniones sobre el complemento (puede anotarlo aquí) e información adicional (el autor, la persona que realizó la última actualización, un enlace al documento, la cantidad de descargas). A la derecha encontrará un botón &quot;Registro de cambios&quot; que le permite tener todo el historial de modificaciones, un botón &quot;Documentación&quot; que se refiere a la documentación del complemento. Luego tiene el idioma disponible y la información variada sobre la fecha de la última versión estable.

> **Importante**
>
> Realmente no se recomienda poner un complemento beta en un Jeedom no beta, pueden resultar muchos problemas operativos.

> **Importante**
>
> Algunos complementos son de pago, en este caso el complemento le ofrecerá comprarlo. Una vez hecho esto, debe esperar unos diez minutos (tiempo de validación del pago), luego regresar al archivo del complemento para instalarlo normalmente.

> **Punta**
>
> También puede agregar un complemento a Jeedom desde un archivo o desde un repositorio de Github. Para hacer esto, en la configuración de Jeedom, active la función apropiada en la sección "Actualizaciones y archivos"". Entonces será posible, colocando el mouse en el extremo izquierdo y haciendo que aparezca el menú de la página del complemento, hacer clic en "Agregar desde otra fuente". Luego puede elegir la fuente "Archivo". Atención, en el caso de agregar por un archivo zip, el nombre del zip debe ser el mismo que el ID del complemento y al abrir el ZIP debe estar presente una carpeta plugin\_info.
