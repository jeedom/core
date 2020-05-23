# Advanced customization
**Settings → System → Advanced customization**

This page, reserved for experts, allows you to add CSS or JS scripts to Jeedom, which will be executed on each page.

You can add your own JS functions, and add or modify CSS classes.

The two parts, JS and CSS, are differentiated according to the Desktop or Mobile display.

## Ressources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tips for customizing the interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## In case of problem

Injecting JS and / or CSS can make Jeedom inoperative.

In this case, two solutions:

- Open a browser in rescue mode : `IP / index.php?rescue=1`
- Connect in SSH and delete the customization files : `desktop / custopn` and` mobile / custom`

