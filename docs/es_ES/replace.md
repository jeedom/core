 # Remplacer

## ¿Por qué tal herramienta? ?

![1](../images/replace1.png)

Jeedom ha estado ofreciendo desde la versión 4.3.2 una nueva herramienta <kbd>Reemplazar</kbd> que, en caso de problema o necesidad de reemplazar un equipo físico o virtual (un sensor de temperatura o presencia, un control de volumen, un nivel de agua, etc.), garantizará la copia de todos los comandos, informaciones, Parámetros avanzados e historial de este equipo a equipos nuevos.<br>
También será responsable de sustituir el ID del antiguo equipo por el nuevo en todos los escenarios, diseños, virtuales, etc. que le hagan referencia.

De hecho, si se elimina el equipo antiguo, la referencia a su número de identificación original se borrará permanentemente. Entonces será necesario recrear todos los comandos y reintegrarlos en todos los diseños, widgets, etc. del nuevo módulo, incluso si es estrictamente del mismo tipo que el original, o incluso el mismo pero con un número de ID diferente.<br>
Además, antes de cualquier eliminación de equipo, Jeedom avisará de las consecuencias de dicha eliminación en una ventana de alerta :

![2](../images/replace2.png)

Aquí, quitar este sensor de vibración causará :

- Eliminación de las visualizaciones definidas en el diseño 'Zonas de alarmas'',
- Eliminación de información de vibración, nivel de batería y fecha de la última comunicación, incluido el historial,
- La suppression de l'équipement dans le scénario ‘Alarme détection intru'.

Y a partir del momento en que este equipo sea eliminado definitivamente, será sustituido en todas estas entidades por su antiguo número de identificación, o un campo vacío en lugar de su nombre original :

![3](../images/replace3.png)
<br><br>

## Operaciones a realizar antes de utilizar esta herramienta

Incluso si la herramienta <kbd>Reemplazar</kbd> le sugerirá que primero haga una copia de seguridad preventiva, se recomienda encarecidamente hacer una antes de comenzar este procedimiento de reemplazo.<br>
Tenga en cuenta que esta herramienta es realmente poderosa porque realizará reemplazos en todos los niveles, incluidos aquellos en los que no había pensado o simplemente había olvidado. Además, no hay ninguna función *deshacer* cancelar o volver.<br><br>

La siguiente fase será el cambio de nombre del antiguo equipo. Para ello basta con cambiarle el nombre, añadiendo el sufijo '**_viejo**' por ejemplo.

![4](../images/replace4.png)
<br>

No olvides guardar.
<br>

Luego deberás realizar la inclusión del nuevo equipo si es físico, o la creación del nuevo equipo virtual, siguiendo el procedimiento estándar específico de cada plugin.
Este equipo será nombrado con su nombre final, luego el objeto padre y su categoría definida antes de activarlo. 
<br>
Obtenemos así dos equipos :

- Equipo antiguo, que puede que ya no exista físicamente, pero que, sin embargo, permanece referenciado en todas las estructuras de Jeedom con sus historias,
- Y el nuevo equipo, en el que será necesario copiar los historiales y referenciarlos en lugar del antiguo.
<br>

![5](../images/replace5.png)
<br><br>

## Usando la herramienta <kbd>Reemplazar</kbd>>

Abra la herramienta <kbd>Reemplazar</kbd> , en el menú <kbd>Herramientas</kbd>>.

![6](../images/replace6.png)
<br>

En el campo *Objeto*, seleccionar objeto(s) padre(s).

![7](../images/replace7.png)
<br>

En las opciones, seleccione el modo deseado (*Reemplazar* O *Copiar*) en la lista desplegable, y dependiendo de las necesidades, las siguientes opciones (que están todas desmarcadas de forma predeterminada), o al menos :

- Copiar la configuración del dispositivo de origen,
- Copie la configuración del comando fuente.
<br>

![8](../images/replace8.png)
<br>

Luego haga clic en <kbd>Filtrar</kbd>>

![9](../images/replace9.png)
<br>

En el campo *Reemplazos*, aparecen todas las entidades relacionadas con el objeto principal :

![10](../images/replace10.png)
<br>

