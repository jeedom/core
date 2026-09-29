# Interactions
**Tools → Interactions**

Jeedom’s interaction system allows you to perform actions using text or voice commands.

These commands can be obtained by:

- SMS: Send a text message to issue commands (action) or ask a question (info).
- Chat: Telegram, Slack, etc.
- Voice: Dictate a phrase using Siri, Google Now, SARAH, etc., to issue commands (actions) or ask a question (for information).
- HTTP: Send an HTTP URL containing text (e.g., Tasker, Slack) to trigger commands (action) or ask a question (info).

The benefit of these interactions lies in their simplified integration with other systems, such as smartphones, tablets, other home automation hubs, etc.

> **Tip**
>
> You can start an interaction by:
> - Click on one of them.
> - Ctrl-click or middle-click to open it in a new browser tab.

You have a search engine that allows you to filter the list of interactions. Pressing the Esc key cancels the search.
To the right of the search field are three buttons that appear in several places throughout Jeedom:
- The cross icon to cancel the search.
- The folder is open to expand all panels and display all interactions.
- The folder closes to fold up all the panels.

Once you're in the interaction configuration screen, you can access a context menu by right-clicking on the interaction tabs. You can also use Ctrl-click or middle-click to open another interaction directly in a new browser tab.

## Interactions

At the top of the page, there are 3 buttons:

- **Add**: Allows you to create new interactions.
- **Refresh**: Recreate all interactions (may take a long time—more than 5 minutes).
- **Test**: Opens a dialog box where you can type and test a phrase.

> **Tip**
>
> If you have an interaction that generates commands for the lights, for example, and you add a new light control module, you’ll need to either regenerate all interactions or go to the interaction in question and save it again to create the commands for this new module.

## Principle

The concept behind this is quite simple: we’ll define a template phrase that Jeedom can use to generate one or several hundred other phrases, which will be possible variations of the template.

We’ll define responses in the same way using a template (this allows Jeedom to have multiple responses for a single question).

You can also define a command to be executed if, for example, the interaction is not related to an action but to a piece of information, or if you want to perform a specific action after that interaction (it is also possible to run a scenario, control multiple commands, etc.).

## Setup

The configuration page consists of several tabs and buttons:

- **Phrases**: Displays the number of phrases in the interaction (clicking on it shows you the phrases).
- **Record**: Records the current interaction.
- **Delete**: Deletes the current interaction.
- **Duplicate**: Duplicates the current interaction.

### General Tab

- **Name**: Name of the interaction (can be left blank; the name replaces the request text in the list of interactions).
- **Group**: Interaction group; this allows you to organize them (can be empty, in which case it will be in the "none" group).
- **Active**: Enables or disables the interaction.
- **Request**: The template phrase (required).
- **Synonym**: Allows you to define synonyms for command names.
- **Answer**: The answer to be provided.
- **Wait before responding (s)**: Adds a delay of X seconds before generating a response. For example, this allows the system to wait for a lamp's status update before responding.
- **Binary Conversion**: Converts binary values to open/closed, for example (only for binary info type commands).
- **Authorized Users**: Restricts interaction to specific users (logins separated by \|).

### Filters tab

- **Limit to command types**: Allows you to use only actions, information, or both types.
- **Limit to commands with the following subtype**: Allows you to limit generation to one or more subtypes.
- **Limit to commands with the following unit**: Allows you to limit the generation to one or more units (Jeedom automatically creates the list based on the units defined in your commands).
- **Limit to commands belonging to the object**: Allows you to limit the generation to one or more objects (Jeedom automatically creates the list based on the objects you have created).
- **Limit to plugin**: Allows you to limit the generation to one or more plugins (Jeedom automatically creates the list based on the installed plugins).
- **Limit to category**: Allows you to limit the generation to one or more categories.
- **Limit to devices**: Allows you to limit the list to a single device or module (Jeedom automatically generates the list based on the devices and modules you have).

### Actions Tab

Use this if you want to target one or more specific commands or pass specific parameters.

#### Examples

