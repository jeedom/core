Voici une documentation sur les méthodes de l’API. Tout d’abord voici
les spécifications (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

L’accès à l’API se fait par l’url : *URL\_JEEDOM*/core/api/jeeApi.php

Divers {#_divers}
======

ping {#_ping}
----

Retourne pong, permet de tester la communication avec Jeedom

version {#_version}
-------

Retourne la version de Jeedom

datetime {#_datetime}
--------

Retourne le datetime de Jeedom en microsecondes

API config {#_api_config}
==========

config::byKey {#_config_bykey}
-------------

Retourne une valeur de configuration.

Paramètres :

-   string key : clef de la valeur de configuration à retourner

-   string plugin : (optionnel), plugin de la valeur de configuration

-   string default : (optionnel), valeur à retourner si la clef n’existe
    pas

config::save {#_config_save}
------------

Enregistre une valeur de configuration

Paramètres :

-   string value : valeur à enregistrer

-   string key : clef de la valeur de configuration à enregistrer

-   string plugin : (optionnel), plugin de la valeur de configuration à
    enregistrer

API JSON Event {#_api_json_event}
==============

event::changes {#_event_changes}
--------------

Retourne la liste des changements depuis le datetime passé en paramètre
(doit être en microsecondes). Vous aurez aussi dans la réponse le
datetime courant de Jeedom (à réutiliser pour l’interrogation suivante)

Paramètres :

-   int datetime

API JSON Plugin {#_api_json_plugin}
===============

plugin::listPlugin {#_plugin_listplugin}
------------------

Retourne la liste de tous les plugins

Paramètres :

-   int activateOnly = 0 (ne retourne que la liste des plugins activés)

-   int orderByCaterogy = 0 (retourne la liste des plugins triés
    par catégorie)

API JSON Objet {#_api_json_objet}
==============

object::all {#_object_all}
-----------

Retourne la liste de tous les objets

object::full {#_object_full}
------------

Retourne la liste de tous les objets, avec pour chaque objet tous ses
équipements et pour chaque équipement toutes ses commandes ainsi que les
états de celles-ci (pour les commandes de type info)

object::fullById {#_object_fullbyid}
----------------

Retourne un objet avec tous ses équipements et pour chaque équipement
toutes ses commandes ainsi que les états de celles-ci (pour les
commandes de type info)

Paramètres :

-   int id

object::byId {#_object_byid}
------------

Retourne l’objet spécifié

Paramètres:

-   int id

object::fullById {#_object_fullbyid_2}
----------------

Retourne un objet, ses équipements et pour chaque équipement toutes ses
commandes ainsi que les états de cellse-ci (pour les commandes de type
info)

object::save {#_object_save}
------------

Retourne l’objet spécifié

Paramètres:

-   int id (vide si c’est une création)

-   string name

-   int father\_id = null

-   int isVisible = 0

-   int position

-   array configuration

-   array display

API JSON Summary {#_api_json_summary}
================

summary::global {#_summary_global}
---------------

Retour le résumé global pour la clef passée en paramètre

Paramètres:

-   string key : (optionnel), clef du résumé voulu, si vide alors Jeedom
    vous renvoi le résumé pour toute les clefs

summary::byId {#_summary_byid}
-------------

Retourne le résumé pour l’objet id

Paramètres:

-   int id : id de l’objet

-   string key : (optionnel), clef du résumé voulu, si vide alors Jeedom
    vous renvoi le résumé pour toute les clefs

API JSON EqLogic {#_api_json_eqlogic}
================

eqLogic::all {#_eqlogic_all}
------------

Retourne la liste de tous les équipements

eqLogic::fullById {#_eqlogic_fullbyid}
-----------------

Retourne un équipement et ses commandes ainsi que les états de celles-ci
(pour les commandes de type info)

eqLogic::byId {#_eqlogic_byid}
-------------

Retourne l’équipement spécifié

Paramètres:

-   int id

eqLogic::byType {#_eqlogic_bytype}
---------------

Retourne tous les équipements appartenant au type (plugin) spécifié

Paramètres:

-   string type

eqLogic::byObjectId {#_eqlogic_byobjectid}
-------------------

Retourne tous les équipements appartenant à l’objet spécifié

Paramètres:

-   int object\_id

eqLogic::byTypeAndId {#_eqlogic_bytypeandid}
--------------------

Renvoi un tableau d’équipement en fonction des paramètres. Le retour
sera de la forme array('eqType1' ⇒array( 'id'⇒…​,'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Paramètres:

-   string\[\] eqType = tableau des types d’équipements voulus

-   int\[\] id = tableau des ID d’équipements personnalisés voulus

eqLogic::save {#_eqlogic_save}
-------------

Retourne l’équipement enregistré/créé

Paramètres:

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

API JSON Cmd {#_api_json_cmd}
============

cmd::all {#_cmd_all}
--------

Retourne la liste de toutes les commandes

cmd::byId {#_cmd_byid}
---------

Retourne la commande spécifiée

Paramètres:

-   int id

cmd::byEqLogicId {#_cmd_byeqlogicid}
----------------

Retourne toutes les commandes appartenant à l’équipement spécifié

Paramètres:

-   int eqLogic\_id

cmd::execCmd {#_cmd_execcmd}
------------

Exécute la commande spécifiée

Paramètres:

-   int id : id d’une commande ou tableau d’id si vous voulez executer
    plusieurs commande d’un coup

-   \[options\] Liste des options de la commande (dépend du type et du
    sous-type de la commande)

cmd::getStatistique {#_cmd_getstatistique}
-------------------

Retourne les statistiques sur la commande (ne marche que sur les
commandes de type info et historisées)

Paramètres:

-   int id

-   string startTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance {#_cmd_gettendance}
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de
type info et historisées)

Paramètres:

-   int id

-   string startTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory {#_cmd_gethistory}
---------------

Retourne l’historique de la commande (ne marche que sur les commandes de
type info et historisées)

Paramètres:

-   int id

-   string startTime : date de début de l’historique

-   string endTime : date de fin de l’historique

cmd::save {#_cmd_save}
---------

Retourne l’objet spécifié

Paramètres:

-   int id (vide si c’est une création)

-   string name

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

API JSON Scenario {#_api_json_scenario}
=================

scenario::all {#_scenario_all}
-------------

Retourne la liste de tous les scénarios

scenario::byId {#_scenario_byid}
--------------

Retourne le scénario spécifié

Paramètres:

-   int id

scenario::export {#_scenario_export}
----------------

Retourne l’export du scénario ainsi que le nom humain du scénario

Paramètres:

-   int id

scenario::import {#_scenario_import}
----------------

Permet d’importer un scénario.

Paramètres:

-   int id : id du scénario dans lequel importer (vide si création)

-   string humanName : nom humain du scénario (vide si création)

-   array import : scénario (issue du champs export de scenario::export)

scenario::changeState {#_scenario_changestate}
---------------------

Change l’état du scénario spécifié.

Paramètres:

-   int id

-   string state : \[run,stop,enable,disable\]

API JSON Log {#_api_json_log}
============

log::get {#_log_get}
--------

Permet de récuperer un log

Paramètres:

-   string log : nom du log à recuperer

-   string start : numéro de ligne sur laquelle commencer la lecture

-   string nbLine : nombre de ligne à recuperer

log::list {#_log_list}
---------

Permet de récuperer la list des logs de Jeedom

Paramètres:

-   string filtre : (optionnel) filtre sur le nom des logs à recuperer

log::empty {#_log_empty}
----------

Permet de vider un log

Paramètres:

-   string log : nom du log à vider

log::remove {#_log_remove}
-----------

Permet de supprimer un log

Paramètres:

-   string log : nom du log a supprimer

API JSON datastore (variable) {#_api_json_datastore_variable}
=============================

datastore::byTypeLinkIdKey {#_datastore_bytypelinkidkey}
--------------------------

Récupère la valeur d’une variable stockée dans le datastore

Paramètres:

-   string type : type de la valeur stockée (pour les scénarios
    c’est scenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut,
    ou l’id du scénario)

-   string key : nom de la valeur

datastore::save {#_datastore_save}
---------------

Enregistre la valeur d’une variable dans le datastore

Paramètres:

-   string type : type de la valeur stockée (pour les scénarios
    c’est scenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut,
    ou l’id du scénario)

-   string key : nom de la valeur

-   mixte value : valeur à enregistrer

API JSON Message {#_api_json_message}
================

message::all {#_message_all}
------------

Retourne la liste de tous les messages

message::removeAll {#_message_removeall}
------------------

Supprime tous les messages

API JSON Interaction {#_api_json_interaction}
====================

interact::tryToReply {#_interact_trytoreply}
--------------------

Essaie de faire correspondre une demande avec une interaction, exécute
l’action et répond en conséquence

Paramètres:

-   query (phrase de la demande)

-   int reply\_cmd = NULL : ID de la commande à utiliser pour répondre,
    si non préciser alors Jeedom vous renvoi la réponse dans le json

interactQuery::all {#_interactquery_all}
------------------

Renvoi la liste complete de toute les interactions

API JSON System {#_api_json_system}
===============

jeedom::halt {#_jeedom_halt}
------------

Permet d’arrêter Jeedom

jeedom::reboot {#_jeedom_reboot}
--------------

Permet de redémarrer Jeedom

jeedom::isOk {#_jeedom_isok}
------------

Permet de savoir si l’état global de Jeedom est OK

jeedom::update {#_jeedom_update}
--------------

Permet de lancer un update de Jeedom

jeedom::backup {#_jeedom_backup}
--------------

Permet de lancer un backup de Jeedom

jeedom::getUsbMapping {#_jeedom_getusbmapping}
---------------------

Liste des ports USB et des noms de clef USB branché dessus

API JSON plugin {#_api_json_plugin_2}
===============

plugin::install {#_plugin_install}
---------------

Installation/Mise à jour d’un plugin donné

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::remove {#_plugin_remove}
--------------

Suppression d’un plugin donné

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::dependancyInfo {#_plugin_dependancyinfo}
----------------------

Renvoi les informations sur le status des dépendances du plugins

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::dependancyInstall {#_plugin_dependancyinstall}
-------------------------

Force l’installation des dépendances du plugin

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::deamonInfo {#_plugin_deamoninfo}
------------------

Renvoi les informations sur le status du démon du plugin

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::deamonStart {#_plugin_deamonstart}
-------------------

Force le démarrage du démon

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::deamonStop {#_plugin_deamonstop}
------------------

Force l’arret du démon

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

plugin::deamonChangeAutoMode {#_plugin_deamonchangeautomode}
----------------------------

Change le mode de gestion du démon

Paramètres:

-   string plugin\_id : nom du plugin (nom logique)

-   int mode : 1 pour automatique, 0 pour manuel

API JSON update {#_api_json_update}
===============

update::all {#_update_all}
-----------

Retour la liste de tous les composants installés, leur version et les
informations associées

update::checkUpdate {#_update_checkupdate}
-------------------

Permet de vérifier les mises à jour

update::update {#_update_update}
--------------

Permet de mettre à jour Jeedom et tous les plugins

API JSON network {#_api_json_network}
================

network::restartDns {#_network_restartdns}
-------------------

Force le (re)démarrage du DNS Jeedom

network::stopDns {#_network_stopdns}
----------------

Force l’arret du DNS Jeedom

network::dnsRun {#_network_dnsrun}
---------------

Renvoi le status du DNS Jeedom

API JSON Exemples {#_api_json_exemples}
=================

Voici un exemple d’utilisation de l’API. Pour l’exemple ci-dessous
j’utilise [cette class
php](https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
qui permet de simplifier l’utilisation de l’api.

Récupération de la liste des objets :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('object::all', array())){
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
