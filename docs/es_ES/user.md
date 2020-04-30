# Usuarios
**Configuración → Sistema → Usuarios**

Esta página le permite definir la lista de usuarios autorizados para conectarse a Jeedom, así como sus derechos de administrador..

En la página tienes tres botones :

- Agregar usuario.
- Guardar.
- Acceso de soporte abierto.

## Lista de usuarios

- **Nombre del usuario** : ID de usuario.
- **Actif** : Le permite desactivar la cuenta sin eliminarla..
- **Local** : Permite la conexión del usuario solo si está en la red local de Jeedom.
- **Profil** : Le permite elegir el perfil de usuario :
    - **Administrateur** : El usuario obtiene todos los derechos (edición / consulta) sobre Jeedom.
    - **Utilisateur** : El usuario puede ver Tablero, vistas, diseños, etc.. y actuar sobre equipos / controles. Sin embargo, no tendrá acceso a la configuración de los controles / equipos ni a la configuración de Jeedom.
    - **Usuario limitado** : El usuario solo ve el equipo autorizado (configurable con el botón &quot;Derechos&quot;).
- **Clave API** : Clave API personal del usuario.
- **Doble autenticación** : Indica si la autenticación doble está activa (OK) o no (NOK).
- **Fecha de la última conexión** : Fecha de inicio de sesión del último usuario. Tenga en cuenta que esta es la fecha de conexión real, por lo que si guarda su computadora, la fecha de conexión no se actualiza cada vez que regresa.
- **Droits** : Modificar derechos de usuario.
- **Contraseña** : Le permite cambiar la contraseña del usuario.
- **Supprimer** : Eliminar usuario.
- **Regenerar clave API** : Regenerar clave de API de usuario.
- **Administrar derechos** : Le permite administrar los derechos de los usuarios con precisión (tenga en cuenta que el perfil debe ser &quot;usuario limitado&quot;).

## Gestión de derechos

Al hacer clic en &quot;Derechos&quot;, aparece una ventana que le permite administrar los derechos del usuario con precisión. La primera pestaña muestra los diferentes equipos.. El segundo presenta los escenarios..

> **Important**
>
> El perfil debe ser limitado; de lo contrario, no se tendrán en cuenta las restricciones establecidas aquí..

Obtiene una tabla que permite, para cada dispositivo y cada escenario, definir los derechos del usuario. :
- **Aucun** : el usuario no ve el equipo / escenario.
- **Visualisation** : el usuario ve el equipo / escenario pero no puede actuar sobre él.
- **Visualización y ejecución** : el usuario ve el equipo / escenario y puede actuar sobre él (enciende una lámpara, inicia el escenario, etc.).

## Sesiones activas

Muestra las sesiones del navegador activas en su Jeedom, con información del usuario, su IP y desde cuándo. Puede cerrar la sesión del usuario con el botón **Desconectar**.

## Dispositivo (s) registrado (s)

Enumere los periféricos (computadoras, móviles, etc.) que han registrado su autenticación en su Jeedom.
Puede ver qué usuario, su IP, cuándo y eliminar el registro para este dispositivo.

> **Note**
>
> El mismo usuario puede haber registrado diferentes dispositivos. Por ejemplo, su computadora de escritorio, computadora portátil, móvil, etc..







