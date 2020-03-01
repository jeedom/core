The widgets page allows you to create custom and unique widgets for your Jeedom.

There are 2 possibilities :

- Either by clicking on the code button and directly writing your html code for your widget (this is not necessarily what we recommend because during jeedom updates your code may become incompatible with jeedom)
- Either by making a widget based on a template that we provide

# But what is a template ?

To make it simple it is code (here html) where we have predefined certain parts that you will be able to configure as you want.

In the case of widgets, we often suggest customizing the icons or putting the images you want.

# The templates

There are 2 types of templates :

- The "simple" : type an icon / image for the "on" and an icon / image for the "off"
- The "multistates" : this allows you to define for example an image if the command has the value "XX" and another if> to "YY" and again if <to "ZZ". Or even an image if the value is &quot;toto&quot;, another if it is &quot;plop&quot; and so on.

# How to do ?

Wece on the Tools -&gt; Widget page, click on &quot;Add&quot; and give a name to your new widget.

Then :
- You choose if it applies to an action or info type order
- Depending on your previous choice, you will have to choose the subtype of the command (binary, digital, other ...)
- Then finally the template in question (we plan to give you examples of renderings for each template)
- Wece the template has been chosen, jeedom gives you the options for configuring it

## Replacement

This is what is called a simple widget, here you just have to say that the &quot;on&quot; corresponds to such icon / image (with the button choose), the &quot;off&quot; is that one ec. Then depending on the template, you may also be asked for the width and the height.. This is only valid for images.

>**Note**
>
>We are sorry for the names in English, this is a constraint of the template system. This choice guarantees a certain speed and efficiency, both for you and for us.. We had no choice

>**TIPS**
>
>For advanced users it is possible in the replacement values to put tags and specify their value in the advanced configuration of the command, display tab and "Optional settings widget". For example if in width you put as value # width # (be careful to put the # around) instead of a number, in &quot;Optional widget settings&quot; you can add width (without the #) and give the value. This allows you to change the image size according to the order and therefore saves you from making a different widget for each image size you want.

## Test

This is called the multistate part, you often have, as for simple widgets, the choice of &quot;height&quot; / &quot;width&quot; for the images only then below the test part.

It&#39;s quite simple. Instead of putting an image for the &quot;on&quot; and / or for the &quot;off&quot; as in the previous case, you go before giving a test to do. If this is true then the widget will display the icon / image in question.

The tests are in the form : #value # == 1, # value # will be automatically replaced by the system with the current value of the order. You can also do for example :

- #value #&gt; 1
- #value# >= 1 && #value# <= 5
- #value # == &#39;toto'

>**Note**
>
>It is important to note the &#39;around the text to compare if the value is a text

>**Note**
>
>For advanced users, it is possible here to also use javascript functions type #value#.match (&quot;^ plop&quot;), here we test if the text starts with plop

>**Note**
>
>It is possible to display the value of the command in the widget by putting for example next to the HTML code of the #value icon#

# Description of widgets

We are going to describe here some widget which have a rather particular functioning.

## Frequent settings

- Time widget : displays the time since which the system has been in the display state.
- We : icon to display if the equipment is on / 1
- off : icon to display if the equipment is off / 0
- Light on : icon to display if the equipment is on / 1 and the theme is light (if empty then jeedom takes the dark img on)
- Light off : icon to display if the equipment is off / 0 and the theme is light (if empty then jeedom takes the dark img off)
- Dark on : icon to display if the equipment is on / 1 and the theme is dark (if empty then jeedom takes the light img on)
- Dark off : icon to display if the equipment is off / 0 and the theme is dark (if empty then jeedom takes the img light off)
- Desktop width : width of the image on desktop in px (just put the number not the px). Important only the width is required, jeedom will calculate the height so as not to distort the image
- Movable width : width of the image on mobile in px (just put the number not the px). Important only the width is required, jeedom will calculate the height so as not to distort the image

## hygrothermograph

This widget is a bit special because it is a multi-command widget, that is to say that it assembles on its display the value of several commands. Here he takes temperature and humidity commands.

To configure it it&#39;s quite simple you have to assign the widget to the temperature control of your equipment and to the humidity control.

>**IMPORTANT**
>
>It is ABSOLUTELY necessary that your orders have the generic type temperature on the temperature and humidity control on the humidity control (this is configured in the advanced configuration of the command tab configuration).

The widget has an optional parameter : scale which allows you to change its size, example by setting scale to 0.5 it will be 2 times smaller

>**NOTE**
>
> Attention on a design it is especially important not to put an order alone with this widget that will not work since it is a widget using the value of several orders it is absolutely necessary to put the complete widget

## Slider Button

- Step : allows to adjust the Step of an action on a button (0.5 by default)

## Compass

- needle : set to 1 for display in compass mode

# Code Widget

## Tags

In code mode you have access to different tags for orders, here is a list (not necessarily exhaustive) :

- #name# : command name
- #valueName# : name of the command value, and = # name # when it is an info type command
- #hide_name# : empty or hidden if the user asked to hide the name of the widget, to put it directly in a class tag
- #id# : order id
- #state# : value of the command, empty for an action type command if it is not linked to a status command
- #uid# : unique identifier for this generation of the widget (if there is several times the same command, case of designs only this identifier is really unique)
- #valueDate# : date of the order value
- #collectDate# : date of order collection
- #alertLevel# : alert level (see [here] (https:// github.com/jeedom/core/blob/alpha/core/config/jeedom.config.php # L67) for the list)
- #hide_history# : whether the history (max, min, average, trend) should be hidden or not. As for # hide_name # it is empty or hidden, and can therefore be used directly in a class. IMPORTANT if this tag is not found on your widget then the # minHistoryValue #, # averageHistoryValue #, # maxHistoryValue # and # trend # tags will not be replaced by Jeedom.
- #minHistoryValue# : minimum value over the period (period defined in the configuration of jeedom by the user)
- #averageHistoryValue# : average value over the period (period defined in the configuration of jeedom by the user)
- #maxHistoryValue# : maximum value over the period (period defined in the configuration of jeedom by the user)
- #trend# : trend over the period (period defined in the configuration of jeedom by the user). Attention the trend is directly a class for icon : fas fa-arrow-up, fas fa-arrow-down or fas fa-minus

## Update values

When a new value jeedom will look in the web page if the command is there and in jeedom.cmd.update if there is an update function. If yes it calls it with a single argument which is an object in the form :

```
{display_value:'#state # &#39;valueDate:'#valueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel#'}
```

Here is a simple example of javascript code to put in your widget :

```
<script>
    jeedom.cmd.update [&#39;# id #&#39;] = function (_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    jeedom.cmd.update [ &#39;# id #&#39;] ({display_value:'#state # &#39;valueDate:'#valueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel # &#39;});
</script>
```

Here 2 important thing :

```
jeedom.cmd.update [&#39;# id #&#39;] = function (_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
```

The function called when updating the widget which takes care of updating the html code of the widget_template

And :

```
jeedom.cmd.update [ &#39;# id #&#39;] ({display_value:'#state # &#39;valueDate:'#valueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel # &#39;});
 ```

 The call to this function for the initialization of the widget.

 You will find [here] (https:// github.com / jeedom / core / tree / V4-stable / core / template) examples of widgets (in the dashboard and mobile folders)
