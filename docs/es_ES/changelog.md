
Registro de cambios Jeedom V4
=========

4.0.54
=====

- Inicio de la actualización del nuevo sitio de documentación.

4.0.53
=====

- Corrección de errores.

4.0.52
=====

- Corrección de errores (la actualización debe hacerse absolutamente si está en 4.0.51).

4.0.51
=====

- Correcciones de errores.
- Optimización del futuro sistema DNS.

4.0.49
=====

- Posibilidad de elegir el motor Jeedom TTS y posibilidad de tener complementos que ofrecen un nuevo motor TTS.
- Soporte mejorado para webview en la aplicación móvil.
- Correcciones de errores.
- Actualizando el documento.

4.0.47
=====

- Mejora del probador de expresión.
- Actualización del repositorio en smart.
- Correcciones de errores.

4.0.44
=====

- Traducciones mejoradas.
- Correcciones de errores.
- Restauración de copia de seguridad en la nube mejorada.
- La restauración en la nube ahora solo repatria la copia de seguridad local, dejando la opción de descargarla o restaurarla.

4.0.43
=====

- Traducciones mejoradas.
- Corrección de errores en plantillas de escenarios..

4.0.0
=====
- Rediseño completo de temas (Core 2019 Light / Dark / Legacy).
- Posibilidad de cambiar el tema automáticamente según la hora.
- En dispositivos móviles, el tema puede cambiar según el brillo (requiere activación *sensor adicional genérico* en cromo, página de cromo://flags).<br/><br/>
- Mejora y reorganización del menú principal..
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Adición de un botón para acceder al probador de expresión.
- Menú de herramientas : Adición de un botón para acceder a las variables..<br/><br/>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (panel de control, escenarios, objetos, widgets, interacciones, complementos) ahora están activos cuando se abre la página, lo que le permite escribir una búsqueda directamente.
- Agregue un botón X en los campos de búsqueda para cancelar la búsqueda..
- Durante una búsqueda, la clave *echap* cancelar búsqueda.
- Tablero de instrumentos : En el modo de edición, el campo de búsqueda y sus botones están deshabilitados y se arreglan.
- Tablero de instrumentos : En el modo de edición, haga clic en un botón. *expand* a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
- Tablero de instrumentos : La ejecución del comando en un mosaico ahora se señala mediante el botón *refresh*. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
- Tablero de instrumentos : Los mosaicos indican un comando de información (historial, que abrirá la ventana Historial) o acción al pasar el mouse.
- Tablero de instrumentos : La ventana de historial ahora le permite abrir este historial en Análisis / Historial.
- Tablero de instrumentos : La ventana de historial conserva su posición / dimensiones cuando se vuelve a abrir otro historial.
- Ventana de configuración de comandos: Ctrl + clic en &quot;Guardar&quot; cierra la ventana después.
- Ventana de configuración del equipo: Ctrl + clic en &quot;Guardar&quot; cierra la ventana después.
- Agregar información de uso al eliminar equipos.
- Objetos : Opción agregada para usar colores personalizados.
- Objetos : Agregar menú contextual en pestañas (cambio rápido de objeto).
- Interacciones : Agregar menú contextual en pestañas (cambio rápido de interacción).
- Complementos : Agregar menú contextual en pestañas (cambio rápido de equipo).
- Complementos : En la página de administración de complementos, un punto naranja indica complementos no estables.
- Mejoras de tabla con filtro y opción de clasificación.
- Posibilidad de asignar un ícono a una interacción.
- Cada página de Jeedom ahora tiene un título en el idioma de la interfaz (pestaña del navegador).
- Prevención del autocompletado en el código de acceso de los campos'.
- Gestión de funciones *Página anterior / Página siguiente* navegador.<br/><br/>
- Widgets : Rediseño del sistema de widgets (menú Herramientas / Widgets).
- Widgets : Posibilidad de reemplazar un widget con otro en todos los comandos que lo usan.
- Widgets : Posibilidad de asignar un widget a múltiples comandos.
- Widgets : Agregar widget numérico de información horizontal.
- Widgets : Agregar un widget vertical numérico de información.
- Widgets : Adición de una brújula de información numérica / widget de viento (gracias @thanaus).
- Widgets : Agregar un widget de lluvia de información numérica (gracias @thanaus)
- Widgets : Visualización del widget de obturador de información / acción proporcional al valor.<br/><br/>
- Configuracion : Mejora y reorganización de pestañas.
- Configuracion : Agregando muchos *tooltips* (Ayuda).
- Configuracion : Agregar un motor de búsqueda.
- Configuracion : Agregar un botón para vaciar el caché del widget (pestaña Caché).
- Configuracion : Opción agregada para deshabilitar el caché del widget (pestaña Caché).
- Configuracion : Capacidad para centrar el contenido de los mosaicos verticalmente (pestaña Interfaz).
- Configuracion : Adición de un parámetro para la purga global de las historias (Comandos Tab).
- Configuracion : Cambio de #message# A las #subject# en Configuración / Registros / Mensajes para evitar la duplicación del mensaje.
- Configuracion : Posibilidad en los resúmenes de agregar una exclusión de los pedidos que no se han actualizado durante más de XX minutos (ejemplo para el cálculo de los promedios de temperatura si un sensor no ha elevado nada durante más de 30 minutos, se excluirá del cálculo )<br/><br/>
- Guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque..
- Guión : Posibilidad mediante Ctrl + clic en el botón *ejecución* guárdelo, ejecútelo y muestre el registro (si el nivel de registro no está activado *Aucun*).
- Guión : Confirmación de eliminación de bloque. Ctrl + clic para evitar la confirmación.
- Guión : Adición de una función de búsqueda en los bloques de Código. Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- Guión : Capacidad para condensar bloques.
- Guión : La acción &#39;Agregar bloque&#39; cambia a la pestaña Escenario si es necesario.
- Guión : Nuevas funciones de copiar / pegar en bloque. Ctrl + clic para cortar / reemplazar.
- Guión : Ya no se agrega un nuevo bloque al final del escenario, sino después del bloque donde estaba antes de hacer clic, determinado por el último campo en el que hizo clic.
- Guión : Implementación de un sistema Deshacer / Rehacer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Guión : Eliminar escenario compartido.
- Guión : Mejora de la ventana de gestión de plantillas de escenarios..<br/><br/>
- Análisis / Equipamiento : Adición de un motor de búsqueda (pestaña Baterías, búsqueda de nombres y padres).
- Análisis / Equipamiento : Ahora se puede hacer clic en el área de calendario / días del equipo para acceder directamente al cambio de batería (s).
- Análisis / Equipamiento : Adición de un campo de búsqueda..<br/><br/>
- Centro de actualizaciones : Advertencia en la pestaña &#39;Núcleo y complementos&#39; y / u &#39;Otros&#39; si hay una actualización disponible. Cambie a &#39;Otros&#39; si es necesario.
- Centro de actualizaciones : diferenciación por versión (estable, beta, ...).
- Centro de actualizaciones : adición de una barra de progreso durante la actualización.<br/><br/>
- Resumen de domótica : El historial de eliminación ahora está disponible en una pestaña (Resumen - Historial).
- Resumen de domótica : Revisión completa, posibilidad de ordenar objetos, equipos, pedidos.
- Resumen de domótica : Adición de equipos e ID de pedidos, en pantalla y en búsqueda.
- Resumen de domótica : Exportación CSV de objeto primario, id, equipo y su id, comando.
- Resumen de domótica : Posibilidad de hacer visibles o no uno o más pedidos.<br/><br/>
- Diseño : Capacidad para especificar el orden (posición) de *Designs* y *Diseños en 3D* (Editar, configurar diseño).
- Diseño : Adición de un campo CSS personalizado en los elementos del *design*.
- Diseño : Desplazamiento de las opciones de visualización en Diseño de la configuración avanzada, en los parámetros de visualización de la *Design*. Esto para simplificar la interfaz y permitir tener diferentes parámetros por *Design*.
- Diseño : Mover y redimensionar componentes en *Design* tiene en cuenta su tamaño, con o sin magnetización.<br/><br/>
- Reducción general (estilos CSS / en línea, refactorización, etc.) y mejoras de rendimiento.
- Elimine Font Awesome 4 para mantener solo Font Awesome 5.
- Actualización de Libs : jquery 3.4.1, CodeMiror 5.46.0, clasificador de tabla 2.31.1.
- Numerosas correcciones de errores.
- Agregar un sistema de configuración masiva (utilizado en la página Equipo para configurar Alertas de comunicaciones en ellos)
- Adición de compatibilidad global de Jeedom DNS con una conexión a internet 4G.
- Arreglo de seguridad

>**IMPORTANT**
>
>Si después de la actualización tiene un error en el tablero, intente reiniciar su caja para que tenga en cuenta las nuevas adiciones de componentes.

>**IMPORTANT**
>
>El complemento de widgets no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han asumido internamente en el núcleo). Más información [aqui](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

