Se informa a todas las tareas de aplicaciones que se ejecutan en el Jeedom
servidor. Este menú es utilizar a sabiendas, o
solicitar soporte técnico.

> **Importante**
>
> En caso de mal manejo de esta página, cualquier solicitud
> Soporte puede ser negado.

Para llegar a ella hay que ir a Administración → ** ** tareas motoras
:

En la parte superior derecha, usted tiene:

-   **Desactivar el sistema cron**: un botón para desactivar o
    reactivar todas las tareas (si deshabilita todo
    nada va a ser funcional en su Jeedom)

-   ** ** Actualizar: un botón para actualizar la tabla de tareas

-   ** ** Añadir: un botón para agregar una tarea programada

-   ** ** Guardar botón para guardar los cambios.

A continuación tiene la imagen de todas las tareas existentes
(Tenga cuidado con algunas tareas pueden lanzar sub-tareas, por lo que es
muy recomendable no cambiar nunca información sobre este
página). En esta tabla son:

-   **\ #**: Identificación de la tarea, puede ser útil para establecer el vínculo entre una
    proceso en ejecución y lo que realmente hace

-   **Acción:** un botón para iniciar o detener la tarea basada
    su estado y un botón para ver el cron en los detalles (como se almacena en la base)

-   ** ** activo: si la tarea está activa (se puede lanzar
    por Jeedom) o no

-   PID ** ** indica el ID del proceso actual

-   Demonio ** **: Si esta caja es "sí", entonces la tarea debe siempre
    estar en curso. Además de encontrar la frecuencia del diablo, y es
    aconsejó no tocar este valor y, sobre todo, nunca se
    disminuir

-   Única ** ** Si es "sí", entonces la tarea se ejecutará una vez
    a continuación, quitar

-   ** ** Clase: clase PHP llamada para realizar la tarea (puede
    estar vacío)

-   ** ** Función: función de PHP llamada en la clase llamada (o no
    si la clase está vacía)

-   ** ** Programación: Programar el tamaño CRON tarea

-   ** ** Tiempo de espera: tiempo máximo de funcionamiento de la tarea. Si la
    tarea es un demonio entonces se detiene automáticamente y
    renovadas en el extremo del tiempo de espera

-   ** ** Último lanzamiento: Fecha de la última ejecución de la tarea

-   ** ** La última vez: la última vez para realizar la tarea (una
    diablo siempre 0s, por lo que no se preocupe por otras tareas
    puede ser 0s)

-   ** ** Estado: estado actual de la tarea (recuerde una tarea demonio
    siempre es "ejecutar")

-   ** ** Eliminar: Eliminar la tarea


