# Interacciones
**Herramientas → Interacciones**

El sistema de interacciones de Jeedom permite realizar acciones mediante comandos de texto o de voz.

Estos comandos se pueden obtener mediante:

- SMS: envía un SMS para ejecutar comandos (acción) o hacer una pregunta (información).
- Chat: Telegram, Slack, etc.
- Voz: dicta una frase con Siri, Google Now, SARAH, etc. Para dar órdenes (acción) o hacer una pregunta (información).
- HTTP: enviar una URL HTTP que contenga el texto (p. ej., Tasker, Slack) para ejecutar comandos (acción) o hacer una pregunta (información).

El interés de estas interacciones radica en la integración simplificada con otros sistemas, como teléfonos inteligentes, tabletas, otros dispositivos domóticos, etc.

> **Consejo**
>
> Puedes iniciar una interacción haciendo lo siguiente:
> - Haz clic en una de ellas.
> - Ctrl + clic o clic con el botón central para abrirlo en una nueva pestaña del navegador.

Dispone de un motor de búsqueda que le permite filtrar la visualización de las interacciones. La tecla Esc cancela la búsqueda.
A la derecha del campo de búsqueda, hay tres botones que aparecen en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda.
- La carpeta está abierta para desplegar todos los paneles y mostrar todas las interacciones.
- La carpeta cerrada para plegar todos los paneles.

Una vez en la configuración de una interacción, dispones de un menú contextual al hacer clic con el botón derecho del ratón sobre las pestañas de la interacción. También puedes utilizar Ctrl + clic o el clic central para abrir directamente otra interacción en una nueva pestaña del navegador.

## Interacciones

En la parte superior de la página hay tres botones:

- **Añadir**: Permite crear nuevas interacciones.
- **Regenerar**: Recrea todas las interacciones (puede tardar mucho tiempo, más de 5 minutos).
- **Probar**: Permite abrir un cuadro de diálogo para escribir y probar una frase.

> **Consejo**
>
> Si tienes una interacción que genera frases para las luces, por ejemplo, y añades un nuevo módulo de control de luces, tendrás que volver a generar todas las interacciones o bien ir a la interacción en cuestión y volver a guardarla para crear las frases de este nuevo módulo.

## Principio

El principio de creación es bastante sencillo: vamos a definir una frase modelo generadora que permitirá a Jeedom crear una o varios cientos de frases más que serán posibles combinaciones de la modelo.

Vamos a definir de la misma manera las respuestas con una plantilla (esto permite a Jeedom tener varias respuestas para una misma pregunta).

También se puede definir un comando que se ejecute si, por ejemplo, la interacción no está relacionada con una acción, sino con una información, o si se desea realizar una acción concreta tras dicha interacción (también es posible ejecutar un escenario, controlar varios comandos…​).

## Configuración

La página de configuración consta de varias pestañas y botones:

- **Frases**: Muestra el número de frases de la interacción (si haces clic encima, se muestran).
- **Grabar**: Graba la interacción actual.
- **Eliminar**: Elimina la interacción actual.
- **Duplicar**: Duplica la interacción actual.

### Pestaña «General»

- **Nombre**: Nombre de la interacción (puede dejarse en blanco; el nombre sustituye al texto de la solicitud en la lista de interacciones).
- **Grupo**: Grupo de interacción, que permite organizarlos (puede estar vacío, en cuyo caso se incluirá en el grupo «ninguno»).
- **Activo**: Permite activar o desactivar la interacción.
- **Solicitud**: La frase modelo generadora (obligatoria).
- **Sinónimo**: Permite definir sinónimos para los nombres de los comandos.
- **Respuesta**: La respuesta que hay que dar.
- **Esperar antes de responder (s)**: Permite añadir un retraso de X segundos antes de generar la respuesta. Esto permite, por ejemplo, esperar a que se reciba la respuesta de estado de una lámpara antes de responder.
- **Conversión binaria**: Permite convertir los valores binarios en abierto/cerrado, por ejemplo (solo para comandos de tipo «información binaria»).
- **Usuarios autorizados**: Limita la interacción a determinados usuarios (los nombres de usuario deben estar separados por \|).

