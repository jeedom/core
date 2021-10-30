# Objets
**Tools → Objects**

The **Objects** allow you to define the tree structure of your home automation.

All the equipment you create must belong to an object and are therefore more easily identifiable. We then say that the object is the **parent** equipment.

To give free choice to personalization, you can name these objects as you wish. Usually, we will define the different parts of his house, like the name of the rooms (this is also the recommended configuration).

![Objects](./images/object_intro.gif)

## Gestion

You have two options :
- **Add** : Create a new object.
- **Overview** : Displays the list of objects created and their configuration.

## Overview

The overview allows you to view all the objects in Jeedom, as well as their configuration :

- **ID** : Object ID.
- **Object** : Object name.
- **Dad** : Name of parent object.
- **Visible** : Object visibility.
- **Mask** : Indicates if the object is hidden on the Dashboard.
- **Summary Defined** : Indicates the number of commands per summary. What is in blue is taken into account in the global summary.
- **Hidden Dashboard Summary** : Indicates hidden summaries on the Dashboard.
- **Hidden Mobile Summary** : Show hidden summaries on mobile.

## My objects

Once you have created an object, it will appear in this part.

> **Tip**
>
> You can open an object by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

You have a search engine to filter the display of objects. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:

- The cross to cancel the search.
- The open folder to unfold all the panels and display all the objects.
- The closed folder to fold all the panels.

Once on the configuration of an object, you have a contextual menu with the Right Click on the tabs of the object. You can also use a Ctrl Click or Center Click to directly open another object in a new browser tab.

## Object tab

By clicking on an object, you access its configuration page. Whatever changes you make, don&#39;t forget to save your changes.

Here are the different characteristics to configure an object :

#### Settings :

- **Object name** : The name of your object.
- **Parent object** : Indicates the parent of the current object, this makes it possible to define a hierarchy between the objects. For example : The living room is related to the apartment. An object can have only one parent but several objects can have the same parent.
- **Visible** : Check this box to make this object visible.
- **Hide on the Dashboard** : Check this box to hide the object on the Dashboard. It is still kept in the list, which allows it to be displayed, but only explicitly.
- **Hide on summary** : Check this box to hide the object on the summary'. It is still kept in the list, which allows it to be displayed, but only explicitly.
- **Action from synthesis** : Here you can indicate a view or a design to go to when you click on the object from the Summary. *Default : Dashboard*.

#### Display :

- **Icon** : Allows you to choose an icon for your object.
- **Custom colors** : Activates the taking into account of the two custom color parameters below.
- **Tag color** : Allows you to choose the color of the object and the equipment attached to it.
- **Tag text color** : Allows you to choose the color of the text of the object. This text will be over the **Tag color**. You choose a color to make the text readable.
- **Only on synthesis** : Allows you to put an image for the Synthesis without it being used as a background image, especially on the page *Dashboard* of this object.
- **Picture** : You have the option to upload an image or delete it. In jpeg format this image will be the background image of the object when you display it on the Dashboard. It will also be used for the thumbnail of the piece on the Synthesis.

> **Tip**
>
> You can change the display order of objects in the Dashboard. In the overview (or by the Home Automation Summary), select your object with the mouse with a drag and drop to give it a new place.

> **Tip**
>
> You can see a graph representing all the elements of Jeedom attached to this object by clicking on the button **Connections**, top right.

> **Tip**
>
> When a device is created and no parent has been defined, it will have as parent : **No**.

## Summary tabs

[See abstracts documentation.](/en_US/concept/summary)


