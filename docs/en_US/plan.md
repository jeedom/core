This page allows you to configure the display of all your home automation
very fine way. It takes time but its only limit is
your imagination.

It is accessible by Home → Dashboard

> ** Tip **
>
> It is possible to go directly to a design through the submenu.

> ** Important **
>
> All actions are done by right clicking on this page, attention
> to do it well in the design. When creating, you need the
> do in the middle of the page (to be sure to be on the design).

In the menu we find (so right click) we find the
following actions:

-   ** Designs **: Shows the list of your designs and access them

-   ** Edit **: Switch to edit mode

-   ** Full Screen **: Allows you to use the entire webpage, which means
    will remove Jeedom's menu from the top

-   ** Add Chart **: Add a chart

-   ** Add text / html **: Add text or code
    html / javascript

-   ** Add Scenario **: Add a scenario

-   ** Add link **

    -   ** To a view **: Adds a link to a view

    -   ** Towards a design **: Adds a link to another
        design

-   ** Add Equipment **: Allows you to add equipment

-   ** Add command **: Add an order

-   ** Add image / camera **: Add an image or feed
    a camera

-   ** Add Zone **: Allows you to add a clickable transparent area
    which will be able to execute a series of actions at a click (depending on
    or not the status of another order)

-   ** Add summary **: Adds information from an object summary or
    general

-   Display ** **

    -   ** None **: Does not display any grid

    -   ** 10x10 **: Displays a grid of 10 by 10

    -   ** 15x15 **: Displays a grid of 15 by 15

    -   ** 20x20 **: Displays a grid of 20 by 20

    -   ** Magnetize elements **: Adds a magnetization between
        elements to make it easier to glue them

    -   ** Magnet at the grid **: Adds a magnetization of the elements to
        the grid (attention: depending on the zoom of the element this
        functionality can more or less work)

    -   ** Hide highlighting of elements **: Hide the
        highlight around items

-   ** Remove design **: removes design

-   ** Create a design **: add a design

-   ** Duplicate design **: duplicates the current design

-   ** Configure the design **: access to the configuration of the design

-   ** Save **: save the design (be careful there is
    also automatic backups during certain actions)

> ** Important **
>
> The configuration of the design elements is done by clicking on
> these.

Configuration of the design
=======================

We find here:

-   **General**

    -   ** Name **: The name of your design

    -   ** Transparent background **: makes the background transparent. Attention if the
        checkbox is checked the background color is not used

    -   ** Background color **: background color of the design (white
        by default)

    -   ** Code **: Code of access to your design (if empty no code
        is not requested)

    -   ** Icon **: An icon for it (appears in the menu of
        design choice)

    -   **Picture**

        -   ** Send **: add a background image to the design

        -   ** Delete image **: delete the image

-   ** ** Sizes

    -   ** Size (LxH) **: Allows you to set the size of your design
        (gray frame in edit mode)

General configuration of elements
===================================

> ** Note **
>
> Depending on the type of the item, the options may change.

> ** Note **
>
> The selected item is highlighted in red (instead of green)
> for all others).

Display setting
---------------------

-   ** Depth **: allows to choose the level of the depth

-   ** Position X (%) **:

-   ** Y position (%) **:

-   ** Width (px) **:

-   ** Height (px) **:

Remove
---------

Delete the item

Duplicate
---------

Duplicate the element

lock
-----------

Lock the item so that it is no longer movable or
redimmensionnable.

Graphic
=========

Display setting
---------------------

-   ** Period **: allows you to choose the posting period

-   ** Show Legend **: Displays the legend

-   ** Show browser **: displays the browser (second graph
    lighter below the first)

-   ** Show Period Selector **: Displays the
    period in the upper left

-   ** Show Scroll Bar **: Displays Scroll Bar

-   ** Transparent background **: makes the background transparent

-   ** Border **: allows to add a border, be careful the syntax is
    HTML (be careful, you have to use a CSS syntax, for example:
    solid 1px black)

Advanced configuration
---------------------

Allows you to choose the commands to graph

Text / html
=========

