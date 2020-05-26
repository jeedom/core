# Widgets
**Tools → Widgets**

The widgets page allows you to create custom widgets for your Jeedom.

There are two types of custom widgets :

- Widgets based on a template (managed by the Jeedom Core).
- Widgets based on user code.

> **NOTE**
>
> If template-based widgets are integrated into the Core and therefore monitored by the development team, the latter has no way of ensuring the compatibility of widgets based on user code according to Jeedom developments.

## Gestion

You have four options :
- **Add** : Allows you to create a new widget.
- **Import** : Allows you to import a widget as a previously exported json file.
- **CODE** : Opens a file editor for editing code widgets.
- **Replacement** : Opens a window allowing you to replace a widget with another on all devices using it.

## My widgets

Once you have created a widget, it will appear in this part.

> **Tip**
>
> You can open a widget by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

You have a search engine to filter the display of widgets. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:

- The cross to cancel the search.
- The open folder to unfold all the panels and display all the widgets.
- The closed folder to fold all the panels.

Once on the configuration of a widget, you have a contextual menu with the Right Click on the tabs of the widget. You can also use a Ctrl Click or Clic Center to directly open another widget in a new browser tab.


## Principe

But what is a template ?
To put it simply, it is code (here html / js) integrated into the Core, some parts of which are configurable by the user with the graphical interface of the Core.

Depending on the type of widget, you can generally customize icons or put images of your choice.

## The templates

There are two types of templates :

- The "**simple**" : Type an icon / image for the "on" and an icon / image for the "off"
- The "**multistate**" : This allows you to define for example an image if the command is set to "XX" and another if> to "YY", and again if <to "ZZ". Or even an image if the value is &quot;toto&quot;, another if &quot;plop&quot;, and so on.

## Creating a widget

Once on the Tools -&gt; Widget page, click on &quot;Add&quot; and give a name to your new widget.

Then :
- You choose if it applies to an action or info type order.
- Depending on your previous choice, you will have to choose the subtype of the command (binary, digital, other...).
- Then finally the template in question (we plan to put examples of renderings for each template).
- Once the template has been chosen, jeedom gives you the options for configuring it.

### Remplacement

This is what is called a simple widget, here you just have to say that the &quot;on&quot; corresponds to such icon / image (with the button choose), the &quot;off&quot; is that one etc. Then depending on the template, you can be offered the width (width) and the height (height). This is only valid for images.

>**NOTE**
>We are sorry for the names in English, this is a constraint of the template system. This choice guarantees a certain speed and efficiency, both for you and for us. We had no choice

>**Tips**
>For advanced users it is possible in the replacement values to put tags and to specify their value in the advanced configuration of the command, tab display and "Optional parameters widget". For example if in width you put as value #width# (be careful to put the # autour) au lieu d'un chiffre, dans "Paramètres optionnels widget" vous pouvez ajouter width (sans les #) and give value. This allows you to change the image size according to the order and therefore saves you from making a different widget for each image size you want

### Test

This is called the multistate part, you often have, as for simple widgets, the choice of &quot;height&quot; / &quot;width&quot; for the images only then below the test part.

It&#39;s quite simple. Instead of putting an image for the &quot;on&quot; and / or for the &quot;off&quot; as in the previous case, you go before giving a test to do. If this is true then the widget will display the icon / image in question.

The tests are in the form : #value# == 1, #value# will be automatically replaced by the system by the current value of the order. You can also do for example :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**NOTE**
>It is important to note the &#39;around the text to compare if the value is a text

>**NOTE**
>For advanced users, it is also possible to use javascript type functions here #value#.match (&quot;^ plop&quot;), here we test if the text starts with plop

>**NOTE**
>It is possible to display the value of the command in the widget by putting for example next to the HTML code of the icon #value#

## Description of widgets

We are going to describe here some widgets which have a somewhat particular functioning.

### Equipement

The equipments have certain configuration parameters :

- dashboard_class / mobile_class : allows to add a class to the equipment. For example col2 for equipment in mobile version, which allows to double the width of the widget

### Frequent settings

