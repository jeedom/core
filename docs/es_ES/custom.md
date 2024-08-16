# Personalización avanzada
**Configuración → Sistema → Personalización avanzada**

Aquí puede administrar funciones **javascript** y reglas **CSS** aplicado en escritorio o móvil.

> **Atención**
>
> El uso de reglas CSS inapropiadas puede romper la visualización de su Jeedom. Las funciones js utilizadas incorrectamente pueden causar daños importantes a varios componentes de su instalación. Recuerde generar y subcontratar una copia de seguridad antes de utilizar estas funciones.

Esta función usa un modo particular del editor de archivos Core con dos ubicaciones:

- escritorio / personalizado : Puede contener ambos archivos **personalizado.js** Y **personalizado.css** que será cargado por el Core en la versión de escritorio.
- móvil / personalizado : Puede contener ambos archivos **personalizado.js** Y **personalizado.css** que será cargado por el Core en la versión móvil.

En la barra de menú del editor de archivos Core, un botón **Activado** O **Desactivado** te dice si el Core debe cargarlos o no. Esta opción también está disponible en **Configuración → Sistema → Configuración** Pestaña de interfaz.

> **Observó**
>
> Cuando se lanza esta página, se crea automáticamente la estructura de árbol, así como los 4 archivos con un comentario en la 1ra línea, incluida la versión del Core que los creó.

## Ressources

[CSS: Hojas de estilo en cascada](https://developer.mozilla.org/en-US/docs/Web/CSS)

[Javascript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Consejos para personalizar la interfaz](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## En caso de problema

Inyectar JS y / o CSS puede hacer que Jeedom no funcione.

En este caso, dos soluciones:

- Abrir un navegador en modo rescate : `IP / index.php?rescue=1`
- Conéctese en SSH y elimine los archivos de personalización : `escritorio / personalizado` y` móvil / personalizado`

## Ejemplo de personalización avanzada en CSS

Todos estos ejemplos deben colocarse en el archivo CSS (no olvide activar la personalización avanzada en la parte superior)

### Eliminar barras de desplazamiento en widgets

```
.eqLogic-widget.cmds{
 overflow-x: oculto !important;
 overflow-y: oculto !important;
}
```

### Eliminar el ancho/alto mínimo de los widgets

Esto le permite tener widgets más pequeños (ancho [ancho mínimo], alto [alto mínimo]), pero tenga cuidado, esto puede hacer que la visualización sea menos atractiva

```
div.cmd-widget.content,
div.cmd-widget .content-sm,
div.cmd-widget .content-lg,
div.cmd-widget.content-xs {
  min-width: desarmado !important;
  min-height: desarmado !important;
}
```

### Margen agregado entre los nombres de objetos y equipos en el tablero 

```
.leyenda div_object .objetoDashLegend {
  margin-bottom: 5px;
}
```
