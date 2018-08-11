Esta página le permite crear una vista 3D de su casa/apartamento que podrá reaccionar en función del estado de las diferentes informaciones de su domótica.

Es accesible a través de Inicio → Dashboard

> **Tip**
>
> Es posible ir directamente a un diseño 3D a través del submenú.

# Importación del modelo 3D

> **IMPORTANTE**
>
> No puedes crear tu modelo 3D directamente en Jeedom, tienes que hacerlo a través de un software de terceros. Recomendamos el software SweetHome3d

Una vez que haya creado su modelo 3D, debe exportarlo a OBJ. Si utiliza SweetHome3d esto se hace desde el menú "Ver 3d" y luego "Exportar a formato OBJ". Luego tienes que tomar todos los archivos generados y ponerlos en un zip (puede tener muchos archivos debido a las texturas)


> **ATENCIÓN**
>
> Un modelo 3d es bastante imponente (puede llegar a varios 100mo). Cuanto mayor sea el tamaño, mayor será el tiempo de renderizado en Jeedom.

Una vez exportado tu modelo 3d es necesario en Jeedom crear un nuevo diseño 3d. Para hacer esto, cambie al modo de edición haciendo clic sólo en el lápiz pequeño a la derecha, luego haga clic en el +, dé un nombre a este nuevo diseño 3D y valide.

Jeedom cambiará automáticamente al nuevo diseño 3d, tienes que volver al modo de edición y hacer clic en las pequeñas ruedas de estrella.

Desde esta pantalla puede enviar : 

- Cambiar el nombre de su diseño
- Añadir un código de acceso
- Seleccione un icono
- Importe su modelo 3D

Haga clic en el botón "enviar" en "Modelo 3D" y seleccione su zip

> **ATENCIÓN**
>
> Jeedom autoriza como máximo la importación de un archivo de 150mo

> **ATENCIÓN**
>
> Debe tener un archivo zip

> **Tip**
>
> Una vez realizada la importación del archivo (puede ser bastante largo dependiendo del tamaño del mismo), es necesario actualizar la página para ver el resultado.


# Configuración de elementos

> **IMPORTANTE**
>
> La configuración sólo se puede realizar en modo edición

Para configurar un elemento en el diseño 3D se hace doble clic sobre el elemento que se desea configurar. Esto le traerá una ventana donde pueda :

- Indique un tipo de enlace (actualmente sólo existe Dispositivo)
- El enlace al elemento en cuestión. Aquí sólo puede poner un enlace a un equipo por el momento. Esto permite al hacer clic en el elemento hacer que aparezca 
- La especificidad, hay varias que veremos a continuación, permite especificar el tipo de equipo y por lo tanto la visualización de la información.

## Iluminación

- Estado : El comando del estado de la luz puede ser binario (0 o 1), digital (0 a 100%) o en color.
- Potencia : potencia de la bombilla (tenga cuidado, esta puede no reflejarla)

## Texto

- Texto: texto a mostrar (puede poner comandos en él, el texto se actualizará automáticamente cuando cambie)
- Tamaño del texto
- Color del texto
- Transparencia del texto: de 0 (invisible) a 1 (visible)
- Color de fondo
- Transparencia del fondo: de 0 (invisible) a 1 (visible)
- Color del borde
- Transparencia del borde: 0 (invisible) a 1 (visible)
- Espaciado encima del objeto: permite especificar el espaciado del texto con respecto al elemento.

## Puerta/Ventana

### Puerta/Ventana

- Estado: Estado de puerta/ventana, 1 cerrado y 0 abierto
- Rotación
    - Habilitar: activa la rotación puerta/ventana al abrir
    - Apertura: la mejor manera es probarlo para asegurarse de que coincide con su puerta/ventana.
- Traslación
    - Activar: activa la traslación al abrir (tipo Puerta/Ventana corredera)
    - Dirección: dirección en la que debe moverse la puerta/ventana (usted tiene arriba/abajo/derecha/izquierda)
    - Repetir: por defecto la puerta/ventana se mueve 1 vez su dimensión en la dirección indicada, pero se puede aumentar este valor.
- Ocultar cuando la puerta/ventana está abierta
    - Habilitar: Oculta el elemento si la puerta/ventana está abierta.
- Color
    - Color apertura: si la casilla está marcada, el elemento tomará este color si la puerta/ventana está abierta.
    - Color cerrado: si selecciona esta opción, el elemento tomará este color si la puerta/ventana está cerrada.

### Persiana

- Estado: estado de la compuerta, 0 abierto otro valor cerrado
- Ocultar cuando la persiana está abierta
    -  Habilitar: oculta el elemento si la persiana está abierta
- Color
    - Color cerrado: si se selecciona esta opción, el elemento tomará este color si el obturador está cerrado.

## Color Condicional

Se usa para dar el color seleccionado al elemento si la condición es válida. Puedes poner tanto color/condición como quieras.

> **Tip**
>
> Las condiciones son evaluadas en orden, la primera que es verdadera será tomada, así que lo siguiente no será evaluada