# Recherche
**Analysis menu → Search**

Jeedom offers an internal search engine.

![Search](./images/search_intro.gif)

You can do searches of different types :

## By Equipment

Select a device with the icon to the right of the field.

The engine will display in the tables below :

- The **Scenarios** using this equipment.
- The **Designs** displaying this equipment.
- The **View** displaying this equipment.
- The **interactions** using this equipment.
- Others **Equipment** using this equipment.
- The **Commands** using this equipment.

## By order

Select an order with the icon to the right of the field.

The engine will display in the tables below :

- The **Scenarios** using this command.
- The **Designs** displaying this command.
- The **View** displaying this command.
- The **interactions** using this command.
- The **Equipment** using this command.
- Others **Commands** using this command.

## By Variable

Select a variable from the drop-down list.

The engine will display in the tables below :

- The **Scenarios** using this variable.
- The **interactions** using this variable.
- The **Equipment** using this variable.
- The **Commands** using this variable.

## By Plugin

Select a plugin from the drop-down list.

The engine will display in the tables below :

- The **Scenarios** using this plugin.
- The **Designs** displaying this plugin.
- The **View** displaying this plugin.
- The **interactions** using this plugin.
- The **Equipment** using this plugin.
- The **Commands** using this plugin.

## By Word

Enter a character string in the search field. Validate with *Enter* or with the button *Search*.

The engine will display in the tables below :

- The **Scenarios** using this string.
	Search in expressions, comments, code blocks.
- The **interactions** using this string.
	Search in fields *Request*.
- The **Equipment** using this string.
	Search in fields *name*, *logicalId*, *eqType*, *How? 'Or' What*, *Tags*.
- The **Commands** using this string.
	Search in fields *name*, *logicalId*, *eqType*, *generic_type*, .
- The **Notes** using this string.
	Notes text search.

## By ID

Enter a number corresponding to a searched Id in the search field. Validate with *Enter* or with the button *Search*.

The engine will display in the tables below :

- The **Scenario** having this Id.
- The **Design** having this Id.
- The **View** having this Id.
- L'**Interaction** having this Id.
- L'**Equipment** having this Id.
- The **Command** having this Id.
- The **NOTE** having this Id.

## Results

For each type of result, it allows actions:
- **Scenarios** : Open the scenario log, or go to the scenario page, with the active search for the term sought.
- **Designs** : Show design.
- **View** : Show view.
- **interactions** : Open the interaction configuration page.
- **Equipment** : Open the equipment configuration page.
- **Commands** : Open the order configuration.
- **Notes** : Open Note.

Each of these options opens a different tab in your browser so as not to lose the current search.

