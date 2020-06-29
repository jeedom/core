El submenú de administración de complementos le permite manipular complementos, excepto
conocer : descargarlos, actualizarlos y activarlos, etc

Gestión de complementos 
===================

Puede acceder a la página de complementos desde Complementos → Administrar
plugins. Una vez que hacemos clic en él, encontramos la lista de
complementos en orden alfabético y un enlace al mercado. Complementos
deshabilitados están en gris.

> **Punta**
>
> Como en muchos lugares de Jeedom, coloca el mouse en el extremo izquierdo
> abre un menú de acceso rápido (puedes
> desde tu perfil siempre déjalo visible). Aquí el menú
> permite tener la lista de complementos ordenados por categorías.

Al hacer clic en un complemento, accede a su configuración. Arriba tu
encuentre el nombre del complemento, luego entre paréntesis, su nombre en Jeedom
(ID) y finalmente, el tipo de versión instalada (estable, beta).

> **Importante**
>
> Al descargar un complemento, está deshabilitado de forma predeterminada.
> Entonces tienes que activarlo tú mismo.

Arriba a la derecha, algunos botones :

-   **Documentación** : Permite el acceso directo a la página de
    documentación del complemento

-   **Cambios** : permite ver el registro de cambios del complemento si existe

-   **Enviar al mercado** : permite enviar el complemento en el mercado
    (solo disponible si eres el autor)

-   **Detalles** : permite encontrar la página de complementos en el mercado

-   **Remove** : Elimina el complemento de tu Jeedom. Ten cuidado, esto
    también elimina permanentemente todo el equipo de este complemento

Abajo a la izquierda, hay un área de estado con :

-   **Estatus** : Le permite ver el estado del complemento (activo / inactivo)

-   **Versión** : la versión del complemento instalado

-   **Acción** : Le permite habilitar o deshabilitar el complemento

-   **Versión Jeedom** : Versión mínima de Jeedom requerida
    para el funcionamiento del complemento

-   **Licencia** : Indica la licencia del complemento que generalmente será
    AGPL

A la derecha, encontramos la zona de registro y vigilancia que permite definir 

-   el nivel de registros específicos del complemento (encontramos esta misma posibilidad en
Administración → Configuración en la pestaña de registros, en la parte inferior de la página)

-   ver los registros del complemento

-   Latido del corazón : cada 5 minutos, Jeedom comprueba si al menos un dispositivo de complemento se ha comunicado en los últimos X minutos (si desea desactivar la funcionalidad, simplemente ponga 0)

-   Reiniciar demonio : si el latido sale mal, entonces Jeedom reiniciará el demonio

Si el complemento tiene dependencias y / o un demonio, estas áreas
adicionales se muestran debajo de las áreas mencionadas anteriormente.

Dependencias :

-   **Apellido** : generalmente será local

-   **Estatus** : le dirá si las dependencias están bien o no

-   **Instalación** : instalará o reinstalará
    dependencias (si no lo hace manualmente y son
    KO, Jeedom se cuidará después de un tiempo)

-   **Última instalación** : fecha de la última instalación de
    Dependencias

Demonio :

-   **Apellido** : generalmente será local

-   **Estatus** : te dirá si el demonio está bien o no

-   **Configuración** : estará bien si todos los criterios para el demonio
    se cumplen los turnos o darán lugar a un bloqueo

