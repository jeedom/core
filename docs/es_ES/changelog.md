# Registro de cambios Jeedom V4.1

## 4.1.0

- Síntesis : Agregar una nueva página **Inicio → Resumen** ofreciendo una síntesis visual global de las partes.
- Buscar : Adición de un motor de búsqueda en **Herramientas → Buscar**.
- Salpicadero : El modo de edición ahora inserta el mosaico movido.
- Salpicadero : Ahora podemos hacer clic en el *tiempo* widgets de acciones de tiempo para abrir la ventana del historial del comando de información vinculada.
- Salpicadero : El tamaño del mosaico de un equipo nuevo se adapta a su contenido.
- Salpicadero : Agregar un botón para filtrar los elementos mostrados por categoría.
- Salpicadero : Ctrl Click en una información abre la ventana de historial con todos los comandos historizados del equipo visibles en el mosaico. Ctrl Haga clic en una leyenda para mostrar solo esta, Alt Haga clic para mostrarlas todas.
- Salpicadero : Posibilidad de desenfocar imágenes de fondo (Configuración -> Interfaz).
- Herramientas / widgets : La funcion *Aplicar en* muestra los comandos vinculados marcados, al desmarcar uno se aplicará el widget principal predeterminado a este comando.
- Reproductores : Posibilidad de agregar la clase CSS a un widget (consulte la documentación del widget).
- Reproductores : Agregar un widget principal *deslizador vertical*.
- Centro de actualizaciones : Las actualizaciones se verifican automáticamente cuando se abre la página si tiene 120 minutos de antigüedad.
- Centro de actualizaciones : La barra de progreso ahora está en la pestaña *Core y plugins*, y el registro se abre por defecto en la pestaña *Información*.
- Centro de actualizaciones : Si abre otro navegador durante una actualización, la barra de progreso y el registro lo indican.
- Centro de actualizaciones : Si la actualización finaliza correctamente, se muestra una ventana que solicita volver a cargar la página.
- Actualizaciones principales : Implementación de un sistema para limpiar viejos archivos Core no utilizados.
- Widgets / Objetos / Escenarios / Interacciones / Páginas de complementos :
	- Ctrl Clic / Clic Center en un widget, objeto, escenarios, interacción, equipo de complemento : Se abre en una pestaña nueva.
	- Ctrl Clic / Clic Center también disponible en sus menús contextuales (en las pestañas).
- Nueva página ModalDisplay:
	- Menú de análisis : Ctrl Click / Click Center en *Tiempo real* : Abra la ventana en una pestaña nueva, en pantalla completa.
	- Menú de herramientas : Ctrl Click / Click Center en *Notas*, *Probador de expresión*, *Las variables*, *Buscar* : Abra la ventana en una pestaña nueva, en pantalla completa.
- Guión : Agregar un motor de búsqueda (a la izquierda del botón Ejecutar).
- Guión : Adición de la función de edad (da la edad del valor del pedido).
- Guión : *stateChanges ()* ahora acepta el periodo *Hoy* (desde la medianoche hasta ahora), *ayer* y *dia* (por 1 día).
- Guión : Funciones *estadísticas (), promedio (), máximo (), mínimo (), tendencia (), duración ()* : Bugfix durante el período *ayer*, y acepta ahora *dia* (por 1 día).
- Guión : Posibilidad de desactivar el sistema de cotización automática (Configuración → Sistema → Configuración : Comandos).
- Guión : Viendo un *Advertencia* si no se configura ningún activador.
- Guión : Corrección de errores de seleccionar en el bloque copiar / pegar.
- Guión : Copiar / pegar bloque entre diferentes escenarios.
- Guión : Las funciones de deshacer / rehacer ahora están disponibles en forma de botones (al lado del botón de creación de bloque).
- Ventana de variables de escenario : clasificación alfabética en la apertura.
- Análisis / Historia : Ctrl Haga clic en una leyenda para mostrar solo este historial, Alt Haga clic para mostrarlos todos.
- Análisis / Historia : Las opciones *agrupación, tipo, variación, escalera* están activos solo con una sola curva mostrada.
- Análisis / Historia : Ahora podemos usar la opción *Area* con la opción *Escalera*.
- Vista : posibilidad de poner escenarios.
- Historial : Integración de la línea de tiempo en DB por razones de confiabilidad.
- Historial : Gestión de múltiples líneas de tiempo.
- Historial : Revisión gráfica del cronograma.
- Resumen de domótica : Equipos de complementos desactivados y sus controles ya no tienen los iconos a la derecha (configuración de equipos y configuración avanzada).
- Resumen de domótica : Posibilidad de buscar en categorías de equipos.
- Resumen de domótica : Posibilidad de mover varios equipos de un objeto a otro.
- Resumen de domótica : Posibilidad de seleccionar todo el equipo de un objeto.
- Motor de tareas : En la pestaña *Demonio*, los complementos deshabilitados ya no aparecen.
- Configuración : La pestaña *Información* ahora está en la pestaña *Principal*.
- Configuración : La pestaña *Comandos* ahora está en la pestaña *Dispositivos*.
- Ventana de configuración avanzada del equipo : Cambio dinámico de la configuración de la centralita.
- Dispositivos : Nueva categoría *Apertura*.
- Sobre ventana : Adición de accesos directos al registro de cambios y preguntas frecuentes.<br/><br/>
- Aplicación web : Integración de la nueva página de resumen.
- Aplicación web : Página de escenarios, un clic en el título del escenario muestra su registro.
- Aplicación web : Ahora podemos seleccionar / copiar parte de un registro.
- Aplicación web : En la búsqueda en un registro, agregue un botón x para cancelar la búsqueda.
- Aplicación web : Persistencia del cambio de tema (8h).
- Aplicación web : En un diseño, un clic con tres dedos vuelve a la página de inicio.
- Aplicación web : Visualización de escenarios por grupo.
- Aplicación web : Muchas correcciones de errores (UI, iOS vertical / horizontal, etc.).<br/><br/>
- Documentación : Adaptaciones en línea con v4 y v4.1.
- Documentación : Nueva página *Atajos de teclado / mouse* incluyendo un resumen de todos los atajos en Jeedom. Accesible desde el Dashboard doc o las preguntas frecuentes.
- Corrección de errores y optimizaciones.
- Lib : Actualizar HighStock v7.1.2 a v8.1.0.
- Lib : Actualizar jQuery v3.4.1 a v3.5.1.
- Lib : Actualizar Font Awesome 5.9.0 a 5.13.0.
- Informe : uso de chronium si está disponible
- Informe : posibilidad de exportar cronogramas

