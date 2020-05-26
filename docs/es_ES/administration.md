# Configuration
**Preferencias → Sistema → Configuración**

Aquí es donde se encuentran la mayoría de los parámetros de configuración.
Aunque muchos, la mayoría de los parámetros están configurados por defecto.


## Pestaña General

En esta pestaña encontramos información general sobre Jeedom :

- **Nombre de tu Jeedom** : Identifica tu Jeedom, especialmente en el mercado. Se puede reutilizar en escenarios o para identificar una copia de seguridad.
- **Lengua** : Lenguaje usado en tu Jeedom.
- **Sistema** : Tipo de hardware en el que está instalado el sistema donde se ejecuta su Jeedom.
- **Generar traducciones** : Genere traducciones, tenga cuidado, esto puede ralentizar su sistema. Opción especialmente útil para desarrolladores.
- **Fecha y hora** : Elige tu zona horaria. Puedes hacer clic en **Forzar sincronización de tiempo** para restaurar la hora incorrecta que se muestra en la esquina superior derecha.
- **Servidor horario opcional** : Indica qué servidor horario debe usarse si hace clic en **Forzar sincronización de tiempo** (ser reservado para expertos).
- **Omitir verificación de tiempo** : le dice a Jeedom que no verifique si el tiempo es consistente entre sí mismo y el sistema en el que se ejecuta. Puede ser útil, por ejemplo, si no conecta Jeedom a Internet y no tiene una batería PSTN en el equipo utilizado.
- **Sistema** : Indica el tipo de hardware en el que está instalado Jeedom.
- **Clave de instalación** : Llave de hardware de su Jeedom en el mercado. Si su Jeedom no aparece en la lista de su Jeedom en el mercado, es recomendable hacer clic en el botón **Restablecer**.
- **Última fecha conocida** : Fecha registrada por Jeedom, utilizada después de un reinicio para sistemas sin batería PSTN.

## Pestaña interfaz

En esta pestaña encontrará los parámetros de personalización de la pantalla.

### Temas

- **Escritorio claro y oscuro** : Le permite elegir un tema claro y oscuro para el escritorio.
- **Móvil claro y oscuro** : igual que el anterior para la versión móvil.
- **Borrar tema de / a** : Le permite definir un período de tiempo durante el cual se utilizará el tema claro previamente elegido. Sin embargo, marque la opción **Cambiar el tema según el tiempo**.
- **Sensor de brillo**   : Interfaz móvil solamente, requiere activación *sensor adicional genérico* en cromo, página de cromo://flags.
- **Ocultar imágenes de fondo** : Le permite ocultar las imágenes de fondo encontradas en los escenarios, objetos, páginas de interacciones, etc.

### Tuiles

- **Azulejos no horizontales** : Restringe el ancho de los mosaicos cada x píxeles.
- **Azulejos no verticales** : Restringe la altura de los mosaicos cada x píxeles.
- **Azulejos de margen** : Espacio vertical y horizontal entre mosaicos, en píxeles.

### Personnalisation

- **Activar** : Active el uso de las siguientes opciones.
- **Transparencia** : Muestra mosaicos del panel de control y algunos contenidos con transparencia. 1 : totalmente opaco, 0 : totalmente transparente.
- **Ronda** : Muestra elementos de la interfaz con ángulos redondeados. 0 : sin redondeo, 1 : redondeo máximo.
- **Deshabilitar sombras** : Deshabilita las sombras de los mosaicos en el tablero, los menús y ciertos elementos de la interfaz.



## Pestaña Redes

Es absolutamente necesario configurar correctamente esta parte importante de Jeedom, de lo contrario, muchos complementos pueden no funcionar. Hay dos formas de acceder a Jeedom : L'**Acceso interno** (de la misma red local que Jeedom) y l'**Acceso externo** (de otra red, especialmente de Internet).

> **Importante**
>
> Esta parte está ahí para explicarle a Jeedom su entorno :
> cambiar el puerto o IP en esta pestaña no cambiará el puerto o IP de Jeedom en realidad. Para eso tiene que conectarse en SSH y editar el archivo / etc / network / interfaces para IP y los archivos etc / apache2 / sites-available / default y etc / apache2 / sites-available / default\_ssl (para HTTPS).
> Sin embargo, en caso de manejo inadecuado de su Jeedom, el equipo de Jeedom no se hace responsable y puede rechazar cualquier solicitud de soporte.

