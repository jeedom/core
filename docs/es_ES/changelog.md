# Registro de cambios Jeedom V4.4

>**IMPORTANTE**
>
>Aunque no sean necesariamente visibles a primera vista, la versión 4.4 de Jeedom trae cambios importantes con una interfaz que ha sido completamente reescrita para un control total y, sobre todo, una ganancia inigualable en fluidez de navegación. También se ha revisado la gestión de dependencias PHP para poder mantenerlas actualizadas automáticamente. Incluso si el equipo de Jeedom y los probadores beta han hecho muchas pruebas, hay tantas versiones de jeedom como jeedom... Por lo tanto, no es posible garantizar el correcto funcionamiento en el 100% de los casos. Sin embargo, en caso de problema puede [abrir un tema en el foro con la etiqueta `v4_4`](https://community.jeedom.com/) o contacta con soporte desde tu perfil de mercado *(siempre que tenga un paquete de servicio o superior)*.

## 4.4.1

- Soporte PHP8.
- Verificar la versión básica mínima requerida antes de instalar o actualizar un complemento.
- Agregar un botón **Asistencia** en la página de configuración del complemento *(Creación automática de una solicitud de ayuda en el foro)*

### 4.4 : Prerrequisitos

- Debian 10 Buster
- PHP 7.3

### 4.4 : Noticias / Mejoras

- **Histórico** : El modal de historial y la página de historial permiten usar botones *Semana, Mes, Año* para recargar dinámicamente un historial más grande.
- **Ventana de selección de imagen** : Se agregó un menú contextual para enviar imágenes y crear, renombrar o eliminar una carpeta.
- **Ventana de selección de iconos** : Posibilidad de agregar un parámetro `ruta` al llamar a `jeedomUtils.chooseIcon` por un complemento para mostrar solo sus iconos.
- **Tablero** : Capacidad para mostrar varios objetos uno al lado del otro *(Ajustes → Sistema → Configuración / Interfaz)*.
- **Tablero** : La ventana de edición de mosaicos del modo de edición permite cambiar el nombre de los comandos.
- **Tablero** : En el diseño de la tabla, posibilidad de insertar atributos HTML *(colspan/rowspan en particular)* para cada celda.
- **Equipo** : Capacidad para deshabilitar las plantillas de widgets de los complementos que los usan para volver a la pantalla predeterminada de Jeedom *(ventana de configuración del dispositivo)*.
- **Equipo** : El equipo inactivo desaparece automáticamente de todas las páginas. El equipo reactivado vuelve a aparecer en el tablero si el objeto principal ya está presente.
- **Equipo** : El equipo invisible desaparece automáticamente del tablero. El equipo que se vuelve a mostrar vuelve a aparecer en el tablero si el objeto principal ya está presente.
- **Análisis > Equipos / Equipos en alerta** : Los dispositivos que entran en alerta aparecen automáticamente y los que salen de alerta desaparecen automáticamente.
- **Centro de mensajes** : Los mensajes centrales sobre anomalías ahora informan una acción, por ejemplo, un enlace para abrir el escenario ofensivo, o equipo, configuración de complemento, etc.
- **Objeto** : Eliminar o crear un resumen da como resultado la actualización del resumen global y el tema.
- **Herramientas > Reemplazar** : Esta herramienta ahora ofrece un modo *Copiar*, permitiendo copiar las configuraciones de equipos y comandos, sin reemplazarlos en los escenarios y otros.
- **Línea de tiempo** : La línea de tiempo ahora carga los primeros 35 eventos. Los siguientes eventos se cargan en el desplazamiento en la parte inferior de la página.
- **Administración** : Posibilidad de diferenciar acciones en caso de error o de alerta de comando.
- **Administración** : Capacidad para establecer widgets de comando predeterminados.
- **Tablero** : posibilidad en la página de configuración del objeto de pedirle a jeedom que reordene el equipo de acuerdo con su uso.
- **Temática** : Posibilidad de elegir el tema directamente desde la url (agregando &theme=Dark o &theme=Light).
- **Temática** : Eliminación del tema **Legado Core2019** *(alpha)*.
- **Relación** : Posibilidad de elegir el tema durante un informe en una página de jeedom.
- **Menú Jeedom** : Un retraso de 0.25s se introdujo en la apertura de submenús.
- **Sistema** : Posibilidad de añadir comandos personalizados (ver documentación)


### 4.4 : Autre

- **Centro** : Inicio de desarrollo en js puro, sin jQuery. Ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.4).
- **Centro** : Lista más detallada de dispositivos USB.
- **Centro** : Se ha añadido un menú contextual en diferentes lugares al nivel de los checkboxes para seleccionarlos todos, ninguno, o invertir la selección *(ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.4))*.
- **Liberación** : Actualizar Highchart v9.3.2 a v10.3.2 (El módulo *calibre sólido* ya no importa).
- **Pedidos** :  Se agregó una opción *(alpha)* no ejecutar una acción si el equipo ya está en el estado esperado.

### 4.4 : Remarques

> **Tablero**
>
> Sobre **Tablero** y los **Puntos de vista**, Núcleo v4.4 ahora cambia automáticamente el tamaño de los mosaicos para crear una cuadrícula perfecta. Las unidades (la altura más pequeña y el ancho más pequeño de un mosaico) de esta cuadrícula se definen en **Ajustes → Sistema → Configuración / Interfaz** por valores *Paso vertical (mínimo 100)* y *Paso horizontal (mínimo 110)*. El valor *Margen* definiendo el espacio entre las baldosas.
> Los mosaicos se adaptan a las dimensiones de la cuadrícula y se pueden hacer una, dos veces, etc. estos valores en alto o ancho. Sin duda será necesario pasar [Modo de edición del tablero](https://doc.jeedom.com/es_ES/core/4.4/dashboard#Mode%20%C3%A9dition) para ajustar el tamaño de algunos mosaicos después de la actualización.


> **Widgets**
>
> Los widgets principales se han reescrito en js/css puro. Tendrás que editar el Dashboard *(Editar luego botón ⁝ en los mosaicos)* y usa la opcion *Ajuste de línea después* en ciertos comandos para encontrar el mismo aspecto visual.
> Todos los widgets principales ahora admiten la visualización *tiempo*, agregando un parámetro opcional *tiempo* / *duración* Dónde *fecha*.

> **Cuadros de diálogo**
>
> Todos los cuadros de diálogo (bootstrap, bootbox, jQuery UI) se han migrado a una Core lib interna (jeeDialog) especialmente desarrollada. Los cuadros de diálogo redimensionables ahora tienen un botón para cambiar *pantalla completa*.


# Registro de cambios Jeedom V4.3

## 4.3.15

- Prohibición de la traducción de Jeedom por parte de los navegadores (evita errores de tipo market.repo).php no encontrado).
- Optimización de la función de reemplazo.

