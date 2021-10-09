# Tipos de equipo
**Herramientas → Tipos de equipos**

Los sensores y actuadores en Jeedom son administrados por complementos, que crean equipos con comandos *Información* (sensor) o *Acción* (solenoide). Esto luego permite activar acciones basadas en el cambio de ciertos sensores, como encender una luz en la detección de movimiento. Pero Jeedom Core y complementos como *Móvil*, *Homebridge*, *Google Smarthome*, *Alexa* etc., no sé qué es este equipo : Un enchufe, una luz, una persiana, etc.

Para superar este problema, especialmente con asistentes de voz (*Enciende la luz de la habitación*), Cote presentó el **Tipos genéricos**, utilizado por estos complementos.

La configuración de estos tipos genéricos se puede hacer directamente en ciertos complementos, o mediante comando en *Configuración avanzada* del mismo.

Esta página permite configurar estos Tipos Genéricos directamente, de forma más directa y sencilla, e incluso ofrece asignación automática una vez que los dispositivos han sido correctamente asignados.

![Tipos de equipo](./images/coreGenerics.gif)

## Tipo de equipo

Esta página ofrece almacenamiento por tipo de equipo : Enchufe, luz, obturador, termostato, cámara, etc. Inicialmente, la mayor parte de su equipo se clasificará en **Equipo sin tipo**. Para asignarles un tipo, puede moverlos a otro tipo o hacer clic con el botón derecho en el equipo para moverlo directamente.

> **Propina**
>
> - Cuando mueves equipo en el juego **Equipo sin tipo**, Jeedom sugiere que elimines los tipos genéricos de sus órdenes.
> - Puede mover varios equipos a la vez marcando las casillas de verificación a la izquierda de ellos.

## Tipo de comando

Una vez que un elemento de equipo se coloca en la posición correcta *Escribe*, al hacer clic en él se accede a la lista de sus pedidos, coloreados de diferente color si es un *Información* (Azul) o un *Acción* (Orange).

Al hacer clic con el botón derecho en un pedido, puede asignarle un Tipo genérico correspondiente a las especificaciones de este pedido (Tipo de información / acción, Numérico, Subtipo binario, etc).

> **Propina**
>
> - El menú contextual de comandos muestra el tipo de equipo en negrita, pero aún permite asignar cualquier tipo genérico de cualquier tipo de equipo.

En cada dispositivo, tienes dos botones :

- **Tipos de auto** : Esta función abre una ventana que le ofrece los tipos genéricos adecuados según el tipo de equipo, los detalles del pedido y su nombre. Luego puede ajustar las propuestas y desmarcar la aplicación para ciertos comandos antes de aceptar o no. Esta función es compatible con la selección mediante casillas de verificación.

- **Tipos de reinicio** : Esta función elimina los tipos genéricos de todos los comandos del equipo.

> **Atención**
>
> No se realizan cambios antes de guardar, con el botón en la parte superior derecha de la página.