- **Acceso interno** : información para unirse a Jeedom desde equipos en la misma red que Jeedom (LAN)
    - **OK / NOK** : indica si la configuración de la red interna es correcta.
    - **Protocolo** : el protocolo a usar, a menudo HTTP.
    - **URL o dirección IP** : Jeedom IP para entrar.
    - **Puerto** : el puerto de la interfaz web de Jeedom, generalmente 80.
        Tenga en cuenta que cambiar el puerto aquí no cambia el puerto real de Jeedom, que seguirá siendo el mismo.
    - **Complementar** : el fragmento de URL adicional (ejemplo : / jeedom) para acceder a Jeedom.

- **Acceso externo** : información para llegar a Jeedom desde fuera de la red local. Para completar solo si no está utilizando Jeedom DNS.
    - **OK / NOK** : indica si la configuración de red externa es correcta.
    - **Protocolo** : protocolo utilizado para acceso al exterior.
    - **URL o dirección IP** : IP externa, si está fija. De lo contrario, proporcione la URL que apunta a la dirección IP externa de su red.
    - **Complementar** : el fragmento de URL adicional (ejemplo : / jeedom) para acceder a Jeedom.

- **Proxy para el mercado** : activación proxy.
    - Marque la casilla habilitar proxy.
    - **Dirección proxy** : Ingrese la dirección del proxy,
    - **Puerto proxy** : Ingrese el puerto proxy,
    - **Login** : Ingrese el inicio de sesión proxy,
    - **Contraseña** : Ingrese la contraseña.

> **Punta**
>
> Si está en HTTPS, el puerto es 443 (predeterminado) y en HTTP el puerto es 80 (predeterminado). Para usar HTTPS desde el exterior, ahora está disponible en el mercado un complemento de letencrypt.

> **Punta**
>
> Para saber si necesita establecer un valor en el campo **Complementar**, mire, cuando inicie sesión en Jeedom en su navegador de Internet, si necesita agregar / Jeedom (o lo que sea) después de la IP.

- **Gestión avanzada** : Esta parte puede no aparecer, dependiendo de la compatibilidad con su hardware.
    Allí encontrará la lista de sus interfaces de red. Puede decirle a Jeedom que no monitoree la red haciendo clic en **deshabilitar la administración de la red Jeedom** (comprobar si Jeedom no está conectado a ninguna red). También puede especificar el rango de ip local en la forma 192.168.1.* (para ser utilizado solo en instalaciones de tipo acoplable).
- **Mercado proxy** : permite el acceso remoto a su Jeedom sin la necesidad de un DNS, una IP fija o abrir los puertos de su caja de Internet.
    - **Usando Jeedom DNS** : activa Jeedom DNS (tenga en cuenta que esto requiere al menos un paquete de servicio)).
    - **Estado DNS** : Estado HTTP HTTP.
    - **Administración** : permite detener y reiniciar el servicio DNS de Jeedom.

> **Importante**
>
> Si no puede hacer que funcione Jeedom DNS, mire la configuración del firewall y el filtro parental de su caja de Internet (en livebox necesita, por ejemplo, el firewall a nivel medio).
- **Duración de las sesiones (hora)** : duración de las sesiones PHP, no se recomienda tocar este parámetro.

## Pestaña Registros

### Timeline

- **Numero maximo de eventos** : Define el número máximo de eventos para mostrar en la línea de tiempo.
- **Eliminar todos los eventos** : Vaciar la línea de tiempo de todos sus eventos grabados.

### Messages

- **Agregue un mensaje a cada error en los registros** : si un complemento o Jeedom escribe un mensaje de error en un registro, Jeedom agrega automáticamente un mensaje en el centro de mensajes (al menos está seguro de que no se lo perderá)).
- **Acción sobre mensaje** : Le permite realizar una acción al agregar un mensaje al centro de mensajes. Tienes 2 etiquetas para estas acciones :
        - #subject# : mensaje en cuestión.
        - #plugin# : complemento que activó el mensaje.

### Alertes

