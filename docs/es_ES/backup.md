Jeedom tiene la posibilidad de ser salvado y restaurado desde o desde
de diferentes lugares.

Configuración 
=============

Accesible desde **Administración → Copias de seguridad**, esta página permite
gestión de respaldo.

Encontrará, a la izquierda, los parámetros y botones de acción. En la
correcto, este es el estado en tiempo real de la acción actual (copia de seguridad
o restauración), si ha lanzado uno.

**Copias de seguridad** 
---------------

-   **Copias de seguridad** : Le permite iniciar una copia de seguridad manualmente y
    inmediatamente (útil si desea hacer un cambio crítico.
    Esto te permitirá volver). También tienes un
    para iniciar una copia de seguridad sin enviar el archivo al
    nube (requiere suscripción ver abajo). Enviando un
    la copia de seguridad en la nube puede llevar un tiempo. Esta opcion
    así evita una pérdida excesiva de tiempo.

-   **Ubicación de respaldo** : Indica la carpeta en la que
    Jeedom copia copias de seguridad. Se recomienda no
    cambiarlo. Si está en un camino relativo, su origen es
    donde está instalado Jeedom.

-   **Número de días de almacenamiento de copias de seguridad** : Número de
    días de respaldo para mantener. Una vez que este período ha pasado, el
    las copias de seguridad se eliminarán. Ten cuidado de no poner un número
    días demasiado altos, de lo contrario su sistema de archivos puede
    estar saturado.

-   **Tamaño total máximo de copias de seguridad (MB)** : Permite limitar
    el lugar ocupado por todas las copias de seguridad en la carpeta
    copia de seguridad. Si se excede este valor, Jeedom eliminará el
    copias de seguridad más antiguas hasta caer por debajo del
    tamaño máximo. Sin embargo, mantendrá al menos una copia de seguridad.

**Copias de seguridad locales** 
-----------------------

-   **Copias de seguridad disponibles** : Lista de copias de seguridad disponibles.

-   **Restaurar copia de seguridad** : Comienza a restaurar la copia de seguridad
    seleccionado arriba.

-   **Eliminar copia de seguridad** : Eliminar copia de seguridad seleccionada
    arriba, solo en la carpeta local.

-   **Enviar una copia de seguridad** : Le permite enviar a la
    guardar un archivo en la computadora que
    actualmente usando (permite, por ejemplo, restaurar un archivo
    previamente recuperado en un nuevo Jeedom o reinstalación).

-   **Descargar copia de seguridad** : Permite descargar a tu
    computadora el archivo de copia de seguridad seleccionado anteriormente.

**Copias de seguridad del mercado** 
----------------------

-   **Enviar copias de seguridad** : Encarga a Jeedom que envíe el
    copias de seguridad en la nube del mercado, tenga cuidado con que debe tener
    obtuve la suscripción.

-   **Enviar una copia de seguridad** : Le permite enviar un
    archivo de respaldo ubicado en su computadora.

-   **Copias de seguridad disponibles** : Lista de respaldos
    nube disponible.

-   **Restaurar copia de seguridad** : Lanza la restauración de un
    copia de seguridad en la nube.

**Copias de seguridad de Samba** 
---------------------

-   **Enviar copias de seguridad** : Encarga a Jeedom que envíe el
    copias de seguridad en el recurso compartido de samba configurado aquí
    Administración → Configuración → pestaña Actualizaciones.

-   **Copias de seguridad disponibles** : Lista de respaldos
    samba disponible.

-   **Restaurar copia de seguridad** : Comienza a restaurar la copia de seguridad
    samba seleccionada arriba.

> **Importante**
>
> Las copias de seguridad de Jeedom deben caer absolutamente en una carpeta solo para él !!! Eliminará todo lo que no sea una copia de seguridad de la carpeta


Lo que se guarda ? 
==============================

Durante una copia de seguridad, Jeedom realizará una copia de seguridad de todos sus archivos y
base de datos. Por lo tanto, esto contiene toda su configuración
(equipos, controles, historia, escenarios, diseño, etc.).

En términos de protocolos, solo el Z-Wave (OpenZwave) es un poco
diferente porque no es posible guardar las inclusiones.
Estos se incluyen directamente en el controlador, por lo que debe
mantener el mismo controlador para encontrar sus módulos Zwave.

> **Nota**
>
> El sistema en el que está instalado Jeedom no está respaldado. si
> ha modificado los parámetros de este sistema (en particular a través de SSH),
> depende de usted encontrar una manera de recuperarlos en caso de problemas.

Copia de seguridad en la nube 
================

El respaldo en la nube le permite a Jeedom enviar sus respaldos
directamente en el mercado. Esto le permite restaurarlos fácilmente
y asegúrate de no perderlos. El mercado guarda los últimos 6
copias de seguridad. Para suscribirte solo ve a tu página
**perfil** en el mercado, luego en la pestaña **mis copias de seguridad**. Vous
puede, desde esta página, recuperar una copia de seguridad o comprar un
suscripción (por 1, 3, 6 o 12 meses).

> **Punta**
>
> Puede personalizar el nombre de los archivos de respaldo desde
> de la pestaña **Mis jeedoms**, evitando sin embargo los personajes
> exótico.

Frecuencia de respaldos automáticos 
======================================

Jeedom realiza una copia de seguridad automática todos los días al mismo tiempo
hora. Es posible modificar esto, desde el &quot;Motor
tareas &quot;(la tarea se llama **Jeedom backup**), pero no lo es
recomendadas. De hecho, se calcula en relación con la carga de la
Market.
