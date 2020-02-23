Aquí hay documentación sobre métodos API. Primero aquí está
las especificaciones (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

El acceso a la API es a través de la url : *URL \ _JEEDOM * / core / api / jeeApi.php

Diverso
======

de ping
----

Regrese pong, pruebe la comunicación con Jeedom

versión
-------

Devuelve la versión de Jeedom

fecha y hora
--------

Devuelve la fecha y hora de Jeedom en microsegundos

API de configuración
==========

config::byKey
-------------

Devuelve un valor de configuración.

configuraciones :

-   clave de cuerda : clave de valor de configuración para devolver

-   complemento de cadena : (opcional), complemento de valor de configuración

-   cadena por defecto : (opcional), valor a devolver si la clave no existe
    no

config::Guardar
------------

Guarda un valor de configuración

configuraciones :

-   valor de cadena : valor para grabar

-   clave de cuerda : clave de valor de configuración para guardar

-   complemento de cadena : (opcional), complemento del valor de configuración para
    registro

API de eventoos JSON
==============

evento::intercambio
--------------

Devuelve la lista de cambios desde la fecha y hora noada en el parámetro
(debe estar en microsegundos). También tendrás en la respuesta el
Fecha y hora actual de Jeedom (se reutilizará para la próxima consulta)

configuraciones :

-   int fecha y hora

API de complementos JSON
===============

plugin::listPlugin
------------------

Devuelve la lista de todos los complementos

configuraciones :

-   int enableOnly = 0 (solo devuelve la lista de complementos activados)

-   int orderByCaterogy = 0 (devuelve la lista de complementos ordenados
    por categoría)

API JSON de objetos
==============

jeeObject::todos
-----------

Devuelve la lista de todos los objetos.

jeeObject::completo
------------

Devuelve la lista de todos los objetos, con para cada objeto todos sus
equipo y para cada equipo todos sus comandos, así como
estados de estos (para comandos de tipo de información)

jeeObject::completoById
----------------

Devuelve un objeto con todo su equipo y para cada equipo.
todos sus comandos, así como sus estados (para
comandos de tipo de información)

configuraciones :

-   int id

jeeObject::BYID
------------

Devuelve el objeto especificado

configuraciones:

-   int id

jeeObject::completoById
----------------

Devuelve un objeto, su equipo y para cada equipo todos sus
comandos, así como los estados de las celdas (para comandos de tipo
info)

jeeObject::Guardar
------------

Devuelve el objeto especificado

configuraciones:

-   Identificación del int (vacía si es una creación)

-   nombre de cadena

-   int father \ _id = null

-   int isVisible = 0

-   posición int

-   configuración de matriz

-   panttodosa de matriz

API de resumen JSON
================

resumen::total
---------------

Devuelve el resumen total de la clave noada en el parámetro

configuraciones:

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom
    te envía el resumen de todas las claves

resumen::BYID
-------------

Devuelve el resumen de la identificación del objeto.

configuraciones:

-   int id : ID de objeto

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom
    te envía el resumen de todas las claves

API JSON EqLogic
================

eqLogic::todos
------------

Devuelve la lista de todos los equipos.

eqLogic::completoById
-----------------

Devuelve el equipo y sus comandos, así como sus estados.
(para pedidos de tipo de información)

configuraciones:

-   int id

eqLogic::BYID
-------------

Devuelve el equipo especificado.

configuraciones:

-   int id

eqLogic::byType
---------------

Devuelve todos los equipos que pertenecen al tipo especificado (complemento)

configuraciones:

-   tipo de cadena

eqLogic::byObjectId
-------------------

Devuelve todo el equipo que pertenece al objeto especificado.

configuraciones:

-   int objeto \ _id

eqLogic::byTypeAndId
--------------------

Devuelve una tabla de equipos según los parámetros.. El regreso
será de la matriz de forma (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

configuraciones:

-   string\[\] eqType = tableau des types d'équipements voulus

-   int\[\] id = tableau des ID d'équipements personnalisés voulus

eqLogic::Guardar
-------------

Retourne l'équipement enregistré/créé

configuraciones:

-   Identificación del int (vacía si es una creación)

-   string eqType\_name (type de l'équipement script, virtuel…​)

-   nombre de cadena

-   string registroicalId = ''

-   int object\_id = null

-   int eqReal\_id = null

-   int isVisible = 0

-   int isEnable = 0

-   configuración de matriz

-   int timeout

-   array category

API JSON Cmd
============

cmd::todos
--------

Retourne la liste de toutes les commandes

cmd::BYID
---------

Retourne la commande spécifiée

configuraciones:

-   int id

cmd::byEqLogicId
----------------

Retourne toutes les commandes appartenant à l'équipement spécifié

configuraciones:

-   int eqLogic\_id

cmd::execCmd
------------

Exécute la commande spécifiée

configuraciones:

-   int id : id d'une commande ou tableau d'id si vous voulez executer
    plusieurs commande d'un coup

-   \[options\] Liste des options de la commande (dépend du type et du
    sous-type de la commande)

cmd::getStatistique
-------------------

Retourne les statistiques sur la commande (ne marche que sur les
commandes de type info et historisées)

configuraciones:

-   int id

-   string startTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de
type info et historisées)

configuraciones:

-   int id

-   string startTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory
---------------

Retourne l'historique de la commande (ne marche que sur les commandes de
type info et historisées)

configuraciones:

-   int id

-   string startTime : date de début de l'historique

-   string endTime : date de fin de l'historique

cmd::Guardar
---------

Devuelve el objeto especificado

configuraciones:

-   Identificación del int (vacía si es una creación)

-   nombre de cadena

-   string registroicalId

-   string eqType

-   string order

-   tipo de cadena

-   string subType

-   int eqLogic\_id

-   int isHistorized = 0

-   string unite = ''

-   array configuration

-   array template

-   array display

-   array html

-   int value = null

-   int isVisible = 1

-   array alert

cmd::evento
-------------------

Permet d'envoyer une valeur à une commande

configuraciones:

-   int id

-   valor de cadena : valeur

-   string fecha y hora : (optionnel) fecha y hora de la valeur

API JSON Scenario
=================

scenario::todos
-------------

Retourne la liste de tous les scénarios

scenario::BYID
--------------

Retourne le scénario spécifié

configuraciones:

-   int id

scenario::export
----------------

Retourne l'export du scénario ainsi que le nom humain du scénario

configuraciones:

-   int id

scenario::import
----------------

Permet d'importer un scénario.

configuraciones:

-   int id : id du scénario dans lequel importer (vide si création)

-   string humanName : nom humain du scénario (vide si création)

-   array import : scénario (issue du champs export de scenario::export)

scenario::changeState
---------------------

Change l'état du scénario spécifié.

configuraciones:

-   int id

-   string state : \[run,stop,enable,disable\]

API JSON Log
============

registro::get
--------

Permet de récuperer un registro

configuraciones:

-   string registro : nom du registro à recuperer

-   string start : numéro de ligne sur laquelle commencer la lecture

-   string nbLine : nombre de ligne à recuperer

registro::add
--------

Permet d'écrire dans un registro

configuraciones:

-   string registro : nom du registro à recuperer

-   tipo de cadena : type de registro (debug, info, warning, error)

-   string mensaje : mensaje text à écrire

-   string registroicalId : registroicalId du mensaje généré


registro::list
---------

Permet de récuperer la list des registros de Jeedom

configuraciones:

-   string filtre : (optionnel) filtre sur le nom des registros à recuperer

registro::empty
----------

Permet de vider un registro

configuraciones:

-   string registro : nom du registro à vider

registro::remove
-----------

Permet de supprimer un registro

configuraciones:

-   string registro : nom du registro a supprimer

API JSON datastore (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Récupère la valeur d'une variable stockée dans le datastore

configuraciones:

-   tipo de cadena : type de la valeur stockée (pour les scénarios
    c'est scenario)

-   id linkId : -1 pour le total (valeur pour les scénarios par défaut,
    ou l'id du scénario)

-   clave de cuerda : nom de la valeur

datastore::Guardar
---------------

Enregistre la valeur d'une variable dans le datastore

configuraciones:

-   tipo de cadena : type de la valeur stockée (pour les scénarios
    c'est scenario)

-   id linkId : -1 pour le total (valeur pour les scénarios par défaut,
    ou l'id du scénario)

-   clave de cuerda : nom de la valeur

-   mixte value : valor para grabar

API JSON Message
================

mensaje::todos
------------

Retourne la liste de tous les mensajes

mensaje::add
--------

Permet d'écrire dans un registro

configuraciones:

-   tipo de cadena : type de registro (debug, info, warning, error)

-   string mensaje : mensaje

-   string acción : acción

-   string registroicalId : registroicalId

mensaje::removeAll
------------------

Supprime tous les mensajes

API JSON Interacción
====================

interact::tryToReply
--------------------

Essaie de faire correspondre une demande avec une interacción, exécute
l'acción et répond en conséquence

configuraciones:

-   query (phrase de la demande)

-   int reply\_cmd = NULL : ID de la commande à utiliser pour répondre,
    si non préciser alors Jeedom vous renvoi la réponse dans le json

interactQuery::todos
------------------

Renvoi la liste complete de toute les interaccións

API JSON System
===============

jeedom::halt
------------

Permet d'arrêter Jeedom

jeedom::reboot
--------------

Permet de redémarrer Jeedom

jeedom::isOk
------------

Permet de savoir si l'état total de Jeedom est OK

jeedom::update
--------------

Permet de lancer un update de Jeedom

jeedom::backup
--------------

Permet de lancer un backup de Jeedom

jeedom::getUsbMapde ping
---------------------

Liste des ports USB et des noms de clef USB branché dessus

API JSON plugin
===============

plugin::insttodos
---------------

Insttodosation/Mise à jour d'un plugin donné

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::remove
--------------

Suppression d'un plugin donné

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::dependancyInfo
----------------------

Renvoi les informations sur le status des dépendances du plugins

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::dependancyInsttodos
-------------------------

Force l'insttodosation des dépendances du plugin

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::deamonInfo
------------------

Renvoi les informations sur le status du démon du plugin

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::deamonStart
-------------------

Force le démarrage du démon

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::deamonStop
------------------

Force l'arret du démon

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

plugin::deamonChangeAutoMode
----------------------------

Change le mode de gestion du démon

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)
-   int mode : 1 pour automatique, 0 pour manuel

API JSON update
===============

update::todos
-----------

Retour la liste de tous les composants insttodosés, leur versión et les
informations associées

update::checkUpdate
-------------------

Permet de vérifier les mises à jour

update::update
--------------

Permet de mettre à jour Jeedom et tous les plugins

update::doUpdate
--------------

configuraciones:

-   int plugin\_id (optionnel) : id du plugin
-   string registroicalId (optionnel) : nom du plugin (nom registroique)

API JSON network
================

network::restartDns
-------------------

Force le (re)démarrage du DNS Jeedom

network::stopDns
----------------

Force l'arret du DNS Jeedom

network::dnsRun
---------------

Renvoi le status du DNS Jeedom

API JSON Exemples
=================

Voici un exemple d'utilisation de l'API. Pour l'exemple ci-dessous
j'utilise [cette class
php](https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
qui permet de simplifier l'utilisation de l'api.

Récupération de la liste des objets :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::todos', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Ejecución de una orden (con la opción de un título y un mensaje)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::execCmd &#39;, array (&#39; id &#39;=&gt; # cmd_id #,&#39; options &#39;=&gt; array (&#39; title &#39;=&gt;&#39; Cuckoo &#39;,&#39; mensaje &#39;=&gt;&#39; Funciona &#39;)))) {
    echo &#39;OK&#39;;
}else{
    echo $jsonrpc->getError();
}
```

La API, por supuesto, se puede usar con otros idiomas (simplemente una publicación
en una página)