-   **(Para reiniciar** : permite lanzar o relanzar al demonio

-   **Parar** : permite detener al demonio (solo en caso
    la gestión automática está deshabilitada)

-   **Gestión automática** : permite activar o desactivar la gestión
    automático (que permite a Jeedom gestionar el demonio y el
    revivir si es necesario. A menos que se indique lo contrario, es aconsejable
    dejar activa la gestión automática)

-   **Último lanzamiento** : fecha del último lanzamiento del demonio

> **Punta**
>
> Algunos complementos tienen una parte de configuración. Si es así,
> aparecerá debajo de las dependencias y zonas de daemon descritas anteriormente.
> En este caso, consulte la documentación del complemento en
> pregunta sobre cómo configurarlo.

A continuación, hay un área de funcionalidad. Esto te permite ver
si el complemento utiliza una de las funciones principales de Jeedom, como :

-   **Interact** : interacciones específicas

-   **Cron** : un cron por minuto

-   **Cron5** : un cron cada 5 minutos

-   **Cron15** : un cron cada 15 minutos

-   **Cron30** : un cron cada 30 minutos

-   **CronHourly** : un cron cada hora

-   **CronDaily** : un cron diario

> **Punta**
>
> Si el complemento usa una de estas funciones, puede
> prohibirle que lo haga desmarcando la casilla &quot;activar&quot; que será
> presente siguiente.

Finalmente, podemos encontrar una sección de Panel que activará o
desactivar la visualización del panel en el tablero o en el dispositivo móvil si
el complemento ofrece uno.

Instalación de complementos 
========================

Para instalar un nuevo complemento, simplemente haga clic en el botón
"Market "(y que Jeedom está conectado a Internet). Después de un corto tiempo de
cargando obtendrá la página.

> **Punta**
>
> Debe haber ingresado la información de su cuenta Market en
> administración (Configuración → Actualizaciones → pestaña Mercado) para
> encuentre los complementos que ya ha comprado, por ejemplo.

En la parte superior de la ventana tienes filtros :

-   **Abierto / De pago** : muestra solo gratis o
    los pagadores.

-   **Oficial / Recomendado** : muestra solo complementos
    funcionarios o asesores

-   **Instalado / No instalado** : muestra solo complementos
    instalado o no instalado

-   **Menú desplegable de categoría** : solo muestra
    ciertas categorías de complementos

-   **Buscar** : le permite buscar un complemento (en el nombre o
    descripción de la misma)

-   **Nombre del usuario** : muestra el nombre de usuario utilizado para
    conexión al mercado y el estado de la conexión

> **Punta**
>
> La pequeña cruz restablece el filtro en cuestión

Una vez que haya encontrado el complemento que desea, simplemente haga clic en
este para traer su tarjeta. Esta hoja te da mucho
información sobre el complemento, incluido :

-   Si es oficial / recomendado o si está obsoleto (realmente necesita
    evite instalar complementos obsoletos)

-   4 acciones :

    -   **Instalar estable** : permite instalar el complemento en su
        versión estable

    -   **Instalar beta** : permite instalar el complemento en su
        versión beta (solo para probadores beta)

    -   **Instalar pro** : permite instalar la versión pro (muy
        poco usado)

    -   **Remove** : si el complemento está instalado actualmente, esto
        botón para borrarlo

A continuación, encontrará la descripción del complemento, la compatibilidad
(si Jeedom detecta una incompatibilidad, se lo notificará), los avisos
en el complemento (puedes calificarlo aquí) e información
complementario (el autor, la persona que realizó la última actualización
día, enlace al documento, número de descargas). Sobre la derecha
encontrará un botón &quot;Changelog&quot; que le permite tener todo
historial de cambios, un botón de &quot;Documentación&quot; que regresa
a la documentación del complemento. Entonces tienes el idioma disponible
y la diversa información sobre la fecha de la última versión estable.

> **Importante**
>
> Realmente no se recomienda poner un complemento beta en un
> Jeedom no beta, muchos problemas operativos pueden
> resultar.

> **Importante**
>
> Algunos complementos son de pago, en este caso la hoja del complemento
> ofrecerá comprarlo. Una vez hecho esto, espere un
> diez minutos (tiempo de validación de pago), luego regresar
> en la hoja del complemento para instalarlo normalmente.

> **Punta**
>
> También puede agregar un complemento a Jeedom desde un archivo o
> de un repositorio de Github. Esto requiere, en la configuración de
> Jeedom, active la función apropiada en &quot;Actualizaciones y
> fichiers". Entonces será posible, colocando el mouse completamente
> izquierda, y cuando aparezca el menú de la página de complementos, haga clic en
> en "Agregar desde otra fuente". Luego puedes elegir el
> fuente "Archivo". Atención, en el caso de la adición por un archivo
> zip, el nombre zip debe ser el mismo que el ID del complemento y de
> abriendo el ZIP debe estar presente una carpeta de plugin\_info.
