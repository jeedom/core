# Registro de cambios Jeedom V4.1

## 4.1.0

### Requisitos previos

- Debian 10 Buster

### Noticias / Mejoras

- **Síntesis** : Agregar una nueva página **Inicio → Resumen** Ofrece un resumen visual global de las partes, con acceso rápido a resúmenes.
- **Buscar** : Adición de un motor de búsqueda en **Herramientas → Buscar**.
- **Salpicadero** : El modo de edición ahora inserta el mosaico movido.
- **Salpicadero** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **Salpicadero** : Ahora podemos hacer clic en el *tiempo* widgets de acciones de tiempo para abrir la ventana del historial del comando de información vinculada.
- **Salpicadero** : El tamaño del mosaico de un equipo nuevo se adapta a su contenido.
- **Salpicadero** : Agregar (¡atrás!) Un botón para filtrar los elementos mostrados por categoría.
- **Salpicadero** : Ctrl Click en una información abre la ventana de historial con todos los comandos historizados del equipo visibles en el mosaico. Ctrl Haga clic en una leyenda para mostrar solo esta, Alt Haga clic para mostrarlas todas.
- **Salpicadero** : Rediseño de la visualización del árbol de objetos (flecha a la izquierda de la búsqueda).
- **Salpicadero** : Posibilidad de desenfocar imágenes de fondo (Configuración -> Interfaz).
- **Herramientas / widgets** : La funcion *Aplicar en* muestra los comandos vinculados marcados, al desmarcar uno se aplicará el widget principal predeterminado a este comando.
- **Reproductores** : Agregar un widget principal *deslizador vertical*.
- **Reproductores** : Agregar un widget principal *binarySwitch*.
- **Centro de actualizaciones** : Las actualizaciones se verifican automáticamente cuando se abre la página si tiene 120 minutos de antigüedad.
- **Centro de actualizaciones** : La barra de progreso ahora está en la pestaña *Core y plugins*, y el registro se abre por defecto en la pestaña *Información*.
- **Centro de actualizaciones** : Si abre otro navegador durante una actualización, la barra de progreso y el registro lo indican.
- **Centro de actualizaciones** : Si la actualización finaliza correctamente, se muestra una ventana que solicita volver a cargar la página.
- **Actualizaciones principales** : Implementación de un sistema para limpiar viejos archivos Core no utilizados.
- **Guión** : Agregar un motor de búsqueda (a la izquierda del botón Ejecutar).
- **Guión** : Adición de la función de edad (da la edad del valor del pedido).
- **Guión** : *stateChanges()* ahora acepta el periodo *Hoy* (medianoche hasta ahora), *ayer* y *dia* (por 1 día).
- **Guión** : Funciones *estadísticas (), promedio (), máximo (), mínimo (), tendencia (), duración()* : Bugfix durante el período *ayer*, y acepta ahora *dia* (por 1 día).
- **Guión** : Posibilidad de desactivar el sistema de cotización automática (Configuración → Sistema → Configuración : Equipements).
- **Guión** : Viendo un *Advertencia* si no se configura ningún activador.
- **Guión** : Corrección de errores de *Seleccione* en el bloque copiar / pegar.
- **Guión** : Copiar / pegar bloque entre diferentes escenarios.
- **Guión** : Las funciones de deshacer / rehacer ahora están disponibles como botones (al lado del botón de creación de bloque).
- **Guión** :  adición de "Exportación histórica" (exportHistory)
- **Ventana de variables de escenario** : Clasificación alfabética en la apertura.
- **Ventana de variables de escenario** : Ahora se puede hacer clic en los escenarios utilizados por las variables, con la apertura de la búsqueda en la variable.
- **Análisis / Historia** : Ctrl Haga clic en una leyenda para mostrar solo este historial, Alt Haga clic para mostrarlos todos.
- **Análisis / Historia** : Las opciones *agrupación, tipo, variación, escalera* están activos solo con una sola curva mostrada.
- **Análisis / Historia** : Ahora podemos usar la opción *Area* con la opción *Escalera*.
- **Análisis / Registros** : Nueva fuente tipo monoespacio para registros.
- **Vista** : Posibilidad de poner escenarios.
- **Vista** : El modo de edición ahora inserta el mosaico movido.
- **Vista** : Modo de edición: Los iconos de actualización del equipo se reemplazan por un icono que permite acceder a su configuración, gracias a un nuevo modal simplificado.
- **Vista** : El orden de visualización ahora es independiente del que se muestra en el Panel de control.
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
- **Reporte** : El uso de *cromo* si está disponible.
- **Reporte** : Posibilidad de exportar cronogramas.
- **Configuración** : La pestaña *Información* ahora está en la pestaña *Principal*.
- **Configuración** : La pestaña *Comandos* ahora está en la pestaña *Dispositivos*.
- **Ventana de configuración avanzada del equipo** : Cambio dinámico de la configuración de la centralita.
- **Dispositivos** : Nueva categoría *Apertura*.
- **Dispositivos** : Posibilidad de invertir comandos tipo cursor (info y acción)
- **Dispositivos** : Posibilidad de agregar clase css a un mosaico (consulte la documentación del widget).
- **Sobre ventana** : Adición de accesos directos al registro de cambios y preguntas frecuentes.
- Widgets / Objetos / Escenarios / Interacciones / Páginas de complementos :
	- Ctrl Clic / Clic Center en un widget, objeto, escenarios, interacción, equipo de complemento : Se abre en una pestaña nueva.
	- Ctrl Clic / Clic Center también disponible en sus menús contextuales (en las pestañas).
