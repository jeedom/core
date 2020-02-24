La página Perfil le permite configurar cierto comportamiento de
Jeedom específico del usuario : temas de la página de inicio de la
versión de escritorio, versión móvil, gráficos ... Permite
también para cambiar tu contraseña.

Puede encontrarlo en la parte superior derecha haciendo clic en el icono del muñeco de nieve.
luego Perfil (seguido de su nombre de usuario).

temas
======

El panel de temas le permite ajustar los parámetros de la interfaz. :

-   **escritorio** : temas para usar en el modo de escritorio, tenga cuidado solo
    el tema predeterminado es oficialmente compatible con Jeedom

-   **Color móvil** : permite elegir el color de la interfaz
    (aquí todo es compatible)

-   **Gráficos de escritorio** : le permite definir el tema predeterminado para
    gráficos en modo escritorio

-   **Gráfico móvil** : le permite definir el tema predeterminado para
    gráficos móviles

-   **Opacidad por widgets del tablero** : permite dar opacidad
    (entre 0 y 1) widgets en el tablero

-   **Opacidad por widgets de diseño** : permite dar opacidad
    (entre 0 y 1) widgets en diseños

-   **Opacidad por widgets de vista** : permite dar opacidad (entre
    0 y 1) widgets en las vistas

-   **Opacidad por widgets móviles** : permite dar opacidad
    (entre 0 y 1) widgets móviles

interfaz
---------

Le permite definir ciertos comportamientos de interfaz :

-   **general**

    -   **Mostrar menús** : dile a Jeedom que muestre el panel
        izquierda, cuando existe, como recordatorio de que este panel es
        disponible en la mayoría de las páginas de complementos, así como en
        página de escenarios, interacciones, objetos ....

-   **Página predeterminada** : página predeterminada para mostrar cuando
    conexión de escritorio / móvil

-   **Objeto predeterminado en el tablero** : objeto de visualización predeterminado
    a su llegada en el tablero de instrumentos / móvil

-   **Vista predeterminada** : ver para mostrar por defecto al llegar
    el tablero de instrumentos / móvil

-   **Diseño por defecto** : diseño para mostrar por defecto cuando
    la llegada al tablero / móvil

    -   **Pantalla completa** : visualización predeterminada en pantalla completa cuando
        la llegada de los diseños

-   **salpicadero**

    -   **Desplegar el panel de escenarios** : permite hacer visible
        de manera predeterminada, el menú de escenario (a la derecha) en el tablero

    -   **Desdobla el panel de objetos** : permite hacer visible por
        predeterminado el menú de objetos (a la izquierda) en el tablero

-   **vista**

    -   **Despliegue el panel de vista** : permite hacer visible por
        menú de vistas predeterminado (izquierda) en vistas

seguridad
--------

-   **Autenticación de 2 pasos** : permite configurar
    Autenticación en 2 pasos (como recordatorio, este es un código cambiante
    aparece cada X segundos en una aplicación móvil, escriba
    autentificador de google). Tenga en cuenta que la autenticación doble solo se solicitará para conexiones externas. Por lo tanto, para la conexión local, el código no será solicitado. Importante si durante la configuración de la autenticación doble tiene un error, compruebe que la libertad (consulte la página de estado) y su teléfono estén al mismo tiempo (una diferencia de 1 minuto es suficiente para que no funcione)

-   **Contraseña** : le permite cambiar su contraseña (no
    olvide volver a escribirlo a continuación)

-   **Hash de usuario** : su clave de API de usuario

### Sesiones activas

Aquí tiene la lista de sus sesiones conectadas actualmente, su ID,
su IP, así como la fecha de la última comunicación. Haciendo clic en
&quot;Cerrar sesión&quot; cerrará la sesión del usuario. Atención si está encendido
un dispositivo registrado esto borrará la grabación.

### Dispositivo registrado

Aquí encontrará la lista de todos los dispositivos registrados (que son
conectarse sin autenticación) a su Jeedom y la fecha de
último uso. Aquí puede eliminar la grabación de un
periférica. Atención, no lo desconecta, solo evitará
su reconexión automática.

Notificaciones
-------------

-   **Comando de notificación del usuario** : Comando predeterminado para
    unirse a usted (comando de tipo de mensaje)
