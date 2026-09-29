# Configuración
**Ajustes → Sistema → Configuración**

En esta página se encuentran la mayoría de los parámetros de configuración.
Aunque son numerosos, la mayoría de los parámetros vienen configurados por defecto.


## Pestaña «General»

En esta pestaña encontrarás información general sobre Jeedom:

- **Nombre de tu Jeedom**: Permite identificar tu Jeedom, especialmente en el Market. Se puede reutilizar en los escenarios o para identificar una copia de seguridad.
- **Idioma**: Idioma utilizado en tu Jeedom.
- **Sistema**: Tipo de equipo en el que está instalado el sistema en el que se ejecuta Jeedom.
- **Fecha y hora**: Selecciona tu zona horaria. Puedes hacer clic en **Forzar la sincronización de la hora** para corregir una hora incorrecta que aparezca en la esquina superior derecha.
- **Servidor de hora opcional**: Indica qué servidor de hora se debe utilizar si haces clic en **Forzar la sincronización de la hora** (solo para expertos).
- **Ignorar la comprobación de la hora**: indica a Jeedom que no compruebe si la hora coincide entre él mismo y el sistema en el que se ejecuta. Puede resultar útil, por ejemplo, si no conectas Jeedom a Internet y el dispositivo utilizado no dispone de una pila RTC.
- **Sistema**: Indica el tipo de dispositivo en el que está instalado Jeedom.
- **Clave de instalación**: Clave física de tu Jeedom en el Market. Si tu Jeedom no aparece en la lista de Jeedom del Market, se recomienda hacer clic en el botón **Restablecer**.
- **Última fecha conocida**: Fecha registrada por Jeedom, que se utiliza tras un reinicio en los sistemas que no disponen de pila RTC.

A continuación, hay varios parámetros que centralizan la información que pueden utilizar los complementos, lo que evita tener que introducirla en cada uno de ellos.

- Datos de localización: latitud, longitud y altitud de tu vivienda o emplazamiento.
- Dirección: Dirección postal de tu vivienda o emplazamiento.
- Varios: Superficie y número de ocupantes de tu vivienda o inmueble.

## Pestaña «Interfaz»

En esta pestaña encontrarás los ajustes para personalizar la visualización.

### Temas

- **Escritorio claro y oscuro**: te permite elegir un tema claro o oscuro para el escritorio.
- **Versión móvil clara y oscura**: igual que en el caso anterior para la versión móvil.
- **Tema claro de / a**: te permite definir un intervalo de tiempo durante el cual se utilizará el tema claro elegido anteriormente. Sin embargo, es necesario marcar la opción **Cambiar el tema según la hora**.
- **Sensor de luminosidad**: Solo disponible en la interfaz móvil; es necesario activar *generic extra sensor* en Chrome, en la página chrome://flags.

### Azulejos

- **No horizontal**: Limita el ancho de los mosaicos cada x píxeles.
- **Sin vertical**: Limita la altura de las fichas cada x píxeles.
- **Margen**: Espacio vertical y horizontal entre las fichas, en píxeles.
- **Centrado vertical de los mosaicos**: Centra verticalmente el contenido de los mosaicos.
- **Iconos de widgets de colores**: Color de los iconos de los widgets según su estado. Se puede modificar por escenario, *setColoredIcon* («Color de los iconos»).
- **Categorías por colores**: Coloreado del título de los mosaicos según la categoría.
- **Móvil: una columna por defecto**: Visualización de los mosaicos a ancho completo en dispositivos móviles


### Imágenes de fondo

- **Mostrar imágenes de fondo**: Mostrar las imágenes de fondo que se encuentran en las páginas de escenarios, objetos, interacciones, etc.
- **Desenfoque del fondo de los objetos**: Permite desenfocar automáticamente las imágenes del fondo de los objetos o habitaciones.
- **Imagen del panel de control**: Imagen de fondo para las páginas del panel de control (según las opciones del objeto).
- **Análisis de imágenes**: Imagen de fondo para las páginas del menú «Análisis».
- **Imagen de «Herramientas»**: Imagen de fondo para las páginas del menú «Herramientas».
- **Opacidad del tema Light**: Opacidad de las imágenes de fondo en el tema Light. Ajústala en función de la luminosidad de las imágenes de fondo para una mejor legibilidad.
- **Opacidad del tema oscuro**: Opacidad de las imágenes de fondo en el tema oscuro.  Ajústala en función de la luminosidad de las imágenes de fondo para una mejor legibilidad.

