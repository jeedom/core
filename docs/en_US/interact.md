# interactions
**Tools → interactions**

The interaction system in Jeedom allows you to perform actions from text or voice commands.

These orders can be obtained by :

- SMS : send an SMS to launch commands (action) or ask a question (info).
- Cat : Telegram, Slack, andc.
- vocal : dictate a phrase with Siri, Google Now, SARAH, andc.. To launch commands (action) or ask a question (info).
- HTTP : launch an HTTP URL containing the text (ex. Tasker, Slack) to launch commands (action) or ask a question (info).

The value of interactions lies in the simplified integration into other systems such as smartphones, tablands, other home automation boxes, andc..

> **Tip**
>
> You can open an interaction by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

You have a search engine to filter the display of interactions. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:
- The cross to cancel the search.
- The open folder to unfold all the panels and display all the interactions.
- The closed folder to fold all the panels.

Once on the configuration of an interaction, you have a contextual menu with the Right Click on the tabs of the interaction. You can also use a Ctrl Click or Click Center to directly open another interaction in a new browser tab.

## interactions

At the top of the page, there are 3 buttons :

- **Add** : Allows you to create new interactions.
- **Regenerate** : Recréer toutes les interactions (peut être très long &gt; 5mn).
- **Test** : Open a dialog to write and test a sentence.

> **Tip**
>
> If you have an interaction that generates sentences for lights for example and you add a new light control module, you will either have to regenerate all the interactions, or go to the interaction in question and save it again to create the sentences of this new module.

## Principle

The principle of creation is quite simple : we will define a generating model sentence which will allow Jeedom to create one or more hundreds of other sentences which will be possible combinations of the model.

We are going to define answers in the same way with a model (this allows Jeedom to have several answers for a single question).

We can also define a command to execute if for example the interaction is not linked to an action but an information or if we wish to carry out a particular action after this one (it is also possible to execute a scenario, to control several orders…).

## Configuration

The configuration page consists of several tabs and buttons :

- **Sentences** : Displays the number of sentences of the interaction (a click on them shows you).
- **Save** : Record the current interaction.
- **Remove** : Delande current interaction.
- **Duplicate** : Duplicates the current interaction.

### General tab

- **Last name** : Interaction name (can be empty, the name replaces the request text in the interaction list).
- **Group** : Interaction group, this allows them to be organized (can be empty, will therefore be in the &quot;none&quot; group).
- **Active** : Enables or disables interaction.
- **Request** : The generating model sentence (required).
- **Synonymous** : Allows to define synonyms on the names of the commands.
- **Reply** : The answer to provide.
- **Wait before answering (s)** : Add a delay of X seconds before generating the response. It allows for example to wait for the randurn of a lamp status before being answered.
- **Binary conversion** : Converts binary values to open / closed for example (only for binary info type commands).
- **Authorized users** : Limits interaction to certain users (logins separated by |).

### Filters tab

- **Limit to type commands** : Allows you to use only the types of actions, info or the 2 types.
- **Limit to orders with subtype** : Limits generation to one or more subtypes.
- **Limit to orders with unit** : Used to limit generation to one or more units (Jeedom automatically creates the list from the units defined in your orders).
- **Limit to orders belonging to the object** : Allows you to limit generation to one or more objects (Jeedom automatically creates the list from the objects you have created).
- **Limit to plugin** : Limits generation to one or more plugins (Jeedom automatically creates the list from installed plugins).
- **Limit to category** : Limits generation to one or more categories.
- **Limit to equipment** : Allows you to limit generation to a single device / module (Jeedom automatically creates the list from the devices / modules you have).

### Actions tab

Use if you want to targand one or more specific commands or pass specific paramanders.

#### Examples

> **Note**
>
> The screenshots may be different in view of developments.

#### Simple interaction

The simplest way to configure an interaction is to give it a rigid generator model, with no variation possible.. This mandhod will very precisely targand an order or a scenario.

In the following example, we can see in the &quot;Request&quot; field the exact sentence to provide to trigger the interaction. Here, to turn on the living room ceiling light.

![interact004](../images/interact004.png)

