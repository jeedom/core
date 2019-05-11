Partie importante dans un logiciel : la partie historisation, véritable
mémoire de celui-ci. Il est possible dans Jeedom d’historiser n’importe
quelle commande de type information (binaire ou numérique). Cela vous
permettra donc par exemple d’historiser une courbe de température, de
consommation ou les ouvertures d’une porte, etc.​

principio
========

Aquí se describe el principio de archivado de Jeedom. es
tienen que entender que si usted está teniendo problemas
para archivar o quiere cambiar los ajustes
historización. Los ajustes por defecto son adecuadas en la mayoría
caso.

archivado
---------

L’archivage de données permet à Jeedom de réduire la quantité de données
conservées en mémoire. Cela permet de ne pas utiliser trop de place et
de ne pas ralentir le système. En effet, si vous conservez toutes les
mesures, cela fait d’autant plus de points à afficher et donc peut
considérablement allonger les temps pour rendre un graphique. En cas
d’un nombre trop important de points, cela peut même faire planter
l’affichage du graphique.

El archivo es una tarea que se lanza a la noche y compacta
Los datos recogidos en el día. Por defecto recupera todos Jeedom
el 2h de datos más antiguo y hace 1h paquete (una
medio, mínimo o máximo dependiendo de la configuración). Por lo tanto,
Aquí dos ajustes, uno para el tamaño del paquete y la otra es saber
desde la hora de hacer (de aviso predeterminado para este son paquetes
1 hora con datos que tiene más de 2 horas de edad).

> **Tip**
>
> Si ha seguido usted debe tener una alta precisión en
> Última 2 horas. Sin embargo, cuando me conecto a 17h,
> Tengo una aclaración sobre las últimas 17 horas. Por qué ? De hecho,
> Para evitar el consumo de recursos innecesariamente, la tarea hecha
> Archivado lleva a cabo sólo una vez al día, por la tarde.

> **Importante**
>
> Por supuesto, este principio archivado sólo se aplica a las órdenes
> Tipo numérico; los comandos binarios, no enlatados Jeedom
> Que los cambios de estado.

Viendo el gráfico
========================

Hay varias maneras de acceder a la historia:

-   poner un área del gráfico en una vista en planta (véase más adelante),

-   haciendo clic en el comando deseado en un widget,

-   por ir a la página de la historia que puede superponer
    diferentes curvas y combinan estilos (área, curva, bar)

-   Móviles restantes soportado en el widget en cuestión

Si ve un gráfico de histórica o haciendo clic
el widget, usted tiene acceso a múltiples opciones de visualización:

On retrouve en haut à droite la période d’affichage (ici sur la dernière
semaine car, par défaut je veux que ça soit seulement une semaine - voir
2 paragraphes au-dessus), ensuite viennent les paramètres de la courbe
(ces paramètres sont gardés d’un affichage à l’autre ; vous n’avez donc
qu’à les configurer une seule fois).

-   Escalera ** **: muestra la curva como una
    escalera o visualización continua.

-   ** ** Cambio: muestra la diferencia en valor en comparación con
    punto anterior.

-   ** ** Línea: muestra el gráfico en filas.

-   **zona**: muestra la forma gráfica de un área.

-   Columna ** ** \ *: muestra el gráfico de barras.

Gráfico de vistas y diseños
=====================================

También puede ver los gráficos en las vistas (veremos aquí
opciones de configuración y no la forma de hacerlo, por lo que debemos
Ir a las imágenes o diseños en documention). aquí está
opciones:

Una vez activados los datos, se puede elegir:

-   ** ** color: el color de la curva.

-   **Tipo**: el tipo de gráfico (área, fila o columna).

-   ** ** Escala: porque se puede poner varias curvas (datos)
    en el mismo gráfico, es posible distinguir las escalas
    (Derecha o izquierda).

-   Escalera ** **: muestra la curva como una
    escalera o visualización continua

-   ** ** Pila: puede apilar los valores de las curvas (véase
    a continuación para los resultados).

-   ** ** Cambio: muestra la diferencia en valor en comparación con
    punto anterior.

Opción en la página Historial
===============================

La página de historial proporciona acceso a algunas opciones adicionales

historia calculada
------------------

Permet d’afficher une courbe en fonction d’un calcul sur plusieurs
commande (vous pouvez à peu prêt tout faire, +-/\* valeur absolue…​ voir
documentation PHP pour certaines fonctions). Ex :
abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de
vie\]\[Hygrométrie\]\[Température\]*)

También tiene acceso a una fórmulas de gestión que le permite
guardarlos para el remostrado más fácil

> **Tip**
>
> Basta con hacer clic en el nombre del objeto a desarrollarse;
> Aparecen los comandos que pueden ser historizar graphées.

historial de pedidos
----------------------

Delante de cada dato puede ser graphée, se encuentran dos iconos:

-   ** ** Papelera: Borra los datos grabados; entonces
    el clic Jeedom pregunta si desea eliminar los datos antes de una
    fecha determinada o todos los datos.

-   Flecha ** ** permite a un CSV exportación de datos históricos.

Eliminación de valor inconsistente
=================================

A veces puede ser que usted tiene valores inconsistentes en
gráficos. Esto es a menudo debido a la preocupación por la interpretación de la
valor. Puede eliminar o cambiar el valor del punto
cuestión haciendo clic en él directamente en el gráfico; de
Además, se puede establecer el mínimo y el máximo permitido para
para evitar problemas futuros.

línea de tiempo
========

La línea de tiempo muestra algunos eventos en su casa como la automatización
cronológico.

Para verlos, debe habilitar el seguimiento en la línea de tiempo de la primera
controles o escenarios apropiados:

-   ** ** Escenario: ya sea directamente en la página de la escritura, o en el
    page de résumé des scénarios pour le faire en "masse"

-   ** ** Orden ya sea en la configuración de control avanzada,
    ya sea en la historia de configuración para que sea a la "masa"

> **Tip**
>
> Vous avez accès aux fenêtres de résumé des scénarios ou de la
> configuration de l’historique directement à partir de la page de
> timeline.

Une fois que vous avez activé le suivi dans la timeline des commandes et
scénarios voulus, vous pourrez voir apparaître ceux-ci sur la timeline.

> **Importante**
>
> Los nuevos eventos después de la activación de la monitorización debe esperar
> En la línea de tiempo antes de ver el espectáculo.

Les cartes sur la timeline affichent :

-   ** ** cuota de comandos: fondo rojo, un icono de la derecha que permite
    mostrar la ventana de configuración extendida del control

-   ** ** Info orden: fondo azul, un icono de la derecha que permite
    mostrar la ventana de configuración extendida del control

-   ** ** Escenario: fondo gris, tiene 2 iconos: uno para la pantalla
    el registro de la escritura y para ir en el guión


