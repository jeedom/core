# Widgets

Un widget es la representación gráfica de un pedido. Cada widget es específico del tipo y subtipo del comando al que se debe aplicar, así como de la versión desde la que se accede a Jeedom *(escritorio o móvil)*.

## Widgets predeterminados

Antes de echar un vistazo a la personalización de widgets, descubramos las posibilidades que ofrecen ciertos widgets presentes por defecto en Core Jeedom.

### Équipements

Los dispositivos (o mosaicos) tienen ciertos parámetros de configuración accesibles a través de la configuración avanzada del dispositivo, pestaña "Pantalla" → "**Parámetros opcionales en el mosaico**".

##### Parámetros opcionales)

- **dashboard_class / mobile_class** : permite agregar una clase al equipo. Por ejemplo, `col2` para dispositivos en versión móvil permitirá duplicar el ancho del widget.

### HygroThermographe

Este widget es un poco especial porque es un widget de múltiples comandos, es decir, ensambla el valor de varios comandos. Aquí toma los comandos de temperatura y humedad. Para configurarlo, debes asignar el widget a los controles de temperatura y humedad de tu equipo.

![Widgy HygroThermographe](./images/widgets3.png)

##### Parámetros opcionales)

- **escala** *(échelle)* : Le permite cambiar el tamaño del widget, completando el parámetro **escala** a `0.5`, el widget será 2 veces más pequeño.

>**IMPORTANTE**      
>Es ABSOLUTAMENTE necesario que se indiquen los tipos genéricos; `Temperatura` en el control de temperatura y` Humedad` en el control de humedad (esto se configura en la configuración avanzada del control, pestaña de configuración).

>**NOTA**      
> Atención en un diseño, es importante no poner un comando solo con este widget, no funcionará ya que es un widget que utiliza el valor de varios comandos, es absolutamente necesario poner el widget completo

### Multiline

Este widget se utiliza para mostrar el contenido de una información / otro comando en varias líneas.

##### Parámetros opcionales)

- **Altura máxima** *(Altura máxima)* : Le permite establecer la altura máxima del widget (un ascensor *(scrollbar)* aparecerá en el lateral si el texto excede).

### Botón deslizante

Widget para acción / control del cursor con botón "**+**" y un botón "**-**" permitiendo actuar con precisión sobre un valor.

##### Parámetros opcionales)

- **paso** *(pas)* : Le permite establecer el paso de cambio de valor *(0.5 por defecto)*.

### Rain

Widget para mostrar niveles de agua.

![Widgy Rain](./images/widgets4.png)

##### Parámetros opcionales)

- **escala** *(échelle)* : Le permite cambiar el tamaño del widget, completando el parámetro **escala** a `0.5`, el widget será 2 veces más pequeño.
- **showRange** : Establecer en "1" para mostrar los valores mínimo y máximo del comando.
- **animar** : Desactiva la animación del widget con un valor de `0`.

### Activar / desactivar icono de alternancia

Respecto a los widgets para conmutadores *(encender / apagar, encender / apagar, abrir / cerrar, etc...)*, Puede considerarse más agradable visualmente mostrar solo un icono que refleje el estado del dispositivo que se va a controlar.

Esta posibilidad se puede utilizar tanto con widgets predeterminados como con widgets personalizados.

Para hacerlo, es necesario tener en cuenta 2 requisitos previos :

- Los **2 comandos de acción / falla** debe estar vinculado a un pedido **info / binario** que almacenará el estado actual del dispositivo.

>**Ejemplo**      
>![Widget de ToggleLink](./images/widgets5.png)

>**Consejo**     
>Desmarcar *"Afficher"* del comando info / binario que no será necesario mostrar.

- Para que Jeedom Core pueda identificar qué comando corresponde a qué acción, es fundamental respetar los siguientes nombres para **2 comandos de acción / falla** :
`` ''
    'on':'on',
    'off':'off',
    'monter':'on',
    'descendre':'off',
    'ouvrir':'on',
    'ouvrirStop':'on',
    'ouvert':'on',
    'fermer':'off',
    'activer':'on',
    'desactiver':'off',
    'desactivar':'off',
    'lock':'on',
    'unlock':'off',
    'marche':'on',
    'arret':'off',
    'detener':'off',
    'stop':'off',
    'go':'on'
`` ''

>**Truco**      
>Siempre que el nombre estandarizado siga siendo legible, es posible adaptar el nombre, por ejemplo *open_volet* o *shutter_close*, *paso 2* y *stop_2*, etc.

## Widgets personalizados

La página de Widgets, accesible desde el menú **Herramientas → Widgets**, le permite agregar widgets personalizados además de los disponibles por defecto en Jeedom.

