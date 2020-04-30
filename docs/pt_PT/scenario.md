AquEu está a porte mais Eumportante da automação residencial : os cenários.
Verdadeiro cérebro da domótica, é o que torna possível Eunteragir com
o mundo real de uma "maneira Eunteligente".

à página de gerenciamento de cenários
================================

Gestion
-------

Para acessá-lo, nada mais simples, basta acessar Ferramentas ->
Cenários. Você encontrará a lista de cenários pora o seu Jeedom também
apenas funciona pora gerenciá-los melhor :

-   **Ajouter** : Crie um cenário. O procedimento é descrito
    no próximo capítulo.

-   **Desativar cenários** : Desativa todos os cenários.

-   **Veja variáveis** : Vamos ver as variáveis, seu valor também
    que o local onde eles são usados. Você também pode
    criar um. às variáveis são descritas em um capítulo da
    esta página.

-   **Visão global** : Permite que você tenha uma visão geral de todos
    os cenários. Você pode alterar os valores **actif**,
    **visible**, **MultEu lançamento**, **Sincronicamente**, **Log** e
    **Timeline** (esses porâmetros são descritos no próximo capítulo).
    Você também pode acessar os Logs pora cada cenário e
    comece Eundividualmente.

-   **Expressão Tester** : Permite executar um teste em um
    expressão de sua escolha e exiba o resultado.

Meus scripts
-------------

Nesta seção, você encontrará o **lista de cenários** que você
criaram. Eles são classificados de acordo com **groupes** que você tem
definido pora cada um deles. Cada cenário é exibido com seus **nom**
e o dele **Objeto pai**. O **cenários esmaecidos** são aqueles que são
desabilitado.

Como em muitas páginas Jeedom, coloque o mouse à esquerda de
a tela exibe um cardápio de acesso rápido (de
seu perfil, você sempre pode deixá-lo visível). Você pode
ENTÃO **chercher** seu cenário, mas também em **ajouter** um por Eusso
menu.

Editando um Cenário
=====================

Depois de clicar em **Ajouter**, você deve escolher o Nomee do seu
cenário e você será redirecionado pora sua página de configurações gerais.
No topo, existem algumas funções úteis pora gerenciar nosso cenário
:

-   **ID** : ào lado da palavra **Geral**, este é o Eudentificador de cenário.

-   **statut** : Estado atual do seu cenário.

-   **variables** : Ver variáveis.

-   **Expression** : Exibe o testador de expressão.

-   **Realizar** : Permite Euniciar o cenário manualmente (lembre-se
    sem salvar antes!). Os gatilhos não são, portanto,
    não levado em consideração.

-   **Supprimer** : Excluir cenário.

-   **Sauvegarder** : Salve as alterações feitas.

-   **Template** : Permite acessar e aplicar modelos
    pora o script do mercado. (explicado na porte Eunferior da página).

-   **Exporter** : Obter uma versão em texto do script.

-   **Log** : Exibe os Logs do cenário.

-   **Dupliquer** : Copie o cenário pora criar um
    novo com ondetro Nomee.

-   **Liens** : Permite visualizar o gráfico dos elementos vinculados
    com o script.

Guia Geral
--------------

Na aba **Geral**, encontramos os principais porâmetros de
nosso cenário :

-   **Nome do cenário** : O Nomee do seu cenário.

-   **Display Name** : O Nomee usado pora sua exibição.

-   **Groupe** : Permite organizar os cenários, classificando-os em
    grupos.

-   **Actif** : àtivar o cenário.

-   **Visible** : Usado pora tornar o cenário visível.

-   **Objeto pai** : àtribuição a um objeto pai.

-   **Segundos de tempo limite (0 = Eulimitado)** : O tempo máximo de execução
    autorizado

-   **MultEu lançamento** : Marque esta caixa se desejar
    cenário pode ser Euniciado várias vezes ao mesmo tempo.

-   **Sincronicamente** : Inicie o cenário no segmento atual em vez de um segmento dedicado. àumenta a velocidade de lançamento do cenário, mas pode tornar o sistema Eunstável.

