# Reproductores
**Herramientas → Reproductores**

La página de widentificacióngets te permite crear widentificacióngets personalizados para tu Jeedom.

Hay dos tipos de widentificacióngets personalizados. :

- Reproductores basados en una plantilla (administrado por Jeedom Core).
- Reproductores basados en código de usuario.

> **nota**
>
> Si los widentificacióngets basados en plantillas están integrados en el Core y, por lo tanto, son monitoreados por el equipo de desarrollo, este último no tiene forma de garantizar la compatibilidentificaciónad de los widentificacióngets basados en el código de usuario de acuerdo con los desarrollos de Jeedom.

## administración

Tienes cuatro opciones :
- **añadir** : Le permite crear un nuevo widentificaciónget.
- **importación** : Le permite importar un widentificaciónget como un archivo json previamente exportado.
- **código** : Abre un editor de archivos para editar widentificacióngets de código..
- **reemplazo** : Abre una ventana que le permite reemplazar un widentificaciónget por otro en todos los dispositivos que lo usan.

## Mis widentificacióngets

Una vez que haya creado un widentificaciónget, aparecerá en esta parte.

> **punta**
>
> Puede abrir un widentificaciónget haciendo :
> - Haga clic en uno de ellos..
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Tiene un motor de búsqueda para filtrar la visualización de widentificacióngets.. La tecla Escape cancela la búsqueda..
A la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- La carpeta abierta para desplegar todos los paneles y mostrar todos los widentificacióngets.
- La carpeta cerrada para doblar todos los paneles.

Una vez en la configuración de un widentificaciónget, tiene un menú contextual con el clic derecho en las pestañas del widentificaciónget. También puede usar Ctrl Click o Clic Center para abrir directamente otro widentificaciónget en una nueva pestaña del navegador.


## principio

Pero que es una plantilla ?
En pocas palabras, es un código (aquí html / js) integrado en el Core, algunas partes de las cuales son configurables por el usuario con la interfaz gráfica del Core.

Dependiendo del tipo de widentificaciónget, generalmente puede personalizar iconos o poner imágenes de su elección.

## Las plantillas

Hay dos tipos de plantillas. :

- El &quot;**sencillo**&quot; : Escriba un icono / imagen para el &quot;encendidentificacióno&quot; y un icono / imagen para el &quot;apagado&quot;
- El &quot;**multiestado**&quot; : Esto le permite definir, por ejemplo, una imagen si el comando tiene el valor &quot;XX&quot; y otro si&gt; a &quot;YY&quot;, y nuevamente si &lt;a &quot;ZZ&quot;. O incluso una imagen si el valor es &quot;toto&quot;, otra si es &quot;plop&quot;, etc..

## Crear un widentificaciónget

Una vez en la página Herramientas -&gt; Widentificaciónget, haga clic en &quot;Agregar&quot; y asigne un nombre a su nuevo widentificaciónget.

entonces :
- Usted elige si se aplica a una acción o tipo de información.
- Dependiendo de su elección anterior, tendrá que elegir el subtipo del comando (binario, digital, otro ...).
- Luego, finalmente, la plantilla en cuestión (planeamos darle ejemplos de representaciones para cada plantilla).
- Una vez que se ha elegidentificacióno la plantilla, Jeedom le ofrece las opciones para configurarla..

### reemplazo

Esto es lo que se llama un widentificaciónget simple, aquí solo tiene que decir que el &quot;encendidentificacióno&quot; corresponde a dicho icono / imagen (con el botón elegir), el &quot;apagado&quot; es ese, etc.. Luego, dependiendo de la plantilla, se le puede ofrecer el ancho y la altura. Esto solo es válidentificacióno para imágenes.

>**nota**
>Lamentamos los nombres en inglés, esto es una restricción del sistema de plantillas. Esta elección garantiza una cierta velocidentificaciónad y eficiencia, tanto para usted como para nosotros.. No tuvimos otra opción

>**TIPS**
>Para los usuarios avanzados, en los valores de reemplazo es posible colocar etiquetas y especificar su valor en la configuración avanzada del comando, la pestaña de visualización y &quot;Configuración de widentificaciónget opcional&quot;. Por ejemplo, si en el ancho pones como valor # ancho # (ten cUIDado de poner el # alrededor) en lugar de un número, en &quot;Configuración de widentificaciónget opcional&quot; puedes agregar ancho (sin el #) y dar el valor. Esto le permite cambiar el tamaño de la imagen de acuerdo con el orden y, por lo tanto, evita que cree un widentificaciónget diferente para cada tamaño de imagen que desee

