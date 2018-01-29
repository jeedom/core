El sistema de interacción en Jeedom permite acciones
desde los comandos de voz o texto.

Estos comandos se pueden obtener por:

-   SMS: enviar un SMS para ejecutar comandos (acción) o pedir una
    tema (información).

-   Chat: Telegrama, Slack, etc.

-   Vocal: dictar una sentencia con Siri, Google Now, Sarah, etc. Para
    los comandos de marcha (acción) o hacer una pregunta (información).

-   HTTP: lanzar una URL HTTP que contiene el texto (por ejemplo, Tasker, Slack).
    para ejecutar comandos (acción) o hacer una pregunta (información).

El interés de la interacción reside en la integración simplificada en
otros sistemas, tales como teléfonos inteligentes, tabletas, otro cuadro de automatización del hogar, etc.

Para acceder a la página interactiva se encuentra en Herramientas →
interacciones:

En la parte superior, hay 3 botones:

-   ** ** Añadir a la creación de nuevas interacciones.

-   Regenerar ** **: que se vuelva a crear todas las interacciones (puede ser
    5mn a largo &gt;).

-   ** ** Prueba de la que se abre un cuadro de diálogo para escribir y
    probar una frase.

> **Tip**
>
> Si usted tiene una interacción que genera las sentencias por las luces
> Por ejemplo, y se añade un nuevo módulo de control
> Luz, es necesario o bien regenerar todas las interacciones, o
> Ir a la interacción que se trate y de regreso a
> Crear frases de este nuevo módulo.

principio
========

El principio de la creación es muy sencillo, vamos a definir una sentencia
modelo de generación que permitirá Jeedom para crear uno o más
cientos de otras frases que serán las posibles combinaciones de
modelo.

Definiremos la misma manera que las respuestas con un modelo (que permite
Jeedom tener varias respuestas a una sola pregunta).

También podemos definir un comando para ejecutar si tal
la interacción no está relacionado con una acción o información pero si
quiere llevar a cabo una acción en particular después de él (que es también
posible ejecutar una secuencia de comandos para controlar múltiples órdenes ...).

configuración
=============

La página de configuración consta de varias pestañas y
botones:

-   ** ** frases: Muestra el número de sentencias de la interacción (un clic
    se muestra)

-   ** ** Guardar: guarda la interacción actual

-   ** ** Eliminar: Eliminar la interacción actual

-   **Duplicar**: duplica la interacción Courrante

general
=======

-   ** ** Nombre: nombre de la interacción (puede estar vacío, el nombre reemplaza
    texto de la aplicación en la lista de interacciones).

-   ** ** Grupo: la interacción del grupo, que ayuda a organizar
    (Puede estar vacío, estará en el grupo "no").

-   **activo**: Habilitar o deshabilitar la interacción.

-   ** ** Solicitud generador de frases (obligatorio).

-   ** ** Sinónimo: conjunto de sinónimos en nombres
    comandos.

-   ** ** Respuesta: La respuesta para.

-   ** ** conversión binaria: para convertir los valores binarios
    abierto / cerrado, por ejemplo (para controles de tipo
    información binaria).

-   ** ** Usuarios Autorizados: interacciones límite hasta cierto
    los usuarios (nombres de usuario separados por |).

filtros
=======

-   **Límite de escribir comandos**: utilizar sólo
    Las acciones típicas, información o 2 tipos.

-   **Límite de comandos que permite subtipo** límite
    la generación de uno o más subtipos.

-   **Límite de comandos que permite la unidad** límite
    la generación de una o más unidades (Jeedom crea la lista
    automáticamente de las unidades definidas en sus pedidos).

-   **Límite a órdenes pertenecientes al objeto** permite límite
    generar uno o más objetos (Jeedom crea la lista
    de forma automática a partir de los objetos que haya creado).

-   ** ** Limite el plugin puede limitar la generación de una o
    varios plugins (Jeedom crea automáticamente la lista de
    plugins instalados).

-   ** ** Límite categoría: limita la generación de
    o más categorías.

-   **Límite de equipos**: puede limitar la generación de
    un dispositivo / módulo (Jeedom crea automáticamente la lista
    de dispositivos / módulos de usted).

acción
======

Utilizarlo si desea orientar uno o más controles específicos
o pasar parámetros especiales.

