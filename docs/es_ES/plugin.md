El submenú Gestión de plugins le permite manipular plugins, para
poder : descargarlos, actualizarlos y activarlos,....

Plugin de gestión
===================

Puede acceder a la página de complementos de gestión de plugins →
plugins. Una vez que se hace clic, la lista de encontrar
plugins en orden alfabético y un enlace para el mercado. plugins
personas con discapacidad se atenúan.

> **Tip**
>
> Al igual que en muchos lugares en Jeedom, poner el ratón mientras la izquierda
> Para que aparezca un menú de acceso rápido (se puede
> Su perfil en dejar la siempre visible). A continuación, el menú
> Para obtener la lista de plugins ordenados por categorías.

Al hacer clic en un plugin, puede acceder a su configuración. En la parte superior, usted
puede buscar el nombre del plugin, luego entre paréntesis, su nombre en Jeedom
(ID) y finalmente, el tipo de versión instalada (estable, beta).

> **Importante**
>
> Cuando se descarga un plugin, éste esta desactivado de forma predeterminada.
> Por lo tanto, debe activarlo usted mismo.

Arriba a la derecha, un par de botones:

-   ** ** Documentación: Permite el acceso directo a la página
    documentación para plugins

-   ** ** Cambios: Para ver el plugin si hay cambios

-   ** ** Enviar en el mercado: enviar el plugin en el mercado
    (Sólo está disponible si usted es el autor)

-   ** ** Detalles: recupera la página del plugin en el mercado

-   **Borrar**: Borra el plugin de tu Jeedom. Atención, este
    También elimina de forma permanente todas las instalaciones de este plugin

Abajo a la izquierda, hay una zona estado con :

-   ** ** Estado: muestra el estado del plugin (activo / inactivo)

-   ** ** Versión: la versión del plug-in instalado

-   **Autor** : el autor del plugin

-   **Acción** : Permite activar o desactivar el plugin

-   **Versión Jeedom** : Indica la versión mínima requerida de Jeedom
    para el plugin

-   **Licencia** : Indica la licencia del plugin que generalmente será
    AGPL

A la derecha, encontramos el área de Log que nos permite definir

-   el nivel de registro específico del plugin (encontramos esta misma posibilidad en
Administration → Configuración en la pestaña registros, en la parte inferior de la página)

-   para ver los registros del plugin

-   Heartbeat: cada 5 minutos, Jeedom comprueba si al menos un dispositivo del plugin se ha comunicado en los últimos X minutos (si quieres deshabilitar la función, pon 0).

-   Reiniciar el demonio: si el heartbeat da error, Jeedom reiniciará el demonio.

Si el plugin tiene dependencias y/o un demonio, estas zonas
se muestran debajo de las áreas listadas arriba.

Dependencias :

-   **Nombre** : normalmente será local

-   **Estado** : le dirá si el deamon es OK o KO

-   **Instalación**: instalará o reinstalará las
    dependencias (si no lo haces manualmente y esta
    KO, Jeedom se encargará de ello después de un tiempo)

-   **Última instalación** : fecha de la última instalación de las
    dependencias

Deamon : 

-   **Nombre** : normalmente será local

-   **Estado** : te dirá si el demonio está OK o KO

-   **Configuración**: estará OK si todos los criterios para que el demonio
    corre son correcto o producen la causa del bloqueo

-   **(Re)Iniciar** : permite iniciar o reiniciar el demonio

-   **Parar**: te permite detener al demonio (Sólo si
    la gestión automática está desactivada)

-   **Gestión automática** : permite activar o desactivar la gestión
    automática (lo que permite a Jeedom gestionar el demonio y el
    reinicio si es necesario. A menos que se indique lo contrario, es aconsejable
    dejar la gestión automática activa)

-   **Último lanzamiento** : fecha del último lanzamiento del demonio

> **Tip**
>
> Algunos plugins tienen una parte de configuración. Si este es el caso,  debe
> aparecer bajo las dependencias y áreas de demonio descritas anteriormente.
> En este caso, es necesario consultar la documentación del plugin.
> pregunta para saber cómo configurarlo.

Debajo, hay un área funcional. Esta le permite ver
si el plugin utiliza una de las funciones principales de Jeedom, tales como :