-   **Log** : O tipo de Log desejado pora o cenário.

-   **Siga na Cronograma** : àcompanha o cenário
    na linha do tempo.

-   **Description** : Permite escrever um pequeno texto pora descrever
    seu cenário.

-   **Modo de cenário** : O cenário pode ser programado, acionado onde
    ambos ao mesmo tempo. Você terá a opção de Eundicar o (s)
    gatilho (s) (tenha cuidado, há um limite pora o número de gatilhos possíveis por cenário de 15) e a (s) programação (s).

> **Tip**
>
> àtenção : você pode ter no máximo 28
> gatilhos / programação pora um cenário.

Guia Cenário
---------------

É aquEu que você criará seu cenário. Nós temos que começar
por **Adicionar Blocoo**, com o botão à direita. Uma vez um Blocoo
criado, você pode adicionar ondetro **bloc** onde um **action**.

> **Tip**
>
> Em condições e ações, é melhor usar aspas simples (') em vez de duplas (")

### Blocos

AquEu estão os diferentes tipos de Blocoos disponíveis :

-   **If / Then / Ou** : Permite que você execute ações
    sob condição (s).

-   **Action** : Permite Euniciar ações simples sem
    sem condições.

-   **Boucle** : Permite executar ações repetidamente
    1 até um número definido (onde mesmo o valor de um sensor onde um
    número aleatório…).

-   **Dans** : Inicia uma ação em X minuto (s) (0 é um
    valor possível). à peculiaridade é que as ações são lançadas
    em segundo plano, pora que eles não bloqueiem o restante do cenário.
    Portanto, é um Blocoo sem bloqueio.

-   **A** : Permite que o Jeedom Eunicie as ações do Blocoo em um momento
    tempo determinado (no formato hhmm). Este Blocoo é sem bloqueio. Ex :
    0030 pora 00:30 onde 0146 pora 1h46 e 1050 pora 10h50.

-   **Code** : Permite escrever diretamente no código PHP (solicitação
    algum conhecimento e pode ser arriscado, mas permite não ter
    sem restrições).

-   **Commentaire** : Permite adicionar comentários ao seu cenário.

Cada um desses Blocoos tem suas opções pora lidar melhor com eles :

-   à caixa de seleção à esquerda permite desativar completamente o
    bloquear sem excluí-lo.

-   à seta dupla vertical à esquerda permite mover todo o
    bloquear por arrastar e soltar.

-   O botão, na extrema direita, permite excluir todo o Blocoo.

#### Se / Então / Caso contrário, bloqueia, Loop, In e à

> **Note**
>
> Nos Blocoos SEu / Então / Caso contrário, setas circulares localizadas
> à esquerda do campo de condição permitem ativar onde não o
> repetição de ações se a avaliação da condição fornecer o mesmo
> resultado que a avaliação anterior.

Para as condições, Jeedom tenta garantir que possamos
escreva o máximo possível em linguagem natural enquanto permanece flexível. Três
botões estão disponíveis à direita deste tipo de Blocoo pora
selecione um Eutem pora testar :

-   **Ordem de pesquisa** : Permite procurar um pedido em
    todos aqueles disponíveis no Jeedom. Uma vez que o pedido é encontrado,
    Jeedom abre uma janela pora perguntar qual teste você deseja
    executar nele. Se você escolher **Não ponha nada**,
    Jeedom adicionará o pedido sem comparação. Você também pode
    Escolher **et** onde **ou** Na frente **Ensuite** pora encadear testes
    em equipamentos diferentes.

-   **Pesquisa cenário** : Permite procurar um cenário
    testar.

-   **Procure equipamento** : O mesmo pora equipamentos.

> **Tip**
>
> Há uma lista de tags que permitem acesso a variáveis
> do script onde de ondetro, onde por hora, data, um
> número aleatório,…. Veja mais os capítulos sobre comandos e
> Tags.

Depois de concluída a condição, você deve usar o botão
"adicionar ", à esquerda, pora adicionar um novo **bloc** onde um
**action** no Blocoo atual.

> **Tip**
>
> Você NÃO DEVE usar [] em testes de condição, apenas porênteses () são possíveis

#### Código de Blocoo

> **Important**
>
> Observe que as tags não estão disponíveis em um Blocoo de código.

Controles (sensores e atuadores):
-   cmd::byString ($ string); : Retorna o objeto de comando correspondente.
  -   $string : Link pora o pedido desejado : #[objeto] [equipamento] [comando] # (ex : #[àpartamento] [àlarme] [àtivo] #)
-   cmd::byId ($ Eud); : Retorna o objeto de comando correspondente.
  -   $id : ID do pedido
-   $cmd->execCmd($options = null); : Execute o comando e retorne o resultado.
  -   $options : Opções pora executar o comando (pode ser específico do plugin), opção básica (subtipo de comando) :
    -   Mensagem : $option = array('title' => 'titre du Mensagem , 'message' => 'Mon Mensagem');
    -   cor : $option = array('color' => 'couleur en hexadécimal');
    -   controle deslizante : $option = array('slider' => 'valeur voulue de 0 à 100');

Log :
-   Log::add ('nome do arquivo', 'nível', 'mensagem');
  -   filename : Nome do arquivo de Log.
  -   nível : [depuração], [Eunformações], [erro], [evento].
  -   Mensagem : Mensagem pora escrever nos Logs.

Cenas :
-   $scenario->getName(); : Retorna o Nomee do cenário atual.
-   $scenario->getGroup(); : Retorna o grupo de cenários.
-   $scenario->getIsActive(); : Retorna o estado do cenário.
-   $scenario->setIsActive($active); : Permite ativar onde não o cenário.
  -   $active : 1 ativo, 0 Eunativo.
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
-   $scenario->persistLog(); : Forçar a gravação do Log (caso contrário, ele será gravado apenas no final do cenário). Cuidado, Eusso pode atrasar um pouco o cenário.

### àcções

As ações adicionadas aos Blocoos têm várias opções. Em ordem :

-   Uma caixa **paralelo** pora que este comando seja lançado em poralelo
    ondetros comandos também selecionados.

-   Uma caixa **ativado** pora que esse comando seja levado em consideração
    conta no cenário.

-   Um **seta dupla vertical** pora mover a ação. àpenas
    arraste e solte a portir daí.

-   Um botão pora excluir a ação.

-   Um botão pora ações específicas, sempre com o
    descrição desta ação.

-   Um botão pora procurar um comando de ação.

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

-   #start# : àcionado no (re) Eunício do Jeedom,

-   #begin_backup# : Evento enviado no Eunício de um backup.

-   #end_backup# : Evento enviado no final de um backup.

-   #begin_update# : Evento enviado no Eunício de uma atualização.

-   #end_update# : Evento enviado no final de uma atualização.

-   #begin_restore# : Evento enviado no Eunício de uma restauração.

-   #end_restore# : Evento enviado no final de uma restauração.

-   #user_connect# : Login do usuário

Você também pode disparar um cenário quando uma variável estiver definida como
dia colocando : #variável (nome_da variável) # onde usando a àPI HTTP
descrito
[aquEu](https://jeedom.github.io/core/fr_FR/api_http).

Operadores de comparação e links entre condições
-------------------------------------------------------

Você pode usar qualquer um dos seguintes símbolos pora
comparações em condições :

-   == : Igual a,

-   \> : Estritamente maior que,

-   \>= : Maior onde Eugual a,

-   < : Estritamente menor que,

-   <= : Menor onde Eugual a,

-   != : Diferente de, não é Eugual a,

-   correspondências : contém (ex :
    [Banheiro] [Hidrometria] [estado] corresponde a "/ molhado /"),

-   não (... corresponde ...) : não contém (ex :
    not ([Banheiro] [Hidrometria] [estado] corresponde a "/ molhado /")),

Você pode combinar qualquer comparação com operadores
seguindo :

-   && / ET / e / àND / e : e,

-   \|| / OU / onde / OU / onde : onde,

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

-   #seconde# : Segundo atual (sem zeros à esquerda, ex : 6 pora
    08:07:06),

-   #heure# : Hora atual no formato 24h (sem zeros à esquerda),
    ex : 8 pora 08:07:06 onde 17 pora 17:15),

-   #heure12# : Hora atual no formato de 12 horas (sem zeros à esquerda),
    ex : 8 pora 08:07:06),

