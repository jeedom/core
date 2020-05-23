# Motor de tareas
**Configuración → Sistema → Motor de tareas**

Esta página informa sobre todas las tareas de la aplicación Jeedom que se ejecutan en el servidor.
Esta página se debe utilizar a sabiendas o a pedido del soporte técnico.

> **Importante**
>
> En caso de mal manejo en esta página, cualquier solicitud de soporte puede ser rechazada.

## Pestaña Cron

En la parte superior derecha, tienes :

- **Deshabilitar sistema cron** : un botón para desactivar o reactivar todas las tareas (si las desactiva todas, nada funcionará en su Jeedom).
- **Fresco** : Actualiza la tabla de tareas.
- **Añadir** : Agregar un trabajo cron manualmente.
- **Guardar** : Guarda tus cambios.

A continuación, tiene la tabla de todas las tareas existentes (atención, algunas tareas pueden iniciar subtareas, por lo que se recomienda no modificar nunca la información en esta página)).

En esta tabla, encontramos :

- **\#** : ID de tarea, útil para vincular un proceso en ejecución con lo que realmente hace.
- **Bienes** : Indica si la tarea está activa (puede ser iniciada por Jeedom) o no.
- **PID** : Indica la ID del proceso actual.
- **Demonio** : Si este cuadro es &quot;sí&quot;, la tarea siempre debe estar en progreso. Además, encontrará la frecuencia del demonio, se recomienda no modificar nunca este valor y, especialmente, nunca disminuirlo.
- **Unico** : Si es &quot;sí&quot;, la tarea se iniciará una vez y luego se eliminará.
- **Clase** : Clase PHP llamada para ejecutar la tarea (puede estar vacía).
- **Función** : Función PHP llamada en la clase llamada (o no si la clase está vacía).
- **Programación** : Programación de la tarea en formato CRON.
- **Tiempo de espera** : Tiempo máximo de ejecución de la tarea. Si la tarea es un demonio, se detendrá y reiniciará automáticamente al final del tiempo de espera.
- **Último lanzamiento** : Fecha de lanzamiento de la última tarea.
- **Última duración** : Último tiempo de ejecución de la tarea (un demonio siempre estará en 0s, no te preocupes porque otras tareas pueden estar en 0s).
- **Estatus** : Estado actual de la tarea (como recordatorio, una tarea daemon siempre se "ejecuta"").

- **Acción** :
    - **Detalles** : Ver el cron en detalle (como se almacena en la base).
    - **Comenzar / Parar** : Iniciar o detener la tarea (dependiendo de su estado).
    - **Supresión** : Eliminar tarea.


## Pestaña Oyente

Los oyentes solo son visibles en la lectura y le permiten ver las funciones llamadas en un evento (actualización de un comando...).

## Pestaña demonio

Tabla resumen de los demonios con su estado, la fecha del último lanzamiento y la posibilidad de
- Iniciar / reiniciar un demonio.
- Detener un demonio si la gestión automática está desactivada.
- Habilitar / deshabilitar la gestión automática de un demonio.

> Tip
> Los demonios de complementos deshabilitados no aparecen en esta página.