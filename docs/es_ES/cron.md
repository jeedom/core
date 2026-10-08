# Motor de tareas
**Ajustes → Sistema → Motor de tareas**

Esta página ofrece información sobre todas las tareas de la aplicación Jeedom que se ejecutan en el servidor.
Esta página debe utilizarse con conocimiento de causa o a petición del servicio técnico.

> **Importante**
>
> En caso de uso incorrecto de esta página, se podrá denegar cualquier solicitud de asistencia.

## Pestaña Cron

Arriba, a la derecha, tienes:

- **Desactivar el sistema cron**: un botón para desactivar o reactivar todas las tareas (si las desactivas todas, nada funcionará en tu Jeedom).
- **Actualizar**: Actualiza la tabla de tareas.
- **Añadir**: Permite añadir una tarea cron manualmente.
- **Guardar**: Guarda los cambios realizados.

A continuación, encontrarás la tabla con todas las tareas existentes (atención: algunas tareas pueden activar subtareas, por lo que se recomienda encarecidamente no modificar nunca la información de esta página).

En esta tabla se incluyen:

- **\#**: ID de la tarea, útil para establecer la relación entre un proceso en ejecución y lo que realmente está haciendo.
- **Activo**: Indica si la tarea está activa (puede ser iniciada por Jeedom) o no.
- **PID**: Indica el identificador de proceso actual.
- **Servicio**: Si esta casilla está marcada como «sí», la tarea debe estar siempre en ejecución. Junto a ella aparece la frecuencia del servicio; se recomienda no modificar nunca este valor y, sobre todo, no reducirlo nunca.
- **Único**: Si se selecciona «Sí», la tarea se ejecutará una vez y luego se eliminará.
- **Clase**: Clase PHP que se invoca para ejecutar la tarea (puede estar vacía).
- **Función**: Función de PHP que se invoca en la clase en cuestión (o no, si la clase está vacía).
- **Programación**: Programación de la tarea en formato CRON.
- **Tiempo de espera**: tiempo máximo de ejecución de la tarea. Si la tarea es un demonio, se detendrá y se reiniciará automáticamente al finalizar el tiempo de espera.
- **Última ejecución**: Fecha de la última ejecución de la tarea.
- **Última duración**: Última duración de la ejecución de la tarea (un demonio siempre tendrá una duración de 0 s; no hay que preocuparse si otras tareas también tienen una duración de 0 s).
- **Estado**: Estado actual de la tarea (recuerde que una tarea de fondo siempre está en «ejecución»).

- **Acción**:
    - **Detalles**: Ver el cron en detalle (tal y como está almacenado en la base de datos).
    - **Iniciar / Detener**: Iniciar o detener la tarea (en función de su estado).
    - **Eliminar**: Permite eliminar la tarea.


## Pestaña «Listener»

Los «listeners» solo son visibles en modo de lectura y permiten ver las funciones que se invocan al producirse un evento (actualización de un comando...).

## Pestaña «Demonio»

Tabla con todos los demonios, su estado y la fecha de su último inicio, así como la posibilidad de:
- Iniciar / Reiniciar un demonio.
- Detener un demonio si la gestión automática está desactivada.
- Activar o desactivar la gestión automática de un demonio.

> Consejo
> Los demonios de los complementos desactivados no aparecen en esta página.
