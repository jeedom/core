# Cenas
**Ferramentas → Cenários**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Cérebro real da automação residencial, os cenários permitem interagir de uma maneira com o mundo real *esperto*.

## Gestion

Você encontrará a lista de cenários do seu Jeedom, bem como funcionalidades para gerenciá-los da melhor maneira possível :

- **Adicionar** : Crie um cenário. O procedimento é descrito no próximo capítulo.
- **Desativar cenários** : Desativa todos os cenários. Raramente usado e conscientemente, já que nenhum cenário será executado.
- **Visão global** : Permite que você tenha uma visão geral de todos os cenários. Você pode alterar os valores **Ativos**, **Visivél**, **Multi lançamento**, **Sincronicamente**, **Log** e **Cronograma** (esses parâmetros são descritos no capítulo a seguir). Você também pode acessar os logs para cada cenário e iniciá-los individualmente.

## Meus scripts

Nesta seção, você encontrará o **lista de cenários** que você criou. Eles são classificados de acordo com seus **Grupo**, possivelmente definido para cada um deles. Cada cenário é exibido com seus **Nome** e o dele **Objeto pai**. O **cenários esmaecidos** são os que estão desativados.

> **Dica**
>
> Você pode abrir um cenário fazendo :
> - Clique em um deles.
> - Ctrl Clic ou Clic Center para abri-lo em uma nova guia do navegador.

Você tem um mecanismo de pesquisa para filtrar a exibição de cenários. A tecla Escape cancela a pesquisa.
À direita do campo de pesquisa, três botões encontrados em vários lugares no Jeedom:
- A cruz para cancelar a pesquisa.
- A pasta aberta para desdobrar todos os painéis e exibir todos os cenários.
- A pasta fechada para dobrar todos os painéis.

Uma vez na configuração de um cenário, você tem um menu contextual com o botão direito do mouse nas guias do cenário. Você também pode usar um Ctrl Click ou Click Center para abrir diretamente outro cenário em uma nova guia do navegador.

## Criação | Editando um Cenário

Depois de clicar em **Adicionar**, você deve escolher o nome do seu cenário. Você é redirecionado para a página de seus parâmetros gerais.
Antes disso, no topo da página, existem algumas funções úteis para gerenciar esse cenário :

- **ID** : Ao lado da palavra **Geral**, este é o identificador de cenário.
- **Estado** : *Preso* onde *Contínuo*, indica o estado atual do cenário.
- **Estado anterior / seguinte** : Cancelar / refazer uma ação.
- **Adicionar bloco** : Permite adicionar um bloco do tipo desejado ao cenário (veja abaixo).
- **Log** : Exibe os logs do cenário.
- **Duplicar** : Copie o cenário para criar um novo com outro nome.
- **Conexões** : Permite visualizar o gráfico dos elementos relacionados ao cenário.
- **Edição de texto** : Exibe uma janela que permite editar o cenário na forma de texto / json. Não esqueça de salvar.
- **Exportação** : Permite obter uma versão em texto puro do cenário.
- **Modelo** : Permite acessar os modelos e aplicar um ao cenário no mercado. (explicado na parte inferior da página).
- **Pesquisa** : Desdobra um campo de pesquisa para pesquisar no cenário. Esta pesquisa desdobra os blocos recolhidos, se necessário, e os dobra novamente após a pesquisa.
- **Realizar** : Permite iniciar o cenário manualmente (independentemente dos gatilhos). Salve antecipadamente para levar em conta as modificações.
- **Remover** : Excluir cenário.
- **Salvar** : Salve as alterações feitas.

> **Dicas**
>
> Duas ferramentas também serão inestimáveis para você na configuração de cenários :
    > - As variáveis visíveis em **Ferramentas → Variáveis**
    > - O testador de expressão, acessível por **Ferramentas → Testador de expressão**
>
> Um **Ctrl Clique no botão executar** permite salvar, executar e exibir diretamente o log do cenário (se o nível do log não for Nenhum)).

## Guia Geral

Na aba **Geral**, encontramos os principais parâmetros do cenário :

