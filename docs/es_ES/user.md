# Usuarios
**Ajustes → Sistema → Usuarios**

Esta página permite definir la lista de usuarios autorizados a iniciar sesión en Jeedom, así como sus derechos de administrador.

En la página hay tres botones:

- Añadir un usuario.
- Guardar.
- Abrir una solicitud de asistencia.

## Lista de usuarios

- **Nombre de usuario**: Identificador del usuario.
- **Activo**: Permite desactivar la cuenta sin eliminarla.
- **Local**: Permite la conexión del usuario únicamente si se encuentra en la red local de Jeedom.
- **Perfil**: Permite seleccionar el perfil del usuario:
    - **Administrador**: El usuario tiene todos los derechos (edición/consulta) en Jeedom.
    - **Usuario**: El usuario puede ver el panel de control, las vistas, los diseños, etc., y manejar los dispositivos y controles. Sin embargo, no tendrá acceso a la configuración de los controles ni de los dispositivos, ni a la configuración de Jeedom.
    - **Usuario con acceso limitado**: El usuario solo ve los dispositivos autorizados (configurable mediante el botón «Derechos»).
- **Clave API**: Clave API personal del usuario.
- **Autenticación de dos factores**: Indica si la autenticación de dos factores está activa (OK) o no (NOK).
- **Fecha de la última conexión**: Fecha de la última conexión del usuario. Atención: aquí se indica la fecha real de conexión, por lo que, si registras tu ordenador, la fecha de conexión no se actualiza cada vez que vuelves a conectarte.
- **Derechos**: Permite modificar los derechos del usuario.
- **Contraseña**: Permite cambiar la contraseña del usuario.
- **Eliminar**: Permite eliminar al usuario.
- **Regenerar clave API**: Regenera la clave API del usuario.
- **Gestionar permisos**: Permite gestionar con precisión los permisos del usuario (atención: el perfil debe estar en «usuario limitado»).

## Gestión de derechos

Al hacer clic en «Derechos», aparece una ventana que te permite gestionar con precisión los derechos del usuario. La primera pestaña muestra los distintos dispositivos. La segunda presenta los escenarios.

> **Importante**
>
> El perfil debe estar restringido; de lo contrario, no se tendrá en cuenta ninguna restricción establecida aquí.

Aparecerá una tabla que te permitirá definir los derechos del usuario para cada dispositivo y cada escenario:
- **Ninguna**: el usuario no ve el equipo ni el escenario.
- **Visualización**: el usuario ve el equipo o el escenario, pero no puede intervenir en él.
- **Visualización y ejecución**: el usuario ve el equipo o el escenario y puede interactuar con él (encender una lámpara, activar el escenario, etc.).

## Sesión(es) activa(s)

Muestra las sesiones de navegador activas en tu Jeedom, con la información del usuario, su IP y desde cuándo. Puedes desconectar al usuario con el botón **Desconectar**.

## Dispositivo(s) registrado(s)

Muestra los dispositivos (ordenadores, móviles, etc.) que se han autenticado en tu Jeedom.
Puedes ver qué usuario, su dirección IP y en qué fecha, y eliminar el registro de ese dispositivo.

> **Nota**
>
> Un mismo usuario puede tener registrados varios dispositivos. Por ejemplo, su ordenador de sobremesa, su portátil, su móvil, etc.







