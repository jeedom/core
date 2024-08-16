# Recherche
**Analysis menu → Search**

Jeedom offers an internal search engine.

![Research](./images/search_intro.gif)

You can do searches of different types :

## By Equipment

Select a device with the icon to the right of the field.

The engine will display in the tables below :

- THE **Scenarios** using this equipment.
- THE **designs** displaying this equipment.
- THE **views** displaying this equipment.
- THE **interactions** using this equipment.
- Others **equipment** using this equipment.
- THE **orders** using this equipment.

## By order

Select an order with the icon to the right of the field.

The engine will display in the tables below :

- THE **Scenarios** using this command.
- THE **designs** displaying this command.
- THE **views** displaying this command.
- THE **interactions** using this command.
- THE **equipment** using this command.
- Others **orders** using this command.

## By Variable

Select a variable from the drop-down list.

The engine will display in the tables below :

- THE **Scenarios** using this variable.
- THE **interactions** using this variable.
- THE **equipment** using this variable.
- THE **orders** using this variable.

## By Plugin

Select a plugin from the drop-down list.

The engine will display in the tables below :

- THE **Scenarios** using this plugin.
- THE **designs** displaying this plugin.
- THE **views** displaying this plugin.
- THE **interactions** using this plugin.
- THE **equipment** using this plugin.
- THE **orders** using this plugin.

## By Word

Enter a character string in the search field. Validate with *enter* or with the button *To research*.

The engine will display in the tables below :

- THE **Scenarios** using this string.
	Search in expressions, comments, code blocks.
- THE **interactions** using this string.
	Search in fields *Request*.
- THE **equipment** using this string.
	Search in fields *name*, *logicalId*, *eqType*, *how*, *tags*.
- THE **orders** using this string.
	Search in fields *name*, *logicalId*, *eqType*, *generic_type*, .
- THE **notes** using this string.
	Notes text search.

## By ID

Enter a number corresponding to a searched Id in the search field. Validate with *enter* or with the button *To research*.

The engine will display in the tables below :

- THE **Scenario** having this Id.
- THE **design** having this Id.
- There **view** having this Id.
- L'**interaction** having this Id.
- L'**equipment** having this Id.
- There **order** having this Id.
- There **note** having this Id.

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

