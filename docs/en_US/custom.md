# Advanced customization
**Settings → System → Advanced customization**

Here you can manage functions **javascript** and rules **CSS** applied on desktop or mobile.

> **Attention**
>
> Using inappropriate CSS rules can break the display of your Jeedom. Incorrectly used js functions can cause significant damage to various components of your installation. Remember to generate and outsource a backup before using these functions.

This function uses a particular mode of the Core file editor with two locations:

- desktop / custom : Can contain both files **custom.js** And **custom.css** which will be loaded by the Core in Desktop version.
- mobile / custom : Can contain both files **custom.js** And **custom.css** which will be loaded by the Core in Mobile version.

In the menu bar of the Core file editor, a button **Enabled** Or **Deactivated** tells you if the Core should load them or not. This option is also available in **Settings → System → Configuration** Interface tab.

> **Noticed**
>
> When this page is launched, the tree structure is automatically created, as well as the 4 files with a comment in the 1st line including the version of the Core which created them.

## Ressources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[Javascript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tips for customizing the interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## In case of problem

Injecting JS and / or CSS can make Jeedom inoperative.

In this case, two solutions:

- Open a browser in rescue mode : `IP / index.php?rescue=1`
- Connect in SSH and delete the customization files : `desktop / custom` and` mobile / custom`

## Example of advanced personalization in CSS

All these examples are to be put in the CSS file (don't forget to activate advanced customization at the top)

### Removing scrollbars on widgets

```
.eqLogic-widget.cmds{
 overflow-x: hidden !important;
 overflow-y: hidden !important;
}
```

### Remove minimum width/height of widgets

This allows you to have smaller widgets (width [min-width], height [min-height]) but be careful this can make the display less attractive

```
div.cmd-widget.content,
div.cmd-widget .content-sm,
div.cmd-widget .content-lg,
div.cmd-widget.content-xs {
  min-width: unset !important;
  min-height: unset !important;
}
```

### Added margin between the names of objects and equipment on the dashboard 

```
.div_object legend .objectDashLegend {
  margin-bottom: 5px;
}
```
