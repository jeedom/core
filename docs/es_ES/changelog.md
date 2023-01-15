# Registro de cambios Jeedom V4.4


### 4.4 : Prerrequisitos

- 
- PHP 7.3

### 4.4 : Noticias / Mejoras

- **** : El modal de historial y la página de historial permiten usar botones *Semana, Mes, Año* para recargar dinámicamente un historial más grande.
- **Menú Jeedom** : Un retraso de 0.25s se introdujo en la apertura de submenús.
- **Ventana de selección de imagen** : Se agregó un menú contextual para enviar imágenes y crear, renombrar o eliminar una carpeta.
- **Ventana de selección de iconos** : Posibilidad de agregar un parámetro `ruta` al llamar a `jeedomUtils.chooseIcon` por un complemento para mostrar solo sus iconos.
- **** : Capacidad para mostrar varios objetos uno al lado del otro *(Ajustes → Sistema → Configuración / Interfaz)*.
- **** : La ventana de edición de mosaicos del modo de edición permite cambiar el nombre de los comandos.
- **** : En el diseño de la tabla, posibilidad de insertar atributos HTML *(colspan/rowspan en particular)* para cada celda.
- **** : Capacidad para deshabilitar las plantillas de widgets de los complementos que los usan para volver a la pantalla predeterminada de Jeedom *(ventana de configuración del dispositivo)*.
- **** : El equipo inactivo desaparece automáticamente de todas las páginas. El equipo reactivado vuelve a aparecer en el tablero si el objeto principal ya está presente.
- **** : El equipo invisible desaparece automáticamente del tablero. El equipo que se vuelve a mostrar vuelve a aparecer en el tablero si el objeto principal ya está presente.
- **Análisis > Dispositivos > Dispositivos en alerta** : Los dispositivos que entran en alerta aparecen automáticamente y los que salen de alerta desaparecen automáticamente.
- **** : Eliminar o crear un resumen da como resultado la actualización del resumen global y el tema.
- **Herramientas > Reemplazar** : Esta herramienta ahora ofrece un modo **, permitiendo copiar las configuraciones de equipos y comandos, sin reemplazarlos en los escenarios y otros.
- **** : La línea de tiempo ahora carga los primeros 35 eventos. En la parte inferior de la página, varios botones le permiten cargar dinámicamente los siguientes eventos.
- **** : Posibilidad de diferenciar acciones en caso de error o de alerta de comando.
- **** : Capacidad para establecer widgets de comando predeterminados.
- Se ha añadido un menú contextual en diferentes lugares al nivel de los checkboxes para seleccionarlos todos, ninguno, o invertir la selección *(ver [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.4))*.
- **** : posibilidad en la página de configuración del objeto de pedirle a jeedom que reordene el equipo de acuerdo con su uso

### 4.4 : Autre

- **** : Inicio de desarrollo en js puro, sin jQuery. Ver [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.4).
- **** : Lista más detallada de dispositivos USB.
- **** : Actualizar Highchart v9.3.2 a v10.3.2 (El módulo ** ya no importa).

### 4.4 : Remarques

> ****
>
> Sobre **** y los ****, Núcleo v4.4 ahora cambia automáticamente el tamaño de los mosaicos para crear una cuadrícula perfecta. Las unidades (la altura más pequeña y el ancho más pequeño de un mosaico) de esta cuadrícula se definen en **Ajustes → Sistema → Configuración / Interfaz** por valores *Paso vertical (mínimo 100)*  *Paso horizontal (mínimo 110)*. El valor ** definiendo el espacio entre las baldosas.
> Los mosaicos se adaptan a las dimensiones de la cuadrícula y se pueden hacer una, dos veces, etc. estos valores en alto o ancho. Sin duda será necesario pasar [Modo de edición del tablero](https:doc.jeedom.com/es_ES/core4.4dashboard#Mode%20%C3%A9dition) para ajustar el tamaño de algunos mosaicos después de la actualización.


> ****
>
> Los widgets principales se han reescrito en js/css puro. Tendrás que editar el Dashboard *(Editar luego botón ⁝ en los mosaicos)* y usa la opcion *Ajuste de línea después* en ciertos comandos para encontrar el mismo aspecto visual.
> Todos los widgets principales ahora admiten la visualización **, agregando un parámetro opcional **  **  **.

> **Cuadros de diálogo**
>
> Todos los cuadros de diálogo (bootstrap, bootbox, jQuery UI) se han migrado a una biblioteca interna Core especialmente desarrollada. Los cuadros de diálogo redimensionables ahora tienen un botón para cambiar **.


# Registro de cambios Jeedom V4.3

## 

- Autorización de una respuesta libre en ** si pones * en el campo de posibles respuestas.
- **Análisis / Historia** : Corrección de errores en la comparación del historial (error introducido en 4.3.10).
- **Síntesis** : L'*Acción de Síntesis* de un objeto ahora es compatible con la versión móvil.
- Corrección de historiales al usar la función de agregación.
- Se corrigió un error en la instalación de un complemento por otro complemento (Ej : mqtt2 instalado por zwavejs).
- Se corrigió un error en el historial donde el valor 0 podía sobrescribir el valor anterior.

## 

- **Análisis / Historia** : Se corrigieron errores en la eliminación del historial.
- Visualización de valores fijos en la ventana de configuración de comandos.
- Se agregó información y control de la herramienta de reemplazo.

## 

- Edición de mosaicos mejorada.
- Visibilidad mejorada de las casillas de verificación temáticas Oscuras y Claras.
- Apilamiento de historial fijo.
- Optimización de la gestión del cambio de hora (gracias @jpty).
- Corrección de errores y mejoras.

## 

- Arreglo del fallo.
- Se mejoró la seguridad de las preguntas al usar la función generateAskResponseLink mediante complementos : uso de un token único (no más envío de la clave API central) y bloqueo de la respuesta solo entre las opciones posibles.
- Se corrigió un error que impedía la instalación de jeedom.
- Se corrigió un error en influxdb.


## 

- Corrección de errores (que afectan a un complemento futuro en desarrollo)).
- Se corrigieron errores de visualización en algunos widgets según la unidad.
- Descripción añadida **** para acciones de mensajes (ver [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.3)).