## 4.3.14

- Carga reducida en DNS.

## 4.3.13

- Corrección de errores en **Herramientas / Reemplazar**.

## 4.3.12

- Optimización en las historias.
- Resumen de corrección de errores en dispositivos móviles.
- Corrección de errores del widget de obturador móvil.
- Curvas de mosaico de corrección de errores con información binaria.

## 4.3.11

- Autorización de una respuesta libre en *pedir* si pones * en el campo de posibles respuestas.
- **Análisis / Historia** : Corrección de errores en la comparación del historial (error introducido en 4.3.10).
- **Síntesis** : L'*Acción de Síntesis* de un objeto ahora es compatible con la versión móvil.
- Corrección de historiales al usar la función de agregación.
- Se corrigió un error en la instalación de un complemento por otro complemento (Ej : mqtt2 instalado por zwavejs).
- Se corrigió un error en el historial donde el valor 0 podía sobrescribir el valor anterior.

## 4.3.10

- **Análisis / Historia** : Se corrigieron errores en la eliminación del historial.
- Visualización de valores fijos en la ventana de configuración de comandos.
- Se agregó información y control de la herramienta de reemplazo.

## 4.3.9

- Edición de mosaicos mejorada.
- Visibilidad mejorada de las casillas de verificación temáticas Oscuras y Claras.
- Apilamiento de historial fijo.
- Optimización de la gestión del cambio de hora (gracias @jpty).
- Corrección de errores y mejoras.

## 4.3.8

- Arreglo del fallo.
- Se mejoró la seguridad de las preguntas al usar la función generateAskResponseLink mediante complementos : uso de un token único (no más envío de la clave API central) y bloqueo de la respuesta solo entre las opciones posibles.
- Se corrigió un error que impedía la instalación de jeedom.
- Se corrigió un error en influxdb.


## 4.3.7

- Corrección de errores (que afectan a un complemento futuro en desarrollo)).
- Se corrigieron errores de visualización en algunos widgets según la unidad.
- Descripción añadida **fuente** para acciones de mensajes (ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.3)).

## 4.3.6

- Conversión de unidad eliminada por segundo (s)).
- Eliminación del menú de actualización del sistema operativo para las cajas Jeedom (las actualizaciones del sistema operativo son administradas por Jeedom SAS).
- Se corrigió un error en el modo de configuración del historial.
- Añadir una acción *cambiar de tema* para escenarios, acciones de valor, acciones pre/post ejecutivas : Permite cambiar el tema de la interfaz inmediatamente, en oscuro, claro u otro (alternar).

## 4.3.5

- Arreglo del fallo.

## 4.3.4

- Se solucionó un problema con las imágenes de fondo.
- Se corrigió un error con el widget de número predeterminado.
- Se corrigió el error de inclusión con algunos complementos (*nueces* por ejemplo).

## 4.3.3

- Comprobación mejorada de la versión de nodejs/npm.

## 4.3.2

- Se solucionó un problema al mostrar el estado de un comando de información en la configuración avanzada del comando si el valor es 0.

## 4.3.1

### 4.3 : Prerrequisitos

- Debian 10 Buster
- PHP 7.3

### 4.3 : Noticias / Mejoras

- **Herramientas / Escenarios** : Modal para editar ctrl+clic en campos editables de bloques/acciones.
- **Herramientas / Escenarios** : Adición de un menú contextual en un escenario para activar/desactivar, cambiar grupo, cambiar objeto principal.
- **Herramientas / Objetos** : Se agregó un menú contextual en un objeto para administrar la visibilidad, cambiar el objeto principal y mover.
- **Herramientas / Reemplazar** : Nueva herramienta de sustitución de equipos y mandos.
- **Análisis / Cronología** : Se agregó un campo de búsqueda para filtrar la visualización.
- **Usuarios** : Se agregó un botón para copiar los derechos de un usuario limitado a otro.
- **Relación** : Capacidad para informar sobre la salud de Jeedom.
- **Relación** : Capacidad para informar sobre equipos alertados.
- **Actualización** : Capacidad para ver desde Jeedom los paquetes OS / PIP2 / PIP3 / NodeJS que se pueden actualizar e iniciar la actualización (cuidado con la función arriesgada y en versión beta).
- **Comando de alerta** : Se agregó una opción para recibir un mensaje en caso de fin de alerta.
- **Complementos** : Posibilidad de deshabilitar la instalación de dependencias por complemento.
- **Mejoramiento** : jeeFrontEnd{}, jeephp2js{}, correcciones de errores menores y optimizaciones.

### 4.3 : WebApp

- Integración de notas.
- Posibilidad de mostrar los mosaicos solo en una columna (configuración en la pestaña de configuración de la interfaz de jeedom)).

### 4.3 : Autre

- **Liberación** : Actualizar Font Awesome 5.13.1 a 5.15.4.

### 4.3 : Notes

- Para usuarios que usan menús en sus diseños en el formulario :

``<a onClick="planHeader_id=15; displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

Ahora debes usar:

``<a onClick="jeephp2js.planHeader_id=15; jeeFrontEnd.plan.displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.3).

