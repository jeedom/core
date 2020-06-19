# Personalización avanzada
**Configuración → Sistema → Personalización avanzada**

Esta página, reservada para expertos, le permite agregar scripts CSS o JS a Jeedom, que se ejecutará en cada página.

Puede agregar sus propias funciones JS y agregar o modificar clases CSS.

Las dos partes, JS y CSS, se diferencian de acuerdo con la pantalla de escritorio o móvil.

## Ressources

[CSS: Hojas de estilo en cascada](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Consejos para personalizar la interfaz](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## En caso de problema

Inyectar JS y / o CSS puede hacer que Jeedom no funcione.

En este caso, dos soluciones:

- Abrir un navegador en modo rescate : `IP / index.php?rescue=1`
- Conéctese en SSH y elimine los archivos de personalización : `desktop / custopn` y` mobile / custom`

