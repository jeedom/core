# Recherche
**Analysis menu → Search**

Jeedom offers an internal search engine.

![Research](./images/search_intro.gif)

You can do searches of different types :

## By Equipment

Select a device with the icon to the right of the field.

The engine will display in the tables below :

- The **Scenarios** using this equipment.
- The **designs** displaying this equipment.
- The **views** displaying this equipment.
- The **interactions** using this equipment.
- Others **equipment** using this equipment.
- The **orders** using this equipment.

## By order

Select an order with the icon to the right of the field.

The engine will display in the tables below :

- The **Scenarios** using this command.
- The **designs** displaying this command.
- The **views** displaying this command.
- The **interactions** using this command.
- The **equipment** using this command.
- Others **orders** using this command.

## By Variable

Select a variable from the drop-down list.

The engine will display in the tables below :

- The **Scenarios** using this variable.
- The **interactions** using this variable.
- The **equipment** using this variable.
- The **orders** using this variable.

## By Plugin

Select a plugin from the drop-down list.

The engine will display in the tables below :

- The **Scenarios** using this plugin.
- The **designs** displaying this plugin.
- The **views** displaying this plugin.
- The **interactions** using this plugin.
- The **equipment** using this plugin.
- The **orders** using this plugin.

## By Word

Enter a character string in the search field. Validate with *enter* or with the button *Search*.

The engine will display in the tables below :

- The **Scenarios** using this string.
	Search in expressions, comments, code blocks.
- The **interactions** using this string.
	Search in fields *Request*.
- The **equipment** using this string.
	Search in fields *name*, *logicalId*, *eqType*, *How? 'Or' What*, *tags*.
- The **orders** using this string.
	Search in fields *name*, *logicalId*, *eqType*, *generic_type*, .
- The **notes** using this string.
	Notes text search.

## By ID

Enter a number corresponding to a searched Id in the search field. Validate with *enter* or with the button *Search*.

The engine will display in the tables below :

- The **Scenario** having this Id.
- The **design** having this Id.
- The **view** having this Id.
- L'**interaction** having this Id.
- L'**equipment** having this Id.
- The **ordered** having this Id.
- The **note** having this Id.

## Results

For each type of result, it allows actions:
- **Scenarios** : Open the scenario log, or go to the scenario page, with the active search for the term sought.
- **designs** : Show design.
- **views** : Show view.
- **interactions** : Open the interaction configuration page.
- **equipment** : Open the equipment configuration page.
- **orders** : Open the order configuration.
- **notes** : Open Note.

Each of these options opens a different tab in your browser so as not to lose the current search.

