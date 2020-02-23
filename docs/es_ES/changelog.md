cambios
=========

4.0.0
=====
- Rediseño completo de temas (Core 2019 Light / Dark / Legacy).
- Posibilidad de cambiar el tema automáticamente según la hora.
- En dispositivos móviles, el tema puede cambiar según el brillo (requiere activar * sensor adicional genérico * en cromo, página de cromo:// flags). <br/><br/>
- Mejora y reorganización del menú principal..
- Menú de complementos : La lista de categorías y complementos ahora está ordenada alfabéticamente.
- Menú de herramientas : Adición de un botón para acceder al probador de expresión.
- Menú de herramientas : Adición de un botón para acceder a las variables. <br/><br/>
- Los campos de búsqueda ahora admiten acentos.
- Los campos de búsqueda (panel de control, escenarios, objetos, widgets, interacciones, complementos) ahora están activos cuando se abre la página, lo que le permite escribir una búsqueda directamente.
- Agregue un botón X en los campos de búsqueda para cancelar la búsqueda..
- Durante una búsqueda, la tecla * escape * cancela la búsqueda.
- salpicadero : En el modo de edición, el campo de búsqueda y sus botones están deshabilitados y se arreglan.
- salpicadero : En el modo de edición, un clic en un botón * expandir * a la derecha de los objetos cambia el tamaño de los mosaicos del objeto a la altura del más alto. Ctrl + clic los reduce a la altura del más bajo.
- salpicadero : La ejecución de la orden en un mosaico ahora se indica mediante el botón * actualizar*. Si no hay ninguno en el mosaico, aparecerá durante la ejecución.
- salpicadero : Los mosaicos indican un comando de información (historial, que abrirá la ventana Historial) o acción al pasar el mouse.
- salpicadero : La ventana de historial ahora le permite abrir este historial en Análisis / Historial.
- salpicadero : La ventana de historial conserva su posición / dimensiones cuando se vuelve a abrir otro historial.
- Ventana de configuración de comandos: Ctrl + clic en &quot;Guardar&quot; cierra la ventana después.
- Ventana de configuración del equipo: Ctrl + clic en &quot;Guardar&quot; cierra la ventana después.
- Agregar información de uso al eliminar equipos.
- objetos : Opción agregada para usar colores personalizados.
- objetos : Agregar menú contextual en pestañas (cambio rápido de objeto).
- interacciones : Agregar menú contextual en pestañas (cambio rápido de interacción).
- plugins : Agregar menú contextual en pestañas (cambio rápido de equipo).
- plugins : En la página de administración de complementos, un punto naranja indica complementos no estables.
- Mejoras de tabla con filtro y opción de clasificación.
- Posibilidad de asignar un ícono a una interacción.
- Cada página de Jeedom ahora tiene un título en el idioma de la interfaz (pestaña del navegador).
- Prevención del autocompletado en los campos &#39;Código de acceso&#39;.
- Gestión de funciones * Página anterior / Página siguiente * del navegador. <br/><br/>
- Reproductores : Rediseño del sistema de widgets (menú Herramientas / Reproductores).
- Reproductores : Posibilidad de reemplazar un widget con otro en todos los comandos que lo usan.
- Reproductores : Posibilidad de asignar un widget a múltiples comandos.
- Reproductores : Agregar widget numérico de información horizontal.
- Reproductores : Agregar un widget vertical numérico de información.
- Reproductores : Adición de una brújula de información numérica / widget de viento (gracias @thanaus).
- Reproductores : Agregar un widget de lluvia de información numérica (gracias @thanaus)
- Reproductores : Visualización del widget de obturador de información / acción proporcional al valor. <br/><br/>
- configuración : Mejora y reorganización de pestañas.
- configuración : Se agregaron muchos * consejos sobre herramientas * (ayuda).
- configuración : Agregar un motor de búsqueda.
- configuración : Agregar un botón para vaciar el caché del widget (pestaña Caché).
- configuración : Opción agregada para deshabilitar el caché del widget (pestaña Caché).
- configuración : Capacidad para centrar el contenido de los mosaicos verticalmente (pestaña Interfaz).
- configuración : Adición de un parámetro para la purga global de las historias (Comandos Tab).
- configuración : Cambie de # mensaje # a # asunto # en Configuración / Registros / Mensajes para evitar la duplicación del mensaje.
- configuración : Posibilidad en los resúmenes de agregar una exclusión de los pedidos que no se hayan actualizado durante más de XX minutos (ejemplo para el cálculo de los promedios de temperatura si un sensor no ha elevado nada durante más de 30 minutos, se excluirá del cálculo ) <br/><br/>
- guión : La coloración de los bloques ya no es aleatoria, sino por tipo de bloque..
- guión : Posibilidad mediante Ctrl + clic en el botón * ejecución * para guardarlo, iniciarlo y mostrar el registro (si el nivel de registro no está activado * Ninguno *).
- guión : Confirmación de eliminación de bloque. Ctrl + clic para evitar la confirmación.
- guión : Adición de una función de búsqueda en los bloques de Código. Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- guión : Capacidad para condensar bloques.
- guión : La acción &#39;Agregar bloque&#39; cambia a la pestaña Escenario si es necesario.
- guión : Nuevas funciones de copiar / pegar en bloque. Ctrl + clic para cortar / reemplazar.
- guión : Ya no se agrega un nuevo bloque al final del escenario, sino después del bloque donde estaba antes de hacer clic, determinado por el último campo en el que hizo clic.
- guión : Implementación de un sistema Deshacer / Rehacer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- guión : Eliminar escenario compartido.
- guión : Mejora de la ventana de gestión de plantillas de escenarios. <br/><br/>
- Análisis / Equipamiento : Adición de un motor de búsqueda (pestaña Baterías, búsqueda de nombres y padres).
- Análisis / Equipamiento : Ahora se puede hacer clic en el área de calendario / días del equipo para acceder directamente al cambio de batería (s).
- Análisis / Equipamiento : Adición de un campo de búsqueda. <br/><br/>
- Centro de actualizaciones : Advertencia en la pestaña &#39;Núcleo y complementos&#39; y / u &#39;Otros&#39; si hay una actualización disponible. Cambie a &#39;Otros&#39; si es necesario.
- Centro de actualizaciones : diferenciación por versión (estable, beta, ...).
- Centro de actualizaciones : Además de una barra de progreso durante la actualización. <br/><br/>
- Resumen de domótica : El historial de eliminación ahora está disponible en una pestaña (Resumen - Historial).
- Resumen de domótica : Revisión completa, posibilidad de ordenar objetos, equipos, pedidos.
- Resumen de domótica : Adición de equipos e ID de pedidos, en pantalla y en búsqueda.
- Resumen de domótica : Exportación CSV de objeto primario, id, equipo y su id, comando.
- Resumen de domótica : Posibilidad de hacer visibles o no uno o más pedidos. <br/><br/>
- diseño : Posibilidad de especificar el orden (posición) de * Diseños * y * Diseños 3D * (Editar, Configurar diseño).
- diseño : Adición de un campo CSS personalizado en los elementos de * diseño*.
- diseño : Desplazamiento de las opciones de visualización en Diseño de la configuración avanzada, en la configuración de visualización de * Diseño*. Esto para simplificar la interfaz y permitir tener diferentes parámetros por * Diseño*.
- diseño : Mover y cambiar el tamaño de los componentes en * Diseño * tiene en cuenta su tamaño, con o sin magnetización. <br/><br/>
- Reducción general (estilos CSS / en línea, refactorización, etc.) y mejoras de rendimiento.
- Elimine Font Awesome 4 para mantener solo Font Awesome 5.
- Actualización de Libs : jquery 3.4.1, CodeMiror 5.46.0, clasificador de tabla 2.31.1.
- Numerosas correcciones de errores.
- Agregar un sistema de configuración masiva (utilizado en la página Equipo para configurar Alertas de comunicaciones en ellos)

>**IMPORTANTE**
>
>Si después de la actualización tiene un error en el tablero, intente reiniciar su caja para que tenga en cuenta las nuevas adiciones de componentes

>**IMPORTANTE**
>
>El complemento de widgets no es compatible con esta versión de Jeedom y ya no será compatible (porque las funciones se han asumido internamente en el núcleo). Más información [aquí] (https://www.jeedom.com/blog/4368-les-widgets-en-v4)

3.3.38
=====

- Se agregó compatibilidad global de Jeedom DNS con conexión a Internet 4G. (Importante : si está utilizando Jeedom DNS y tiene una conexión 4G, debe verificar en la configuración de Jeedom DNS la casilla correspondiente).
- Correcciones ortográficas.
- Arreglo de seguridad

3.3.37
=====

- Correcciones de errores.

3.3.36
=====

- Adición de redondeo en el número de días desde el último cambio de batería.
- Correcciones de errores

3.3.35
=====

- Correcciones de errores.
- Posibilidad de instalar complementos directamente desde el mercado en línea.

3.3.34
=====
- Se corrigió un error que podía evitar que la batería volviera a funcionar
- Corrección de un error en las etiquetas en las interacciones.
- El estado de &quot;tiempo de espera&quot; (no comunicación) del equipo ahora tiene prioridad sobre el estado de advertencia o peligro
- Corrección de errores en las copias de seguridad en la nube


3.3.33
=====

- Correcciones de errores

3.3.32
=====

- Correcciones de errores
- Soporte móvil para controles deslizantes en diseños
- INTELIGENTE : optimización de la gestión de swap

3.3.31
=====

- Correcciones de errores

3.3.30
=====

- Corrección de un error en la visualización de sesiones de usuario.
- Actualización de la documentación
- Eliminación de la actualización de gráficos en tiempo real, luego de numerosos errores reportados
- Corrección de un error que podría impedir la visualización de ciertos registros.
- Corrección de un error en el servicio de monitoreo.
- Corrección de un error en la página &quot;Análisis del equipo&quot;, la fecha de actualización de la batería ahora es correcta
- Mejora de la acción remove_inat en escenarios

3.3.29
=====

- Corrección de la desaparición de la fecha de la última verificación de actualizaciones
- Se corrigió un error que podía bloquear las copias de seguridad en la nube
- Corrección de un error en el cálculo del uso de las variables si es de forma variable (toto, mavaleur)

3.3.28
=====

- Se corrigió un error de rueda infinita en la página de actualizaciones
- Varias correcciones y optimizaciones.

3.3.27
=====

- Corrección de un error en la traducción de los días en francés.
- Estabilidad mejorada (reinicio automático del servicio MySql y watchdog para verificar la hora de inicio)
- Correcciones de errores
- Deshabilitar acciones en pedidos al editar diseños, vistas o paneles

3.3.26
=====

- Correcciones de errores
- Corrección de un error en el lanzamiento múltiple del escenario
- Corrección de un error en las alertas sobre el valor de los pedidos.

3.3.25
=====
- Corrección de errores
- Cambiar la línea de tiempo al modo de tabla (debido a errores en la biblioteca independiente de Jeedom)
- Adición de clases para soportes de color en el complemento de modo

3.3.24
=====
-   Corrección de un error en la pantalla del número de actualización.
-	Se eliminó la edición del código html de la configuración avanzada de comandos debido a demasiados errores
-	Correcciones de errores
-	Mejora de la ventana de selección de iconos.
-	Actualización automática de la fecha de cambio de batería si la batería es más del 90% y 10% más alta que el valor anterior
-	Adición de un botón en la administración para restablecer los derechos y lanzar una verificación de Jeedom (derecha, cron, base de datos ...)
-	Eliminación de opciones de visibilidad avanzadas para equipos en el tablero de instrumentos / vista / diseño / móvil. Ahora, si desea ver o no el equipo en el tablero de instrumentos / móvil, simplemente marque o no la casilla de visibilidad general. Para vistas y diseño, simplemente coloque o no el equipo en él

3.3.22
=====
- Correcciones de errores
- Reemplazo de pedidos mejorado (en vistas, plan y plan3d)
- Se corrigió un error que podía evitar abrir ciertos equipos de complemento (alarma o tipo virtual)

3.3.21
=====
- Se corrigió un error por el cual la visualización de la hora podía exceder las 24 h
- Corrección de un error en la actualización de los resúmenes de diseño.
- Corrección de un error en la gestión de los niveles de alertas en ciertos widgets durante la actualización del valor
- Se corrigió la visualización del equipo desactivado en algunos complementos
- Corrección de un error al indicar el cambio de batería en Jeedom
- Visualización mejorada de registros al actualizar Jeedom
- Corrección de errores al actualizar una variable (que no siempre iniciaba los escenarios o no activaba una actualización de los comandos en todos los casos)
- Se corrigió un error en las copias de seguridad de la nube, o la duplicidad no se instalaba correctamente
- Mejora de TTS interno en Jeedom
- Mejora del sistema de verificación de sintaxis cron


3.3.20
=====
- Corrección de un error en los escenarios o podrían permanecer bloqueados en &quot;en progreso&quot; mientras están desactivados
- Se solucionó un problema con el lanzamiento de un escenario no planificado
- Corrección de errores de zona horaria

3.3.19
=====
- Corrección de errores (especialmente durante la actualización)


3.3.18
=====
- Correcciones de errores

3.3.17
=====
- Corrección de un error en las copias de seguridad de samba

3.3.16
=====
- Posibilidad de eliminar una variable.
- Adición de una pantalla 3D (beta)
- Rediseño del sistema de respaldo en la nube (respaldo incremental y encriptado).
- Agregar un sistema integrado de toma de notas (en Análisis -&gt; Nota).
- Adición de la noción de etiqueta en el equipo (se puede encontrar en la configuración avanzada del equipo).
- Adición de un sistema de historial sobre la eliminación de pedidos, equipos, objetos, vista, diseño, diseño 3D, escenario y usuario..
- Adición de la acción jeedom_reboot para iniciar un reinicio de Jeedom.
- Agregar opción en la ventana de generación cron.
- Ahora se agrega un mensaje si se encuentra una expresión no válida al ejecutar un escenario.
- Agregar un comando en los escenarios : value (orden) permite tener el valor de una orden si no está dada automáticamente por jeedom (caso cuando se almacena el nombre de la orden en una variable).
- Adición de un botón para actualizar los mensajes del centro de mensajes..
- Agregue en la configuración de la acción sobre el valor de un comando un botón para buscar una acción interna (escenario, pausa ...).
- Adición de una acción &quot;Restablecer a cero del IS&quot; en los escenarios
- Posibilidad de agregar imágenes en segundo plano en las vistas
- Posibilidad de agregar imágenes de fondo en objetos
- La información de actualización disponible ahora está oculta para usuarios no administradores
- Soporte mejorado para () en el cálculo de expresiones
- Posibilidad de editar los escenarios en modo texto / json
- Además en la página de salud de una verificación de espacio libre para el tmp Jeedom
- Posibilidad de agregar opciones en informes
- Adición de un latido por complemento y reinicio automático de daemon en caso de problemas
- Adición de oyentes en la página del motor de tareas
- optimizaciones
- Posibilidad de consultar los registros en versión móvil (wepapp)
- Adición de una etiqueta de acción en los escenarios (ver documentación)
- Posibilidad de tener una vista de pantalla completa agregando &quot;&amp; fullscreen = 1&quot; en la url
- Adición de la última comunicación en los escenarios (para tener la última fecha de comunicación de un equipo)
- Actualización en tiempo real de gráficos (simple, no calculado o líneas de tiempo)
- Posibilidad de eliminar un elemento de la configuración de diseño.
- Posibilidad de tener un informe sobre el nivel de la batería (informe del equipo)
- Los widgets de escenario ahora se muestran por defecto en el tablero
- Cambie el tono de los widgets por horizontal 25 a 40, vertical 5 a 20 y margen 1 a 4 (puede restablecer los valores anteriores en la configuración de jeedom, pestaña widget)
- Posibilidad de poner un icono en los escenarios.
- Visualización de widgets móviles en una sola columna.
- Incorporación de la gestión de demonios en el motor de tareas.
- Adición de la función color_gradient en los escenarios.

3.2.16
=====
- Corrección de un error al instalar dependencias de ciertos complementos en Smart

3.2.15
=====
- Corrección de un error al guardar el equipo.

3.2.14
=====
- Preparación para evitar un error al cambiar a 3.3.X
- Corrección de un problema al solicitar soporte para complementos de terceros

3.2.12
=====
- Correcciones de errores
- optimizaciones

3.2.11
=====
- Correcciones de errores.

3.2.10
=====
- Correcciones de errores.
- Sincronización mejorada con el mercado.
- Mejora del proceso de actualización en particular en la copia de archivos que ahora comprueba el tamaño del archivo copiado.
- Corrección de errores en las funciones stateDuration, lastStateDuration y lastChangeStateDuration (gracias @kiboost).
- Optimización del cálculo del gráfico de enlaces y el uso de variables..
- Mejora de la ventana de detalles de la tarea cron que ahora muestra el escenario, así como la acción a realizar para las tareas doIn (gracias @kiboost).

3.2.9
=====
- Correcciones de errores
- Corrección de un error en los íconos del editor de archivos y en el probador de expresiones
- Corrección de errores en los oyentes.
- Adición de una alerta si un complemento bloquea crons
- Corrección de un error en el sistema de monitoreo en la nube si la versión del agente es inferior a 3.XX

3.2.8
=====
- Correcciones de errores
- Adición de una opción en la administración de Jeedom para especificar el rango de ip local (útil en instalaciones de tipo docker)
- Corrección de un error en el cálculo del uso de variables.
- Adición de un indicador en la página de salud que indica el número de procesos que se matan por falta de memoria (en general indica que la libertad está demasiado cargada)
- Editor de archivos mejorado

3.2.7
=====
- Correcciones de errores
- Actualización de documentos
- Posibilidad de usar las etiquetas en las condiciones de los bloques &quot;A&quot; e &quot;IN&quot;
- Corrección de errores de categorías de mercado para widgets / scripts / escenarios...

3.2.6
=====
- Correcciones de errores
- Actualización de documentos
- Estandarización de los nombres de ciertos pedidos en los escenarios.
- Optimización del rendimiento

3.2.5
=====
- Correcciones de errores
- Reactivación de interacciones (inactivo debido a la actualización)

3.2.4
=====
- Correcciones de errores
- Corrección de un error en cierto modal en español
- Corrección de un error de cálculo en time_diff
- Preparación para el futuro sistema de alerta.

3.2.3
=====
- Corrección de errores en funciones mín. / Máx.....
- Exportación mejorada de gráficos y visualización en modo tabla

3.2.2
=====
- Eliminación del antiguo sistema de actualización de widgets (en desuso desde la versión 3.0). Atención, si su widget no utiliza el nuevo sistema, existe el riesgo de mal funcionamiento (duplicación del mismo en este caso). Ejemplo de widget [aquí] (https://github.com/jeedom/core/tree/beta/core/template/dashboard)
- Posibilidad de mostrar los gráficos en forma de tabla o exportarlos en csv o xls
- Los usuarios ahora pueden agregar su propia función php para escenarios. Ver documentación de escenarios para implementación
- JEED-417 : adición de una función time_diff en los escenarios
- Adición de un retraso configurable antes de la respuesta en las interacciones (permite esperar a que se produzca la retroalimentación de estado, por ejemplo)
- JEED-365 : Eliminación del &quot;comando de información del usuario&quot; para ser reemplazado por acciones en el mensaje. Le permite iniciar varios comandos diferentes, iniciar un escenario ... Atención, si tenía un &quot;comando de información del usuario&quot;, debe reconfigurarse.
- Agregue una opción para abrir fácilmente un acceso al soporte (en la página del usuario y al abrir un ticket)
- Corrección de un error de derechos después de la restauración de una copia de seguridad
- Actualizando traducciones
- Actualización de la biblioteca (jquery y highcharts)
- Posibilidad de prohibir un pedido en interacciones automáticas
- Interacciones automáticas mejoradas
- Corrección de errores en el manejo de sinónimos de interacciones
- Adición de un campo de búsqueda de usuario para conexiones LDAP / AD (hace que Jeedom AD sea compatible)
- Correcciones ortográficas (gracias a dab0u por su enorme trabajo)
- JEED-290 : Ya no podemos conectarnos con los identificadores predeterminados (admin / admin) de forma remota, solo la red local está autorizada
- JEED-186 : Ahora podemos elegir el color de fondo en los diseños
- Para el bloque A, posibilidad de poner una hora entre las 12:01 a.m. y las 12:59 a.m. simplemente poniendo los minutos (ex 30 para las 12:30 a.m.)
- Agregar sesiones activas y dispositivos registrados a la página de perfil de usuario y a la página de administración de usuarios
- JEED-284 : la conexión permanente ahora depende de un usuario único y una clave periférica (y ya no ese usuario)
- JEED-283 : ajout d'un mode *rescue* à jeedom en rajoutant &rescue=1 dans l'url
- JEED-8 : ajout du nom du scénario sur le titre de la page lors de l'édition
- Optimisation des modifications d'organisation (taille des widgets, position des équipements, position des commandes) sur le dashboard et les vue. Attention maintenant les modifications ne sont sauvegardées que lorsque l'on quitte le mode édition.
- JEED-18 : Ajout des logs lors de l'ouverture d'un ticket au support
- JEED-181 : ajout d'une commande name dans les scénarios pour avoir le nom de la commande ou de l'équipement ou de l'objet
- JEED-15 : Ajout des batterie et alerte sur la webapp
- Correction du bugs de déplacement des objets du design sous Firefox
- JEED-19 : Lors d'une mise à jour il est maintenant possible de mettre à jour le script d'update avant la mise à jour
- JEED-125 : ajout d'un lien vers la documentation de réinitialisation de mot de passe
- JEED-2 : Amélioration de la gestion de l'heure lors d'un redémarrage
- JEED-77 : Ajout de la gestion des variables dans l'API http
- JEED-78 : ajout de la fonction tag pour les scénarios. Attention il faut dans les scénarios utilisant les tags passer de \#montag\# à tag(montag)
- JEED-124 : Corriger la gestion des timeouts des scénarios
- Correcciones de errores
- Possibilité de désactiver une interaction
- Ajout d'un éditeur de fichiers (réservé aux utilisateurs expérimentés)
- Ajout des génériques Types "Lumière Etat" (Binaire), "Lumière Température Couleur" (Info), "Lumière Température Couleur" (Action)
- Possibilité de rendre des mots obligatoires dans une interaction

3.1.7
=====
- Correcciones de errores (en particulier sur les historiques et fonctions statistiques)
- Amélioration du système de mises à jour avec une page de notes de version (que vous devez vérifier vous même avant chaque mise à
    jour !!!!)
- Correction d'un bug qui récupérait les logs lors des restaurations

3.1
===
- Correcciones de errores
- Optimisation globale de Jeedom (sur le chargement des classes de plugins, temps presque divisé par 3)
- Support de Debian 9
- Mode onepage (changement de page sans recharger toute la page, juste la partie qui change)
- Ajout d'une option pour masquer les objets sur le dashboard mais qui permet de toujours les avoir dans la liste
- Un double-clic sur un nœud sur le graphique de lien (sauf pour les variables) amène sur sa page de configuration
- Possibilité de mettre le texte à gauche/droit/au centre sur les designs pour les éléments de type texte/vue/design
- Ajout des résumés d'objets sur le dashboard (liste des objets à gauche)
- Ajout des interactions de type "previens-moi-si"
- Revue de la page d'accueil des scénarios
- Ajout d'un historique de commandes pour les commandes SQL ou système dans l'interface de Jeedom
- Possibilité d'avoir les graphiques d'historiques des commandes en webapp (par appui long sur la commande)
- Ajout de l'avancement de l'update de la webapp
- Reprise en cas d'erreur de mise à jour de la webapp
- Suppression des scénarios "simples" (redondant avec la configuration avancée des commandes)
- Ajout de hachure sur les graphs pour distinguer les jours
- Refonte de la page des interactions
- Refonte de la page profils
- Refonte de la page d'administration
- Ajout d'une "santé" sur les objets
- Corrección de errores sur le niveau de batterie des équipements
- Ajout de méthode dans le core pour la gestion des commandes mortes (doit être ensuite implémentée dans le plugin)
- Possibilité d'historiser des commandes de type texte
- Sur la page historique vous pouvez maintenant faire le graphique d'un calcul
- Ajout d'une gestion de formule de calcul pour les historiques
- Remise à jour de toute la documentation :
    - Toute les docs ont été revues
    - Suppression des images pour faciliter la mise à jour et le
        multilingue
- Plus de choix possibles sur les réglage des tailles de zone dans les vues
- Possibilité de choisir la couleur du texte du résumé d'objet
- Ajout d'une action remove\_inat dans les scénarios permettant d'annuler toutes les programmations des bloc DANS/A
- Possibilité dans les designs pour les widgets au survol de choisir la position du widget
- Ajout d'un paramètre reply\_cmd sur les interactions pour spécifier l'id de la commande à utiliser pour répondre
- Ajout d'une timeline sur la page historique (attention doit être activée sur chaque commande et/ou scénario que vous voulez voir apparaitre)
- Possibilité de vider les évènements de la timeline
- Possibilité de vider les IPs bannies
- Correction/amélioration de la gestion des comptes utilisateurs
    - Possibilité de supprimer le compte admin de base
    - Prévention du passage en normal du dernier administrateur
    - Ajout d'une sécurité pour éviter la suppression du compte avec lequel on est connecté
- Possibilité dans la configuration avancé des équipements de mettre la disposition des commandes dans le widgets en mode table en choisissant pour chaque commande la case ou la mettre
- Possibilité de réorganiser les widgets des équipements depuis le dashboard (en mode édition clic droit sur le widget)
- Changement du pas des widgets (de 40\*80 à 10\*10). Attention cela va impacter la disposition sur votre dashboard/vue/design
- Possibilité de donner une taille de 1 à 12 aux objets sur le dashboard
- Possibilité de lancer indépendamment les actions des scénarios (et plugin type mode/alarm si compatible) en parallèle des autres
- Possibilité d'ajouter un code d'accès à un design
- Ajout d'un watchdog indépendant de Jeedom pour vérifier le status de MySql et Apache

3.0.11
======
- Correcciones de errores sur les demandes "ask" en timeout

3.0.10
======
- Correcciones de errores sur l'interface de configuration des interactions

3.0
===
- Suppression du mode esclave
- Possibilité de déclencher un scénario sur un changement d'une variable
- Les mises à jour de variables déclenchent maintenant la mise à jour des commandes d'un équipement virtuel (il faut la dernière version du plugin)
- Possibilité d'avoir une icône sur les commandes de type info
- Possibilité sur les commandes d'afficher le nom et l'icône
- Ajout d'une action "alert" sur les scénarios : message en haut dans jeedom
- Ajout d'une action "popup" sur les scénarios : message à valider
- Les widgets des commandes peuvent maintenant avoir une méthode d'update ce qui évite un appel ajax à Jeedom
- Les widgets des scénarios sont maintenant mis à jour sans appel ajax pour avoir le widget
- Le résumé global et des pièces sont maintenant mis à jour sans appel ajax
- Un clic sur un élément d'un résumé domotique vous amène sur une vue détaillée de celui-ci
- Vous pouvez maintenant mettre dans les résumés des commandes de type texte
- Changement des bootstraps slider en slider (correction du bug du double événement des sliders)
- Sauvegarde automatique des vues lors du clic sur le bouton "voir le résultat"
- Possibilité d'avoir les docs en local
- Les développeurs tiers peuvent ajouter leur propre système de gestion de tickets
- Refonte de la configuration des droits utilisateurs (tout est sur la page de gestion des utilisateurs)
- Actualización de Libs : jquery (en 3.0) , jquery mobile, hightstock et table sorter, font-awesome
- Grosse amélioration des designs:
    - Toutes les actions sont maintenant accessibles à partir d'un
        clic droit
    - Possibilité d'ajouter une commande seule
    - Possibilité d'ajouter une image ou un flux vidéo
    - Possibilité d'ajouter des zones (emplacement cliquable) :
        - Zone de type macro : lance une série d'actions lors d'un clic dessus
        - Zone de type binaire : lance une série d'actions lors d'un clic dessus en fonction de l'état d'une commande
        - Zone de type widget : affiche un widget au clic ou au survol de la zone
    - Optimisation générale du code
    - Possibilité de faire apparaître une grille et de choisir sa taille (10x10,15x15 ou 30x30)
    - Possibilité d'activer une aimantation des widgets sur la grille
    - Possibilité d'activer une aimantation des widgets entre eux
    - Certains types de widgets peuvent maintenant être dupliqués
    - Possibilité de verrouiller un élément
- Les plugins peuvent maintenant utiliser une clef api qui leur est propre
- Ajout d'interactions automatiques, Jeedom va essayer de comprendre la phrase, d'exécuter l'action et de répondre
- Ajout de la gestion des démons en version mobile
- Ajout de la gestion des crons en version mobile
- Ajout de certaines informations de santé en version mobile
- Ajout sur la page batterie des modules en alerte
- Les objets sans widget sont automatiquement masqués sur le dashboard
- Ajout d'un bouton dans la configuration avancée d'un équipement/d'une commande pour voir les événements de celui-ci/celle-ci
- Les déclencheurs d'un scénario peuvent maintenant être des conditions
- Un double clic sur la ligne d'une commande (sur la page de configuration) ouvre maintenant la configuration avancée de celle-ci
- Possibilité d'interdire certaines valeurs pour une commande (dans la configuration avancée de celle-ci)
- Ajout des champs de configuration sur le retour d'état automatique (ex revenir à 0 au bout de 4min) dans la configuration avancée d'une commande
- Ajout d'une fonction valueDate dans les scénarios (voir documentation des scénarios)
- Possibilité dans les scénarios de modifier la valeur d'une commande avec l'action "event"
- Ajout d'un champ commentaire sur la configuration avancée d'un équipement
- Ajout d'un système d'alerte sur les commandes avec 2 niveaux : alerte et danger. La configuration se trouve dans la configuration avancée des commandes (de type info seulement bien sûr). Vous pouvez voir les modules en alerte sur la page Analyse → Equipements. Vous pouvez configurer les actions sur alerte sur la page de configuration générale de Jeedom
- Ajout d'une zone "tableau" sur les vues qui permet d'afficher une ou plusieurs colonnes par case. Les cases supportent aussi le code HTML
- Jeedom peut maintenant tourner sans les droits root (expérimental). Attention car sans les droits root vous devrez manuellement lancer les scripts pour les dépendances des plugins
- Optimisation du calcul des expressions (calcul des tags uniquement si présents dans l'expression)
- Ajout dans l'API de fonction pour avoir accès au résumé (global et d'objet)
- Possibilité de restreindre l'accès de chaque clef api en fonction de l'IP
- Possibilité sur l'historique de faire des regroupements par heure ou année
- Le timeout sur la commande wait peut maintenant être un calcul
- Correction d'un bug s'il y a des " dans les paramètres d'une action
- Passage au sha512 pour le hash des mots de passe (le sha1 étant compromis)
- Correction d'un bug dans la gestion du cache qui le faisait grossir indéfiniment
- Correction de l'accès à la doc des plugins tiers si ceux-ci n'ont pas de doc en local
- Les interactions peuvent prendre en compte la notion de contexte (en fonction de la demande précédente ainsi que celle d'avant)
- Possibilité de pondérer les mots en fonction de leur taille pour l'analyse de la compréhension
- Les plugins peuvent maintenant ajouter des interactions
- Les interactions peuvent maintenant renvoyer des fichiers en plus de la réponse
- Possibilité de voir sur la page de configuration des plugins les fonctionnalités de ceux-ci (interact, cron…​) et de les désactiver unitairement
- Les interactions automatiques peuvent renvoyer les valeurs des résumés
- Possibilité de définir des synonymes pour les objets, équipements, commandes et résumés qui seront utilisés dans les réponses contextuelles et résumés
- Jeedom sait gérer plusieurs interactions liées (contextuellement) en une. Elles doivent être séparées par un mot clef (par défaut et). Exemple : "Combien fait-il dans la chambre et dans le salon ?" ou "Allume la lumière de la cuisine et de la chambre."
- Le statut des scénarios sur la page d'édition est maintenant mis à jour dynamiquement
- Possibilité d'exporter une vue en PDF, PNG, SVG ou JPEG avec la commande "report" dans un scénario
- Possibilité d'exporter un design en PDF, PNG, SVG ou JPEG avec la commande "report" dans un scénario
- Possibilité d'exporter un panel d'un plugin en PDF, PNG, SVG ou JPEG avec la commande "report" dans un scénario
- Ajout d'une page de gestion de rapport (pour les re-télécharger ou les supprimer)
- Correction d'un bug sur la date de dernière remontée d'un événement pour certains plugins (alarme)
- Correction d'un bug d'affichage avec Chrome 55
- Optimisation du backup (sur un RPi2 le temps est divisé par 2)
- Optimisation de la restauration
- Optimisation du processus de mise à jour
- Uniformisation du tmp jeedom, maintenant tout est dans /tmp/jeedom
- Possibilité d'avoir un graph des différentes liaisons d'un scénario, équipement, objet, commande ou variable
- Possibilité de régler la profondeur des graphiques de lien en fonction de l'objet d'origine
- Possibilité d'avoir les logs des scénarios en temps réel (ralentit l'exécution des scénarios)
- Possibilité de passer des tags lors du lancement d'un scénario
- Optimisation du chargement des scénarios et pages utilisant des actions avec option (type configuration du plugin alarme ou mode)

2.4.6
=====
- Amélioration de la gestion de la répétition des valeurs des commandes

2.4.5
=====
- Correcciones de errores
- Optimisation de la vérification des mises à jour

2.4
---
- Optimisation générale
    - Regroupement de requêtes SQL
    - Suppression de requêtes inutiles
    - Passage en cache du pid, état et dernier lancement des scénarios
    - Passage en cache du pid, état et dernier lancement des crons
    - Dans 99% des cas plus de requête d'écriture sur la base en fonctionnement nominal (donc hors configuration de Jeedom, modifications, installation, mise à jour…​)
- Suppression du fail2ban (car facilement contournable en envoyant une fausse adresse ip), cela permet d'accélérer Jeedom
- Ajout dans les interactions d'une option sans catégorie pour que l'on puisse générer des interactions sur des équipements sans catégorie
- Ajout dans les scénarios d'un bouton de choix d'équipement sur les commandes de type slider
- Mise à jour de bootstrap en 2.3.7
- Ajout de la notion de résumé domotique (permet de connaitre d'un seul coup le nombre de lumières à ON, les porte ouvertes, les volets, les fenêtres, la puissance, les détections de mouvement…​). Tout cela se configure sur la page de gestion des objets
- Ajout de pre et post commande sur une commande. Permet de déclencher tout le temps une action avant ou après une autre action. Peut aussi permettre de synchroniser des équipements pour, par exemple, que 2 lumières s'allument toujours ensemble avec la même intensité.
- Optimisation des listenner
- Ajout de modal pour afficher les informations brutes (attribut de l'objet en base) d'un équipement ou d'une commande
- Possibilité de copier l'historique d'une commande sur une autre commande
- Possibilité de remplacer une commande par une autre dans tout Jeedom (même si la commande à remplacer n'existe plus)

2.3
---
- Correction des filtres sur le market
- Correction des checkbox sur la page d'édition des vues (sur une zone graphique)
- Correction des checkbox historiser, visible et inverser dans le tableau des commandes
- Correction d'un soucis sur la traduction des javascripts
- Ajout d'une catégorie de plugin : objet communiquant
- Ajout de GENERIC\_TYPE
- Suppression des filtres nouveau et top sur le parcours des plugins du market
- Renommage de la catégorie par défaut sur le parcours des plugins du market en "Top et nouveauté"
- Correction des filtres gratuit et payant sur le parcours des plugins du market
- Correction d'un bug qui pouvait amener à une duplication des courbes sur la page d'historique
- Correction d'un bug sur la valeur de timeout des scénarios
- correction d'un bug sur l'affichage des widgets dans les vues qui prenait la version dashboard
- Correction d'un bug sur les designs qui pouvait utiliser la configuration des widgets du dashboard au lieu des designs
- Correcciones de errores de la sauvegarde/restauration si le nom du jeedom contient des caractères spéciaux
- Optimisation de l'organisation de la liste des generic type
- Amélioration de l'affichage de la configuration avancée des équipements
- Correction de l'interface d'accès au backup depuis
- Sauvegarde de la configuration lors du test du market
- Préparation à la suppression des bootstrapswtich dans les plugins
- Correction d'un bug sur le type de widget demandé pour les designs (dashboard au lieu de dplan)
- correction de bug sur le gestionnaire d'événements
- passage en aléatoire du backup la nuit (entre 2h10 et 3h59) pour éviter les soucis de surcharge du market
- Correction du market de widget
- Correction d'un bug sur l'accès au market (timeout)
- Correction d'un bug sur l'ouverture des tickets
- Correction d'un bug de page blanche lors de la mise à jour si le /tmp est trop petit (attention la correction prend effet à l'update n+1)
- Ajout d'un tag *jeedom\_name* dans les scénarios (donne le nom du jeedom)
- Correcciones de errores
- Déplacement de tous les fichiers temporaire dans /tmp
- Amélioration de l'envoi des plugins (dos2unix automatique sur les fichiers \*.sh)
- Refonte de la page de log
- Ajout d'un thème darksobre pour mobile
- Possibilité pour les développeurs d'ajouter des options de configuration des widget sur les widgets spécifique (type sonos, koubachi et autre)
- Optimisation des logs (merci @kwizer15)
- Possibilité de choisir le format des logs
- Optimisation diverse du code (merci @kwizer15)
- Passage en module de la connexion avec le market (permettra d'avoir un jeedom sans aucun lien au market)
- Ajout d'un "repo" (module de connexion type la connexion avec le market) fichier (permet d'envoi un zip contenant le plugin)
- Ajout d'un "repo" github (permet d'utiliser github comme source de plugin, avec système de gestion de mise à jour)
- Ajout d'un "repo" URL (permet d'utiliser URL comme source de plugin)
- Ajout d'un "repo" Samba (utilisable pour pousser des backups sur un serveur samba et récupérer des plugins)
- Ajout d'un "repo" FTP (utilisable pour pousser des backups sur un serveur FTP et récupérer des plugins)
- Ajout pour certain "repo" de la possibilité de récupérer le core de jeedom
- Ajout de tests automatique du code (merci @kwizer15)
- Possibilité d'afficher/masquer les panels des plugins sur mobile et ou desktop (attention maintenant par défaut les panels sont masqués)
- Possibilité de désactiver les mises à jour d'un plugin (ainsi que la vérification)
- Possibilité de forcé la versification des mises à jour d'un plugin
- Légère refonte du centre de mise à jour
- Possibilité de désactiver la vérification automatique des mises à jour
- Correction d'un bug qui remettait toute les données à 0 suite à un redémarrage
- Possibilité de configurer le niveau de log d'un plugin directement sur la page de configuration de celui-ci
- Possibilité de consulter les logs d'un plugin directement sur la page de configuration de celui-ci
- Suppression du démarrage en debug des démons, maintenant le niveau de logs du démon est le même que celui du plugin
- Nettoyage de lib tierce
- Suppression de responsive voice (fonction dit dans les scénarios qui marchait de moins en moins bien)
- Correction de plusieurs faille de sécurité
- Ajout d'un mode synchrone sur les scénarios (anciennement mode rapide)
- Possibilité de rentrer manuellement la position des widgets en % sur les design
- Refonte de la page de configuration des plugins
- Possibilité de configurer la transparence des widgets
- Ajout de l'action jeedom\_poweroff dans les scénarios pour arrêter jeedom
- Retour de l'action scenario\_return pour faire un retour à une interaction (ou autre) à partir d'un scénario
- Passage en long polling pour la mise à jour de l'interface en temps réel
- Correction d'un bug lors de refresh multiple de widget
- Optimisation de la mise à jour des widgets commandes et équipements
- Ajout d'un tag *begin\_backup*, *end\_backup*, *begin\_update*, *end\_update*, *begin\_restore*, *end\_restore* dans les scénarios

2.2
---
- Correcciones de errores
- Simplification de l'accès aux configurations des plugins à partir de la page santé
- Ajout d'une icône indiquant si le démon est démarré en debug ou non
- Ajout d'une page de configuration globale des historiques (accessible à partir de la page historique)
- Correcciones de errores pour docker
- Possibilité d'autoriser un utilisateur à se connecter uniquement à partir d'un poste sur le réseau local
- Refonte de la configuration des widgets (attention il faudra sûrement reprendre la configuration de certains widgets)
- Renforcement de la gestion des erreurs sur les widgets
- Possibilité de réordonner les vues
- Refonte de la gestion des thèmes

2.1
---
- Refonte du système de cache de Jeedom (utilisation de doctrine cache). Cela permet par exemple de connecter Jeedom à un serveur redis ou memcached. Par défaut Jeedom utilise un système de fichiers (et non plus la BDD MySQL ce qui permet de la décharger un peu), celui-ci se trouve dans /tmp il est donc conseillé si vous avez plus de 512 Mo de RAM de monter le /tmp en tmpfs (en RAM pour plus de rapidité et une diminution de l'usure de la carte SD, je recommande une taille de 64mo). Attention lors du redémarrage de Jeedom le cache est vidé il faut donc attendre pour avoir la remontée de toutes les infos
- Refonte du système de log (utilisation de monolog) qui permet une
intégration à des systèmes de logs (type syslog(d))
- Optimisation du chargement du dashboard
- Correction de nombreux warning
- Possibilité lors d'un appel api à un scénario de passer des tags dans l'url
- Support d'apache
- Optimisation pour docker avec support officiel de docker
- Optimisation pour les synology
- Support + optimisation pour php7
- Refonte des menus Jeedom
- Suppression de toute la partie gestion réseau : wifi, ip fixe… (reviendra sûrement sous forme de plugin). ATTENTION ce n'est pas le mode maître/esclave de jeedom qui est supprimé
- Suppression de l'indication de batterie sur les widgets
- Ajout d'une page qui résume le statut de tous les équipements sur batterie
- Refonte du DNS Jeedom, utilisation d'openvpn (et donc du plugin openvpn)
- Mise à jour de toutes les libs
- interacción : ajout d'un système d'analyse syntaxique (permet de supprimer les interactions avec de grosses erreurs de syntaxe type « le chambre »)
- Suppression de la mise à jour de l'interface par nodejs (passage en pulling toutes les secondes sur la liste des événements)
- Possibilité pour les applications tierces de demander par l'api les événements
- Refonte du système « d'action sur valeur » avec possibilité de faire plusieurs actions et aussi l'ajout de toutes les actions possibles dans les scénarios (attention il faudra peut-être toutes les reconfigurer suite à la mise à jour)
- Possibilité de désactiver un bloc dans un scénario
- Ajout pour les développeurs d'un système d'aide tooltips. Il faut sur un label mettre la classe « help » et mettre un attribut data-help avec le message d'aide souhaité. Cela permet à Jeedom d'ajouter automatiquement à la fin de votre label une icône « ? » et au survol d'afficher le texte d'aide
- Changement du processus de mise à jour du core, on ne demande plus l'archive au Market mais directement à Github maintenant
- Ajout d'un système centralisé d'installation des dépendances sur les plugins
- Refonte de la page de gestion des plugins
- Ajout des adresses mac des différentes interfaces
- Ajout de la connexion en double authentification
- Suppression de la connexion par hash (pour des raisons de sécurité)
- Ajout d'un système d'administration OS
- Ajout de widgets standards Jeedom
- Ajout d'un système en beta pour trouver l'IP de Jeedom sur le réseau (il faut connecter Jeedom sur le réseau, puis aller sur le market et cliquer sur « Mes Jeedoms » dans votre profil)
- Ajout sur la page des scénarios d'un testeur d'expression
- Revue du système de partage de scénario

2.0
---
- Refonte du système de cache de Jeedom (utilisation de doctrine cache). Cela permet par exemple de connecter Jeedom à un serveur redis ou memcached. Par défaut Jeedom utilise un système de fichiers (et non plus la BDD MySQL ce qui permet de la décharger un peu), celui-ci se trouve dans /tmp il est donc conseillé si vous avez plus de 512 Mo de RAM de monter le /tmp en tmpfs (en RAM pour plus de rapidité et une diminution de l'usure de la carte SD, je recommande une taille de 64mo). Attention lors du redémarrage de Jeedom le cache est vidé il faut donc attendre pour avoir la remontée de toutes les infos
- Refonte du système de log (utilisation de monolog) qui permet une
intégration à des systèmes de logs (type syslog(d))
- Optimisation du chargement du dashboard
- Correction de nombreux warning
- Possibilité lors d'un appel api à un scénario de passer des tags dans l'url
- Support d'apache
- Optimisation pour docker avec support officiel de docker
- Optimisation pour les synology
- Support + optimisation pour php7
- Refonte des menus Jeedom
- Suppression de toute la partie gestion réseau : wifi, ip fixe… (reviendra sûrement sous forme de plugin). ATTENTION ce n'est pas le mode maître/esclave de jeedom qui est supprimé
- Suppression de l'indication de batterie sur les widgets
- Ajout d'une page qui résume le statut de tous les équipements sur batterie
- Refonte du DNS Jeedom, utilisation d'openvpn (et donc du plugin openvpn)
- Mise à jour de toutes les libs
- interacción : ajout d'un système d'analyse syntaxique (permet de supprimer les interactions avec de grosses erreurs de syntaxe type « le chambre »)
- Suppression de la mise à jour de l'interface par nodejs (passage en pulling toutes les secondes sur la liste des événements)
- Possibilité pour les applications tierces de demander par l'api les événements
- Refonte du système « d'action sur valeur » avec possibilité de faire plusieurs actions et aussi l'ajout de toutes les actions possibles dans les scénarios (attention il faudra peut-être toutes les reconfigurer suite à la mise à jour)
- Possibilité de désactiver un bloc dans un scénario
- Ajout pour les développeurs d'un système d'aide tooltips. Il faut sur un label mettre la classe « help » et mettre un attribut data-help avec le message d'aide souhaité. Cela permet à Jeedom d'ajouter automatiquement à la fin de votre label une icône « ? » et au survol d'afficher le texte d'aide
- Changement du processus de mise à jour du core, on ne demande plus l'archive au Market mais directement à Github maintenant
- Ajout d'un système centralisé d'installation des dépendances sur les plugins
- Refonte de la page de gestion des plugins
- Ajout des adresses mac des différentes interfaces
- Ajout de la connexion en double authentification
- Suppression de la connexion par hash (pour des raisons de sécurité)
- Ajout d'un système d'administration OS
- Ajout de widgets standards Jeedom
- Ajout d'un système en beta pour trouver l'IP de Jeedom sur le réseau (il faut connecter Jeedom sur le réseau, puis aller sur le market et cliquer sur « Mes Jeedoms » dans votre profil)
- Ajout sur la page des scénarios d'un testeur d'expression
- Revue du système de partage de scénario
