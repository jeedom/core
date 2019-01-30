descripción
===========

La actualización ** ** centro permite actualizar todos
Jeedom características, incluyendo software básico (core), el
plugins, widgets, etc. Otras extensiones de las funciones de gestión
son disponible (quitar, reinstalar, cheque, etc.)

Página Centro de Actualización
================================

Se puede acceder desde el menú de administración ** → hacer Center
día**.

Se encuentra, a la izquierda, todas las características de
Jeedom y en la parte derecha ** ** información que describe lo
sucedido, cuando se puso en marcha una actualización.

Las funciones de la parte superior de la página.
---------------------------------

La parte superior de la tabla, son los botones de control. Jeedom es
se conecta periódicamente con el mercado para ver si las actualizaciones
están disponibles (fecha de la última verificación es en la parte superior
izquierdo de la tabla). Si desea realizar una comprobación manual,
puede pulsar el botón "Buscar actualizaciones".

El botón **Actualización** permite actualizar todos
Jeedom. Una vez que haya hecho clic en él, obtenemos estos
opciones:

-   **Guardar** antes: Realizar una copia de seguridad antes Jeedom
    para realizar la actualización.

-   **Actualización** plugins: Se utiliza para incluir plugins en la
    actualización.

-   **Actualización** núcleo: si se debe incluir en el núcleo Jeedom
    la actualización.

-   ** ** modo forzado: realiza la actualización en modo forzado, es decir,
    que incluso si hay un error, Jeedom continúa y no restaurará
    la salvaguardia.

-   **Actualización** volver a aplicar: Para volver a aplicar un conjunto
    al día. (Nota: Todas las actualizaciones se pueden volver a aplicar.)

> **Importante**
>
> Antes de una actualización por defecto Jeedom hará una copia de seguridad. en
> Caso se refieren a la hora de aplicar una actualización, se Jeedom
> Restaurar automáticamente la copia de seguridad realizada inmediatamente antes. este principio
> ¿Es válida para las actualizaciones y no Jeedom plugins.

> **Tip**
>
> Puede forzar una actualización de Jeedom, incluso si se hace
> No ofrecer.

Las actualizaciones de la tabla
---------------------------

La mesa consta de dos pestañas:

-   **básicas y complementos** Contiene software básico y Jeedom
    lista de plugins instalados.

-   **Otros**: tiene widgets, scripts, etc.

Encontrará la siguiente información: \ * ** ** Estado: OK o NOK.
Se utiliza para determinar el estado actual del plugin. \ * ** ** nombre: Usted va
encontrar la fuente del elemento, el tipo de elemento y el nombre. \ *
**** Versión: Muestra la versión exacta del artículo. \ *** ** Opciones:
Marque esta casilla si no desea que esto se hizo
día durante la actualización general (botón **Actualización**).

> **Tip**
>
> Para cada tabla, la primera línea permite siguiente filtro
> El estado, el nombre o la versión de estos elementos.

En cada línea, puede utilizar las siguientes funciones
cada elemento:

-   Vuelva a instalar ** **: Fuerza reasentamiento.

-   ** ** Eliminar: desinstalar el.

-   ** ** Check: Consulta el origen de las actualizaciones de si
    una nueva actualización disponible.

-   **Actualización**: Para actualizar el elemento (si tiene
    una actualización).

-   ** ** Cambios: Permite acceder a la lista de cambios
    actualización.

> **Importante**
>
> Si el registro de cambios está vacío, pero hay todavía una actualización
> Día, quiere decir que esta es la documentación se ha actualizado.
> No es necesario pedir al desarrollador de la
> Los cambios, ya que no tiene necesariamente. (Esto es a menudo una apuesta
> Actualización de la traducción de la documentación)

> **Tip**
>
> Tenga en cuenta que "núcleo: jeedom" significa "actualizar software
> Jeedom básico".

línea de comandos actualizada
================================

Es posible hacer una actualización directamente Jeedom SSH.
Una vez conectado, este es el comando para realizar:

    sudo php /var/www/html/install/update.php

Los ajustes posibles son:

-   **`modo`** : `forzado`, para ejecutar una actualización en modo forzado (no 
    hace caso omiso de los errores).

-   **`version`** seguido por el número de versión para volver a aplicar
    cambios desde esta versión.

Un ejemplo de sintaxis para una actualización forzada en
volver a aplicar los cambios desde 1.188.0:

    sudo php /var/www/html/install/update.php versión mode = fuerza = 1.188.0

Atención después de una necesidad de actualización en línea de comandos
volver a aplicar los derechos a la carpeta Jeedom:

    chown -R www-data: www-data / var / www / html
