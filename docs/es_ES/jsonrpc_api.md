Aquí está la documentación sobre los métodos API. Lo primero de todo aquí
Las especificaciones (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

El acceso a la API es a través de la URL: URL * \ * _JEEDOM / core / api / jeeApi.php

Diverso
======

ping
----

Devuelve pong, para probar la comunicación con Jeedom

versión
-------

Devuelve la versión de Jeedom

datetime
--------

Devuelve la fecha y hora Jeedom en microsegundos

API config
==========

config::byKey
-------------

Devuelve un valor de configuración.

Parámetros :

-   clave de cadena: el valor de configuración de tecla para volver

-   cadena Plugin (opcional), el valor de configuración del plugin

-   string predeterminado: (opcional) Valor de retorno si no existe la clave
    no

config::save
------------

Recibe un valor de configuración

Configuraciones :

-   valor de cadena: Valor que debe registrarse

-   cadena de clave: clave para el valor de configuración para grabar

-   cadena Plugin (opcional), el valor de configuración del plugin
    registro

API JSON Event
==============

event::changes
--------------

Devuelve lista de cambios desde la fecha y hora como un parámetro
(Debe estar en microsegundos). También responderá en el
fecha y hora actuales Jeedom (reutilización para la consulta siguiente)

Parámetros:

-   int datetime

API JSON Plugin
===============

plugin::listPlugin
------------------

Devuelve una lista de todos los plugins

Parámetros:

-   activateOnly int = 0 (sólo devuelve la lista de plug-ins habilitados)

-   orderByCaterogy int = 0 (devuelve la lista de plugins ordenados
    por categoría)

API JSON Objet
==============

jeeObject::all
-----------

Devuelve una lista de todos los objetos

jeeObject::full
------------

Devuelve una lista de todos los objetos, con cada objeto de todo
instalaciones y equipos para cada uno de todos los mandos y la
declaraciones de éstos (por comandos de información de tipo)

jeeObject::fullById
----------------

Devuelve un objeto con todas sus instalaciones y equipos para cada
todos los mandos y las declaraciones de éstos (por
comandos de información de tipo)

Parámetros:

-   int id

jeeObject::byId
------------

Devuelve el objeto especificado

Parámetros:

-   int id

jeeObject::fullById
----------------

Devuelve un objeto, instalaciones y equipos para cada todo
órdenes y las declaraciones de cellse que (para los comandos de tipo
info)

jeeObject::save
------------

Devuelve el objeto especificado

Parámetros:

-   id int (en blanco si se trata de una creación)

-   string name

-   int father\_id = null

-   int isVisible = 0

-   int position

-   array configuration

-   array display

API JSON Summary
================

summary::global
---------------

Respaldar el resumen general de parámetro clave pasado

Parámetros:

-   string key : clave (opcional) del resumen deseada, si está vacío entonces Jeedom
    hace referencia a la síntesis de todas las llaves

summary::byId
-------------

Devuelve resumen para el identificador de objeto

Parámetros:

-   int id : id de objeto

-   string key : (opcional), clave para el resumen deseado, si está vacío, entonces Jeedom
    enviará el resumen de todas las claves

API JSON EqLogic
================

eqLogic::all
------------

Devuelve una lista de todos los equipos

eqLogic::fullById
-----------------

Devuelve un equipo y sus controles y las declaraciones de éstos
(Para los comandos de información de tipo)

Parámetros:

-   int id

eqLogic::byId
-------------

Retourne l’équipement spécifié

Parámetros: 

-   int id

eqLogic::byType
---------------

Retourne tous les équipements appartenant au type (plugin) spécifié

Parámetros:

-   string type

eqLogic::byObjectId
-------------------

Retourne tous les équipements appartenant à l’objet spécifié

Parámetros:

-   int object\_id

eqLogic::byTypeAndId
--------------------

Renvoi un tableau d’équipement en fonction des paramètres. Le retour
sera de la forme array('eqType1' ⇒array( 'id'⇒…​,'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Parámetros:

-   string\[\] eqType = tableau des types d’équipements voulus

-   int\[\] id = tableau des ID d’équipements personnalisés voulus

eqLogic::save
-------------

Retourne l’équipement enregistré/créé

Parámetros:

-   int id (vide si c’est une création)

-   string eqType\_name (type de l’équipement script, virtuel…​)

-   string name

-   string logicalId = ''

-   int object\_id = null

-   int eqReal\_id = null

-   int isVisible = 0

-   int isEnable = 0

-   array configuration

-   int timeout

-   array category

API JSON Cmd
============

cmd::all
--------

Retourne la liste de toutes les commandes

cmd::byId
---------

Retourne la commande spécifiée

Parámetros:

-   int id

cmd::byEqLogicId
----------------

Retourne toutes les commandes appartenant à l’équipement spécifié

Parámetros:

-   int eqLogic\_id

cmd::execCmd
------------

Exécute la commande spécifiée

Parámetros:

-   int id : id d’une commande ou tableau d’id si vous voulez executer
    plusieurs commande d’un coup

-   \[options\] Liste des options de la commande (dépend du type et du
    sous-type de la commande)

cmd::getStatistique
-------------------

Retourne les statistiques sur la commande (ne marche que sur les
commandes de type info et historisées)

Parámetros:

-   int id

-   string startTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de
type info et historisées)

Parámetros:

-   int id

-   string startTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory
---------------

Retourne l’historique de la commande (ne marche que sur les commandes de
type info et historisées)

Parámetros:

-   int id

-   string startTime : date de début de l’historique

-   string endTime : date de fin de l’historique

cmd::save
---------

Retourne l’objet spécifié

Parámetros:

-   int id (vide si c’est une création)

-   string name

-   string logicalId

-   string eqType

-   string order

-   string type

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

cmd::event
-------------------

Permet d'envoyer une valeur à une commande

Parámetros:

-   int id

-   string value : valeur

-   string datetime : (optionnel) datetime de la valeur

API JSON Scenario
=================

scenario::all
-------------

Retourne la liste de tous les scénarios

scenario::byId
--------------

Retourne le scénario spécifié

Parámetros:

-   int id

scenario::export
----------------

Retourne l’export du scénario ainsi que le nom humain du scénario

Parámetros:

-   int id

scenario::import 
----------------

Permite importar un escenario.

Parámetros:

-   int id : id du scénario dans lequel importer (vide si création)

-   string humanName : nom humain du scénario (vide si création)

-   array import : scénario (issue du champs export de scenario::export)

scenario::changeState
---------------------

Cambia el estado del escenario especificado.

Parámetros:

-   int id

-   string state : \[run,stop,enable,disable\]

API JSON Log
============

log::get
--------

Permite recuperar un log

Parámetros:

-   string log : nom du log à recuperer

-   string start : numéro de ligne sur laquelle commencer la lecture

-   string nbLine : nombre de ligne à recuperer

log::list
---------

Permet de récuperer la list des logs de Jeedom

Parámetros:

-   string filtre : (optionnel) filtre sur le nom des logs à recuperer

log::empty
----------

Permite vaciar un registro

Parámetros:

-   string log : nom du log à vider

log::remove
-----------

Permite eliminar un registro

Parámetros:

-   string log : nom du log a supprimer

API JSON datastore (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Récupère la valeur d’une variable stockée dans le datastore

Parámetros:

-   string type : type de la valeur stockée (pour les scénarios
    es escenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut,
    ou l’id du scénario)

-   string key : nom de la valeur

datastore::save
---------------

Enregistre la valeur d’une variable dans le datastore

Parámetros:

-   string type : type de la valeur stockée (pour les scénarios
    es escenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut,
    ou l’id du scénario)

-   string key : nom de la valeur

-   mixte value : valeur à enregistrer

API JSON Message
================

message::all
------------

Retourne la liste de tous les messages

message::removeAll
------------------

Borra todos los mensajes

API JSON Interaction
====================

interact::tryToReply
--------------------

Essaie de faire correspondre une demande avec une interaction, exécute
l’action et répond en conséquence

Parámetros:

-   query (phrase de la demande)

-   int reply\_cmd = NULL : ID de la commande à utiliser pour répondre,
    si non préciser alors Jeedom vous renvoi la réponse dans le json

interactQuery::all
------------------

Renvoi la liste complete de toute les interactions

API JSON System
===============

jeedom::halt
------------

Permite detener a Jeedom

jeedom::reboot
--------------

Permite reiniciar a Jeedom

jeedom::isok
------------

Permet de savoir si l’état global de Jeedom est OK

jeedom::update
--------------

Permet de lancer un update de Jeedom

jeedom::copia de seguridad
--------------

Permet de lancer un backup de Jeedom

jeedom::getUsbMapping
---------------------

Liste des ports USB et des noms de clef USB branché dessus

API JSON plugin
===============

plugin::install
---------------

Installation/Mise à jour d’un plugin donné

Parámetros:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::remove
--------------

Suppression d’un plugin donné

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::dependancyInfo
----------------------

Renvoi les informations sur le status des dépendances du plugins

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::dependancyInstall
-------------------------

Force l’installation des dépendances du plugin

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonInfo
------------------

Renvoi les informations sur le status du démon du plugin

Parámetros:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonStart
-------------------

Force le démarrage du démon

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonStop
------------------

Force l’arret du démon

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonChangeAutoMode
----------------------------

Change le mode de gestion du démon

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)
-   int mode : 1 pour automatique, 0 pour manuel

API JSON update
===============

update::all
-----------

Retour la liste de tous les composants installés, leur version et les
informations associées

update::checkUpdate
-------------------

Permet de vérifier les mises à jour

update::update
--------------

Permet de mettre à jour Jeedom et tous les plugins

update::doUpdate
--------------

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

API JSON network
================

network::restartDns
-------------------

Force le (re)démarrage du DNS Jeedom

network::stopDns
----------------

Force l’arret du DNS Jeedom

network::dnsRun
---------------

Renvoi le status du DNS Jeedom

API JSON Exemples
=================

Voici un exemple d’utilisation de l’API. Pour l’exemple ci-dessous
j’utilise [cette class
php](https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
qui permet de simplifier l’utilisation de l’api.

Récupération de la liste des objets :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Exécution d’une commande (avec comme option un titre et un message)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

L’API est bien sur utilisable avec d’autres langages (simplement un post
sur une page) 
