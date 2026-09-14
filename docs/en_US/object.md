# Objects
**Tools → Objects**

**Objects** allow you to define the structure of your home automation system.

All devices you create must belong to an object, which makes them easier to find. In this case, the object is said to be the **parent** of the device.

To give you the freedom to customize, you can name these objects however you like. Typically, you’ll use them to identify different areas of your home, such as the names of the rooms (which is, in fact, the recommended setup).

![Objects](../images/object_intro.gif)

## Management

You have two options:
- **Add**: Allows you to create a new object.
- **Overview**: Displays a list of the created objects and their configurations.

## Overview

The overview allows you to view all objects in Jeedom, as well as their configuration:

- **ID**: Object ID.
- **Object**: Name of the object.
- **Parent**: Name of the parent object.
- **Visible**: Visibility of the object.
- **Hidden**: Indicates whether the object is hidden on the Dashboard.
- **Summary Defined**: Indicates the number of commands per summary. Items in blue are included in the overall summary.
- **Hidden Dashboard Summary**: Shows the summaries that are hidden on the Dashboard.
- **Hidden Mobile Summary**: Indicates summaries that are hidden on mobile devices.

## My objects

Once you've created an object, it will appear in this section.

> **Tip**
>
> You can open an object by doing the following:
> - Click on one of them.
> - Ctrl-click or middle-click to open it in a new browser tab.

You have a search engine that allows you to filter the list of objects. Pressing the Esc key cancels the search.
To the right of the search field are three buttons that appear in several places throughout Jeedom:

- The cross icon to cancel the search.
- The folder is open to expand all the panels and display all the objects.
- The folder closes to fold up all the panels.

Once you're in an object's configuration, you can access a context menu by right-clicking on the object's tabs. You can also use Ctrl+click or the middle mouse button to open another object directly in a new browser tab.

## "Object" tab

Clicking on an object takes you to its configuration page. Whatever changes you make, don't forget to save them.

Here are the various properties for configuring an object:

#### Settings:

- **Object Name**: The name of your object.
- **Parent object**: Indicates the parent of the current object; this allows you to define a hierarchy among objects. For example: The living room has the apartment as its parent. An object can have only one parent, but multiple objects can share the same parent.
- **Visible**: Check this box to make this object visible.
- **Hide on the Dashboard**: Check this box to hide the object on the Dashboard. It will still appear in the list, so you can view it, but only if you explicitly select it.
- **Hide on Summary**: Check this box to hide the object on the summary. It is still retained in the list, which allows you to display it, but only explicitly.
- **Action from the Overview**: Here, you can specify a view or design to navigate to when you click on the object from the Overview. *Default: Dashboard*.

#### Display:

- **Icon**: Lets you choose an icon for your object.
- **Custom Colors**: Makes the two custom color settings below active.
- **Tag Color**: Allows you to choose the color of the object and the devices associated with it.
- **Tag Text Color**: Allows you to choose the color of the object’s text. This text will appear over the **tag color**. It’s up to you to choose a color that makes the text legible.
- **For the summary only**: Allows you to add an image to the summary without using it as a background image, particularly on this object's *Dashboard* page.
- **Image**: You can upload an image or delete it. If saved in JPEG format, this image will serve as the background image for the object when you view it on the Dashboard. It will also be used as the thumbnail for the room on the Overview page.

> **Tip**
>
> You can change the order in which objects appear on the Dashboard by going to the Home Automation Summary (Analysis -> Home Automation Summary) and selecting an object with your mouse, then dragging and dropping it to a new location.

> **Tip**
>
> You can view a diagram showing all Jeedom elements associated with this object by clicking the **Links** button in the upper-right corner.

> **Tip**
>
> When a device is created and no parent has been defined, its parent will be: **None**.

## Summaries Tabs

[See the documentation on summaries.](https://doc.jeedom.com/concept/en_US/summary)