> **Note**
>
> Screenshots may vary depending on updates.

#### Simple interaction

The simplest way to configure an interaction is to assign it a rigid generator template with no possible variations. This method will target a specific command or scenario very precisely.

In the following example, the "Request" field shows the exact phrase you need to say to trigger the interaction. In this case, to turn on the living room ceiling light.

![interact004](../images/interact004.png)

In this screenshot, you can see the configuration for setting up an interaction linked to a specific action. This action is defined in the "Action" section of the page.

It’s easy to imagine doing the same thing with multiple actions to turn on several lights in the living room, as in the following example:

![interact005](../images/interact005.png)

In the two examples above, the template phrase is identical, but the resulting actions vary depending on what is configured in the “Action” section. Therefore, even with a simple, single-phrase interaction, we can already envision combined actions involving various commands and scenarios (scenarios can also be triggered in the “Action” section of interactions).

> **Tip**
>
> To add a scenario or create a new action, type "scenario" (without an accent), then press the Tab key on your keyboard to bring up the scenario selector.

#### Multiple Command Interaction

Here, we’ll explore the benefits and power of interactions; using a template phrase, we’ll be able to generate phrases for an entire group of commands.

We’ll go back to what we did earlier, delete the actions we added, and instead of the fixed phrase in “Request,” we’ll use the tags **\#command\#** and **\#device\#**. Jeedom will then replace these tags with the names of the commands and the device (this highlights the importance of having consistent command and device names).

![interact006](../images/interact006.png)

So we can see here that Jeedom generated 152 sentences based on our model. However, they aren’t very well constructed, and there’s a bit of everything.

To organize all of this, we’ll use the filters (on the right side of our configuration page). In this example, we want to generate phrases to turn on lights. So we can uncheck the “info” command type (if I save, I’ll be left with only 95 generated phrases), and then, in the subtypes, we can keep only “default” checked, which corresponds to the action button (leaving only 16 phrases).

![interact007](../images/interact007.png)

That’s better, but we can make it sound even more natural. If I take the generated example “In the entryway,” it would be nice to be able to change this phrase to “turn on the entryway” or “light up the entryway.” To do this, Jeedom has a “Synonym” field below the “Request” field that lets us give different names to the commands in our “generated” phrases. Here, it’s “on”; I even have “on2” in the modules that can control two outputs.

In the "Synonyms" section, you'll enter the name of the command and the synonym(s) to use:

![interact008](../images/interact008.png)

Here we see a slightly new syntax for synonyms. A command name can have multiple synonyms; in this case, "on" has "turn on" and "switch on" as synonyms. The syntax is therefore "*command name*" ***=*** "*synonym 1*"***,*** "*synonym 2*" (you can include as many synonyms as you like). Then, to add synonyms for another command name, simply add a vertical bar "*\|*" after the last synonym, followed by the name of the command for which you want to define synonyms—just as in the first section—and so on.

That’s an improvement, but the command “on” and “input” are still missing the article “l’,” and others are missing “la,” “le,” or “un,” etc. We could modify the device name to include it—that would be one solution—or we could use variations in the request. This involves listing a series of possible words for a specific position in the sentence; Jeedom will then generate sentences using these variations.

![interact009](../images/interact009.png)

We now have some slightly more correct sentences, along with some that aren’t quite right—for our example, “on” and “entrance.” So we find “Turn on entry,” “Turn on an entry,” “Turn on an entry,” “Turn on the entry,” etc. We therefore have all possible variations with what we’ve added between the “\[ \]” for each synonym, which quickly generates a lot of sentences (168 in this case).

To refine the list and avoid unlikely commands such as “turn on the TV,” we can allow Jeedom to filter out syntactically incorrect requests. It will therefore remove commands that deviate too far from the actual syntax of a sentence. In our case, this reduces the number of phrases from 168 to 130.

![interact010](../images/interact010.png)

It’s therefore important to carefully construct your template phrases and synonyms, as well as to select the right filters to avoid generating too many unnecessary phrases. Personally, I find it helpful to have a few inconsistencies, such as “un entrée,” because if you have a foreign guest who doesn’t speak French very well, the interactions will still work.