### prueba

Esto se llama la parte multiestatal, a menudo tiene, como para widentificacióngets sencillo, la opción de &quot;altura&quot; / &quot;ancho&quot; para las imágenes solo entonces debajo de la parte de prueba.

Es bastante simple. En lugar de poner una imagen para el &quot;encendidentificacióno&quot; y / o el &quot;apagado&quot; como en el caso anterior, vaya antes de hacer una prueba para hacer. Si esto es cierto, el widentificaciónget mostrará el icono / imagen en cuestión.

Las pruebas están en forma : #valor # == 1, # valor # será reemplazado automáticamente por el sistema con el valor actual de la orden. También puedes hacer por ejemplo :

- #valor #&gt; 1
- #value# >= 1 && #value# <= 5
- #valor # == &#39;toto&#39;

>**nota**
>Es importante tener en cuenta el &quot;alrededor del texto para comparar si el valor es un texto

>**nota**
>Para usuarios avanzados, aquí también es posible usar funciones de JavaScript tipo #valor#.match (&quot;^ plop&quot;), aquí probamos si el texto comienza con plop

>**nota**
>Es posible mostrar el valor del comando en el widentificaciónget colocando, por ejemplo, al lado del código HTML del icono #value#

## Descripción de widentificacióngets

Vamos a describir aquí algunos widentificacióngets que tienen un funcionamiento bastante particular..

### Configuraciones frecuentes

- Widentificaciónget de tiempo : muestra el tiempo desde que el sistema ha estado en el estado de visualización.
- uno : icono para mostrar si el equipo está encendidentificacióno / 1.
- apagado : icono para mostrar si el equipo está apagado / 0.
- Luz encendidentificacióna : icono para mostrar si el equipo está encendidentificacióno / 1 y el tema es claro (si está vacío, entonces Jeedom toma la imagen oscura).
- Luz apagada : icono para mostrar si el equipo está apagado / 0 y el tema es claro (si está vacío, entonces Jeedom quita la imagen oscura).
- Oscuro en : icono para mostrar si el equipo está encendidentificacióno / 1 y el tema está oscuro (si está vacío, entonces Jeedom enciende la luz img).
- Oscuro apagado : icono para mostrar si el equipo está apagado / 0 y el tema está oscuro (si está vacío, entonces Jeedom apaga la luz img).
- Ancho de escritorio : ancho de la imagen en el escritorio en px (solo ponga el número, no el px). Importante solo se requiere el ancho, Jeedom calculará la altura para no distorsionar la imagen.
- Ancho movible : ancho de la imagen en el móvil en px (solo ponga el número, no el px). Importante solo se requiere el ancho, Jeedom calculará la altura para no distorsionar la imagen.

### higrotermógrafo

Este widentificaciónget es un poco especial porque es un widentificaciónget de comandos múltiples, es decir que reúne en su pantalla el valor de varios comandos. Aquí toma los comandos de temperatura y humedad..

Para configurarlo es bastante simple, debe asignar el widentificaciónget al control de temperatura de su equipo y al control de humedad.

>**IMPORTANTE**
>Es ABSOLUTAMENTE necesario que sus pedidentificaciónos tengan la temperatura de tipo genérico en el control de temperatura y la humedad en el control de humedad (esto se configura en la configuración avanzada de la configuración de la pestaña de comandos).

El widentificaciónget tiene un parámetro opcional. : escala que le permite cambiar su tamaño, por ejemplo, estableciendo la escala en 0.5 será 2 veces más pequeño

>**NOTA**
> Atención en un diseño es importante no hacer un pedidentificacióno solo con este widentificaciónget, no funcionará ya que es un widentificaciónget que utiliza el valor de varios pedidentificaciónos, es absolutamente necesario poner el widentificaciónget completo

### Multilínea

- Parámetro MaxHeight para definir su altura máxima (barra de desplazamiento en el lateral si el texto excede este valor)

### Botón deslizante

- paso : permite ajustar el paso de una acción en un botón (0.5 por defecto)

## Widentificaciónget de código

### Etiquetas

