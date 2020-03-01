# WIDget
**Tools → WIDget**

The wIDgets page allows you to create custom wIDgets for your Jeedom.

There are two types of custom wIDgets :

- WIDget based on a template (managed by the Jeedom Core).
- WIDget based on user code.

> **NOTE**
>
> If template-based wIDgets are integrated into the Core and therefore monitored by the development team, the latter has no way of ensuring the compatibility of wIDgets based on user code according to Jeedom developments.

## Management

You have four options :
- **Add** : Allows you to create a new wIDget.
- **Import** : Allows you to import a wIDget as a previously exported json file.
- **Coded** : Opens a file editor for editing code wIDgets.
- **Replacement** : Opens a window allowing you to replace a wIDget with another on all devices using it.

## My wIDgets

Wece you have created a wIDget, it will appear in this part.

> **Tip**
>
> You can open a wIDget by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

You have a search engine to filter the display of wIDgets. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:

- The cross to cancel the search.
- The open folder to unfold all the panels and display all the wIDgets.
- The closed folder to fold all the panels.

Wece on the configuration of a wIDget, you have a contextual menu with the Right Click on the tabs of the wIDget. You can also use a Ctrl Click or Clic Center to directly open another wIDget in a new browser tab.


## Principle

But what is a template ?
To put it simply, it is code (here html / js) integrated into the Core, some parts of which are configurable by the user with the graphical interface of the Core.

Depending on the type of wIDget, you can generally customize icons or put images of your choice.

## The templates

There are two types of templates :

- The "**simple**" : Type an icon / image for the "on" and an icon / image for the "off"
- The "**multistates**" : This allows you to define for example an image if the command is set to "XX" and another if> to "YY", and again if <to "ZZ". Or even an image if the value is &quot;toto&quot;, another if &quot;plop&quot;, and so on.

## Creating a wIDget

Wece on the Tools -&gt; WIDget page, click on &quot;Add&quot; and give a name to your new wIDget.

Then :
- You choose if it applies to an action or info type order.
- Depending on your previous choice, you will have to choose the subtype of the command (binary, digital, other ...).
- Then finally the template in question (we plan to put examples of renderings for each template).
- Wece the template has been chosen, Jeedom gives you the options for configuring it.

### Replacement

This is what is called a simple wIDget, here you just have to say that the &quot;on&quot; corresponds to such icon / image (with the button choose), the &quot;off&quot; is that one etc.. Then depending on the template, you can be offered the wIDth and the height. This is only valID for images.

>**NOTE**
>We are sorry for the names in English, this is a constraint of the template system. This choice guarantees a certain speed and efficiency, both for you and for us.. We had no choice

>**Tips**
>For advanced users it is possible in the replacement values to put tags and to specify their value in the advanced configuration of the command, tab display and "Optional parameters wIDget". For example if in wIDth you put as value # wIDth # (be careful to put the # around) instead of a number, in &quot;Optional wIDget settings&quot; you can add wIDth (without the #) and give the value. This allows you to change the image size according to the order and therefore saves you from making a different wIDget for each image size you want

### Test

This is called the multistate part, you often have, as for simple wIDgets, the choice of &quot;height&quot; / &quot;wIDth&quot; for the images only then below the test part.

It&#39;s quite simple. Instead of putting an image for the &quot;on&quot; and / or for the &quot;off&quot; as in the previous case, you go before giving a test to do. If this is true then the wIDget will display the icon / image in question.

The tests are in the form : #value # == 1, # value # will be automatically replaced by the system with the current value of the order. You can also do for example :

- #value #&gt; 1
- #value# >= 1 && #value# <= 5
- #value # == &#39;toto'

>**NOTE**
>It is important to note the &#39;around the text to compare if the value is a text

>**NOTE**
>For advanced users, it is possible here to also use javascript functions type #value#.match (&quot;^ plop&quot;), here we test if the text starts with plop

>**NOTE**
>It is possible to display the value of the command in the wIDget by putting for example next to the HTML code of the #value icon#

## Description of wIDgets

We are going to describe here some wIDgets which have a somewhat particular functioning.

### Frequent settings

