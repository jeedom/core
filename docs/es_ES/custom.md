# Personalización avanzada
**Ajustes → Sistema → Personalización avanzada**

Aquí puedes gestionar las funciones **JavaScript** y las reglas **CSS** que se aplican tanto en el ordenador como en el móvil.

> **Atención**
>
> El uso de reglas CSS inadecuadas puede alterar la visualización de tu Jeedom. Las funciones JavaScript utilizadas de forma incorrecta pueden causar daños importantes en distintos componentes de tu instalación. Recuerda generar y exportar una copia de seguridad antes de utilizar estas funciones.

Esta función utiliza un modo específico del editor de archivos del Core con dos ubicaciones:

- escritorio / personalizado: Puede contener los dos archivos **custom.js** y **custom.css**, que serán cargados por el núcleo en la versión de escritorio.
- móvil / personalizado: Puede contener los dos archivos **custom.js** y **custom.css**, que serán cargados por el núcleo en la versión móvil.

En la barra de menú del editor de archivos del Core, un botón **Activado** o **Desactivado** te indica si el Core debe cargarlos o no. Esta opción también está disponible en **Ajustes → Sistema → Configuración**, en la pestaña Interfaz.

> **Nota**
>
> Al abrir esta página, se crea automáticamente la estructura de directorios, así como los cuatro archivos con un comentario en la primera línea que indica la versión del Core que los ha creado.

## Recursos

[CSS: Hojas de estilo en cascada](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Consejos para personalizar la interfaz](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## En caso de problemas

Incluir código JS y/o CSS puede hacer que Jeedom deje de funcionar.

En este caso, hay dos soluciones:

- Abre un navegador en modo de rescate: `IP/index.php?rescue=1`
- Conéctate por SSH y elimina los archivos de personalización: `desktop/custom` y `mobile/custom`

## Ejemplo de personalización avanzada en CSS

Todos estos ejemplos deben incluirse en el archivo CSS (no olvides activar la personalización avanzada en la parte superior)

### Eliminación de las barras de desplazamiento en los widgets

```
.eqLogic-widget .cmds{
 overflow-x: hidden !important;
 overflow-y: hidden !important;
}
```

### Eliminar el ancho/alto mínimo de los widgets

Esto permite tener widgets más pequeños (anchura [min-width], altura [min-height]), pero ten en cuenta que puede hacer que la visualización resulte menos atractiva.

```
div.cmd-widget .content,
div.cmd-widget .content-sm,
div.cmd-widget .content-lg,
div.cmd-widget .content-xs {
  min-width: unset !important;
  min-height: unset !important;
}
```

### Se han añadido márgenes entre el nombre de los objetos y los dispositivos en el panel de control

```
.div_object legend .objectDashLegend {
  margin-bottom: 5px;
}
```
