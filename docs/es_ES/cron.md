Informa de todas las tareas de la aplicación Jeedom que se ejecutan en
servidor. Este menú se debe utilizar a sabiendas o en el
solicitar soporte técnico.

> **Important**
>
> En caso de mal manejo en esta página, cualquier solicitud de
> el apoyo puede ser denegado.

Para acceder, ve a **Administración → Motor de tareas**
:

# Cron

En la parte superior derecha, tienes :

-   **Deshabilitar sistema cron** : un botón para desactivar o
    Vuelva a habilitar todas las tareas (si las deshabilita todas, más
    nada funcionará en tu Jeedom)

-   **Fresco** : un botón para actualizar la tabla de tareas

-   **Ajouter** : un botón para agregar un trabajo cron

-   **Enregistrer** : un botón para guardar sus cambios.

A continuación tiene la tabla de todas las tareas existentes.
(tenga cuidado, algunas tareas pueden iniciar subtareas, por lo que es
Se recomienda encarecidamente nunca modificar la información sobre este
página). En esta tabla, encontramos :

-   **\#** : ID de tarea, puede ser útil para vincular un
    proceso que se está ejecutando y lo que realmente hace

-   **Action** : un botón para iniciar o detener la tarea en función
    su estado y un botón para ver el cron en detalle (como está almacenado en la base de datos)

-   **Actif** : indica si la tarea está activa (se puede iniciar
    por Jeedom) o no

-   **PID** : indica la ID del proceso actual

-   **Demonio** : si este cuadro es &quot;sí&quot;, la tarea siempre debe
    estar en curso. Luego, encuentras la frecuencia del demonio, es
    aconseja nunca tocar este valor y especialmente nunca
    disminuirlo

-   **Unique** : si es &quot;sí&quot;, la tarea se iniciará una vez
    luego borrará

-   **Classe** : Clase PHP llamada para ejecutar la tarea (puede
    estar vacío)

-   **Fonction** : Función PHP llamada en la clase llamada (o no
    si la clase esta vacia)

-   **Programmation** : programar la tarea en formato CRON

-   **Timeout** : tiempo máximo de ejecución de la tarea. Si la
    la tarea es un demonio, entonces se detendrá automáticamente y
    reiniciado al final del tiempo de espera

-   **Último lanzamiento** : fecha de lanzamiento de la última tarea

-   **Última duración** : última vez para completar la tarea (un
    demonio siempre estará a 0s, así que no te preocupes por otras tareas
    puede ser 0s)

-   **Statut** : estado actual de la tarea (como recordatorio, una tarea de demonio
    todavía está &quot;ejecutado&quot;)

-   **Suppression** : eliminar tarea


# Listener

Los oyentes solo son visibles en la lectura y le permiten ver las funciones llamadas en un evento (actualización de un pedido ...)
