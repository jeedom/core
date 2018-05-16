El submenú Administración de Plugin permite manipular a los plugins
a saber: descargar, actualizar y activar ...

Plugin de gestión
===================

Puede acceder a la página de complementos de gestión de plugins →
plugins. Una vez que se hace clic, la lista de encontrar
plugins en orden alfabético y un enlace para el mercado. plugins
personas con discapacidad se atenúan.

> **Tip**
>
> Al igual que en muchos lugares en Jeedom, poner el ratón mientras la izquierda
> Para que aparezca un menú de acceso rápido (se puede
> Su perfil en dejar la siempre visible). A continuación, el menú
> Para obtener la lista de plugins ordenados por categorías.

Al hacer clic en un plugin de acceso de configuración. por encima de usted
encontrar el nombre del plugin y los soportes nombre en Jeedom
(ID) y, finalmente, el tipo versión instalada (estable, beta).

> **Importante**
>
> Al descargar un plugin que está desactivado por defecto.
> Esto requiere que se activa por sí mismo.

Arriba a la derecha, un par de botones:

-   ** ** Documentación: Permite el acceso directo a la página
    documentación para plugins

-   ** ** Cambios: Para ver el plugin si hay cambios

-   ** ** Enviar en el mercado: enviar el plugin en el mercado
    (Sólo está disponible si usted es el autor)

-   ** ** Detalles: recupera la página del plugin en el mercado

-   ** ** Eliminar: Eliminar el plugin su Jeedom. Tenga en cuenta que
    También elimina de forma permanente todas las instalaciones de este plugin

Abajo a la izquierda nos encontramos con un área de estado con:

-   ** ** Estado: muestra el estado del plugin (activo / inactivo)

-   ** ** Versión: la versión del plug-in instalado

-   ** ** Autor: El autor del plugin

-   ** ** Acción: Activar o desactivar el plugin

-   ** ** jeedom Versión: Indica la versión del mínimo requerido Jeedom
    para el plugin

-   ** ** Licencia: Indica la licencia plugin para ser generalmente
    AGPL

A la derecha se encuentra el área de registro que define el nivel de registro
complemento específico (nos encontramos con esta misma oportunidad de
Administation → Configuración de la ficha de registro en la parte inferior de la página).

Si el plugin tiene dependencias y / o demonio estas áreas
Adicionales aparecen en las áreas mencionadas anteriormente.

dependencias:

-   **Nombre:** generalmente ser local

-   ** ** Estado: dirá si las dependencias son OK o KO

-   ** ** Instalación: se instalar o reinstalar
    dependencias (si no lo hace de forma manual y está
    KO Jeedom se carga en sí mismo después de un tiempo)

-   ** ** Instalación Último: fecha de la última instalación
    dependencias

demonio:

-   **Nombre:** generalmente ser local

-   ** ** Estado: le diga si el diablo está OK o KO

-   ** ** Configuración va a estar bien si todos los criterios para el diablo
    correr juntos o dar a la causa de la obstrucción

-   **(Re) Inicio**: para iniciar o reiniciar el demonio

-   ** ** Detener: Detener el demonio (sólo en el caso
    gestión automática está desactivada)

-   ** ** Gestión automática: Activar o desactivar la administración
    automática (permitiendo Jeedom administrar a sí mismo y el diablo
    Si necesita aumentar salvo en contra de la indicación se recomienda
    dejar que la gestión automática activa)

-   ** ** Último lanzamiento: fecha de la última puesta en marcha del diablo

> **Tip**
>
> Algunos plugins tienen una sección de configuración. Si ese es el caso,
> Aparecen en las dependencias y áreas demonio descritos anteriormente.
> En este caso, es necesario hacer referencia a la documentación del plugin
> Pregunta para saber cómo configurarlo.

A continuación se encuentra una zona de características. Esto le permite ver
si el plugin utiliza una de las funciones básicas Jeedom como:

-   Interactuar ** **: interacciones específicas

-   Cron ** ** Minuto cron

