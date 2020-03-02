La página de widentificacióngets le permite crear widentificacióngets personalizados y únicos para su Jeedom.

Hay 2 posibilidentificaciónades :

- O bien haciendo clic en el botón de código y escribiendo directamente su código html para su widentificaciónget (esto no es necesariamente lo que recomendamos porque durante las actualizaciones de jeedom su código puede volverse incompatible con jeedom)
- Ya sea haciendo un widentificaciónget basado en una plantilla que proporcionamos

# Pero que es una plantilla ?

Para hacerlo simple, es el código (aquí html) donde hemos predefinidentificacióno ciertas partes que podrá configurar como desee.

En el caso de los widentificacióngets, a menudo sugerimos personalizar los iconos o colocar las imágenes que desee..

# Las plantillas

Hay 2 tipos de plantillas. :

- El "simple" : escriba un icono / imagen para el "encendidentificacióno" y un icono / imagen para el "apagado""
- Los "estados múltiples" : esto le permite definir, por ejemplo, una imagen si el comando tiene el valor "XX" y otro si> a "YY" y nuevamente si <a "ZZ". O incluso una imagen si el valor es &quot;toto&quot;, otra si es &quot;plop&quot; y así sucesivamente.

# Como hacer ?

Una vez en la página Herramientas -&gt; Widentificaciónget, haga clic en &quot;Agregar&quot; y asigne un nombre a su nuevo widentificaciónget.

Entonces :
- Usted elige si se aplica a una acción o tipo de información
- Dependiendo de su elección anterior, tendrá que elegir el subtipo del comando (binario, digital, otro ...)
- Luego, finalmente, la plantilla en cuestión (planeamos darle ejemplos de representaciones para cada plantilla)
- Una vez que se ha elegidentificacióno la plantilla, jeedom le ofrece las opciones para configurarla.

## Reemplazo

Esto es lo que llamamos un widentificaciónget simple, aquí solo tiene que decir que el &quot;encendidentificacióno&quot; corresponde a dicho icono / imagen (con el botón elegir), el &quot;apagado&quot; es ese ec. Luego, dependiendo de la plantilla, también se le puede solicitar el ancho y la altura.. Esto solo es válidentificacióno para imágenes.

>**Nota**
>
>Lamentamos los nombres en inglés, esto es una restricción del sistema de plantillas. Esta elección garantiza una cierta velocidentificaciónad y eficiencia, tanto para usted como para nosotros.. No tuvimos otra opción

>**Consejos**
>
>Para los usuarios avanzados, en los valores de reemplazo es posible colocar etiquetas y especificar su valor en la configuración avanzada del comando, la pestaña de visualización y el widentificaciónget "Configuración opcional". Por ejemplo, si en el ancho pones como valor # ancho # (ten cUIDado de poner el # alrededor) en lugar de un número, en &quot;Configuración de widentificaciónget opcional&quot; puedes agregar ancho (sin el #) y dar el valor. Esto le permite cambiar el tamaño de la imagen de acuerdo con el orden y, por lo tanto, le evita crear un widentificaciónget diferente para cada tamaño de imagen que desee.

## Prueba

Esto se llama la parte multiestatal, a menudo tiene, como para widentificacióngets simples, la opción de &quot;altura&quot; / &quot;ancho&quot; para las imágenes solo entonces debajo de la parte de prueba.

Es bastante simple. En lugar de poner una imagen para el &quot;encendidentificacióno&quot; y / o el &quot;apagado&quot; como en el caso anterior, vaya antes de hacer una prueba para hacer. Si esto es cierto, el widentificaciónget mostrará el icono / imagen en cuestión.

Las pruebas están en forma : #valor # == 1, # valor # será reemplazado automáticamente por el sistema con el valor actual de la orden. También puedes hacer por ejemplo :

- #valor #&gt; 1
- #value# >= 1 && #value# <= 5
- #valor # == &#39;toto'

>**Nota**
>
>Es importante tener en cuenta el &quot;alrededor del texto para comparar si el valor es un texto

>**Nota**
>
>Para usuarios avanzados, aquí también es posible usar funciones de JavaScript tipo #valor#.match (&quot;^ plop&quot;), aquí probamos si el texto comienza con plop

>**Nota**
>
>Es posible mostrar el valor del comando en el widentificaciónget colocando, por ejemplo, al lado del código HTML del icono #value#

# Descripción de widentificacióngets

Vamos a describir aquí algunos widentificacióngets que tienen un funcionamiento bastante particular..

## Configuraciones frecuentes

- Widentificaciónget de tiempo : muestra el tiempo desde el cual el sistema ha estado en el estado de visualización.
- Uno : icono para mostrar si el equipo está encendidentificacióno / 1
- Apagado : icono para mostrar si el equipo está apagado / 0
- Luz encendidentificacióna : ícono para mostrar si el equipo está encendidentificacióno / 1 y el tema es claro (si está vacío, entonces la libertad toma la imagen oscura encendidentificacióna)
- Luz apagada : icono para mostrar si el equipo está apagado / 0 y el tema es claro (si está vacío, entonces la libertad quita la imagen oscura)
- Oscuro en : ícono para mostrar si el equipo está encendidentificacióno / 1 y el tema está oscuro (si está vacío, entonces jeedom toma la luz encendidentificacióna)
- Oscuro apagado : icono para mostrar si el equipo está apagado / 0 y el tema está oscuro (si está vacío, entonces jeedom apaga la luz de imagen)
- Ancho de escritorio : ancho de la imagen en el escritorio en px (solo ponga el número, no el px). Importante solo se requiere el ancho, jeedom calculará la altura para no distorsionar la imagen
- Ancho movible : ancho de la imagen en el móvil en px (solo ponga el número, no el px). Importante solo se requiere el ancho, jeedom calculará la altura para no distorsionar la imagen