### Pestaña «Filtros»

- **Limitar a los comandos de tipo**: Permite utilizar únicamente los tipos «acciones», «información» o ambos tipos.
- **Limitar a los comandos con el subtipo**: Permite limitar la generación a uno o varios subtipos.
- **Limitar a los comandos con la unidad**: Permite limitar la generación a una o varias unidades (Jeedom crea la lista automáticamente a partir de las unidades definidas en tus comandos).
- **Limitar a los comandos que pertenecen al objeto**: Permite limitar la generación a uno o varios objetos (Jeedom crea la lista automáticamente a partir de los objetos que hayas creado).
- **Limitar al plugin**: Permite limitar la generación a uno o varios plugins (Jeedom crea la lista automáticamente a partir de los plugins instalados).
- **Limitar a la categoría**: Permite limitar la generación a una o varias categorías.
- **Limitar a los dispositivos**: Permite limitar la generación a un único dispositivo o módulo (Jeedom crea la lista automáticamente a partir de los dispositivos o módulos que tienes).

### Pestaña «Acciones»

Utilízalo si quieres seleccionar uno o varios comandos específicos o pasar parámetros concretos.

#### Ejemplos

> **Nota**
>
> Las capturas de pantalla pueden variar en función de las actualizaciones.

#### Interacción sencilla

La forma más sencilla de configurar una interacción es asignarle un modelo generador rígido, sin posibilidad de variación. Este método se centrará de forma muy precisa en un comando o en un escenario.

En el siguiente ejemplo, en el campo «Solicitud» se puede ver la frase exacta que hay que decir para activar la interacción. En este caso, para encender la luz del techo del salón.

![interact004](../images/interact004.png)

En esta captura de pantalla se puede ver la configuración necesaria para establecer una interacción vinculada a una acción específica. Esta acción se define en la sección «Acción» de la página.

Es fácil imaginar cómo hacer lo mismo con varias acciones para encender varias lámparas en el salón, como en el siguiente ejemplo:

![interact005](../images/interact005.png)

En los dos ejemplos anteriores, la frase modelo es idéntica, pero las acciones que se derivan de ella varían en función de lo que se haya configurado en la sección «Acción»; por lo tanto, incluso con una interacción sencilla de una sola frase, ya es posible imaginar acciones combinadas entre diversos comandos y distintos escenarios (también se pueden activar escenarios en la sección «Acción» de las interacciones).

> **Consejo**
>
> Para añadir un escenario o crear una nueva acción, escribe «scenario» sin acento y pulsa la tecla de tabulación del teclado para que aparezca el selector de escenarios.

#### Interacción con múltiples controles

Aquí veremos todo el interés y el potencial de las interacciones: con una frase modelo podremos generar frases para todo un grupo de comandos.

Vamos a retomar lo que hemos hecho anteriormente, eliminar las acciones que habíamos añadido y, en lugar de la frase fija, en «Solicitud», vamos a utilizar las etiquetas **\#comando\#** y **\#equipo\#**. De este modo, Jeedom sustituirá estas etiquetas por el nombre de los comandos y el nombre del equipo (aquí se aprecia la importancia de que los nombres de los comandos y los equipos sean coherentes).

![interact006](../images/interact006.png)

Así pues, podemos observar aquí que Jeedom ha generado 152 frases a partir de nuestro modelo. Sin embargo, no están muy bien construidas y hay un poco de todo.

Para poner orden en todo esto, vamos a utilizar los filtros (parte derecha de nuestra página de configuración). En este ejemplo, queremos generar frases para encender las luces. Por lo tanto, podemos desmarcar el tipo de comando «info» (si guardo los cambios, solo me quedarán 95 frases generadas) y, a continuación, en los subtipos, podemos dejar marcada únicamente la opción «predeterminado», que corresponde al botón de acción (así que solo quedan 16 frases).

