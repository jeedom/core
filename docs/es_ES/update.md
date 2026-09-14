# Centro de actualizaciones
**Ajustes → Sistema → Centro de actualizaciones**


El **centro de actualizaciones** permite actualizar todas las funcionalidades de Jeedom, incluyendo el software básico (core) y sus complementos.
Hay disponibles otras funciones de gestión de extensiones (eliminar, reinstalar, comprobar, etc.).


## Funciones de la página

En la parte superior de la página, independientemente de la pestaña, se encuentran los botones de control.

Jeedom se conecta periódicamente al Market para comprobar si hay actualizaciones disponibles. La fecha de la última comprobación aparece en la parte superior izquierda de la página.

Al abrir la página, si han pasado más de dos horas desde la última comprobación, Jeedom vuelve a realizarla automáticamente.
También puedes utilizar el botón **Comprobar actualizaciones** para hacerlo manualmente.
Si quieres realizar una comprobación manual, puedes pulsar el botón «Comprobar actualizaciones».

El botón **Guardar** debe utilizarse cuando modifiques las opciones de la tabla que aparece más abajo, para indicar que no se actualicen determinados complementos si es necesario.

## Actualizar el Core

El botón **Actualizar** permite actualizar el Core, los complementos o ambos.
Una vez que hayas hecho clic en él, aparecerán estas opciones:
- **Preactualización**: Permite actualizar el script de actualización antes de aplicar las nuevas actualizaciones. Se suele utilizar a petición del servicio de asistencia técnica.
- **Hacer una copia de seguridad antes**: Realiza una copia de seguridad de Jeedom antes de llevar a cabo la actualización. La copia de seguridad se realiza únicamente de forma local (ni en Market ni en Samba).
- **Actualizar los complementos**: Permite incluir los complementos en la actualización.
- **Actualizar el núcleo**: Permite incluir el núcleo de Jeedom (el Core) en la actualización.

- **Modo forzado**: Realiza la actualización en modo forzado, es decir, que, aunque se produzca un error, Jeedom continuará y no restaurará la copia de seguridad. (¡Este modo desactiva la copia de seguridad!).
- **Actualización que hay que volver a aplicar**: Permite volver a aplicar una actualización. (Nota: No todas las actualizaciones se pueden volver a aplicar.)

> **Importante**
>
> Antes de una actualización, por defecto, Jeedom realizará una copia de seguridad. En caso de que surja algún problema durante la aplicación de una actualización, Jeedom restaurará automáticamente la copia de seguridad realizada justo antes. Este principio solo es válido para las actualizaciones de Jeedom y no para las actualizaciones de los complementos.

> **Consejo**
>
> Puedes forzar una actualización de Jeedom, aunque este no te la proponga.

## Pestañas «Core» y «Plugins»

La tabla muestra las versiones del Core y de los complementos instalados.

Los complementos tienen una insignia junto a su nombre que indica su versión: de color verde si es *estable*, naranja si es *beta* u otro.

- **Estado**: OK o NOK.
- **Nombre**: Nombre y procedencia del complemento
- **Versión**: Indica la versión concreta del Core o del plugin.
- **Opciones**: Marca esta casilla si no deseas que este complemento se actualice durante la actualización general (botón **Actualizar**).

En cada línea, puedes utilizar las siguientes funciones:

- **Reinstalar**: Fuerza la reinstalación.
- **Eliminar**: Permite desinstalarlo.
- **Comprobar**: Consulta la fuente de actualizaciones para saber si hay alguna nueva disponible.
- **Actualizar**: Permite actualizar el elemento (si hay una actualización disponible).
- **Registro de cambios**: Permite acceder a la lista de cambios de la actualización.

> **Importante**
>
> Si el registro de cambios está vacío pero, aun así, hay una actualización, significa que se ha actualizado la documentación. Por lo tanto, no es necesario preguntar al desarrollador por los cambios, ya que no tiene por qué haberlos. (A menudo se trata de una actualización de la traducción o de la documentación).
> El desarrollador del complemento también puede, en algunos casos, realizar correcciones de errores sencillas, que no requieren necesariamente actualizar el registro de cambios.

> **Consejo**
>
> Cuando inicias una actualización, aparece una barra de progreso encima del panel. Evita realizar otras acciones durante la actualización.

## Pestaña «Sistema operativo/Paquete»

> **IMPORTANTE**
>
> Esta pestaña está reservada a usuarios avanzados y solo a ellos; el más mínimo error aquí puede ESTROPEAR tu Jeedom (sin posibilidad de recurrir al servicio de asistencia).

Esta pestaña permite ver las actualizaciones disponibles para el sistema operativo (apt) y los paquetes de Python (pip2 y pip3), así como actualizar los paquetes que lo requieran.

## Pestaña «Información»

Durante la actualización o una vez finalizada, esta pestaña permite consultar en tiempo real el registro de dicha actualización.

> **Nota**
>
> Este registro suele terminar con *[END UPDATE SUCCESS]*. Es posible que aparezcan algunas líneas de error en este tipo de registro; sin embargo, salvo que surja un problema real tras la actualización, no siempre es necesario ponerse en contacto con el servicio de asistencia por este motivo.

## Actualización mediante la línea de comandos

Es posible actualizar Jeedom directamente a través de SSH.
Una vez conectado, este es el comando que hay que ejecutar:

```sudo php /var/www/html/install/update.php```

Les paramètres possibles sont :

- **mode** : `force`, pour lancer une mise à jour en mode forcé (ne tient pas compte des erreurs).
- **version** : Suivi du numéro de version, pour ré-appliquer les changements depuis cette version.

Voici un exemple de syntaxe pour faire une mise à jour forcée en ré-appliquant les changements depuis la 4.0.04 :

```sudo php  /var/www/html/install/update.php mode=force version=4.0.04```

Atención: tras una actualización mediante la línea de comandos, hay que volver a aplicar los permisos a la carpeta de Jeedom:

```sudo chown -R www-data:www-data /var/www/html```