### Opciones

- **Visualización en tabla**: Muestra en formato de tabla las páginas del menú «Herramientas» y los complementos compatibles.
- **Ubicación de las notificaciones**: lugar de la página en el que aparecen las notificaciones.
- **Duración de las notificaciones**: Tiempo que permanecen visibles las notificaciones, en segundos. 0 para que no se oculten automáticamente.

### Personalización

- **Activar**: Activa el uso de las opciones que aparecen a continuación.
- **Transparencia**: Muestra los mosaicos del panel de control y determinados contenidos con transparencia. 1: totalmente opaco, 0: totalmente transparente.
- **Redondeado**: Muestra los elementos de la interfaz con esquinas redondeadas. 0: sin redondear, 1: redondeado al máximo.
- **Desactivar las sombras**: Desactiva las sombras de los mosaicos del panel de control, de los menús y de algunos elementos de la interfaz.



## Pestaña «Redes»

Es imprescindible configurar correctamente esta parte importante de Jeedom; de lo contrario, es posible que muchos complementos no funcionen. Se puede acceder a Jeedom de dos formas diferentes: el **acceso interno** (desde la misma red local que Jeedom) y el **acceso externo** (desde otra red, en particular desde Internet).

> **Importante**
>
> Esta sección solo sirve para explicar a Jeedom su entorno:
> Cambiar el puerto o la dirección IP en esta pestaña no modificará realmente el puerto ni la dirección IP de Jeedom. Para ello, hay que conectarse por SSH y editar el archivo /etc/network/interfaces para la dirección IP, así como los archivos etc/apache2/sites-available/default y etc/apache2/sites-available/default\_ssl (para el HTTPS).
> No obstante, en caso de uso indebido de su Jeedom, el equipo de Jeedom no se hará responsable y podrá rechazar cualquier solicitud de asistencia técnica.

- **Acceso interno**: información para conectarse a Jeedom desde un dispositivo de la misma red que Jeedom (LAN)
    - **OK/NOK**: indica si la configuración de la red interna es correcta.
    - **Protocolo**: el protocolo que se debe utilizar, normalmente HTTP.
    - **Dirección URL o IP**: Introduce la IP de Jeedom.
    - **Puerto**: el puerto de la interfaz web de Jeedom, normalmente el 80.
Atención: cambiar el puerto aquí no modifica el puerto real de Jeedom, que seguirá siendo el mismo.
    - **Complemento**: el fragmento de URL adicional (ejemplo: /Jeedom) para acceder a Jeedom.

- **Acceso externo**: información para conectarse a Jeedom desde fuera de la red local. Rellénalo solo si no utilizas el DNS de Jeedom.
    - **OK/NOK**: indica si la configuración de la red externa es correcta.
    - **Protocolo**: protocolo utilizado para el acceso externo.
    - **Dirección URL o IP**: IP externa, si es fija. De lo contrario, indica la URL que apunta a la dirección IP externa de tu red.
    - **Complemento**: el fragmento de URL adicional (ejemplo: /Jeedom) para acceder a Jeedom.

- **Proxy para Market**: activación del proxy.
    - Marca la casilla «Activar el proxy».
    - **Dirección del proxy**: Introduce la dirección del proxy,
    - **Puerto del proxy**: Introduce el puerto del proxy,
    - **Nombre de usuario**: Introduce el nombre de usuario del proxy,
    - **Contraseña**: Introduce la contraseña.

> **Consejo**
>
> Si utilizas HTTPS, el puerto es el 443 (por defecto) y, si utilizas HTTP, el puerto es el 80 (por defecto). Para utilizar HTTPS desde el exterior, ya hay disponible un complemento de Let's Encrypt en el Market.

> **Consejo**
>
> Para saber si necesitas introducir un valor en el campo **complemento**, comprueba, al iniciar sesión en Jeedom desde tu navegador web, si tienes que añadir /Jeedom (u otra cosa) después de la dirección IP.