Verifique el dispositivo de origen (renombrado a '**_viejo**'), es decir aquel del que deseamos copiar las órdenes, información, historial, etc
En este caso, el equipo fuente será, por tanto, : [Habitación de un amigo][T°Chambre_old](767 | z2m).<br>
Haga clic en la línea para mostrar los diferentes campos relacionados.

![11](../images/replace11.png)
<br>

En el juego *Objetivo* a la derecha, desplácese hacia abajo en la lista y seleccione el nuevo equipo que lo reemplazará, es decir [Habitación de invitados][Temperatura ambiente] en nuestro ejemplo.

![12](../images/replace12.png)
<br>

En las listas desplegables que se muestran a continuación a la derecha, la información se presenta sobre un fondo azul, las acciones sobre un fondo naranja (abajo otro ejemplo de una luminaria donde hay acciones e información).

![13](../images/replace13.png)
<br>

Y si hay una coincidencia directa (mismo nombre en particular), los diferentes parámetros se establecerán automáticamente.

![14](../images/replace14.png)
<br>

Aquí todo se reconoce automáticamente.
De lo contrario, el campo estará vacío y tendrá que seleccionar manualmente la información/acción correspondiente de la lista desplegable, si es relevante.

![15](../images/replace15.png)
<br>

Haga clic en <kbd>Reemplazar</kbd>>,

![16](../images/replace16.png)
<br>

Validar la reposición, comprobando que se ha realizado una copia de seguridad antes (ojo, no hay vuelta atrás) !).

![17](../images/replace17.png)
<br>

Además, la herramienta te lo sugerirá en esta etapa. Pero al salir de esta función para realizar esta copia de seguridad en este momento, también abandonarás todas las configuraciones ya realizadas, de ahí el interés de realizar esta copia de seguridad desde el inicio del procedimiento.<br><br>

Después de ejecutar el comando, después de una breve espera, aparecerá una ventana emergente de alerta que indicará la finalización exitosa del procedimiento.<br><br>

## Cheques

Asegúrese de que se haya tenido en cuenta el nuevo equipo en los diseños, escenarios, widgets, virtuales, plug-ins, etc. con su configuración (layout, display, asignación de widgets, etc.), y (si aplica) los asociados. historia.

![18](../images/replace18.png)
<br>

Para verificar adecuadamente que no se hayan generado problemas adicionales después de este reemplazo, es posible utilizar la función de detección de comandos huérfanos.
Vaya a <kbd>Análisis</kbd> , <kbd>Equipo</kbd> , haga clic en la pestaña *Comandos huérfanos*.

![19](../images/replace19.png)
<br>

![20](../images/replace20.png)
<br>

Si todo salió bien, no debería haber líneas presentes en este informe.
 
![21](../images/replace21.png)
<br>

En caso contrario, será necesario realizar un análisis línea por línea de cada problema identificado para solucionarlo.

![22](../images/replace22.png)
<br>

Pero si la herramienta <kbd>Reemplazar</kbd> no tiene en cuenta los comandos huérfanos, aún es posible realizar reemplazos con esta función. <kbd>Este comando reemplaza la ID</kbd> que se encuentra aquí en la ventana de configuración de comandos :

![23](../images/replace23.png)
<br><br>

## Finalisation

Si todo es correcto, el equipo antiguo (T°Chambre_old en el ejemplo) se puede eliminar definitivamente. No deberían aparecer más referencias en la ventana emergente de advertencia durante la eliminación, excepto los comandos intrínsecos a este equipo.

![24](../images/replace24.png)
<br>

Aquí, este equipo sólo está referenciado por su objeto de pertenencia y sus propios comandos, lo cual es normal. Por lo tanto, podemos eliminarlo sin arrepentirnos.<br><br>

## Conclusion

Esta herramienta es práctica, pero igualmente peligrosa si se utiliza mal debido a sus implicaciones en múltiples niveles.<br>
Además, tenga en cuenta estos fundamentos :

- Realice sistemáticamente una copia de seguridad preventiva, incluso antes de utilizar la herramienta <kbd>Reemplazar</kbd>>,
- No es posible deshacer ni revertir después de ejecutar este comando,
- Y por último, se recomienda encarecidamente familiarizarse al menos con el uso de esta herramienta.
