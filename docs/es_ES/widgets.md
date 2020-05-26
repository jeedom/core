# Widgets
**Herramientas → Widgets**

La página de widgets te permite crear widgets personalizados para tu Jeedom.

Hay dos tipos de widgets personalizados :

- Widgets basados en una plantilla (administrado por Jeedom Core).
- Widgets basados en código de usuario.

> **Nota**
>
> Si los widgets basados en plantillas están integrados en el Core y, por lo tanto, son monitoreados por el equipo de desarrollo, este último no tiene forma de garantizar la compatibilidad de los widgets basados en el código de usuario de acuerdo con los desarrollos de Jeedom.

## Gestion

Tienes cuatro opciones :
- **Añadir** : Le permite crear un nuevo widget.
- **Importación** : Le permite importar un widget como un archivo json previamente exportado.
- **CODIGO** : Abre un editor de archivos para editar widgets de código.
- **Reemplazo** : Abre una ventana que le permite reemplazar un widget por otro en todos los dispositivos que lo usan.

## Mis widgets

Una vez que haya creado un widget, aparecerá en esta parte.

> **Punta**
>
> Puede abrir un widget haciendo :
> - Haga clic en uno de ellos.
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Tiene un motor de búsqueda para filtrar la visualización de widgets. La tecla Escape cancela la búsqueda.
A la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:

- La cruz para cancelar la búsqueda.
- La carpeta abierta para desplegar todos los paneles y mostrar todos los widgets.
- La carpeta cerrada para doblar todos los paneles.

Una vez en la configuración de un widget, tiene un menú contextual con el clic derecho en las pestañas del widget. También puede usar Ctrl Click o Clic Center para abrir directamente otro widget en una nueva pestaña del navegador.


## Principe

Pero que es una plantilla ?
En pocas palabras, es un código (aquí html / js) integrado en el Core, algunas partes de las cuales son configurables por el usuario con la interfaz gráfica del Core.

Dependiendo del tipo de widget, generalmente puede personalizar iconos o poner imágenes de su elección.

## Las plantillas

Hay dos tipos de plantillas :

- La "**sencillo**" : Escriba un icono / imagen para el "encendido" y un icono / imagen para el "apagado""
- La "**multiestado**" : Esto le permite definir, por ejemplo, una imagen si el comando tiene el valor "XX" y otro si> a "YY", y nuevamente si <a "ZZ". O incluso una imagen si el valor es &quot;toto&quot;, otra si es &quot;plop&quot;, etc.

## Crear un widget

Una vez en la página Herramientas -&gt; Widget, haga clic en &quot;Agregar&quot; y asigne un nombre a su nuevo widget.

Entonces :
- Usted elige si se aplica a una acción o tipo de información.
- Dependiendo de su elección anterior, tendrá que elegir el subtipo del comando (binario, digital, otro...).
- Luego, finalmente, la plantilla en cuestión (planeamos poner ejemplos de representaciones para cada plantilla).
- Una vez que se ha elegido la plantilla, jeedom le ofrece las opciones para configurarla.

### Remplacement

Esto es lo que se llama un widget simple, aquí solo tiene que decir que el &quot;encendido&quot; corresponde a dicho icono / imagen (con el botón elegir), el &quot;apagado&quot; es ese, etc. Luego, dependiendo de la plantilla, se le puede ofrecer el ancho (ancho) y la altura (altura). Esto solo es válido para imágenes.

>**Nota**
>Lamentamos los nombres en inglés, esto es una restricción del sistema de plantillas. Esta elección garantiza una cierta velocidad y eficiencia, tanto para usted como para nosotros. No tuvimos otra opción

>**Consejos**
>Para los usuarios avanzados, en los valores de reemplazo es posible colocar etiquetas y especificar su valor en la configuración avanzada del comando, la visualización de pestañas y el widget "Parámetros opcionales". Por ejemplo si en ancho pones como valor #width# (tenga cuidado de poner el # autour) au lieu d'un chiffre, dans "Paramètres optionnels widget" vous pouvez ajouter width (sans les #) y dar valor. Esto le permite cambiar el tamaño de la imagen de acuerdo con el orden y, por lo tanto, le evita crear un widget diferente para cada tamaño de imagen que desee

### Test

Esto se llama la parte multiestatal, a menudo tiene, como para widgets simples, la opción de &quot;altura&quot; / &quot;ancho&quot; para las imágenes solo entonces debajo de la parte de prueba.

Es bastante simple. En lugar de poner una imagen para el &quot;encendido&quot; y / o el &quot;apagado&quot; como en el caso anterior, vaya antes de hacer una prueba para hacer. Si esto es cierto, el widget mostrará el icono / imagen en cuestión.

Las pruebas están en forma : #value# == 1, #value# será reemplazado automáticamente por el sistema por el valor actual de la orden. También puedes hacer por ejemplo :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Nota**
>Es importante tener en cuenta el &quot;alrededor del texto para comparar si el valor es un texto

>**Nota**
>Para usuarios avanzados, también es posible usar funciones de tipo javascript aquí #value#.match (&quot;^ plop&quot;), aquí probamos si el texto comienza con plop

>**Nota**
>Es posible mostrar el valor del comando en el widget colocando, por ejemplo, al lado del código HTML del icono #value#

## Descripción de widgets

Vamos a describir aquí algunos widgets que tienen un funcionamiento algo particular.

### Equipement

Los equipos tienen ciertos parámetros de configuración :

