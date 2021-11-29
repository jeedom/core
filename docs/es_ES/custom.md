# Personalización avanzada
**Configuración → Sistema → Personalización avanzada**

Aquí puede administrar funciones **JavaScript** y reglas **CSS** aplicado en escritorio o móvil.

> **Atención**
>
> El uso de reglas CSS inapropiadas puede romper la visualización de su Jeedom. Las funciones js utilizadas incorrectamente pueden causar daños importantes a varios componentes de su instalación. Recuerde generar y subcontratar una copia de seguridad antes de utilizar estas funciones.

Esta función usa un modo particular del editor de archivos Core con dos ubicaciones:

- escritorio / personalizado : Puede contener ambos archivos **custom.js** y **custom.css** que será cargado por el Core en la versión de escritorio.
- móvil / personalizado : Puede contener ambos archivos **custom.js** y **custom.css** que será cargado por el Core en la versión móvil.

En la barra de menú del editor de archivos Core, un botón **Activado** Dónde **Desactivado** te dice si el Core debe cargarlos o no. Esta opción también está disponible en **Configuración → Sistema → Configuración** Pestaña de interfaz.

> **Observación**
>
> Cuando se lanza esta página, se crea automáticamente la estructura de árbol, así como los 4 archivos con un comentario en la 1ra línea, incluida la versión del Core que los creó.

## Ressources

[CSS: Hojas de estilo en cascada](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Consejos para personalizar la interfaz](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## En caso de problema

Inyectar JS y / o CSS puede hacer que Jeedom no funcione.

En este caso, dos soluciones:

- Abrir un navegador en modo rescate : `IP / index.php?rescue=1`
- Conéctese en SSH y elimine los archivos de personalización : `escritorio / personalizado` y` móvil / personalizado`