- **Agregar un mensaje a cada tiempo de espera** : Agregue un mensaje en el centro de mensajes si cae un dispositivo **tiempo de espera**.
- **Orden de tiempo de espera** : Comando de tipo **mensaje** para ser usado si un equipo está en **tiempo de espera**.
- **Agregue un mensaje a cada batería en Advertencia** : Agregue un mensaje en el centro de mensajes si un dispositivo tiene el nivel de batería en **advertencia**.
- **Comando de batería en Advertencia** : Comando de tipo **mensaje** para ser usado si el equipo tiene el nivel de batería **advertencia**.
- **Agregue un mensaje a cada batería en peligro** : Agregue un mensaje en el centro de mensajes si un dispositivo tiene el nivel de batería en **peligro**.
- **Comando con batería en peligro** : Comando de tipo **mensaje** para ser usado si el equipo tiene el nivel de batería **peligro**.
- **Agregue un mensaje a cada Advertencia** : Agregue un mensaje en el centro de mensajes si un pedido entra en alerta **advertencia**.
- **Comando de advertencia** : Comando de tipo **mensaje** para usar si un pedido entra en alerta **advertencia**.
- **Agregue un mensaje a cada peligro** : Agregue un mensaje en el centro de mensajes si un pedido entra en alerta **peligro**.
- **Comando en peligro** : Comando de tipo **mensaje** para usar si un pedido entra en alerta **peligro**.

### Logs

- **Motor de registro** : Le permite cambiar el motor de registro para, por ejemplo, enviarlos a un demonio syslog (d).
- **Formato de registro** : Formato de registro a utilizar (Precaución : no afecta los registros de demonios).
- **Número máximo de líneas en un archivo de registro** : Define el número máximo de líneas en un archivo de registro. Se recomienda no tocar este valor, ya que un valor demasiado grande podría llenar el sistema de archivos y / o hacer que Jeedom no pueda mostrar el registro.
- **Nivel de registro predeterminado** : Cuando selecciona &quot;Predeterminado&quot;, para el nivel de un registro en Jeedom, se utilizará.

A continuación encontrará una tabla para administrar con precisión el nivel de registro de los elementos esenciales de Jeedom, así como el de los complementos.

## Pestaña de pedidos

Se pueden registrar muchos pedidos. Por lo tanto, en Análisis → Historia, obtiene gráficos que representan su uso. Esta pestaña le permite establecer parámetros globales para el registro de comandos.

### Historique

- **Ver estadísticas de widgets** : Ver estadísticas sobre widgets. El widget debe ser compatible, que es el caso para la mayoría. El comando también debe ser de tipo numérico.
- **Período de cálculo para min, max, promedio (en horas)** : Período de cálculo de estadísticas (24h por defecto). No es posible tomar menos de una hora.
- **Periodo de cálculo de la tendencia (en horas)** : Periodo de cálculo de tendencia (2h por defecto). No es posible tomar menos de una hora.
- **Retraso antes de archivar (en horas)** : Indica el retraso antes de que Jeedom archive los datos (24 horas por defecto). Es decir que los datos históricos deben tener más de 24 horas para ser archivados (como recordatorio, el archivado promediará o tomará el máximo o mínimo de los datos durante un período que corresponde al tamaño de los paquetes).
- **Archivar por paquete desde (en horas)** : Este parámetro proporciona el tamaño del paquete (1 hora por defecto). Esto significa, por ejemplo, que Jeedom tomará períodos de 1 hora, promedio y almacenará el nuevo valor calculado eliminando los valores promediados.
- **Umbral de cálculo de tendencia baja** : Este valor indica el valor desde el cual Jeedom indica que la tendencia es descendente. Debe ser negativo (predeterminado -0.1).
- **Alto umbral de cálculo de tendencia** : Lo mismo para el ascenso.
- **Período predeterminado de visualización de gráficos** : Período que se usa de forma predeterminada cuando desea mostrar el historial de un pedido. Cuanto más corto sea el período, más rápido Jeedom mostrará el gráfico solicitado.

> **Nota**
>
> El primer parámetro **Ver estadísticas de widgets** es posible pero está deshabilitado de manera predeterminada porque extiende significativamente el tiempo de visualización del tablero. Si activa esta opción, de manera predeterminada, Jeedom se basa en los datos de las últimas 24 horas para calcular estas estadísticas.
> El método de cálculo de tendencia se basa en el cálculo de mínimos cuadrados (ver [aquí](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s) para el detalle).

### Push

- **URL de inserción global** : le permite agregar una URL para llamar en caso de una actualización del pedido. Puedes usar las siguientes etiquetas :
**\#value\#** por el valor del pedido, **\#cmd\_name\#** para el nombre del comando,
**\#cmd\_id\#** para el identificador único del pedido,
**\#humanname\#** para el nombre completo de la orden (ej : \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#),
**\#eq_name\#** para el nombre del equipo

