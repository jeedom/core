# Sustituir

## ¿Para qué sirve una herramienta así?

![1](../images/replace1.png)

Jeedom ofrece, desde la versión 4.3.2, una nueva herramienta <kbd>Sustituir</kbd> que, en caso de que surja algún problema o sea necesario sustituir un equipo físico o virtual (un sensor de temperatura, de presencia, un control de volumen, un nivel de agua, etc.), se encargará de transferir todos los comandos, la información, los parámetros avanzados y el historial de dicho equipo a un nuevo equipo.<br>
También se encargará de sustituir el ID del equipo antiguo por el nuevo en todos los escenarios, diseños, entornos virtuales, etc., en los que se hiciera referencia a él.

De hecho, si se elimina el equipo antiguo, la referencia a su número de identificación original se borrará definitivamente. En ese caso, habrá que volver a crear todos los controles e integrarlos de nuevo en todos los diseños, widgets, etc., para el nuevo módulo, incluso si este es exactamente del mismo tipo que el original, o incluso el mismo pero con un número de identificación diferente.<br>
Además, antes de eliminar cualquier dispositivo, Jeedom te avisará de las consecuencias de dicha eliminación en una ventana de alerta:

![2](../images/replace2.png)

En este caso, la eliminación de este sensor de vibraciones provocará:

- La eliminación de los elementos de visualización definidos en el diseño «Alarmas de zonas»,
- La eliminación de la información sobre vibraciones, el nivel de batería y la fecha de la última comunicación, incluso en lo que respecta a los historiales,
- Eliminación del equipo en el escenario «Alarma de detección de intrusos».

Y, en el momento en que este equipo se elimine definitivamente, se sustituirá en todas estas entidades por su antiguo número de identificación, o por un campo en blanco en lugar de su denominación original:

![3](../images/replace3.png)
<br><br>

## Operaciones que hay que realizar antes de utilizar esta herramienta

Aunque la herramienta <kbd>Sustituir</kbd> te propondrá realizar una copia de seguridad preventiva; se recomienda encarecidamente hacerla antes de iniciar este procedimiento de sustitución.<br>
Ten en cuenta que esta herramienta es realmente potente, ya que va a realizar las sustituciones a todos los niveles, incluso en aquellos en los que no habías pensado o que simplemente se te habían olvidado. Además, no existe la función *undo* para anular o dar marcha atrás.<br><br>

El siguiente paso será cambiar el nombre del equipo antiguo. Para ello, basta con cambiarle el nombre, añadiéndole, por ejemplo, el sufijo «**_old**».

![4](../images/replace4.png)
<br>

No te olvides de guardar.
<br>

A continuación, hay que añadir el nuevo dispositivo si se trata de un dispositivo físico, o crear el nuevo dispositivo virtual, siguiendo el procedimiento estándar propio de cada complemento.
Este dispositivo recibirá su nombre definitivo, y a continuación se definirán el objeto principal y su categoría antes de activarlo.
<br>
De este modo, se obtienen dos equipos:

- El equipo antiguo, que quizá ya no exista físicamente, pero que sigue figurando en todas las estructuras de Jeedom con su historial,
- Y el nuevo equipo, en el que habrá que copiar los historiales y dar de alta en su lugar del antiguo.
<br>

![5](../images/replace5.png)
<br><br>

## El uso de la herramienta <kbd>Sustituir</kbd>

Abrir la herramienta <kbd>Sustituir</kbd>, en el menú <kbd>Herramientas</kbd>.

![6](../images/replace6.png)
<br>

En el campo *Objeto*, selecciona el objeto o los objetos principales.

![7](../images/replace7.png)
<br>

En las opciones, selecciona el modo deseado (*Reemplazar* o *Copiar*) en el menú desplegable y, según sea necesario, las siguientes opciones (que están desmarcadas por defecto), como mínimo:

- Copiar la configuración del equipo de origen,
- Copiar la configuración del comando de origen.
<br>

![8](../images/replace8.png)
<br>

A continuación, haz clic en <kbd>Filtrar</kbd>

![9](../images/replace9.png)
<br>

En el campo *Sustituciones* aparecen todas las entidades relacionadas con el objeto principal:

![10](../images/replace10.png)
<br>