- Nueva página ModalDisplay :
	- Menú de análisis : Ctrl Click / Click Center en *Tiempo real* : Abra la ventana en una pestaña nueva, en pantalla completa.
	- Menú de herramientas : Ctrl Click / Click Center en *Notas*, *Probador de expresión*, *Las variables*, *Buscar* : Abra la ventana en una pestaña nueva, en pantalla completa.
- Bloque de código, Editor de archivos, Personalización avanzada : Adaptación del tema oscuro.

### WebApp
- Integración de la nueva página de resumen.
- Página de escenarios, un clic en el título del escenario muestra su registro.
- Ahora podemos seleccionar / copiar parte de un registro.
- En la búsqueda en un registro, agregue un botón x para cancelar la búsqueda.
- Persistencia del cambio de tema (8h).
- En un diseño, un clic con tres dedos vuelve a la página de inicio.
- Visualización de escenarios por grupo.
- Nueva fuente tipo monoespacio para registros.
- Muchas correcciones de errores (UI, vertical / horizontal iOS, etc.).

### Autres
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

### Changements
- La funcion **escenario-> getHumanName()** de la clase de escenario php ya no regresa *[objeto] [grupo] [nombre]* Pero *[grupo] [objeto] [nombre]*.
- La funcion **escenario-> byString()** ahora debe llamarse con la estructura *[grupo] [objeto] [nombre]*.
- Las funciones **red-> getInterfaceIp () red-> getInterfaceMac () red-> getInterfaces()** han sido reemplazados por **network-> getInterfacesInfo()**


# Registro de cambios Jeedom V4.0

## 4.0.61

- Se solucionó un problema al aplicar una plantilla de escenario
- Adición de una opción para deshabilitar la verificación SSL durante la comunicación con el mercado (no recomendado pero útil en ciertas configuraciones de red específicas)
- Se solucionó un problema con el historial de archivo si el modo de suavizado era para siempre
- Correcciones de errores
- Corrección del comando trigger () en escenarios para que devuelva el nombre del disparador (sin el #) en lugar del valor, para el valor debes usar triggerValue()

## 4.0.60

- Eliminación del nuevo sistema DNS en eu.jeedom.enlace que sigue a demasiados operadores que prohíben flujos http2 permanentes

## 4.0.59

- Errores corregidos en los widgets de tiempo
- Aumento de la cantidad de contraseñas incorrectas antes del destierro (evita problemas con la aplicación web al rotar claves API))