### Customize Responses

Until now, in response to an interaction, we’ve had a simple sentence that didn’t say much other than that something had happened. The idea is for Jeedom to tell us what it did in a bit more detail. That’s where the “response” field comes in—it allows us to customize the feedback based on the command that was executed.

To do this, we’ll use Jeedom tags again. For our lights, we can use a phrase like: “I turned on \#equipment\#” (see screenshot below).

![interact011](../images/interact011.png)

You can also add any value from another command, such as a temperature, a number of people, etc.

![interact012](../images/interact012.png)

### Binary conversion

Binary conversions apply to "info" commands whose type is binary (returns only 0 or 1). You must therefore enable the appropriate filters, as shown in the screenshot below (for categories, you can check all of them; for this example, I’ve selected only “light”).

![interact013](../images/interact013.png)

As you can see here, I’ve kept the request structure almost exactly the same (this was intentional to focus on the specifics). Of course, I’ve adapted the synonyms to ensure consistency. However, for the response, it is **essential** to include only \#value\#, which represents the 0 or 1 that Jeedom will replace with the following binary conversion.

The **binary conversion** field must contain two responses: first, the response if the command value is 0, followed by a vertical bar "\|" as a separator, and finally the response if the command value is 1. Here, the responses are simply "no" and "yes," but you could use a slightly longer sentence instead.

> **Warning**
>
> Tags do not work in binary conversions.

### Authorized users

The "Authorized Users" field allows you to restrict the command to specific people; you can enter multiple profiles by separating them with a "\|".

Example: person1\|person2

It’s possible that an alarm could be turned on or off by a child or a neighbor who comes over to water your plants while you’re away.

### Exclusion regexp

