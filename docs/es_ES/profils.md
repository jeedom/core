# preferencias
**Configuración → Preferencias**

La página de Preferencias le permite configurar ciertos comportamientos de Jeedom específicos del usuario.

## Pestaña de preferencias

### interfaz

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

### Notificaciones

- **Comando de notificación del usuario** : Comando predeterminado para comunicarse con usted (comando de tipo de mensaje).

## Pestaña de seguridad

- **Autenticación de 2 pasos** : le permite configurar la autenticación en 2 pasos (como recordatorio, es un código que cambia cada X segundos que se muestra en una aplicación móvil, escriba * google authentificator *). Tenga en cuenta que la autenticación doble solo se solicitará para conexiones externas. Por lo tanto, para la conexión local, el código no será solicitado. Importante si durante la configuración de la autenticación doble tiene un error, compruebe que Jeedom (consulte la página de estado) y su teléfono estén al mismo tiempo (una diferencia de 1 minuto es suficiente para que no funcione).
- **Contraseña** : Le permite cambiar su contraseña (no olvide volver a escribirla a continuación).
- **Hash de usuario** : Tu clave de API de usuario.

### Sesiones activas

Vous avez ici la liste de vos sessions actuellement connecté, leur ID, leur IP ainsi que la date de dernière communication. En cliquant sur "Déconnecter" cela déconnectera l'utilisateur. Attention si il est sur un périphérique enregistré cela supprimera également l'enregistrement.

### Périphérique enregistrés

Vous retrouvez ici la liste de tous les périphériques enregistré (qui se connecte sans authentification) à votre Jeedom ainsi que la date de dernière utilisation.
Vous pouvez ici supprimer l'enregistrement d'un périphérique. Attention cela ne le déconnecte pas mais empêchera juste sa reconnexion automatique.