- Time widget : displays the time since which the system has been in the display state.
- We : icon to display if the equipment is on / 1.
- Off : icon to display if the equipment is off / 0.
- Light on : icon to display if the equipment is on / 1 and the theme is light (if empty then Jeedom takes the dark img on).
- Light off : icon to display if the equipment is off / 0 and the theme is light (if empty then Jeedom takes the img dark off).
- Dark on : icon to display if the equipment is on / 1 and the theme is dark (if empty then Jeedom takes the img light on).
- Dark off : icon to display if the equipment is off / 0 and the theme is dark (if empty then Jeedom takes the light img off).
- Desktop width : width of the image on desktop in px (just put the number not the px). Important only the width is requested, Jeedom will calculate the height so as not to distort the image.
- Movable width : width of the image on mobile in px (just put the number not the px). Important only the width is requested, Jeedom will calculate the height so as not to distort the image.

### HygroThermographe

This widget is a bit special because it is a multi-command widget, that is to say that it assembles on its display the value of several commands. Here he takes temperature and humidity commands.

To configure it it&#39;s quite simple you have to assign the widget to the temperature control of your equipment and to the humidity control.

>**Important**
>It is ABSOLUTELY necessary that your orders have the generic type temperature on the temperature control and humidity on the humidity control (this is configured in the advanced configuration of the command tab configuration).

##### Optional parameter (s))

- scale : Allows you to change its size, example by setting scale to 0.5 it will be 2 times smaller.

>**NOTE**
> Attention on a design it is important not to put a command alone with this widget it will not work since it is a widget using the value of several commands, it is absolutely necessary to put the complete widget

### Multiline

##### Optional parameter (s))

- maxHeight : Allows you to define its maximum height (scrollbar on the side if the text exceeds this value).

### Slider Button

##### Optional parameter (s))

- Step : Allows to adjust the step of an action on a button (0.5 by default).

### Rain

##### Optional parameter (s))

- scale : Allows you to change its size, example by setting scale to 0.5 it will be 2 times smaller.
- showRange : Displays the min / max values of the command.


## Code Widget

### Tags

In code mode you have access to different tags for orders, here is a list (not necessarily exhaustive)) :

- #name# : command name
- #valueName# : name of the order value, and = #name# when it's an info type command
- #minValue# : minimum value that the command can take (if the command is of type slider)
- #maxValue# : maximum value that can take the command (if the command is of type slider)
- #hide_name# : empty or hidden if the user asked to hide the name of the widget, to put it directly in a class tag
- #id# : order id
- #state# : value of the command, empty for an action type command if it is not linked to a status command
- #uid# : unique identifier for this generation of the widget (if there is several times the same command, case of designs:  only this identifier is really unique)
- #valueDate# : date of the order value
- #collectDate# : date of order collection
- #alertLevel# : alert level (see [here](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) for the list)
- #hide_history# : whether the history (max, min, average, trend) should be hidden or not. As for the #hide_name# it is empty or hidden, and can therefore be used directly in a class. IMPORTANT if this tag is not found on your widget then the tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# and #tendance# will not be replaced by Jeedom.
- #minHistoryValue# : minimum value over the period (period defined in the Jeedom configuration by the user)
- #averageHistoryValue# : average value over the period (period defined in the Jeedom configuration by the user)
- #maxHistoryValue# : maximum value over the period (period defined in the Jeedom configuration by the user)
- #tendance# : trend over the period (period defined in the configuration of Jeedom by the user). Attention the trend is directly a class for icon : fas fa-arrow-up, fas fa-arrow-down or fas fa-minus

### Update values

When a new value Jeedom will look in the html page, if the command is there and in Jeedom.cmd.update if there is an update function. If yes it calls it with a single argument which is an object in the form :

`` ''
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
`` ''

Here is a simple example of javascript code to put in your widget :

`` ''
<script>
    Jeedom.cmd.update ['#id#'] = function (_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Collect date : '+ _options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
</script>
`` ''

Here are two important things :

`` ''
Jeedom.cmd.update ['#id#'] = function (_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Collect date : '+ _options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
`` ''
The function called when updating the widget. It then updates the html code of the widget_template.

`` ''
Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
`` ''
 The call to this function for the initialization of the widget.

 You will find [here](https://github.com/Jeedom/core/tree/V4-stable/core/template) examples of widgets (in dashboard and mobile folders)