-   #minute# : Minuto atual (sem zeros à esquerda, ex : 7 pora
    08:07:06),

-   #jour# : Dia atual (sem zeros à esquerda, ex : 6 pora
    07/06/2017),

-   #mois# : Mês atual (sem zeros à esquerda, ex : 7 pora
    07/06/2017),

-   #annee# : àno atual,

-   #time# : Hora e minuto atuais (ex : 1715 pora 17h15),

-   #timestamp# : Número de segundos desde 1 de janeiro de 1970,

-   #date# : Dia e mês. àtenção, o primeiro número é o mês.
    (ex : 1215 pora 15 de dezembro),

-   #semaine# : Número da semana (ex : 51),

-   #sjour# : Nome do dia da semana (ex : Sábado),

-   #njour# : Número do dia de 0 (domingo) a 6 (sábado),

-   #smois# : Nome do mês (ex : Janeiro),

-   #IP# : IP Eunterno da Jeedom,

-   #hostname# : Nome da máquina Jeedom,

-   #trigger# : Talvez o Nomee do comando que Eunicionde o cenário, 'api', se o lançamento foEu Euniciado pela àPI, 'schedule', se foEu Euniciado pela programação, 'user', se Euniciado manualmente

Você também possuEu as seguintes tags adicionais se o seu script tiver sido
desencadeado por uma Eunteração :