- Time wIDget : displays the time since the system has been in the display state.
- We : icon to display if the equipment is on / 1.
- Off : icon to display if the equipment is off / 0.
- Light on : icon to display if the equipment is on / 1 and the theme is light (if empty then Jeedom takes the dark img on).
- Light off : icon to display if the equipment is off / 0 and the theme is light (if empty then Jeedom takes the dark img off).
- Dark on : icon to display if the equipment is on / 1 and the theme is dark (if empty then Jeedom takes the img light on).
- Dark off : icon to display if the equipment is off / 0 and the theme is dark (if empty then Jeedom takes the img light off).
- Desktop wIDth : wIDth of the image on desktop in px (just put the number not the px). Important only the wIDth is requested, Jeedom will calculate the height so as not to distort the image.
- Movable wIDth : wIDth of the image on mobile in px (just put the number not the px). Important only the wIDth is requested, Jeedom will calculate the height so as not to distort the image.

### Hygrothermograph

This wIDget is a bit special because it is a multi-command wIDget, that is to say that it assembles on its display the value of several commands. Here he takes temperature and humIDity commands.

To configure it it&#39;s quite simple you have to assign the wIDget to the temperature control of your equipment and to the humIDity control.

>**Important**
>It is ABSOLUTELY necessary that your orders have the generic type temperature on the temperature control and humIDity on the humIDity control (this is configured in the advanced configuration of the command tab configuration).

The wIDget has an optional parameter : scale which allows you to change its size, example by setting scale to 0.5 it will be 2 times smaller

>**NOTE**
> Attention on a design it is important not to put a command alone with this wIDget it will not work since it is a wIDget using the value of several commands, it is absolutely necessary to put the complete wIDget

### Multiline

- MaxHeight parameter to define its maximum height (scrollbar on the sIDe if the text exceeds this value)

### SlIDer Button

- Step : allows to adjust the Step of an action on a button (0.5 by default)

## Coded WIDget

### Tags

In code mode you have access to different tags for orders, here is a list (not necessarily exhaustive) :

- #name# : command name
- #valueName# : name of the command value, and = # name # when it is an info type command
- #hIDe_name# : empty or hIDden if the user asked to hIDe the name of the wIDget, to put it directly in a class tag
- #ID# : order ID
- #state# : value of the command, empty for an action type command if it is not linked to a status command
- #uID# : unique IDentifier for this generation of the wIDget (if there is several times the same command, case of designs:  only this IDentifier is really unique)
- #valueDate# : date of the order value
- #collectDate# : date of order collection
- #alertLevel# : alert level (see [here] (https:// github.com/jeedom/core/blob/alpha/core/config/jeedom.config.php # L67) for the list)
- #hIDe_history# : whether the history (max, min, average, trend) should be hIDden or not. As for # hIDe_name # it is empty or hIDden, and can therefore be used directly in a class. Important if this tag is not found on your wIDget then the tags # minHistoryValue #, # averageHistoryValue #, # maxHistoryValue # and # trend # will not be replaced by Jeedom.
- #minHistoryValue# : minimum value over the period (period defined in the Jeedom configuration by the user)
- #averageHistoryValue# : average value over the period (period defined in the Jeedom configuration by the user)
- #maxHistoryValue# : maximum value over the period (period defined in the Jeedom configuration by the user)
- #trend# : trend over the period (period defined in the Jeedom configuration by the user). Attention the trend is directly a class for icon : fas fa-arrow-up, fas fa-arrow-down or fas fa-minus

### Update values

When a new value Jeedom will look in the html page, if the command is there and in Jeedom.cmd.update if there is an update function. If yes it calls it with a single argument which is an object in the form :

```
{display_value:'#state # &#39;valueDate:'#valueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel#'}
```

Here is a simple example of javascript code to put in your wIDget :

```
<script>
    Jeedom.cmd.update [&#39;# ID #&#39;] = function (_options){
      $('.cmd[data-cmd_ID=#ID#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_ID=#ID#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update [ &#39;# ID #&#39;] ({display_value:'#state # &#39;valueDate:'#valueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel # &#39;});
</script>
```

Here are two important things :

```
Jeedom.cmd.update [&#39;# ID #&#39;] = function (_options){
  $('.cmd[data-cmd_ID=#ID#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_ID=#ID#] .state').empty().append(_options.display_value +' #unite#');
}
```
The function called when updating the wIDget. It then updates the html code of the wIDget_template.

```
Jeedom.cmd.update [ &#39;# ID #&#39;] ({display_value:'#state # &#39;valueDate:'#valueDate # &#39;collectDate:'#collectDate # &#39;alertLevel:'#alertLevel # &#39;});
```
 The call to this function for the initialization of the wIDget.

 You will find [here] (https:// github.com / Jeedom / core / tree / V4-stable / core / template) examples of wIDgets (in the dashboard and mobile folders)