It is possible to create [Regexp](https://fr.wikipedia.org/wiki/Expression_rationnelle) Exclusion: if a generated phrase matches this regex, it will be deleted. The purpose is to eliminate false positives—that is, a phrase generated by Jeedom that makes something active or interferes with another interaction that uses a similar phrase.

There are two places where you can apply a regular expression:
- In the interaction itself, in the "Exclusion Regexp" field.
- In the Administration→Configuration→Interactions menu, under the "General exclusion regexp for interactions" field.

For the "General Exclusion Regex for Interactions" field, this rule will apply to all interactions that are created or saved from this point forward. If you want to apply it to all existing interactions, you must regenerate the interactions. Generally, this is used to remove incorrectly formed phrases found in most generated interactions.

For the "Exclusion Regexp" field on the configuration page of each interaction, you can enter a specific regular expression that will apply only to that interaction. This allows you to filter out interactions more precisely. It can also be used to exclude an interaction for a specific command when you do not want to offer that option as part of a batch command.

The following screenshot shows the interaction without the regular expression. In the list on the left, I’ve filtered the sentences to show you only those that will be deleted. In reality, there are 76 sentences generated by the interaction’s configuration.

![interact014](../images/interact014.png)

As you can see in the screenshot below, I added a simple regular expression that searches for the word "Julie" in the generated sentences and removes them. However, as you can see in the list on the left, there are still sentences containing the word “julie.” In regular expressions, “Julie” is not the same as “julie”; this is called case sensitivity—in other words, uppercase letters are treated differently from lowercase letters. As you can see in the following screenshot, only 71 sentences remain; the 5 containing “Julie” have been removed.

A regular expression is structured as follows:

- First, a delimiter—in this case, a forward slash "/" placed at the beginning and end of the expression.
- The character following the slash represents any character, space, or number.
- The "\*" symbol indicates that the character preceding it—in this case, a period—may appear zero or more times; in other words, any element.
- Then "Julie," which is the search term (word or other phrase), followed again by a period and then a slash.

If we translate this expression into a sentence, it would be "search for the word 'Julie' preceded by anything and followed by anything."

This is an extremely simple version of regular expressions, but it’s still quite complicated to understand. It took me a while to figure out how it works. As a slightly more complex example, here’s a regexp to validate a URL:

/\^(https?:\\/\\/)?(\[\\da-z\\.-\]+)\\.(\[a-z\\.\]{2,6})(\[\\/\\w\\.-\]\*)\*\\/?\$/

Once you can write that, you’ve got regular expressions down.

![interact015](../images/interact015.png)

To resolve the issue of uppercase and lowercase letters, we can add an option to our expression that makes it case-insensitive—in other words, one that treats lowercase letters as equivalent to uppercase letters. To do this, we simply need to add an "i" at the end of our expression.

![interact016](../images/interact016.png)

When we add the "i" option, we see that only 55 generated sentences remain, but in the list on the left—using the "julie" filter to search for sentences containing that word—we see that there are actually many more.

Since this is an extremely complex topic, I won’t go into further detail here—there are plenty of tutorials online to help you—and don’t forget that Google is your friend, too, because yes, it’s my friend; it’s what taught me to understand regular expressions and even how to code. So if it helped me, it can help you, too, if you’re willing to put in the effort.

Useful links:

- <http://www.commentcamarche.net/contents/585-javascript-l-objet-regexp>
- <https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>
- <https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/les-expressions-regulieres-partie-1-2>

### Answer consisting of several pieces of information

It is also possible to include multiple info commands in a single response, for example, to provide a summary of the situation.

![interact021](../images/interact021.png)

In this example, we see a simple query that will return a response with three different temperatures; so here, we can include just about anything we want to get a set of information all at once.

### Is anyone in the room?

#### Basic version

- So the question is, “Is anyone in the room?”
- The response will be "No, there's no one in the room" or "Yes, there's someone in the room."
- The command for that is "\#\[Julie's Room\]\[FGMS-001-2\]\[Presence\]\#"

![interact017](../images/interact017.png)

This example specifically targets a particular device, which allows for a personalized response. So, we could imagine replacing the response in the example with "no, there's no one in *julie*'s room\|yes, there's someone in *julie*'s room"

#### Evolution

- So the question is "#command# [in the |in the] #object#"
- The response will be "no, there is no one in the room" or "yes, there is someone in the room"
- There is no command that corresponds to this in the Action section, since it is a Multiple Commands interaction.
- By adding a regular expression, you can filter out the commands you don’t want to see so that only the phrases related to “Presence” commands remain.

![interact018](../images/interact018.png)

Without the regex, we get 11 phrases here, but the purpose of my interaction is to generate phrases solely to ask if anyone is in a room, so I don’t need information about the status of lights or other devices like outlets—which can be resolved using regex filtering. To make it even more flexible, we can add synonyms, but in that case, we’ll need to remember to update the regexp.

### Monitor temperature, humidity, and light levels

#### Basic version

We could write the phrase explicitly, such as “What is the temperature in the living room?”, but we would have to create a separate phrase for each temperature, light, and humidity sensor. With Jeedom’s phrase generation system, however, a single interaction can generate phrases for all sensors measuring these three types of data.

Here is a generic example used to monitor the temperature, humidity, and light levels in different rooms (objects as defined by Jeedom).

![interact019](../images/interact019.png)

- We can see, then, that a generic phrase type "What is the temperature in the living room?" or "How bright is the bedroom?" can be converted to: "What is \[the \|l\\'\]\#command\# object" (using \[word1 \| word2\] allows you to specify either option to generate all possible variations of the phrase using word1 or word2). During generation, Jeedom will generate all possible combinations of phrases with all existing commands (based on the filters), replacing \#command\# with the command name and \#object\# with the object name.
- The response will be of type "21 °C" or "200 lux." Simply enter: \#value\# \#unit\# (the unit must be specified in the configuration of each command for which you want one)
- This example generates a sentence for all commands of the "numeric info" type that include a unit, so you can uncheck units in the filter on the right to limit the results to the type you're interested in.

#### Evolution

So we can add synonyms to the command name to make it sound more natural, and add a regular expression to filter out commands that have nothing to do with our interaction.

Adding a synonym allows you to tell Jeedom that a command called "X" can also be called "Y." So, in our sentence, if we say "turn on y," Jeedom knows that this means "turn on x." This method is very useful for renaming commands that, when displayed on screen, are written in a way that doesn’t sound natural when spoken or fit into a written sentence—such as “ON.” A button labeled that way makes perfect sense on its own, but not within the context of a sentence.

You can also add a Regexp filter to remove certain commands. Using the simple example, we see phrases like "battery" or "latency," which have nothing to do with our temperature/humidity/light interaction.

![interact020](../images/interact020.png)

So here's a regex:

**(battery\|latency\|pressure\|speed\|power consumption)**

This allows you to remove all commands that contain any of these words in their text

> **Note**
>
> The regex here is a simplified version for ease of use. You can therefore either use traditional expressions or use simplified expressions as in this example.

### Control a dimmer or a thermostat (slider)

#### Basic version

You can control a light using a percentage setting (dimmer) or a thermostat using interactions. Here’s an example of how to control a light dimmer using interactions:

![interact022](../images/interact022.png)

As you can see, the request here contains the tag **\#consigne\#** (you can use any name you like), which is included in the dimmer command to apply the desired value. To do this, there are three parts: \* Request: where you create a tag that will represent the value to be sent to the interaction. \* Response: you reuse the tag in the response to ensure that Jeedom correctly understood the request. \* Action: you set an action for the light you want to control and pass our *consigne* tag as the value.

> **Note**
>
> You can use any tag except those already used by Jeedom; you can use multiple tags to control, for example, several commands. Also note that all tags are passed to the scenarios triggered by the interaction (however, the scenario must be set to “Run in foreground”).

#### Evolution

You may want to control all slider-type commands with a single interaction. With the following example, you’ll be able to control multiple dimmers with a single interaction and thus generate a set of phrases to control them.

![interact033](../images/interact033.png)

In this interaction, there are no commands in the action section; we let Jeedom generate the list of phrases based on the tags. You can see the tag **\#slider\#**. It is essential to use this tag for commands in a multi-command interaction; it does not have to be the last word in the phrase. You can also see in the example that you can use a tag in the response that isn’t part of the request. Most of the tags available in scenarios are also available in interactions and can therefore be used in a response.

Result of the interaction:

![interact034](../images/interact034.png)

Note that the **\#equipment\#** tag, which is not used in the request, is included in the response.

### Controlling the color of an LED strip

You can control a color command through interactions—for example, by asking Jeedom to turn on an LED strip in blue. Here’s the interaction you need to set up:

![interact023](../images/interact023.png)

So far, nothing too complicated, but you do need to have configured the colors in Jeedom for this to work; go to the menu → Settings (top right), then to the "Interaction Settings" section:

![interact024](../images/interact024.png)

As you can see in the screenshot, no colors have been configured yet, so you’ll need to add colors using the "+" button on the right. The color name is the name you’ll assign to the interaction. Then, on the right side (in the "HTML Code" column), click on the black color to select a new color.

![interact025](../images/interact025.png)

You can add as many as you like, and you can name them anything you want—for example, you could assign a color to each family member’s name.

Once set up, you say, “Turn the Christmas tree green,” and Jeedom will look for a color in the request and apply it to the command.
### Use in conjunction with a scenario

#### Basic version

You can link an interaction to a scenario to perform actions that are slightly more complex than simply executing an action or requesting information.

![interact026](../images/interact026.png)

This example allows you to trigger the scenario linked in the "Action" section; of course, you can have multiple scenarios.

### Programming an action using interactions

Interactions let you do a lot of specific things. You can dynamically schedule an action. Example: “Set the heat to 22 at 2:50 p.m.” It’s very simple—just use the tags \#time\# (if you’re specifying a specific time) or \#duration\# (for a set amount of time, e.g., in 1 hour):

![interact23](../images/interact23.JPG)

> **Note**
>
> You'll notice the \#value\# tag in the response; in the case of a scheduled interaction, this tag contains the actual scheduled time.
