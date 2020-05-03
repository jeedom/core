AquEu está a parte mais importante da automação residencial : os cenários.
Verdadeiro cérebro da domótica, é o que torna possível interagir com
o mundo real de uma "maneira inteligente".

à página de gerenciamento de cenários
================================

Gestion
-------

Para acessá-lo, nada mais simples, basta acessar Ferramentas ->
Cenários. Você encontrará a lista de cenários para o seu Jeedom também
apenas funciona para gerenciá-los melhor :

-   **Ajouter** : Crie um cenário. O procedimento é descrito
    no próximo capítulo.

-   **Desativar cenários** : Desativa todos os cenários.

-   **Veja variáveis** : Vamos ver as variáveis, seu valor também
    que o local onde eles são usados. Você também pode
    criar um. As variáveis são descritas em um capítulo da
    esta página.

-   **Visão global** : Permite que você tenha uma visão geral de todos
    os cenários. Você pode alterar os valores **actif**,
    **visible**, **MultEu lançamento**, **Sincronicamente**, **Log** et
    **Timeline** (esses parâmetros são descritos no próximo capítulo).
    Você também pode acessar os logs para cada cenário e
    comece individualmente.

-   **Expressão Tester** : Permite executar um teste em um
    expressão de sua escolha e exiba o resultado.

Meus scripts
-------------

Nesta seção, você encontrará o **lista de cenários** que você
criaram. Eles são classificados de acordo com **groupes** que você tem
definido para cada um deles. Cada cenário é exibido com seus **nom**
e o dele **Objeto pai**. O **cenários esmaecidos** são aqueles que são
desabilitado.

Como em muitas páginas Jeedom, coloque o mouse à esquerda de
a tela exibe um cardápio de acesso rápido (de
seu perfil, você sempre pode deixá-lo visível). Você pode
ENTÃO **chercher** seu cenário, mas também em **ajouter** um por isso
menu.

Editando um Cenário
=====================

Depois de clicar em **Ajouter**, você deve escolher o nome do seu
cenário e você será redirecionado para sua página de configurações gerais.
No topo, existem algumas funções úteis para gerenciar nosso cenário
:

-   **ID** : Ao lado da palavra **Geral**, este é o identificador de cenário.

-   **statut** : Estado atual do seu cenário.

-   **variables** : Ver variáveis.

-   **Expression** : Exibe o testador de expressão.

-   **Realizar** : Permite iniciar o cenário manualmente (lembre-se
    sem salvar antes!). Os gatilhos não são, portanto,
    não levado em consideração.

-   **Supprimer** : Excluir cenário.

-   **Sauvegarder** : Salve as alterações feitas.

-   **Template** : Permite acessar e aplicar modelos
    para o script do mercado. (explicado na parte inferior da página).

-   **Exporter** : Obter uma versão em texto do script.

-   **Log** : Exibe os logs do cenário.

-   **Dupliquer** : Copie o cenário para criar um
    novo com outro nome.

-   **Liens** : Permite visualizar o gráfico dos elementos vinculados
    com o script.

Guia Geral
--------------

Na aba **Geral**, encontramos os principais parâmetros de
nosso cenário :

-   **Nome do cenário** : O nome do seu cenário.

-   **Display Name** : O nome usado para sua exibição.

-   **Groupe** : Permite organizar os cenários, classificando-os em
    grupos.

-   **Actif** : Ativar o cenário.

-   **Visible** : Usado para tornar o cenário visível.

-   **Objeto pai** : Atribuição a um objeto pai.

-   **Segundos de tempo limite (0 = ilimitado)** : O tempo máximo de execução
    autorizado

-   **MultEu lançamento** : Marque esta caixa se desejar
    cenário pode ser iniciado várias vezes ao mesmo tempo.

-   **Sincronicamente** : Inicie o cenário no segmento atual em vez de um segmento dedicado. Aumenta a velocidade de lançamento do cenário, mas pode tornar o sistema instável.

-   **Log** : O tipo de Log desejado para o cenário.

-   **Siga na Timeline** : Acompanha o cenário
    na linha do tempo.

-   **Description** : Permite escrever um pequeno texto para descrever
    seu cenário.

