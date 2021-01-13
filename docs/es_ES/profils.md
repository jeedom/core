# Preferencias
**Configuración → Preferencias**

La página de Preferencias le permite configurar ciertos comportamientos de Jeedom específicos del usuario.

## Pestaña de preferencias

### Interface

Define ciertos comportamientos de interfaz

- **Página predeterminada** : Página que se mostrará de forma predeterminada al conectarse a una computadora de escritorio o un dispositivo móvil.
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

- **Autenticación de 2 pasos** : permite configurar la autenticación en 2 pasos (como recordatorio, es un código que cambia cada X segundos que se muestra en una aplicación móvil, escriba *autenticador de google*). Tenga en cuenta que solo se solicitará la doble autenticación para conexiones externas. Por lo tanto, para conexiones locales, no se solicitará el código.

  **Importante** si durante la configuración de la doble autenticación tienes un error, es necesario comprobar que Jeedom (ver en la página de salud) y tu teléfono están bien al mismo tiempo (1 min de diferencia es suficiente para que no funcione).

- **Contraseña** : Le permite cambiar su contraseña (no olvide volver a escribirla a continuación)).

- **Hash de usuario** : Tu clave de API de usuario.

### Sesiones activas

Aquí tiene la lista de sus sesiones conectadas actualmente, su ID, su IP, así como la fecha de la última comunicación. Al hacer clic en &quot;Desconectar&quot; esto desconectará al usuario. Tenga cuidado si está en un dispositivo registrado, esto también eliminará el registro.

### Dispositivos registrados

Aquí encontrará la lista de todos los dispositivos registrados (que se conectan sin autenticación) a su Jeedom, así como la fecha del último uso.
Aquí puede eliminar el registro de un dispositivo. Atención, no lo desconecta, solo impedirá su reconexión automática.
