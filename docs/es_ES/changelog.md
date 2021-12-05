# Registro de cambios Jeedom V4.2

## 4.2.0

### 4.2 : Prerrequisitos

- Debian 10 Buster
- PHP 7.3

### 4.2 : Noticias / Mejoras

- **Síntesis** : Posibilidad de configurar objetos para ir a un *diseño* o un *ver* desde la síntesis.
- **Tablero** : La ventana de configuración del dispositivo (modo de edición) ahora le permite configurar widgets móviles y tipos genéricos.
- **Widgets** : Internacionalización de Widgets de terceros (código de usuario). ver [Doc dev](https://doc.jeedom.com/es_ES/dev/core4.2).
- **Análisis / Historia** : Posibilidad de comparar un historial durante un período determinado.
- **Análisis / Historia** : Visualización de varios ejes Y con su propia escala.
- **Análisis / Equipo** : Los pedidos huérfanos ahora muestran su nombre y fecha de eliminación si aún están en el historial de eliminación, así como un enlace al escenario o equipo afectado.
- **Análisis / Registros** : Numeración de líneas de registro. Posibilidad de mostrar el registro sin procesar.
- **Registros** : Coloración de troncos según ciertos eventos. Posibilidad de mostrar el registro sin procesar.
- **Resúmenes** : Posibilidad de definir un icono diferente cuando el resumen es nulo (sin persianas abiertas, sin luz encendida, etc).
- **Resúmenes** : Posibilidad de no mostrar nunca el número a la derecha del icono, o solo si es positivo.
- **Resúmenes** : El cambio del parámetro de resumen en la configuración y en los objetos ahora es visible, sin esperar un cambio en el valor de resumen.
- **Resúmenes** : Ahora es posible configurar [acciones sobre resúmenes](/es_ES/concept/summary#Actions sur résumés) (ctrl + clic en un resumen) gracias a los virtuales.
- **Tipos de equipo** : [Nueva página](/es_ES/core/4.2/types) **Herramientas → Tipos de equipos** permitiendo la asignación de tipos genéricos a dispositivos y comandos, con soporte para tipos dedicados a complementos instalados (ver [Doc dev](https://doc.jeedom.com/es_ES/dev/core4.2)).
- **Selección de ilustraciones** : Nueva ventana global para la elección de ilustraciones *(iconos, imágenes, fondos)*.
- **Pantalla de mesa** : Adición de un botón a la derecha de la búsqueda en las páginas *Objetos* *Escenarios* *Interacciones* *Widgets* y *Complementos* para cambiar al modo de mesa. Esto se almacena mediante una cookie o en **Configuración → Sistema → Configuración / Interfaz, Opciones**. Los complementos pueden usar esta nueva función del Core. ver [Doc dev](https://doc.jeedom.com/es_ES/dev/core4.2).
- **Configuración del equipo** : Posibilidad de configurar una curva de historial en la parte inferior del mosaico de un dispositivo.
- **Ordenado** : Posibilidad de realizar un cálculo sobre una acción de comando de tipo slider antes de la ejecución del comando.
- **Complementos / Gestión** : Visualización de la categoría de complementos y un enlace para abrir directamente su página sin pasar por el menú Complementos.
- **Guión** : Función de respaldo de código (*plegado de código*) en *Bloques de código*. Atajos Ctrl + Y y Ctrl + I.
- **Guión** : Copiar / pegar y deshacer / rehacer la corrección de errores (reescritura completa).
- **Guión** : Agregar funciones de cálculo ''''averageTemporal(commande,période)'''' Y ''''averageTemporalBetween(commande,start,end)'''' permitiendo obtener la media ponderada por la duración del período.
- **Guión** : Se agregó soporte para tipos genéricos en escenarios.
	- Desencadenar : ''#genericType(LIGHT_STATE,#[Salón]#)# > 0`
	- IF `genericType (LIGHT_STATE,#[Salón]#) > 0`
	- Acción `GenericType`
- **Objetos** : Los complementos ahora pueden solicitar parámetros específicos específicos de los objetos.
- **Usuarios** : Los complementos ahora pueden solicitar parámetros específicos específicos para los usuarios.
- **Usuarios** : Capacidad para gestionar los perfiles de diferentes usuarios de Jeedom desde la página de gestión de usuarios.
- **Usuarios** : Capacidad para ocultar objetos / ver / diseñar / diseño 3d para usuarios limitados.
- **Centro de actualizaciones** : El Centro de actualizaciones ahora muestra la fecha de la última actualización.
- **Agregar al usuario que realiza una acción** : Además en las opciones de ejecución del comando de la identificación y el nombre de usuario que inician la acción (visible en el evento de registro, por ejemplo)
- **Complemento de documentación y registro de cambios beta** : Gestión de documentación y registro de cambios para complementos en versión beta. Atención, en beta, el registro de cambios no tiene fecha.
- **Principal** : Integración del complemento JeeXplorer en el Core. Ahora se usa para código de widget y personalización avanzada.
- **Configuración** : Nueva opción en configuración / interfaz para no colorear el banner del título del equipo.
- **Configuración** : Posibilidad de configurar fondos de pantalla en las páginas Tablero, Análisis, Herramientas y su opacidad según el tema.
- **Configuración**: Agregar DNS de Jeedom basado en Wireguard en lugar de Openvpn (Administración / redes). Más rápido y más estable, pero aún en prueba. Tenga en cuenta que actualmente esto no es compatible con Jeedom Smart.
- **Configuración** : Configuración de OSDB: Adición de una herramienta para la edición masiva de equipos, comandos, objetos, escenarios.
- **Configuración** : Configuración de OSDB: Agregar un constructor de consultas SQL dinámico.
- **Configuración**: Posibilidad de desactivar el monitoreo en la nube (Administración / Actualizaciones / Mercado).
- **jeeCLI** : Además de ''''jeeCli.php'''' en la carpeta core / php de Jeedom para administrar algunas funciones de la línea de comandos.
- *Grandes mejoras en la interfaz en términos de rendimiento / capacidad de respuesta. jeedomUtils {}, jeedomUI {}, menú principal reescrito en CSS puro, eliminación de initRowWorflow (), simplificación del código, correcciones de CSS para pantallas pequeñas, etc.*

### 4.2 : Widgets principales

- Ahora se puede acceder a la configuración de los widgets para la versión móvil desde la ventana de configuración del equipo en el modo de edición del tablero.
- Los parámetros opcionales disponibles en los widgets ahora se muestran para cada widget, ya sea en la configuración del comando o desde el modo de edición del tablero.
- Muchos Core Widgets ahora aceptan configuraciones de color opcionales. (Control deslizante horizontal y vertical, indicador, brújula, lluvia, obturador, control deslizante de plantillas, etc.).
- Widgets principales con visualización de *hora* ahora admite un parámetro opcional **hora : con fecha de** para mostrar una fecha relativa (ayer a las 4:48 p.m., último lunes a las 2:00 p.m., etc).
- Los widgets de tipo Cursor (acción) ahora aceptan un parámetro opcional *paso* para definir el paso de cambio en el cursor.
- El widget **action.slider.value** ahora está disponible en el escritorio, con un parámetro opcional *noslider*, lo que lo convierte en un *entrada* sencillo.
- El widget **info.numeric.default** (*Indicador*) se ha rehecho en CSS puro y se ha integrado en dispositivos móviles. Por lo tanto, ahora son idénticos en computadoras de escritorio y dispositivos móviles.

### 4.2 : Respaldo en la nube

Hemos agregado una confirmación de la contraseña de la copia de seguridad en la nube para evitar errores de entrada (como recordatorio, el usuario es el único que conoce esta contraseña, en caso de olvidarla, Jeedom no puede recuperarla ni acceder a las copias de seguridad).

>**IMPORTANTE**
>
> Después de la actualización, DEBE ir a Configuración → Sistema → pestaña Actualización de configuración / Mercado e ingresar la confirmación de la contraseña de la copia de seguridad en la nube para que se pueda hacer.

### 4.2 : Seguridad

- Para aumentar significativamente la seguridad de la solución Jeedom, el sistema de acceso a archivos ha cambiado. Antes de que ciertos archivos estuvieran prohibidos en determinadas ubicaciones. Desde v4.2, los archivos están explícitamente permitidos por tipo y ubicación.
- Cambie a nivel de API, anteriormente "tolerante" si llegó con la clave principal que indica el complemento XXXXX. Este ya no es el caso, debes llegar con la clave correspondiente al plugin.
- En la API http, puede indicar un nombre de complemento en el tipo, esto ya no es posible. El tipo correspondiente al tipo de solicitud (escenario, eqLogic, cmd, etc.) debe corresponder al complemento. Por ejemplo, para el complemento virtual que tenía ''''type=virtual'''' en la URL ahora es necesario reemplazar por ''''plugin=virtualYtype=event''''.
- Refuerzo de sesiones : Cambiar a sha256 con 64 caracteres en modo estricto.
- La cookie "permanecer conectado" (3 meses como máximo) ahora es "de una sola vez", que se renueva con cada uso.

El equipo de Jeedom es consciente de que estos cambios pueden tener un impacto y ser embarazosos para usted, pero no podemos comprometer la seguridad.
Los complementos deben respetar las recomendaciones sobre la estructura de árbol de carpetas y archivos : [Doc](https://doc.jeedom.com/es_ES/dev/plugin_template).

# Registro de cambios Jeedom V4.1

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

### 4.1 Noticias / Mejoras

- **Síntesis** : Agregar una nueva página **Inicio → Resumen** Ofrece un resumen visual global de las partes, con acceso rápido a resúmenes.
- **Investigación** : Adición de un motor de búsqueda en **Herramientas → Buscar**.
- **Tablero** : El modo de edición ahora inserta el mosaico movido.
- **Tablero** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **Tablero** : Ahora podemos hacer clic en el *hora* widgets de acciones de tiempo para abrir la ventana del historial del comando de información vinculada.
- **Tablero** : El tamaño del mosaico de un equipo nuevo se adapta a su contenido.
- **Tablero** : Agregar (¡atrás!) Un botón para filtrar los elementos mostrados por categoría.
- **Tablero** : Ctrl Click en una información abre la ventana de historial con todos los comandos historizados del equipo visibles en el mosaico. Ctrl Haga clic en una leyenda para mostrar solo esta, Alt Haga clic para mostrarlas todas.
- **Tablero** : Rediseño de la visualización del árbol de objetos (flecha a la izquierda de la búsqueda).
- **Tablero** : Posibilidad de desenfocar imágenes de fondo (Configuración -> Interfaz).
- **Herramientas / widgets** : La funcion *Aplicar en* muestra los comandos vinculados marcados, al desmarcar uno se aplicará el widget principal predeterminado a este comando.
- **Widgets** : Agregar un widget principal *deslizador vertical*.
- **Widgets** : Agregar un widget principal *binarySwitch*.
- **Centro de actualizaciones** : Las actualizaciones se verifican automáticamente cuando se abre la página si tiene 120 minutos de antigüedad.
- **Centro de actualizaciones** : La barra de progreso ahora está en la pestaña *Core y plugins*, y el registro se abre por defecto en la pestaña *Información*.
- **Centro de actualizaciones** : Si abre otro navegador durante una actualización, la barra de progreso y el registro lo indican.
- **Centro de actualizaciones** : Si la actualización finaliza correctamente, se muestra una ventana que solicita volver a cargar la página.
- **Actualizaciones principales** : Implementación de un sistema para limpiar viejos archivos Core no utilizados.
- **Guión** : Agregar un motor de búsqueda (a la izquierda del botón Ejecutar).
- **Guión** : Adición de la función de edad (da la edad del valor del pedido).
- **Guión** : *stateChanges()* ahora acepta el periodo *hoy* (medianoche hasta ahora), *ayer* y *día* (por 1 día).
- **Guión** : Funciones *estadísticas (), promedio (), máximo (), mínimo (), tendencia (), duración()* : Bugfix durante el período *ayer*, y acepta ahora *día* (por 1 día).
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
- **Análisis / Historia** : Ahora podemos usar la opción *Zona* con la opción *Escalera*.
- **Análisis / Registros** : Nueva fuente tipo monoespacio para registros.
- **Ver** : Posibilidad de poner escenarios.
- **Ver** : El modo de edición ahora inserta el mosaico movido.
- **Ver** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **Ver** : El orden de visualización ahora es independiente del que se muestra en el Panel de control.
- **Cronología** : Separación de páginas de historia y cronología.
- **Cronología** : Integración de la línea de tiempo en DB por razones de confiabilidad.
- **Cronología** : Gestión de múltiples líneas de tiempo.
- **Cronología** : Rediseño gráfico completo de la línea de tiempo (Escritorio / Móvil).
- **Resumen global** : Vista de resumen, soporte para resúmenes de un objeto diferente o con un objeto raíz vacío (escritorio y aplicación web).
- **Herramientas / Objetos** : Nueva pestaña *Resumen por equipos*.
- **Resumen de domótica** : Equipos de complementos desactivados y sus controles ya no tienen los iconos a la derecha (configuración de equipos y configuración avanzada).
- **Resumen de domótica** : Posibilidad de buscar en categorías de equipos.
- **Resumen de domótica** : Posibilidad de mover varios equipos de un objeto a otro.
- **Resumen de domótica** : Posibilidad de seleccionar todo el equipo de un objeto.
- **Motor de tareas** : En la pestaña *Demonio*, los complementos deshabilitados ya no aparecen.
- **Relación** : El uso de *cromo* si está disponible.
- **Relación** : Posibilidad de exportar cronogramas.
- **Configuración** : La tabla *Información* ahora está en la pestaña *Principal*.
- **Configuración** : La tabla *Pedidos* ahora está en la pestaña *Equipo*.
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
	- Menú de herramientas : Ctrl Click / Click Center en *Notas*, *Probador de expresión*, *Variables*, *Investigación* : Abra la ventana en una pestaña nueva, en pantalla completa.
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
- **Lib** : Actualizar HighStock v7.1.2 a v8.2.0.
- **Lib** : Actualizar jQuery v3.4.1 a v3.5.1.
- **Lib** : Actualizar Font Awesome 5.9.0 a 5.13.1.
- **API** :  adición de una opción para prohibir que una clave api de un complemento ejecute métodos centrales (general)
- Asegurando solicitudes Ajax.
- Asegurar las llamadas a la API.
- Correcciones de errores.
- Numerosas optimizaciones de rendimiento de escritorio / móvil.

### 4.1 : Changements
- La funcion **escenario-> getHumanName()** de la clase de escenario php ya no regresa *[objeto] [grupo] [nombre]* Pero *[grupo] [objeto] [nombre]*.
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

- Debian 9 Stretch

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
- Durante una búsqueda, la clave *escapar* cancelar búsqueda.
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
- Guión : Posibilidad haciendo Ctrl + clic en el botón *ejecución* guárdelo, ejecútelo y muestre el registro (si el nivel de registro no está en *No*).
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

- **Lib** : Actualización de jquery 3.4.1
- **Lib** : Actualizar CodeMiror 5.46.0
- **Lib** : Actualizar tablesorter 2.31.1
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
