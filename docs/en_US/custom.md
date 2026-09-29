# Advanced Customization
**Settings → System → Advanced Customization**

Here you can manage **JavaScript** functions and **CSS** rules applied to desktop or mobile devices.

> **Warning**
>
> Using inappropriate CSS rules can break the display on your Jeedom. Incorrectly used JavaScript functions can cause significant damage to various components of your system. Be sure to create and export a backup before using these functions.

This feature uses a special mode of the Core's file editor with two locations:

- desktop / custom: May contain both the **custom.js** and **custom.css** files, which will be loaded by the Core in the desktop version.
- mobile / custom: May contain both the **custom.js** and **custom.css** files, which will be loaded by the Core in the mobile version.

In the menu bar of the Core's file editor, an **Enabled** or **Disabled** button indicates whether the Core should load them or not. This option is also available in **Settings → System → Configuration** on the Interface tab.

> **Note**
>
> When this page is loaded, the directory structure is created automatically, along with four files, each with a comment on the first line indicating the version of the Core that created them.

## Resources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tips for Customizing the Interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## If you encounter a problem

Injecting JavaScript and/or CSS can render Jeedom inoperable.

In this case, there are two solutions:

- Open a browser in rescue mode: `IP/index.php?rescue=1`
- Connect via SSH and delete the customization files: `desktop/custom` and `mobile/custom`

## Example of advanced CSS customization

All of these examples should be added to the CSS file (don't forget to enable advanced customization at the top)

### Removing scroll bars from widgets

```
.eqLogic-widget .cmds{
 overflow-x: hidden !important;
 overflow-y: hidden !important;
}
```

### Remove the minimum width/height of widgets

This allows for smaller widgets (width [min-width], height [min-height]), but be careful—it can make the display look less attractive.

```
div.cmd-widget .content,
div.cmd-widget .content-sm,
div.cmd-widget .content-lg,
div.cmd-widget .content-xs {
  min-width: unset !important;
  min-height: unset !important;
}
```

### Added spacing between object names and devices on the dashboard

```
.div_object legend .objectDashLegend {
  margin-bottom: 5px;
}
```