## 4.0.57

- Refuerzo de la seguridad de las cookies
- Usar cromo (si está instalado) para informes
- Se solucionó un problema con el cálculo de la hora del estado en los widgets si la zona horaria de jeedom no es la misma que la del navegador
- Correcciones de errores

## 4.0.55

- El nuevo dns (\*. Eu.jeedom.enlace) se convierte en el DNS primario (el antiguo DNS todavía funciona)

## 4.0.54

- Inicio de la actualización del nuevo sitio de documentación

## 4.0.53

- Corrección de errores.

## 4.0.52

- Corrección de errores (actualice para hacerlo absolutamente si está en 4.0.51).

## 4.0.51

- Correcciones de errores.
- Optimización del futuro sistema DNS.

## 4.0.49

- Posibilidad de elegir el motor Jeedom TTS y posibilidad de tener plugins que ofrezcan un nuevo motor TTS.
- Compatibilidad mejorada con webview en la aplicación móvil.
- Correcciones de errores.
- Actualizando el documento.

## 4.0.47

- Probador de expresión mejorado.
- Actualización del repositorio en smart.
- Correcciones de errores.

## 4.0.44

- Traducciones mejoradas.
- Correcciones de errores.
- Restauración de copia de seguridad en la nube mejorada.
- La restauración en la nube ahora solo recupera la copia de seguridad local, dejando la opción de descargarla o restaurarla.

## 4.0.43

- Traducciones mejoradas.
- Errores corregidos en las plantillas de escenarios.