![interact007](../images/interact007.png)

Así está mejor, pero se puede hacer aún más natural. Si tomamos el ejemplo generado «En la entrada», estaría bien poder transformar esta frase en «enciende la entrada» o «encender la entrada». Para ello, Jeedom dispone, debajo del campo «solicitud», de un campo de sinónimos que nos permitirá dar un nombre diferente a los comandos en nuestras frases «generadas»; en este caso es «on», e incluso tengo «on2» en los módulos que pueden controlar dos salidas.

En los sinónimos, indicaremos el nombre del comando y el sinónimo o sinónimos que se deben utilizar:

![interact008](../images/interact008.png)

Aquí podemos ver una sintaxis algo nueva para los sinónimos. Un nombre de comando puede tener varios sinónimos; en este caso, «on» tiene como sinónimos «allume» y «allumer». Por lo tanto, la sintaxis es «*nombre del comando*» ***=*** «*sinónimo 1*»***,*** «*sinónimo 2*» (se pueden añadir tantos sinónimos como se desee). A continuación, para añadir sinónimos a otro nombre de comando, basta con añadir después del último sinónimo una barra vertical «*\|*», tras la cual se puede volver a indicar el nombre del comando al que se le van a asignar sinónimos, igual que en la primera parte, etc.

Ya está mejor, pero aún faltan, para el comando «on», «entrada», la preposición «l’», y para otros, «la», «le» o «un», etc. Se podría modificar el nombre del equipo para añadirlo, lo cual sería una solución; si no, se pueden utilizar las variaciones en la solicitud. Esto consiste en enumerar una serie de palabras posibles para una posición concreta de la frase; así, Jeedom generará frases con esas variaciones.

![interact009](../images/interact009.png)

Ahora tenemos frases un poco más correctas junto con otras que no lo son, por ejemplo, «on» y «entrada». Así, encontramos «Enciende la entrada», «Enciende una entrada», «Enciende la entrada», etc. De este modo, tenemos todas las variantes posibles con lo que hemos añadido entre «\[ \]» y esto para cada sinónimo, lo que genera rápidamente muchas frases (en este caso, 168).

Para afinar los resultados y evitar que aparezcan cosas improbables como «enciende la tele», podemos permitir que Jeedom elimine las peticiones con sintaxis incorrecta. De este modo, eliminará aquellas que se alejen demasiado de la sintaxis real de una frase. En nuestro caso, pasamos de 168 frases a 130.

![interact010](../images/interact010.png)

Por lo tanto, es importante elaborar bien las frases modelo y los sinónimos, así como seleccionar los filtros adecuados para no generar demasiadas frases innecesarias. Personalmente, me parece interesante que haya algunas incoherencias del tipo «una entrada», ya que si en tu casa hay una persona extranjera que no habla bien el francés, las interacciones seguirán funcionando de todos modos.

### Personalizar las respuestas

Hasta ahora, como respuesta a una interacción, teníamos una simple frase que no indicaba gran cosa, salvo que había ocurrido algo. La idea sería que Jeedom nos indicara lo que ha hecho con un poco más de precisión. Ahí es donde entra en juego el campo de respuesta, en el que podremos personalizar la respuesta en función del comando ejecutado.

Para ello, volveremos a utilizar las etiquetas de Jeedom. Para nuestras luces, podemos utilizar una frase del tipo: «He encendido \#equipement\#» (véase la captura de pantalla más abajo).

![interact011](../images/interact011.png)

También se puede añadir cualquier valor de otro comando, como una temperatura, un número de personas, etc.

![interact012](../images/interact012.png)

### Conversión binaria

