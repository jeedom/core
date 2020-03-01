The **objects** allow you to deende the tree structure of your home automation.
All the equipment you create must belong to an object and
will be more easily identifiable. We then say that the object
is the **relative** equipment. Object management is accessible
from the menu **Tools → Objects**.

To give free choice to customization, you can name these
objects as you want. Usually, we will deende the different
parts of his house, like the names of the rooms (this is
recommended configuration).

Management 
=======

You have two options :

-   **Add** : Create a new object.

-   **Overview** : Displays the list of created objects
    as well as their configuration.

My objects 
==========

Once you have created an object, it will appear in this part.

Object tab 
------------

By clicking on an object, you access its configuration page. What
whatever the changes made, don&#39;t forget to save at the
end.

Here are the different characteristics to configure an object :

-   **Object name** : The name of your object.

-   **Dad** : Indicates the relative of the current object, this allows
    deende a hierarchy between objects. For example : The living room has
    to relative the apartment. An object can only have one relative
    but several objects can have the same relative.

-   **Jeedom** : Check this box to make this object visible.

-   **Hide on the dashboard** : Check this box to hide
    the object on the dashboard. It is still kept in the
    list, which allows you to display it, but only
    explicitly.

-   **Icon** : Allows you to choose an icon for your object.

-   **Tag color** : Allows you to choose the color of the object and
    equipment attached to it.

-   **Tag text color** : Allows you to choose the text color
    of the object. This text will be over the **Tag color**. To you
    to choose a color to make the text readable.

-   **Summary text color** : Allows you to choose the color of
    results of the object summary in the dashboard.

-   **Size on the dashboard (1 to 12)** : Allows you to deende the width
    the display of this object in the dashboard. For example : if you
    put `6` to two consecutive objects in the list, then it
    will be side by side on the dashboard. If you put `3` to four
    objects that follow each other, they will also be side by side.

> **Tip**
>
> You can change the display order of objects in the dashboard.
> In the menu, on the left of your page, use the vertical arrows
> drag and drop to give them a new place.

> **Tip**
>
> You can see a graph representing all the elements of Jeedom
> attached to this object by clicking on the button **Connections**, up at
> right.

> **Tip**
>
> When a device is created and no relative has been deended, it
> will have as relative : **No** .

Summary tab 
-------------

Summaries are global information, assigned to an object, which
are displayed in particular on the dashboard next to the name of the latter.

### Bulletin board 

The columns represent the summaries assigned to the current object. Three
lines are proposed to you :

-   **Go up in the global summary** : Check the box if you
    want the summary to be displayed in the menu bar
    from Jeedom.

-   **Hide on desktop** : Check the box if you do not want
    the summary is displayed next to the name of the object on the dashboard.

-   **Hide on mobile** : Check the box if you do not want
    the summary is displayed when you view it from a mobile.

### Orders 

Each tab represents a type of summary deended in the configuration
from Jeedom. Click on **Add an order** so that it is
taken into account in the summary. You have the choice to select the
order any Jeedom equipment, even if it is not for
relative this object.

> **Tip**
>
> If you want to add a summary type or to configure the
> method of calculating the result, the unit, the icon and the name of a summary,
> you have to go to the general configuration of Jeedom :
> **Administration → Configuration → Summaries tab**.

Overview 
==============

The overview allows you to view all the objects in
Jeedom, as well as their configuration :

-   **Id** : Object Id.

-   **Object** : Object name.

-   **Dad** : Name of relative object.

-   **Jeedom** : Object visibility.

-   **Mask** : Indicates if the object is hidden on the dashboard.

-   **Summary Deended** : Indicates the number of orders per summary. This
    which is in blue is taken into account in the global summary.

-   **Hidden Dashboard Summary** : Show hidden summaries on
    the dashboard.

-   **Hidden Mobile Summary** : Show hidden summaries on
    the cellphone.


