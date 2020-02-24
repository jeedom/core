This page allows you to configure the display of all your home automation
very fine way. It takes time but its only limit is
your imagination.

It is accessible by Home → Design

> **Tip**
>
> It is possible to go directly to a design thanks to the submenu.

> **Important**
>
> All actions are done by right clickk on this page, attention
> to do it well in design. During creation, it is therefore necessary to
> do in the middle of the page (to be sure to be on the design).

In the menu (right clickk therefore), we find the
following actions :

-   **designs** : Displays the list of your designs and allows you to access them

-   **Editing** : Switch to edit mode

-   **Full screen** : Allows you to use the entire web page, which
    will remove Jeedom menu from the top

-   **Add graphic** : Add a graphic

-   **Add text / html** : Allows you to add text or code
    html / javascript

-   **Add scenario** : Add a scenario

-   **Add link**

    -   **Towards a view** : Add a link to a view

    -   **Towards a design** : Add a link to another
        design

-   **Add equipment** : Adds equipment

-   **Add order** : Add an order

-   **Add image / camera** : Allows you to add an image or stream
    of a camera

-   **Add area** : Add a transparent clickkable area
    who can execute a series of actions on a clickk (depending
    or not the status of another order)

-   **Add summary** : Adds information from an object summary or
    general

-   **Viewing**

    -   **Any** : Does not display any grid

    -   **10x10** : Displays a 10 by 10 grid

    -   **15x15** : Displays a grid of 15 by 15

    -   **20x20** : Displays a 20 by 20 grid

    -   **Magnetize the elements** : Add a magnetization between
        elements to make it easier to stick them

    -   **Magnet on the grid** : Add a magnetization of the elements to
        the grid (attention : depending on the zoom of the item this
        functionality can more or less work)

    -   **Hide item highlighting** : Hide it
        highlight around items

-   **Delete design** : delete design

-   **Create a design** : allows to add a new design

-   **Duplicate design** : duplicate the current design

-   **Configure the design** : access to design configuration

-   **Save** : allows to save the design (attention, there are
    also automatic backups during certain actions)

> **Important**
>
> The configuration of the design elements is done by clickking on
> these.

Design configuration 
=======================

Found here :

-   **General**

    -   **Last name** : The name of your design

    -   **Transparent background** : makes the background transparent. Be careful if the
        box is checked, the background color is not used

    -   **Background color** : design background color (white
        by default)

    -   **Coded** : Access code to your design (if empty, no code
        is not requested)

    -   **Icon** : An icon for it (appears in the menu
        choice of design)

    -   **Picture**

        -   **To send** : allows to add a background image to the design

        -   **Delete image** : delete image

-   **sizes**

    -   **Size (WxH)** : Allows you to fix the size of your design
        (gray frame in edit mode)

General configuration of elements 
===================================

> **Note**
>
> Depending on the type of item, options may change.

> **Note**
>
> The selected item is highlighted in red (instead of green
> for everyone else).

Display setting 
---------------------

-   **Depth** : allows to choose the depth level

-   **Position X (%)** :

-   **Position Y (%)** :

-   **Width (px)** :

-   **Height (px)** :

Remove 
---------

Remove item

Duplicate 
---------

Allows you to duplicate the element

lock 
-----------

Locks the element so that it is no longer movable or
resizable.

Graphic 
=========

Display settings 
---------------------

-   **Period** : allows you to choose the display period

-   **Show caption** : show legend

-   **Show browser** : display the browser (second graph
    lighter below the first)

-   **Show period selector** : displays the selector
    period top left

-   **Show scroll bar** : displays the scroll bar

-   **Transparent background** : makes the background transparent

-   **border** : add a border, beware the syntax is
    HTML (be careful, you must use CSS syntax, for example :
    solid 1px black)

Advanced configuration 
---------------------

Allows you to choose the commands to grapher

Text / html 
=========

-   **Icon** : Icon to display in front

-   **Background color** : allows you to change the background color or
    put transparent, do not forget to pass &quot;Default&quot; on NO

-   **Text color** : allows you to change the color of the icons and
    texts (be careful to pass Default to No)

-   **Smooth it out** : allows you to round off the angles (do not
    forget to put%, ex 50%)