Hay dos tipos de widgets personalizados :

- Widgets *Núcleo* basado en plantillas. Estos widgets son administrados por Jeedom Core y, por lo tanto, monitoreados por el equipo de desarrollo. Su compatibilidad está asegurada con futuras evoluciones de Jeedom.
- Widgets *Tercero* basado en el código de usuario. A diferencia de los widgets Core, el equipo de desarrollo de Jeedom no tiene control sobre el código insertado en estos widgets, su compatibilidad con desarrollos futuros no está garantizada. Por lo tanto, el usuario debe mantener estos widgets.

### Gestion

![Widgets](./images/widgets.png)

Tienes cuatro opciones :
- **Añadir** : Le permite agregar un widget *Núcleo*.
- **Importar** : Le permite importar un widget como un archivo json previamente exportado.
- **Codificado** : Accede a la página de edición del widget *Tercero*.
- **Reemplazo** : Abre una ventana que le permite reemplazar un widget por otro en todos los dispositivos que lo usan.

### Mis widgets

En esta parte encontrarás todos los widgets que has creado clasificados por tipo.

![Mes Widgets](./images/widgets1.png)

> **Truco**      
> Puede abrir un widget haciendo :
> - `Click` en uno de ellos.
> - `Ctrl + Click` o` Click + Center` para abrirlo en una nueva pestaña del navegador.

El motor de búsqueda te permite filtrar la visualización de widgets según diferentes criterios (nombre, tipo, subtipo, etc...). La tecla `Esc` cancela la búsqueda.

![Recherche Widgets](./images/widgets2.png)

A la derecha del campo de búsqueda, tres botones que se pueden encontrar en varios lugares de Jeedom:

- **La Cruz** para cancelar la búsqueda.
- **El archivo abierto** para desplegar todos los paneles y mostrar widgets.
- **El archivo cerrado** colapsar todos los paneles y ocultar los widgets.

Una vez en la página de configuración de un widget, se puede acceder a un menú contextual haciendo `` clic derecho '' en las pestañas del widget. También puede usar un `Ctrl + Click` o` Click + Center` para abrir directamente otro widget en una nueva pestaña del navegador.

### Crear un widget

Una vez en la página **Herramientas → Widgets** tienes que hacer clic en el botón "**Añadir**" y dale un nombre a tu nuevo widget.

Luego :
- Tú eliges si se aplica a una orden de tipo **Acción** o **Información**.
- Dependiendo de la elección anterior, deberá **elige el subtipo** De la orden.
- Finalmente **la plantilla** entre los que estarán disponibles según las opciones anteriores.
- Una vez que se ha elegido la plantilla, Jeedom muestra las opciones de configuración para ella a continuación.

### Las plantillas

#### Definición de una plantilla

En pocas palabras, es código (HTML / JS), integrado en el Core, algunas partes del cual son configurables por el usuario a través de la interfaz gráfica del menú **Widgets**. A partir de la misma base de datos y teniendo en cuenta los elementos que ingresarás en la plantilla, el Core generará widgets únicos correspondientes a la pantalla que deseas obtener.

Dependiendo del tipo de widget, generalmente puede personalizar los íconos, poner las imágenes de su elección y / o incrustar código HTML.

Hay dos tipos de plantillas :

- Los "**simple**" : como un icono / imagen para el "**Nosotros**" y un icono / imagen para el "**Apagado**".
- Los "**multiestados**" : Esto permite definir, por ejemplo, una imagen si el comando tiene el valor "**XX**" y otro tan grande que "**YY**" o si menos de "**ZZ**". También funciona para valores de texto, una imagen si el valor es "**foo**", otro si "**plaf**" y así enseguida...

#### Remplacement

Esto se llama una plantilla simple, aquí solo tiene que decir que el "**Nosotros**" coincide con ese icono / imagen *(usando el botón elegir)*, la "**Apagado**" a otro icono / imagen, etc...      

La caja **Widget de tiempo**, si está disponible, muestra la duración desde el último cambio de estado en el widget.

Para las plantillas que usan imágenes, puede configurar el ancho del widget en píxeles según el soporte (**Ancho de escritorio** Y **Ancho movible**). También se pueden seleccionar diferentes imágenes de acuerdo con el tema activo de Jeedom *(claro u oscuro)*.

