Jeedom offre la possibilité d’être sauvegardé et restauré depuis ou à partir
de différents emplacements.

configuración
=============

Accessible depuis **Réglages → Système → Sauvegardes**, cette page permet la
gestion des sauvegardes, restaurations.

Se encuentra, a la izquierda, los parámetros y los botones de acción. En la
Derecha, este es el estado en tiempo real de la acción actual (copia de seguridad
o restauración), si se puso en marcha una.

** ** Respaldo
---------------

-   ** ** Las copias de seguridad: inicia una copia de seguridad de forma manual
    inmediatamente (útil si desea hacer un cambio fundamental.
    Esto le permitirá volver). Usted también tiene una
    botón para iniciar una copia de seguridad sin necesidad de enviar el archivo en el
    Cloud (requiere suscripción véase más adelante). el envío de una
    copia de seguridad en la nube puede llevar algún tiempo. esta opción
    evitando así una pérdida de demasiado tiempo.

-   ** ** Ubicación copias de seguridad: Especifica la carpeta
    Jeedom copiar las copias de seguridad. No se recomienda
    cambiar. Si usted es una ruta relativa, su origen es
    donde instaló Jeedom.

-   **Número de días (s) para el almacenamiento de copias de seguridad**: Número de
    Días para guardar copias de seguridad. Una vez que este período de tiempo, la
    Se eliminarán las copias de seguridad. Tenga cuidado de no poner un número
    de días demasiado alto, de lo contrario el sistema de archivos puede
    estar saturado.

-   **tamaño total máximo de copias de seguridad (MB)**: Restringe
    el espacio ocupado por todas las copias de seguridad en el archivo
    copia de seguridad. Si se supera este valor, se eliminará Jeedom
    las copias de seguridad más antiguas a caer por debajo de la
    tamaño máximo. Sin embargo, mantener al menos una copia de seguridad.

** ** Las copias de seguridad locales
-----------------------

-   ** ** Las copias de seguridad disponibles: Lista de copias de seguridad disponibles.

-   ** ** Restaurar copia de seguridad: Se inicia la restauración de la copia de seguridad
    seleccionado anteriormente.

-   ** ** Eliminar copia de seguridad: Elimina la copia de seguridad seleccionada
    anteriormente, sólo en la carpeta local.

-   ** ** Email Backup: Envía el archivo en el
    copias de seguridad de un archivo ubicado en el equipo que es
    utiliza actualmente (por ejemplo, permite restaurar un archivo
    previamente recuperado de un nuevo Jeedom o reubicación).

-   ** ** Descargar copia de seguridad: Le permite descargar su
    Archivo de la computadora de la copia de seguridad seleccionada terminado.

** ** Mercado de copia de seguridad
----------------------

-   **Envío de copias de seguridad** Indica enviar el Jeedom
    copias de seguridad en la nube de Mercado, se debe tener
    tomó la suscripción.

-   ** ** Email Backup: Envía una nube
    Archivo de copia de seguridad en su ordenador.

-   ** ** Disponible copias de seguridad: la lista de copias de seguridad
    Nube disponibles.

-   ** ** Restaurar copia de seguridad: Se inicia la restauración de una
    copia de seguridad en la nube.

Las copias de seguridad Samba ** **
---------------------

-   **Envío de copias de seguridad** Indica enviar el Jeedom
    copias de seguridad en la parte de la samba establecen aquí
    Administración → Configuración → Actualizaciones Tab.

-   ** ** Disponible copias de seguridad: la lista de copias de seguridad
    Samba disponibles.

-   ** ** Restaurar copia de seguridad: Se inicia la restauración de la copia de seguridad
    samba seleccionado anteriormente.

> **Tip**
>
> Selon ce qui sera activé, dans la page
> Administration→Configuration→Onglet Mises à jour, vous pouvez voir
> plus ou moins de sections.

> **Tip**
>
> Lors d’une réinstallation de Jeedom et en ayant pris l’abonnement de
> sauvegarde vers le cloud du Market, vous devez renseigner votre compte
> Market sur votre nouveau Jeedom (Administration→Configuration→Onglet
> Mises à jour) puis venir ici pour lancer la restauration.

> **Tip**
>
> Il est possible, en cas de soucis, de faire une sauvegarde en ligne de
> commande : `sudo php /usr/share/nginx/www/jeedom/install/backup.php`
> ou `sudo php /var/www/html/install/backup.php` selon votre système.

> **Tip**
>
> Il est possible aussi de restaurer une sauvegarde en ligne de
> commandes (par défaut, Jeedom restaure la sauvegarde la plus récente
> présente dans le répertoire de sauvegarde) :
> `sudo php /usr/share/nginx/www/jeedom/install/restore.php` ou
> `sudo php /var/www/html/install/restore.php`.

Lo que se ahorra?
==============================

Durante una copia de seguridad, Jeedom será una copia de seguridad de todos los archivos y
base de datos. De manera que contiene toda la configuración
(Equipamiento, órdenes, escenarios históricos, diseño, etc.).

En cuanto a los protocolos, sólo el Z-Wave (OpenZwave) es un poco
diferente, porque no es posible guardar las inclusiones.
Se incluyen directamente en el controlador, por lo que
mantener el mismo controlador para encontrar sus módulos Zwave.

> **Nota**
>
> El sistema instalado en él Jeedom no se guarda. si
> ¿Ha cambiado los parámetros del sistema (especialmente a través de SSH)
> Es para usted para encontrar una manera de conseguir que en caso de problemas.

copia de seguridad de la nube
================

La copia de seguridad de la nube permite Jeedom enviar las copias de seguridad
directamente en el mercado. Esto le permite restaurar fácilmente
y asegúrese de no perderlos. El mercado conserva el último 6
copias de seguridad. Para suscribirse sólo tiene que ir a su página
**** perfil en el mercado, entonces, en mis copias de seguridad** ** pestaña. Usted
puede, desde esta página, recoger una copia de seguridad o comprar
suscripción (para 1, 3, 6 o 12 meses).

> **Tip**
>
> Usted puede personalizar el nombre de los archivos de copia de seguridad de
> Ficha ** ** Mi Jeedoms, pero evite caracteres
> Exótico.

Frecuencia copias de seguridad automáticas
======================================

Jeedom realiza una copia de seguridad automática de todos los días a la misma
hora. Es posible cambiarlo, vaya a la "Engine
tareas "(la tarea se denomina copia de seguridad ** ** Jeedom), pero esto no es
recomendadas. De hecho, se calcula en relación a la carga
Mercado.