-   **Modo de cenário** : O cenário pode ser programado, acionado ou
    ambos ao mesmo tempo. Você terá a opção de indicar o (s)
    gatilho (s) (tenha cuidado, há um limite para o número de gatilhos possíveis por cenário de 15) e a (s) programação (s).

> **Tip**
>
> Atenção : você pode ter no máximo 28
> gatilhos / programação para um cenário.

Guia Cenário
---------------

É aquEu que você criará seu cenário. Nós temos que começar
por **Adicionar bloco**, com o botão à direita. Uma vez um bloco
criado, você pode adicionar outro **bloc** onde um **action**.

> **Tip**
>
> Em condições e ações, é melhor usar aspas simples (') em vez de duplas (")

### Blocos

AquEu estão os diferentes tipos de blocos disponíveis :

-   **If / Then / Ou** : Permite que você execute ações
    sob condição (s).

-   **Action** : Permite iniciar ações simples sem
    sem condições.

-   **Boucle** : Permite executar ações repetidamente
    1 até um número definido (onde mesmo o valor de um sensor onde um
    número aleatório…).

-   **Dans** : Inicia uma ação em X minuto (s) (0 é um
    valor possível). à peculiaridade é que as ações são lançadas
    em segundo plano, para que eles não bloqueiem o restante do cenário.
    Portanto, é um bloco sem bloqueio.

-   **A** : Permite que o Jeedom inicie as ações do bloco em um momento
    tempo determinado (no formato hhmm). Este bloco é sem bloqueio. Ex :
    0030 para 00:30 onde 0146 para 1h46 e 1050 para 10h50.

-   **Code** : Permite escrever diretamente no código PHP (solicitação
    algum conhecimento e pode ser arriscado, mas permite não ter
    sem restrições).

-   **Commentaire** : Permite adicionar comentários ao seu cenário.

Cada um desses blocos tem suas opções para lidar melhor com eles :

-   à caixa de seleção à esquerda permite desativar completamente o
    bloquear sem excluí-lo.

-   à seta dupla vertical à esquerda permite mover todo o
    bloquear por arrastar e soltar.

-   O botão, na extrema direita, permite excluir todo o bloco.

#### Se / Então / Caso contrário, bloqueia, Loop, In e A

> **Note**
>
> Nos blocos SEu / Então / Caso contrário, setas circulares localizadas
> à esquerda do campo de condição permitem ativar onde não o
> repetição de ações se a avaliação da condição fornecer o mesmo
> resultado que a avaliação anterior.

Para as condições, Jeedom tenta garantir que possamos
escreva o máximo possível em linguagem natural enquanto permanece flexível. Três
botões estão disponíveis à direita deste tipo de bloco para
selecione um item para testar :

-   **Ordem de pesquisa** : Permite procurar um pedido em
    todos aqueles disponíveis no Jeedom. Uma vez que o pedido é encontrado,
    Jeedom abre uma janela para perguntar qual teste você deseja
    executar nele. Se você escolher **Não ponha nada**,
    Jeedom adicionará o pedido sem comparação. Você também pode
    Escolher **et** onde **ou** Na frente **Ensuite** para encadear testes
    em equipamentos diferentes.

-   **Pesquisa cenário** : Permite procurar um cenário
    testar.

-   **Procure equipamento** : O mesmo para equipamentos.

> **Tip**
>
> Há uma lista de tags que permitem acesso a variáveis
> do script onde de outro, onde por hora, data, um
> número aleatório,…. Veja mais os capítulos sobre comandos e
> Tags.

Depois de concluída a condição, você deve usar o botão
"adicionar ", à esquerda, para adicionar um novo **bloc** onde um
**action** no bloco atual.

> **Tip**
>
> Você NÃO DEVE usar [] em testes de condição, apenas parênteses () são possíveis

#### Código de bloco

> **Important**
>
> Observe que as tags não estão disponíveis em um bloco de código.

Controles (sensores e atuadores):
-   cmd::byString ($ string); : Retorna o objeto de comando correspondente.
  -   $string : Link para o pedido desejado : #[objeto] [equipamento] [comando] # (ex : #[àpartamento] [àlarme] [àtivo] #)
-   cmd::byId ($ id); : Retorna o objeto de comando correspondente.
  -   $id : ID do pedido
-   $cmd->execCmd($options = null); : Execute o comando e retorne o resultado.
  -   $options : Opções para executar o comando (pode ser específico do plugin), opção básica (subtipo de comando) :
    -   Mensagem : $option = array('title' => 'titre du Mensagem , 'message' => 'Mon message');
    -   cor : $option = array('color' => 'couleur en hexadécimal');
    -   controle deslizante : $option = array('slider' => 'valeur voulue de 0 à 100');

Log :
-   log::add ('nome do arquivo', 'nível', 'mensagem');
  -   filename : Nome do arquivo de Log.
  -   nível : [depuração], [Eunformações], [erro], [evento].
  -   Mensagem : Mensagem para escrever nos logs.

Cenas :
-   $scenario->getName(); : Retorna o nome do cenário atual.
-   $scenario->getGroup(); : Retorna o grupo de cenários.
-   $scenario->getIsActive(); : Retorna o estado do cenário.
-   $scenario->setIsActive($active); : Permite ativar onde não o cenário.
  -   $active : 1 ativo, 0 inativo.
-   $scenario->setOnGoing($onGoing); : Vamos dizer se o cenário está em execução onde não.
  -   $onGoing => 1 en cours , 0 arrêté.
-   $scenario->save(); : Salvar alterações.
-   $scenario->setData($key, $value); : Salvar um dado (variável).
  -   $key : chave de valor (int onde string).
  -   $value : valor a armazenar (int, string, array onde objeto).
-   $scenario->getData($key); : Obter dados (variável).
  -   $key => chave de valor (int onde string).
-   $scenario->removeData($key); : Excluir dados.
-   $scenario->setLog($message); : Escreva uma mensagem no Log de script.
-   $scenario->persistLog(); : Forçar a gravação do Log (caso contrário, ele será gravado apenas no final do cenário). Cuidado, isso pode atrasar um pouco o cenário.

### Acções

As ações adicionadas aos blocos têm várias opções. Em ordem :

-   Uma caixa **paralelo** para que este comando seja lançado em paralelo
    outros comandos também selecionados.

-   Uma caixa **ativado** para que esse comando seja levado em consideração
    conta no cenário.

-   Um **seta dupla vertical** para mover a ação. Apenas
    arraste e solte a partir daí.

-   Um botão para excluir a ação.

-   Um botão para ações específicas, sempre com o
    descrição desta ação.

-   Um botão para procurar um comando de ação.

> **Tip**
>
> Dependendo do comando selecionado, podemos ver diferentes
> campos adicionais exibidos.

Possíveis substituições
===========================

Triggers
----------------

Existem gatilhos específicos (além dos fornecidos pelo
Pedidos) :

-   #start# : Acionado no (re) início do Jeedom,

-   #begin_backup# : Evento enviado no início de um backup.

-   #end_backup# : Evento enviado no final de um backup.

-   #begin_update# : Evento enviado no início de uma atualização.

-   #end_update# : Evento enviado no final de uma atualização.

-   #begin_restore# : Evento enviado no início de uma restauração.

-   #end_restore# : Evento enviado no final de uma restauração.

-   #user_connect# : Login do usuário

Você também pode disparar um cenário quando uma variável estiver definida como
dia colocando : #variável (nome_da variável) # onde usando a API HTTP
descrito
[aquEu](https://jeedom.github.io/core/fr_FR/api_http).

Operadores de comparação e links entre condições
-------------------------------------------------------

Você pode usar qualquer um dos seguintes símbolos para
comparações em condições :

-   == : Igual a,

-   \> : Estritamente maior que,

-   \>= : Maior onde igual a,

-   < : Estritamente menor que,

-   <= : Menor onde igual a,

-   != : Diferente de, não é igual a,

-   correspondências : contém (ex :
    [Banheiro] [Hidrometria] [estado] corresponde a "/ molhado /"),

-   não (... corresponde ...) : não contém (ex :
    not ([Banheiro] [Hidrometria] [estado] corresponde a "/ molhado /")),

Você pode combinar qualquer comparação com operadores
seguindo :

-   && / ET / e / AND / e : et,

-   \|| / OU / onde / OU / onde : ou,

-   \|^ / XOR / xor : onde exclusivo.

Tags
--------

Uma tag é substituída durante a execução do cenário por seu valor. Você
pode usar as seguintes tags :

> **Tip**
>
> Para exibir os zeros à esquerda, use o
> Função Date (). Veja
> [aquEu](http://php.net/manual/fr/function.date.php).

-   #seconde# : Segundo atual (sem zeros à esquerda, ex : 6 para
    08:07:06),

-   #heure# : Hora atual no formato 24h (sem zeros à esquerda),
    ex : 8 para 08:07:06 onde 17 para 17:15),

-   #heure12# : Hora atual no formato de 12 horas (sem zeros à esquerda),
    ex : 8 para 08:07:06),

-   #minute# : Minuto atual (sem zeros à esquerda, ex : 7 para
    08:07:06),

