Es aquí donde vamos a ser capaces de definir la lista de usuarios
permitido para conectarse a Jeedom sino también sus derechos
director

Accesible por la Administración → Usuarios.

En haut à droite vous avez un bouton pour ajouter un utilisateur, un
pour sauvegarder et un bouton pour ouvrir un accès au support.

A continuación tiene una tabla:

-   ** ** Nombre de usuario: el identificador de usuario

-   ** ** activo: inhabilitará la cuenta

-   ** ** Sólo local: Permite la conexión del usuario
    sólo si la red local Jeedom

-   ** ** Perfiles: Seleccione el perfil de usuario:

    -   Administrador ** ** recibe toda Jeedom derechos

    -   ** ** Usuario: puede ver el tablero de instrumentos, de las vistas,
        diseño, etc. y que actúa sobre los controles del equipo /. En cambio,
        no tendrá acceso a los comandos de configuración / instalaciones
        o la Jeedom configuración.

    -   ** ** limitada de usuario: el usuario sólo ve la
        equipo autorizado (configurable con el "Administrar
        los derechos")

-   ** ** clave de la API: API personal clave de usuario

-   ** ** autenticación de doble indica si la autenticación de dos factores
    está activo (OK) o no (NOK)

-   ** ** Fecha de la última conexión: Fecha de la última conexión
    el usuario Jeedom. Atención aquí es la fecha de conexión
    real, así que si se registra su ordenador, la fecha de
    la conexión no se actualiza cada vez que regrese.

-   ** ** Cambiar la contraseña: Cambiar la contraseña
    usuario

-   ** ** Eliminar: Eliminar usuario

-   ** ** clave de API regenerado: regenera la clave de la API del usuario

-   ** ** Manejo de los derechos: a finamente gestionar los derechos
    Usuario (ver los perfiles deben ser
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

-   **No**: el usuario no ve el equipo / escenario

-   ** ** Visualización: el usuario ve el equipo / escenario, pero
    no puede actuar sobre ella

-   ** ** Visualización y ejecución: El usuario ve
    Equipo / escenario y puede actuar sobre ella (encender una lámpara, lanzamiento
    la secuencia de comandos, etc.)