- dashboard_class / mobile_class : permite agregar una clase al equipo. Por ejemplo, col2 para equipos en versión móvil, que permite duplicar el ancho del widget

### Configuraciones frecuentes

- Widget de tiempo : muestra el tiempo desde el cual el sistema ha estado en el estado de visualización.
- Uno : icono para mostrar si el equipo está encendido / 1.
- Apagado : icono para mostrar si el equipo está apagado / 0.
- Luz encendida : icono para mostrar si el equipo está encendido / 1 y el tema es claro (si está vacío, entonces Jeedom toma la imagen oscura).
- Luz apagada : icono para mostrar si el equipo está apagado / 0 y el tema es claro (si está vacío, entonces Jeedom quita la imagen oscura).
- Oscuro en : icono para mostrar si el equipo está encendido / 1 y el tema está oscuro (si está vacío, entonces Jeedom enciende la luz img).
- Oscuro apagado : icono para mostrar si el equipo está apagado / 0 y el tema está oscuro (si está vacío, entonces Jeedom apaga la luz img)).
- Ancho de escritorio : ancho de la imagen en el escritorio en px (solo ponga el número, no el px). Importante solo se solicita el ancho, Jeedom calculará la altura para no distorsionar la imagen.
- Ancho movible : ancho de la imagen en el móvil en px (solo ponga el número, no el px). Importante solo se solicita el ancho, Jeedom calculará la altura para no distorsionar la imagen.

### HygroThermographe

Este widget es un poco especial porque es un widget de comandos múltiples, es decir que reúne en su pantalla el valor de varios comandos. Aquí toma los comandos de temperatura y humedad.

Para configurarlo es bastante simple, debe asignar el widget al control de temperatura de su equipo y al control de humedad.

>**IMPORTANTE**
>Es ABSOLUTAMENTE necesario que sus pedidos tengan la temperatura de tipo genérico en el control de temperatura y la humedad en el control de humedad (esto se configura en la configuración avanzada de la configuración de la pestaña de comandos).

##### Parámetros opcionales)

- escala : Le permite cambiar su tamaño, por ejemplo, estableciendo la escala en 0.5 será 2 veces más pequeño.

>**Nota**
> Atención en un diseño, es importante no poner un comando solo con este widget, no funcionará ya que es un widget que utiliza el valor de varios comandos, es absolutamente necesario poner el widget completo

### Multiline

##### Parámetros opcionales)

- maxHeight : Le permite definir su altura máxima (barra de desplazamiento en el lateral si el texto excede este valor).

### Botón deslizante

##### Parámetros opcionales)

- paso : Permite ajustar el paso de una acción en un botón (0.5 por defecto).

### Rain

##### Parámetros opcionales)

- escala : Le permite cambiar su tamaño, por ejemplo, estableciendo la escala en 0.5 será 2 veces más pequeño.
- mostrarel : Muestra los valores min / max del comando.


## Widget de código

### Etiquetas

En el modo de código tiene acceso a diferentes etiquetas para pedidos, aquí hay una lista (no necesariamente exhaustiva)) :

- #name# : nombre del comando
- #valueName# : nombre del valor del pedido, y = #name# cuando es un comando de tipo de información
- #minValue# : valor mínimo que puede tomar el comando (si el comando es de tipo deslizador)
- #maxValue# : valor máximo que puede tomar el comando (si el comando es de tipo deslizador)
- #hide_name# : vacío u oculto si el usuario solicitó ocultar el nombre del widget, ponerlo directamente en una etiqueta de clase
- #id# : ID de pedido
- #state# : valor del comando, vacío para un comando de tipo de acción si no está vinculado a un comando de estado
- #uid# : Identificador único para esta generación del widget (si hay varias veces el mismo comando, caso de diseños:  solo este identificador es realmente único)
- #valueDate# : fecha del valor del pedido
- #collectDate# : fecha de recogida del pedido
- #alertLevel# : nivel de alerta (ver [aquí](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) para la lista)
- #hide_history# : si el historial (máximo, mínimo, promedio, tendencia) debe estar oculto o no. En cuanto a la #hide_name# está vacío u oculto y, por lo tanto, se puede usar directamente en una clase. IMPORTANTE si esta etiqueta no se encuentra en su widget, entonces las etiquetas #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# y #tendance# no será reemplazado por Jeedom.
- #minHistoryValue# : valor mínimo durante el período (período definido en la configuración de Jeedom por el usuario)
- #averageHistoryValue# : valor promedio durante el período (período definido en la configuración de Jeedom por el usuario)
- #maxHistoryValue# : valor máximo durante el período (período definido en la configuración de Jeedom por el usuario)
- #tendance# : tendencia durante el período (período definido en la configuración de Jeedom por el usuario). Atención, la tendencia es directamente una clase de ícono : fas fa-flecha hacia arriba, fas fa-flecha hacia abajo o fas fa-minus

### Actualizar valores

Cuando un nuevo valor Jeedom buscará en la página html, si el comando está allí y en Jeedom.cmd.actualizar si hay una función de actualización. En caso afirmativo, lo llama con un solo argumento que es un objeto en la forma :

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
La función llamada al actualizar el widget. Luego actualiza el código html del widget_template.

`` ''
Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
`` ''
 La llamada a esta función para la inicialización del widget.

 Encontraras [aquí](https://github.com/Jeedom/core/tree/V4-stable/core/template) ejemplos de widgets (en el tablero y carpetas móviles)
