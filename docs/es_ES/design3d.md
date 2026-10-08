# Diseño en 3D
**Inicio → Design3D**

Esta página te permite crear una vista en 3D de tu vivienda que podrá reaccionar en función del estado de los distintos datos de tu sistema de domótica.


> **Consejo**
>
> Es posible acceder directamente a un diseño en 3D a través del submenú.

## Importación del modelo 3D

> **IMPORTANTE**
>
> No puedes crear tu modelo 3D directamente en Jeedom, tienes que hacerlo mediante un programa de terceros. Te recomendamos el excelente SweetHome3d (http://www.sweethome3d.com/fr/).

Una vez creado tu modelo 3D, debes exportarlo en formato OBJ. Si utilizas SweetHome3D, esto se hace desde el menú «Vista 3D» y, a continuación, «Exportar a formato OBJ». A continuación, debes reunir todos los archivos generados y guardarlos en un archivo zip (puede que haya muchos archivos debido a las texturas).

> **IMPORTANTE**
>
> Los archivos deben estar en la carpeta raíz del archivo zip y no en una subcarpeta.

> **ATENCIÓN**
>
> Un modelo 3D ocupa bastante espacio (puede llegar a tener varios cientos de MB). Cuanto más grande sea, más tardará en renderizarse en Jeedom.

Una vez exportado tu modelo 3D, en Jeedom debes crear un nuevo diseño 3D. Para ello, debes pasar al modo de edición haciendo clic en el pequeño lápiz situado a la derecha; a continuación, haz clic en el signo «+», asigna un nombre a este nuevo diseño 3D y confirma.

Jeedom cambiará automáticamente al nuevo diseño en 3D; hay que volver al modo de edición y hacer clic en las ruedecillas dentadas.

Desde esta pantalla puedes:

- Cambiar el nombre de tu diseño
- Añadir un código de acceso
- Elegir un icono
- Importar tu modelo 3D

Haz clic en el botón «Enviar» junto a «Modelo 3D» y selecciona tu archivo zip.

> **ATENCIÓN**
>
> ¡Jeedom permite importar un archivo de hasta 150 MB!

> **ATENCIÓN**
>
> Es obligatorio que el archivo esté en formato zip.

> **Consejo**
>
> Una vez importado el archivo (esto puede tardar bastante, dependiendo de su tamaño), debes actualizar la página para ver el resultado (F5).


## Configuración de los elementos

> **IMPORTANTE**
>
> La configuración solo se puede realizar en modo de edición.

Para configurar un elemento del diseño en 3D, haz doble clic en el elemento que quieras configurar. Se abrirá una ventana en la que podrás:

- Indica un tipo de relación (actualmente solo existe «Equipo»)
- Introduce el enlace al elemento en cuestión. Por el momento, aquí solo puedes introducir un enlace a un dispositivo. De este modo, al hacer clic en el elemento, aparecerá el dispositivo.
- Definir la especificidad: existen varias, que veremos a continuación; esto permite especificar el tipo de equipo y, por lo tanto, la información que se muestra.

### Iluminación

- Estado: El comando de estado de la luz puede ser binario (0 o 1), numérico (del 0 al 100 %) o de color
- Potencia: potencia de la bombilla (atención: puede que no refleje la realidad)

### Texto

- Texto: texto que se va a mostrar (puedes incluir comandos; el texto se actualizará automáticamente cuando estos cambien)
- Tamaño del texto
- Color del texto
- Transparencia del texto: de 0 (invisible) a 1 (visible)
- Color de fondo
- Transparencia del fondo: de 0 (invisible) a 1 (visible)
- Color del borde
- Transparencia del borde: de 0 (invisible) a 1 (visible)
- Espaciado por encima del objeto: permite indicar el espaciado del texto con respecto al objeto

### Puertas y ventanas

#### Puertas y ventanas

- Estado: estado de la puerta/ventana, 1 cerrado y 0 abierto
- Rotación
	- Activar: activa la rotación de la puerta/ventana al abrirla
	- Apertura: lo mejor es probarlo para asegurarte de que se adapta a tu puerta o ventana.
- Traducción
	- Activar: activa el desplazamiento al abrirse (tipo puerta/ventana corredera)
	- Sentido: sentido en el que debe moverse la puerta o ventana (puedes elegir entre arriba/abajo/derecha/izquierda)
	- Repetición: por defecto, la puerta o ventana se desplaza una vez su dimensión en la dirección indicada, pero puedes aumentar este valor
- Ocultar cuando la puerta o ventana esté abierta
	- Activar: Oculta el elemento si la puerta o ventana está abierta
- Color
	- Color de «abierto»: si se marca esta casilla, el elemento adoptará este color cuando la puerta o ventana esté abierta
	- Color cuando está cerrada: si se marca esta casilla, el elemento adoptará este color cuando la puerta o ventana esté cerrada

#### Persianas

- Estado: estado de la persiana, 0 = abierta y cualquier otro valor = cerrada
- Ocultar cuando la persiana está abierta
	- Activar: oculta el elemento si la persiana está abierta
- Color
	- Color cuando está cerrado: si se marca esta casilla, el elemento adoptará este color cuando la persiana esté cerrada

### Color condicional

Si la condición es válida, permite asignar el color elegido al elemento. Puedes establecer tantos colores y condiciones como quieras.

> **Consejo**
>
> Las condiciones se evalúan por orden; se tomará la primera que sea verdadera, por lo que las siguientes no se evaluarán.