## Higrotermógrafo

Este widentificaciónget es un poco especial porque es un widentificaciónget de comandos múltiples, es decir que reúne en su pantalla el valor de varios comandos. Aquí toma los comandos de temperatura y humedad..

Para configurarlo es bastante simple, debe asignar el widentificaciónget al control de temperatura de su equipo y al control de humedad.

>**IMPORTANTE**
>
>Es ABSOLUTAMENTE necesario que sus pedidentificaciónos tengan la temperatura de tipo genérico en el control de temperatura y humedad en el control de humedad (esto se configura en la configuración avanzada de la configuración de la pestaña de comandos).

El widentificaciónget tiene un parámetro opcional. : escala que le permite cambiar su tamaño, por ejemplo, estableciendo la escala en 0.5 será 2 veces más pequeño

>**Nota**
>
> Atención en un diseño es importante no hacer un pedidentificacióno solo con este widentificaciónget, no funcionará ya que es un widentificaciónget que utiliza el valor de varios pedidentificaciónos, es absolutamente necesario poner el widentificaciónget completo

## Botón deslizante

- paso : permite ajustar el paso de una acción en un botón (0.5 por defecto)

## Brújula

- aguja : configurado en 1 para mostrar en modo brújula

# Widentificaciónget de código

## Yiquetas

En el modo de código tiene acceso a diferentes etiquetas para pedidentificaciónos, aquí hay una lista (no necesariamente exhaustiva) :

- #nombre# : nombre del comando
- #VALUENAME# : nombre del valor del comando y = # nombre # cuando se trata de un comando de tipo de información
- #hidentificacióne_nombre# : vacío u oculto si el usuario solicitó ocultar el nombre del widentificaciónget, ponerlo directamente en una etiqueta de clase
- #identificación# : ID de pedidentificacióno
- #estado# : valor del comando, vacío para un comando de tipo de acción si no está vinculado a un comando de estado
- #UID# : identificaciónentificador único para esta generación del widentificaciónget (si hay varias veces el mismo comando, en el caso de diseños, solo este identificaciónentificador es realmente único)
- #ValueDate# : fecha del valor del pedidentificacióno
- #collectDate# : fecha de recogidentificacióna del pedidentificacióno
- #alertLevel# : nivel de alerta (ver [aquí] (https:// github.com/jeedom/core/blob/alpha/core/config/jeedom.config.php # L67) para la lista)
- #hidentificacióne_history# : si el historial (máximo, mínimo, promedio, tendencia) debe estar oculto o no. En cuanto a # hidentificacióne_nombre #, está vacío u oculto y, por lo tanto, se puede usar directamente en una clase. IMPORTANTEE si esta etiqueta no se encuentra en su widentificaciónget, entonces las etiquetas # minHistoryValue #, # averageHistoryValue #, # maxHistoryValue # y # trend # no serán reemplazadas por Jeedom.
- #minHistoryValue# : valor mínimo durante el período (período definidentificacióno en la configuración de libertad por el usuario)
- #averageHistoryValue# : valor promedio durante el período (período definidentificacióno en la configuración de libertad por el usuario)
- #maxHistoryValue# : valor máximo durante el período (período definidentificacióno en la configuración de libertad por el usuario)
- #tendencia# : tendencia durante el período (período definidentificacióno en la configuración de libertad por el usuario). Atención, la tendencia es directamente una clase de ícono : fas fa-flecha hacia arriba, fas fa-flecha hacia abajo o fas fa-minus

## Actualizar valores

Cuando un nuevo valor jeedom se verá en la página web si el comando está allí y en jeedom.cmd.actualizar si hay una función de actualización. En caso afirmativo, lo llama con un solo argumento que es un objeto en la forma :

```
{display_value:'#ValueDate estado #&#39;:'#ValueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel#'}
```

Aquí hay un ejemplo simple de código JavaScript para poner en su widentificaciónget :

```
<script>
    jeedom.cmd.update [&#39;# identificación #&#39;] = function (_options){
      $('.cmd[data-cmd_identificación=#identificación#]').attr('title','Date de valeur : '+_options.ValueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_identificación=#identificación#] .estado').empty().append(_options.display_value +' #unite#');
    }
    jeedom.cmd.update [ &#39;# identificación #&#39;] ({display_value:'#ValueDate estado #&#39;:'#ValueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel # &#39;});
</script>
```

Aquí 2 cosas importantes :

```
jeedom.cmd.update [&#39;# identificación #&#39;] = function (_options){
  $('.cmd[data-cmd_identificación=#identificación#]').attr('title','Date de valeur : '+_options.ValueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_identificación=#identificación#] .estado').empty().append(_options.display_value +' #unite#');
}
```

La función llamada al actualizar el widentificaciónget que se encarga de actualizar el código html del widentificaciónget_template

Y :

```
jeedom.cmd.update [ &#39;# identificación #&#39;] ({display_value:'#ValueDate estado #&#39;:'#ValueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel # &#39;});
 ```

 La llamada a esta función para la inicialización del widentificaciónget.

 Encontrará [aquí] (https:// github.com / jeedom / core / tree / V4-stable / core / template) ejemplos de widentificacióngets (en el tablero y las carpetas móviles)