-   ** ** Cron5: cron cada 5 minutos

-   ** ** Cron15: cada 15 minutos

-   ** ** Cron30: cada 30 minutos

-   ** ** CronHourly cada hora

-   ** ** CronDaily: diario

> **Tip**
>
> Si el plugin utiliza una de estas funciones. Puede específicamente
> Prohibirle hacerlo desmarcando "Activar" para ser
> Presente en el exterior.

Por último podemos encontrar una sección de panel que permitirá o
desactivar el panel de la pantalla en el salpicadero o móvil si
ofertas plugin.

La instalación de un plug-in
========================

Para instalar un nuevo plugin simplemente haga clic en el botón
"Mercado" (y Jeedom está conectado a Internet). Después de un breve periodo de tiempo
cargar obtendrá la página.

> **Tip**
>
> Debe haber introducido la información de su cuenta en el mercado
> Administración (Configuración → → pestaña Actualizaciones de mercado) a
> Encuentra los plugins que ya ha adquirido tal.

En la parte superior de la ventana que tiene filtros:

-   **libre / Pago**: sólo muestra los libres o
    pagar.

-   Oficial **/** recomendados: Muestra sólo los plugins
    oficial o recomendadas

-   Ubicado **/** no se instala: Muestra sólo los plugins
    instalado o no instalado

-   ** ** Categoría Hover: muestra solamente
    ciertas categorías de plugins

-   ** ** Buscar: Búsquedas un plugin (en el nombre o
    descripción de la misma)

-   ** ** Nombre de usuario: Muestra el nombre de usuario utilizado para
    conexión con el mercado y el estado de conexión

> **Tip**
>
> La pequeña cruz restablece el filtro en cuestión

Una vez que haya encontrado el plugin deseado, basta con hacer clic en
una para mostrar su página. Esta tarjeta le da mucha
información sobre el plugin incluye:

-   Si funcionario / o recomendado es obsoleta (que realmente
    evitar la instalación de complementos obsoletos)

-   4 acciones:

    -   ** ** Instalar Estable instala el plugin en su
        versión estable

    -   Instalar beta ** ** instala el plugin en su
        beta (sólo para betatesters)

    -   ** ** Instalar Pro: Se instala la versión Pro (muy
        poco utilizado)

    -   ** ** Eliminar: si el plugin está instalado actualmente,
        elimina el botón

A continuación encontrará una descripción de la compatibilidad plug-in
(Si Jeedom detecta una falta de coincidencia, se le advertirá) comentarios
el plug-in (puedes aquí la nota) y la información
Además (el autor, la persona que hizo la última actualización
día, un enlace al documento, el número de descargas). Sobre la derecha
a encontrar un botón de "Cambios" que le permite tener todo
el historial de cambios, un botón de "documentación" que devuelve
la documentación del plugin. Entonces usted tiene el idioma disponibles
y diversa información sobre la fecha de la última versión estable.

> **Importante**
>
> Está realmente no recomienda poner un plugin beta en una
> Jeedom no beta, muchas preocupaciones pueden operar
> Resultado.

> **Importante**
>
> Algunos plugins están pagando, en este caso el plugin se conecta
> Oferta de compra, una vez hecho esto se necesita una
> Diez minutos (tiempo de confirmación de pago) y el retorno
> En el archivo plugin para instalar normalmente.

> **Tip**
>
> También puede añadir un plugin para Jeedom desde un archivo o
> A partir de un repositorio de Github. Para ello, tenemos que, en la configuración
> Jeedom, activar la función correspondiente en el "Actualizaciones y
> Archivos. "Entonces será posible, utilizando el ratón mientras que en
> Izquierda, y aparecerá el menú de la página del plugin, haga clic
> "Añadir de otra fuente." A continuación, puede elegir el
> Fuente "archivo". Atención en el caso de añadir un archivo
> Postal, nombre postal debe ser el mismo que el ID plugin y pronto
> Abrir la carpeta ZIP plug-in \ _info debe estar presente.