Entrada en el blog [aquí](https://blog.jeedom.com/6739-jeedom-4-3/)

# Registro de cambios Jeedom V4.2

## 4.2.21

- Se corrigió un error en los resúmenes.

## 4.2.20

- Se agregó un sistema para corregir paquetes pip durante una mala instalación.

## 4.2.19

- Se agregó administración de versiones para paquetes de python (permite corregir el problema con el complemento zigbee).

## 4.2.18

- Actualización de nodejs.

## 4.2.17

- Núcleo de corrección de errores : Acceso de usuario limitado a diseños y vistas.
- Interfaz de usuario de corrección de errores : Visualización de bloques A en Chrome.
- Arreglo del fallo : Enlace a la documentación cuando el complemento está en versión beta.

## 4.2.16

- Núcleo de corrección de errores : Guión : Combinar elementos pegados en algunos casos.
- Núcleo de corrección de errores : Creación de archivos con editor de archivos.
- Arreglo del fallo : Mayor demora para contactar con el servicio de monitoreo (permite aligerar la carga en los servidores en la nube)).

## 4.2.15

- Interfaz de usuario de corrección de errores : Guión : Agregar la acción *tipo genérico* en el modo de selección.
- Núcleo de corrección de errores : Retraso fijo en las historias calculadas.
- Arreglo del fallo : Instalación de dependencias del complemento zigbee.

## 4.2.14

- Interfaz de usuario de corrección de errores : Investigación eliminada al activar la opción de registro sin procesar.
- Interfaz de usuario de corrección de errores : No se puede descargar el registro vacío.
- Interfaz de usuario de corrección de errores : Widget cmd.action.slider.value

- Núcleo de corrección de errores : Tamaño de las imágenes de fondo en relación al tamaño del diseño.
- Núcleo de corrección de errores : Se solucionó un problema con las claves de API aún deshabilitadas.

## 4.2.13

- Interfaz de usuario de corrección de errores : Opción *Ocultar en el escritorio* resúmenes.
- Interfaz de usuario de corrección de errores : Historiques: Respetar las escalas al hacer zoom.

- Núcleo de corrección de errores : Solucionó un problema de tamaño de copia de seguridad con el complemento Atlas.

- Mejora : Creación de claves api por defecto inactivas (si la solicitud de creación no proviene del complemento).
- Mejora : Tamaño de copia de seguridad agregado en la página de administración de copias de seguridad.

## 4.2.12

- Interfaz de usuario de corrección de errores : Mostrar la carpeta de una acción en la línea de tiempo.

- Núcleo de corrección de errores : Visualización de la clave API de cada complemento en la página de configuración.
- Núcleo de corrección de errores : Añadir opción *Hora* en un gráfico en Diseño.
- Núcleo de corrección de errores : Curva de mosaico con valor negativo.
- Núcleo de corrección de errores : Error 403 al reiniciar.

- Mejora : Visualización del valor de activación en el registro de escenarios.

## 4.2.11

- Interfaz de usuario de corrección de errores : Posición en el resumen domótico de objetos de nueva creación.
- Interfaz de usuario de corrección de errores : Problemas de visualización de diseño 3D.

- Núcleo de corrección de errores : Nuevas propiedades de resumen no definidas.
- Núcleo de corrección de errores : Actualizar valor al hacer clic en el rango de widgets *Control deslizante*.
- Núcleo de corrección de errores : Editando archivo vacío (0b).
- Núcleo de corrección de errores : Inquietudes de detectar la IP real del cliente a través del DNS de Jeedom. Se recomienda reiniciar la caja después de la actualización para que esta se active.

## 4.2.9

- Interfaz de usuario de corrección de errores : Corrección de widgets *predeterminado numérico* (cmdName demasiado largo).
- Interfaz de usuario de corrección de errores : Pasar variables css *--url-iconsDark* y *--url-iconsLight* en absoluto (Bug Safari MacOS).
- Interfaz de usuario de corrección de errores : Posición de las notificaciones en *centro Superior*.

- Núcleo de corrección de errores : Paso predeterminado para widgets *Control deslizante* a la 1.
- Núcleo de corrección de errores : La actualización de la página indica *en curso* seguro *FIN DE ACTUALIZAR ERROR* (actualización de registro).
- Núcleo de corrección de errores : Modificación de valor de un historial.
- Núcleo de corrección de errores : Problemas solucionados con la instalación de dependencias de python.

- Mejora : Nuevas opciones en gráficos de diseño para escala y agrupación del eje Y.

- Centro : Actualización de biblioteca *elFinder* 2.1.59 -> 2.1.60

## 4.2.8

- Interfaz de usuario de corrección de errores : Resumen de domótica, borrar historial de eliminación.
- Interfaz de usuario de corrección de errores : Opción *No mostrar* en el modal *primer usuario*.
- Interfaz de usuario de corrección de errores : Curva en mosaicos de fondo en una vista.
- Interfaz de usuario de corrección de errores : Historias, escala de ejes cuando se reduce el zoom.
- Interfaz de usuario de corrección de errores : Historiales, apilamiento en vistas.
- Interfaz de usuario de corrección de errores : Visualización del nombre de usuario al eliminar.
- Interfaz de usuario de corrección de errores : Opciones para mostrar números sin *icono si es nulo*.

- Núcleo de corrección de errores : Compruebe Apache mod_alias.

- Mejora : Opción en configuración para autorizar fechas a futuro en las historias.

## 4.2.0

### 4.2 : Prerrequisitos

- Debian 10 Buster
- PHP 7.3

### 4.2 : Noticias / Mejoras

