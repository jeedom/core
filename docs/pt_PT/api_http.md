# API HTTP

A Jeedom fornece aos desenvolvedores e usuários uma API completa para que eles possam controlar a Jeedom a partir de qualquer objeto conectado.

Duas APIs estão disponíveis : um piloto JSON RPC 2 orientado ao desenvolvedor.0 e outro via solicitação de URL e HTTP.

Essa API é muito fácil de usar por solicitações HTTP simples via URL.

> **NOTA**
>
> Para toda esta documentação, \#IP\_JEEDOM\# corresponde ao seu URL de acesso Jeedom. Este é (a menos que você esteja conectado à sua rede local) o endereço da Internet que você usa para acessar o Jeedom de fora.

> **NOTA**
>
> Para toda esta documentação, \#API\_KEY\# corresponde à sua chave API, específica para sua instalação. Para encontrá-lo, vá ao menu "Geral" → guia "Configuração" → "Geral"".

## Cenas

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cenário & id = \#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cenário & id=#ID#&action=#ACTION#)

- **ID** : corresponde ao seu ID do cenário. O ID pode ser encontrado na página do cenário relevante, em "Ferramentas" → "Cenários", após a seleção do cenário, ao lado do nome da guia "Geral"". Outra maneira de encontrá-lo : em "Ferramentas" → "Cenários", clique em "Visão geral".
- **Ação** : corresponde à ação que você deseja aplicar. Os comandos disponíveis são : "iniciar "," parar "," desativar "e" ativar "para iniciar, parar, desativar ou ativar o cenário, respectivamente.
- **Etiquetas** \ [opcional \] : se a ação for "iniciar", você pode passar as tags para o cenário (consulte a documentação sobre os cenários) nas tags de formulário = para% 3D1% 20tata% 3D2 (observe que% 20 corresponde a um espaço e% 3D para = ).

##  Comando Info / Ação

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cmd & id = \#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cmd & id=#ID#)

- **ID** : corresponde ao ID do que você deseja controlar ou do qual deseja receber informações.

A maneira mais fácil de obter esse URL é ir para a página **Análise → Resumo da automação residencial**, para pesquisar o pedido e abrir sua configuração avançada (o ícone "engrenagem"), você verá um URL que já contém tudo o que precisa, dependendo do tipo e subtipo do pedido.

> **NOTA**
>
> É possível para o campo \#ID\# faça vários pedidos de uma só vez. Para fazer isso, você deve passar uma matriz em json (ex% 5B12,58,23% 5D, observe que \ [e \] devem ser codificados, portanto,% 5B e% 5D). O retorno de Jeedom será um json.

> **NOTA**
>
> Os parâmetros devem ser codificados para o URL, você pode usar uma ferramenta, [aqui](https://meyerweb.com/eric/tools/dencoder/).

## Interaction

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = interact & query = \#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = interagir e consultar=#QUERY#)

- **consulta** : pergunta a Jeedom.
- **utf8** \ [opcional \] : informa ao Jeedom se deve codificar a consulta no utf8 antes de tentar responder.
- **emptyReply** \ [opcional \] : 0 para Jeedom responder mesmo se ele não entendeu, 1 caso contrário.
- **perfil** \ [opcional \] : nome de usuário da pessoa que iniciou a interação.
- **reply\_cmd** \ [opcional \] : ID do pedido a ser usado para responder à solicitação.

## Message

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = message & category = \#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = message & category=#CATEGORY#&message=#MESSAGE#)

- **categoria** : categoria de mensagem para adicionar ao centro de mensagens.
- **Mensagem** : mensagem em questão, tenha cuidado ao pensar em codificar a mensagem (o espaço se torna% 20, =% 3D…). Você pode usar uma ferramenta, [aqui](https://meyerweb.com/eric/tools/dencoder/).

## Objet

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = object)

Retorna em json a lista de todos os objetos Jeedom.

## Equipement

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = eqLogic & object\_id = \#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = eqLogic & object_id=#OBJECT_ID#)

- **objeto\_id** : ID do objeto cujo equipamento queremos recuperar.

## Commande

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = command & eqLogic\_id = \#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = command & eqLogic_id=#EQLOGIC_ID#)

- **eqLogic\_id** : ID do equipamento do qual os pedidos devem ser recuperados.

## Dados completos

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = fullData)

Retorna todos os objetos, equipamentos, comandos (e seus valores se forem informações) em json.

## Variable

Voaqui l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = variable & name = \#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = variável & nome=#NAME#&value=)*Valor*

- **nome** : nome da variável cujo valor é desejado (lendo o valor).
- **Valor** \ [opcional \] : se "value" for especificado, a variável aceitará esse valor (escrevendo um valor).
