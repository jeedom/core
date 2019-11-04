Es aquí donde vamos a ser capaces de definir la lista de usuarios
permitido para conectarse a Jeedom sino también sus derechos
director

Accesible en Ajustes → Sistema → Usuarios.

En la parte superior derecha tienes un botón para añadir un usuario, otro
para guardar y un botón para abrir el acceso a soporte.

A continuación tiene una tabla:

-   **Nombre de usuario**: el identificador de usuario

-   **Activo**: permite desactivar la cuenta

-   **Sólo local**: Permite la conexión del usuario
    sólo si la red local Jeedom

-   **Perfiles**: permite elegir el perfil del usuario:

    -   **Administrador**: recibe toda Jeedom derechos

    -   **Usuario**: puede ver el dashboard, las vistas, las
        diseño, etc. y que actúa sobre los controles del equipo /. En cambio,
        no tendrá acceso a los comandos de configuración / instalaciones
        ni a la configuración de Jeedom.

    -   **Usuario limitado**: el usuario sólo ve las
        equipo autorizado (configurable con el "Administrar
        los derechos")

-   **Clave API**: clave API personal del usuario

-   **Doble autenticación**: indica si la doble autenticación 
    está activo (OK) o no (NOK)

-   **Fecha de la última conexión**: fecha de la última conexión de
    el usuario Jeedom. Atención aquí es la fecha de conexión
    real, así que si se registra su ordenador, la fecha de
    la conexión no se actualiza cada vez que regrese.

-   **Cambiar la contraseña**: permite cambiar la contraseña de
    usuario

-   **Eliminar**: permite eliminar al usuario

-   **Regenerar llave API**: regenera la llave API del usuario

-   **Gestionar derechos**: permite gestionar finamente los derechos de
    el usuario (atención el perfil debe estar en
    "Usuario limitado")

Gestión de derechos
==================

Al hacer clic en "Administrar los derechos" aparece una ventana y le permite
finamente gestionar los derechos de los usuarios. La primera muestra de la ficha
los diferentes equipos. La segunda presenta los escenarios.

> **Importante**
>
> El perfil debe ser limitada o ninguna restricción puso aquí
> Se considerará

Se obtiene una tabla que, para cada dispositivo y cada
escenario, definir derechos de usuario:

-   **Ninguno**: el usuario no ve el equipo/escenario

-   **Visualización**: el usuario ve el equipo / escenario, pero
    no puede actuar sobre ella

-   **Visualización y ejecución**: El usuario ve
    Equipo / escenario y puede actuar sobre ella (encender una lámpara, lanzamiento
    la secuencia de comandos, etc.)


