# Registros
**Análisis → Registros**

Los registros son archivos de log que permiten realizar un seguimiento de lo que ocurre en el sistema de domótica. En la mayoría de los casos, los registros solo servirán para la depuración y para que el equipo de soporte técnico resuelva problemas.

> **Consejo**
>
> Al abrir la página, se muestra el primer registro disponible.

La página de registros es bastante sencilla:
A la izquierda, una lista de los registros disponibles, con un campo de búsqueda para filtrar los nombres de los registros.
En la parte superior derecha hay 5 botones:

- **Buscar**: Permite filtrar la visualización del registro actual.
- **Pausar/Reanudar**: Permite pausar o reanudar la actualización en tiempo real del registro actual.
- **Descargar**: Permite descargar el registro actual.
- **Vaciar**: Permite vaciar el registro actual.
- **Eliminar**: Permite eliminar el registro actual. Si Jeedom lo necesita, lo volverá a crear automáticamente.
- **Eliminar todos los registros**: Elimina todos los registros existentes.

> **Consejo**
>
> Ten en cuenta que el registro http.error no se puede eliminar. Es fundamental que, si lo eliminas (por ejemplo, desde la línea de comandos), no se volverá a crear por sí solo, sino que habrá que reiniciar el sistema.

## En tiempo real

El registro «Event» es un poco especial. En primer lugar, para que funcione, debe estar en el nivel «info» o «debug»; además, recoge todos los eventos o acciones que tienen lugar en el sistema de domótica. Para acceder a él, hay que ir a la página de registros o a Análisis → Tiempo real.

Una vez que hayas hecho clic en él, aparecerá una ventana que se actualiza en tiempo real y te muestra todos los eventos de tu sistema de domótica.

En la esquina superior derecha hay un campo de búsqueda (solo funciona si no estás en pausa) y un botón para poner en pausa (útil, por ejemplo, para copiar y pegar).