-   **border** : add a border, beware the syntax is
    HTML (use CSS syntax, for example : solid
    1px black)

-   **Font size** : allows you to change the font size
    (ex 50%, you must put the% sign)

-   **Text alignment** : allows you to choose the alignment of the
    text (left / right / centered)

-   **fat** : bold text

-   **Text** : Text in HTML code that will be in the element

> **Important**
>
> If you put HTML code (especially Javascript), be careful
> to check it before because you can if there is an error in it
> or if it overwrites a Jeedom component completely crashing the design and
> all you have to do is delete it directly from the database

Scenario 
========

Display settings 
---------------------

No specific display settings

Link 
====

Display settings 
---------------------

-   **Last name** : Name of the link (displayed text)

-   **Link** : Link to the design or view in question

-   **Background color** : allows you to change the background color or
    put transparent, do not forget to pass &quot;Default&quot; on NO

-   **Text color** : allows you to change the color of the icons and
    texts (be careful to pass Default to No)

-   **Round off the angles (don&#39;t forget to put%, ex 50%)** :
    allows you to round off the angles, don&#39;t forget to put the%

-   **Border (attention CSS syntax, ex : solid 1px black)** : allows
    add a border, beware the syntax is HTML

-   **Font size (ex 50%, you must put the% sign)** :
    allows you to change the font size

-   **Text alignment** : allows you to choose the alignment of the
    text (left / right / centered)

-   **fat** : bold text

Equipment 
==========

Display settings 
---------------------

No specific display settings

Advanced configuration 
---------------------

Displays the advanced equipment configuration window (see
documentation Home automation summary (&quot;display&quot;))

Ordered 
========

Display settings 
---------------------

No specific display settings

Advanced configuration 
---------------------

Displays the advanced configuration window of the command (see
documentation Home automation summary (&quot;display&quot;))

Picture / Camera 
============

Display settings 
---------------------

-   **Pin up** : defines what you want to display, still image or
    stream from a camera

-   **Picture** : allows you to send the image in question (if you have
    choose an image)

-   **Camera** : camera to display (if you chose camera)

Zoned 
====

Display settings 
---------------------

-   **Type of area** : This is where you choose the type of area :
    Simple macro, Binary macro or Widget on hover

### Single macro 

In this mode, a clickk on the zone performs one or more actions.

Here you just need to indicate the list of actions to do when clickking
on the area

### Binary macro 

In this mode, Jeedom will execute the On or Off action (s) in
depending on the status of the order you give. Ex : if the order
is 0 then Jeedom will execute the On action (s) otherwise it will execute
the action (s) Off

-   **Binary information** : Command giving the status to be checked for
    decide what action to take (On or Off)

You just have to put the actions to do for the On and
for the Off

### Hover widget 

In this mode, when hovering or clickking in the Jeedom area, you
display the widget in question

-   **Equipment** : widget to display when hovering or clickking

-   **Show on flyover** : if checked, displays the widget on hover

-   **View on one clickk** : if checked, then the widget is displayed at
    click

-   **Position** : allows you to choose the location where the
    widget (default bottom right)

summary 
======

-   **Link** : Used to indicate the summary to be displayed (General for the
    global if not indicate the object)

-   **Background color** : allows you to change the background color or
    put transparent, do not forget to pass &quot;Default&quot; on NO

-   **Text color** : allows you to change the color of the icons and
    texts (be careful to pass Default to No)

-   **Round off the angles (don&#39;t forget to put%, ex 50%)** :
    allows you to round off the angles, don&#39;t forget to put the%

-   **Border (attention CSS syntax, ex : solid 1px black)** : allows
    add a border, beware the syntax is HTML

-   **Font size (ex 50%, you must put the% sign)** :
    allows you to change the font size

-   **fat** : bold text


FAQ 
======

>**I can no longer edit my design**
>
>If you have put a widget or an image that takes almost the entire design, you must clickk outside the widget or image to access the menu by right-clickking.

>**Delete a design that no longer works**
>
>In the administration part then OS / DB, do &quot;select * from planHeader&quot;, get the id of the design in question and do a &quot;delete from planHeader where id = # TODO #&quot; and &quot;delete from plan where planHeader_id = # todo # &quot;by replacing # TODO # with the design id previously found.