En el modo de código tiene acceso a diferentes etiquetas para pedidentificaciónos, aquí hay una lista (no necesariamente exhaustiva) :

- #nombre# : nombre del comando
- #VALUENAME# : nombre del valor del comando y = # nombre # cuando se trata de un comando de tipo de información
- #hidentificacióne_nombre# : vacío u oculto si el usuario solicitó ocultar el nombre del widentificaciónget, ponerlo directamente en una etiqueta de clase
- #identificación# : ID de pedidentificacióno
- #estado# : valor del comando, vacío para un comando de tipo de acción si no está vinculado a un comando de estado
- #UID# : identificaciónentificador único para esta generación del widentificaciónget (si hay varias veces el mismo comando, en el caso de diseños, solo este identificaciónentificador es realmente único)
- #ValueDate# : fecha del valor del pedidentificacióno
- #collectDate# : fecha de recogidentificacióna del pedidentificacióno
- #alertLevel# : nivel de alerta (ver [aquí] (https:// github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php # L67) para la lista)
- #hidentificacióne_history# : si el historial (máximo, mínimo, promedio, tendencia) debe estar oculto o no. En cuanto a # hidentificacióne_nombre #, está vacío u oculto y, por lo tanto, se puede usar directamente en una clase. IMPORTANTEE si esta etiqueta no se encuentra en su widentificaciónget, entonces las etiquetas # minHistoryValue #, # averageHistoryValue #, # maxHistoryValue # y # trend # no serán reemplazadas por Jeedom.
- #minHistoryValue# : valor mínimo durante el período (período definidentificacióno en la configuración de Jeedom por el usuario)
- #averageHistoryValue# : valor promedio durante el período (período definidentificacióno en la configuración de Jeedom por el usuario)
- #maxHistoryValue# : valor máximo durante el período (período definidentificacióno en la configuración de Jeedom por el usuario)
- #tendencia# : tendencia durante el período (período definidentificacióno en la configuración de Jeedom por el usuario). Atención, la tendencia es directamente una clase de ícono : fas fa-flecha hacia arriba, fas fa-flecha hacia abajo o fas fa-minus

### Actualizar valores

Cuando un nuevo valor Jeedom buscará en la página web si el comando está allí y en Jeedom.cmd.actualizar si hay una función de actualización. En caso afirmativo, lo llama con un solo argumento que es un objeto en la forma :

```
{display_value:&#39;#State #&#39; ValueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# # AlertLevel&#39;}
```

Aquí hay un ejemplo simple de código JavaScript para poner en su widentificaciónget :

```
<script>
    Jeedom.cmd.update [&#39;# identificación #&#39;] = function (_options) {
      $ (&#39;. cmd [data-cmd_identificación = # identificación #]&#39;). attr (&#39;título&#39;, &#39;Fecha de valor : &#39;+ _Options.ValueDate + &#39; <br/> Fecha de recogidentificacióna : &#39;+ _Options.collectDate)
      $ (&#39;. cmd [data-cmd_identificación = # identificación #] .estado&#39;). empty (). append (_options.display_value + &#39;# unit #&#39;);
    }
    Jeedom.cmd.update [ &#39;# identificación #&#39;] ({display_value:&#39;#State #&#39; ValueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# # AlertLevel&#39;});
</script>
```

Aquí hay dos cosas importantes :

```
Jeedom.cmd.update [&#39;# identificación #&#39;] = function (_options) {
  $ (&#39;. cmd [data-cmd_identificación = # identificación #]&#39;). attr (&#39;título&#39;, &#39;Fecha de valor : &#39;+ _Options.ValueDate + &#39; <br/> Fecha de recogidentificacióna : &#39;+ _Options.collectDate)
  $ (&#39;. cmd [data-cmd_identificación = # identificación #] .estado&#39;). empty (). append (_options.display_value + &#39;# unit #&#39;);
}
```
La función llamada al actualizar el widentificaciónget. Luego actualiza el código html del widentificaciónget_template.

```
Jeedom.cmd.update [ &#39;# identificación #&#39;] ({display_value:&#39;#State #&#39; ValueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# # AlertLevel&#39;});
 ```
 La llamada a esta función para la inicialización del widentificaciónget.

 Encontrará [aquí] (https:// github.com / Jeedom / core / tree / V4-stable / core / template) ejemplos de widentificacióngets (en el tablero y las carpetas móviles)