Las conversiones binarias se aplican a los comandos de tipo «info» cuyo subtipo sea binario (que devuelvan únicamente 0 o 1). Por lo tanto, hay que activar los filtros adecuados, tal y como se puede ver en la captura de pantalla que aparece más abajo (en cuanto a las categorías, se pueden marcar todas; en este ejemplo, solo he seleccionado «luz»).

![interact013](../images/interact013.png)

Como se puede ver aquí, he mantenido prácticamente la misma estructura para la solicitud (lo he hecho a propósito para centrarme en los detalles específicos). Por supuesto, he adaptado los sinónimos para que el texto resulte coherente. Sin embargo, en la respuesta, es **imprescindible** incluir únicamente \#valor\#, que representa el 0 o el 1 que Jeedom sustituirá por la conversión binaria que viene a continuación.

El campo **conversión binaria** debe contener dos respuestas: en primer lugar, la respuesta si el valor del comando es 0; a continuación, una barra vertical «\|» como separador; y, por último, la respuesta si el comando es 1. En este caso, las respuestas son simplemente «no» y «sí», pero se podría incluir una frase un poco más larga.

> **Advertencia**
>
> Las etiquetas no funcionan en las conversiones binarias.

### Usuarios autorizados

El campo «Usuarios autorizados» permite autorizar únicamente a determinadas personas a ejecutar el comando; puedes introducir varios perfiles separándolos con «\|».

Ejemplo: persona1\|persona2

Es posible que un niño o un vecino que venga a regar las plantas en tu ausencia active o desactive la alarma.

### Expresión regular de exclusión