- **Gestión avanzada**: Es posible que esta sección no aparezca, dependiendo de la compatibilidad con tu hardware.
Aquí encontrarás la lista de tus interfaces de red. Podrás indicar a Jeedom que no supervise la red haciendo clic en **desactivar la gestión de la red por parte de Jeedom** (marcar esta casilla si Jeedom no está conectado a ninguna red). También puede especificar aquí el rango de direcciones IP locales en el formato 192.168.1.* (solo debe utilizarse en instalaciones de tipo Docker).
- **Proxy Market**: permite el acceso remoto a tu Jeedom sin necesidad de un DNS, una IP fija ni abrir los puertos de tu router.
    - **Utilizar los DNS de Jeedom**: activa los DNS de Jeedom (atención: esto requiere al menos un Service Pack).
    - **Estado del DNS**: estado del DNS HTTP.
    - **Gestión**: permite detener y reiniciar el servicio DNS de Jeedom.

> **Importante**
>
> Si no consigues que funcione el DNS de Jeedom, comprueba la configuración del cortafuegos y del filtro parental de tu router (en el caso de Livebox, por ejemplo, el cortafuegos debe estar en nivel medio).
- **Duración de las sesiones (horas)**: duración de las sesiones PHP; no se recomienda modificar este parámetro.

## Pestaña «Registros»

### Cronología

- **Número máximo de eventos**: Define el número máximo de eventos que se mostrarán en la línea de tiempo.
- **Eliminar todos los eventos**: Permite borrar de la cronología todos los eventos registrados.

### Mensajes

- **Añadir un mensaje por cada error en los registros**: si un plugin o Jeedom escribe un mensaje de error en un registro, Jeedom añade automáticamente un mensaje en el centro de mensajes (al menos así te aseguras de no pasártelo por alto).
- **Acción al recibir un mensaje**: Permite realizar una acción cuando se añade un mensaje al centro de mensajes. Dispones de 2 etiquetas para estas acciones:
        - #asunto#: mensaje en cuestión.
        - #plugin#: plugin que ha activado el mensaje.

### Alertas

- **Añadir un mensaje cada vez que se produzca un **timeout****: Añade un mensaje al centro de mensajes si un dispositivo entra en **timeout**.
- **Comando de tiempo de espera**: comando de tipo **mensaje** que se debe utilizar si un equipo está en **tiempo de espera**.
- **Añadir un mensaje a cada batería en estado de **advertencia****: Añade un mensaje al centro de mensajes si el nivel de batería de un dispositivo se encuentra en estado de **advertencia**.
- **Comando de batería en «Warning»**: Comando de tipo **mensaje** que se debe utilizar si un equipo tiene el nivel de batería en **«Warning»**.
- **Añadir un mensaje cada vez que la batería esté en peligro**: Añade un mensaje al centro de mensajes si el nivel de batería de un dispositivo se encuentra en **peligro**.
- **Aviso de batería en peligro**: Comando de tipo **mensaje** que se debe utilizar si el nivel de batería de un equipo se encuentra en **peligro**.
- **Añadir un mensaje a cada advertencia**: Añade un mensaje al centro de mensajes si un comando pasa al estado de **advertencia**.
- **Comando en modo «Warning»**: Comando de tipo **mensaje** que se debe utilizar si un comando pasa al estado de alerta **«warning»**.
- **Añadir un mensaje a cada «Peligro»**: Añade un mensaje al centro de mensajes si un comando pasa al estado de alerta **peligro**.
- **Comando de peligro**: Comando de tipo **mensaje** que se debe utilizar si un comando pasa al estado de alerta **peligro**.

### Registros

- **Motor de registro**: Permite cambiar el motor de registro para, por ejemplo, enviar los mensajes a un demonio syslog(d).
- **Formato de los registros**: Formato de registro que se debe utilizar (Atención: esto no afecta a los registros de los demonios).
- **Número máximo de líneas en un archivo de registro**: Define el número máximo de líneas en un archivo de registro. Se recomienda no modificar este valor, ya que un valor demasiado alto podría llenar el sistema de archivos y/o impedir que Jeedom muestre el registro.
- **Nivel de registro por defecto**: cuando seleccionas «Por defecto» como nivel de registro en Jeedom, se utilizará este nivel.

A continuación encontrarás una tabla que te permite gestionar con precisión el nivel de registro de los elementos esenciales de Jeedom, así como el de los complementos.

## Pestaña «Resúmenes»

