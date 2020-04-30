Aqui está a documentação sobre métodos de API. Primeiro aqui é
as especificações (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

O acesso à API é via URL : *URL\_JEEDOM * / core / api / jeeApi.php

Divers
======

ping
----

Return pong, teste a comunicação com Jeedom

version
-------

Retorna a versão do Jeedom

datetime
--------

Retorna a data e hora do Jeedom em microssegundos

API de configuração
==========

config::byKey
-------------

Retorna um valor de configuração.

Configurações :

-   chave de cadeia : chave do valor de configuração a retornar

-   plugin de string : (opcional), plug-in de valor de configuração

-   padrão de cadeia : (opcional), valor a retornar se a chave não existir
    Não

config::save
------------

Salva um valor de configuração

Configurações :

-   valor da string : valor a registrar

-   chave de cadeia : chave do valor de configuração para salvar

-   plugin de string : (opcional), plug-in do valor de configuração para
    Registro

API de Eventoos JSON
==============

event::changes
--------------

Retorna a listaa de alterações desde a data e hora Nãosada no parâmetro
(deve estar em microssegundos). Você também terá na resposta o
Data / hora atual do Jeedom (a ser reutilizado para a próxima consulta)

Configurações :

-   int data e hora

API de plug-in JSON
===============

plugin::listPlugin
------------------

Retorna a listaa de todos os plugins

Configurações :

-   int activOnOnly = 0 (retorna apenas a listaa de plugins ativados)

-   int orderByCaterogy = 0 (retorna a listaa de plugins classificados
    por categoria)

API JSON do objeto
==============

object::all
-----------

Retorna a listaa de todos os objetos

object::full
------------

Retorna a listaa de todos os objetos, com para cada objeto todas as suas
equipamento e para cada equipamento todos os seus comandos, bem como
estados destes (para comandos do tipo info)

object::fullById
----------------

Retorna um objeto com todos os seus equipamentos e para cada equipamento
todos os seus comandos, bem como seus estados (por
comandos do tipo de informação)

Configurações :

-   int id

object::byId
------------

Retorna o objeto especificado

Configurações:

-   int id

object::fullById
----------------

Retorna um objeto, seu equipamento e para cada equipamento todo o seu
comandos, bem como os estados da célula (para comandos de tipo
info)

object::save
------------

Retorna o objeto especificado

Configurações:

-   int id (vazio se for uma criação)

-   nome da string

-   int pai\_id = nulo

-   int isVisible = 0

-   posição int

-   configuração de matriz

-   exibição de matriz

API de resumo JSON
================

summary::global
---------------

Retornar o resumo global da chave Nãosada no parâmetro

Configurações:

-   chave de cadeia : (opcional), chave do resumo desejado, se vazio, então Jeedom
    envia o resumo de todas as chaves

summary::byId
-------------

Retorna o resumo para o ID do objeto

Configurações:

-   int id : Object ID

-   chave de cadeia : (opcional), chave do resumo desejado, se vazio, então Jeedom
    envia o resumo de todas as chaves

API JSON EqLogic
================

eqLogic::all
------------

Retorna a listaa de todos os equipamentos

eqLogic::fullById
-----------------

Retorna o equipamento e seus comandos, bem como seus estados
(para pedidos do tipo info)

Configurações:

-   int id

eqLogic::byId
-------------

Retorna o equipamento especificado

Configurações:

-   int id

eqLogic::byType
---------------

Retorna todos os equipamentos pertencentes ao tipo especificado (plugin)

Configurações:

-   tipo de string

eqLogic::byObjectId
-------------------

Retorna todos os equipamentos pertencentes ao objeto especificado

Configurações:

-   int objeto\_id

eqLogic::byTypeAndId
--------------------

Retorna uma tabela de equipamentos de acordo com os parâmetros. O retorno
será da matriz de formulários ('eqType1' ⇒ matriz ('id'⇒…,' cmds '⇒
matriz (….)), 'eqType2' ⇒ matriz ('id'⇒…,' cmds '⇒ matriz (….))….,id1 ⇒
array ('id'⇒…,' cmds '⇒ array (….)), id2 ⇒ array (' id'⇒…, 'cmds' ⇒
array(…​.))..)

Configurações:

-   string \ [\] eqType = tabela dos tipos de equipamentos necessários

-   int \ [\] id = tabela de IDs de equipamentos personalizados desejados

eqLogic::save
-------------

Retorna o equipamento registrado / criado

Configurações:

-   int id (vazio se for uma criação)

-   string eqType\_name (tipo de script, equipamento virtual etc.)

-   nome da string

-   Log de stringicId = ''

-   int objeto\_id = nulo

-   int eqReal\_id = null

-   int isVisible = 0

-   int isEnable = 0

-   configuração de matriz

-   int timeout

-   categoria de matriz

API JSON Cmd
============

cmd::all
--------

Retorna a listaa de todos os comandos

cmd::byId
---------

Retorna o comando especificado

Configurações:

-   int id

cmd::byEqLogicId
----------------

Retorna todos os pedidos pertencentes ao equipamento especificado

Configurações:

-   int eqLogic\_id

cmd::execCmd
------------

Execute o comando especificado

Configurações:

-   int id : id de um comando ou matriz de id se você deseja executar
    pedidos múltiplos de uma só vez

-   \ [options \] Lista de opções de comando (depende do tipo e
    subtipo de comando)

cmd::getStatistique
-------------------

Retorna estatísticas do pedido (funciona apenas em
informações e comandos históricos)

Configurações:

-   int id

-   início da cordaTime : data de início do cálculo das estatísticas

-   string endTime : data final do cálculo das estatísticas

cmd::getTendance
----------------

Retorna a tendência no comando (funciona apenas nos comandos de
informações e tipo histórico)

Configurações:

-   int id

-   início da cordaTime : data de início do cálculo de tendência

-   string endTime : data de término do cálculo de tendência

cmd::getHistory
---------------

Retorna o histórico de comandos (funciona apenas nos comandos de
informações e tipo histórico)

Configurações:

-   int id

-   início da cordaTime : data de início do histórico

-   string endTime : data final do histórico

cmd::save
---------

Retorna o objeto especificado

Configurações:

-   int id (vazio se for uma criação)

-   nome da string

-   Log de stringicId

-   string eqType

-   ordem das cordas

-   tipo de string

-   string subType

-   int eqLogic\_id

-   int isHistorized = 0

-   unidade de cordas = ''

-   configuração de matriz

-   modelo de matriz

-   exibição de matriz

-   array html

-   valor int = nulo

-   int isVisible = 1

-   alerta de matriz

cmd::event
-------------------

Permite que você envie um valor para um pedido

Configurações:

-   int id

-   valor da string : Valor

-   data e hora da string : (opcional) valor data e hora

API do cenário JSON
=================

scenario::all
-------------

Retorna a listaa de todos os cenários

scenario::byId
--------------

Retorna o cenário especificado

Configurações:

-   int id

scenario::export
----------------

Retorna a exportaração do cenário, bem como o nome humano do cenário

Configurações:

-   int id

scenario::import
----------------

Permite importarar um cenário.

Configurações:

-   int id : ID do cenário no qual importarar (vazio se criação)

-   string humanName : nome humano do cenário (vazio se criação)

-   importaração de matriz : cenário (do campo cenário de exportaração::export)

scenario::changeState
---------------------

Altera o estado do cenário especificado.

Configurações:

-   int id

-   estado da string: \ [executar, parar, ativar, desativar \]

API de Log JSON
============

log::get
--------

Permite recuperar um Log

Configurações:

-   Log de string : nome do Log a recuperar

-   início da corda : número da linha na qual começar a ler

-   string nbLine : número de linhas para recuperar

log::list
---------

Obtenha a listaa de Logs do Jeedom

Configurações:

-   filtro de string : (opcional) filtro no nome dos Logs para recuperar

log::empty
----------

Esvaziar um Log

Configurações:

-   Log de string : nome do Log para esvaziar

log::remove
-----------

Permite excluir um Log

Configurações:

-   Log de string : nome do Log a ser excluído

API de armazenamento de dados JSON (variável)
=============================

datastore::byTypeLinkIdKey
--------------------------

Obter o valor de uma variável armazenada no armazenamento de dados

Configurações:

-   tipo de string : tipo de valor armazenado (para cenários
    é cenário)

-   id linkId : -1 para global (valor para cenários padrão,
    ou o ID do cenário)

-   chave de cadeia : nome do valor

datastore::save
---------------

Armazena o valor de uma variável no armazenamento de dados

Configurações:

-   tipo de string : tipo de valor armazenado (para cenários
    é cenário)

-   id linkId : -1 para global (valor para cenários padrão,
    ou o ID do cenário)

-   chave de cadeia : nome do valor

-   valor misto : valor a registrar

API de mensagem JSON
================

message::all
------------

Retorna a listaa de todas as mensagens

message::removeAll
------------------

Excluir todas as mensagens

API de interação JSON
====================

interact::tryToReply
--------------------

Tente combinar uma solicitação com uma interação, execute
ação e responde em conformidade

Configurações:

-   consulta (frase de solicitação)

-   int reply\_cmd = NULL : ID do comando a ser usado para responder,
    se não especificar, o Jeedom envia a resposta no json

interactQuery::all
------------------

Retorna a listaa completa de todas as interações

API do sistema JSON
===============

jeedom::halt
------------

Stop Jeedom

jeedom::reboot
--------------

Reinicie o Jeedom

jeedom::isOk
------------

Permite saber se o estado global de Jeedom está OK

jeedom::update
--------------

Permite iniciar uma atualização do Jeedom

jeedom::backup
--------------

Permite iniciar um backup do Jeedom

jeedom::getUsbMapping
---------------------

Lista de portas USB e nomes da chave USB conectada a ela

API de plug-in JSON
===============

plugin::install
---------------

Instalação / Atualização de um determinado plugin

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::remove
--------------

Exclusão de um determinado plugin

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::dependancyInfo
----------------------

Retorna informações sobre o status das dependências do plugin

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::dependancyInstall
-------------------------

Forçar a instalação das dependências do plug-in

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::deamonInfo
------------------

Retorna informações sobre o status do daemon do plug-in

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::deamonStart
-------------------

Forçar o demônio a começar

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::deamonStop
------------------

Forçar demônio parar

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

plugin::deamonChangeAutoMode
----------------------------

Alterar o modo de gerenciamento do daemon

Configurações:

-   plugin de string\_id : nome do plug-in (nome lógico)

-   modo int : 1 para automático, 0 para manual

API de atualização JSON
===============

update::all
-----------

Retorne a listaa de todos os componentes instalados, sua versão e o
informação relacionada

update::checkUpdate
-------------------

Permite verificar se há atualizações

update::update
--------------

Permite atualizar o Jeedom e todos os plugins

update::doUpdate
--------------

Configurações:

-   int plugin\_id (opcional) : ID do plugin
-   sequência lógicaId (opcional) : nome do plug-in (nome lógico)

API de rede JSON
================

network::restartDns
-------------------

Forçar o (re) início do DNS Jeedom

network::stopDns
----------------

Força o DNS Jeedom a parar

network::dnsRun
---------------

Retornar o status DNS do Jeedom

Exemplos de API JSON
=================

Aqui está um exemplo de uso da API. Para o exemplo abaixo
Eu uso [essa classe
php](https://github.com/jeedom/core/blob/release/core/class/jsonrpcClient.class.php)
o que simplifica o uso da API.

Recuperando a listaa de objetos :

`` `{.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-> sendRequest ('objeto::TODOS ', array ())){
    print_r ($ jsonrpc-> obterResult ());
}else{
    echo $ jsonrpc-> obterError ();
}
`` ''

Execução de uma ordem (com a opção de um título e uma mensagem)

`` `{.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-> sendRequest ('cmd::execCmd ', array (' id '=> # cmd_id #,' options '=> array (' title '=>' Cuckoo ',' Mensagem '=>' Funciona '))){
    eco 'OK';
}else{
    echo $ jsonrpc-> obterError ();
}
`` ''

É claro que a API pode ser usada com outros idiomas (simplesmente uma postagem
em uma página)