- **Nome do cenário** : O nome do seu cenário.
- **Display Name** : O nome usado para sua exibição. Opcional, se não for concluído, o nome do cenário é usado.
- **Grupo** : Permite organizar os cenários, classificando-os em grupos (visíveis na página de cenários e em seus menus contextuais).
- **Ativos** : Ativar o cenário. Se não estiver ativo, ele não será executado pelo Jeedom, independentemente do modo de disparo.
- **Visivél** : Usado para tornar o cenário visível (Painel).
- **Objeto pai** : Atribuição a um objeto pai. Será então visível ou não, de acordo com este pai.
- **Tempo limite em segundos (0 = ilimitado)** : O tempo máximo de execução permitido para este cenário. Além desse tempo, a execução do cenário é interrompida.
- **Multi lançamento** : Marque esta caixa se desejar que o cenário possa ser iniciado várias vezes ao mesmo tempo.
>**IMPORTANTE**
>
>O multi-lançamento funciona a partir do segundo, ou seja, se você tiver 2 lançamentos no mesmo segundo sem a caixa marcada, ainda haverá 2 lançamentos do cenário (quando não deve). Da mesma forma, durante vários lançamentos no mesmo segundo, é possível que alguns lançamentos percam as tags. Conclusão: você DEVE ABSOLUTAMENTE evitar vários lançamentos nos mesmos segundos.
- **Sincronicamente** : Inicie o cenário no segmento atual em vez de um segmento dedicado. Aumenta a velocidade na qual o cenário é iniciado, mas pode tornar o sistema instável.
- **Log** : O tipo de log desejado para o cenário. Você pode cortar o log do cenário ou, pelo contrário, fazê-lo aparecer em Análise → Tempo real.
- **Cronograma** : Mantenha um acompanhamento do cenário na linha do tempo (consulte Histórico doc).
- **ícone** : Permite escolher um ícone para o cenário em vez do ícone padrão.
- **Descrição** : Permite que você escreva um pequeno texto para descrever seu cenário.
- **Modo de cenário** : O cenário pode ser programado, acionado ou ambos. Você terá a opção de indicar o (s) gatilho (s) (máximo de 15 gatilhos) e a (s) programação (ões)).

> **Dica**
>
> Agora as condições podem ser inseridas no modo acionado. Por exemplo : ``#[Garage][Open Garage][Ouverture]# == 1``
> Atenção : você pode ter no máximo 28 gatilhos / programação para um cenário.

> **Modo de ponta programado**
>
> O modo agendado usa sintaxe **Cron**. Por exemplo, você pode executar um cenário a cada 20 minutos com  ``*/20 * * * *``, ou às 05:00 para resolver uma infinidade de coisas para o dia com ``0 5 * * *``. O ? à direita de um programa permite configurá-lo sem ser um especialista em sintaxe do Cron.

## Guia Cenário

É aqui que você criará seu cenário. Depois de criar o cenário, seu conteúdo está vazio, então ele fará ... nada. Você tem que começar com **Adicionar bloco**, com o botão à direita. Após a criação de um bloco, você pode adicionar outro **Bloco** ou um **Ação**.

Para maior comodidade e não ter que reordenar constantemente os blocos no cenário, a adição de um bloco é feita após o campo em que o cursor do mouse está localizado.
*Por exemplo, se você tiver dez blocos e clicar na condição SE do primeiro bloco, o bloco adicionado será adicionado após o bloco, no mesmo nível. Se nenhum campo estiver ativo, ele será adicionado no final do cenário.*