Ejemplos
========

> **Nota**
>
> Las capturas de pantalla pueden diferir en vista de la evolución.

simple interacción
------------------

La forma más fácil de configurar una interacción, que es de él
dar un generador de modelo rígido, sin ninguna posibilidad de variación. Este
método de orientar con precisión un comando o script.

En el siguiente ejemplo, se puede ver en la "Aplicación" sentencia de campo
Exact para proporcionar para activar la interacción. En este caso, para encender
techo de la sala de estar.

![interact004](../images/interact004.png)

Se puede ver en esta captura de pantalla, la configuración de un
interacción relacionada con una acción específica. Esta acción se define en
la sección "Acción" de la página.

Es fácil imaginar haciendo lo mismo con varias acciones
más lámparas de luz en la sala de estar como el siguiente ejemplo:

![interact005](../images/interact005.png)

En los dos ejemplos anteriores, el modelo es idéntico pero la sentencia
acciones resultantes cambian de acuerdo con lo que se configura
en la "acción", por lo que ya puede con un simple interacción
sola frase imaginar acción combinada entre varios comandos y
escenarios (que también pueden desencadenar escenarios en el juego
la acción de las interacciones).

> **Tip**
>
> Para agregar una secuencia de comandos, crear una nueva acción, escribir "escenario"
> Sin acento, presione Tab en su teclado para
> Abra el selector de escenario.

varios comandos de interacción
------------------------------

Veremos aquí todo el interés y toda la potencia de
interacciones con un modelo frase podemos generar
frases a un grupo de comandos.

Vamos a recuperar lo que se ha hecho anteriormente, eliminar acciones
se había añadido, y en lugar de la sentencia fija en "Solicitud"
vamos a utilizar etiquetas **\ # comando \ #** y **\ # Equipo \ #**.
Por lo tanto, Jeedom sustituirá estas etiquetas con el nombre del comando y el nombre de
el equipo (se puede ver la importancia de los nombres
Control / características coherentes).

![interact006](../images/interact006.png)

Se puede observar aquí que Jeedom genera 152 frases de
nuestro modelo. Sin embargo, no están muy bien construidos y
tiene un poco de todo.

A la orden en todo esto, vamos a utilizar filtros (parte
derecha de nuestra página de configuración). En este ejemplo, queremos
generar frases para encender las luces. Podemos desmarcar la
escribir info (si salvo, me quedo más de 95 frases
Generamos) y en subtipos, no podemos mantener esa marcada
"Por defecto", que es el botón de acción (también lo son sólo el 16
frases).

![interact007](../images/interact007.png)

Es mejor, pero podemos hacer aún más natural. Si tomo
Ejemplo genera "Input On", que sería bueno para convertir
esta frase "Entrada de las luces" o "entrada de luz". Para hacer
Jeedom le atribuya el campo de aplicación, un campo que significará
nos permiten nombrar a los nombres de los comandos de manera diferente en nuestra
"Generados" frases aquí es "sobre", incluso "On2" en los módulos
que pueden controlar dos salidas.

En sinónimos, por lo que vamos a especificar el nombre del comando y la (s)
sinónimo (s) a utilizar:

![interact008](../images/interact008.png)

Aquí podemos ver un poco nueva sintaxis de sinónimos. Un nombre
el control puede tener varios sinónimos, aquí "nosotros" es sinónimo
"Luz" y "luz". La sintaxis es "* * nombre del comando"
****** = "sinónimo * 1 *"*** *** "sinónimo * 2 *" (se puede poner como muchos
sinónimo desea). A continuación, añadir sinónimos a otro
nombre del comando, sólo tiene que añadir después de la última barra de sinónimos
vertical "* | *" tras laquel se puede nombrar el nuevo
comando que será sinónimo de que la primera pieza, etc.

Es mejor, pero todavía carece de la "a" "de entrada"
la "L" y el otro "el" o "la" o "a", etc. Podriamos
cambiar el nombre del dispositivo para agregar, sería una solución,
si se pueden utilizar los cambios en la demanda. esto implica
enumerar una serie de palabras posibles a un lugar de la oración, Jeedom
por lo tanto, generar frases con estas variaciones.

![interact009](../images/interact009.png)