-   #jour# : Dia atual (sem zeros à esquerda, ex : 6 para
    07/06/2017),

-   #mois# : Mês atual (sem zeros à esquerda, ex : 7 para
    07/06/2017),

-   #annee# : Ano atual,

-   #time# : Hora e minuto atuais (ex : 1715 para 17h15),

-   #timestamp# : Número de segundos desde 1 de janeiro de 1970,

-   #date# : Dia e mês. Atenção, o primeiro número é o mês.
    (ex : 1215 para 15 de dezembro),

-   #semaine# : Número da semana (ex : 51),

-   #sjour# : Nome do dia da semana (ex : Samedi),

-   #njour# : Número do dia de 0 (domingo) a 6 (sábado),

-   #smois# : Nome do mês (ex : Janvier),

-   #IP# : IP interno da Jeedom,

-   #hostname# : Nome da máquina Jeedom,

-   #trigger# : Talvez o nome do comando que inicionde o cenário, 'api', se o lançamento foEu iniciado pela API, 'schedule', se foEu iniciado pela programação, 'user', se iniciado manualmente

Você também possuEu as seguintes tags adicionais se o seu script tiver sido
desencadeado por uma interação :

-   #query# : Interação que aciononde o cenário,

-   #profil# : perfil do usuário que inicionde o cenário
    (pode estar vazio).

