# Summary
**Home → Overview**

The *Overview* page provides a visual, centralized overview of your objects and their summaries.

![Summary](../images/synthesis_intro.gif)

To make the most of it, you need to set up the summaries and a background image for each object: [See the documentation on summaries.](https://doc.jeedom.com/concept/en_US/summary)

You can set it as the default page in **Settings → Preferences**, on desktop and/or mobile.

## Display

For each object, its background image and summaries are displayed:

Depending on the type of summary (key), visible in **Settings → System → Configuration / Summaries**:
- Top left: The name of the object.
- Top right: Temperature (*temperature*) and humidity (*humidity*).
- Under the headings: Alert (*security*) and Motion (*motion*), colored green or red depending on whether there is one or more alerts or motion events in progress.
- Below: All other summaries for this object.

You can configure the object's display in **Tools → Objects**:
- Its name.
- If it is visible. However, you can keep an Object visible—even on the Dashboard—by checking *Hide on summary*.
- Its background image.
- Whether the background image should be used only on the Overview page or also as the background once the object is selected.
- The items in its summaries, in the *Summary* tab.

> **Tip**
>
> The order in which objects are displayed is the same as in the *home automation summary*, which you can reorder (Analysis → Home Automation Summary).

> **Note**
>
> If an object does not have a summary defined, a button appears in the upper-right corner to access the object's settings/summaries.

## Features

For each object, you can click:
- Click the object's title to view the dashboard for that object and its child objects (also accessible via the *Home → Dashboard → Object* menu).
- In the image, click here to open the page showing only the devices associated with this object.
- Click on a summary item to view the items associated with that object or type. These items appear in a modal window without leaving the current page, allowing you to quickly close a shutter, turn on a light, and so on. The size of the modal window adjusts to fit the devices it displays, but you can move it around. Even with the summary modal open, you can click on other summaries.


> **Tip**
>
> Ctrl-click or middle-click on the object or a summary item to open another tab in your browser.

When you click on an object from the overview, the button to the left of the search bar is replaced by a button that displays a preview of the rooms so you can navigate to them more quickly.