## Pestaña Resúmenes

Agregar resúmenes de objetos. Esta información se muestra en la parte superior derecha de la barra de menú de Jeedom, o al lado de los objetos :

- **Clave** : Clave para el resumen, especialmente para no tocar.
- **Apellido** : Nombre abstracto.
- **Cálculo** : Método de cálculo, puede ser de tipo :
    - **Suma** : suma los diferentes valores,
    - **Promedio** : valores promedio,
    - **Texto** : muestra el valor literalmente (especialmente para aquellos de tipo string).
- **Icono** : Ícono de resumen.
- **Unidad** : Unidad de resumen.
- **Método de conteo** : Si cuenta un dato binario, entonces tiene que poner este valor en binario, por ejemplo, si cuenta el número de luces encendidas pero solo tiene el valor del atenuador (0 a 100), entonces tiene que poner el valor binario, así Jeedom consideró que Si el valor es mayor que 1, entonces la lámpara está encendida.
- **Mostrar si el valor es 0** : Marque esta casilla para mostrar el valor, incluso cuando sea 0.
- **Enlace a un virtual** : Inicia la creación de pedidos virtuales que tienen como valor los del resumen.
- **Eliminar resumen** : El último botón, en el extremo derecho, elimina el resumen de la línea.

## Pestaña del equipo

- **Número de fallas antes de la desactivación del equipo** : Número de fallas de comunicación con el equipo antes de la desactivación del equipo (un mensaje le avisará si esto sucede).
- **Umbrales de la batería** : Le permite administrar los umbrales de alerta global en las pilas.

## Pestaña Informes

Configurar la generación y gestión de informes

- **Tiempo de espera después de la generación de la página (en ms)** : Tiempo de espera después de cargar el informe para tomar la &quot;foto&quot;, para cambiar si su informe está incompleto, por ejemplo.
- **Limpiar informes anteriores de (días)** : Define el número de días antes de eliminar un informe (los informes ocupan un poco de espacio, así que tenga cuidado de no poner demasiada conservación).

## Pestaña Enlaces

Configurar gráficos de enlace. Estos enlaces le permiten ver, en forma de gráfico, las relaciones entre objetos, equipos, objetos, etc.

- **Profundidad para escenarios** : Permite definir, al mostrar un gráfico de enlaces de un escenario, el número máximo de elementos para mostrar (cuantos más elementos haya, más lento será el gráfico para generar y más difícil será de leer).
- **Profundidad para objetos** : Lo mismo para los objetos.
- **Profundidad para equipamiento** : Lo mismo para el equipo.
- **Profundidad para controles** : Lo mismo para pedidos.
- **Profundidad para variables** : Lo mismo para las variables.
- **Parámetro de prerender** : Le permite actuar sobre el diseño del gráfico.
- **Parámetro de procesamiento** : ídem.

## Pestaña interacciones

Esta pestaña le permite establecer parámetros globales relacionados con las interacciones que encontrará en Herramientas → Interacciones.

> **Punta**
>
> Para activar el registro de interacción, vaya a la pestaña Configuración → Sistema → Configuración : Registros, luego verifique **Depurar** en la lista de abajo. Atención : los registros serán muy detallados !

### General

Aquí tienes tres parámetros :

- **Sensibilidad** : hay 4 niveles de correspondencia (la sensibilidad va de 1 (corresponde exactamente) a 99)
    -   por 1 palabra : nivel de coincidencia para interacciones de una sola palabra.
    -   2 palabras : el nivel de coincidencia para interacciones de dos palabras.
    -   3 palabras : el nivel de coincidencia para interacciones de tres palabras.
    -   más de 3 palabras : nivel de coincidencia para interacciones de más de tres palabras.
- **No responda si no se entiende la interacción** : de manera predeterminada, Jeedom responde &quot;No entendí&quot; si no hay interacción. Es posible desactivar esta operación para que Jeedom no responda. Marque la casilla para deshabilitar la respuesta.
- **Regex de exclusión general para interacciones** : permite definir una expresión regular que, si corresponde a una interacción, eliminará automáticamente esta oración de la generación (reservada para expertos). Para más información ver explicaciones en el capítulo **Exclusión de expresiones regulares** documentación sobre interacciones.

### Interacción automática, contextual y advertencia

