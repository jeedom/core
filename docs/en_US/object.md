The **objects** allow to define the tree of your home automation.
All the equipment you create will belong to an object and
will be easier to locate. We then say that the object
is the **parent** of the equipment. Object management is accessible
from the menu **Tools → Objects**.

To give free choice to the customization, you can name these
objects as you want. Usually, we will define the different
parts of his house, like the names of the pieces (this is also the
recommended configuration).

Management
=======

Two options are available to you:

-   **Add**: Create a new object.

-   **Overview**: Displays the list of created objects
    as well as their configuration.

My objects
==========

Once you have created an object, it will appear in this part.

Object tab
------------

Clicking on an object will take you to its configuration page. What
whatever changes are made, do not forget to save to the
end.

Here are the different features to configure an object:

-   **Name of the object**: The name of your object.

-   **Father**: Indicates the parent of the current object, this allows you to
    define a hierarchy between the objects. For example: The show has
    for parent the apartment. An object can only have one parent
    but many objects can have the same parent.

-   **Visible**: Check this box to make this object visible.

-   **Hide on dashboard**: Check this box to hide
    the object on the dashboard. It is still preserved in the
    list, which allows to display it, but only
    explicit way.

-   **Icon**: Choose an icon for your object.

-   **Color of the tag**: Allows to choose the color of the object and the
    equipment attached to it.

-   **Tag text color**: Allows you to choose the color of the text
    of the object. This text will be over **the color of the tag**. To you
    to choose a color to make the text readable.

-   **Abstract text color**: Allows you to choose the color of the text
    summary results of the object in the dashboard.

-   **Size on the dashboard (1 to 12)**: Sets the width
    the display of this object in the dashboard. For example: if you
    put `6` to two consecutive objects in the list, then
    will be side by side on the dashboard. If you put `3` to four
    objects that follow each other, they will also be side by side.

> **Tip**
>
> You can change the display order of objects in the dashboard.
> In the menu, on the left of your page, use the vertical arrows
> drag and drop to give them a new place.

> **Tip**
>
> You can see a graphic representing all the elements of Jeedom
> attached to this object by clicking on the **Links** button, up to
> right.

> **Tip**
>
> When a device is created and no parent has been defined, it
> will have as parent: **None**.

Summary tab
-------------

Summaries are global information, assigned to an object, which
are displayed on the dashboard next to the name of the dashboard.

### Bulletin board

The columns represent the summaries assigned to the current object. Three
lines are available:

-   **Go up in the global summary**: Check the box if you
    want the summary to be displayed in the menu bar
    from Jeedom.

-   **Hide in desktop**: Check the box if you do not want
    the summary is displayed next to the object name on the dashboard.

-   **Hide in mobile**: Check the box if you do not want
    the summary is displayed when you view it from a mobile.

### Orders

Each tab represents a summary type defined in the configuration
from Jeedom. Click **Add an order** so that it is
taken into account in the summary. You have the choice to select the
control of any Jeedom equipment, even if it does not have to
parent this object.

> **Tip**
>
> If you want to add a summary type or to configure the
> method of calculating the result, the unit, the icon and the name of an abstract,
> you have to go to Jeedom's general configuration:
> **Administration → Configuration → Summaries Tab**.

Overview
==============

The overview allows you to view all the objects in
Jeedom, as well as their configuration:

-   **ID**: ID of the object.

-   **Object**: Name of the object.

-   **Father**: Name of the parent object.

-   **Visible**: Visibility of the object.

-   **Hidden**: Indicates if the object is hidden on the dashboard.

-   **Summary Set**: Indicates the number of orders per summary. This
    which is in blue is taken into account in the overall summary.

-   **Summary Dashboard Hidden**: Indicates hidden summaries on
    the dashboard.

-   **Summary Mobile Masked**: Indicates hidden summaries on
    the cellphone.