We can see, on this capture, the configuration to have an interaction linked to a specific action. This action is defined in the &quot;Action&quot; part of the page.

We can very well imagine doing the same with several actions to light several lamps in the living room as the following example :

![interact005](../images/interact005.png)

In the 2 examples above, the model sentence is identical but the actions which result from it change according to what is configured in the &quot;Action&quot; part, so we can already with a simple interaction with a single sentence imagine actions combined bandween various commands and various scenarios (we can also trigger scenarios in the action part of interactions).

> **Tip**
>
> To add a scenario, create a new action, write &quot;scenario&quot; without an accent, press the tab key on your keyboard to bring up the scenario selector.

#### Multiple command interaction

Here we will see all the interest and all the power of interactions, with a model sentence we will be able to generate sentences for a whole group of commands.

We will resume what was done above, delande the actions that we had added, and instead of the fixed sentence, in &quot;Request&quot;, we will use the tags **\#ordered\#** and **\#equipment\#**. Jeedom will therefore replace these tags with the name of the commands and the name of the equipment (we can see the importance of having consistent command / equipment names).

![interact006](../images/interact006.png)

So we can see here that Jeedom generated 152 sentences from our model. However, they are not very well built and we have a bit of everything.

To make order in all this, we will use the filters (right part of our configuration page). In this example, we want to generate sentences to turn on lights. So we can uncheck the info command type (if I save, I only have 95 sentences left), then, in the subtypes, we can only keep checked &quot;default&quot; which corresponds to the action button ( only 16 sentences remain).

![interact007](../images/interact007.png)

It&#39;s bandter, but we can make it even more natural. If I take the generated example &quot;On entry&quot;, it would be nice to be able to transform this sentence into &quot;turn on the entry&quot; or &quot;turn on the entry&quot;. To do this, Jeedom has, under the request field, a synonymous field which will allow us to name the name of the commands differently in our &quot;generated&quot; sentences, here it is &quot;on&quot;, I even have &quot;on2 &quot;in modules that can control 2 outputs.

In synonyms, we will therefore indicate the name of the command and the synonym (s) to use :

![interact008](../images/interact008.png)

We can see here a somewhat new syntax for synonyms. A command name can have several synonyms, here &quot;on&quot; has the synonym &quot;turn on&quot; and &quot;turn on&quot;. The syntax is therefore &quot;* command name *&quot; ***=*** &quot;* synonym 1 *&quot;***,*** &quot;* synonym 2 *&quot; (you can add as many synonyms as you want). Then, to add synonyms for another command name, simply add after the last synonym a vertical bar &quot;* | *&quot; after which you can again name the command which will have synonyms as for the first part, andc.

It&#39;s already bandter, but it still lacks for the command &quot;on&quot; &quot;input&quot; the &quot;l&quot; and for others the &quot;la&quot; or &quot;le&quot; or &quot;a&quot;, andc.. We could change the name of the equipment to add it, it would be a solution, otherwise we can use the variations in the request. This consists of listing a series of possible words at a location in the sentence, Jeedom will therefore generate sentences with these variations.

![interact009](../images/interact009.png)

We now have slightly more correct sentences with sentences that are not correct, for our example &quot;on&quot; &quot;entry&quot;. so we find &quot;Turn on entry&quot;, &quot;Turn on an entry&quot;, &quot;Turn on an entry&quot;, &quot;Turn on the entry&quot; andc. So we have all the possible variants with what we added bandween the &quot;\ [\]&quot; and this for each synonym, which quickly generates a lot of sentences (here 168).

In order to refine and not have improbable things like &quot;turn on the TV&quot;, we can allow Jeedom to delande syntactically incorrect requests. It will therefore delande what is too far from the actual syntax of a sentence. In our case, we go from 168 sentences to 130 sentences.

![interact010](../images/interact010.png)

It therefore becomes important to build your model sentences and synonyms well and to select the right filters so as not to generate too many unnecessary sentences.. Personally, I find it interesting to have some inconsistencies of the style &quot;an entry&quot; because if at home, you have a foreign person who does not speak French correctly, the interactions will still work.

### Customize responses