-   La **interacciones automáticas** permitir que Jeedom intente comprender una solicitud de interacción incluso si no hay ninguna definida. Luego buscará un nombre de objeto y / o equipo y / o orden para tratar de responder lo mejor posible.

-   La **interacciones contextuales** le permite encadenar múltiples solicitudes sin repetir todo, por ejemplo :
    - *Jeedom manteniendo el contexto :*
        - *Vosotras* : Cuanto esta el en el cuarto ?
        - *Jeedom* : Temperatura 25.2 ° C
        - *Vosotras* : y en la sala de estar ?
        - *Jeedom* : Temperatura 27.2 ° C
    - *Haz dos preguntas en una :*
        - *Vosotras* : ¿Cómo es en el dormitorio y en la sala de estar? ?
        - *Jeedom* : Temperatura 23.6 ° C, temperatura 27.2 ° C
-   Interacciones de tipo **Avísame** permita pedirle a Jeedom que le avise si un pedido excede / cae o vale un cierto valor.
    - *Vosotras* : Notificarme si la temperatura de la sala supera los 25 ° C ?
    - *Jeedom* : Bueno (*Tan pronto como la temperatura de la sala supere los 25 ° C, Jeedom le dirá, una vez*)

> **Nota**
>
> De manera predeterminada, Jeedom le responderá por el mismo canal que utilizó para pedirle que le notifique. Si no encuentra uno, utilizará el comando predeterminado especificado en esta pestaña : **Comando de retorno predeterminado**.

Aquí están las diferentes opciones disponibles :

- **Habilitar interacciones automáticas** : Marque para habilitar las interacciones automáticas.
- **Habilitar respuestas contextuales** : Marque para habilitar las interacciones contextuales.
- **Respuesta contextual prioritaria si la oración comienza con** : Si la oración comienza con la palabra que complete aquí, Jeedom priorizará una respuesta contextual (puede poner varias palabras separadas por **;** ).
- **Cortar una interacción a la mitad si contiene** : Lo mismo para la división de una interacción que contiene varias preguntas. Aquí das las palabras que separan las diferentes preguntas.
- **Activa las interacciones "Notificarme""** : Marque para habilitar las interacciones de tipo **Avísame**.
- **Respuesta &quot;Dime&quot; si la oración comienza con** : Si la oración comienza con esta (s) palabra (s) entonces Jeedom buscará hacer una interacción del tipo **Avísame** (puedes poner varias palabras separadas por **;** ).
- **Comando de retorno predeterminado** : Comando de retorno predeterminado para la interacción de tipo **Avísame** (utilizado, en particular, si ha programado la alerta a través de la interfaz móvil)
- **Sinónimo de objetos** : Lista de sinónimos para objetos (ej : rdc|planta baja|bajo tierra|bajo; sdb|Cuarto de baño).
- **Sinónimo de equipamiento** : Lista de sinónimos para equipos.
- **Sinónimo de pedidos** : Lista de sinónimos para comandos.
- **Sinónimo de resúmenes** : Lista de sinónimos para resúmenes.
- **Sinónimo de comando de control deslizante máximo** : Sinónimo de colocar un comando de tipo deslizador al máximo (por ejemplo, se abre para abrir el obturador del dormitorio ⇒ obturador del dormitorio al 100%).
- **Sinónimo de comando mínimo de control deslizante** : Sinónimo de poner un comando de tipo deslizante al mínimo (por ejemplo, se cierra para cerrar el obturador del dormitorio ⇒ obturador del dormitorio al 0%).

## Pestaña de seguridad

### LDAP

- **Habilitar autenticación LDAP** : habilitar la autenticación a través de un AD (LDAP).
- **Anfitrión** : servidor que aloja el AD.
- **Dominio** : dominio de su AD.
- **DN base** : DN base de su AD.
- **Nombre del usuario** : nombre de usuario para que Jeedom inicie sesión en AD.
- **Contraseña** : contraseña para que Jeedom se conecte a AD.
- **Campos de busqueda de usuario** : campos de búsqueda de inicio de sesión de usuario. Por lo general, uid para LDAP, SamAccountName para Windows AD.
- **Filtro de administradores (opcional)** : los administradores filtran en AD (para la gestión de grupos, por ejemplo)
- **Filtro de usuario (opcional)** : filtro de usuario en el AD (para gestión de grupos, por ejemplo)
- **Filtro de usuario limitado (opcional)** : filtrar usuarios limitados en el AD (por ejemplo, para la administración de grupos)
- **Permitir REMOTO\_USER** : Active REMOTE\_USER (usado en SSO por ejemplo).

