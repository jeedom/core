# Preferencias
**Configuración → Preferencias**

La página «Preferencias» te permite configurar determinados comportamientos de Jeedom específicos para cada usuario.

## Pestaña «Preferencias»

### Interfaz

Define determinados comportamientos de la interfaz

- **Página predeterminada**: Página que se mostrará por defecto al iniciar sesión desde un ordenador o un dispositivo móvil.
- **Objeto por defecto**: Objeto que se mostrará por defecto al acceder al panel de control o al móvil.

- **Vista predeterminada**: Vista que se mostrará por defecto al acceder al panel de control o a la aplicación móvil.
- **Desplegar el panel de vistas**: Permite que el menú de vistas (a la izquierda) aparezca visible por defecto en las vistas.

- **Diseño predeterminado**: Diseño que se mostrará por defecto al acceder al panel de control o a la aplicación móvil.
- **Diseño a pantalla completa**: visualización predeterminada a pantalla completa al acceder a los diseños.

- **Diseño 3D predeterminado**: Diseño 3D que se mostrará por defecto al acceder al panel de control o a la aplicación móvil.
- **Diseños en 3D a pantalla completa**: Visualización predeterminada a pantalla completa al acceder a los diseños en 3D.

### Notificaciones

- **Comando de notificación al usuario**: comando predeterminado para ponerse en contacto contigo (comando de tipo mensaje).

## Pestaña «Seguridad»

- **Autenticación en dos pasos**: permite configurar la autenticación en dos pasos (a modo de recordatorio, se trata de un código que cambia cada X segundos y que aparece en una aplicación móvil, como *Google Authenticator*). Cabe destacar que la autenticación de dos pasos solo se solicitará para las conexiones externas; por lo tanto, para las conexiones locales no se pedirá el código.

**Importante**: si al configurar la autenticación de dos factores se produce un error, comprueba que Jeedom (consulta la página de estado) y tu teléfono tengan la misma hora (una diferencia de tan solo 1 minuto es suficiente para que no funcione).

- **Contraseña**: Te permite cambiar tu contraseña (no olvides volver a escribirla en el campo de abajo).

- **Hash del usuario**: Tu clave API de usuario.

### Sesiones activas

Aquí tienes la lista de tus sesiones actualmente conectadas, su ID, su dirección IP y la fecha de la última comunicación. Al hacer clic en «Desconectar», se desconectará al usuario. Atención: si está conectado desde un dispositivo registrado, esto también eliminará el registro.

### Dispositivos registrados

Aquí encontrarás la lista de todos los dispositivos registrados (que se conectan sin autenticación) en tu Jeedom, así como la fecha de su último uso.
Aquí puedes eliminar el registro de un dispositivo. Ten en cuenta que esto no lo desconecta, sino que simplemente impedirá que se vuelva a conectar automáticamente.