-   #query# : Interação que aciononde o cenário,

-   #profil# : perfil do usuário que Eunicionde o cenário
    (pode estar vazio).

> **Important**
>
> Quando um cenário é acionado por uma Eunteração, é
> necessariamente executado no modo rápido.

Funções de cálculo
-----------------------

Várias funções estão disponíveis pora o equipamento :

-   average (order, period) and averageBetween (order, Começo, end)
    : Dê a média do pedido ao longo do período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde
    entre os 2 terminais necessários (no formato Ymd H:i:s onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   min (ordem, período) e minBetween (ordem, Eunício, fim) :
    Dê o pedido mínimo durante o período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde
    entre os 2 terminais necessários (no formato Ymd H:i:s onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   max (ordem, período) e maxBetween (ordem, Eunício, fim) :
    Forneça o máximo do pedido durante o período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde
    entre os 2 terminais necessários (no formato Ymd H:i:s onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   duração (ordem, valor, período) e
    durationbetween (comando, valor, Eunício, fim) : Indique a duração em
    minutos durante os quais o equipamento teve o valor selecionado no
    período (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde
    entre os 2 terminais necessários (no formato Ymd H:i:s onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   estatísticas (ordem, cálculo, período) e
    statisticsBetween (comando, cálculo, Eunício, fim) : Dê o resultado
    cálculos estatísticos diferentes (soma, contagem, padrão,
    variação, média, mín. e máx.) durante o período
    (período = [mês, dia, hora, min] onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde
    entre os 2 terminais necessários (no formato Ymd H:i:s onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   tendência (ordem, período, limite) : Dá a tendência de
    ordem durante o período (período = [mês, dia, hora, min] onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   stateDuration (comando) : Dá duração em segundos
    desde a última mudança de valor. Retorna -1 se nenhum
    o histórico não existe onde se o valor não existe no histórico.
    Retorna -2 se o pedido não estiver registrado.

-   lastChangeStateDuration (comando, valor) : Indique a duração em
    segundos desde a última mudança de estado pora o valor passado
    como porâmetro. Retorna -1 se nenhum
    o histórico não existe onde se o valor não existe no histórico.
    Retorna -2 se o pedido não estiver registrado

-   lastStateDuration (comando, valor) : Dá duração em segundos
    durante o qual o equipamento teve recentemente o valor escolhido.
    Retorna -1 se não houver histórico onde se o valor não existir no histórico.
    Retorna -2 se o pedido não estiver registrado

-   stateChanges (ordem, [valor], período) e
    stateChangesBetween (comando, [valor], Eunício, fim) : Dê o
    número de alterações de estado (para um determinado valor, se Eundicado,
    onde no total, de ondetra forma) durante o período (período = [mês, dia, hora, min] onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde
    entre os 2 terminais necessários (no formato Ymd H:i:s onde
    [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   lastBetween (comando, Eunício, fim) : Retorna o último valor
    registrado pora o equipamento entre os 2 terminais necessários (sob o
    formulário Ymd H:i:s onde [expressão
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   variável (variável, padrão) : Obtenha o valor de um
    variável onde o valor padrão desejado :

-   cenário : Retorna o status do cenário. 1 em andamento, 0
    se porado e -1 se desativado, -2 se o cenário não existir e -3
    se o estado não for consistente. Para ter o Nomee "humano" do cenário, você pode usar o botão dedicado à direita da pesquisa de cenário.

-   lastScenarioExecution (cenário) : Dá duração em segundos
    desde o lançamento do último cenário :

-   collectDate (cmd, [formato]) : Retorna a data dos últimos dados
    pora o comando fornecido como porâmetro, o segundo porâmetro opcional
    permite especificar o formato de retorno (detalhes
    [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1
    significa que o pedido não pode ser encontrado e -2 que o pedido não é
    nenhum tipo de Eunformação

-   valueDate (cmd, [formato]) : Retorna a data dos últimos dados
    pora o comando fornecido como porâmetro, o segundo porâmetro opcional
    permite especificar o formato de retorno (detalhes
    [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1
    significa que o pedido não pode ser encontrado e -2 que o pedido não é
    nenhum tipo de Eunformação

-   eqEnable (equipamento) : Retorna o status do equipamento. -2 se
    o equipamento não pode ser encontrado, 1 se o equipamento estiver ativo e 0 se não estiver
    está Eunativo

-   valor (cmd) : Retorna o valor de um pedido, se não for fornecido automaticamente pelo Jeedom (caso ao armazenar o Nomee do pedido em uma variável)    

-   tag (montag, [padrão]) : Usado pora recuperar o valor de uma tag onde
    o padrão se não existir :

-   Nomee (tipo, comando) : Usado pora recuperar o Nomee do comando,
    equipamento onde objeto. O tipo vale cmd, eqLogic onde
    objeto.

-   lastCommunication (equipamento, [formato]) : Retorna a data da última comunicação
    pora o equipamento dado como porâmetro, o segundo porâmetro opcional
    permite especificar o formato de retorno (detalhes
    [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1
    significa que o equipamento não pode ser encontrado

-   cor_gradient (start_colour, end_colour, min_value, max_value, value) : Retorna uma cor calculada com relação ao valor no Euntervalo cor_Começo / cor_end. O valor deve estar entre min_value e max_value

Os períodos e Euntervalos dessas funções também podem
use com [expressões
PHP](http://php.net/manual/fr/datetime.formats.relative.php) comme por
Exemplo :

-   àgora : agora

-   Hoje : 00:00 hoje (permite, por exemplo, obter
    resultados do dia entre 'Hoje' e 'Agora')

-   Segunda-feira passada : segunda-feira passada às 00:00

-   5 dias atrás : 5 dias atrás

-   Ontem meio-dia : ontem ao meio dia

-   Etc.

AquEu estão exemplos práticos pora entender os valores retornados por
essas diferentes funções :

| Soquete com valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos)    |
|--------------------------------------|--------------------------------------|
| média (captura, período)             | Retorna a média de 0 e 1 (pode  |
|                                      | ser Eunfluenciado pela pesquisa)      |
| averageBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o pedido médio entre 1 de janeiro de 2015 e 15 de janeiro de 2015                         |
| min (captura, período)                 | Retorna 0 : o plugue foEu extinto durante o período              |
| minBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o pedido mínimo entre 1 de janeiro de 2015 e 15 de janeiro de 2015                         |
| max (captura, período)                 | Retorna 1 : o plugue estava bem Euluminado no período              |
| maxBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o máximo do pedido entre 1 de janeiro de 2015 e 15 de janeiro de 2015                         |
| duração (tomada, 1, período)          | Retorna 60 : o plugue ficonde (em 1) por 60 minutos no período                              |
| durationBetween (\# [Salon] [Take] [State] \#, 0, última segunda-feira, agora)   | Retorna a duração em minutos durante os quais o soquete estava desativado desde a última segunda-feira.                |
| estatísticas (captura, contagem, período)    | Retorna 8 : houve 8 escalações no período               |
| tendência (captura, período, 0.1)        | Retorna -1 : tendência descendente    |
| stateDuration (captura)               | Retorna 600 : o plugue está em seu estado atual por 600 segundos (10 minutos)                             |
| lastChangeStateDuration (obtido, 0)   | Retorna 600 : o soquete saiu (mude pora 0) pela última vez há 600 segundos (10 minutos) atrás     |
| lastChangeStateDuration (take, 1)   | Retorna 4200 : a tomada ligada (mude pora 1) pela última vez há 4200 segundos (1h10)                               |
| lastStateDuration (obtido, 0)         | Retorna 600 : o soquete está desligado por 600 segundos (10 minutos)     |
| lastStateDuration (take, 1)         | Retorna 3600 : o soquete foEu ligado pela última vez por 3600 segundos (1h)           |
| stateChanges (tomada, período)        | Retorna 3 : o plugue mudonde de estado 3 vezes durante o período            |
| stateChanges (take, 0, período)      | Retorna 2 : o soquete apagonde (passando pora 0) duas vezes durante o período                              |
| stateChanges (take, 1, period)      | Retorna 1 : o plugue está aceso (mude pora 1) uma vez durante o período                              |
| lastBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, ontem, hoje) | Retorna a última temperatura registrada ontem.                    |
| variável (plop, 10)                  | Retorna o valor da variável plop onde 10 se estiver vazia onde não existir                         |
| cenário (\# [Banheiro] [Leve] [àutomático] \#) | Retorna 1 em andamento, 0 se porado e -1 se desativado, -2 se o cenário não existir e -3 se o estado não for consistente                         |
| lastScenarioExecution (\# [Banheiro] [Leve] [àutomático] \#)   | Retorna 300 se o cenário foEu Euniciado pela última vez há 5 minutos                                  |
| collectDate (\# [Banheiro] [Hidrometria] [Umidade] \#)     | Devoluções 01-01-2015 17:45:12          |
| valueDate (\# [Banheiro] [Hidrometria] [Umidade] \#) | Devoluções 01-01-2015 17:50:12          |
| eqEnable (\# [Nenhum] [Basílica] \#)       | Retorna -2 se o equipamento não for encontrado, 1 se o equipamento estiver ativo e 0 se estiver Eunativo          |
| tag (montag, toto)                   | Retorna o valor de "montag" se existir, caso contrário, retorna o valor "para"                               |
| Nomee (eqLogic, \# [Banheiro] [Hidrometria] [Umidade] \#)     | Retorna Hidrometria                  |

Funções matemáticas
---------------------------

Uma caixa de ferramentas de funções genéricas também pode ser usada pora
realizar conversões onde cálculos :

-   rand (1,10) : Dê um número aleatório de 1 a 10.

-   randText (texto1; texto2; texto ...) : Retorna um dos
    textos aleatoriamente (separar o texto por um;). Não existe
    limite no número de texto.

-   randomColor (mínimo, máximo) : Dá uma cor aleatória entre 2
    terminais (0 => vermelho, 50 => verde, 100 => azul).

-   gatilho (comando) : Usado pora descobrir o gatilho pora o cenário
    onde pora saber se é o pedido feito como um porâmetro que possui
    desencadeonde o cenário.

-   gatilhoValue (comando) : Usado pora descobrir o valor de
    gatilho de cenário.

-   round (valor, [decimal]) : àrredondar acima, [decimal]
    número de casas decimais após o ponto decimal.

-   ímpor (valor) : Permite saber se um número é ímpor onde não.
    Retorna 1 se ímpor 0, caso contrário.

-   mediana (comando1, comando2….commandN) : Retorna a mediana
    valores.

-   Tempo_op (hora, valor) : Permite que você execute operações no prazo,
    com Tempo = Tempo (ex : 1530) e value = value pora adicionar onde adicionar
    subtrair em minutos.

-   `time_between (hora, Eunício, fim)` : Permite testar se é uma hora
    entre dois valores com `Tempo = Tempo` (ex : 1530), `Começo = Tempo`,` end = Tempo`.
    Os valores Eunicial e final podem chegar à meia-noite.

-   `time_diff (data1, data1 [, formato])` : Usado pora descobrir a diferença entre duas datas (as datas devem estar no formato àAAà / MM / DD HH:MM:SS).
    Por padrão (se você não colocar nada no formato), o método retornará o número total de dias. Você pode perguntar em segundos (s), minutos (m), horas (h). Exemplo em segundos `time_diff (02-02 2018 14:55:00,2018-02-25 14:55:00,s)`

-   `formatTime (time)` : Formata o retorno de uma cadeia
    `# tempo #`.

-   andar (hora / 60) : Converte de segundos pora minutos onde
    minutos a horas (piso (tempo / 3600) por segundos
    em horas)

E exemplos práticos :


| Exemplo de função                  | Resultado retornado                    |
|--------------------------------------|--------------------------------------|
| randText (é # [sala] [olho] [temperatura] #; à temperatura é # [sala] [olho] [temperatura] #; àtualmente, temos # [sala] [olho] [temperatura] #) | a função retornará um desses textos aleatoriamente a cada execução.                           |
| randomColor (40,60)                 | Retorna uma cor aleatória próxima ao verde.   
| gatilho (# [Banheiro] [Hidrometria] [Umidade] #)   | 1 se estiver bom \# \ [Banheiro \] \ [Hidrometria \] \ [Umidade \] \# que Eunicionde o cenário caso contrário 0  |
| gatilhoValue (# [Banheiro] [Hidrometria] [Umidade] #) | 80 se a hidrometria de \# \ [Banheiro \] \ [Hidrometria \] \ [Umidade \] \# for 80%.                         |
| redondo (# [Banheiro] [Hidrometria] [Umidade] # / 10) | Retorna 9 se a porcentagem de umidade e 85                     |
| ímpor (3)                             | Retorna 1                            |
| mediana (15,25,20)                   | Retorna 20                           |
| Tempo_op (# Tempo #, -90)               | se forem 16h50, retorne : 1 650 - 1 130 = 1520                          |
| formatTime (1650)                   | Retorna 16:50                        |
| de piso (130/60)                      | Retorna 2 (minutos se 130s onde horas se 130m)                      |

Pedidos específicos
=========================

Além dos comandos de automação residencial, você tem acesso às seguintes ações :

-   **Pause** (dormir) : Pausa x segundo (s).

-   **variable** (variável) : Criação / modificação de uma variável onde valor
    de uma variável.

-   **Remover variável** (delete_variable) : Permite excluir uma variável

-   **Cenas** (cenário) : Permite controlar cenários. à porte de tags
    permite enviar tags pora o cenário, ex : montag = 2 (tenha cuidado lá
    use apenas letras de a a z. Sem letras maiúsculas, sem
    acentos e sem caracteres especiais). Recebemos a tag no
    cenário de destino com a função tag (montag). O comando "Rese SI" permite redefinir o status de "SI" (esse status é usado pora a não repetição das ações de um "SI" se você passar pela segunda vez consecutiva)

-   **Stop** (parar) : Pára o script.

-   **Attendre** (espera) : àguarde até que a condição seja válida
    (máximo de 2h), o tempo limite está em segundo (s).

-   **VaEu o projeto** (gotodesign) : Mude o design exibido em todos
    navegadores por design solicitado.

-   **Adicionar um registro** (log) : Permite adicionar uma mensagem no Log.

-   **Criar mensagem** (mensagem) : àdicione uma mensagem no centro
    de mensagens.

-   **Activar / Desactivar Hide / Show equipamentos** (equipamento) : Deixa
    modificar as propriedades de um dispositivo
    visível / Eunvisível, ativo / Eunativo.

-   **Aplicar** (pergunte) : Permite que você diga ao Jeedom pora perguntar
    uma pergunta pora o usuário. à resposta é armazenada em um
    variável, então apenas teste seu valor. Por enquanto,
    apenas plugins sms e slack são compatíveis. Tenha cuidado, Eusso
    função está bloqueando. Enquanto não houver resposta onde o
    Tempoout não for atingido, o cenário aguarda.

-   **Pare Jeedom** (jeedom_poweroff) : Peça ao Jeedom pora desligar.

-   **Reiniciar o Jeedom** (jeedom_reboot) : Peça ao Jeedom pora reiniciar.

-   **Retornar um texto / um dado** (cenário_retorno) : Retorna um texto onde um valor
    pora uma Eunteração, por exemplo.

-   **ícone** (ícone) : Permite alterar o ícone de representação do cenário.

-   **Alerte** (alerta) : Permite exibir uma pequena mensagem de alerta em todos
    navegadores que têm uma página Jeedom aberta. Você pode
    mais, escolha 4 níveis de alerta.

-   **Pop-up** (pop-up) : Permite exibir um pop-up que deve ser absolutamente
    validado em todos os navegadores que possuem uma página jeedom aberta.

-   **Rapport** (relatório) : Exportar uma visualização em formato (PDF, PNG, JPEG
    onde SVG) e envie-o através de um comando de tipo de mensagem.
    Observe que, se seu acesso à Interne estiver em HTTPS não assinado, Eusso
    funcionalidade não funcionará. HTTP onde HTTPS assinado é necessário.

-   **Excluir Blocoo IN / à agendado** (remove_inat) : Permite excluir o
    programação de todos os Blocoos IN e à do cenário.

-   **Evento** (evento) : Permite Eunserir um valor em um comando de tipo de Eunformação arbitrariamente

-   **Tag** (tag) : Permite adicionar / modificar uma tag (a tag existe apenas durante a execução atual do cenário, diferente das variáveis que sobrevivem ao final do cenário)

Modelo cenário
====================

Essa funcionalidade permite transformar um cenário em um modelo pora
por exemplo, aplique-o em ondetro Jeedom onde compartilhe-o no
Mercado. É também a portir daí que você pode recuperar um cenário
do mercado.

![scenario15](../images/scenario15.JPG)

Você verá esta janela :

![scenario16](../images/scenario16.JPG)

à portir daí, você tem a possibilidade :

-   Envie um modelo pora o Jeedom (arquivo JSON antecipadamente
    recuperado),

-   Consulte a lista de cenários disponíveis no mercado,

-   Crie um modelo a portir do cenário atual (não se esqueça de
    dê um Nomee),

-   Para consultar os modelos atualmente presentes no seu Jeedom.

Ao clicar em um modelo, você obtém :

![scenario17](../images/scenario17.JPG)

No topo você pode :

-   **Partager** : Compartilhe o modelo no mercado,

-   **Supprimer** : Excluir modelo,

-   **Baixar** : recuperar o modelo como um arquivo JSON
    pora enviá-lo de volta pora ondetro Jeedom, por exemplo.

Abaixo, você tem a porte pora aplicar seu modelo a
cenário atual.

Desde de um Jeedom pora ondetro onde de uma Eunstalação pora ondetra,
os pedidos podem ser diferentes, Jeedom solicita a
correspondência de ordens entre os presentes durante a criação
do modelo e os presentes em casa. Você só precisa preencher o
as ordens de correspondência se aplicam.

Adição da função php
====================

> **IMPORTANT**
>
> à adição da função PHP é reservada pora usuários avançados. O menor erro pode travar o seu Jeedom

## Configurar

Vá pora a configuração do Jeedom, então OS / DB e Eunicie o editor de arquivos.

Vá pora a pasta de dados, php e clique no arquivo user.function.class.php.

É nesta classe que você deve adicionar suas funções, você encontrará um exemplo de função básica.

> **IMPORTANT**
>
> Se você tiver um problema, sempre poderá voltar ao arquivo original e copiar o conteúdo de user.function.class.sample.php em user.function.class.php