## 

- Conversión de unidad eliminada por segundo (s)).
- Eliminación del menú de actualización del sistema operativo para las cajas Jeedom (las actualizaciones del sistema operativo son administradas por Jeedom SAS).
- Se corrigió un error en el modo de configuración del historial.
- Añadir una acción ** para escenarios, acciones de valor, acciones pre/post ejecutivas : Permite cambiar el tema de la interfaz inmediatamente, en oscuro, claro u otro (alternar).

## 

- Arreglo del fallo.

## 

- Se solucionó un problema con las imágenes de fondo.
- Se corrigió un error con el widget de número predeterminado.
- Se corrigió el error de inclusión con algunos complementos (** por ejemplo).

## 

- Comprobación mejorada de la versión de nodejs/npm.

## 

- Se solucionó un problema al mostrar el estado de un comando de información en la configuración avanzada del comando si el valor es 0.

## 

### 4.3 : Prerrequisitos

- 
- PHP 7.3

### 4.3 : Noticias / Mejoras

- **Herramientas / Escenarios** : Modal para editar ctrl+clic en campos editables de bloques/acciones.
- **Herramientas / Escenarios** : Adición de un menú contextual en un escenario para activar/desactivar, cambiar grupo, cambiar objeto principal.
- **Herramientas / Objetos** : Se agregó un menú contextual en un objeto para administrar la visibilidad, cambiar el objeto principal y mover.
- **Herramientas / Reemplazar** : Nueva herramienta de sustitución de equipos y mandos.
- **Análisis / Cronología** : Se agregó un campo de búsqueda para filtrar la visualización.
- **** : Se agregó un botón para copiar los derechos de un usuario limitado a otro.
- **** : Capacidad para informar sobre la salud de Jeedom.
- **** : Capacidad para informar sobre equipos alertados.
- **Actualización** : Capacidad para ver desde Jeedom los paquetes OS / PIP2 / PIP3 / NodeJS que se pueden actualizar e iniciar la actualización (cuidado con la función arriesgada y en versión beta).
- **Comando de alerta** : Se agregó una opción para recibir un mensaje en caso de fin de alerta.
- **** : Posibilidad de deshabilitar la instalación de dependencias por complemento.
- **** : jeeFrontEnd{}, jeephp2js{}, correcciones de errores menores y optimizaciones.

### 4.3 : WebApp

- Integración de notas.
- Posibilidad de mostrar los mosaicos solo en una columna (configuración en la pestaña de configuración de la interfaz de jeedom)).

### 4.3 : Autre

- **** : Actualizar Font Awesome 5.13.1 a 5.15.4.

### 4.3 : Notes

- Para usuarios que usan menús en sus diseños en el formulario :

``<a onClick="planHeader_id=15; displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1imagesnewhome.png" height=30px><div><br><li><a>``

Ahora debes usar:

``<a onClick="jeephp2js.planHeader_id=15; jeeFrontEnd.plan.displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1imagesnewhome.png" height=30px><div><br><li><a>``

 [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.3).

Entrada en el blog [](https:blog.jeedom.com6739-jeedom-4-3)

# Registro de cambios Jeedom V4.2

## 

- Se corrigió un error en los resúmenes.

## 

- Se agregó un sistema para corregir paquetes pip durante una mala instalación.

## 

- Se agregó administración de versiones para paquetes de python (permite corregir el problema con el complemento zigbee).

## 

- Actualización de nodejs.

## 

- Núcleo de corrección de errores : Acceso de usuario limitado a diseños y vistas.
- Interfaz de usuario de corrección de errores : Visualización de bloques A en Chrome.
-  : Enlace a la documentación cuando el complemento está en versión beta.

## 

- Núcleo de corrección de errores : Guión : Combinar elementos pegados en algunos casos.
- Núcleo de corrección de errores : Creación de archivos con editor de archivos.
-  : Mayor demora para contactar con el servicio de monitoreo (permite aligerar la carga en los servidores en la nube)).

## 

- Interfaz de usuario de corrección de errores : Guión : Agregar la acción ** en el modo de selección.
- Núcleo de corrección de errores : Retraso fijo en las historias calculadas.
-  : Instalación de dependencias del complemento zigbee.

## 

- Interfaz de usuario de corrección de errores : Investigación eliminada al activar la opción de registro sin procesar.
- Interfaz de usuario de corrección de errores : No se puede descargar el registro vacío.
- Interfaz de usuario de corrección de errores : 

- Núcleo de corrección de errores : Tamaño de las imágenes de fondo en relación al tamaño del diseño.
- Núcleo de corrección de errores : Se solucionó un problema con las claves de API aún deshabilitadas.

## 

- Interfaz de usuario de corrección de errores :  *Ocultar en el escritorio* resúmenes.
- Interfaz de usuario de corrección de errores : Historiques: Respetar las escalas al hacer zoom.

- Núcleo de corrección de errores : Solucionó un problema de tamaño de copia de seguridad con el complemento Atlas.

