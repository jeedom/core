# Utilisateurs
**Configuración → Sistema → Usuarios**

Esta página le permite definir la lista de usuarios autorizados para conectarse a Jeedom, así como sus derechos de administrador.

En la página tienes tres botones :

- Añadir un usuario.
- Guardar.
- Acceso de soporte abierto.

## Lista de usuarios

- **Nombre del usuario** : ID de usuario.
- **Bienes** : Le permite desactivar la cuenta sin eliminarla.
- **Local** : Permite la conexión del usuario solo si está en la red local de Jeedom.
- **Perfil** : Permite elegir el perfil de usuario :
    - **Administrador** : El usuario obtiene todos los derechos (edición / consulta) sobre Jeedom.
    - **Usuario** : El usuario puede ver Tablero, vistas, diseños, etc. y actuar sobre equipos / controles. Sin embargo, no tendrá acceso a la configuración de los controles / equipos ni a la configuración de Jeedom.
    - **Usuario limitado** : El usuario solo ve el equipo autorizado (configurable con el botón "Derechos"").
- **Clave API** : Clave de API personal del usuario.
- **Doble autenticación** : Indica si la autenticación doble está activa (OK) o no (NOK).
- **Fecha de la última conexión** : Fecha de inicio de sesión del último usuario. Tenga en cuenta que esta es la fecha de conexión real, por lo que si guarda su computadora, la fecha de conexión no se actualiza cada vez que regresa.
- **Derechos** : Modificar derechos de usuario.
- **Contraseña** : Le permite cambiar la contraseña del usuario.
- **Remove** : Eliminar usuario.
- **Regenerar clave API** : Regenera la clave API del usuario.
- **Administrar derechos** : Le permite administrar con precisión los derechos de los usuarios (tenga en cuenta que el perfil debe estar en "usuario limitado"").

## Gestión de derechos

Al hacer clic en &quot;Derechos&quot;, aparece una ventana que le permite administrar los derechos del usuario con precisión. La primera pestaña muestra los diferentes equipos. El segundo presenta los escenarios.

> **Importante**
>
> El perfil debe ser limitado; de lo contrario, no se tendrán en cuenta las restricciones establecidas aquí.

Obtiene una tabla que permite, para cada dispositivo y cada escenario, definir los derechos del usuario :
- **No** : el usuario no ve el equipo / escenario.
- **Visualización** : el usuario ve el equipo / escenario pero no puede actuar sobre él.
- **Visualización y ejecución** : el usuario ve el equipo / escenario y puede actuar sobre él (encender una lámpara, iniciar el escenario, etc).

## Sesiones activas))

Muestra las sesiones del navegador activas en su Jeedom, con información del usuario, su IP y desde cuándo. Puede cerrar la sesión del usuario con el botón **Desconectar**.

## Dispositivo (s) registrado (s))

Enumere los periféricos (computadoras, móviles, etc.) que han registrado su autenticación en su Jeedom.
Puede ver qué usuario, su IP, cuándo y eliminar el registro para este dispositivo.

> **Nota**
>
> El mismo usuario puede haber registrado diferentes dispositivos. Por ejemplo, su computadora de escritorio, computadora portátil, móvil, etc.