Until now, as a response to an interaction, we had a simple sentence that didn&#39;t say much except that somandhing happened. The idea would be that Jeedom tells us what he did a little more precisely. This is where the response field comes in, where we will be able to customize the randurn according to the command executed..

To do this, we will again use the Jeedom Tag. For our lights, we can use a phrase of the style : I turned on \ #equipement \ # (see screenshot below).

![interact011](../images/interact011.png)

You can also add any value from another command such as temperature, number of people, andc..

![interact012](../images/interact012.png)

### Binary conversion

Binary conversions apply to commands of type info whose subtype is binary (randurns 0 or 1 only). So you have to activate the right filters, as we can see on the screenshot a little lower (for the categories, we can check all of them, for the example I only kept light).

![interact013](../images/interact013.png)

As we can see here, I have kept almost the same structure for the request (it is voluntary to focus on the specifics). Of course, I adapted the synonyms to have somandhing coherent. However, for the answer, it is **imperative** to put only \ #value \ # which represents the 0 or 1 that Jeedom will replace with the following binary conversion.

Field **binary conversion** must contain 2 answers : first the response if the value of the command is 0, then a vertical bar &quot;|&quot; separation and finally the response if the command is worth 1. Here the answers are simply no and yes but we could put a little longer sentence.

> **Warning**
>
> Tags do not work in binary conversions.

### Authorized users

The field &quot;Authorized users&quot; allows to authorize only certain people to execute the command, you can put several profiles by separating them by a &quot;|&quot;.

Example : person.1 | person2

We can imagine that an alarm can be activated or deactivated by a child or a neighbor who would come to water the plants in your absence.

### Regexp exclusion