> **Important**
>
> Quando um cenário é acionado por uma interação, é
> necessariamente executado no modo rápido.

Funções de cálculo
-----------------------

Várias funções estão disponíveis para o equipamento :

-   average (order, period) and averageBetween (order, start, end)
    : Dê a média do pedido ao longo do período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre os 2 terminais necessários (no formato Ymd H:i:s ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   min (ordem, período) e minBetween (ordem, início, fim) :
    Dê o pedido mínimo durante o período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre os 2 terminais necessários (no formato Ymd H:i:s ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   max (ordem, período) e maxBetween (ordem, início, fim) :
    Forneça o máximo do pedido durante o período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre os 2 terminais necessários (no formato Ymd H:i:s ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   duração (ordem, valor, período) e
    durationbetween (comando, valor, início, fim) : Indique a duração em
    minutos durante os quais o equipamento teve o valor selecionado no
    período (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre os 2 terminais necessários (no formato Ymd H:i:s ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   estatísticas (ordem, cálculo, período) e
    statisticsBetween (comando, cálculo, início, fim) : Dê o resultado
    cálculos estatísticos diferentes (soma, contagem, padrão,
    variação, média, mín. e máx.) durante o período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre os 2 terminais necessários (no formato Ymd H:i:s ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   tendência (ordem, período, limite) : Dá a tendência de
    ordem durante o período (período = [mês, dia, hora, min] ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   stateDuration (comando) : Dá duração em segundos
    desde a última mudança de valor. Retorna -1 se nenhum
    o histórico não existe onde se o valor não existe no histórico.
    Retorna -2 se o pedido não estiver registrado.

-   lastChangeStateDuration (comando, valor) : Indique a duração em
    segundos desde a última mudança de estado para o valor passado
    como parâmetro. Retorna -1 se nenhum
    o histórico não existe onde se o valor não existe no histórico.
    Retorna -2 se o pedido não estiver registrado

-   lastStateDuration (comando, valor) : Dá duração em segundos
    durante o qual o equipamento teve recentemente o valor escolhido.
    Retorna -1 se não houver histórico onde se o valor não existir no histórico.
    Retorna -2 se o pedido não estiver registrado

-   stateChanges (ordem, [valor], período) e
    stateChangesBetween (comando, [valor], início, fim) : Dê o
    número de alterações de estado (para um determinado valor, se indicado,
    onde no total, de outra forma) durante o período (período = [mês, dia, hora, min] ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre os 2 terminais necessários (no formato Ymd H:i:s ou
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   lastBetween (comando, início, fim) : Retorna o último valor
    registrado para o equipamento entre os 2 terminais necessários (sob o
    formulário Ymd H:i:s onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   variável (variável, padrão) : Obtenha o valor de um
    variável onde o valor padrão desejado :

-   cenário : Retorna o status do cenário. 1 em andamento, 0
    se parado e -1 se desativado, -2 se o cenário não existir e -3
    se o estado não for consistente. Para ter o nome "humano" do cenário, você pode usar o botão dedicado à direita da pesquisa de cenário.

-   lastScenarioExecution (cenário) : Dá duração em segundos
    desde o lançamento do último cenário :

-   collectDate (cmd, [formato]) : Retorna a data dos últimos dados
    para o comando fornecido como parâmetro, o segundo parâmetro opcional
    permite especificar o formato de retorno (detalhes
    [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1
    significa que o pedido não pode ser encontrado e -2 que o pedido não é
    nenhum tipo de informação

-   valueDate (cmd, [formato]) : Retorna a data dos últimos dados
    para o comando fornecido como parâmetro, o segundo parâmetro opcional
    permite especificar o formato de retorno (detalhes
    [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1
    significa que o pedido não pode ser encontrado e -2 que o pedido não é
    nenhum tipo de informação

-   eqEnable (equipamento) : Retorna o status do equipamento. -2 se
    o equipamento não pode ser encontrado, 1 se o equipamento estiver ativo e 0 se não estiver
    está inativo

-   valor (cmd) : Retorna o valor de um pedido, se não for fornecido automaticamente pelo Jeedom (caso ao armazenar o nome do pedido em uma variável)    

-   tag (montag, [padrão]) : Usado para recuperar o valor de uma tag ou
    o padrão se não existir :

-   nome (tipo, comando) : Usado para recuperar o nome do comando,
    equipamento onde objeto. O tipo vale cmd, eqLogic ou
    objeto.

-   lastCommunication (equipamento, [formato]) : Retorna a data da última comunicação
    para o equipamento dado como parâmetro, o segundo parâmetro opcional
    permite especificar o formato de retorno (detalhes
    [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1
    significa que o equipamento não pode ser encontrado

-   color_gradient (start_colour, end_colour, min_value, max_value, value) : Retorna uma cor calculada com relação ao valor no intervalo color_Começo / color_end. O valor deve estar entre min_value e max_value

Os períodos e intervalos dessas funções também podem
use com [expressões
PHP](http://php.net/manual/fr/datetime.formats.relative.php) comme par
Exemplo :

-   Agora : maintenant

-   Hoje : 00:00 hoje (permite, por exemplo, obter
    resultados do dia entre 'Hoje' e 'Agora')

-   Segunda-feira passada : segunda-feira passada às 00:00

-   5 dias atrás : 5 dias atrás

-   Ontem meio-dia : ontem ao meio dia

-   Etc.

AquEu estão exemplos práticos para entender os valores retornados por
essas diferentes funções :

| Soquete com valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos)    |
|--------------------------------------|--------------------------------------|
| média (captura, período)             | Retorna a média de 0 e 1 (pode  |
|                                      | ser influenciado pela pesquisa)      |
| averageBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o pedido médio entre 1 de janeiro de 2015 e 15 de janeiro de 2015                         |
| min (captura, período)                 | Retorna 0 : o plugue foEu extinto durante o período              |
| minBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o pedido mínimo entre 1 de janeiro de 2015 e 15 de janeiro de 2015                         |
| max (captura, período)                 | Retorna 1 : o plugue estava bem iluminado no período              |
| maxBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o máximo do pedido entre 1 de janeiro de 2015 e 15 de janeiro de 2015                         |
| duração (tomada, 1, período)          | Retorna 60 : o plugue ficonde (em 1) por 60 minutos no período                              |
| durationBetween (\# [Salon] [Take] [State] \#, 0, última segunda-feira, agora)   | Retorna a duração em minutos durante os quais o soquete estava desativado desde a última segunda-feira.                |
| estatísticas (captura, contagem, período)    | Retorna 8 : houve 8 escalações no período               |
| tendência (captura, período, 0.1)        | Retorna -1 : tendência descendente    |
| stateDuration (captura)               | Retorna 600 : o plugue está em seu estado atual por 600 segundos (10 minutos)                             |
| lastChangeStateDuration (obtido, 0)   | Retorna 600 : o soquete saiu (mude para 0) pela última vez há 600 segundos (10 minutos) atrás     |
| lastChangeStateDuration (take, 1)   | Retorna 4200 : a tomada ligada (mude para 1) pela última vez há 4200 segundos (1h10)                               |
| lastStateDuration (obtido, 0)         | Retorna 600 : o soquete está desligado por 600 segundos (10 minutos)     |
| lastStateDuration (take, 1)         | Retorna 3600 : o soquete foEu ligado pela última vez por 3600 segundos (1h)           |
| stateChanges (tomada, período)        | Retorna 3 : o plugue mudonde de estado 3 vezes durante o período            |
| stateChanges (take, 0, período)      | Retorna 2 : o soquete apagonde (passando para 0) duas vezes durante o período                              |
| stateChanges (take, 1, period)      | Retorna 1 : o plugue está aceso (mude para 1) uma vez durante o período                              |
| lastBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, ontem, hoje) | Retorna a última temperatura registrada ontem.                    |
| variável (plop, 10)                  | Retorna o valor da variável plop onde 10 se estiver vazia onde não existir                         |
| cenário (\# [Banheiro] [Leve] [àutomático] \#) | Retorna 1 em andamento, 0 se parado e -1 se desativado, -2 se o cenário não existir e -3 se o estado não for consistente                         |
| lastScenarioExecution (\# [Banheiro] [Leve] [àutomático] \#)   | Retorna 300 se o cenário foEu iniciado pela última vez há 5 minutos                                  |
| collectDate (\# [Banheiro] [Hidrometria] [Umidade] \#)     | Devoluções 01-01-2015 17:45:12          |
| valueDate (\# [Banheiro] [Hidrometria] [Umidade] \#) | Devoluções 01-01-2015 17:50:12          |
| eqEnable (\# [Nenhum] [Basílica] \#)       | Retorna -2 se o equipamento não for encontrado, 1 se o equipamento estiver ativo e 0 se estiver inativo          |
| tag (montag, toto)                   | Retorna o valor de "montag" se existir, caso contrário, retorna o valor "para"                               |
| nome (eqLogic, \# [Banheiro] [Hidrometria] [Umidade] \#)     | Retorna Hidrometria                  |

Funções matemáticas
---------------------------

Uma caixa de ferramentas de funções genéricas também pode ser usada para
realizar conversões onde cálculos :

-   rand (1,10) : Dê um número aleatório de 1 a 10.

-   randText (texto1; texto2; texto ...) : Retorna um dos
    textos aleatoriamente (separar o texto por um;). Não existe
    limite no número de texto.

-   randomColor (mínimo, máximo) : Dá uma cor aleatória entre 2
    terminais (0 => vermelho, 50 => verde, 100 => azul).

-   gatilho (comando) : Usado para descobrir o gatilho para o cenário
    onde para saber se é o pedido feito como um parâmetro que possui
    desencadeonde o cenário.

-   triggerValue (comando) : Usado para descobrir o valor de
    gatilho de cenário.

-   round (valor, [decimal]) : Arredondar acima, [decimal]
    número de casas decimais após o ponto decimal.

-   ímpor (valor) : Permite saber se um número é ímpor onde não.
    Retorna 1 se ímpor 0, caso contrário.

-   mediana (comando1, comando2….commandN) : Retorna a mediana
    valores.

-   time_op (hora, valor) : Permite que você execute operações no prazo,
    com Tempo = Tempo (ex : 1530) e value = value para adicionar onde adicionar
    subtrair em minutos.

-   `time_between (hora, início, fim)` : Permite testar se é uma hora
    entre dois valores com `Tempo = time` (ex : 1530), `Começo = time`,` end = time`.
    Os valores inicial e final podem chegar à meia-noite.

-   `time_diff (data1, data1 [, formato])` : Usado para descobrir a diferença entre duas datas (as datas devem estar no formato AAAà / MM / DD HH:MM:SS).
    Por padrão (se você não colocar nada no formato), o método retornará o número total de dias. Você pode perguntar em segundos (s), minutos (m), horas (h). Exemplo em segundos `time_diff (02-02 2018 14:55:00,2018-02-25 14:55:00,s)`

-   `formatTime (time)` : Formata o retorno de uma cadeia
    `# tempo #`.

-   andar (hora / 60) : Converte de segundos para minutos ou
    minutos a horas (piso (tempo / 3600) por segundos
    em horas)

E exemplos práticos :


| Exemplo de função                  | Resultado retornado                    |
|--------------------------------------|--------------------------------------|
| randText (é # [sala] [olho] [temperatura] #; à temperatura é # [sala] [olho] [temperatura] #; Atualmente, temos # [sala] [olho] [temperatura] #) | a função retornará um desses textos aleatoriamente a cada execução.                           |
| randomColor (40,60)                 | Retorna uma cor aleatória próxima ao verde.   
| gatilho (# [Banheiro] [Hidrometria] [Umidade] #)   | 1 se estiver bom \# \ [Banheiro \] \ [Hidrometria \] \ [Umidade \] \# que inicionde o cenário caso contrário 0  |
| triggerValue (# [Banheiro] [Hidrometria] [Umidade] #) | 80 se a hidrometria de \# \ [Banheiro \] \ [Hidrometria \] \ [Umidade \] \# for 80%.                         |
| redondo (# [Banheiro] [Hidrometria] [Umidade] # / 10) | Retorna 9 se a porcentagem de umidade e 85                     |
| ímpor (3)                             | Retorna 1                            |
| mediana (15,25,20)                   | Retorna 20                           |
| time_op (# Tempo #, -90)               | se forem 16h50, retorne : 1 650 - 1 130 = 1520                          |
| formatTime (1650)                   | Retorna 16:50                        |
| de piso (130/60)                      | Retorna 2 (minutos se 130s onde horas se 130m)                      |

Pedidos específicos
=========================

Além dos comandos de automação residencial, você tem acesso às seguintes ações :

-   **Pause** (dormir) : Pausa x segundo (s).

-   **variable** (variável) : Criação / modificação de uma variável onde valor
    de uma variável.

-   **Remover variável** (delete_variable) : Permite excluir uma variável

-   **Cenas** (cenário) : Permite controlar cenários. à parte de tags
    permite enviar tags para o cenário, ex : montag = 2 (tenha cuidado lá
    use apenas letras de a a z. Sem letras maiúsculas, sem
    acentos e sem caracteres especiais). Recebemos a tag no
    cenário de destino com a função tag (montag). O comando "Rese SI" permite redefinir o status de "SI" (esse status é usado para a não repetição das ações de um "SI" se você passar pela segunda vez consecutiva)

-   **Stop** (parar) : Pára o script.

-   **Attendre** (espera) : Aguarde até que a condição seja válida
    (máximo de 2h), o tempo limite está em segundo (s).

-   **VaEu o projeto** (gotodesign) : Mude o design exibido em todos
    navegadores por design solicitado.

-   **Adicionar um registro** (log) : Permite adicionar uma mensagem no Log.

-   **Criar mensagem** (mensagem) : Adicione uma mensagem no centro
    de mensagens.

-   **Activar / Desactivar Hide / Show equipamentos** (equipamento) : Deixa
    modificar as propriedades de um dispositivo
    visível / invisível, ativo / inativo.

-   **Aplicar** (pergunte) : Permite que você diga ao Jeedom para perguntar
    uma pergunta para o usuário. à resposta é armazenada em um
    variável, então apenas teste seu valor. Por enquanto,
    apenas plugins sms e slack são compatíveis. Tenha cuidado, isso
    função está bloqueando. Enquanto não houver resposta onde o
    timeout não for atingido, o cenário aguarda.

-   **Pare Jeedom** (jeedom_poweroff) : Peça ao Jeedom para desligar.

-   **Reiniciar o Jeedom** (jeedom_reboot) : Peça ao Jeedom para reiniciar.

-   **Retornar um texto / um dado** (cenário_retorno) : Retorna um texto onde um valor
    para uma interação, por exemplo.

-   **ícone** (ícone) : Permite alterar o ícone de representação do cenário.

-   **Alerte** (alerta) : Permite exibir uma pequena mensagem de alerta em todos
    navegadores que têm uma página Jeedom aberta. Você pode
    mais, escolha 4 níveis de alerta.

-   **Pop-up** (pop-up) : Permite exibir um pop-up que deve ser absolutamente
    validado em todos os navegadores que possuem uma página jeedom aberta.

-   **Rapport** (relatório) : Exportar uma visualização em formato (PDF, PNG, JPEG
    onde SVG) e envie-o através de um comando de tipo de mensagem.
    Observe que, se seu acesso à Interne estiver em HTTPS não assinado, isso
    funcionalidade não funcionará. HTTP onde HTTPS assinado é necessário.

-   **Excluir bloco IN / à agendado** (remove_inat) : Permite excluir o
    programação de todos os blocos IN e à do cenário.

-   **Evento** (evento) : Permite inserir um valor em um comando de tipo de informação arbitrariamente

-   **Tag** (tag) : Permite adicionar / modificar uma tag (a tag existe apenas durante a execução atual do cenário, diferente das variáveis que sobrevivem ao final do cenário)

Modelo cenário
====================

Essa funcionalidade permite transformar um cenário em um modelo para
por exemplo, aplique-o em outro Jeedom onde compartilhe-o no
Mercado. É também a partir daí que você pode recuperar um cenário
do mercado.

![scenario15](../images/scenario15.JPG)

Você verá esta janela :

![scenario16](../images/scenario16.JPG)

à partir daí, você tem a possibilidade :

-   Envie um modelo para o Jeedom (arquivo JSON antecipadamente
    recuperado),

-   Consulte a lista de cenários disponíveis no mercado,

-   Crie um modelo a partir do cenário atual (não se esqueça de
    dê um nome),

-   Para consultar os modelos atualmente presentes no seu Jeedom.

Ao clicar em um modelo, você obtém :

![scenario17](../images/scenario17.JPG)

No topo você pode :

-   **Partager** : Compartilhe o modelo no mercado,

-   **Supprimer** : Excluir modelo,

-   **Baixar** : recuperar o modelo como um arquivo JSON
    para enviá-lo de volta para outro Jeedom, por exemplo.

Abaixo, você tem a parte para aplicar seu modelo a
cenário atual.

Desde de um Jeedom para outro onde de uma instalação para outra,
os pedidos podem ser diferentes, Jeedom solicita a
correspondência de ordens entre os presentes durante a criação
do modelo e os presentes em casa. Você só precisa preencher o
as ordens de correspondência se aplicam.

Adição da função php
====================

> **IMPORTANT**
>
> à adição da função PHP é reservada para usuários avançados. O menor erro pode travar o seu Jeedom

## Configurar

Vá para a configuração do Jeedom, então OS / DB e inicie o editor de arquivos.

Vá para a pasta de dados, php e clique no arquivo user.function.class.php.

É nesta classe que você deve adicionar suas funções, você encontrará um exemplo de função básica.

> **IMPORTANT**
>
> Se você tiver um problema, sempre poderá voltar ao arquivo original e copiar o conteúdo de user.function.class.sample.php em user.function.class.php