- **Síntesis** : Posibilidad de configurar objetos para ir a un *diseño* o un *visto* desde la síntesis.
- **Tablero** : La ventana de configuración del dispositivo (modo de edición) ahora le permite configurar widgets móviles y tipos genéricos.
- **Widgets** : Internacionalización de Widgets de terceros (código de usuario). ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.2).
- **Análisis / Historia** : Posibilidad de comparar un historial durante un período determinado.
- **Análisis / Historia** : Visualización de múltiples ejes en Y. Opción para que cada eje tenga su propia escala, agrupada por unidad o no.
- **Análisis / Historia** : Posibilidad de ocultar los ejes Y. Menú contextual en las leyendas con solo visualización, ocultación de ejes, cambio de color de la curva.
- **Análisis / Historia** : Los cálculos del historial guardado ahora se muestran encima de la lista de comandos, de la misma manera que estos.
- **Análisis / Equipo** : Los pedidos huérfanos ahora muestran su nombre y fecha de eliminación si aún están en el historial de eliminación, así como un enlace al escenario o equipo afectado.
- **Análisis / Registros** : Numeración de líneas de registro. Posibilidad de mostrar el registro sin procesar.
- **Registros** : Coloración de troncos según ciertos eventos. Posibilidad de mostrar el registro sin procesar.
- **Resúmenes** : Posibilidad de definir un icono diferente cuando el resumen es nulo (sin persianas abiertas, sin luz encendida, etc).
- **Resúmenes** : Posibilidad de no mostrar nunca el número a la derecha del icono, o solo si es positivo.
- **Resúmenes** : El cambio del parámetro de resumen en la configuración y en los objetos ahora es visible, sin esperar un cambio en el valor de resumen.
- **Resúmenes** : Ahora es posible configurar [acciones sobre resúmenes](/es_ES/concept/summary#Actions seguro résumés) (ctrl + clic en un resumen) gracias a los virtuales.
- **Relación** : Vista previa de archivos PDF.
- **Tipos de equipo** : [Nueva página](/es_ES/core/4.2/types) **Herramientas → Tipos de equipos** permitiendo la asignación de tipos genéricos a dispositivos y comandos, con soporte para tipos dedicados a complementos instalados (ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.2)).
- **Selección de ilustraciones** : Nueva ventana global para la elección de ilustraciones *(iconos, imágenes, fondos)*.
- **Pantalla de mesa** : Adición de un botón a la derecha de la búsqueda en las páginas *Objetos* *Escenarios* *Interacciones* *Widgets* y *Complementos* para cambiar al modo de mesa. Esto se almacena mediante una cookie o en **Configuración → Sistema → Configuración / Interfaz, Opciones**. Los complementos pueden usar esta nueva función del Core. ver [Desarrollador de documentos](https://doc.jeedom.com/es_ES/dev/core4.2).
- **Configuración del equipo** : Posibilidad de configurar una curva de historial en la parte inferior del mosaico de un dispositivo.
- **Ordenado** : Posibilidad de realizar un cálculo sobre una acción de comando de tipo slider antes de la ejecución del comando.
- **Complementos / Gestión** : Visualización de la categoría de complementos y un enlace para abrir directamente su página sin pasar por el menú Complementos.
- **Guión** : Función de respaldo de código (*plegado de código*) en *Bloques de código*. Atajos Ctrl + Y y Ctrl + I.
- **Guión** : Copiar / pegar y deshacer / rehacer la corrección de errores (reescritura completa).
- **Guión** : Agregar funciones de cálculo ``averageTemporal(commande,période)`` Y ``averageTemporalBetween(commande,start,end)`` permitiendo obtener la media ponderada por la duración del período.
- **Guión** : Se agregó soporte para tipos genéricos en escenarios.
	- Desencadenar : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0``
	- SI ``genericType(LIGHT_STATE,#[Salon]#) > 0``
	- Valores ``genericType``
- **Objetos** : Los complementos ahora pueden solicitar parámetros específicos específicos de los objetos.
- **Usuarios** : Los complementos ahora pueden solicitar parámetros específicos específicos para los usuarios.
- **Usuarios** : Capacidad para gestionar los perfiles de diferentes usuarios de Jeedom desde la página de gestión de usuarios.
- **Usuarios** : Capacidad para ocultar objetos / ver / diseñar / diseño 3d para usuarios limitados.
- **Centro de actualizaciones** : El Centro de actualizaciones ahora muestra la fecha de la última actualización.
- **Agregar al usuario que realiza una acción** : Además en las opciones de ejecución del comando de la identificación y el nombre de usuario que inician la acción (visible en el evento de registro, por ejemplo)
- **Complemento de documentación y registro de cambios beta** : Gestión de documentación y registro de cambios para complementos en versión beta. Atención, en beta, el registro de cambios no tiene fecha.
- **General** : Integración del complemento JeeXplorer en el Core. Ahora se usa para código de widget y personalización avanzada.
- **Configuración** : Nueva opción en configuración / interfaz para no colorear el banner del título del equipo.
- **Configuración** : Posibilidad de configurar fondos de pantalla en las páginas Tablero, Análisis, Herramientas y su opacidad según el tema.
- **Configuración**: Agregar DNS de Jeedom basado en Wireguard en lugar de Openvpn (Administración / redes). Más rápido y más estable, pero aún en prueba. Tenga en cuenta que actualmente esto no es compatible con Jeedom Smart.
- **Configuración** : Configuración de OSDB: Adición de una herramienta para la edición masiva de equipos, comandos, objetos, escenarios.
- **Configuración** : Configuración de OSDB: Agregar un constructor de consultas SQL dinámico.
- **Configuración**: Capacidad para deshabilitar el monitoreo en la nube (Administración / Actualizaciones / Mercado).
- **jeeCLI** : Además de ``jeeCli.php`` en la carpeta core / php de Jeedom para administrar algunas funciones de la línea de comandos.
- *Grandes mejoras en la interfaz en términos de rendimiento / capacidad de respuesta. jeedomUtils {}, jeedomUI {}, menú principal reescrito en CSS puro, eliminación de initRowWorflow (), simplificación del código, correcciones de CSS para pantallas pequeñas, etc.*

### 4.2 : Widgets principales

- Ahora se puede acceder a la configuración de los widgets para la versión móvil desde la ventana de configuración del equipo en el modo de edición del tablero.
- Los parámetros opcionales disponibles en los widgets ahora se muestran para cada widget, ya sea en la configuración del comando o desde el modo de edición del tablero.
- Muchos Core Widgets ahora aceptan configuraciones de color opcionales. (Control deslizante horizontal y vertical, indicador, brújula, lluvia, obturador, control deslizante de plantillas, etc.).
- Widgets principales con visualización de *tiempo* ahora admite un parámetro opcional **tiempo : fecha** para mostrar una fecha relativa (ayer a las 4:48 p.m., último lunes a las 2:00 p.m., etc).
- Los widgets de tipo Cursor (acción) ahora aceptan un parámetro opcional *pasos* para definir el paso de cambio en el cursor.
- El widget **acción.deslizador.valor** ahora está disponible en el escritorio, con un parámetro opcional *deslizador*, lo que lo convierte en un *aporte* sencillo.
- El widget **info.numeric.default** (*Medir*) se ha rehecho en CSS puro y se ha integrado en dispositivos móviles. Por lo tanto, ahora son idénticos en computadoras de escritorio y dispositivos móviles.

### 4.2 : Respaldo en la nube

Hemos agregado una confirmación de la contraseña de la copia de seguridad en la nube para evitar errores de entrada (como recordatorio, el usuario es el único que conoce esta contraseña, en caso de olvidarla, Jeedom no puede recuperarla ni acceder a las copias de seguridad).

>**IMPORTANTE**
>
> Después de la actualización, DEBE ir a Configuración → Sistema → pestaña Actualización de configuración / Mercado e ingresar la confirmación de la contraseña de la copia de seguridad en la nube para que se pueda hacer.

### 4.2 : Seguridad

- Para aumentar significativamente la seguridad de la solución Jeedom, el sistema de acceso a archivos ha cambiado. Antes de que ciertos archivos estuvieran prohibidos en determinadas ubicaciones. Desde v4.2, los archivos están explícitamente permitidos por tipo y ubicación.
- Cambie a nivel de API, anteriormente "tolerante" si llegó con la clave principal que indica el complemento XXXXX. Este ya no es el caso, debes llegar con la clave correspondiente al plugin.
- En la API http, puede indicar un nombre de complemento en el tipo, esto ya no es posible. El tipo correspondiente al tipo de solicitud (escenario, eqLogic, cmd, etc.) debe corresponder al complemento. Por ejemplo, para el complemento virtual que tenía ``type=virtual`` en la URL ahora es necesario reemplazar por ``plugin=virtualYtype=event``.
- Refuerzo de sesiones : Cambiar a sha256 con 64 caracteres en modo estricto.

El equipo de Jeedom es consciente de que estos cambios pueden tener un impacto y ser embarazosos para usted, pero no podemos comprometer la seguridad.
Los complementos deben respetar las recomendaciones sobre la estructura de árbol de carpetas y archivos : [Médico](https://doc.jeedom.com/es_ES/dev/plugin_template).

[Blog: Jeedom 4 introducción.2 : la seguridad](https://blog.jeedom.com/6165-introduction-jeedom-4-2-la-securite/)

# Registro de cambios Jeedom V4.1

## 4.1.28

- Armonización de plantillas de widgets para comandos de acción / predeterminados

## 4.1.27

- Corrección de una brecha de seguridad gracias @Maxime Rinaudo y @Antoine Cervoise de Synacktiv (www.synacktiv.com)

## 4.1.26

- Se corrigió un problema de instalación de dependencia de apt en Smart debido al cambio de certificado en Let's encrypt.

## 4.1.25

- Se corrigió el problema de instalación de la dependencia de apt.

## 4.1.24

- Revisión de la opción de configuración de comandos **Gestionar la repetición de valores** se convierte en **Repita valores idénticos (Sí|Non)**. [Consulte el artículo del blog para obtener más detalles](https://blog.jeedom.com/5414-nouvelle-gestion-de-la-repetition-des-valeurs/)

## 4.1.23

- Errores corregidos en el archivo del historial
- Se corrigió un problema de caché que podía desaparecer durante un reinicio
- Se corrigió un error en la gestión de repeticiones de comandos binarios : en ciertos casos si el equipo envía dos veces 1 o 0 seguidos, solo se tuvo en cuenta el primer ascenso. Tenga en cuenta que esta corrección de errores puede provocar una sobrecarga de la CPU. Por lo tanto, es necesario actualizar los complementos también (Philips Hue en particular) para otros casos (activación de múltiples escenarios, mientras que este no era el caso antes de la actualización). Pregunta sobre la repetición de valores (configuración avanzada del comando) y cámbielo a "nunca repetir" para encontrar la operación anterior.

## 4.1.22

- Adición de un sistema que permite a Jeedom SAS comunicar mensajes a todos los Jeedom
- Cambiar el DNS de Jeedom al modo de alta disponibilidad

## 4.1.20

- Corrección de errores de desplazamiento horizontal en diseños.
- Corrección de errores de desplazamiento en las páginas de equipos de complementos.
- Corrección de errores de la configuración de color en los enlaces de vista / diseño en un diseño.
- Corrección de errores y optimización de la línea de tiempo.
- Revisión de diseños móviles con tres dedos ahora limitada a perfiles de administrador.

## 4.1.19

- Eliminación de corrección de errores de zona en una vista.
- Error de corrección de errores js que puede aparecer en navegadores antiguos.
- Corrección de error cmd.info.numeric.default.html si el comando no está visible.
- Página de inicio de sesión de corrección de errores.

## 4.1.18

- Corrección de errores históricos en diseños.
- Búsquedas de corrección de errores en Análisis / Historial.
- Búsqueda de corrección de errores en una variable, enlace a un dispositivo.
- Corrección de errores de resúmenes en color sobre síntesis.
- Corrección de errores en los comentarios del escenario con json.
- Corrección de errores en las actualizaciones resumidas en las vistas previas del modo Panel.
- Corrección de errores de elementos *imagen* en un diseño.
- Se agregaron opciones de agrupación por tiempo para gráficos en vistas.
- Conservación del contexto de síntesis al hacer clic en los resúmenes.
- Centrado de imágenes de síntesis.

## 4.1.0

### 4.1 : Prerrequisitos

- Debian 10 Buster

### 4.1 : Noticias / Mejoras

- **Síntesis** : Agregar una nueva página **Inicio → Resumen** Ofrece un resumen visual global de las partes, con acceso rápido a resúmenes.
- **Investigar** : Adición de un motor de búsqueda en **Herramientas → Buscar**.
- **Tablero** : El modo de edición ahora inserta el mosaico movido.
- **Tablero** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **Tablero** : Ahora podemos hacer clic en el *tiempo* widgets de acciones de tiempo para abrir la ventana del historial del comando de información vinculada.
- **Tablero** : El tamaño del mosaico de un equipo nuevo se adapta a su contenido.
- **Tablero** : Agregar (¡atrás!) Un botón para filtrar los elementos mostrados por categoría.
- **Tablero** : Ctrl Click en una información abre la ventana de historial con todos los comandos historizados del equipo visibles en el mosaico. Ctrl Haga clic en una leyenda para mostrar solo esta, Alt Haga clic para mostrarlas todas.
- **Tablero** : Rediseño de la visualización del árbol de objetos (flecha a la izquierda de la búsqueda).
- **Tablero** : Posibilidad de desenfocar imágenes de fondo (Configuración -> Interfaz).
- **Herramientas / widgets** : La funcion *Aplicar en* muestra los comandos vinculados marcados, al desmarcar uno se aplicará el widget principal predeterminado a este comando.
- **Widgets** : Agregar un widget principal *deslizadorVertical*.
- **Widgets** : Agregar un widget principal *interruptor binario*.
- **Centro de actualizaciones** : Las actualizaciones se verifican automáticamente cuando se abre la página si tiene 120 minutos de antigüedad.
- **Centro de actualizaciones** : La barra de progreso ahora está en la pestaña *Core y plugins*, y el registro se abre por defecto en la pestaña *Información*.
- **Centro de actualizaciones** : Si abre otro navegador durante una actualización, la barra de progreso y el registro lo indican.
- **Centro de actualizaciones** : Si la actualización finaliza correctamente, se muestra una ventana que solicita volver a cargar la página.
- **Actualizaciones principales** : Implementación de un sistema para limpiar viejos archivos Core no utilizados.
- **Guión** : Agregar un motor de búsqueda (a la izquierda del botón Ejecutar).
- **Guión** : Adición de la función de edad (da la edad del valor del pedido).
- **Guión** : *stateChanges()* ahora acepta el periodo *Este Dia* (medianoche hasta ahora), *el dia de ayer* y *día* (por 1 día).
- **Guión** : Funciones *estadísticas (), promedio (), máximo (), mínimo (), tendencia (), duración()* : Bugfix durante el período *el dia de ayer*, y acepta ahora *día* (por 1 día).
- **Guión** : Posibilidad de desactivar el sistema de cotización automática (Configuración → Sistema → Configuración : Equipements).
- **Guión** : Viendo un *advertencia* si no se configura ningún activador.
- **Guión** : Corrección de errores de *Seleccione* en el bloque copiar / pegar.
- **Guión** : Copiar / pegar bloque entre diferentes escenarios.
- **Guión** : Las funciones de deshacer / rehacer ahora están disponibles como botones (al lado del botón de creación de bloque).
- **Guión** :  adición de "Exportación histórica" (exportHistory)
- **Ventana de variables de escenario** : Clasificación alfabética en la apertura.
- **Ventana de variables de escenario** : Ahora se puede hacer clic en los escenarios utilizados por las variables, con la apertura de la búsqueda en la variable.
- **Análisis / Historia** : Ctrl Haga clic en una leyenda para mostrar solo este historial, Alt Haga clic para mostrarlos todos.
- **Análisis / Historia** : Las opciones *agrupación, tipo, variación, escalera* están activos solo con una sola curva mostrada.
- **Análisis / Historia** : Ahora podemos usar la opción *Área* con la opción *Escalera*.
- **Análisis / Registros** : Nueva fuente tipo monoespacio para registros.
- **Visto** : Posibilidad de poner escenarios.
- **Visto** : El modo de edición ahora inserta el mosaico movido.
- **Visto** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **Visto** : El orden de visualización ahora es independiente del que se muestra en el Panel de control.
- **Línea de tiempo** : Separación de páginas de historia y cronología.
- **Línea de tiempo** : Integración de la línea de tiempo en DB por razones de confiabilidad.
- **Línea de tiempo** : Gestión de múltiples líneas de tiempo.
- **Línea de tiempo** : Rediseño gráfico completo de la línea de tiempo (Escritorio / Móvil).
- **Resumen global** : Vista de resumen, soporte para resúmenes de un objeto diferente o con un objeto raíz vacío (escritorio y aplicación web).
- **Herramientas / Objetos** : Nueva pestaña *Resumen por equipos*.
- **Resumen de domótica** : Equipos de complementos desactivados y sus controles ya no tienen los iconos a la derecha (configuración de equipos y configuración avanzada).
- **Resumen de domótica** : Posibilidad de buscar en categorías de equipos.
- **Resumen de domótica** : Posibilidad de mover varios equipos de un objeto a otro.
- **Resumen de domótica** : Posibilidad de seleccionar todo el equipo de un objeto.
- **Motor de tareas** : En la pestaña *Demonio*, los complementos deshabilitados ya no aparecen.
- **Relación** : El uso de *cromo* si está disponible.
- **Relación** : Posibilidad de exportar cronogramas.
- **Configuración** : Pestaña *Información* ahora está en la pestaña *General*.
- **Configuración** : Pestaña *Pedidos* ahora está en la pestaña *Equipo*.
- **Ventana de configuración avanzada del equipo** : Cambio dinámico de la configuración de la centralita.
- **Equipo** : Nueva categoría *Apertura*.
- **Equipo** : Posibilidad de invertir comandos tipo cursor (info y acción)
- **Equipo** : Posibilidad de agregar clase css a un mosaico (consulte la documentación del widget).
- **Sobre ventana** : Adición de accesos directos al registro de cambios y preguntas frecuentes.
- Widgets / Objetos / Escenarios / Interacciones / Páginas de complementos :
	- Ctrl Clic / Clic Center en un widget, objeto, escenarios, interacción, equipo de complemento : Se abre en una pestaña nueva.
	- Ctrl Clic / Clic Center también disponible en sus menús contextuales (en las pestañas).
- Nueva página ModalDisplay :
	- Menú de análisis : Ctrl Click / Click Center en *Tiempo real* : Abra la ventana en una pestaña nueva, en pantalla completa.
	- Menú de herramientas : Ctrl Click / Click Center en *Calificaciones*, *Probador de expresión*, *Variables*, *Investigar* : Abra la ventana en una pestaña nueva, en pantalla completa.
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
- **Documentación** : Adaptaciones en línea con v4 y v4.1.
- **Documentación** : Nueva página *Atajos de teclado / mouse* incluyendo un resumen de todos los atajos en Jeedom. Accesible desde el Dashboard doc o las preguntas frecuentes.
- **Liberación** : Actualizar HighStock v7.1.2 a v8.2.0.
- **Liberación** : Actualizar jQuery v3.4.1 a v3.5.1.
- **Liberación** : Actualizar Font Awesome 5.9.0 a 5.13.1.
- **API** :  adición de una opción para prohibir que una clave api de un complemento ejecute métodos centrales (general)
- Asegurando solicitudes Ajax.
- Asegurar las llamadas a la API.
- Correcciones de errores.
- Numerosas optimizaciones de rendimiento de escritorio / móvil.

### 4.1 : Changements
- La funcion **escenario-> getHumanName()** de la clase de escenario php ya no regresa *[objeto] [grupo] [nombre]* pero *[grupo] [objeto] [nombre]*.
- La funcion **escenario-> byString()** ahora debe llamarse con la estructura *[grupo] [objeto] [nombre]*.
- Las funciones **red-> getInterfaceIp () red-> getInterfaceMac () red-> getInterfaces()** han sido reemplazados por **network-> getInterfacesInfo()**


# Registro de cambios Jeedom V4.0

## 4.0.62

- Nueva migración de kernel buster + para smart y Pro v2
- Verificación de la versión del sistema operativo durante las actualizaciones importantes de Jeedom


## 4.0.61

- Se solucionó un problema al aplicar una plantilla de escenario
- Adición de una opción para deshabilitar la verificación SSL al comunicarse con el mercado (no se recomienda pero es útil en ciertas configuraciones de red específicas)
- Se solucionó un problema con el historial de archivo si el modo de suavizado era para siempre
- Correcciones de errores
- Corrección del comando trigger () en escenarios para que devuelva el nombre del disparador (sin el #) en lugar del valor, para el valor debes usar triggerValue()

## 4.0.60

- Eliminación del nuevo sistema DNS en eu.jeedom.enlace siguiendo demasiados operadores que prohíben flujos http2 permanentes

## 4.0.59

- Errores corregidos en los widgets de tiempo
- Aumento del número de contraseñas incorrectas antes de prohibir (evita problemas con la aplicación web al rotar claves API)

## 4.0.57

- Refuerzo de la seguridad de las cookies
- Usar cromo (si está instalado) para informes
- Se solucionó un problema con el cálculo de la hora del estado en los widgets si la zona horaria de jeedom no es la misma que la del navegador
- Arreglo del fallo

## 4.0.55

- El nuevo dns (\*. Eu.jeedom.enlace) se convierte en el DNS primario (el antiguo DNS todavía funciona)

## 4.0.54

- Inicio de la actualización al nuevo sitio de documentación

## 4.0.53

- Arreglo del fallo.

## 4.0.52

- Corrección de errores (actualice para hacerlo absolutamente si está en 4.0.51).

## 4.0.51

- Arreglo del fallo.
- Optimización del futuro sistema DNS.

## 4.0.49

- Posibilidad de elegir el motor Jeedom TTS y posibilidad de tener plugins que ofrezcan un nuevo motor TTS.
- Compatibilidad mejorada con webview en la aplicación móvil.
- Arreglo del fallo.
- Actualización de documentos.

## 4.0.47

- Probador de expresión mejorado.
- Actualización del repositorio en smart.
- Arreglo del fallo.

## 4.0.44

- Traducciones mejoradas.
- Arreglo del fallo.
- Restauración de copia de seguridad en la nube mejorada.
- La restauración en la nube ahora solo recupera la copia de seguridad local, dejando la opción de descargarla o restaurarla.

## 4.0.43

- Traducciones mejoradas.
- Errores corregidos en las plantillas de escenarios.

## 4.0.0

### 4.0 : Prerrequisitos

- Estiramiento de Debian 9

### 4.0 : Noticias / Mejoras

- Rediseño completo del tema (Core 2019 Light / Dark / Legacy).
- Posibilidad de cambiar el tema automáticamente en función de la hora.
- En el móvil, el tema puede cambiar dependiendo del brillo (Requiere activar *sensor adicional genérico* en cromo, página de cromo://flags).<br/><br/>
- Mejora y reorganización del menú principal.
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Agregue un botón para acceder al probador de expresiones.
- Menú de herramientas : Agregar un botón para acceder a las variables.<br/><br/>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (panel, escenarios, objetos, widgets, interacciones, complementos) ahora están activos cuando se abre la página, lo que le permite escribir una búsqueda directamente.
- Se agregó un botón X en los campos de búsqueda para cancelar la búsqueda.
- Durante una búsqueda, la clave *Escapar* cancelar búsqueda.
- Tablero : En el modo de edición, el control de búsqueda y sus botones se desactivan y se fijan.
- Tablero : En el modo de edición, un clic de un botón *expandir* a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
- Tablero : La ejecución del comando en un mosaico ahora se indica con el botón *actualizar*. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
- Tablero : Los mosaicos indican un comando de información (histórico, que abrirá la ventana Historial) o una acción al pasar el mouse.
- Tablero : La ventana del historial ahora le permite abrir este historial en Análisis / Historial.
- Tablero : La ventana del historial mantiene su posición / dimensiones al reabrir otro historial.
- Ventana de configuración de comandos: Ctrl + clic en "Guardar" cierra la ventana después.
- Ventana de configuración del equipo: Ctrl + clic en "Guardar" cierra la ventana después.
- Agregar información de uso al eliminar un dispositivo.
- Objetos : Opción agregada para usar colores personalizados.
- Objetos : Adición de un menú contextual en las pestañas (cambio rápido de objeto).
- Interacciones : Adición de un menú contextual en las pestañas (cambio rápido de interacción).
- Complementos : Adición de un menú contextual en las pestañas (cambio rápido de equipo).
- Complementos : En la página de administración de complementos, un punto naranja indica los complementos en la versión no estable.
- Mejoras en la tabla con opción de filtro y clasificación.
- Posibilidad de asignar un icono a una interacción.
- Cada página de Jeedom ahora tiene un título en el idioma de la interfaz (pestaña del navegador).
- Prevención del autocompletado en los campos de 'Código de acceso''.
- Gestión de funciones *Página anterior / Página siguiente* desde el navegador.<br/><br/>
- Widgets : Rediseño del sistema de widgets (menú Herramientas / Widgets).
- Widgets : Posibilidad de reemplazar un widget por otro en todos los comandos que lo utilizan.
- Widgets : Posibilidad de asignar widgets a varios comandos.
- Widgets : Agregar un widget de información numérica horizontal.
- Widgets : Agregar un widget de información numérica vertical.
- Widgets : Adición de un widget de información de viento / brújula numérica (gracias @thanaus).
- Widgets : Se agregó un widget de información de lluvia numérica (gracias @thanaus)
- Widgets : Visualización del widget de obturador de información / acción proporcional al valor.<br/><br/>
- Configuración : Mejora y reorganización de pestañas.
- Configuración : Adición de muchos *información sobre herramientas* (aide).
- Configuración : Agregar un motor de búsqueda.
- Configuración : Se agregó un botón para vaciar la caché de widgets (pestaña Caché).
- Configuración : Se agregó una opción para deshabilitar la caché de widgets (pestaña Caché).
- Configuración : Posibilidad de centrar verticalmente el contenido de los mosaicos (pestaña Interfaz).
- Configuración : Se agregó un parámetro para la purga global de registros (pestaña Pedidos).
- Configuración : Cambio de #message# a #subject# en Configuración / Registros / Mensajes para evitar duplicar el mensaje.
- Configuración : Posibilidad en los resúmenes de agregar una exclusión de pedidos que no se han actualizado durante más de XX minutos (ejemplo para el cálculo de promedios de temperatura si un sensor no ha reportado nada durante más de 30min se excluirá del cálculo)<br/><br/>
- Guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque.
- Guión : Posibilidad haciendo Ctrl + clic en el botón *ejecución* guárdelo, ejecútelo y muestre el registro (si el nivel de registro no está en *Ninguna*).
- Guión : Confirmación de eliminación de bloque. Ctrl + clic para evitar la confirmación.
- Guión : Adición de una función de búsqueda en bloques de código. Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Mayús + G
- Guión : Posibilidad de condensar los bloques.
- Guión : La acción 'Agregar bloque' cambia a la pestaña Escenario si es necesario.
- Guión : Nuevas funciones de copiar / pegar en bloque. Ctrl + clic para cortar / reemplazar.
- Guión : Ya no se agrega un nuevo bloque al final del escenario, sino después del bloque donde estaba antes de hacer clic, determinado por el último campo en el que hizo clic.
- Guión : Configurar un sistema Deshacer / Rehacer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Guión : Eliminar el uso compartido de escenarios.
- Guión : Mejora de la ventana de gestión de plantillas de escenarios.<br/><br/>
- Análisis / Equipo : Adición de un motor de búsqueda (pestaña Baterías, búsqueda por nombres y padres).
- Análisis / Equipo : Ahora se puede hacer clic en la zona de día del calendario / equipo para acceder directamente al cambio de batería (s).
- Análisis / Equipo : Agregar un campo de búsqueda.<br/><br/>
- Centro de actualizaciones : Advertencia en la pestaña 'Núcleo y complementos' y / o 'Otros' si hay una actualización disponible. Cambie a 'Otros' si es necesario.
- Centro de actualizaciones : diferenciación por versión (estable, beta, ...).
- Centro de actualizaciones : adición de una barra de progreso durante la actualización.<br/><br/>
- Resumen de domótica : El historial de eliminaciones ahora está disponible en una pestaña (Resumen - Historial).
- Resumen de domótica : Rediseño completo, posibilidad de ordenar objetos, equipos, pedidos.
- Resumen de domótica : Adición de ID de equipo y pedido, para mostrar y buscar.
- Resumen de domótica : Exportación CSV del objeto principal, identificación, equipo y su identificación, comando.
- Resumen de domótica : Posibilidad de hacer visibles o no uno o más comandos.<br/><br/>
- Diseño : Posibilidad de especificar el orden (posición) del *Diseños* y *Diseños 3d* (Editar, configurar diseño).
- Diseño : Adición de un campo CSS personalizado en los elementos del *diseño*.
- Diseño : Se movieron las opciones de visualización en Diseño de la configuración avanzada, en los parámetros de visualización de la *Diseño*. Esto con el fin de simplificar la interfaz y permitir tener diferentes parámetros mediante *Diseño*.
- Diseño : Mover y cambiar el tamaño de componentes en *Diseño* tiene en cuenta su tamaño, con o sin magnetización.<br/><br/>
- Adición de un sistema de configuración masiva (utilizado en la página Equipo para configurar alertas de comunicaciones en ellos)

### 4.0 : Autres

- **Liberación** : Actualización de jquery 3.4.1
- **Liberación** : Actualizar CodeMiror 5.46.0
- **Liberación** : Actualizar tablesorter 2.31.1
- Aligeramiento general (estilos CSS / en línea, refactorización, etc.) y mejoras de rendimiento.
- Adición de compatibilidad global de Jeedom DNS con una conexión a Internet 4G.
- Numerosas correcciones de errores.
- Correcciones de seguridad.

### 4.0 : Changements

- Elimine Font Awesome 4 para mantener solo Font Awesome 5.
- El complemento del widget no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han tomado internamente en el núcleo). Más información [aquí](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

>**IMPORTANTE**
>
> Si después de la actualización tiene un error en el Tablero, intente reiniciar su caja para que tenga en cuenta las nuevas adiciones de componentes.
