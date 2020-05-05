
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
- En dispositivos móviles, el tema puede cambiar según el brillo (requiere activar * sensor adicional genérico * en cromo, página de cromo://flags).<br/><br/>
- Mejora y reorganización del menú principal..
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Adición de un botón para acceder al probador de expresión.
- Menú de herramientas : Adición de un botón para acceder a las variables..<br/><br/>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (panel de control, escenarios, objetos, widgets, interacciones, complementos) ahora están activos cuando se abre la página, lo que le permite escribir una búsqueda directamente.
- Agregue un botón X en los campos de búsqueda para cancelar la búsqueda..
- Durante una búsqueda, la tecla * escape * cancela la búsqueda.
- Salpicadero : En el modo de edición, el campo de búsqueda y sus botones están deshabilitados y se arreglan.
- Salpicadero : En el modo de edición, un clic en un botón * expandir * a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
- Salpicadero : La ejecución de la orden en un mosaico ahora se indica mediante el botón * actualizar*. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
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
- Gestión de funciones * Página anterior / Página siguiente * del navegador.<br/><br/>
- Reproductores : Rediseño del sistema de widgets (menú Herramientas / Widgets).
- Reproductores : Posibilidad de reemplazar un widget con otro en todos los comandos que lo usan.
- Reproductores : Posibilidad de asignar un widget a múltiples comandos.
- Reproductores : Agregar widget numérico de información horizontal.
- Reproductores : Agregar un widget vertical numérico de información.
- Reproductores : Adición de una brújula de información numérica / widget de viento (gracias @thanaus).
- Reproductores : Agregar un widget de lluvia de información numérica (gracias @thanaus)
- Reproductores : Visualización del widget de obturador de información / acción proporcional al valor.<br/><br/>
- Configuración : Mejora y reorganización de pestañas.
- Configuración : Se agregaron muchos * consejos sobre herramientas * (ayuda).
- Configuración : Agregar un motor de búsqueda.
- Configuración : Agregar un botón para vaciar el caché del widget (pestaña Caché).
- Configuración : Opción agregada para deshabilitar el caché del widget (pestaña Caché).
- Configuración : Capacidad para centrar el contenido de los mosaicos verticalmente (pestaña Interfaz).
- Configuración : Adición de un parámetro para la purga global de las historias (Comandos Tab).
- Configuración : Cambie de # mensaje # a # asunto # en Configuración / Registros / Mensajes para evitar la duplicación del mensaje.
- Configuración : Posibilidad en los resúmenes de agregar una exclusión de los pedidos que no se han actualizado durante más de XX minutos (ejemplo para el cálculo de los promedios de temperatura si un sensor no ha elevado nada durante más de 30 minutos, se excluirá del cálculo )<br/><br/>
- Guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque..
- Guión : Posibilidad mediante Ctrl + clic en el botón * ejecución * para guardarlo, iniciarlo y mostrar el registro (si el nivel de registro no está activado * Ninguno *).
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
- Diseño : Posibilidad de especificar el orden (posición) de * Diseños * y * Diseños 3D * (Editar, Configurar diseño).
- Diseño : Adición de un campo CSS personalizado en los elementos de * diseño*.
- Diseño : Desplazamiento de las opciones de visualización en Diseño de la configuración avanzada, en la configuración de visualización de * Diseño*. Esto para simplificar la interfaz y permitir tener diferentes parámetros por * Diseño*.
- Diseño : Mover y cambiar el tamaño de los componentes en * Diseño * tiene en cuenta su tamaño, con o sin magnetización.<br/><br/>
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
>El complemento de widgets no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han asumido internamente en el núcleo). Más información [aquí](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