-   **Interacción** : interacciones específicas

-   **Cron** : un cron por minuto

-   **Cron5** : un cron cada 5 minutos

-   **Cron15** : un cron cada 15 minutos

-   **Cron30** : un cron cada 30 minutos

-   **CronHourly** : un cron cada hora

-   **CronDaily** : un cron diario

> **Tip**
>
> Si el plugin utiliza una de estas funciones, usted puede específicamente
> prohibirlo desmarcando la casilla "activar", que está
> presente junto a él.

Por último, hay una sección del Panel que le permitirá activar o desactivar
la pantalla del panel en el dashboard o en el móvil si lo
ofrece el plugin.

Installation de un plugin 
========================

Para instalar un nuevo plugin, simplemente haga clic en el botón
"Market" (Jeedom debe estar conectado a Internet). Después de un corto período de tiempo de
cargando, obtendrá la página.

> **Tip**
>
> Debe haber ingresado la información de su cuenta de Market en
> administración (Configuration→Actualizaciones→Pestaña market) con el fin de
> encontrar los plugins que ya ha comprado, por ejemplo.

En la parte superior de la ventana, hay filtros:

-   **Gratis/Pago** : permite mostrar solamente los gratis o
    los de pago.

-   **Oficial/Aconsejado** : permite mostrar solamente los plugins
    oficiales o recomendados

-   **Instalado/No instalado** : permite mostrar solamente los plugins
    instalados o no instalados

-   **Menú desplegable de categoría** : le permite mostrar sólo
    algunas categorías de plugins

-   **Buscar** : permite buscar un plugin (por nombre o la
    descripción de este)

-   **Nombre de usuario** : muestra el nombre de usuario utilizado para la
    conexión al Market así como el estado de la conexión

> **Tip**
>
> La pequeña cruz le permite reajustar el filtro en cuestión

Una vez que haya encontrado el plugin deseado, simplemente haga clic en
el para mostrar su ficha. Esta ficha te da mucha
información sobre el plugin, incluyendo:

-   Si es oficial/recomendado u obsoleto (es realmente necesario
    evitar la instalación de plugins obsoletos)

-   4 acciones :

    -   **Instalar estable** : permite instalar el plugin en su
        versión estable

    -   **Instalar beta**: permite instalar el plugin en su
        versión beta (solo para betatesters)

    -   **Installer pro**: permite instalar la versión pro (muy
        poco utilizado)

    -   **Eliminar**: si el plugin está instalado actualmente, este
        botón permite borrarlo

A continuación, encontrará la descripción del plugin, compatibilidad
(si Jeedom detecta una incompatibilidad, te lo notificará), los avisos
en el plugin (puedes verlo aquí) e información
(el autor, la persona que hizo la última actualización
, un enlace al manual, el número de descargas). En el lado derecho
encontrará un botón "Changelog" que le permite ver todo
el historial de modificaciones, un botón "Documentación" que devuelve
a la documentación del plugin. Entonces tienes el idioma disponible
e información diversa sobre la fecha de la última versión estable.

> **Importante**
>
> Realmente no es recomendable poner un plugin beta en un sistema
> Jeedom no beta, muchos problemas de funcionamiento pueden ocurrir como
> resultado.

> **Importante**
>
> Algunos plugins no son libres, en este caso en la ficha del plugin se
> le ofrecerá comprarlo. Una vez hecho esto, tienes que esperar a que aparezca el mensaje
> 10 minutos (tiempo de validación del pago) y, a continuación, devolución
> en el archivo del plugin para instalarlo normalmente.

> **Tip**
>
> También puedes añadir un plugin a Jeedom desde un fichero o
> de un depósito de Github. Para ello, en la configuración de
> Jeedom, active la función correspondiente en la sección "Actualizaciones y
> archivos". Entonces será posible, colocando el ratón en la posición
> a la izquierda, y al abrir el menú de la página del plugin, haga clic en
> sobre "Añadir de otra fuente". A continuación, puede seleccionar la opción
> Fuente "Archivo". Atención, en el caso de la adición de un archivo
> zip, el nombre del zip debe ser el mismo que el ID del plugin y 
> cuando se abre el ZIP, debe estar presente una carpeta  plugin\_info.
