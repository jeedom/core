# Logs
**Análisis → Registros**

Los registros son archivos de registro, lo que le permite seguir lo que sucede en la automatización de su hogar. En la mayoría de los casos, el equipo de soporte solo utilizará los registros para depurar y resolver problemas.

> **Punta**
>
> Cuando se abre la página, se muestra el primer registro disponible.

La página de registros es bastante simple :
A la izquierda, una lista de registros disponibles, con un campo de búsqueda para filtrar el nombre de los registros.
Arriba a la derecha 5 botones :

- **Buscar** : Le permite filtrar la visualización del registro actual.
- **Pausa / Reanudar** : Pausa / reanuda la actualización en tiempo real del registro actual.
- **Descargar** : Permite descargar el registro actual.
- **Vaciar** : Permite vaciar el registro actual.
- **Remove** : Eliminar el registro actual. Si Jeedom lo necesita, lo recreará automáticamente.
- **Eliminar todos los registros** : Eliminar todos los registros presentes.

> **Punta**
>
> Tenga en cuenta que el registro http.el error no se puede eliminar. Es esencial que si lo elimina (en la línea de comando, por ejemplo) no se volverá a crear, debe reiniciar el sistema.

## Tiempo real

El registro &quot;Evento&quot; es un poco especial. En primer lugar, para que funcione, debe estar en el nivel de información o depuración, luego enumera todos los eventos o acciones que ocurren en la automatización del hogar. Para acceder a él, debe ir a la página de registro o en Análisis → Tiempo real.

Una vez que hace clic en él, obtiene una ventana que se actualiza en tiempo real y le muestra todos los eventos de su domótica.

En la parte superior derecha tiene un campo de búsqueda (solo funciona si no está en pausa) y un botón para pausar (útil para hacer una copia / pegar, por ejemplo).