> **Dica**
>
> Em condições e ações, é melhor favorecer aspas simples (') em vez de aspas duplas (").

> **Dica**
>
> Ctrl Shift Z ou Ctrl Shift Y permite que você'**Cancelar** ou refazer uma modificação (adicionando ação, bloco...).

## Blocos

Aqui estão os diferentes tipos de blocos disponíveis :

- **If / Then / Ou** : Permite que você execute ações sob condição (se isso, então aquele).
- **Ação** : Permite iniciar ações simples sem nenhuma condição.
- **Laço** : Permite que ações sejam executadas repetidamente de 1 a um número definido (ou mesmo o valor de um sensor ou um número aleatório…).
- **Dans** : Permite iniciar uma ação em X minuto (s) (0 é um valor possível). A peculiaridade é que as ações são iniciadas em segundo plano, para que não bloqueiem o restante do cenário. Portanto, é um bloco sem bloqueio.
- **à** : Permite que o Jeedom inicie as ações do bloco em um determinado momento (no formato hhmm). Este bloco é sem bloqueio. Ex : 0030 para 00:30 ou 0146 para 1h46 e 1050 para 10h50.
- **CÓDIGO** : Permite escrever diretamente no código PHP (requer certo conhecimento e pode ser arriscado, mas permite que você não tenha restrições).
- **COMMENTAIRE** : Permite adicionar comentários ao seu cenário.

Cada bloco tem suas opções para lidar melhor com eles :

- À esquerda :
    - A seta bidirecional permite mover um bloco ou uma ação para reordená-los no cenário.
    - O olho reduz um bloqueio (*colapso*) para reduzir seu impacto visual. Ctrl Clique nos olhos para reduzi-los ou exibi-los todos.
    - A caixa de seleção permite desativar completamente o bloco sem excluí-lo. Portanto, não será executado.

- À direita :
    - O ícone Copiar permite copiar o bloco para fazer uma cópia em outro lugar. Ctrl Clique no ícone corta o bloco (copie e exclua).
    - O ícone Colar permite colar uma cópia do bloco copiado anteriormente após o bloco no qual você usa esta função.  Ctrl Clique no ícone substitui o bloco pelo bloco copiado.
    - O ícone - permite excluir o bloco, com uma solicitação de confirmação. Ctrl Clique exclui o bloco sem confirmação.

### Se / Então / Caso contrário, bloqueia | Laço | Dans | A

Pelas condições, o Jeedom tenta torná-las possíveis o máximo possível em linguagem natural, mantendo-se flexível.
> NÃO use [] em testes de condição, apenas parênteses () são possíveis.

Três botões estão disponíveis à direita deste tipo de bloco para selecionar um item para testar :

- **Ordem de pesquisa** : Permite procurar um pedido em todos os disponíveis no Jeedom. Depois que o pedido é encontrado, o Jeedom abre uma janela para perguntar qual teste você deseja executar nele. Se você escolher **Não ponha nada**, Jeedom adicionará o pedido sem comparação. Você também pode escolher **e** onde **onde** Na frente **Em seguida** para encadear testes em diferentes equipamentos.
- **Pesquisa cenário** : Permite procurar um cenário para testar.
- **Procure equipamento** : O mesmo para equipamentos.

> **NOTA**
>
> Em blocos do tipo Se / Então / Caso contrário, as setas circulares à esquerda do campo de condição permitem ativar ou não a repetição de ações se a avaliação da condição fornecer o mesmo resultado que na avaliação anterior.

> **Dica**
>
> Há uma lista de tags que permitem acessar variáveis do cenário ou de outro, ou pela hora, data, número aleatório,… Veja abaixo os capítulos sobre comandos e tags.

Depois que a condição estiver concluída, você deve usar o botão "adicionar" à esquerda para adicionar um novo **Bloco** ou um **Ação** no bloco atual.


### Código de bloco

O bloco Code permite executar código php. Portanto, é muito poderoso, mas requer um bom conhecimento da linguagem php.

#### Acesso a controles (sensores e atuadores)

-  ``cmd::byString($string);`` : Retorna o objeto de comando correspondente.
    -   ``$string``: Link para o pedido desejado : ``#[objet][Equipamento][commande]#`` (ex : ``#[Appartement][Alarme][Ativos]#``)
-  ``cmd::byId($id);`` : Retorna o objeto de comando correspondente.
    -  ``$id`` : ID do pedido.
-  ``$cmd->execCmd($options = null);`` : Execute o comando e retorne o resultado.
    - ``$options`` : Opções para a execução do comando (podem ser específicas para o plugin). Opções básicas (subtipo de comando) :
        -  ``message`` : ``$option = array('title' => 'titre du Mensagem , 'message' => 'Mon message');``
        -  ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
        -  ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Acesso ao log

-  ``log::add('filename','level','message');``
    - ``filename`` : Nome do arquivo de log.
    - ``level`` : [depuração], [informações], [erro], [evento].
    - ``message`` : Mensagem para escrever nos logs.

#### Acesso ao cenário

- ``$scenario->getName();`` : Retorna o nome do cenário atual.
- ``$scenario->getGroup();`` : Retorna o grupo de cenários.
- ``$scenario->getIsActive();`` : Retorna o estado do cenário.
- ``$scenario->setIsActive($active);`` : Permite ativar ou não o cenário.
    - ``$active`` : 1 ativo, 0 inativo.
- ``$scenario->setOnGoing($onGoing);`` : Vamos dizer se o cenário está em execução ou não.
    - ``$onGoing => 1`` : 1 em andamento, 0 parado.
- ``$scenario->save();`` : Salvar alterações.
- ``$scenario->setData($key, $value);`` : Salvar um dado (variável).
    - ``$key`` : chave de valor (int ou string).
    - ``$value`` : valor para armazenar (``int``, ``string``, ``array`` onde ``object``).
- ``$scenario->getData($key);`` : Obter dados (variável).
    - ``$key => 1`` : chave de valor (int ou string).
- ``$scenario->removeData($key);`` : Excluir dados.
- ``$scenario->setLog($message);`` : Escreva uma mensagem no log de script.
- ``$scenario->persistLog();`` : Forçar a gravação do log (caso contrário, ele será gravado apenas no final do cenário). Cuidado, isso pode atrasar um pouco o cenário.

> **Dica**
>
> Adição de uma função de pesquisa no bloco Código : Pesquisa : Ctrl + F e Enter, próximo resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G

[Cenas : Pequenos códigos com amigos](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/CodesScenario/)

### Bloco de comentários

O bloco de comentários age de maneira diferente quando está oculto. Seus botões à esquerda desaparecem, assim como o título do bloco, e reaparecem ao passar o mouse. Da mesma forma, a primeira linha do comentário é exibida em negrito.
Isso permite que esse bloco seja usado como uma separação puramente visual dentro do cenário.

### Acções

As ações adicionadas aos blocos têm várias opções :

- Uma caixa **ativado** para que esse comando seja levado em consideração no cenário.
- Uma caixa **paralelo** para que este comando seja iniciado em paralelo (ao mesmo tempo) com os outros comandos também selecionados.
- Um **seta dupla vertical** para mover a ação. Basta arrastar e soltar a partir daí.
- Um botão para **Remover** a ação.
- Um botão para ações específicas, sempre que a descrição (em foco) dessa ação.
- Um botão para procurar um comando de ação.

> **Dica**
>
> Dependendo do comando selecionado, você pode ver diferentes campos adicionais exibidos.

## Possíveis substituições

### Triggers

Existem gatilhos específicos (além dos fornecidos pelos comandos) :

- ``#start#`` : Acionado no (re) início do Jeedom.
- ``#begin_backup#`` : Evento enviado no início de um backup.
- ``#end_backup#`` : Evento enviado no final de um backup.
- ``#begin_update#`` : Evento enviado no início de uma atualização.
- ``#end_update#`` : Evento enviado no final de uma atualização.
- ``#begin_restore#`` : Evento enviado no início de uma restauração.
- ``#end_restore#`` : Evento enviado no final de uma restauração.
- ``#user_connect#`` : Login do usuário

Você também pode disparar um cenário quando uma variável é atualizada, colocando : #variable(nom_variable)# ou usando a API HTTP descrita [aqui](https://doc.jeedom.com/pt_PT/core/4.1/api_http).

### Operadores de comparação e links entre condições

Você pode usar qualquer um dos seguintes símbolos para comparações em condições :

- ``==`` : Igual a.
- ``>`` : Estritamente maior que.
- ``>=`` : Maior ou igual a.
- ``<`` : Estritamente menor que.
- ``<=`` : Menor ou igual a.
- ``!=`` : Diferente de, não é igual a.
- ``matches`` : Contém. Ex : ``[Salle de bain][Hydrometrie][etat] matches "/humide/"``.
- ``not ( …​ matches …​)`` : Não contém. Ex :  ``not([Salle de bain][Hydrometrie][etat] matches "/humide/")``.

Você pode combinar qualquer comparação com os seguintes operadores :

- ``&&`` / ``ET`` / ``et`` / ``AND`` / ``and`` : et,
- ``||`` / ``OU`` / ``ou`` / ``OR`` / ``or`` : ou,
- ``^`` / ``XOR`` / ``xor`` : ou exclusivo.

### Tags

Uma tag é substituída durante a execução do cenário por seu valor. Você pode usar as seguintes tags :

> **Dica**
>
> Para exibir os zeros à esquerda, use a função Data (). Veja [aqui](http://php.net/manual/fr/function.date.php).

- ``#seconde#`` : Segundo atual (sem zeros à esquerda, ex : 6 para 08:07:06).
- ``#hour#`` : Hora atual no formato 24h (sem zeros à esquerda)). Ex : 8 para 08:07:06 ou 17 para 17:15.
- ``#hour12#`` : Hora atual no formato de 12 horas (sem zeros à esquerda)). Ex : 8 para 08:07:06.
- ``#minute#`` : Minuto atual (sem zeros à esquerda)). Ex : 7 para 08:07:06.
- ``#day#`` : Dia atual (sem zeros à esquerda)). Ex : 6 em 07/07/2017.
- ``#month#`` : Mês atual (sem zeros à esquerda)). Ex : 7 em 07/07/2017.
- ``#year#`` : Ano atual.
- ``#time#`` : Hora e minuto atuais. Ex : 1715 para 17h15.
- ``#timestamp#`` : Número de segundos desde 1 de janeiro de 1970.
- ``#date#`` : Dia e mês. Atenção, o primeiro número é o mês. Ex : 1215 para 15 de dezembro.
- ``#week#`` : Número da semana.
- ``#sday#`` : Nome do dia da semana. Ex : Sábado.
- ``#nday#`` : Número do dia de 0 (domingo) a 6 (sábado).
- ``#smonth#`` : Nome do mês. Ex : Janeiro.
- ``#IP#`` : IP interno da Jeedom.
- ``#hostname#`` : Nome da máquina Jeedom.
- ``#trigger#`` (obsoleto, melhor usar ``trigger()``) : Talvez o nome do comando que iniciou o cenário :
    - ``api`` se o lançamento foi acionado pela API,
    - ``schedule`` se foi iniciado pela programação,
    - ``user`` se foi iniciado manualmente,
    - ``start`` para um lançamento na inicialização do Jeedom.
- ``#trigger_value#`` (descontinuado, melhor usar triggerValue()) : Para o valor do comando que acionou o cenário

Você também tem as seguintes tags adicionais se seu cenário foi acionado por uma interação :

- #query# : Interação que acionou o cenário.
- #profil# : Perfil do usuário que iniciou o cenário (pode estar vazio).

> **IMPORTANTE**
>
> Quando um cenário é acionado por uma interação, é necessariamente executado no modo rápido. Portanto, no segmento de interação e não em um segmento separado.

### Funções de cálculo

Várias funções estão disponíveis para o equipamento :

- ``average(commande,période)`` e ``averageBetween(commande,start,end)`` : Dê a média do pedido ao longo do período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre os 2 terminais necessários (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``min(commande,période)`` e ``minBetween(commande,start,end)`` : Dê o pedido mínimo durante o período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre os 2 terminais necessários (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``max(commande,période)`` e ``maxBetween(commande,start,end)`` : Forneça o máximo do pedido durante o período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre os 2 terminais necessários (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``duration(commande, valeur, période)`` e ``durationbetween(commande,valeur,start,end)`` : Indique a duração em minutos durante os quais o equipamento teve o valor escolhido durante o período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre os 2 terminais necessários (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``statistics(commande,calcul,période)`` e ``statisticsBetween(commande,calcul,start,end)`` : Forneça o resultado de diferentes cálculos estatísticos (soma, contagem, padrão, variação, média, mín., Máx.) Ao longo do período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre os 2 terminais necessários (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``tendance(commande,période,seuil)`` : Dá a tendência do pedido ao longo do período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``stateDuration(commande)`` : Dá a duração em segundos desde a última alteração no valor.
    -1 : Não existe histórico ou valor não existe no histórico.
    -2 : O pedido não está registrado.

- ``lastChangeStateDuration(commande,valeur)`` : Dá a duração em segundos desde a última mudança de estado para o valor passado no parâmetro.
    -1 : Não existe histórico ou valor não existe no histórico.
    -2 O pedido não está registrado

- ``lastStateDuration(commande,valeur)`` : Dá a duração em segundos durante os quais o equipamento teve o valor escolhido pela última vez.
    -1 : Não existe histórico ou valor não existe no histórico.
    -2 : O pedido não está registrado.

- ``age(commande)`` : Dá a idade em segundos do valor do comando (``collecDate``)
    -1 : O comando não existe ou não é do tipo info.

- ``stateChanges(commande,[valeur], période)`` e ``stateChangesBetween(commande, [valeur], start, end)`` : Indique o número de alterações de estado (em direção a um determinado valor, se indicado, ou no total, se não) durante o período (período=[mês, dia, hora, min] ou [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre os 2 terminais necessários (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``lastBetween(commande,start,end)`` : Fornece o último valor registrado para o equipamento entre os 2 terminais solicitados (no formato Ymd H:i:s ou [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``variable(mavariable,valeur par défaut)`` : Recupera o valor de uma variável ou o valor desejado por padrão.

- ``scenario(scenario)`` : Retorna o status do cenário.
    1 : Contínuo,
    0 : Preso,
    -1 : Inválido,
    -2 : O cenário não existe,
    -3 : Estado não é consistente.
    Para ter o nome "humano" do cenário, você pode usar o botão dedicado à direita da pesquisa de cenário.

- ``lastScenarioExecution(scenario)`` : Dá a duração em segundos desde o último lançamento do cenário.
    0 : O cenário não existe

- ``collectDate(cmd,[format])`` : Retorna a data dos últimos dados para o comando dado no parâmetro, o 2º parâmetro opcional permite especificar o formato de retorno (detalhes [aqui](http://php.net/manual/fr/function.date.php)).
    -1 : Não foi possível encontrar o comando,
    -2 : O comando não é do tipo info.

- ``valueDate(cmd,[format])`` : Retorna a data dos últimos dados para o comando dado no parâmetro, o 2º parâmetro opcional permite especificar o formato de retorno (detalhes [aqui](http://php.net/manual/fr/function.date.php)).
    -1 : Não foi possível encontrar o comando,
    -2 : O comando não é do tipo info.

- ``eqEnable(equipement)`` : Retorna o status do equipamento.
    -2 : O equipamento não pode ser encontrado,
    1 : O equipamento está ativo,
    0 : O equipamento está inativo.

- ``value(cmd)`` : Retorna o valor de um pedido se ele não for fornecido automaticamente pelo Jeedom (caso ao armazenar o nome do pedido em uma variável)

- ``tag(montag,[defaut])`` : Usado para recuperar o valor de uma tag ou o valor padrão, se ele não existir.

- ``name(type,commande)`` : Usado para recuperar o nome do pedido, equipamento ou objeto. Tipo : cmd, eqLogic ou objeto.

- ``lastCommunication(equipment,[format])`` : Retorna a data da última comunicação para o equipamento dado como parâmetro; o 2º parâmetro opcional permite especificar o formato de retorno (detalhes [aqui](http://php.net/manual/fr/function.date.php)). Um retorno de -1 significa que o equipamento não pode ser encontrado.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Retorna uma cor calculada com relação ao valor no intervalo color_start / color_end. O valor deve estar entre min_value e max_value.

Os períodos e intervalos dessas funções também podem ser usados com [Expressões PHP](http://php.net/manual/fr/datetime.formats.relative.php) como por exemplo :

- ``Now`` : agora.
- ``Today`` : 00:00 hoje (permite, por exemplo, obter resultados para o dia se entre ``Today`` e ``Now``).
- ``Last Monday`` : segunda-feira passada às 00:00.
- ``5 days ago`` : 5 dias atrás.
- ``Yesterday noon`` : ontem ao meio dia.
- Etc.

Aqui estão exemplos práticos para entender os valores retornados por essas diferentes funções :

| Soquete com valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos))    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Retorna a média de 0 e 1 (pode  |
|                                      | ser influenciado por pesquisas)      |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Retorna o pedido médio entre 1 de janeiro de 2015 e 15 de janeiro de 2015                       |
| ``min(prise,période)``                 | Retorna 0 : o plugue foi extinto durante o período              |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Retorna o pedido mínimo entre 1 de janeiro de 2015 e 15 de janeiro de 2015                       |
| ``max(prise,période)``                 | Retorna 1 : o plugue estava bem iluminado no período              |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Retorna o máximo do pedido entre 1 de janeiro de 2015 e 15 de janeiro de 2015                       |
| ``duration(prise,1,période)``          | Retorna 60 : o plugue ficou (em 1) por 60 minutos no período                              |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Retorna a duração em minutos durante os quais o soquete estava desativado desde a última segunda-feira.                |
| ``statistics(prise,count,période)``    | Retorna 8 : houve 8 escalações no período               |
| ``tendance(prise,période,0.1)``        | Retorna -1 : tendência descendente    |
| ``stateDuration(prise)``               | Retorna 600 : o plugue está em seu estado atual por 600 segundos (10 minutos)                             |
| ``lastChangeStateDuration(prise,0)``   | Retorna 600 : o soquete saiu (mude para 0) pela última vez há 600 segundos (10 minutos)     |
| ``lastChangeStateDuration(prise,1)``   | Retorna 4200 : a tomada ligada (mude para 1) pela última vez há 4200 segundos (1h10)                               |
| ``lastStateDuration(prise,0)``         | Retorna 600 : o soquete está desligado por 600 segundos (10 minutos)     |
| ``lastStateDuration(prise,1)``         | Retorna 3600 : o soquete foi ligado pela última vez por 3600 segundos (1 h)           |
| ``stateChanges(prise,période)``        | Retorna 3 : o plugue mudou de estado 3 vezes durante o período            |
| ``stateChanges(prise,0,période)``      | Retorna 2 : o soquete apagou (passando para 0) duas vezes durante o período                              |
| ``stateChanges(prise,1,période)``      | Retorna 1 : o plugue está aceso (mude para 1) uma vez durante o período                              |
| ``lastBetween(#[Salle de bain][Hydrometrie][Humidité]#,Yesterday,Today)`` | Retorna a última temperatura registrada ontem.                    |
| ``variable(plop,10)``                  | Retorna o valor da variável plop ou 10 se estiver vazia ou não existir                         |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Retorna 1 em andamento, 0 se parado e -1 se desativado, -2 se o cenário não existir e -3 se o estado não for consistente                         |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Retorna 300 se o cenário foi iniciado pela última vez há 5 minutos                                  |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Devoluções 01-01-2015 17:45:12          |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Devoluções 01-01-2015 17:50:12          |
| ``eqEnable(#[Aucun][Basilique]#)``       | Retorna -2 se o equipamento não for encontrado, 1 se o equipamento estiver ativo e 0 se estiver inativo          |
| ``tag(montag,toto)``                   | Retorna o valor de "montag" se existir, caso contrário, retorna o valor "para"                               |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Retorna Hidrometria                  |


### Funções matemáticas

Uma caixa de ferramentas de funções genéricas também pode ser usada para realizar conversões ou cálculos :

- ``rand(1,10)`` : Dê um número aleatório de 1 a 10.
- ``randText(texte1;texte2;texte…​..)`` : Permite retornar um dos textos aleatoriamente (separe os textos por um; ). Não há limite no número de textos.
- ``randomColor(min,max)`` : Dá uma cor aleatória entre 2 limites (0 => vermelho, 50 => verde, 100 => azul).
- ``trigger(commande)`` : Permite descobrir o gatilho para o cenário ou saber se foi o comando passado como um parâmetro que acionou o cenário.
- ``triggerValue(commande)`` : Usado para descobrir o valor do gatilho do cenário.
- ``round(valeur,[decimal])`` : Arredonda acima, número [decimal] de casas decimais após o ponto decimal.
- ``odd(valeur)`` : Permite saber se um número é ímpar ou não. Retorna 1 se ímpar 0, caso contrário.
- ``median(commande1,commande2…​.commandeN)`` : Retorna a mediana dos valores.
- ``avg(commande1,commande2…​.commandeN)`` : Retorna a média dos valores.
- ``time_op(time,value)`` : Permite executar operações dentro do prazo, com time = time (ex : 1530) e value = value para adicionar ou subtrair em minutos.
- ``time_between(time,start,end)`` : Usado para testar se um tempo está entre dois valores com ``time=temps`` (ex : 1530), ``start=temps``, ``end=temps``. Os valores inicial e final podem chegar à meia-noite.
- ``time_diff(date1,date2[,format, round])`` : Usado para descobrir a diferença entre duas datas (as datas devem estar no formato AAAA / MM / DD HH:MM:SS). Por padrão, o método retorna a diferença em dia (s)). Você pode perguntar em segundos (s), minutos (m), horas (h). Exemplo em segundos ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. A diferença é retornada em absoluto, a menos que você especifique ``f`` (``sf``, ``mf``, ``hf``, ``df``). Você também pode usar ``dhms`` quem não retornará exemplo ``7j 2h 5min 46s``. O parâmetro round opcional arredondado para x dígitos após o ponto decimal (2 por padrão). Ex: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Formata o retorno de uma cadeia ``#time#``.
- ``floor(time/60)`` : Converter segundos em minutos ou minutos em horas (``floor(time/3600)`` por segundos a horas).
- ``convertDuration(secondes)`` : Converte segundos em d / h / min / s.

E exemplos práticos :


| Exemplo de função                  | Resultado retornado                    |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | a função retornará um desses textos aleatoriamente a cada execução.                           |
| ``randomColor(40,60)``                 | Retorna uma cor aleatória próxima ao verde.
| ``trigger(#[Salle de bain][Hydrometrie][Humidité]#)``   | 1 se é bom ``#[Salle de bain][Hydrometrie][Humidité]#`` quem iniciou o cenário caso contrário 0  |
| ``triggerValue(#[Salle de bain][Hydrometrie][Humidité]#)`` | 80 se a hidrometria de ``#[Salle de bain][Hydrometrie][Humidité]#`` é 80%.                         |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / 10)`` | Retorna 9 se a porcentagem de umidade e 85                     |
| ``odd(3)``                             | Retorna 1                            |
| ``median(15,25,20)``                   | Retorna 20
| ``avg(10,15,18)``                      | Retorna 14.3                     |
| ``time_op(#time#, -90)``               | se forem 16h50, retorne : 1 650 - 1 130 = 1520                          |
| ``formatTime(1650)``                   | Retorna 16:50                        |
| ``floor(130/60)``                     | Retorna 2 (minutos se 130s ou horas se 130m)                      |
| ``convertDuration(3600)``             | Retorna 1h 0min 0s                      |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Retorna o tempo de ignição em Dias / Horas / minutos do tempo de transição para o estado 1 do módulo desde o 1º dia do mês |


### Pedidos específicos

Além dos comandos de automação residencial, você tem acesso às seguintes ações :

- **Pausa** (sleep) : Pausa de x segundo (s).
- **Variável** (variable) : Criação / modificação de uma variável ou o valor de uma variável.
- **Remover variável** (delete_variable) : Permite excluir uma variável.
- **Cenas** (scenario) : Permite controlar cenários. A parte de tags permite enviar tags para o cenário, ex : montag = 2 (tenha cuidado, use apenas letras de a a z. Sem letras maiúsculas, sem acentos e sem caracteres especiais). Recuperamos a tag no cenário de destino com a função tag (montag). O comando "Redefinir para SI" permite redefinir o status de "SI" (esse status é usado para a não repetição das ações de um "SI" se você passar pela segunda vez consecutiva nele)
- **Pare** (stop) : Pára o script.
- **Esperar** (wait) : Aguarde até que a condição seja válida (máximo de 2h), o tempo limite será em segundos (s).
- **Vai o projeto** (gotodesign) : Alterar o design exibido em todos os navegadores pelo design solicitado.
- **Adicionar um registro** (log) : Permite adicionar uma mensagem no log.
- **Criar mensagem** (message) : Adicionar uma mensagem ao centro de mensagens.
- **Activar / Desactivar Hide / Show equipamentos** (equipement) : Permite modificar as propriedades de equipamento visível / invisível, ativo / inativo.
- **Aplicar** (ask) : Permite indicar a Jeedom que é necessário fazer uma pergunta ao usuário. A resposta é armazenada em uma variável, então você só precisa testar seu valor.
    No momento, apenas plugins sms, slack, telegram e snips são compatíveis, assim como o aplicativo móvel.
    Atenção, esta função está bloqueando. Enquanto não houver resposta ou o tempo limite não for atingido, o cenário aguarda.
- **Stop Jeedom** (jeedom_poweroff) : Peça ao Jeedom para desligar.
- **Retornar um texto / um dado** (scenery_return) : Retorna um texto ou um valor para uma interação por exemplo.
- **ícone** (icon) : Permite alterar o ícone de representação do cenário.
- **Aviso** (alert) : Exibe uma pequena mensagem de alerta em todos os navegadores que têm uma página Jeedom aberta. Além disso, você pode escolher 4 níveis de alerta.
- **Pop-up** (popup) : Permite exibir um pop-up que deve ser absolutamente validado em todos os navegadores que possuem uma página jeedom aberta.
- **Relatório** (report) : Permite exportar uma visualização em formato (PDF, PNG, JPEG ou SVG) e enviá-la usando um comando do tipo mensagem. Observe que, se seu acesso à Internet estiver em HTTPS não assinado, essa funcionalidade não funcionará. HTTP ou HTTPS assinado é necessário.
- **Excluir bloco IN / A agendado** (remove_inat) : Apagar a programação de todos os blocos dentro e A Cenário.
- **Evento** (event) : Permite inserir um valor em um comando de tipo de informação arbitrariamente.
- **Tag** (tag) : Permite adicionar / modificar uma marca (a marca existe apenas durante a execução atual do cenário, diferentemente das variáveis que sobrevivem ao final do cenário).
- **Coloração de ícones do painel** (setColoredIcon) : permite ativar ou não a coloração de ícones no painel.

### Template cenário

Essa funcionalidade permite transformar um cenário em um modelo para, por exemplo, aplicá-lo a outro Jeedom.

Clicando no botão **Modelo** na parte superior da página, você abre a janela de gerenciamento de modelos.

A partir daí, você tem a possibilidade :

- Envie um modelo para Jeedom (arquivo JSON recuperado anteriormente).
- Consulte a lista de cenários disponíveis no mercado.
- Crie um modelo a partir do cenário atual (não esqueça de dar um nome).
- Para consultar os modelos atualmente presentes no seu Jeedom.

Ao clicar em um modelo, você pode :

- **Compartilhe** : Compartilhe o modelo no mercado.
- **Remover** : Excluir modelo.
- **Baixar** : Obtenha o modelo como um arquivo JSON para enviá-lo para outro Jeedom, por exemplo.

Abaixo, você tem a parte para aplicar seu modelo ao cenário atual.

Como, de um Jeedom para outro ou de uma instalação para outro, os comandos podem ser diferentes, o Jeedom solicita a correspondência dos comandos entre os presentes durante a criação do modelo e os presentes em casa. Você só precisa preencher a correspondência dos pedidos e aplicar.

## Adição da função php

> **IMPORTANTE**
>
> A adição da função PHP é reservada para usuários avançados. O menor erro pode ser fatal para o seu Jeedom.

### Configurar

Vá para a configuração do Jeedom, então OS / DB e inicie o editor de arquivos.

Vá para a pasta de dados, php e clique no arquivo user.function.class.php.

Está nisso *Class* que você pode adicionar suas funções, você encontrará um exemplo de função básica.

> **IMPORTANTE**
>
> Se você tiver alguma dúvida, sempre poderá reverter para o arquivo original, copiando o conteúdo de ``user.function.class.sample.php`` Dans ``user.function.class.php``