>**Truco**     
>Para usuarios avanzados, es posible colocar etiquetas en los valores de reemplazo y especificar su valor en la configuración avanzada del comando.    
>Si, por ejemplo, en **Ancho de escritorio** pones como valor '#largeur_desktop#'' (**tenga cuidado de poner el** ''#'' **autour**) puis dans la configuratinosotros avancée d'une commande, ongly affichage → "**Paramètres optionnels widget**" vous ajoutez la paramètre ''largeur_desktop'' (**sans les** ''#`) y darle el valor "**90**", este widget personalizado en este comando tendrá 90 píxeles de ancho. Esto le permite adaptar el tamaño del widget a cada pedido sin tener que hacer un widget específico cada vez.

#### Test

Esto se llama plantillas multiestado *(varios estados)*. En lugar de poner una imagen para el "**Nosotros** y / o para el "**Apagado** como en el caso anterior, se le asignará un icono según la validación de una condición *(test)*. Si esto es cierto, el widget mostrará el icono / imagen en cuestión.

Como antes, se pueden seleccionar diferentes imágenes según el tema activo en Jeedom y el cuadro **Widget de tiempo** muestra la duración desde el último cambio de estado.

Las pruebas están en forma : ''#value# == 1`, `#value#`será reemplazado automáticamente por el valor actual del comando. También puedes hacer por ejemplo :

- ''#value# > 1`
- ''#value# >= 1 && #value# <= 5''
- ''#value# == 'toto'''

>**NOTA**     
>Es imprescindible mostrar los apóstrofos (**'**) alrededor del texto para comparar si el valor es texto *(info / otro)*.

>**NOTA**     
>Para usuarios avanzados, también es posible utilizar funciones javascript como `#value#.match ("^ plop") `, aquí probamos si el texto comienza con` plop`.

>**NOTA**     
>Es posible mostrar el valor del comando en el widget especificando `#value#`en el código HTML de la prueba. Para mostrar la unidad, agregue `#unite#''.

## Widget de código

### Etiquetas

En el modo de código tiene acceso a diferentes etiquetas para pedidos, aquí hay una lista (no necesariamente exhaustiva)) :

- **#name#** : nombre del comando
- **#valueName#** : nombre del valor del pedido, y = #name# cuando es un comando de tipo de información
- **#minValue#** : valor mínimo que puede tomar el comando (si el comando es de tipo deslizador)
- **#maxValue#** : valor máximo que puede tomar el comando (si el comando es de tipo deslizador)
- **#hide_name#** : vacío u oculto si el usuario solicitó ocultar el nombre del widget, ponerlo directamente en una etiqueta de clase
- **#id#** : ID de pedido
- **#state#** : valor del comando, vacío para un comando de tipo de acción si no está vinculado a un comando de estado
- **#uid#** : Identificador único para esta generación del widget (si hay varias veces el mismo comando, caso de diseños:  solo este identificador es realmente único)
- **#valueDate#** : fecha del valor del pedido
- **#collectDate#** : fecha de recogida del pedido
- **#alertLevel#** : nivel de alerta (ver [aquí](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) para la lista)
- **#hide_history#** : si el historial (máximo, mínimo, promedio, tendencia) debe estar oculto o no. En cuanto a la #hide_name# está vacío u oculto y, por lo tanto, se puede usar directamente en una clase. IMPORTANTE si esta etiqueta no se encuentra en su widget, entonces las etiquetas #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# y #tendance# no será reemplazado por Jeedom.
- **#minHistoryValue#** : valor mínimo durante el período (período definido en la configuración de Jeedom por el usuario)
- **#averageHistoryValue#** : valor promedio durante el período (período definido en la configuración de Jeedom por el usuario)
- **#maxHistoryValue#** : valor máximo durante el período (período definido en la configuración de Jeedom por el usuario)
- **#tendance#** : tendencia durante el período (período definido en la configuración de Jeedom por el usuario). Atención, la tendencia es directamente una clase de ícono : fas fa-flecha hacia arriba, fas fa-flecha hacia abajo o fas fa-minus

### Actualizar valores

Cuando un nuevo valor, Jeedom buscará en la página si el comando está allí y en Jeedom.cmd.actualizar si hay una función de actualización. En caso afirmativo, lo llama con un solo argumento que es un objeto en la forma :

`` ''
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
`` ''

Aquí hay un ejemplo simple de código JavaScript para poner en su widget :

`` ''
<script>
    Jeedom.cmd.update ['#id#'] = función (_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Fecha de recogida : '+ _options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
</script>
`` ''

Aquí hay dos cosas importantes :

`` ''
Jeedom.cmd.update ['#id#'] = función (_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Fecha de recogida : '+ _options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
`` ''
La función se llama durante una actualización del widget. Luego actualiza el código html del widget_template.

`` ''
Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
`` ''
 La llamada a esta función para la inicialización del widget.

### Exemples

 Encontraras [aquí](https://github.com/Jeedom/core/tree/V4-stable/core/template) ejemplos de widgets (en el tablero y carpetas móviles)