- Mejora : Creación de claves api por defecto inactivas (si la solicitud de creación no proviene del complemento).
- Mejora : Tamaño de copia de seguridad agregado en la página de administración de copias de seguridad.

## 

- Interfaz de usuario de corrección de errores : Mostrar la carpeta de una acción en la línea de tiempo.

- Núcleo de corrección de errores : Visualización de la clave API de cada complemento en la página de configuración.
- Núcleo de corrección de errores : Añadir opción ** en un gráfico en Diseño.
- Núcleo de corrección de errores : Curva de mosaico con valor negativo.
- Núcleo de corrección de errores : Error 403 al reiniciar.

- Mejora : Visualización del valor de activación en el registro de escenarios.

## 

- Interfaz de usuario de corrección de errores : Posición en el resumen domótico de objetos de nueva creación.
- Interfaz de usuario de corrección de errores : Problemas de visualización de diseño 3D.

- Núcleo de corrección de errores : Nuevas propiedades de resumen no definidas.
- Núcleo de corrección de errores : Actualizar valor al hacer clic en el rango de widgets **.
- Núcleo de corrección de errores : Editando archivo vacío (0b).
- Núcleo de corrección de errores : Inquietudes de detectar la IP real del cliente a través del DNS de Jeedom. Se recomienda reiniciar la caja después de la actualización para que esta se active.

## 

- Interfaz de usuario de corrección de errores : Corrección de widgets *predeterminado numérico* (cmdName demasiado largo).
- Interfaz de usuario de corrección de errores : Pasar variables css *--url-iconsDark*  *--url-iconsLight* en absoluto (Bug Safari MacOS).
- Interfaz de usuario de corrección de errores : Posición de las notificaciones en *centro Superior*.

- Núcleo de corrección de errores : Paso predeterminado para widgets ** a la 1.
- Núcleo de corrección de errores : La actualización de la página indica *en curso*  *FIN DE ACTUALIZAR ERROR* (actualización de registro).
- Núcleo de corrección de errores : Modificación de valor de un historial.
- Núcleo de corrección de errores : Problemas solucionados con la instalación de dependencias de python.

- Mejora : Nuevas opciones en gráficos de diseño para escala y agrupación del eje Y.

-  : Actualización de biblioteca ** 

## 

- Interfaz de usuario de corrección de errores : Resumen de domótica, borrar historial de eliminación.
- Interfaz de usuario de corrección de errores :  *No mostrar* en el modal *primer usuario*.
- Interfaz de usuario de corrección de errores : Curva en mosaicos de fondo en una vista.
- Interfaz de usuario de corrección de errores : Historias, escala de ejes cuando se reduce el zoom.
- Interfaz de usuario de corrección de errores : Historiales, apilamiento en vistas.
- Interfaz de usuario de corrección de errores : Visualización del nombre de usuario al eliminar.
- Interfaz de usuario de corrección de errores : Opciones para mostrar números sin *icono si es nulo*.

- Núcleo de corrección de errores : Compruebe Apache mod_alias.

- Mejora : Opción en configuración para autorizar fechas a futuro en las historias.

## 

### 4.2 : Prerrequisitos

- 
- PHP 7.3

### 4.2 : Noticias / Mejoras

