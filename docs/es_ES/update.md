Descripción 
===========

La **centro de actualizaciones** le permite actualizar todo
Características de Jeedom, incluido el software central,
complementos, widgets, etc.. Otras funciones de administración de extensiones
están disponibles (eliminar, reinstalar, verificar, etc.)

La página del Centro de actualizaciones 
================================

Se puede acceder desde el menú. **Configuración → Sistema → Centro de actualizaciones
día** y consta de 3 pestañas y una parte superior.

Funciones en la parte superior de la página.. 
---------------------------------

En la parte superior de la página, independientemente de la pestaña, se encuentran los botones de control. 
Jeedom se conecta periódicamente al mercado para ver si hay actualizaciones
están disponibles (la fecha de la última verificación se indica en la parte superior
a la izquierda de la página). Si desea realizar una verificación manual,
puedes presionar el botón "Buscar actualizaciones".

El botón **Poner al día** permite actualizar el conjunto de
Jeedom. Una vez que hace clic en él, obtenemos estos diferentes
opciones :
-   **Pre-actualización** : La permite actualizar el script de actualización antes
    aplicaciones de nuevas actualizaciones.

-   **Ahorre antes** : Copia de seguridad de Jeedom antes
    realizar la actualización.

-   **Actualizar complementos** : La permite incluir complementos en
    actualización.

-   **Actualiza el núcleo** : La permite incluir el núcleo Jeedom en
    la actualización.

-   **Modo forzado** : Actualización en modo forzado, es decir
    que, incluso si hay un error, Jeedom continúa y no restaurará
    la salvaguardia. (¡Este modo desactiva el guardado!)

-   **Actualización para volver a aplicar** : La permite volver a aplicar una apuesta.
    al día. (NB : No todas las actualizaciones se pueden volver a aplicar).

> **Importante**
>
> Antes de una actualización, por defecto, Jeedom hará una copia de seguridad. en
> si hay un problema al aplicar una actualización, Jeedom lo hará
> restaurar automáticamente la copia de seguridad realizada justo antes. Este principio
> solo es válido para las actualizaciones de Jeedom y no para los complementos.

> **Punta**
>
> Puede forzar una actualización de Jeedom, incluso si no lo hace
> no ofrezcas ninguna.

Las pestañas Core y Plugins y la pestaña Otros
------------------------------------------

Estas dos pestañas similares consisten en una tabla :

-   **Núcleo y complementos** : Contiene el software básico de Jeedom (núcleo) y el
    lista de complementos instalados.

-   **Otro** : Contiene widgets, scripts, etc..

Encontrará la siguiente información : \* **Estatus** : OK o NOK.
Permite conocer el estado actual del complemento. \* **Apellido** : Tu ahi
encuentre la fuente del elemento, el tipo de elemento y su nombre. \*
**Versión** : Indica la versión específica del artículo.. \* **Opciones** :
Marque esta casilla si no desea que este artículo se actualice.
día durante la actualización general (Botón **Poner al día**).

> **Punta**
>
> Para cada tabla, la primera línea permite el siguiente filtro
> el nombre de los elementos presentes.

En cada línea, puede usar las siguientes funciones para
cada elemento :

-   **Restablecer** : Reasentamiento forzado.

-   **Remove** : La permite desinstalarlo.

-   **Comprobar** : Consulte la fuente de actualizaciones para averiguar si
    hay una nueva actualización disponible.

-   **Poner al día** : La permite actualizar el elemento (si tiene
    una actualización).

-   **Cambios** : Acceda a la lista de cambios en el
    actualización.

> **Importante**
>
> Si el registro de cambios está vacío pero aún tiene una actualización
> actualización significa que la documentación ha sido actualizada.
> Por lo tanto, no es necesario pedirle al desarrollador
> cambios, ya que no necesariamente hay. (a menudo es una apuesta
> traducción, documentación)

> **Punta**
>
> Tenga en cuenta que &quot;núcleo : jeedom &quot;significa&quot; actualizar el software
> Base Jeedom".

Pestaña Registros
-----------

Pestaña a la que cambia automáticamente al instalar
actualización, le permite seguir todo lo que sucede durante la actualización
actualizado con core, como complementos.


Actualización de línea de comando 
================================

Es posible actualizar Jeedom directamente en SSH.
Una vez conectado, este es el comando para realizar :

    sudo php /var/www/html/install/update.php

Los posibles parámetros son :

-   **`mode`** : `force`, pour lancer une actualización en mode forcé (ne
    ignora los errores).

-   **`version`** : seguido del número de versión, para volver a aplicar el
    cambios desde esta versión.

Aquí hay un ejemplo de sintaxis para hacer una actualización forzada en
volver a aplicar los cambios desde 3.2.14 :

    sudo php / var / www / html / install / update.modo php = versión forzada = 3.2.14

Atención, después de una actualización en la línea de comando, es necesario
volver a aplicar los derechos en la carpeta Jeedom :

    chown -R www-data:www-data / var / www / html