Ahora tenemos un poco más frases correctas con frases
no son sólo, para nuestro ejemplo "en" "introducir". Así nos encontramos
"Resulta entrada", "A su vez en una entrada", "A su vez en una entrada", "Lighter
entrada ", etc. Fue por lo tanto todas las posibles variantes con lo que es
añadido entre "\ [\]", y esto para cada sinónimo, generando
pronto muchas frases (en este caso 168).

Con el fin de refinar y no tener cosas improbables como
"Se enciende el televisor," puede permitir Jeedom para eliminar aplicaciones
sintácticamente incorrecta. Por lo tanto, se eliminará lo que también está muy lejos
la sintaxis real de una frase. En nuestro caso se pasa 168
130 frases.

![interact010](../images/interact010.png)

Por lo tanto, se vuelve importante para construir sus modelos y frases
sinónimos y seleccionar los filtros adecuados no generan
demasiadas palabras innecesarias. En lo personal, me resulta interesante tener
algunas inconsistencias como "entrada" porque si usted,
una persona extranjera no parecen ser adecuadamente los franceses
interacciones todavía funcionan.

personalizar respuestas
--------------------------

Hasta el momento, en respuesta a una interacción, tuvimos un sencillo
sentencia no indica mucho, excepto que algo está
pasado. La idea sería que Jeedom nos dice que lo hizo un poco más
con precisión. Aquí es donde el campo de respuesta en el que nos vamos
para personalizar el rendimiento en función de la orden ejecutada.

Para ello, vamos a utilizar de nuevo las etiquetas Jeedom. para nuestra
luces pueden utilizar una frase como: Encendí
\ #equipement \ # (ver imagen abajo).

![interact011](../images/interact011.png)

También puede añadir cualquier valor a otro comando como
una temperatura, un número de personas, etc.

![interact012](../images/interact012.png)

conversión binaria
------------------

Las conversiones binarias aplican a los tipos de comandos cuya información
subtipo es binarios (devuelve 0 o 1 solamente). Por consiguiente, debemos activar
buenos filtros, como puede verse en la captura de un poco menor
(Para las categorías pueden todas comprobar, por ejemplo, tengo
mantenido como luz).

![interact013](../images/interact013.png)

Como podemos ver aquí, casi me quedé con la misma estructura
a la demanda (es decir voluntaria para centrarse en
especificidades). Por supuesto, me he adaptado sinónimos para algunos
algo consistente. Por contra, la respuesta, es imperativo ** ** a
con sólo el \ #VALEUR \ # representa el 0 o 1 que se Jeedom
sustituye por el siguiente conversión binaria.

La conversión binaria ** ** campo debe contener dos respuestas: la primera
la respuesta si el valor del pedido es 0, entonces una barra vertical "|"
separación y finalmente la respuesta si el comando es 1. Aquí
Las respuestas son simplemente no y sí, pero se puede poner una sentencia
un poco más.

> **Aviso**
>
> Las etiquetas no funcionan en las conversiones binarias.

usuarios autorizados
----------------------

Los "Usuarios Autorizados" campo para permitir que solamente ciertos
las personas para ejecutar el comando, puede poner varios perfiles
separados por un "|".

Ejemplo: persona.1 | person2

Uno puede imaginar que una alarma puede ser activada o desactivada por una
niño o vecino que le regar las plantas en su ausencia.

la exclusión de expresiones regulares
------------------

