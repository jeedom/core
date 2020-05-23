Jeedom fornece aos desenvolvedores e usuários uma API
completo para que você possa controlar o Jeedom de qualquer objeto
connecté.

Duas APIs estão disponíveis : um piloto orientado ao desenvolvedor
JSON RPC 2.0 e outro via solicitação de URL e HTTP.

Essa API é muito facilmente usada por solicitações HTTP simples via
URL.

> **NOTA**
>
> Para toda esta documentação, \#IP\_JEEDOM\# corresponde ao seu URL
> acesso ao Jeedom. Isso é (a menos que você esteja conectado à sua rede
> local) do endereço da Internet usado para acessar o Jeedom
> do lado de fora.

> **NOTA**
>
> Para toda esta documentação, \#API\_KEY\# corresponde à sua chave
> API, específica para sua instalação. Para encontrá-lo, você tem que ir para
> o menu "Geral" → guia "Configuração" → "Geral"".

Cenas 
========

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cenário & id = \#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cenário & id=#ID#&action=#ACTION#)

-   **ID** : corresponde ao seu ID do cenário. O ID está no
    página do cenário em questão, em "ferramentas" → "Cenários", uma vez que o
    cenário selecionado, ao lado do nome da guia "Geral". Outros
    maneira de encontrá-lo : em "Ferramentas" → "Cenários", clique em
    "Visão global".

-   **Ação** : corresponde à ação que você deseja aplicar. O
    pedidos disponíveis são : "iniciar "," parar "," desativar "e
    "ativar "para iniciar, parar, desativar ou
    ativar o cenário.

-   **Etiquetas** \ [opcional \] : se a ação for "iniciar", você pode pular
    para o cenário (consulte a documentação sobre cenários) em
    as tags do formulário = para% 3D1% 20tata% 3D2 (observe que% 20 corresponde a um
    espaço e% 3D para = )

Comando Info / Ação 
====================

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cmd & id = \#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cmd & id=#ID#)

-   **ID** : corresponde ao id do que você deseja dirigir ou do qual
    você deseja receber informações

A maneira mais fácil de obter esse URL é ir para a página Ferramentas →
Resumo da automação residencial, para procurar o comando e abrir sua configuração
avançado (o ícone "engrenagem") e você verá um URL que contém
já tudo o que você precisa, dependendo do tipo e subtipo do
commande.

> **NOTA**
>
> É possível para o campo \#ID\# fazer vários pedidos
> de repente. Para fazer isso, você deve passar uma matriz em json (ex
> % 5B12,58,23% 5D, observe que \ [e \] devem ser codificados; portanto,% 5B
> e% 5D). O retorno de Jeedom será um json

> **NOTA**
>
> Os parâmetros devem ser codificados para o URL, você pode usar
> uma ferramenta, [aqui](https://meyerweb.com/eric/tools/dencoder/)

Interação 
===========

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = interact & query = \#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = interagir e consultar=#QUERY#)

-   **consulta** : pergunta a Jeedom

-   **utf8** \ [opcional \] : informa ao Jeedom se deve codificar a consulta
    no utf8 antes de tentar responder

-   **emptyReply** \ [opcional \] : 0 para o Jeedom responder mesmo que
    não entendi, 1 caso contrário

-   **perfil** \ [opcional \] : nome de usuário da pessoa
    desencadeando interação

-   **reply\_cmd** \ [opcional \] : ID do comando a ser usado para
    atender a demanda

Mensagem 
=======

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = message & category = \#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = message & category=#CATEGORY#&message=#MESSAGE#)

-   **categoria** : categoria de mensagem para adicionar ao centro de mensagens

-   **Mensagem** : mensagem em questão, tenha cuidado ao pensar em codificação
    a mensagem (o espaço se torna% 20, =% 3D…). Você pode usar um
    outil, [aqui](https://meyerweb.com/eric/tools/dencoder/)

Objeto 
=====

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = object)

Retorna em json a lista de todos os objetos Jeedom

Equipamento 
==========

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = eqLogic & object\_id = \#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = eqLogic & object_id=#OBJECT_ID#)

-   **objeto\_id** : ID do objeto do qual queremos recuperar
    équipements

Ordem 
========

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = command & eqLogic\_id = \#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = command & eqLogic_id=#EQLOGIC_ID#)

-   **eqLogic\_id** : ID do equipamento do qual queremos recuperar
    commandes

Dados completos 
=========

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = fullData)

Retorna todos os objetos, equipamentos, comandos (e seu valor se este
são informações) em json

Variável 
========

Aqui está o URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = variable & name = \#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = variável & nome=#NAME#&value=)*Valor*

-   **nome** : nome da variável cujo valor é desejado (leitura de
    o valor)

-   **Valor** \ [opcional \] : se "value" for especificado, a variável
    assumirá esse valor (escrever um valor)