[Consulta la documentación sobre los resúmenes.](https://doc.jeedom.com/concept/es_ES/summary)

## Pestaña «Equipos»

### Equipos

- **Número de fallos antes de la desactivación del equipo**: Número de fallos de comunicación con el equipo antes de que este se desactive (se le avisará mediante un mensaje si esto ocurre).
- **Umbrales de las pilas**: Permite gestionar los umbrales de alerta generales de las pilas.

Se puede registrar el historial de muchos comandos. Así, en Análisis→Historial, se pueden consultar gráficos que muestran su uso. Esta pestaña permite configurar los parámetros generales para el registro del historial de los comandos.

### Historial de pedidos

- **Mostrar estadísticas en los widgets**: Permite mostrar estadísticas en los widgets. El widget debe ser compatible, lo cual ocurre en la mayoría de los casos. Además, el comando debe ser de tipo numérico.
- **Periodo de cálculo para mínimo, máximo y media (en horas)**: Periodo de cálculo de las estadísticas (24 horas por defecto). No es posible establecer un periodo inferior a una hora.
- **Período de cálculo de la tendencia (en horas)**: Período de cálculo de las tendencias (2 h por defecto). No es posible establecer un valor inferior a una hora.
- **Plazo antes del archivo (en horas)**: Indica el tiempo que debe transcurrir antes de que Jeedom archive un dato (24 h por defecto). Es decir, los datos históricos deben tener más de 24 h de antigüedad para ser archivados (a modo de recordatorio, el archivo calculará la media o tomará el máximo o el mínimo de los datos en un periodo que se corresponde con el tamaño de los paquetes).
- **Archivar por paquete de (en horas)**: Este parámetro indica precisamente el tamaño de los paquetes (1 h por defecto). Esto significa, por ejemplo, que Jeedom tomará intervalos de 1 h, calculará la media y almacenará el nuevo valor calculado, eliminando los valores promediados.
- **Umbral de cálculo de tendencia a la baja**: este valor indica a partir de qué valor Jeedom señala que la tendencia es a la baja. Debe ser negativo (por defecto, -0,1).
- **Umbral de cálculo de la tendencia alcista**: Lo mismo ocurre con las subidas.
- **Período de visualización de gráficos por defecto**: período que se utiliza por defecto cuando se desea visualizar el historial de un comando. Cuanto más corto sea el período, más rápido mostrará Jeedom el gráfico solicitado.

> **Nota**
>
> La primera opción, **Mostrar estadísticas en los widgets**, está disponible pero desactivada por defecto, ya que alarga considerablemente el tiempo de carga del panel de control. Si activas esta opción, Jeedom utilizará, por defecto, los datos de las últimas 24 horas para calcular estas estadísticas.
> El método de cálculo de tendencias se basa en el cálculo de los mínimos cuadrados (véase [aquí](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s) (para más detalles).

### Push

- **URL de notificación global**: permite añadir una URL a la que se debe acceder en caso de que se actualice un pedido. Puedes utilizar las siguientes etiquetas:
**\#value\#** para el valor del comando, **\#cmd\_name\#** para el nombre del comando,
**\#cmd\_id\#** para el identificador único del comando,
**\#humanname\#** para el nombre completo del comando (p. ej.: \#\[Cuarto de baño\]\[Hidrometría\]\[Humedad\]\#),
**\#eq_name\#** como nombre del equipo

## Pestaña «Informes»

Permite configurar la generación y la gestión de informes

- **Tiempo de espera tras la generación de la página (en ms)**: Tiempo de espera tras la carga del informe para realizar la «captura de pantalla»; cámbialo si, por ejemplo, tu informe está incompleto.
- **Eliminar los informes más antiguos de (días)**: Establece el número de días tras los cuales se eliminará un informe (los informes ocupan algo de espacio, así que ten cuidado de no establecer un periodo de conservación demasiado largo).

## Pestaña «Enlaces»

Permite configurar los gráficos de enlaces. Estos enlaces permiten visualizar, en forma de gráfico, las relaciones entre los objetos, los equipos, los dispositivos, etc.

- **Profundidad de los escenarios**: permite definir, al visualizar un gráfico de enlaces de un escenario, el número máximo de elementos que se mostrarán (cuantos más elementos haya, más tardará el gráfico en generarse y más difícil resultará de leer).
- **Profundidad para los objetos**: Lo mismo ocurre con los objetos.
- **Profundidad de los equipos**: Lo mismo se aplica a los equipos.
- **Profundidad de los mandos**: Lo mismo ocurre con los mandos.
- **Profundidad de las variables**: Lo mismo se aplica a las variables.
- **Parámetro de prerenderizado**: Permite modificar la disposición del gráfico.
- **Parámetros de renderizado**: Igual.

## Pestaña «Interacciones»

Esta pestaña permite configurar los parámetros generales relativos a las interacciones que encontrarás en Herramientas→Interacciones.

> **Consejo**
>
> Para activar el registro de interacciones, hay que ir a la pestaña Ajustes → Sistema → Configuración: Registros y, a continuación, marcar **Depuración** en la lista de la parte inferior. Atención: ¡los registros serán entonces muy detallados!

### General

Aquí tienes tres parámetros:

- **Sensibilidad**: hay 4 niveles de coincidencia (la sensibilidad va de 1 [coincide exactamente] a 99) para
    -   1 palabra: el nivel de coincidencia para interacciones de una sola palabra.
    -   2 palabras: el nivel de coincidencia para las interacciones de dos palabras.
    -   3 palabras: el nivel de coincidencia para interacciones de tres palabras.
    -   + de 3 palabras: el nivel de coincidencia para interacciones de más de tres palabras.
- **No responder si no se entiende la interacción**: por defecto, Jeedom responde «No lo he entendido» si no hay ninguna interacción que coincida. Es posible desactivar esta función para que Jeedom no responda nada. Marca la casilla para desactivar la respuesta.
- **Expresión regular general de exclusión para las interacciones**: permite definir una expresión regular que, si coincide con una interacción, eliminará automáticamente esa frase de la generación (reservado a expertos). Para más información, consulta las explicaciones del capítulo **Expresión regular de exclusión** de la documentación sobre interacciones.

### Interacción automática, contextual y alertas

-   Las **interacciones automáticas** permiten a Jeedom intentar comprender una solicitud de interacción aunque no se haya definido ninguna. A continuación, buscará el nombre de un objeto, un equipo o un comando para intentar responder de la mejor manera posible.

-   Las **interacciones contextuales** te permiten encadenar varias solicitudes sin tener que repetir todo, por ejemplo:
    - *Jeedom mantiene el contexto:*
        - *Tú*: ¿A qué temperatura está la habitación?
        - *Jeedom*: Temperatura 25,2 °C
        - *Tú*: ¿y en el salón?
        - *Jeedom*: Temperatura 27,2 °C
    - *Plantear dos preguntas en una:*
        - *Tú*: ¿A qué temperatura está la habitación y el salón?
        - *Jeedom*: Temperatura 23,6 °C, Temperatura 27,2 °C
-   Las interacciones del tipo **Avisarme** permiten pedirle a Jeedom que te avise si un comando supera, cae por debajo o alcanza un valor determinado.
    - *Tú*: ¿Me avisas si la temperatura del salón supera los 25 °C?
    - *Jeedom*: Vale (*En cuanto la temperatura del salón supere los 25 °C, Jeedom te lo avisará, solo una vez*)

> **Nota**
>
> Por defecto, Jeedom te responderá por el mismo canal que hayas utilizado para pedirle que te avise. Si no encuentra ninguno, utilizará el comando por defecto especificado en esta pestaña: **Comando de respuesta por defecto**.

Estas son las diferentes opciones disponibles:

- **Activar las interacciones automáticas**: Marca esta casilla para activar las interacciones automáticas.
- **Activar respuestas contextuales**: Marca esta casilla para activar las interacciones contextuales.
- **Respuesta contextual prioritaria si la frase empieza por**: Si la frase empieza por la palabra que introduzcas aquí, Jeedom dará prioridad a una respuesta contextual (puedes introducir varias palabras separándolas con **;**).
- **Dividir una interacción en dos si contiene**: Lo mismo se aplica a la división de una interacción que contenga varias preguntas. Aquí debes indicar las palabras que separan las distintas preguntas.
- **Activar las interacciones «Avísame»**: Marca esta casilla para activar las interacciones del tipo **«Avísame»**.
- **Respuesta del tipo «Avísame» si la frase empieza por**: Si la frase empieza por esta(s) palabra(s), Jeedom intentará realizar una interacción del tipo **«Avísame»** (puedes introducir varias palabras separándolas con **;**).
- **Comando de respuesta por defecto**: Comando de respuesta por defecto para una interacción del tipo **Avísame** (que se utiliza, entre otras cosas, si has programado la alerta a través de la interfaz móvil)
- **Sinónimos para los objetos**: Lista de sinónimos para los objetos (p. ej.: planta baja|planta de calle|sótano|parte baja; baño|cuarto de baño).
- **Sinónimos de «equipos»**: Lista de sinónimos de «equipos».
- **Sinónimos de los comandos**: Lista de sinónimos de los comandos.
- **Sinónimos para los resúmenes**: Lista de sinónimos para los resúmenes.
- **Sinónimo de «ajuste del control deslizante al máximo»**: Sinónimo de ajustar un control deslizante al máximo (por ejemplo, «abrir» para abrir la persiana del dormitorio ⇒ persiana del dormitorio al 100 %).
- **Sinónimo de «control deslizante al mínimo»**: Sinónimo de ajustar un control deslizante al mínimo (por ejemplo, «cerrar» para cerrar la persiana del dormitorio ⇒ persiana del dormitorio al 0 %).

## Pestaña «Seguridad»

### LDAP

- **Activar la autenticación LDAP**: activa la autenticación a través de un AD (LDAP).
- **Host**: servidor que aloja el AD.
- **Dominio**: dominio de tu AD.
- **Base DN**: base DN de tu AD.
- **Nombre de usuario**: nombre de usuario para que Jeedom se conecte al AD.
- **Contraseña**: contraseña para que Jeedom se conecte al AD.
- **Campos de búsqueda de usuario**: campos de búsqueda para el inicio de sesión del usuario. Por lo general, uid para LDAP y SamAccountName para Windows AD.
- **Filtro de administradores (opcional)**: filtro de administradores en el AD (por ejemplo, para la gestión de grupos)
- **Filtro de usuarios (opcional)**: filtro de usuarios en el AD (por ejemplo, para la gestión de grupos)
- **Filtro de usuarios con restricciones (opcional)**: filtro de usuarios con restricciones en el Active Directory (por ejemplo, para la gestión de grupos)
- **Permitir REMOTE\_USER**: Activa REMOTE\_USER (utilizado, por ejemplo, en SSO).

### Iniciar sesión

- **Número de fallos permitidos**: define el número de intentos consecutivos permitidos antes de bloquear la dirección IP
- **Tiempo máximo entre intentos fallidos (en segundos)**: tiempo máximo para que dos intentos se consideren sucesivos
- **Duración del bloqueo (en segundos), -1 para infinito**: tiempo de bloqueo de la IP
- **IP «blanca»**: lista de direcciones IP que nunca pueden bloquearse
- **Eliminar las direcciones IP bloqueadas**: permite borrar la lista de direcciones IP actualmente bloqueadas

La lista de direcciones IP bloqueadas se encuentra al final de esta página. En ella encontrarás la dirección IP, la fecha de bloqueo y la fecha prevista de finalización del bloqueo.

## Pestaña «Actualizaciones/Market»

### Actualización de Jeedom

- **Fuente de actualización**: Elige la fuente de actualización del núcleo de Jeedom.
- **Versión del núcleo**: Versión del núcleo que hay que descargar.
- **Comprobar automáticamente si hay actualizaciones**: Indica si se debe buscar automáticamente si hay nuevas actualizaciones (atención: para evitar sobrecargar el Market, la hora de comprobación puede variar).

### Los repositorios

Los repositorios son espacios de almacenamiento (y de servicio) que permiten trasladar copias de seguridad, descargar complementos, descargar el núcleo de Jeedom, etc.

### Archivo

Repositorio que sirve para activar el envío de complementos mediante archivos.

#### GitHub

Repositorio utilizado para conectar Jeedom con GitHub.

- **Token**: Token para acceder al repositorio privado.
- **Usuario u organización del repositorio del núcleo de Jeedom**: Nombre del usuario o de la organización en GitHub para el núcleo.
- **Nombre del repositorio para el núcleo de Jeedom**: Nombre del repositorio para el núcleo.
- **Rama para el núcleo de Jeedom**: Rama del repositorio para el núcleo.

#### Mercado

Repositorio utilizado para conectar Jeedom con el Market; se recomienda encarecidamente utilizar este repositorio. Atención: cualquier solicitud de asistencia técnica podrá ser rechazada si utilizas un repositorio distinto a este.

- **Dirección**: Dirección del Market. (https://market.jeedom.com).
- **Nombre de usuario**: Tu nombre de usuario en el Market.
- **Contraseña**: Tu contraseña de Market.
- **[Copia de seguridad en la nube] Nombre**: Nombre de tu copia de seguridad en la nube (atención: debe ser único para cada Jeedom, ya que, de lo contrario, podrían sobrescribirse entre sí).
- **[Copia de seguridad en la nube] Contraseña**: Contraseña de la copia de seguridad en la nube. IMPORTANTE: no debes perderla bajo ningún concepto, ya que no hay forma de recuperarla. Sin ella, no podrás restaurar tu Jeedom.
- **[Copia de seguridad en la nube] Frecuencia de la copia de seguridad completa**: Frecuencia de la copia de seguridad completa en la nube. Una copia de seguridad completa tarda más en realizarse que una incremental (que solo envía los cambios). Se recomienda realizar una al mes.

#### Samba

Repositorio que permite enviar automáticamente una copia de seguridad de Jeedom a un recurso compartido de Samba (por ejemplo, un NAS de Synology).

- **\[Copia de seguridad\] IP**: dirección IP del servidor Samba.
- **\[Copia de seguridad\] Usuario**: Nombre de usuario para iniciar sesión (no se admiten conexiones anónimas). Es imprescindible que el usuario tenga derechos de lectura Y de escritura en el directorio de destino.
- **\[Copia de seguridad\] Contraseña**: Contraseña del usuario (atención: no se permiten caracteres especiales).
- **\[Copia de seguridad\] Recurso compartido**: Ruta del recurso compartido (ten en cuenta que debes detenerte justo en el recurso compartido).
- **\[Copia de seguridad\] Ruta**: Ruta dentro del recurso compartido (debe indicarse de forma relativa); esta debe existir.

> **Nota**
>
> Si la ruta de acceso a tu carpeta de copia de seguridad de Samba es:
> \\\\192.168.0.1\\Copias de seguridad\\Domótica\\Jeedom. Por lo tanto, IP = 192.168.0.1, Compartido = //192.168.0.1/Copias de seguridad, Ruta = Domótica/Jeedom

> **Nota**
>
> Al validar el recurso compartido de Samba, tal y como se ha descrito anteriormente, aparece una nueva opción de copia de seguridad en la sección Ajustes→Sistema→Copias de seguridad de Jeedom. Al activarla, Jeedom la enviará automáticamente durante la próxima copia de seguridad. Se puede realizar una prueba ejecutando una copia de seguridad manual.

> **Importante**
>
> Es posible que tengas que instalar el paquete smbclient para que el repositorio funcione.

> **Importante**
>
> El protocolo Samba cuenta con varias versiones; el nivel de seguridad de la V1 es vulnerable y, en algunos NAS, se puede obligar al cliente a utilizar la v2 o la v3 para conectarse. Por lo tanto, si aparece el error *protocol negotiation failed: NT_STATUS_INVAID_NETWORK_RESPONSE*, es muy probable que en el NAS se haya activado esta restricción. En ese caso, debes modificar el archivo /etc/samba/smb.conf del sistema operativo de tu Jeedom y añadir estas dos líneas:
> protocolo del cliente máximo = SMB3
> protocolo mínimo del cliente = SMB2
> El smbclient de Jeedom utilizará entonces la versión 2 o la 3, y si se configura SMB3 en ambos, solo se utilizará SMB3. Por lo tanto, tendrás que adaptarlo en función de las restricciones del NAS u otro servidor Samba.

> **Importante**
>
> Jeedom debe ser el único que escriba en esta carpeta, que debe estar vacía por defecto (es decir, antes de la configuración y del envío de la primera copia de seguridad, la carpeta no debe contener ningún archivo ni carpeta).

#### URL

- **URL principal de Jeedom**
- **URL de la versión básica de Jeedom**

## Pestaña «Caché»

Permite supervisar y actuar sobre la caché de Jeedom:

- **Motor de caché**: elección del motor de caché para Jeedom:
  - Sistema de archivos: Almacenamiento de la información de la caché en /tmp/jeedom/cache (es decir, en la RAM) en modo de archivo; utiliza una biblioteca de terceros. Próximamente se sustituirá por Fichier (beta).
  - Archivo (beta): Almacenamiento de la información de la caché en /tmp/jeedom/cache (es decir, en la RAM) en modo de archivo. Es la opción más eficaz, pero se guarda cada 30 minutos.
  - MySQL (beta): Uso de una tabla de caché en la base de datos. Es la opción menos eficiente, pero se guarda en tiempo real (no hay posibilidad de pérdida de datos).
  - Redis (beta): Reservado para expertos, utiliza Redis para gestionar la caché (por lo que es necesario que instales tú mismo Redis y las dependencias de php-redis).
- **Limpiar la caché**: obliga a eliminar los objetos que ya no son útiles. Jeedom lo hace automáticamente todas las noches.
- **Borrar todos los datos de la caché**: Borra completamente la caché.
¡Ojo, esto puede provocar la pérdida de datos!
- **Tiempo de espera para el «long polling»**: frecuencia con la que Jeedom comprueba si hay eventos pendientes para los clientes (interfaz web, aplicación móvil…). Cuanto más corto sea este tiempo, más rápido se actualizará la interfaz; sin embargo, esto consume más recursos y, por lo tanto, puede ralentizar Jeedom.

>**IMPORTANTE**
>
> Cualquier cambio en el motor de caché provoca que este se reinicie, por lo que hay que esperar a que los módulos vuelvan a enviar la información para recuperarlo todo.

## Pestaña API

Aquí encontrarás la lista de las diferentes claves API disponibles en tu Jeedom. De forma predeterminada, el núcleo cuenta con dos claves API:

- En general: en la medida de lo posible, hay que evitar utilizarlo,
- y otra para profesionales: se utiliza para la gestión de flotas. Puede estar vacía.
- Además, encontrarás una clave API por cada complemento que necesites.

Para cada clave API de plugin, así como para las API HTTP, JSON-RPC y TTS, puedes definir su ámbito:

- **Desactivada**: la clave API no se puede utilizar,
- **Direcciones IP permitidas**: solo se permite una lista de direcciones IP (véase Ajustes→Sistema→Configuración: Seguridad),
- **Localhost**: solo se permiten las solicitudes procedentes del sistema en el que está instalado Jeedom,
- **Activado**: sin restricciones; cualquier sistema que tenga acceso a tu Jeedom podrá acceder a esta API.

Para cada clave API de un plugin, puedes restringirles el acceso a los métodos principales (generales) para limitar su uso únicamente a su método integrado (atención: algunos plugins, como «mobile» o «jeelink», necesitan absolutamente los métodos principales).

## Pestaña &gt;\_OS/DB

> **Importante**
>
> Esta pestaña está reservada para expertos.
> Si modificas Jeedom con alguna de estas dos soluciones, el servicio de asistencia podría negarse a ayudarte.

### Comprobaciones del sistema

- **Comprobación general**: Permite ejecutar una prueba de coherencia de Jeedom.
- **Restablecimiento de permisos**: Permite volver a aplicar los permisos correctos a los directorios y archivos del núcleo de Jeedom.
- **Comprobación de los paquetes del sistema**: Permite iniciar una comprobación de los paquetes instalados.
- **Comprobación de la base de datos**: Permite iniciar una comprobación de la base de datos de Jeedom y corregir los errores si es necesario.
- **Limpieza de la base de datos**: Inicia una comprobación de la base de datos y elimina las entradas que no se utilicen.


### Herramientas del sistema

- **Editor de archivos**: Permite acceder a los distintos archivos del sistema operativo y editarlos, eliminarlos o crearlos.
- **Administración del sistema**: Permite acceder a una interfaz de administración del sistema. Se trata de una especie de consola de línea de comandos en la que se pueden ejecutar los comandos más útiles, sobre todo para obtener información sobre el sistema.
- **Editor masivo**: Herramienta para la edición masiva de dispositivos, comandos, objetos y escenarios.
- **Administración de la base de datos**: Permite acceder a la base de datos de Jeedom. A continuación, puedes introducir comandos en el campo superior.
- **Usuario / Contraseña**: Nombre de usuario y contraseña de acceso a la base de datos que utiliza Jeedom.