Puede crear
[Regexp] (https://fr.wikipedia.org/wiki/Expression_rationnelle)
exclusión, si se genera una frase coincidencias con esta expresión regular será
borrado. La ventaja es ser capaz de eliminar los falsos positivos, es decir
es decir, una frase generada por Jeedom que activa algo que
no lo que queremos o que pueda parasitar otra
interacción que tendría una sentencia similar.

Tenemos 2 lugares para aplicar Regexp:

-   en la misma interacción en el "exclusión Regexp".

-   Desde el menú Configuración Administración → → → Interacción campo "Regexp
    exclusión general para la interacción".

Para el "exclusión general Regex para las interacciones," Este
regla se aplicará a todas las interacciones, las cuales serán creados o
salvado de nuevo más tarde si queremos aplicarlo a todos
las interacciones se deben regenerar interacciones.
Generalmente se utiliza para borrar las frases de forma incorrecta
formada por encontrarse en la mayoría de las interacciones generadas.

Para el campo "exclusión Regexp" en la página de configuración
cada interacción, uno puede poner un Regexp específica que actuará
Sólo dicha interacción, que le permite quitar
específicamente para la interacción. Esto también puede permitir
borrar una interacción para un comando específico para el cual
no ofrece esta posibilidad en el contexto de una generación
varios comandos.

La captura de pantalla muestra la interacción sin la Regexp. En la
lista de la izquierda, que filtrar para mostrar sólo las frases que esa
frases que se van a eliminar. En realidad hay 76 frases generadas
con la configuración de la interacción.

![interact014](../images/interact014.png)

Como se puede ver en la pantalla de abajo, he añadido una
sola expresión regular que se va a buscar la palabra "Julie" en las frases generadas
y borrar, pero podemos ver en la lista de la izquierda hay
siempre unas frases con la palabra "Julie" en expresiones
Julie regular no es igual a Julie, esto se llama una
mayúsculas y minúsculas o en mayúsculas bien el francés es diferente
una diminuta. Como se muestra en la siguiente pantalla, se
permanece más de 71 frases, se suprimieron con un "Julie" 5.

Una expresión regular es el siguiente:

-   Un primer delimitador, que aquí hay una barra "/" colocado
    principio y fin de plazo.

-   El punto siguiente de la barra representa cualquier
    carácter, espacio o número.

-   El "\ *" sobre él indica que puede ser 0 o más veces
    el carácter que le precede, un punto aquí, todo bien en francés
    cualquier artículo.

-   Entonces Julie, que es el término de búsqueda (palabra o otro esquema
    expresión), seguido de nuevo por un punto y raya vertical.

Si traducimos esta expresión en una frase sería "buscar la
Julie palabra que está precedido por nada y seguido por cualquier
qué".

Esta es una versión muy simple de expresiones regulares, pero
ya es muy complicado de entender, me llevó un tiempo comprender
el funcionamiento. Como un ejemplo un poco más complejo, una expresión regular para
comprobar una dirección URL:

/ \ ^ (Https: \\ / \\ /) (\ [\\ da-z \\ .- \] +) \\ (\ [az \\ \.] {2,6}) (?. \ [\\ / \\ w
\\ .- \] \ *) \ * \\ /? \ $ /

Una vez que usted lo escribe, a entender las expresiones
regular.

![interact015](../images/interact015.png)

Para resolver el problema de mayúsculas y minúsculas se pueden añadir a
nuestra expresión de una opción que hará insensible o
es decir, considerando una minúscula igual a una de capital;
Para ello simplemente hay que añadir al final de nuestro término una
"I".

![interact016](../images/interact016.png)

Con la adición de la "i" se encuentra a no más de 55
generada frases y en la lista de la izquierda con filtro de Julie
frases de búsqueda que contengan esa palabra, nos encontramos con que hay
mucho más.

Como se trata de un tema muy complejo, no voy a entrar en más
detalle aquí, hay bastantes tutoriales en la red para ayudarle, y
recuerde que Google es tu amigo también porque sí, este es mi amigo,
fue él quien me enseñó a entender la expresión regular e incluso la codificación. Entonces
si me ayudó, sino que también le puede ayudar si se pone la derecha
lo hará.

Enlaces útiles :

-   <Http://www.commentcamarche.net/contents/585-javascript-l-objet-regexp>

-   <Https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>

-   <Https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/les-expressions-regulieres-partie-1-2>

Respuesta compuesta de varias informaciones
------------------------------------------

También es posible poner varios comandos en una información
respuesta, por ejemplo, para un resumen de estado.

![interact021](../images/interact021.png)

En este ejemplo vemos una oración sencilla que nos va a devolver
respondemos con 3 temperaturas diferentes, por lo que podemos poner un poco de aquí
todo lo que desea tener un conjunto de información de una
sola vez.

¿Hay alguien en la habitación?
------------------------------------

### versión básica

-   La pregunta es "¿La hay alguien en la habitación"

-   La respuesta será "no hay nadie en la habitación" o "sí hay
    alguien en la habitación "

-   El comando que responde que es "\ # \ [Sala
    Julie \] \ [GSMF-001-2 \] \ [Presencia \] \ # "