### Connexion

- **Número de fallas toleradas** : define el número de intentos sucesivos permitidos antes de prohibir la IP
- **Tiempo máximo entre fallas (en segundos)** : tiempo máximo para 2 intentos para ser considerado sucesivo
- **Duración del destierro (en segundos), -1 por infinito** : Tiempo de prohibición de IP
- **IP "blanco"** : lista de IP que nunca se pueden prohibir
- **Eliminar IP prohibidas** : Borrar la lista de IP actualmente prohibidas

La lista de IP prohibidas se encuentra al final de esta página. Encontrará la IP, la fecha de prohibición y la fecha de finalización de la prohibición programada.

## Actualización / Pestaña Market

### Actualización de Jeedom

- **Fuente de actualización** : Elija la fuente de actualización principal de Jeedom.
- **Versión Core** : Versión principal para recuperar.
- **Buscar actualizaciones automáticamente** : Indique si debe verificar automáticamente si hay nuevas actualizaciones (tenga cuidado de evitar sobrecargar el mercado, el tiempo de verificación puede cambiar).

### Depósitos

Los repositorios son espacios de almacenamiento (y servicio) para poder mover copias de seguridad, recuperar complementos, recuperar el núcleo de Jeedom, etc.

### Fichier

Depósito utilizado para activar el envío de complementos por archivos.

#### Github

Depósito utilizado para conectar Jeedom a Github.

- **Simbólico** : Token para acceder al depósito privado.
- **Usuario u organización del repositorio principal de Jeedom** : Nombre de usuario u organización en github para el núcleo.
- **Nombre del repositorio para el núcleo Jeedom** : Nombre del repositorio para core.
- **Industria central de Jeedom** : Rama del repositorio central.

#### Market

Depósito utilizado para conectar Jeedom al mercado, se recomienda encarecidamente utilizar este depósito. Atención : cualquier solicitud de soporte puede ser rechazada si utiliza un depósito diferente a este.

- **Dirección** : Dirección du Mercado.(https://www.Jeedom.com/market).
- **Nombre del usuario** : Su nombre de usuario en el mercado.
- **Contraseña** : Tu contraseña de mercado.
- **Nombre [nube de respaldo]** : Nombre de su copia de seguridad en la nube (la atención debe ser única para cada Jeedom en riesgo de que se estrelle entre ellos)).
- **[Copia de seguridad de la nube] Contraseña** : Contraseña de respaldo en la nube. IMPORTANTE no debes perderlo, no hay forma de recuperarlo. Sin ella, ya no podrás restaurar tu Jeedom.
- **[Nube de respaldo] Frecuencia de respaldo completo** : Frecuencia de copia de seguridad en la nube completa. Una copia de seguridad completa es más larga que una incremental (que solo envía las diferencias). Se recomienda hacer 1 por mes.

#### Samba

Depósito que permite enviar automáticamente una copia de seguridad de Jeedom en un recurso compartido de Samba (ex : NAS Synology).

- **\ [Copia de seguridad \] IP** : IP del servidor Samba.
- **\ [Copia de seguridad \] Usuario** : Nombre de usuario para la conexión (las conexiones anónimas no son posibles). El usuario debe tener derechos de lectura Y escritura en el directorio de destino.
- **\ [Copia de seguridad \] Contraseña** : Contraseña de usuario.
- **\ [Copia de seguridad \] Compartir** : Ruta para compartir (tenga cuidado de detenerse en el nivel de compartir).
- **\ [Copia de seguridad \] Ruta** : Ruta en el intercambio (para poner en relativo), esto debe existir.

> **Nota**
>
> Si la ruta a su carpeta de copia de seguridad samba es :
> \\\\ 192.168.0.1 \\ Copias de seguridad \\ Automatización del hogar \\ Jeedom Then IP = 192.168.0.1, Compartir = //192.168.0.1 / Copias de seguridad, Ruta = Domótica / Jeedom

> **Nota**
>
> Al validar el uso compartido de Samba, como se describió anteriormente, aparece una nueva forma de respaldo en la sección Configuración → Sistema → Copias de respaldo de Jeedom. Al activarlo, Jeedom lo enviará automáticamente durante la próxima copia de seguridad. Una prueba es posible realizando una copia de seguridad manual.

