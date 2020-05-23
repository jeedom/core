# Preferencias
**Configuración → Preferencias**

La página de Preferencias le permite configurar ciertos comportamientos de Jeedom específicos del usuario.

## Pestaña de preferencias

### Interface

Define ciertos comportamientos de interfaz

- **Panel de objetos en el tablero** : Muestra el panel de objetos (a la izquierda) en el Panel, sin tener que hacer clic en el botón dedicado.
- **Página predeterminada** : Página para mostrar de forma predeterminada para mostrar cuando se conecta a una computadora de escritorio o móvil.
- **Objeto predeterminado** : Objeto para mostrar por defecto al llegar al Tablero / móvil.

- **Vista predeterminada** : Ver para mostrar de forma predeterminada a la llegada en el Panel de control / móvil.
- **Despliegue el panel de vista** : Se usa para hacer que el menú de vista (izquierda) sea visible en las vistas de forma predeterminada.

- **Diseño por defecto** : Diseñe para mostrar por defecto a su llegada al Tablero / móvil.
- **Diseño de pantalla completa** : Visualización predeterminada en pantalla completa al llegar a los diseños.

- **Diseño 3D por defecto** : Diseño 3D para mostrar por defecto al llegar al Tablero / móvil.
- **Diseño de pantalla completa en 3D** : Visualización predeterminada en pantalla completa a la llegada en diseños 3D.

### Notifications

- **Comando de notificación del usuario** : Comando predeterminado para unirse a usted (comando de tipo de mensaje).

## Pestaña de seguridad

- **Autenticación de 2 pasos** : permite configurar la autenticación en 2 pasos (como recordatorio, es un código que cambia cada X segundos que se muestra en una aplicación móvil, escriba *autenticador de google*). Tenga en cuenta que la autenticación doble solo se solicitará para conexiones externas. Por lo tanto, para conexiones locales, no se solicitará el código.

  **Importante** si durante la configuración de la autenticación doble tiene un error, debe verificar que Jeedom (consulte la página de salud) y su teléfono estén al mismo tiempo (una diferencia de 1 minuto es suficiente para que no funcione).

- **Contraseña** : Le permite cambiar su contraseña (no olvide volver a escribirla a continuación)).

- **Hash de usuario** : Su clave de API de usuario.

-   **Ver los menús** : dile a Jeedom que muestre el panel
    izquierda, cuando existe, como recordatorio de que este panel es
    disponible en la mayoría de las páginas de complementos, así como en
    página de escenarios, interacciones, objetos.

-   **Página predeterminada** : página predeterminada para mostrar cuando
    conexión de escritorio / móvil

-   **Objeto predeterminado en el dashboard** : objeto de visualización predeterminado
    a su llegada en el tablero de instrumentos / móvil

-   **Vista predeterminada** : ver para mostrar por defecto al llegar
    el tablero de instrumentos / móvil

-   **Diseño por defecto** : diseño para mostrar por defecto cuando
    la llegada al tablero / móvil

    -   **Pantalla completa** : visualización predeterminada en pantalla completa cuando
        la llegada de los diseños

-   **Salpicadero**

    -   **Desplegar el panel de escenarios** : permite hacer visible
        de manera predeterminada, el menú de escenario (a la derecha) en el tablero

    -   **Desplegar el panel de los objetos** : permite hacer visible por
        predeterminado el menú de objetos (a la izquierda) en el tablero

-   **Vista**

    -   **Despliegue el panel de vista** : permite hacer visible por
        menú de vistas predeterminado (izquierda) en vistas

Seguridad
--------

-   **Autenticación de 2 pasos** : permite configurar
    Autenticación en 2 pasos (como recordatorio, este es un código cambiante
    aparece cada X segundos en una aplicación móvil, escriba
    Google Authenticator o Google Authenticator). Tenga en cuenta que la autenticación doble solo se solicitará para conexiones externas. Por lo tanto, para la conexión local, el código no será solicitado. Importante si durante la configuración de la autenticación doble tiene un error, compruebe que la libertad (consulte la página de estado) y su teléfono estén al mismo tiempo (una diferencia de 1 minuto es suficiente para que no funcione)

-   **Contraseña** : le permite cambiar su contraseña (no
    olvide volver a escribirlo a continuación)

-   **Hash de usuario** : su clave de API de usuario


### Sesiones activas

Aquí tiene la lista de sus sesiones conectadas actualmente, su ID, su IP, así como la fecha de la última comunicación. Al hacer clic en &quot;Desconectar&quot; esto desconectará al usuario. Atención, si está en un dispositivo registrado, esto también eliminará la grabación.

### Dispositivo registrado

Aquí encontrará la lista de todos los dispositivos registrados (que se conectan sin autenticación) a su Jeedom, así como la fecha del último uso.
Aquí puede eliminar el registro de un dispositivo. Atención, no lo desconecta, solo impedirá su reconexión automática.