### 4.0.53

- Corrección de errores.

### 4.0.52

- Corrección de errores (la actualización debe hacerse absolutamente si está en 4.0.51).

### 4.0.51

- Correcciones de errores.
- Optimización del futuro sistema DNS.

### 4.0.49

- Posibilidad de elegir el motor Jeedom TTS y posibilidad de tener complementos que ofrecen un nuevo motor TTS.
- Soporte mejorado para webview en la aplicación móvil.
- Correcciones de errores.
- Actualizando el documento.

### 4.0.47

- Mejora del probador de expresión.
- Actualización del repositorio en smart.
- Correcciones de errores.

### 4.0.44

- Traducciones mejoradas.
- Correcciones de errores.
- Restauración de copia de seguridad en la nube mejorada.
- La restauración en la nube ahora solo repatria la copia de seguridad local, dejando la opción de descargarla o restaurarla.

### 4.0.43

- Traducciones mejoradas.
- Corrección de errores en plantillas de escenarios.

## 4.0.0
- Rediseño completo de temas (Core 2019 Light / Dark / Legacy).
- Posibilidad de cambiar el tema automáticamente según la hora.
- En dispositivos móviles, el tema puede cambiar según el brillo (requiere activación *sensor adicional genérico* en cromo, página de cromo://flags).<br/><br/>
- Mejora y reorganización del menú principal.
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Adición de un botón para acceder al probador de expresión.
- Menú de herramientas : Adición de un botón para acceder a las variables.<br/><br/>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (Panel de control, escenarios, objetos, widgets, interacciones, complementos) ahora están activos cuando se abre la página, lo que le permite escribir una búsqueda directamente.
- Agregue un botón X en los campos de búsqueda para cancelar la búsqueda.
- Durante una búsqueda, la clave *escapar* cancelar búsqueda.
- Salpicadero : En el modo de edición, el campo de búsqueda y sus botones están deshabilitados y se arreglan.
- Salpicadero : En el modo de edición, haga clic en un botón *expandir* a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
- Salpicadero : La ejecución del comando en un mosaico ahora se señala mediante el botón *Actualizar*. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
- Salpicadero : Los mosaicos indican un comando de información (historial, que abrirá la ventana Historial) o acción al pasar el mouse.
- Salpicadero : La ventana de historial ahora le permite abrir este historial en Análisis / Historial.
- Salpicadero : La ventana de historial conserva su posición / dimensiones cuando se vuelve a abrir otro historial.
- Ventana de configuración de comandos: Ctrl + clic en &quot;Guardar&quot; cierra la ventana después.
- Ventana de configuración del equipo: Ctrl + clic en &quot;Guardar&quot; cierra la ventana después.
- Agregar información de uso al eliminar equipos.
- Objetos : Opción agregada para usar colores personalizados.
- Objetos : Agregar menú contextual en pestañas (cambio rápido de objeto).
- Interacciones : Agregar menú contextual en pestañas (cambio rápido de interacción).
- Plugins : Agregar menú contextual en pestañas (cambio rápido de equipo).
- Plugins : En la página de administración de complementos, un punto naranja indica complementos no estables.
- Mejoras de tabla con filtro y opción de clasificación.
- Posibilidad de asignar un ícono a una interacción.
- Cada página de Jeedom ahora tiene un título en el idioma de la interfaz (pestaña del navegador).
- Prevención del autocompletado en el código de acceso de los campos'.
- Gestión de funciones *Página anterior / Página siguiente* navegador.<br/><br/>
- Reproductores : Rediseño del sistema de widgets (menú Herramientas / Widgets).
- Reproductores : Posibilidad de reemplazar un widget con otro en todos los comandos que lo usan.
- Reproductores : Posibilidad de asignar un widget a múltiples comandos.
- Reproductores : Agregar widget numérico de información horizontal.
- Reproductores : Agregar un widget vertical numérico de información.
- Reproductores : Adición de una brújula de información numérica / widget de viento (gracias @thanaus).
- Reproductores : Agregar un widget de lluvia de información numérica (gracias @thanaus)
- Reproductores : Visualización del widget de obturador de información / acción proporcional al valor.<br/><br/>
- Configuración : Mejora y reorganización de pestañas.
- Configuración : Agregando muchos *información sobre herramientas* (Ayuda).
- Configuración : Agregar un motor de búsqueda.
- Configuración : Agregar un botón para vaciar el caché del widget (pestaña Caché).
- Configuración : Opción agregada para deshabilitar el caché del widget (pestaña Caché).
- Configuración : Capacidad para centrar el contenido de los mosaicos verticalmente (pestaña Interfaz).
- Configuración : Adición de un parámetro para la purga global de las historias (Comandos Tab).
- Configuración : Cambio de #message# A las #subject# en Configuración / Registros / Mensajes para evitar la duplicación del mensaje.
- Configuración : Posibilidad en los resúmenes de agregar una exclusión de los pedidos que no se han actualizado durante más de XX minutos (ejemplo para el cálculo de los promedios de temperatura si un sensor no ha elevado nada durante más de 30 minutos, se excluirá del cálculo )<br/><br/>
- Guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque.
- Guión : Posibilidad mediante Ctrl + clic en el botón *ejecución* guárdelo, ejecútelo y muestre el registro (si el nivel de registro no está activado *Ninguna*).
- Guión : Confirmación de eliminación de bloque. Ctrl + clic para evitar la confirmación.
- Guión : Adición de una función de búsqueda en los bloques de Código. Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- Guión : Capacidad para condensar bloques.
- Guión : La acción &#39;Agregar bloque&#39; cambia a la pestaña Escenario si es necesario.
- Guión : Nuevas funciones de copiar / pegar en bloque. Ctrl + clic para cortar / reemplazar.
- Guión : Ya no se agrega un nuevo bloque al final del escenario, sino después del bloque donde estaba antes de hacer clic, determinado por el último campo en el que hizo clic.
- Guión : Implementación de un sistema Deshacer / Rehacer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Guión : Eliminar escenario compartido.
- Guión : Mejora de la ventana de gestión de plantillas de escenarios.<br/><br/>
- Análisis / Equipamiento : Adición de un motor de búsqueda (pestaña Baterías, búsqueda de nombres y padres).
- Análisis / Equipamiento : Ahora se puede hacer clic en el área de calendario / días del equipo para acceder directamente al cambio de batería (s).
- Análisis / Equipamiento : Adición de un campo de búsqueda.<br/><br/>
- Centro de actualizaciones : Advertencia en la pestaña &#39;Núcleo y complementos&#39; y / u &#39;Otros&#39; si hay una actualización disponible. Cambie a &#39;Otros&#39; si es necesario.
- Centro de actualizaciones : diferenciación por versión (estable, beta, ...).
- Centro de actualizaciones : adición de una barra de progreso durante la actualización.<br/><br/>
- Resumen de domótica : El historial de eliminación ahora está disponible en una pestaña (Resumen - Historial).
- Resumen de domótica : Revisión completa, posibilidad de ordenar objetos, equipos, pedidos.
- Resumen de domótica : Adición de equipos e ID de pedidos, en pantalla y en búsqueda.
- Resumen de domótica : Exportación CSV de objeto primario, id, equipo y su id, comando.
- Resumen de domótica : Posibilidad de hacer visibles o no uno o más pedidos.<br/><br/>
- Diseño : Capacidad para especificar el orden (posición) de *Diseños* y *Diseños 3D* (Editar, configurar diseño).
- Diseño : Adición de un campo CSS personalizado en los elementos del *Diseño*.
- Diseño : Desplazamiento de las opciones de visualización en Diseño de la configuración avanzada, en los parámetros de visualización de la *Diseño*. Esto para simplificar la interfaz y permitir tener diferentes parámetros por *Diseño*.
- Diseño : Mover y redimensionar componentes en *Diseño* tiene en cuenta su tamaño, con o sin magnetización.<br/><br/>
- Reducción general (estilos CSS / en línea, refactorización, etc.) y mejoras de rendimiento.
- Elimine Font Awesome 4 para mantener solo Font Awesome 5.
- Actualización de Libs : jquery 3.4.1, CodeMiror 5.46.0, clasificador de tabla 2.31.1.
- Numerosas correcciones de errores.
- Agregar un sistema de configuración masiva (utilizado en la página Equipo para configurar la Alerta de comunicación en estos)

>**IMPORTANTE**
>
>Si después de la actualización tiene un error en el Tablero, intente reiniciar su caja para que tenga en cuenta las nuevas adiciones de componentes.

>**IMPORTANTE**
>
>El complemento de widgets no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han asumido internamente en el núcleo). Más información [aquí](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).