> **Importante**
>
> Es posible que deba instalar el paquete smbclient para que funcione el repositorio.

> **Importante**
>
> El protocolo Samba tiene varias versiones, el v1 tiene un nivel de seguridad comprometido y en algunos NAS puede obligar al cliente a usar v2 o v3 para conectarse. Entonces si tienes un error *negociación de protocolo fallida: NT_STATUS_INVAID_NETWORK_RESPONSE* hay una buena posibilidad de que en el lado del NAS la restricción esté en su lugar. Luego debe modificar el archivo / etc / samba / smb en su sistema operativo Jeedom.conf y agregue estas dos líneas :
> protocolo max del cliente = SMB3
> protocolo min del cliente = SMB2
> El smbclient del lado de Jeedom usará v2 donde v3 y al poner SMB3 en ambos solo SMB3. Entonces, depende de usted adaptarse según las restricciones en el NAS u otro servidor Samba

> **Importante**
>
> Jeedom debe ser el único en escribir en esta carpeta y debe estar vacío de forma predeterminada (es decir, antes de la configuración y el envío de la primera copia de seguridad, la carpeta no debe contener ningún archivo o carpeta).

#### URL

- **URL central de Jeedom**
- **URL de la versión principal de Jeedom**

## Pestaña Caché

Permite monitorear y actuar en el caché Jeedom :

- **Estadística** : Número de objetos actualmente en caché.
- **Limpiar la tapa** : Forzar la eliminación de objetos que ya no son útiles. Jeedom hace esto automáticamente todas las noches.
- **Borrar todos los datos en caché** : Vacíe la tapa completamente.
    Tenga en cuenta que esto puede causar pérdida de datos !
- **Borrar el caché del widget** : Borrar el caché dedicado a los widgets.
- **Deshabilitar caché de widgets** : Marque la casilla para deshabilitar las cachés de widgets.
- **Tiempo de pausa para encuestas largas** : Frecuencia con la que Jeedom comprueba si hay eventos pendientes para los clientes (interfaz web, aplicación móvil, etc.)). Cuanto más corto sea este tiempo, más rápido se actualizará la interfaz, sin embargo, utiliza más recursos y, por lo tanto, puede ralentizar Jeedom.

## Pestaña API

Aquí encontrará la lista de las diferentes claves API disponibles en su Jeedom. Core tiene dos claves API :

-   un general : tanto como sea posible, evite usarlo,
-   y otro para profesionales : utilizado para la gestión de flotas. Puede estar vacio.
-   Luego encontrará una clave API por complemento que la necesita.

Para cada clave de complemento API, así como para las API HTTP, JsonRPC y TTS, puede definir su alcance :

- **Discapacitado** : La clave API no se puede usar,
- **IP blanca** : solo se autoriza una lista de IP (consulte Configuración → Sistema → Configuración : Redes),
- **Localhost** : solo se permiten solicitudes del sistema en el que está instalado Jeedom,
- **Activado** : sin restricciones, cualquier sistema con acceso a su Jeedom podrá acceder a esta API.

## Onglet &gt;\_OS/DB

> **Importante**
>
> Esta pestaña está reservada para expertos.
> Si modifica Jeedom con una de estas dos soluciones, el soporte puede negarse a ayudarlo.

- **General** :
    - **Verificación general** : Vamos a lanzar la prueba de consistencia Jeedom.
- **&gt;\_SYSTEM** :
    - **Administración** : Proporciona acceso a una interfaz de administración del sistema. Es un tipo de consola de shell en la que puede iniciar los comandos más útiles, en particular para obtener información sobre el sistema.
    - Restablecimiento de derechos : Le permite volver a aplicar los derechos correctos a los directorios y archivos de Jeedom Core.
- **Editor de archivos** : Permite el acceso a varios archivos del sistema operativo y editarlos, eliminarlos o crearlos.
- **Base de datos** :
    - **Administración** : Permite el acceso a la base de datos Jeedom. Luego puede ejecutar comandos en el campo superior.
    - **Verificación** : Permite iniciar una verificación en la base de datos Jeedom y corregir errores si es necesario
    - **Limpieza** : Inicia una verificación de la base de datos y limpia las entradas no utilizadas.
    - **Usuario** : Nombre de usuario utilizado por Jeedom en la base de datos,
    - **Contraseña** : contraseña para acceder a la base de datos utilizada por Jeedom.