Es posible crear [Expresión regular](https://fr.wikipedia.org/wiki/Expression_rationnelle) de exclusión: si una frase generada coincide con esta expresión regular, se eliminará. La ventaja es que permite eliminar los falsos positivos, es decir, una frase generada por Jeedom que activa algo que no se ajusta a lo que queremos o que podría interferir en otra interacción que tuviera una frase similar.

Hay dos lugares donde se puede aplicar una expresión regular:
- En el propio campo «Expresión regular de exclusión».
- En el menú Administración→Configuración→Interacciones→campo «Expresión regular general de exclusión para las interacciones».

En el campo «Expresión regular general de exclusión para las interacciones», esta regla se aplicará a todas las interacciones que se creen o se guarden de nuevo a partir de ese momento. Si se desea aplicarla a todas las interacciones existentes, es necesario regenerarlas. Por lo general, se utiliza para eliminar frases mal formuladas que suelen aparecer en la mayoría de las interacciones generadas.

En el campo «Expresión regular de exclusión» de la página de configuración de cada interacción, se puede introducir una expresión regular específica que solo afectará a dicha interacción. Esto te permite eliminar de forma más precisa una interacción concreta. También puede servir para eliminar una interacción en un pedido específico en el que no se desee ofrecer esta opción en el contexto de la generación de pedidos múltiples.

La siguiente captura de pantalla muestra la interacción sin la expresión regular. En la lista de la izquierda, filtro las frases para mostrarte solo aquellas que se van a eliminar. En realidad, hay 76 frases generadas con la configuración de la interacción.

![interact014](../images/interact014.png)

Como podéis ver en la siguiente captura de pantalla, he añadido una expresión regular sencilla que busca la palabra «Julie» en las frases generadas y las elimina. Sin embargo, en la lista de la izquierda se puede ver que sigue habiendo frases con la palabra «julie»; en las expresiones regulares, «Julie» no es igual a «julie»; a esto se le llama «distinción entre mayúsculas y minúsculas» o, en buen francés, una mayúscula es diferente de una minúscula. Como se puede ver en la siguiente captura de pantalla, solo quedan 71 frases; las 5 que contenían «Julie» han sido eliminadas.

Una expresión regular se compone de la siguiente manera:

- En primer lugar, un delimitador; en este caso, una barra oblicua «/» situada al principio y al final de la expresión.
- El carácter que sigue a la barra vertical representa cualquier carácter, espacio o número.
- Por su parte, el símbolo «*» indica que puede aparecer cero o más veces el carácter que le precede, en este caso un punto; es decir, en buen español, cualquier elemento.
- A continuación, «Julie», que es la palabra que hay que buscar (palabra u otra forma de expresión), seguida de nuevo de un punto y una barra.

Si tradujéramos esta expresión a una frase, quedaría así: «busca la palabra Julie precedida de cualquier cosa y seguida de cualquier cosa».

Se trata de una versión extremadamente sencilla de las expresiones regulares, pero que ya resulta muy complicada de entender. Me llevó un tiempo comprender cómo funcionaba. Como ejemplo un poco más complejo, una expresión regular para validar una URL:

/\^(https?:\\/\\/)?(\[\\da-z\\.-\]+)\\.(\[a-z\\.\]{2,6})(\[\\/\\w\\.-\]\*)\*\\/?\$/

Si eres capaz de escribir esto, es que ya has entendido las expresiones regulares.

![interact015](../images/interact015.png)

Para resolver el problema de las mayúsculas y minúsculas, podemos añadir a nuestra expresión una opción que la haga insensible a las mayúsculas y minúsculas; en otras palabras, que considere que una letra minúscula es igual a una mayúscula; para ello, basta con añadir una «i» al final de nuestra expresión.

![interact016](../images/interact016.png)

Al añadir la opción «i», vemos que solo quedan 55 frases generadas y, en la lista de la izquierda con el filtro «julie» para buscar las frases que contienen esa palabra, vemos que hay muchas más.

Como se trata de un tema extremadamente complejo, no voy a entrar en más detalles aquí; hay suficientes tutoriales en Internet para ayudaros, y no olvidéis que Google también es vuestro amigo, porque sí, es mi amigo, fue él quien me enseñó a entender las expresiones regulares e incluso a programar. Así que, si a mí me ha ayudado, también puede ayudaros a vosotros si ponéis un poco de buena voluntad.

Enlaces útiles:

- <http://www.commentcamarche.net/contents/585-javascript-l-objet-regexp>
- <https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>
- <https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/les-expressions-regulieres-partie-1-2>

### Respuesta compuesta por varios datos

También es posible incluir varios comandos de información en una respuesta, por ejemplo, para obtener un resumen de la situación.

![interact021](../images/interact021.png)

En este ejemplo vemos una frase sencilla que nos devolverá una respuesta con tres temperaturas diferentes; por lo tanto, aquí podemos incluir prácticamente todo lo que queramos para obtener un conjunto de información de una sola vez.

### ¿Hay alguien en la habitación?

#### Versión básica

- Así pues, la pregunta es: «¿Hay alguien en la habitación?»
- La respuesta será «no, no hay nadie en la habitación» o «sí, hay alguien en la habitación».
- El comando correspondiente es «\#\[Habitación de Julie\]\[FGMS-001-2\]\[Presencia\]\#»

![interact017](../images/interact017.png)

Este ejemplo se centra específicamente en un dispositivo concreto, lo que permite obtener una respuesta personalizada. Por lo tanto, se podría sustituir la respuesta del ejemplo por «no, no hay nadie en la habitación de *julie*\|sí, hay alguien en la habitación de *julie*».

#### Evolución

- La pregunta es, por tanto, «#comando# [en el |en la] #objeto#».
- La respuesta será «no, no hay nadie en la habitación» o «sí, hay alguien en la habitación».
- No hay ningún comando que responda a eso en la sección «Acción», ya que se trata de una interacción de «Múltiples comandos».
- Al añadir una expresión regular, se pueden filtrar los comandos que no se desean mostrar para quedarnos solo con las frases relacionadas con los comandos de «Presencia».

![interact018](../images/interact018.png)

Sin la expresión regular, aquí se obtienen 11 frases, pero el objetivo de mi interacción es generar frases únicamente para preguntar si hay alguien en una habitación, por lo que no necesito información sobre el estado de las luces ni de otros elementos como los enchufes, lo cual se puede resolver con el filtrado mediante expresiones regulares. Para que sea aún más flexible, se pueden añadir sinónimos, pero en ese caso no hay que olvidar modificar la expresión regular.

### Conocer la temperatura, la humedad y la luminosidad

#### Versión básica

Se podría escribir la frase de forma explícita, por ejemplo, «¿cuál es la temperatura del salón?», pero habría que crear una frase para cada sensor de temperatura, luminosidad y humedad. Con el sistema de generación de frases de Jeedom, basta con una sola interacción para generar las frases correspondientes a todos los sensores de estos tres tipos de medición.

Aquí tienes un ejemplo genérico que sirve para conocer la temperatura, la humedad y la luminosidad de las diferentes estancias (objeto en el sentido de Jeedom).

![interact019](../images/interact019.png)

- Así pues, se puede observar que una frase genérica del tipo «¿Cuál es la temperatura del salón?» o «¿Cuál es la luminosidad del dormitorio?» puede convertirse en: «¿cuál es \[la \|l\\'\]\#comando\# objeto?» (el uso de \[palabra1 \| palabra2\] permite indicar «esta posibilidad o aquella» para generar todas las variantes posibles de la frase con palabra1 o palabra2). Durante la generación, Jeedom generará todas las combinaciones posibles de frases con todos los comandos existentes (en función de los filtros), sustituyendo \#comando\# por el nombre del comando y \#objeto\# por el nombre del objeto.
- La respuesta será del tipo «21 °C» o «200 lux». Basta con escribir: \#valor\# \#unidad\# (la unidad debe completarse en la configuración de cada comando para el que se desee tener una).
- Por lo tanto, este ejemplo genera una frase para todos los comandos de tipo «información numérica» que tienen una unidad; así pues, podemos desmarcar las unidades en el filtro de la derecha, limitándolo al tipo que nos interesa.

#### Evolución

Así pues, podemos añadir sinónimos al nombre del comando para que suene más natural, o añadir una expresión regular para filtrar los comandos que no tengan nada que ver con nuestra interacción.

Añadir un sinónimo permite indicarle a Jeedom que un comando llamado «X» también puede llamarse «Y» y, por lo tanto, en nuestra frase, si decimos «encender y», Jeedom sabe que se trata de encender x. Este método resulta muy práctico para renombrar los nombres de los comandos que, al mostrarse en pantalla, están escritos de una forma que no resulta natural al pronunciarlos o en una frase escrita, como «ON». Un botón escrito así tiene todo su sentido, pero no en el contexto de una frase.

También se puede añadir un filtro Regexp para eliminar algunos comandos. Si retomamos el ejemplo sencillo, vemos frases como «batería» o «latencia», que no tienen nada que ver con nuestra interacción entre temperatura, humedad y luminosidad.

![interact020](../images/interact020.png)

Así pues, podemos ver una expresión regular:

**(batería\|latencia\|presión\|velocidad\|consumo)**

Esto permite eliminar todos los pedidos que contengan alguna de estas palabras en su descripción

> **Nota**
>
> La expresión regular que aparece aquí es una versión simplificada para facilitar su uso. Por lo tanto, se pueden utilizar tanto las expresiones tradicionales como las simplificadas, tal y como se muestra en este ejemplo.

### Controlar un regulador de intensidad o un termostato (control deslizante)

#### Versión básica

Es posible controlar una lámpara mediante un regulador de intensidad (variador) o un termostato con las interacciones. A continuación se muestra un ejemplo de cómo controlar el regulador de intensidad de una lámpara con las interacciones:

![interact022](../images/interact022.png)

Como se puede ver, en la solicitud aparece la etiqueta **\#consigna\#** (se puede poner lo que se quiera), que se utiliza en el comando del regulador para aplicar el valor deseado. Para ello, hay tres partes: \* Solicitud: en la que se crea una etiqueta que representará el valor que se enviará a la interacción. \* Respuesta: se reutiliza la etiqueta en la respuesta para asegurarnos de que Jeedom ha entendido correctamente la solicitud. \* Acción: se establece una acción en la lámpara que queremos controlar y, en el valor, le pasamos nuestra etiqueta *consigne*.

> **Nota**
>
> Se puede utilizar cualquier etiqueta, excepto aquellas que ya utiliza Jeedom; puede haber varias para controlar, por ejemplo, varios mandos. Cabe señalar también que todas las etiquetas se pasan a los escenarios iniciados por la interacción (aunque es necesario que el escenario esté en «Ejecutar en primer plano»).

#### Evolución

Es posible que queramos controlar todos los mandos de tipo deslizador con una sola acción. Con el siguiente ejemplo, podremos controlar varios reguladores con una sola acción y, por lo tanto, generar un conjunto de frases para controlarlos.

![interact033](../images/interact033.png)

En esta interacción, no hay ningún comando en la parte de acción; dejamos que Jeedom genere la lista de frases a partir de las etiquetas. Se puede ver la etiqueta **\#slider\#**. Es imprescindible utilizar esta etiqueta para las instrucciones en una interacción con múltiples comandos; no tiene por qué ser la última palabra de la frase. También se puede observar en el ejemplo que en la respuesta se puede utilizar una etiqueta que no forme parte de la solicitud. La mayoría de las etiquetas disponibles en los escenarios también lo están en las interacciones y, por lo tanto, pueden utilizarse en una respuesta.

Resultado de la interacción:

![interact034](../images/interact034.png)

Se puede observar que la etiqueta **\#equipamiento\#**, que no se utiliza en la solicitud, sí aparece en la respuesta.

### Controlar el color de una tira de LED

Es posible controlar un dispositivo de iluminación de colores mediante interacciones, por ejemplo, pidiéndole a Jeedom que encienda una tira de LED en azul. Esta es la interacción que hay que realizar:

![interact023](../images/interact023.png)

Hasta aquí nada muy complicado, pero hay que haber configurado los colores en Jeedom para que funcione; ve al menú → Configuración (arriba a la derecha) y, a continuación, a la sección «Configuración de interacciones»:

![interact024](../images/interact024.png)

Como se puede ver en la captura de pantalla, no hay ningún color configurado, por lo que hay que añadir colores con el signo «+» de la derecha. El nombre del color es el nombre que le darás a la interacción; luego, en la parte derecha (columna «Código HTML»), al hacer clic en el color negro, puedes elegir un nuevo color.

![interact025](../images/interact025.png)

Podemos añadir tantos como queramos y ponerles el nombre que queramos, así que podríamos, por ejemplo, asignar un color al nombre de cada miembro de la familia.

Una vez configurado, si dices «Enciende el árbol de Navidad en verde», Jeedom buscará un color en la solicitud y lo aplicará al comando.
### Uso combinado con un escenario

#### Versión básica

Es posible vincular una interacción a un escenario para llevar a cabo acciones algo más complejas que la ejecución de una simple acción o una solicitud de información.

![interact026](../images/interact026.png)

Este ejemplo permite, por tanto, ejecutar el escenario vinculado en la sección de acciones; por supuesto, se pueden tener varios.

### Programación de una acción con interacciones

Las interacciones permiten hacer muchas cosas concretas. Puedes programar una acción de forma dinámica. Ejemplo: «Pon la calefacción a 22 a las 14:50». Para ello, nada más sencillo: basta con utilizar las etiquetas \#time\# (si se define una hora concreta) o \#duration\# (para dentro de X tiempo, por ejemplo, dentro de 1 hora):

![interact23](../images/interact23.JPG)

> **Nota**
>
> Observará en la respuesta la etiqueta \#value\#, que, en el caso de una interacción programada, contiene la hora efectiva de programación.
