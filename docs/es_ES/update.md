Descripción 
===========

La **centro de actualizaciones** le permite actualizar todo
Características de Jeedom, incluido el software central,
complementos, widgets, etc. Otras funciones de administración de extensiones
están disponibles (eliminar, reinstalar, verificar, etc)

La página del Centro de actualizaciones 
================================

Se puede acceder desde el menú **Administración → Centro de actualización
jour**.

Encontrará, a la izquierda, todas las funcionalidades de
Jeedom y en la parte correcta **Información** quien describe lo que el
sucedió, cuando lanzaste una actualización.

Funciones en la parte superior de la página. 
---------------------------------

En la parte superior de la tabla están los botones de control. Jeedom se
conectarse periódicamente con Market para ver si hay actualizaciones
están disponibles (la fecha de la última verificación se indica en la parte superior
izquierda de la mesa). Si desea realizar una verificación manual,
puedes presionar el botón "Buscar actualizaciones".

El botón **Poner al día** permite actualizar el conjunto de
Jeedom. Una vez que hace clic en él, obtenemos estos diferentes
opciones :

-   **Ahorre antes** : Copia de seguridad de Jeedom antes
    realizar la actualización.

-   **Actualizar complementos** : Le permite incluir complementos en
    actualización.

-   **Actualiza el núcleo** : Le permite incluir el núcleo Jeedom en
    la actualización.

-   **Modo forzado** : Actualización en modo forzado, es decir
    que, incluso si hay un error, Jeedom continúa y no restaurará
    la salvaguardia.

-   **Actualización para volver a aplicar** : Le permite volver a aplicar una apuesta
    al día. (NB : No se pueden volver a aplicar todas las actualizaciones.)

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

La tabla de actualización 
---------------------------

La tabla consta de dos pestañas :

-   **Núcleo y complementos** : Contiene software básico Jeedom y
    lista de complementos instalados.

-   **Otro** : Contiene widgets, scripts, etc.

Encontrará la siguiente información : \* **Estatus** : OK o NOK.
Le permite conocer el estado actual del complemento. \* **Apellido** : Tu ahi
encuentre la fuente del elemento, el tipo de elemento y su nombre. \*
**Versión** : Indica la versión específica del artículo. \* **Opciones** :
Marque esta casilla si no desea que este artículo se actualice
día durante la actualización general (Botón **Poner al día**).

> **Punta**
>
> Para cada tabla, la primera línea permite el siguiente filtro
> El estado, nombre o versión de los elementos presentes.

En cada línea, puede usar las siguientes funciones para
cada elemento :

-   **Restablecer** : Reasentamiento forzado.

-   **Remove** : Le permite desinstalarlo.

-   **Comprobar** : Consulte la fuente de actualizaciones para averiguar si
    hay una nueva actualización disponible.

-   **Poner al día** : Le permite actualizar el elemento (si tiene
    una actualización).

-   **Cambios** : Acceda a la lista de cambios en el
    actualización.

> **Importante**
>
> Si el registro de cambios está vacío pero aún tiene una actualización
> actualización significa que la documentación ha sido actualizada.
> Por lo tanto, no es necesario pedirle al desarrollador
> cambios, ya que no necesariamente hay. (a menudo es una apuesta
> de la traducción de la documentación)

> **Punta**
>
> Tenga en cuenta que &quot;núcleo : jeedom &quot;significa&quot; actualizar el software
> Base Jeedom".

Actualización de línea de comando 
================================

Es posible actualizar Jeedom directamente en SSH.
Una vez conectado, este es el comando para realizar :

    sudo php /var/www/html/install/update.php

Los posibles parámetros son :

-   **`moda`** : `force`, para iniciar una actualización en modo forzado (no
    ignorar errores).

-   **`version`** : seguido del número de versión, para volver a aplicar el
    cambios desde esta versión.

Aquí hay un ejemplo de sintaxis para hacer una actualización forzada en
volver a aplicar los cambios desde 1.188.0 :

    sudo php / var / www / html / install / update.modo php = forzar versión = 1.188.0

Atención, después de una actualización en la línea de comando, es necesario
volver a aplicar los derechos en la carpeta Jeedom :

    chown -R www-data:www-data / var / www / html