-   ** Icon **: Icon to display in front

-   ** Background Color **: allows you to change the background color or the
    put transparent, do not forget to pass "Default" on NO

-   ** Text Color **: Change the color of the icons and
    texts (be careful to pass Default on No)

-   ** Rounding angles **: rounds angles (do not
    forget to put%, ex 50%)

-   ** Border **: allows to add a border, be careful the syntax is
    HTML (you have to use a CSS syntax, for example: solid
    1px black)

-   ** Font Size **: allows you to change the font size
    (ex 50%, it is necessary to put the sign%)

-   ** Alignment of text **: allows to choose the alignment of the
    text (left / right / centered)

-   ** Bold **: puts the text in bold

-   ** Text **: Text to the HTML code that will be in the element

> ** Important **
>
> If you put HTML code (especially Javascript), pay attention
> to check it before because you can if there is an error in it
> or if he crushes a Jeedom component completely plant the design and
> it will only remain to delete it directly database

Scenario
========

Display setting
---------------------

No specific display settings

Link
====

Display setting
---------------------

-   ** Name **: Name of the link (displayed text)

-   ** Link **: Link to the design or view in question

-   ** Background Color **: allows you to change the background color or the
    put transparent, do not forget to pass "Default" on NO

-   ** Text Color **: Change the color of the icons and
    texts (be careful to pass Default on No)

-   ** Round the angles (do not forget to put%, ex 50%) **:
    allows to round the angles, do not forget to put the%

-   ** Border (note CSS syntax, eg solid 1px black) **: allows
    to add a border, be careful the syntax is HTML

-   ** Size of the font (ex 50%, it is necessary to put the sign%) **:
    allows you to change the font size

-   ** Alignment of text **: allows to choose the alignment of the
    text (left / right / centered)

-   ** Bold **: puts the text in bold

Equipment
==========

Display setting
---------------------

No specific display settings

Advanced configuration
---------------------

Displays the advanced configuration window of the device (see
home automation summary ("display")

order
========

Display setting
---------------------

No specific display settings

Advanced configuration
---------------------

Displays the advanced configuration window of the command (see
home automation summary ("display")

Picture / Camera
============

Display setting
---------------------

-   ** View **: set the ones you want to display, still image or
    flow of a camera

-   ** Image **: allows to send the image in question (if you have
    chose an image)

-   ** Camera **: camera to display (if you chose camera)

Zoned
====

Display setting
---------------------

-   ** Zone Type **: This is where you choose the type of zone:
    Simple Macro, Binary Macro, or Rollover Widget

### Simple macro

In this mode, one click on the zone performs one or more actions.

All you need to do here is list the actions to be done when clicking
on the area

### Binary macro

In this mode Jeedom will execute the action (s) On or Off in
depending on the status of the command you give. Ex if the order
is 0 then Jeedom will execute the action (s) On otherwise it will execute
the action (s) off

-   ** Binary Information **: Command giving the status to check for
    decide what to do (on or off)

All you need to do below is to put the actions to be done for one and
for the off

### Overflight widget

In this mode when hovering over or clicking in the jeedom zone you
will display the widget in question

-   ** Equipment **: widget to display when hovering over or clicking

-   ** Show on flyover **: if checked displays the widget on flyby

-   ** Show on a click **: if ticked then the widget is displayed at
    click

-   ** Position **: allows you to choose the location where the
    widget (default down right)

summary
======

-   ** Link **: Allows indicated the summary to display (General for the
    overall if not indicate the object)

-   ** Background Color **: allows you to change the background color or the
    put transparent, do not forget to pass "Default" on NO

-   ** Text Color **: Change the color of the icons and
    texts (be careful to pass Default on No)

-   ** Round the angles (do not forget to put%, ex 50%) **:
    allows to round the angles, do not forget to put the%

-   ** Border (note CSS syntax, eg solid 1px black) **: allows
    to add a border, be careful the syntax is HTML

-   ** Size of the font (ex 50%, it is necessary to put the sign%) **:
    allows you to change the font size

-   ** Bold **: puts the text in bold