## 4.0.0
- Rediseño completo del tema (Core 2019 Light / Dark / Legacy).
- Posibilidad de cambiar el tema automáticamente en función de la hora.
- En el móvil, el tema puede cambiar dependiendo del brillo (Requiere activar *sensor adicional genérico* en cromo, página de cromo://flags).<br/><br/>
- Mejora y reorganización del menú principal.
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Agregue un botón para acceder al probador de expresiones.
- Menú de herramientas : Agregar un botón para acceder a las variables.<br/><br/>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (tablero, escenarios, objetos, widgets, interacciones, complementos) ahora están activos al abrir la página, lo que le permite escribir una búsqueda directamente.
- Se agregó un botón X en los campos de búsqueda para cancelar la búsqueda.
- Durante una búsqueda, la clave *escapar* cancelar búsqueda.
- Salpicadero : En el modo de edición, el control de búsqueda y sus botones se desactivan y se fijan.
- Salpicadero : En el modo de edición, un clic de un botón *expandir* a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
- Salpicadero : La ejecución del comando en un mosaico ahora se indica con el botón *Actualizar*. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
- Salpicadero : Los mosaicos indican un comando de información (histórico, que abrirá la ventana Historial) o una acción al pasar el mouse.
- Salpicadero : La ventana del historial ahora le permite abrir este historial en Análisis / Historial.
- Salpicadero : La ventana del historial mantiene su posición / dimensiones al reabrir otro historial.
- Ventana de configuración de comandos: Ctrl + clic en "Guardar" cierra la ventana después.
- Ventana de configuración del equipo: Ctrl + clic en "Guardar" cierra la ventana después.
- Agregar información de uso al eliminar un dispositivo.
- Objetos : Opción agregada para usar colores personalizados.
- Objetos : Adición de un menú contextual en las pestañas (cambio rápido de objeto).
- Interacciones : Adición de un menú contextual en las pestañas (cambio rápido de interacción).
- Plugins : Adición de un menú contextual en las pestañas (cambio rápido de equipo).
- Plugins : En la página de administración de complementos, un punto naranja indica los complementos en la versión no estable.
- Mejoras en la tabla con opción de filtro y clasificación.
- Posibilidad de asignar un icono a una interacción.
- Cada página de Jeedom ahora tiene un título en el idioma de la interfaz (pestaña del navegador).
- Prevención del autocompletado en los campos de 'Código de acceso''.
- Gestión de funciones *Página anterior / Página siguiente* desde el navegador.<br/><br/>
- Reproductores : Rediseño del sistema de widgets (menú Herramientas / Widgets).
- Reproductores : Posibilidad de reemplazar un widget por otro en todos los comandos que lo utilizan.
- Reproductores : Posibilidad de asignar widgets a varios comandos.
- Reproductores : Agregar un widget de información numérica horizontal.
- Reproductores : Agregar un widget de información numérica vertical.
- Reproductores : Adición de un widget de información de viento / brújula numérica (gracias @thanaus).
- Reproductores : Se agregó un widget de información de lluvia numérica (gracias @thanaus)
- Reproductores : Visualización del widget de obturador de información / acción proporcional al valor.<br/><br/>
- Configuración : Mejora y reorganización de pestañas.
- Configuración : Adición de muchos *información sobre herramientas* (aide).
- Configuración : Agregar un motor de búsqueda.
- Configuración : Se agregó un botón para vaciar la caché de widgets (pestaña Caché).
- Configuración : Se agregó una opción para deshabilitar la caché de widgets (pestaña Caché).
- Configuración : Posibilidad de centrar verticalmente el contenido de los mosaicos (pestaña Interfaz).
- Configuración : Se agregó un parámetro para la purga global de registros (pestaña Pedidos).
- Configuración : Cambio de #message# A las #subject# en Configuración / Registros / Mensajes para evitar duplicar el mensaje.
- Configuración : Posibilidad en los resúmenes de agregar una exclusión de pedidos que no se han actualizado durante más de XX minutos (ejemplo para el cálculo de promedios de temperatura si un sensor no ha reportado nada durante más de 30min se excluirá del cálculo)<br/><br/>
- Guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque.
- Guión : Posibilidad haciendo Ctrl + clic en el botón *ejecución* guárdelo, ejecútelo y muestre el registro (si el nivel de registro no está en *Ninguna*).
- Guión : Confirmación de eliminación de bloque. Ctrl + clic para evitar la confirmación.
- Guión : Adición de una función de búsqueda en bloques de código. Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- Guión : Posibilidad de condensar los bloques.
- Guión : La acción 'Agregar bloque' cambia a la pestaña Escenario si es necesario.
- Guión : Nuevas funciones de copiar / pegar en bloque. Ctrl + clic para cortar / reemplazar.
- Guión : Ya no se agrega un nuevo bloque al final de la línea de tiempo, sino después del bloque en el que estaba antes de hacer clic, determinado por el último campo en el que hizo clic.
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
- Diseño : Posibilidad de especificar el orden (posición) del *Diseños* y *Diseños en 3D* (Editar, configurar diseño).
- Diseño : Adición de un campo CSS personalizado en los elementos del *Diseño*.
- Diseño : Se movieron las opciones de visualización en Diseño de la configuración avanzada, en los parámetros de visualización de la *Diseño*. Esto con el fin de simplificar la interfaz y permitir tener diferentes parámetros mediante *Diseño*.
- Diseño : Mover y cambiar el tamaño de componentes en *Diseño* tiene en cuenta su tamaño, con o sin magnetización.<br/><br/>
- Aligeramiento general (estilos CSS / en línea, refactorización, etc.) y mejoras de rendimiento.
- Elimine Font Awesome 4 para mantener solo Font Awesome 5.
- Actualización de Libs : jquery 3.4.1, CodeMiror 5.46.0, clasificador de tabla 2.31.1.
- Numerosas correcciones de errores.
- Adición de un sistema de configuración masiva (utilizado en la página Equipo para configurar alertas de comunicaciones en ellos)
- Adición de compatibilidad global de Jeedom DNS con una conexión a internet 4G.
- Arreglo de seguridad

>**Importante**
>
>Si después de la actualización tiene un error en el Tablero, intente reiniciar su caja para que tenga en cuenta las nuevas adiciones de componentes.

>**Importante**
>
>El complemento de widget no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han tomado internamente en el núcleo). Más información [aquí](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).