Marca el equipo de origen (renombrado como «**_old**»), es decir, aquel del que deseas copiar los comandos, la información, el historial…
En este caso, el equipo de origen será: [Habitación de invitados][Temperatura_habitación_antigua] (767 | z2m).<br>
Haz clic en la línea para que aparezcan los distintos campos asociados.

![11](../images/replace11.png)
<br>

En la sección *Destino* de la derecha, despliega la lista y selecciona el nuevo dispositivo que lo va a sustituir, es decir, [Habitación de invitados][Temperatura de la habitación] en nuestro ejemplo.

![12](../images/replace12.png)
<br>

En los menús desplegables que aparecen a continuación a la derecha, la información se muestra sobre fondo azul y las acciones sobre fondo naranja (a continuación se muestra otro ejemplo de una luminaria en la que hay acciones e información).

![13](../images/replace13.png)
<br>

Y si existe una coincidencia directa (en concreto, el mismo nombre), los distintos parámetros se definirán automáticamente.

![14](../images/replace14.png)
<br>

Aquí, todo se reconoce automáticamente.
De lo contrario, el campo quedará vacío y habrá que seleccionar manualmente en la lista desplegable la información o acción correspondiente, si procede.

![15](../images/replace15.png)
<br>

Haz clic en <kbd>Sustituir</kbd>,

![16](../images/replace16.png)
<br>

Confirma la sustitución, comprobando que se haya realizado previamente una copia de seguridad (¡atención, no es posible dar marcha atrás!).

![17](../images/replace17.png)
<br>

De hecho, la herramienta te lo propondrá en este paso. Pero si sales de esta función para realizar esta copia de seguridad en ese momento, también perderás todos los ajustes ya realizados, de ahí que sea recomendable hacer esta copia de seguridad desde el principio del procedimiento.<br><br>

Tras enviar el pedido, tras una breve espera aparecerá una ventana emergente de aviso que indicará que el proceso se ha realizado correctamente.<br><br>

## Las comprobaciones

Asegúrate de que el nuevo equipo se haya tenido en cuenta en los diseños, escenarios, widgets, elementos virtuales, complementos, etc., con su configuración (disposición, visualización, asignación de widgets, etc.) y, en su caso, el historial asociado.

![18](../images/replace18.png)
<br>

Para comprobar que no se ha producido ningún problema adicional tras esta sustitución, se puede utilizar la función de detección de comandos huérfanos.
Ir a <kbd>Análisis</kbd>, <kbd>Equipos</kbd>, haz clic en la pestaña *Comandos huérfanos*.

![19](../images/replace19.png)
<br>

![20](../images/replace20.png)
<br>

Si todo ha ido bien, este informe no debería contener ninguna línea.
 
![21](../images/replace21.png)
<br>

De lo contrario, habrá que realizar un análisis línea por línea de cada problema detectado para solucionarlo.

![22](../images/replace22.png)
<br>

Pero si la herramienta no tiene en cuenta los comandos huérfanos <kbd>Sustituir</kbd>, aun así es posible realizar sustituciones con esta función <kbd>Este comando sustituye al ID</kbd> que se encuentra aquí, en la ventana de configuración del mando:

![23](../images/replace23.png)
<br><br>

## Finalización

Si todo está correcto, el equipo antiguo (T°Habitación_antigua en el ejemplo) puede eliminarse definitivamente. No debe aparecer ninguna referencia en la ventana emergente de advertencia durante la eliminación, salvo los comandos intrínsecos a dicho equipo.

![24](../images/replace24.png)
<br>

En este caso, este dispositivo ya solo se identifica por el objeto al que pertenece y por sus propios comandos, lo cual es normal. Por lo tanto, podemos eliminarlo sin remordimientos.<br><br>

## Conclusión

Esta herramienta es práctica, pero también resulta peligrosa si se utiliza incorrectamente debido a sus implicaciones a varios niveles.<br>
Además, ten muy en cuenta estos aspectos fundamentales:

- Realiza siempre una copia de seguridad por precaución, incluso antes de utilizar la herramienta. <kbd>Sustituir</kbd>,
- Una vez ejecutado este comando, no es posible cancelarlo ni revertirlo,
- Y, por último, es muy recomendable familiarizarse, al menos en lo básico, con el uso de esta herramienta.
