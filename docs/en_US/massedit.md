# Bulk Editor
**Settings → System → Configuration | OS/DB**

This tool allows you to edit a large number of devices, commands, objects, or scenarios. It is completely generic and automatically adopts the schema and structure of the Jeedom database. It thus supports plugins and the configuration of their devices.

> **Warning**
>
> While this tool is fairly easy to use, it is intended for advanced users. In fact, it is very easy to change any setting on dozens of devices or hundreds of commands, which can render certain functions—or even the Core itself—inoperable.

## Usage

The *Filter* section lets you select what you want to edit, then add selection filters based on their parameters. A test button lets you see, without making any changes, the items selected by the specified filters.

The *Edit* section allows you to change settings for these items.

- **Column**: Setting.
- **Value**: The value of the parameter.
- **JSON value**: The parameter property/value if the parameter is of type JSON (key->value).

### Examples:

#### Rename a group of scenarios

- In the *Filter* section, select **Scenario**.
- Click the **+** button to add a filter.
- In this filter, select the *group* column, and enter the name of the group you want to rename as the value.
- Click the *Test* button to view the scenarios in this group.
- In the *Edit* section, select the *group* column, then enter the name you want in the value field.
- Click **Run** in the upper-right corner.

#### Hide all devices in an object/room:

- In the *Filter* section, select **Equipment**.
- Click the **+** button to add a filter.
- In this filter, select the *object_id* column and enter the ID of the object in question (visible under Tools/Objects, Overview).
- Click the *Test* button to view the scenarios in this group.
- In the *Edit* section, select the *isvisible* column, then enter the value 0.
- Click **Run** in the upper-right corner.