![interact017](../images/interact017.png)

Este ejemplo objetivo precisamente equipo específico permitiendo
tener una respuesta personalizada. Uno podría imaginar la sustitución
la respuesta del ejemplo "no hay nadie en la cámara de
* * Julie | sí hay alguien en la habitación * * Julie "

### evolución

-   La pregunta es "\ #commande \ # \ [en | la \] \ #objet \ #"

-   La respuesta será "no hay nadie en la habitación" o "sí hace
    alguien en la habitación "

-   No hay control que se ocupa de esto en la sección acción vista
    se trata de una interacción comandos múltiple

-   Mediante la adición de una expresión regular puede limpiar los controles
    que uno no quiere tener sólo las oraciones en
    controles "presencia".

![interact018](../images/interact018.png)

Sin Regexp se obtiene aquí 11 frases, o mis objetivos de interacción
generar frases sólo para preguntar si hay alguien en
una habitación, así que no tenía necesidad de lámpara de estado o al otro como
tomado, que puede ser resuelto con el filtrado de expresión regular. hacer
aún más flexible puede agregar sinónimos, pero en este caso se
No hay que olvidar que cambiar la expresión regular.

Conociendo la temperatura / humedad / brillo
--------------------------------------------

### versión básica

Se podría escribir la frase dura como "¿cuál es la
viviendo temperatura ambiente", pero sería hacer uno para cada sensor
de temperatura, luz y humedad. Con el sistema de generación
Jeedom frase, podemos con una sola interacción generar
frases para todos los sensores de estos 3 tipos de medición.

Aquí un ejemplo genérico utilizado para determinar la temperatura,
humedad, el brillo de las diversas partes (objeto bajo Jeedom).

![interact019](../images/interact019.png)

-   Podemos ver que una frase genérica, "¿Cuál es el
    la temperatura de los vivos "o" ¿Cuál es la luminosidad de la habitación "
    pueden ser convertidos en "lo que es \ [el | \\ 's \] \ # comando \ # objeto"
    (Use \ [palabra1 | palabra2 \] decir esta posibilidad
    o que uno a generar todas las posibles variantes de la sentencia
    word2 word1 o con). Cuando la generación Jeedom generará todo
    posibles combinaciones de frases con todos los pedidos
    (filtros basados) existente mediante la sustitución de \ #commande \ # por
    el nombre del comando y \ #objet \ # el nombre del objeto.

-   La respuesta será de tipo "21 ° C" o "200 lux" simplemente poner:
    \ #value \ # \ #unite \ # (la unidad es completa en la configuración
    cada comando para el que desea tener uno)

-   Por tanto, este ejemplo se genera una oración para todos los pedidos
    dicha información digital que una unidad, podemos desmarcar
    Unidades en el filtro derecho limitado al tipo de interés.

### evolución

Podemos añadir sinónimos para el nombre del comando para algunos
algo más natural, añadir una expresión regular para filtrar los comandos
no tienen nada que ver con nuestra interacción.

Adición de media, digamos que un comando llamando Jeedom
"X" también se llama "Y" y, por tanto, en nuestra sentencia si era "luces
y", Jeedom sabe que es x luz. Este método es muy conveniente
cambiar el nombre de los nombres de comandos cuando se muestra en
la pantalla se escriben de una manera que no es natural o vocalmente
en una frase escrita que el botón "ON", escrito como es
completamente lógico, pero no en el contexto de una frase.

También puede agregar un filtro para eliminar Regexp algunos comandos.
Al tomar el ejemplo sencillo vemos frases "batería" o
"Latencia", que no tienen nada que ver con nuestra interacción
temperatura / humedad / brillo.

![interact020](../images/interact020.png)

Podemos ver una expresión regular:

**(batería | latencia | presión | velocidad | consumo)**

Esto eliminará todos los pedidos que tienen uno de estos
palabras en su sentencia

> **Nota**
>
> La expresión regular que aquí hay una versión simplificada para facilitar su uso.
> Tenemos la posibilidad de utilizar las expresiones tradicionales o
> Usar expresiones simplificadas como en este ejemplo.

Un control del atenuador o un termostato (deslizante)
-------------------------------------------

### versión básica

Es posible controlar una lámpara de porcentaje (inversor) o una
termostato con interacciones. He aquí un ejemplo para impulsar su
conducir una lámpara con interacciones:

![interact022](../images/interact022.png)

Como se ve, hay aquí en la demanda Etiquetado **\ # set \ #** (nos
puede poner lo que quiera) que se incluye en el orden de
inversor para aplicar el valor deseado. Para ello, contamos con 3 partes
: \ * Aplicación: en el que se crea una etiqueta que representará el valor
para ser enviado a la interacción. \ * Respuesta: la reutilización de la etiqueta
la respuesta para asegurarse Jeedom entiende correctamente la solicitud.
\ * Acción: se pone una acción sobre la lámpara que desea y la unidad
el valor que le nuestro conjunto * * etiqueta de transferencia.

> **Nota**
>
> Se puede utilizar cualquier etiqueta, excepto los ya utilizados por
> Jeedom, puede haber varios para conducir tales
> Más comandos. También tenga en cuenta que todas las etiquetas se pasan a
Escenarios> iniciadas por la interacción (sin embargo ese escenario
> O "Ejecutar primer plano").

### evolución

Podemos querer controlar todos los controles de tipo cursor con una
interacción individual. Con el siguiente ejemplo vamos a por lo tanto, ser capaz de ordenar
varias unidades con una sola interacción y por lo tanto generan una
un conjunto de frases de controlar.

![interact033](../images/interact033.png)

En esta interacción, no hay ningún control en la parte de acción, se
dejó Jeedom generar etiquetas de la lista de frases pueden ser
ver la etiqueta **\ # deslizador \ #**. Es imperativo el uso de esta etiqueta para
instrucciones en un múltiples comandos de interacción, puede que no sea
la última palabra de la frase. También se puede ver en el ejemplo que nos
ser utilizado en la respuesta una etiqueta que no es parte de la
la demanda, la mayoría de las etiquetas disponibles en los escenarios son
Disponible también en las interacciones y por lo tanto puede ser utilizado
en una respuesta.

Resultado de la interacción:

![interact034](../images/interact034.png)

Podemos ver que la etiqueta **\ # Equipo \ #** que no se utiliza
en la aplicación se completa en la respuesta.

Controlar el color de un panel LED
--------------------------------------

Es posible controlar un control del color por interacciones
preguntando por ejemplo Jeedom para iluminar un panel de LED en azul.
Esta interacción a:

![interact023](../images/interact023.png)

Hasta aquí nada complicado, sin embargo, se debe configurar
los colores en Jeedom para que esto funcione; cita en
Menú → Configuración (arriba a la derecha) y la parte
"Interacciones de configuración":

![interact024](../images/interact024.png)

Como se ve en la pantalla, no hay color
configurado, por lo que añadir color con el signo "+" a la derecha. la
nombre del color es el nombre que se perderá la interacción,
a continuación, en la parte derecha ( "Código HTML"), haciendo clic en el
negro se puede elegir un nuevo color.

![interact025](../images/interact025.png)

Podemos añadir tantos como queramos, podemos poner como nombre
ninguna, por lo que uno podría imaginar asignar un color a
el nombre de cada miembro de la familia.

Una vez configurado, se dice "Luz del árbol verde", se Jeedom
buscar en la solicitud de un color y aplicarlo a la orden.

Utilice acoplado a un escenario
---------------------------------

### versión básica

Es posible acoplar una interacción con un escenario de
hacer un poco de acciones más complejas que la ejecución de un simple
acción o solicitud de información.

![interact026](../images/interact026.png)

Este ejemplo hace que sea posible poner en marcha el escenario que está vinculado de
parte de acción, que por supuesto puede tener varios.

Programación de una acción con la interacción
------------------------------------------------

Las interacciones permiten hacer muchas cosas en particular.
Puede configurar dinámicamente una acción. Ejemplo: "Turns
la calefacción a 22 para las 14:50 hrs. "Nada podría ser más sencillo, basta
etiquetas Use \ #time \ # (si definimos una hora específica) o
\ #duration \ # (a dentro del tiempo X, en el ejemplo 1 hora):

![interact23](../images/interact23.JPG)

> **Nota**
>
> Usted se dará cuenta en la respuesta de la etiqueta \ #value \ # contiene
> En el caso de una interacción en tiempo de programación programada
> efectiva