- **Síntesis** : Posibilidad de configurar objetos para ir a un ** o un ** desde la síntesis.
- **** : La ventana de configuración del dispositivo (modo de edición) ahora le permite configurar widgets móviles y tipos genéricos.
- **** : Internacionalización de Widgets de terceros (código de usuario). ver [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.2).
- **Análisis / Historia** : Posibilidad de comparar un historial durante un período determinado.
- **Análisis / Historia** : Visualización de múltiples ejes en Y. Opción para que cada eje tenga su propia escala, agrupada por unidad o no.
- **Análisis / Historia** : Posibilidad de ocultar los ejes Y. Menú contextual en las leyendas con solo visualización, ocultación de ejes, cambio de color de la curva.
- **Análisis / Historia** : Los cálculos del historial guardado ahora se muestran encima de la lista de comandos, de la misma manera que estos.
- **Análisis / Equipo** : Los pedidos huérfanos ahora muestran su nombre y fecha de eliminación si aún están en el historial de eliminación, así como un enlace al escenario o equipo afectado.
- **Análisis / Registros** : Numeración de líneas de registro. Posibilidad de mostrar el registro sin procesar.
- **** : Coloración de troncos según ciertos eventos. Posibilidad de mostrar el registro sin procesar.
- **Resúmenes** : Posibilidad de definir un icono diferente cuando el resumen es nulo (sin persianas abiertas, sin luz encendida, etc).
- **Resúmenes** : Posibilidad de no mostrar nunca el número a la derecha del icono, o solo si es positivo.
- **Resúmenes** : El cambio del parámetro de resumen en la configuración y en los objetos ahora es visible, sin esperar un cambio en el valor de resumen.
- **Resúmenes** : Ahora es posible configurar [acciones sobre resúmenes](/es_ES/conceptsummary#Actions  résumés) (ctrl + clic en un resumen) gracias a los virtuales.
- **** : Vista previa de archivos PDF.
- **Tipos de equipo** : [Nueva página](/es_ES/core4.2types) **Herramientas → Tipos de equipos** permitiendo la asignación de tipos genéricos a dispositivos y comandos, con soporte para tipos dedicados a complementos instalados (ver [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.2)).
- **Selección de ilustraciones** : Nueva ventana global para la elección de ilustraciones *(iconos, imágenes, fondos)*.
- **Pantalla de mesa** : Adición de un botón a la derecha de la búsqueda en las páginas ** *Escenarios* ** **  ** para cambiar al modo de mesa. Esto se almacena mediante una cookie o en **Configuración → Sistema → Configuración / Interfaz, Opciones**. Los complementos pueden usar esta nueva función del Core. ver [Desarrollador de documentos](https:doc.jeedom.com/es_ES/devcore4.2).
- **Configuración del equipo** : Posibilidad de configurar una curva de historial en la parte inferior del mosaico de un dispositivo.
- **** : Posibilidad de realizar un cálculo sobre una acción de comando de tipo slider antes de la ejecución del comando.
- **Complementos / Gestión** : Visualización de la categoría de complementos y un enlace para abrir directamente su página sin pasar por el menú Complementos.
- **Guión** : Función de respaldo de código (*plegado de código*) en *Bloques de código*. Atajos Ctrl + Y y Ctrl + I.
- **Guión** : Copiar / pegar y deshacer / rehacer la corrección de errores (reescritura completa).
- **Guión** : Agregar funciones de cálculo ``averageTemporal(commande,période)`` Y ``averageTemporalBetween(commande,start,end)`` permitiendo obtener la media ponderada por la duración del período.
- **Guión** : Se agregó soporte para tipos genéricos en escenarios.
	- Desencadenar : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0``
	-  ``genericType(LIGHT_STATE,#[Salon]#) > 0``
	-  ``genericType``
- **** : Los complementos ahora pueden solicitar parámetros específicos específicos de los objetos.
- **** : Los complementos ahora pueden solicitar parámetros específicos específicos para los usuarios.
- **** : Capacidad para gestionar los perfiles de diferentes usuarios de Jeedom desde la página de gestión de usuarios.
- **** : Capacidad para ocultar objetos / ver / diseñar / diseño 3d para usuarios limitados.
- **Centro de actualizaciones** : El Centro de actualizaciones ahora muestra la fecha de la última actualización.
- **Agregar al usuario que realiza una acción** : Además en las opciones de ejecución del comando de la identificación y el nombre de usuario que inician la acción (visible en el evento de registro, por ejemplo)
- **Complemento de documentación y registro de cambios beta** : Gestión de documentación y registro de cambios para complementos en versión beta. Atención, en beta, el registro de cambios no tiene fecha.
- **** : Integración del complemento JeeXplorer en el Core. Ahora se usa para código de widget y personalización avanzada.
- **** : Nueva opción en configuración / interfaz para no colorear el banner del título del equipo.
- **** : Posibilidad de configurar fondos de pantalla en las páginas Tablero, Análisis, Herramientas y su opacidad según el tema.
- ****: Agregar DNS de Jeedom basado en Wireguard en lugar de Openvpn (Administración / redes). Más rápido y más estable, pero aún en prueba. Tenga en cuenta que actualmente esto no es compatible con Jeedom Smart.
- **** : Configuración de OSDB: Adición de una herramienta para la edición masiva de equipos, comandos, objetos, escenarios.
- **** : Configuración de OSDB: Agregar un constructor de consultas SQL dinámico.
- ****: Capacidad para deshabilitar el monitoreo en la nube (Administración / Actualizaciones / Mercado).
- **** : Además de ``jeeCli.php`` en la carpeta core / php de Jeedom para administrar algunas funciones de la línea de comandos.
- *Grandes mejoras en la interfaz en términos de rendimiento / capacidad de respuesta. jeedomUtils {}, jeedomUI {}, menú principal reescrito en CSS puro, eliminación de initRowWorflow (), simplificación del código, correcciones de CSS para pantallas pequeñas, etc.*

### 4.2 : Widgets principales

- Ahora se puede acceder a la configuración de los widgets para la versión móvil desde la ventana de configuración del equipo en el modo de edición del tablero.
- Los parámetros opcionales disponibles en los widgets ahora se muestran para cada widget, ya sea en la configuración del comando o desde el modo de edición del tablero.
- Muchos Core Widgets ahora aceptan configuraciones de color opcionales. (Control deslizante horizontal y vertical, indicador, brújula, lluvia, obturador, control deslizante de plantillas, etc.).
- Widgets principales con visualización de ** ahora admite un parámetro opcional ** : ** para mostrar una fecha relativa (ayer a las 4:48 p.m., último lunes a las 2:00 p.m., etc).
- Los widgets de tipo Cursor (acción) ahora aceptan un parámetro opcional ** para definir el paso de cambio en el cursor.
- El widget **acción.deslizador.valor** ahora está disponible en el escritorio, con un parámetro opcional **, lo que lo convierte en un ** .
- El widget **** (**) se ha rehecho en CSS puro y se ha integrado en dispositivos móviles. Por lo tanto, ahora son idénticos en computadoras de escritorio y dispositivos móviles.

### 4.2 : Respaldo en la nube

Hemos agregado una confirmación de la contraseña de la copia de seguridad en la nube para evitar errores de entrada (como recordatorio, el usuario es el único que conoce esta contraseña, en caso de olvidarla, Jeedom no puede recuperarla ni acceder a las copias de seguridad).

>****
>
> Después de la actualización, DEBE ir a Configuración → Sistema → pestaña Actualización de configuración / Mercado e ingresar la confirmación de la contraseña de la copia de seguridad en la nube para que se pueda hacer.

### 4.2 : Seguridad

- Para aumentar significativamente la seguridad de la solución Jeedom, el sistema de acceso a archivos ha cambiado. Antes de que ciertos archivos estuvieran prohibidos en determinadas ubicaciones. Desde v4.2, los archivos están explícitamente permitidos por tipo y ubicación.
- Cambie a nivel de API, anteriormente "tolerante" si llegó con la clave principal que indica el complemento XXXXX. Este ya no es el caso, debes llegar con la clave correspondiente al plugin.
- En la API http, puede indicar un nombre de complemento en el tipo, esto ya no es posible. El tipo correspondiente al tipo de solicitud (escenario, eqLogic, cmd, etc.) debe corresponder al complemento. Por ejemplo, para el complemento virtual que tenía ``type=virtual`` en la URL ahora es necesario reemplazar por ``plugin=virtualYtype=event``.
- Refuerzo de sesiones : Cambiar a sha256 con 64 caracteres en modo estricto.

El equipo de Jeedom es consciente de que estos cambios pueden tener un impacto y ser embarazosos para usted, pero no podemos comprometer la seguridad.
Los complementos deben respetar las recomendaciones sobre la estructura de árbol de carpetas y archivos : [](https:doc.jeedom.com/es_ES/devplugin_template).

[Blog: Jeedom 4 introducción.2 : la seguridad](https:blog.jeedom.com6165-introduction-jeedom-4-2-la-securite)

# Registro de cambios Jeedom V4.1

## 

- Armonización de plantillas de widgets para comandos de acción / predeterminados

## 

- Corrección de una brecha de seguridad gracias @Maxime Rinaudo y @Antoine Cervoise de Synacktiv (www.synacktiv.com)

## 

- Se corrigió un problema de instalación de dependencia de apt en Smart debido al cambio de certificado en Let's encrypt.

## 

- Se corrigió el problema de instalación de la dependencia de apt.

## 

- Revisión de la opción de configuración de comandos **Gestionar la repetición de valores** se convierte en **Repita valores idénticos (Sí|Non)**. [Consulte el artículo del blog para obtener más detalles](https:blog.jeedom.com5414-nouvelle-gestion-de-la-repetition-des-valeurs)

## 

- Errores corregidos en el archivo del historial
- Se corrigió un problema de caché que podía desaparecer durante un reinicio
- Se corrigió un error en la gestión de repeticiones de comandos binarios : en ciertos casos si el equipo envía dos veces 1 o 0 seguidos, solo se tuvo en cuenta el primer ascenso. Tenga en cuenta que esta corrección de errores puede provocar una sobrecarga de la CPU. Por lo tanto, es necesario actualizar los complementos también (Philips Hue en particular) para otros casos (activación de múltiples escenarios, mientras que este no era el caso antes de la actualización). Pregunta sobre la repetición de valores (configuración avanzada del comando) y cámbielo a "nunca repetir" para encontrar la operación anterior.

## 

- Adición de un sistema que permite a Jeedom SAS comunicar mensajes a todos los Jeedom
- Cambiar el DNS de Jeedom al modo de alta disponibilidad

## 

- Corrección de errores de desplazamiento horizontal en diseños.
- Corrección de errores de desplazamiento en las páginas de equipos de complementos.
- Corrección de errores de la configuración de color en los enlaces de vista / diseño en un diseño.
- Corrección de errores y optimización de la línea de tiempo.
- Revisión de diseños móviles con tres dedos ahora limitada a perfiles de administrador.

## 

- Eliminación de corrección de errores de zona en una vista.
- Error de corrección de errores js que puede aparecer en navegadores antiguos.
- Corrección de error cmd.info.numeric.default.html si el comando no está visible.
- Página de inicio de sesión de corrección de errores.

## 

- Corrección de errores históricos en diseños.
- Búsquedas de corrección de errores en Análisis / Historial.
- Búsqueda de corrección de errores en una variable, enlace a un dispositivo.
- Corrección de errores de resúmenes en color sobre síntesis.
- Corrección de errores en los comentarios del escenario con json.
- Corrección de errores en las actualizaciones resumidas en las vistas previas del modo Panel.
- Corrección de errores de elementos ** en un diseño.
- Se agregaron opciones de agrupación por tiempo para gráficos en vistas.
- Conservación del contexto de síntesis al hacer clic en los resúmenes.
- Centrado de imágenes de síntesis.

## 

### 4.1 : Prerrequisitos

- 

### 4.1 : Noticias / Mejoras

- **Síntesis** : Agregar una nueva página **Inicio → Resumen** Ofrece un resumen visual global de las partes, con acceso rápido a resúmenes.
- **** : Adición de un motor de búsqueda en **Herramientas → Buscar**.
- **** : El modo de edición ahora inserta el mosaico movido.
- **** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **** : Ahora podemos hacer clic en el ** widgets de acciones de tiempo para abrir la ventana del historial del comando de información vinculada.
- **** : El tamaño del mosaico de un equipo nuevo se adapta a su contenido.
- **** : Agregar (¡atrás!) Un botón para filtrar los elementos mostrados por categoría.
- **** : Ctrl Click en una información abre la ventana de historial con todos los comandos historizados del equipo visibles en el mosaico. Ctrl Haga clic en una leyenda para mostrar solo esta, Alt Haga clic para mostrarlas todas.
- **** : Rediseño de la visualización del árbol de objetos (flecha a la izquierda de la búsqueda).
- **** : Posibilidad de desenfocar imágenes de fondo (Configuración -> Interfaz).
- **Herramientas / widgets** : La funcion *Aplicar en* muestra los comandos vinculados marcados, al desmarcar uno se aplicará el widget principal predeterminado a este comando.
- **** : Agregar un widget principal **.
- **** : Agregar un widget principal **.
- **Centro de actualizaciones** : Las actualizaciones se verifican automáticamente cuando se abre la página si tiene 120 minutos de antigüedad.
- **Centro de actualizaciones** : La barra de progreso ahora está en la pestaña *Core y plugins*, y el registro se abre por defecto en la pestaña **.
- **Centro de actualizaciones** : Si abre otro navegador durante una actualización, la barra de progreso y el registro lo indican.
- **Centro de actualizaciones** : Si la actualización finaliza correctamente, se muestra una ventana que solicita volver a cargar la página.
- **Actualizaciones principales** : Implementación de un sistema para limpiar viejos archivos Core no utilizados.
- **Guión** : Agregar un motor de búsqueda (a la izquierda del botón Ejecutar).
- **Guión** : Adición de la función de edad (da la edad del valor del pedido).
- **Guión** : *stateChanges()* ahora acepta el periodo ** (medianoche hasta ahora), **  ** (por 1 día).
- **Guión** :  *estadísticas (), promedio (), máximo (), mínimo (), tendencia (), duración()* : Bugfix durante el período **, y acepta ahora ** (por 1 día).
- **Guión** : Posibilidad de desactivar el sistema de cotización automática (Configuración → Sistema → Configuración : Equipements).
- **Guión** : Viendo un ** si no se configura ningún activador.
- **Guión** : Corrección de errores de ** en el bloque copiar / pegar.
- **Guión** : Copiar / pegar bloque entre diferentes escenarios.
- **Guión** : Las funciones de deshacer / rehacer ahora están disponibles como botones (al lado del botón de creación de bloque).
- **Guión** :  adición de "Exportación histórica" (exportHistory)
- **Ventana de variables de escenario** : Clasificación alfabética en la apertura.
- **Ventana de variables de escenario** : Ahora se puede hacer clic en los escenarios utilizados por las variables, con la apertura de la búsqueda en la variable.
- **Análisis / Historia** : Ctrl Haga clic en una leyenda para mostrar solo este historial, Alt Haga clic para mostrarlos todos.
- **Análisis / Historia** : Las opciones *agrupación, tipo, variación, escalera* están activos solo con una sola curva mostrada.
- **Análisis / Historia** : Ahora podemos usar la opción ** con la opción **.
- **Análisis / Registros** : Nueva fuente tipo monoespacio para registros.
- **** : Posibilidad de poner escenarios.
- **** : El modo de edición ahora inserta el mosaico movido.
- **** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **** : El orden de visualización ahora es independiente del que se muestra en el Panel de control.
- **** : Separación de páginas de historia y cronología.
- **** : Integración de la línea de tiempo en DB por razones de confiabilidad.
- **** : Gestión de múltiples líneas de tiempo.
- **** : Rediseño gráfico completo de la línea de tiempo (Escritorio / Móvil).
- **Resumen global** : Vista de resumen, soporte para resúmenes de un objeto diferente o con un objeto raíz vacío (escritorio y aplicación web).
- **Herramientas / Objetos** : Nueva pestaña *Resumen por equipos*.
- **Resumen de domótica** : Equipos de complementos desactivados y sus controles ya no tienen los iconos a la derecha (configuración de equipos y configuración avanzada).
- **Resumen de domótica** : Posibilidad de buscar en categorías de equipos.
- **Resumen de domótica** : Posibilidad de mover varios equipos de un objeto a otro.
- **Resumen de domótica** : Posibilidad de seleccionar todo el equipo de un objeto.
- **Motor de tareas** : En la pestaña *Demonio*, los complementos deshabilitados ya no aparecen.
- **** : El uso de ** si está disponible.
- **** : Posibilidad de exportar cronogramas.
- **** :  ** ahora está en la pestaña *General*.
- **** :  ** ahora está en la pestaña **.
- **Ventana de configuración avanzada del equipo** : Cambio dinámico de la configuración de la centralita.
- **** : Nueva categoría **.
- **** : Posibilidad de invertir comandos tipo cursor (info y acción)
- **** : Posibilidad de agregar clase css a un mosaico (consulte la documentación del widget).
- **Sobre ventana** : Adición de accesos directos al registro de cambios y preguntas frecuentes.
- Widgets / Objetos / Escenarios / Interacciones / Páginas de complementos :
	- Ctrl Clic / Clic Center en un widget, objeto, escenarios, interacción, equipo de complemento : Se abre en una pestaña nueva.
	- Ctrl Clic / Clic Center también disponible en sus menús contextuales (en las pestañas).
- Nueva página ModalDisplay :
	- Menú de análisis : Ctrl Click / Click Center en *Tiempo real* : Abra la ventana en una pestaña nueva, en pantalla completa.
	- Menú de herramientas : Ctrl Click / Click Center en **, *Probador de expresión*, **, ** : Abra la ventana en una pestaña nueva, en pantalla completa.
- Bloque de código, Editor de archivos, Personalización avanzada : Adaptación del tema oscuro.
- Ventana de selección de imágenes mejorada.

### 4.1 : WebApp
- Integración de la nueva página de resumen.
- Página de escenarios, un clic en el título del escenario muestra su registro.
- Ahora podemos seleccionar / copiar parte de un registro.
- En la búsqueda en un registro, agregue un botón x para cancelar la búsqueda.
- Persistencia del cambio de tema (8h).
- En un diseño, un clic con tres dedos vuelve a la página de inicio.
- Visualización de escenarios por grupo.
- Nueva fuente tipo monoespacio para registros.
- Muchas correcciones de errores (UI, vertical / horizontal iOS, etc.).

### 4.1 : Autres
- **** : Adaptaciones en línea con v4 y v4.1.
- **** : Nueva página *Atajos de teclado / mouse* incluyendo un resumen de todos los atajos en Jeedom. Accesible desde el Dashboard doc o las preguntas frecuentes.
- **** : Actualizar HighStock v7.1.2 a v8.2.0.
- **** : Actualizar jQuery v3.4.1 a v3.5.1.
- **** : Actualizar Font Awesome 5.9.0 a 5.13.1.
- **** :  adición de una opción para prohibir que una clave api de un complemento ejecute métodos centrales (general)
- Asegurando solicitudes Ajax.
- Asegurar las llamadas a la API.
- Correcciones de errores.
- Numerosas optimizaciones de rendimiento de escritorio / móvil.

### 4.1 : Changements
- La funcion **escenario-> getHumanName()** de la clase de escenario php ya no regresa *[objeto] [grupo] [nombre]*  *[grupo] [objeto] [nombre]*.
- La funcion **escenario-> byString()** ahora debe llamarse con la estructura *[grupo] [objeto] [nombre]*.
- Las funciones **red-> getInterfaceIp () red-> getInterfaceMac () red-> getInterfaces()** han sido reemplazados por **network-> getInterfacesInfo()**


# Registro de cambios Jeedom V4.0

## 

- Nueva migración de kernel buster + para smart y Pro v2
- Verificación de la versión del sistema operativo durante las actualizaciones importantes de Jeedom


## 

- Se solucionó un problema al aplicar una plantilla de escenario
- Adición de una opción para deshabilitar la verificación SSL al comunicarse con el mercado (no se recomienda pero es útil en ciertas configuraciones de red específicas)
- Se solucionó un problema con el historial de archivo si el modo de suavizado era para siempre
- Correcciones de errores
- Corrección del comando trigger () en escenarios para que devuelva el nombre del disparador (sin el #) en lugar del valor, para el valor debes usar triggerValue()

## 

- Eliminación del nuevo sistema DNS en eu.jeedom.enlace siguiendo demasiados operadores que prohíben flujos http2 permanentes

## 

- Errores corregidos en los widgets de tiempo
- Aumento del número de contraseñas incorrectas antes de prohibir (evita problemas con la aplicación web al rotar claves API)

## 

- Refuerzo de la seguridad de las cookies
- Usar cromo (si está instalado) para informes
- Se solucionó un problema con el cálculo de la hora del estado en los widgets si la zona horaria de jeedom no es la misma que la del navegador
- Arreglo del fallo

## 

- El nuevo dns (\*. Eu.jeedom.enlace) se convierte en el DNS primario (el antiguo DNS todavía funciona)

## 

- Inicio de la actualización al nuevo sitio de documentación

## 

- Arreglo del fallo.

## 

- Corrección de errores (actualice para hacerlo absolutamente si está en 4.0.51).

## 

- Arreglo del fallo.
- Optimización del futuro sistema DNS.

## 

- Posibilidad de elegir el motor Jeedom TTS y posibilidad de tener plugins que ofrezcan un nuevo motor TTS.
- Compatibilidad mejorada con webview en la aplicación móvil.
- Arreglo del fallo.
- Actualización de documentos.

## 

- Probador de expresión mejorado.
- Actualización del repositorio en smart.
- Arreglo del fallo.

## 

- Traducciones mejoradas.
- Arreglo del fallo.
- Restauración de copia de seguridad en la nube mejorada.
- La restauración en la nube ahora solo recupera la copia de seguridad local, dejando la opción de descargarla o restaurarla.

## 

- Traducciones mejoradas.
- Errores corregidos en las plantillas de escenarios.

## 

### 4.0 : Prerrequisitos

- Estiramiento de Debian 9

### 4.0 : Noticias / Mejoras

- Rediseño completo del tema (Core 2019 Light / Dark / Legacy).
- Posibilidad de cambiar el tema automáticamente en función de la hora.
- En el móvil, el tema puede cambiar dependiendo del brillo (Requiere activar *sensor adicional genérico* en cromo, página de cromo:flags).<br><br>
- Mejora y reorganización del menú principal.
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Agregue un botón para acceder al probador de expresiones.
- Menú de herramientas : Agregar un botón para acceder a las variables.<br><br>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (panel, escenarios, objetos, widgets, interacciones, complementos) ahora están activos cuando se abre la página, lo que le permite escribir una búsqueda directamente.
- Se agregó un botón X en los campos de búsqueda para cancelar la búsqueda.
- Durante una búsqueda, la clave ** cancelar búsqueda.
-  : En el modo de edición, el control de búsqueda y sus botones se desactivan y se fijan.
-  : En el modo de edición, un clic de un botón ** a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
-  : La ejecución del comando en un mosaico ahora se indica con el botón **. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
-  : Los mosaicos indican un comando de información (histórico, que abrirá la ventana Historial) o una acción al pasar el mouse.
-  : La ventana del historial ahora le permite abrir este historial en Análisis / Historial.
-  : La ventana del historial mantiene su posición / dimensiones al reabrir otro historial.
- Ventana de configuración de comandos: Ctrl + clic en "Guardar" cierra la ventana después.
- Ventana de configuración del equipo: Ctrl + clic en "Guardar" cierra la ventana después.
- Agregar información de uso al eliminar un dispositivo.
-  : Opción agregada para usar colores personalizados.
-  : Adición de un menú contextual en las pestañas (cambio rápido de objeto).
-  : Adición de un menú contextual en las pestañas (cambio rápido de interacción).
-  : Adición de un menú contextual en las pestañas (cambio rápido de equipo).
-  : En la página de administración de complementos, un punto naranja indica los complementos en la versión no estable.
- Mejoras en la tabla con opción de filtro y clasificación.
- Posibilidad de asignar un icono a una interacción.
- Cada página de Jeedom ahora tiene un título en el idioma de la interfaz (pestaña del navegador).
- Prevención del autocompletado en los campos de 'Código de acceso''.
- Gestión de funciones *Página anterior / Página siguiente* desde el navegador.<br><br>
-  : Rediseño del sistema de widgets (menú Herramientas / Widgets).
-  : Posibilidad de reemplazar un widget por otro en todos los comandos que lo utilizan.
-  : Posibilidad de asignar widgets a varios comandos.
-  : Agregar un widget de información numérica horizontal.
-  : Agregar un widget de información numérica vertical.
-  : Adición de un widget de información de viento / brújula numérica (gracias @thanaus).
-  : Se agregó un widget de información de lluvia numérica (gracias @thanaus)
-  : Visualización del widget de obturador de información / acción proporcional al valor.<br><br>
-  : Mejora y reorganización de pestañas.
-  : Adición de muchos ** (aide).
-  : Agregar un motor de búsqueda.
-  : Se agregó un botón para vaciar la caché de widgets (pestaña Caché).
-  : Se agregó una opción para deshabilitar la caché de widgets (pestaña Caché).
-  : Posibilidad de centrar verticalmente el contenido de los mosaicos (pestaña Interfaz).
-  : Se agregó un parámetro para la purga global de registros (pestaña Pedidos).
-  : Cambio de #message# a #subject# en Configuración / Registros / Mensajes para evitar duplicar el mensaje.
-  : Posibilidad en los resúmenes de agregar una exclusión de pedidos que no se han actualizado durante más de XX minutos (ejemplo para el cálculo de promedios de temperatura si un sensor no ha reportado nada durante más de 30min se excluirá del cálculo)<br><br>
- Guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque.
- Guión : Posibilidad haciendo Ctrl + clic en el botón *ejecución* guárdelo, ejecútelo y muestre el registro (si el nivel de registro no está en **).
- Guión : Confirmación de eliminación de bloque. Ctrl + clic para evitar la confirmación.
- Guión : Adición de una función de búsqueda en bloques de código.  : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Mayús + G
- Guión : Posibilidad de condensar los bloques.
- Guión : La acción 'Agregar bloque' cambia a la pestaña Escenario si es necesario.
- Guión : Nuevas funciones de copiar / pegar en bloque. Ctrl + clic para cortar / reemplazar.
- Guión : Ya no se agrega un nuevo bloque al final del escenario, sino después del bloque donde estaba antes de hacer clic, determinado por el último campo en el que hizo clic.
- Guión : Configurar un sistema Deshacer / Rehacer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Guión : Eliminar el uso compartido de escenarios.
- Guión : Mejora de la ventana de gestión de plantillas de escenarios.<br><br>
- Análisis / Equipo : Adición de un motor de búsqueda (pestaña Baterías, búsqueda por nombres y padres).
- Análisis / Equipo : Ahora se puede hacer clic en la zona de día del calendario / equipo para acceder directamente al cambio de batería (s).
- Análisis / Equipo : Agregar un campo de búsqueda.<br><br>
- Centro de actualizaciones : Advertencia en la pestaña 'Núcleo y complementos' y / o 'Otros' si hay una actualización disponible. Cambie a 'Otros' si es necesario.
- Centro de actualizaciones : diferenciación por versión (estable, beta, ...).
- Centro de actualizaciones : adición de una barra de progreso durante la actualización.<br><br>
- Resumen de domótica : El historial de eliminaciones ahora está disponible en una pestaña (Resumen - Historial).
- Resumen de domótica : Rediseño completo, posibilidad de ordenar objetos, equipos, pedidos.
- Resumen de domótica : Adición de ID de equipo y pedido, para mostrar y buscar.
- Resumen de domótica : Exportación CSV del objeto principal, identificación, equipo y su identificación, comando.
- Resumen de domótica : Posibilidad de hacer visibles o no uno o más comandos.<br><br>
-  : Posibilidad de especificar el orden (posición) del **  *Diseños 3d* (Editar, configurar diseño).
-  : Adición de un campo CSS personalizado en los elementos del **.
-  : Se movieron las opciones de visualización en Diseño de la configuración avanzada, en los parámetros de visualización de la **. Esto con el fin de simplificar la interfaz y permitir tener diferentes parámetros mediante **.
-  : Mover y cambiar el tamaño de componentes en ** tiene en cuenta su tamaño, con o sin magnetización.<br><br>
- Adición de un sistema de configuración masiva (utilizado en la página Equipo para configurar alertas de comunicaciones en ellos)

### 4.0 : Autres

- **** : Actualización de jquery 3.4.1
- **** : Actualizar CodeMiror 5.46.0
- **** : Actualizar tablesorter 2.31.1
- Aligeramiento general (estilos CSS / en línea, refactorización, etc.) y mejoras de rendimiento.
- Adición de compatibilidad global de Jeedom DNS con una conexión a Internet 4G.
- Numerosas correcciones de errores.
- Correcciones de seguridad.

### 4.0 : Changements

- Elimine Font Awesome 4 para mantener solo Font Awesome 5.
- El complemento del widget no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han tomado internamente en el núcleo). Más información [](https:www.Jeedom.comblog4368-les-widgets-en-v4).

>****
>
> Si después de la actualización tiene un error en el Tablero, intente reiniciar su caja para que tenga en cuenta las nuevas adiciones de componentes.