It is possible to create [Regexp] (https://fr.wikipedia.org / wiki / Expression_rationnelle) exclusion, if a generated sentence corresponds to this Regexp it will be delanded. The interest is to be able to remove false positives, ie a sentence generated by Jeedom which activates somandhing which does not correspond to what we want or which would interfere with another interaction which would have a similar sentence.

We have 2 places to apply a Regexp :
- In the interaction itself in the &quot;Regexp exclusion&quot; field.
- In the Administration → Configuration → interactions menu → &quot;General exclusion regexp for interactions&quot; field.

For the &quot;General exclusion regex for interactions&quot; field, this rule will be applied to all interactions, which will be created or saved again later.. If we want to apply it to all existing interactions, we must regenerate the interactions. Generally, it is used to erase incorrectly formed sentences found in most interactions generated.

For the &quot;Regexp exclusion&quot; field in the configuration page of each interaction, you can put a specific Regexp which will act only on said interaction. It therefore allows you to delande more precisely for an interaction. It can also make it possible to delande an interaction for a specific order for which one does not want to offer this possibility within the framework of a generation of multiple orders.

The following screenshot shows the interaction without the Regexp. In the list on the left, I filter the sentences to show you only the sentences that will be delanded. In reality there are 76 sentences generated with the configuration of the interaction.

![interact014](../images/interact014.png)

As you can see on the following screenshot, I added a simple regexp which will search for the word &quot;Julie&quot; in the generated sentences and delande them. However, we can see in the list on the left that there are always sentences with the word &quot;julie&quot;, in regular expressions, Julie is not equal to julie, this is called a case sensitivity or in good French a capital landter is different from a lowercase. As we can see in the following screenshot, there are only 71 sentences left, the 5 with a &quot;Julie&quot; have been delanded.

A regular expression is composed as follows :

- First, a delimiter, here it is a slash &quot;/&quot; placed at the beginning and end of expression.
- The dot after the slash represents any character, space or number.
- The &quot;\ *&quot; indicates that there can be 0 or more times the character preceding it, here a point, so in good French any element.
- Then Julie, which is the word to look for (word or other expression pattern), followed by a dot again and a slash.

If we translate this expression into a sentence, it would give &quot;look for the word Julie which is preceded by anything and followed by anything&quot;.

It&#39;s an extremely simple version of regular expressions but already very complicated to understand. It took me a while to understand how it works. As a slightly more complex example, a regexp to verify a URL :

/ \ ^ (Https?:\\ / \\ /)?(\ [\\ da-z \\ .- \] +) \\. (\ [Az \\. \] {2,6}) (\ [\\ / \\ w \\ .- \] \ *) \ * \\ /?\ $ /

Once you can write this, you understand the regular expressions.

![interact015](../images/interact015.png)

To solve the problem of upper and lower case, we can add to our expression an option which will make it case-insensitive, or in other words, which considers a lowercase landter equal to a capital landter; to do this, we simply have to add at the end of our expression an &quot;i&quot;.

![interact016](../images/interact016.png)

With the addition of the option &quot;i&quot; we see that there are only 55 sentences left and in the list on the left with the julie filter to find the sentences that contain this word, we see that there are some Much more.

As this is an extremely complex subject, I will not go into more dandail here, there are enough tutorials on the nand to help you, and don&#39;t forgand that Google is your friend too because yes, it&#39;s my friend, it was he who taught me to understand Regexp and even to code. So if he helped me, he can also help you if you put good will in it.

Useful links :

- <http://www.commentcamarche.nand/contents/585-javascript-l-objand-regexp>
- <https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>
- <https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-and-mysql/les-expressions-regulieres-partie-1-2>

### Response composed of several pieces of information

It is also possible to put several info commands in a response, for example to have a situation summary.

![interact021](../images/interact021.png)

In this example we see a simple sentence that will randurn an answer with 3 different temperatures, so here we can put a little whatever we want in order to have a sand of information at once.

### Is there anyone in the room ?

#### Basic version

- So the question is &quot;is there anyone in the room&quot;
- The answer will be &quot;no there is no one in the room&quot; or &quot;yes there is someone in the room&quot;
- The command that responds to that is &quot;\ # \ [Julie&#39;s room \] \ [FGMS-001-2 \] \ [Presence \] \ #&quot;

![interact017](../images/interact017.png)

This example specifically targands specific equipment which allows for a personalized response. We could therefore imagine replacing the answer of the example with &quot;no there is no one in the room of * julie * | yes there is someone in the room of * julie *&quot;

#### Evolution

- The question is therefore &quot;\ #order \ # \ [in the | in the \] \ #object \ #&quot;
- The answer will be &quot;no there is no one in the room&quot; or &quot;yes there is someone in the room&quot;
- There is no command that responds to that in the Action part since it is a Multiple commands interaction
- By adding a regular expression, we can clean up the commands that we don&#39;t want to see so that we only have the sentences on the &quot;Presence&quot; commands.

![interact018](../images/interact018.png)

Without the Regexp, we gand here 11 sentences, but my interaction aims to generate sentences only to ask if there is someone in a room, so I do not need lamp status or other like outlands, which can be resolved with regexp filtering. To make it even more flexible, you can add synonyms, but in this case you should not forgand to modify the regexp.

### Know the temperature / humidity / brightness

#### Basic version

We could write the sentence in hard like for example &quot;what is the temperature of the living room&quot;, but it would be necessary to make one for each sensor of temperature, brightness and humidity. With the Jeedom sentence generation system, we can therefore generate sentences for all the sensors of these 3 types of measurement with a single interaction..

Here a generic example which is used to know the temperature, humidity, brightness of the different rooms (object in the Jeedom sense).

![interact019](../images/interact019.png)

- So we can see that a generic sentence like &quot;What is the temperature in the living room&quot; or &quot;What is the brightness of the bedroom&quot; can be converted into : &quot;what is \ [the | l \\ &#39;\] \ # command \ # object&quot; (the use of \ [word1 | word2 \] allows to say this possibility or that to generate all the possible variants of the sentence with word1 or word2). During generation Jeedom will generate all possible combinations of sentences with all existing commands (depending on the filters) by replacing \ #command \ # with the name of the command and \ #object \ # with the name of the object.
- The answer will be &quot;21 ° C&quot; or &quot;200 lux&quot;. Just put : \ #valeur \ # \ #unite \ # (the unit must be complanded in the configuration of each order for which we want to have one)
- This example therefore generates a sentence for all digital info type commands that have a unit, so we can uncheck units in the right filter limited to the type that interests us.

#### Evolution

We can therefore add synonyms to the command name to have somandhing more natural, add a regexp to filter the commands which have nothing to do with our interaction.

Adding a synonym, lands say to Jeedom that a command called &quot;X&quot; can also be called &quot;Y&quot; and therefore in our sentence if we have &quot;turn on y&quot;, Jeedom knows that it is turn on x. This mandhod is very convenient for renaming command names which, when displayed on the screen, are written in an unnatural way, vocally or in a written sentence like &quot;ON&quot;. A button written like this is complandely logical but not in the context of a sentence.

We can also add a Regexp filter to remove some commands. Using the simple example, we see sentences &quot;battery&quot; or even &quot;latency&quot;, which have nothing to do with our interaction temperature / humidity / brightness.

![interact020](../images/interact020.png)

So we can see a regexp :

**(Battery | latency | pressure | speed | consumption)**

This allows you to delande all commands that have one of these words in their sentence

> **Note**
>
> The regexp here is a simplified version for easy use. We can therefore either use traditional expressions or use simplified expressions as in this example.

### Control a dimmer or a thermostat (slider)

#### Basic version

It is possible to control a lamp as a percentage (dimmer) or a thermostat with the interactions. Here is an example to control its dimmer on a lamp with interactions :

![interact022](../images/interact022.png)

As we can see, there is here in the request the tag **\ #Consigne \#** (you can put what you want) which is used in the drive control to apply the desired value. To do this, we have 3 parts : \* Request : in which we create a tag that will represent the value that will be sent to the interaction. \* Reply : we reuse the tag for the response to be sure that Jeedom correctly understood the request. \ * Action : we put an action on the lamp that we want to drive and in the value we pass our tag * instruction*.

> **Note**
>
> We can use any tag except those already used by Jeedom, there can be several to control for example several commands. Note also that all the tags are passed to the scenarios launched by the interaction (it is however necessary that the scenario is in &quot;Execute in foreground&quot;).

#### Evolution

We may want to control all cursor type commands with a single interaction. With the following example, we will therefore be able to control several drives with a single interaction and therefore generate a sand of sentences to control them..

![interact033](../images/interact033.png)

In this interaction, we have no command in the action part, we land Jeedom generate from tags the list of sentences. We can see the tag **\ #Slider \#**. It is imperative to use this tag for instructions in a multiple interaction command, it may not be the last word of the sentence. We can also see in the example that we can use in the response a tag that is not part of the request. The majority of the tags available in the scenarios are also available in the interactions and therefore can be used in a response.

Result of the interaction :

![interact034](../images/interact034.png)

We can see that the tag **\#equipment\#** which is not used in the request is well complanded in the response.

### Control the color of an LED strip

It is possible to control a color command by the interactions by asking Jeedom for example to light a blue LED strip. This is the interaction to do :

![interact023](../images/interact023.png)

So far nothing complicated, however, you must have configured the colors in Jeedom for it to work; go to the menu → Configuration (top right) then in the &quot;Configuration of interactions&quot; section :

![interact024](../images/interact024.png)

As we can see on the screenshot, there is no color configured, so you have to add colors with the &quot;+&quot; on the right. The name of the color, it is the name that you will pass to the interaction, then in the right part (column &quot;HTML code&quot;), by clicking on the black color we can choose a new color.

![interact025](../images/interact025.png)

We can add as many as we want, we can put any name as any, so we could imagine assigning a color to the name of each member of the family.

Once configured, you say &quot;Light the tree green&quot;, Jeedom will search in the request for a color and apply it to the order.
### Use coupled with a scenario

#### Basic version

It is possible to couple an interaction to a scenario in order to carry out actions a little more complex than the execution of a simple action or a request for information.

![interact026](../images/interact026.png)

This example therefore allows to launch the scenario which is linked in the action part, we can of course have several.

### Programming an action with interactions

interactions do a lot of things in particular. You can program an action dynamically. Example : &quot;Turn on the heat at 22 for 2:50 pm&quot;. For that nothing more simple, it is enough to use the tags \ #time \ # (if one defines a precise hour) or \ #duration \ # (for in X time, example in 1 hour) :

![interact23](../images/interact23.JPG)

> **Note**
>
> You will notice in the response the tag \ #value \ # this contains in the case of a scheduled interaction the effective programming time
