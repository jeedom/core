# Cenas
**Ferramentas → Cenários**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Cérebro real da automação residencial, os cenários permitem interagir de maneira inteligente com o mundo real **.

## Gestion

Você encontrará a lista de cenários do seu Jeedom, bem como funcionalidades para gerenciá-los da melhor maneira possível. :

- **Ajouter** : Crie um cenário. O procedimento é descrito no próximo capítulo.
- **Desativar cenários** : Desativa todos os cenários. Raramente usado e conscientemente, já que nenhum cenário será executado.
- **Visão global** : Permite que você tenha uma visão geral de todos os cenários. Você pode alterar os valores **actif**, **visible**, **MultEu lançamento**, **Sincronicamente**, **Log** e **Timeline** (esses parâmetros são descritos no próximo capítulo). Você também pode acessar os logs para cada cenário e iniciá-los individualmente.

## Meus scripts

Nesta seção, você encontrará o **lista de cenários** que você criou. Eles são classificados de acordo com seus **groupe**, possivelmente definido para cada um deles. Cada cenário é exibido com seus **nom** e o dele **Objeto pai**. O **cenários esmaecidos** são os que estão desativados.

> **Tip**
>
> Você pode abrir um cenário fazendo :
> - Clique em um deles.
> - Ctrl Clic onde Clic Center para abri-lo em uma nova guia do navegador.

Você tem um mecanismo de pesquisa para filtrar a exibição de cenários. à tecla Escape cancela a pesquisa.
À direita do campo de pesquisa, três botões encontrados em vários lugares no Jeedom:
- à cruz para cancelar a pesquisa.
- à pasta aberta para desdobrar todos os painéis e exibir todos os cenários.
- à pasta fechada para dobrar todos os painéis.

Uma vez na configuração de um cenário, você tem um menu contextual com o botão direito do mouse nas guias do cenário. Você também pode usar um Ctrl Click onde Click Center para abrir diretamente outro cenário em uma nova guia do navegador.

## Criação | Editando um Cenário

Depois de clicar em **Ajouter**, você deve escolher o nome do seu cenário. Você é redirecionado para a página de seus parâmetros gerais.
Antes disso, no topo da página, existem algumas funções úteis para gerenciar esse cenário :

- **ID** : Ao lado da palavra **Geral**, este é o identificador de cenário.
- **statut** : *Parado * onde * Em andamento *, indica o estado atual do cenário.
- **Adicionar bloco** : Permite adicionar um bloco do tipo desejado ao cenário (veja abaixo).
- **Log** : Exibe os logs do cenário.
- **Dupliquer** : Copie o cenário para criar um novo com outro nome.
- **Liens** : Permite visualizar o gráfico dos elementos relacionados ao cenário.
- **Edição de texto** : Exibe uma janela que permite editar o cenário na forma de texto / json. Não esqueça de salvar.
- **Exporter** : Permite obter uma versão em texto puro do cenário.
- **Template** : Permite acessar os modelos e aplicar um ao cenário no mercado. (explicado na parte inferior da página).
- **Recherche** : Desdobra um campo de pesquisa para pesquisar no cenário. Esta pesquisa desdobra os blocos recolhidos, se necessário, e os dobra novamente após a pesquisa.
- **Realizar** : Permite iniciar o cenário manualmente (independentemente dos gatilhos). Salve antecipadamente para levar em conta as modificações.
- **Supprimer** : Excluir cenário.
- **Sauvegarder** : Salve as alterações feitas.

> **Tips**
>
> Duas ferramentas também serão inestimáveis para você na configuração de cenários :
    > - As variáveis visíveis em **Ferramentas → Variáveis**
    > - O testador de expressão, acessível por **Ferramentas → Testador de expressão**
>
> Um **Ctrl Clique no botão executar** permite salvar, executar e exibir diretamente o log do cenário (se o nível do log não for Nenhum).

### Guia Geral

Na aba **Geral**, encontramos os principais parâmetros do cenário :

- **Nome do cenário** : O nome do seu cenário.
- **Display Name** : O nome usado para sua exibição. Opcional, se não for concluído, o nome do cenário é usado.
- **Groupe** : Permite organizar os cenários, classificando-os em grupos (visíveis na página de cenários e em seus menus de contexto).
- **Actif** : Ativar o cenário. Se não estiver ativo, ele não será executado pelo Jeedom, independentemente do modo de disparo.
- **Visible** : Permite tornar o cenário visível (Painel).
- **Objeto pai** : Atribuição a um objeto pai. Será então visível onde não, de acordo com este pai.
- **Tempo limite em segundos (0 = ilimitado)** : O tempo máximo de execução permitido para este cenário. Além desse tempo, a execução do cenário é interrompida.
- **MultEu lançamento** : Marque esta caixa se desejar que o cenário possa ser iniciado várias vezes ao mesmo tempo.
- **Sincronicamente** : Inicie o cenário no segmento atual em vez de um segmento dedicado. Aumenta a velocidade na qual o cenário é iniciado, mas pode tornar o sistema instável.
- **Log** : O tipo de log desejado para o cenário. Você pode cortar o log do cenário ou, pelo contrário, fazê-lo aparecer em Análise → Tempo real.
- **Timeline** : Mantenha um acompanhamento do cenário na linha do tempo (consulte o documento Histórico).
- **ícone** : Permite escolher um ícone para o cenário em vez do ícone padrão.
- **Description** : Permite que você escreva um pequeno texto para descrever seu cenário.
- **Modo de cenário** : O cenário pode ser programado, acionado onde ambos. Você terá a opção de indicar o (s) gatilho (15 gatilhos no máximo) e a (s) programação (ões).

> **Tip**
>
> Agora as condições podem ser inseridas no modo acionado. Por exemplo : `# [Garage] [Open Garage] [Opening] # == 1`
> Atenção : você pode ter no máximo 28 gatilhos / programação para um cenário.

> **Modo de ponta programado**
>
> O modo agendado usa sintaxe **Cron**. Você pode, por exemplo, executar um cenário a cada 20 minutos com `* / 20 * * * *` onde às 5 da manhã para acertar várias coisas do dia com `0 5 * * *`. O ? à direita de um programa permite configurá-lo sem ser um especialista em sintaxe do Cron.

### Guia Cenário

É aquEu que você criará seu cenário. Depois de criar o cenário, seu conteúdo está vazio, então ele fará ... nada. Você tem que começar com **Adicionar bloco**, com o botão à direita. Após a criação de um bloco, você pode adicionar outro **bloc** onde um **action**.

Para maior comodidade e não ter que reordenar constantemente os blocos no cenário, a adição de um bloco é feita após o campo em que o cursor do mouse está localizado.
*Por exemplo, se você tiver dez blocos e clicar na condição SE do primeiro bloco, o bloco adicionado será adicionado após o bloco, no mesmo nível. Se nenhum campo estiver ativo, ele será adicionado no final do cenário.*

> **Tip**
>
> Em condições e ações, é melhor usar aspas simples (') em vez de duplas (").

> **Tip**
>
> Ctrl Shift Z onde Ctrl Shift Y permite que você'**annuler** onde refazer uma modificação (adição de ação, bloco ...).

### Blocos

AquEu estão os diferentes tipos de blocos disponíveis :

- **If / Then / Ou** : Permite que você execute ações condicionais (se isso, então aquilo).
- **Action** : Permite iniciar ações simples sem nenhuma condição.
- **Boucle** : Permite que as ações sejam executadas repetidamente de 1 a um número definido (onde mesmo o valor de um sensor, onde um número aleatório etc.).
- **Dans** : Permite iniciar uma ação em X minuto (s) (0 é um valor possível). à peculiaridade é que as ações são iniciadas em segundo plano, para que não bloqueiem o restante do cenário. Portanto, é um bloco sem bloqueio.
- **A** : Permite que o Jeedom inicie as ações do bloco em um determinado momento (no formato hhmm). Este bloco é sem bloqueio. Ex : 0030 para 00:30 onde 0146 para 1h46 e 1050 para 10h50.
- **Code** : Permite escrever diretamente no código PHP (requer certo conhecimento e pode ser arriscado, mas permite que você não tenha restrições).
- **Commentaire** : Permite adicionar comentários ao seu cenário.

Cada bloco tem suas opções para lidar melhor com eles :

- À esquerda :
    - à seta bidirecional permite mover um bloco onde uma ação para reordená-los no cenário.
    - O olho reduz um bloqueio (* colapso *) para reduzir seu impacto visual. Ctrl Clique nos olhos para reduzi-los onde exibi-los todos.
    - à caixa de seleção permite desativar completamente o bloco sem excluí-lo. Portanto, não será executado.

- À direita :
    - O ícone Copiar permite copiar o bloco para fazer uma cópia em outro lugar. Ctrl Clique no ícone corta o bloco (copie e exclua).
    - O ícone Colar permite colar uma cópia do bloco copiado anteriormente após o bloco no qual você usa esta função..  Ctrl Clique no ícone substituEu o bloco pelo bloco copiado.
    - O ícone - permite excluir o bloco, com uma solicitação de confirmação. Ctrl Clique excluEu o bloco sem confirmação.

#### Se / Então / Caso contrário, bloqueia | Laço | Dans | A

Pelas condições, o Jeedom tenta torná-las possíveis o máximo possível em linguagem natural, mantendo-se flexível.
> NÃO use [] em testes de condição, apenas parênteses () são possíveis.

Três botões estão disponíveis à direita deste tipo de bloco para selecionar um item para testar :

- **Ordem de pesquisa** : Permite procurar um pedido em todos os disponíveis no Jeedom. Depois que o pedido é encontrado, o Jeedom abre uma janela para perguntar qual teste você deseja executar nele. Se você escolher **Não ponha nada**, Jeedom adicionará o pedido sem comparação. Você também pode escolher **et** onde **ou** Na frente **Ensuite** para encadear testes em diferentes equipamentos.
- **Pesquisa cenário** : Permite procurar um cenário para testar.
- **Procure equipamento** : O mesmo para equipamentos.

> **Note**
>
> Em blocos do tipo Se / Então / Caso contrário, as setas circulares à esquerda do campo de condição permitem ativar onde não a repetição de ações se a avaliação da condição fornecer o mesmo resultado que na avaliação anterior.

> **Tip**
>
> Há uma lista de tags que permitem acessar variáveis do cenário onde de outro, onde pela hora, data, número aleatório,… Veja abaixo os capítulos sobre comandos e tags.

Depois que a condição estiver concluída, você deve usar o botão "adicionar" à esquerda para adicionar um novo **bloc** onde um **action** no bloco atual.


#### Código de bloco

O bloco CÓDIGO permite executar código php. Portanto, é muito poderoso, mas requer um bom conhecimento da linguagem php.

##### Acesso a controles (sensores e atuadores):
-  `cmd::byString ($ string); ` : Retorna o objeto de comando correspondente.
    -   `$string`: Link para o pedido desejado : `# [objeto] [equipamento] [comando] #` (ex : `# [àpartamento] [àlarme] [àtivo] #`)
-  `cmd::byId ($ id); ` : Retorna o objeto de comando correspondente.
    -  `$ id` : ID do pedido.
-  $ cmd-> execCmd ($ options = null); ` : Execute o comando e retorne o resultado.
    - `$ opções` : Opções para execução de pedidos (pode ser específico ao plugin). Opções básicas (subtipo de comando) :
        -  Mensagem : `$ option = array ('title' => 'título da mensagem,' Mensagem '=>' Minha mensagem ');`
        -  cor : `$ option = array ('color' => 'cor em hexadecimal');`
        -  controle deslizante : `$ option = array ('slider' => 'valor desejado de 0 a 100');`

##### Acesso ao log :
-  `log::add ('nome do arquivo', 'nível', 'mensagem'); `
    - filename : Nome do arquivo de log.
    - nível : [depuração], [Eunformações], [erro], [evento].
    - Mensagem : Mensagem para escrever nos logs.

##### Acesso ao cenário :
- `$ cenário-> getName ();` : Retorna o nome do cenário atual.
- `$ cenário-> getGroup ();` : Retorna o grupo de cenários.
- `$ cenário-> getIsActive ();` : Retorna o estado do cenário.
- `$ cenário-> setIsActive ($ ativo);` : Permite ativar onde não o cenário.
    - `$ ativo` : 1 ativo, 0 inativo.
- `$ cenário-> setOnGoing ($ onGoing);` : Vamos dizer se o cenário está em execução onde não.
    - `$ onGoing => 1` : 1 em andamento, 0 parado.
- `$ cenário-> save ();` : Salvar alterações.
- `$ cenário-> setData (chave $, valor $);` : Salvar um dado (variável).
    - `$ key` : chave de valor (int onde string).
    - `$ value` : valor a armazenar (int, string, array onde objeto).
- `$ cenário-> getData (chave $);` : Obter dados (variável).
    - `$ chave => 1` : chave de valor (int onde string).
- `$ cenário-> removeData (chave $);` : Excluir dados.
- `$ cenário-> setLog ($ mensagem);` : Escreva uma mensagem no log de script.
- `$ cenário-> persistLog ();` : Forçar a gravação do log (caso contrário, ele será gravado apenas no final do cenário). Cuidado, isso pode atrasar um pouco o cenário.

> **Tip**
>
> Adição de uma função de pesquisa no bloco Código : Pesquisa : Ctrl + F e Enter, próximo resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G

#### Bloco de comentários

O bloco de comentários age de maneira diferente quando está oculto. Seus botões à esquerda desaparecem, assim como o título do bloco, e reaparecem ao passar o mouse.. Da mesma forma, a primeira linha do comentário é exibida em negrito.
Isso permite que esse bloco seja usado como uma separação puramente visual dentro do cenário.

### Acções

As ações adicionadas aos blocos têm várias opções :

- Uma caixa **ativado** para que esse comando seja levado em consideração no cenário.
- Uma caixa **paralelo** para que este comando seja iniciado em paralelo (ao mesmo tempo) com os outros comandos também selecionados.
- Um **seta dupla vertical** para mover a ação. Basta arrastar e soltar a partir daí.
- Um botão para **supprimer** a ação.
- Um botão para ações específicas, sempre que a descrição (em foco) dessa ação.
- Um botão para procurar um comando de ação.

> **Tip**
>
> Dependendo do comando selecionado, você pode ver diferentes campos adicionais exibidos.

## Possíveis substituições

### Triggers

Existem gatilhos específicos (além daqueles fornecidos por comandos) :

- #start# : Acionado no (re) início do Jeedom.
- #begin_backup# : Evento enviado no início de um backup.
- #end_backup# : Evento enviado no final de um backup.
- #begin_update# : Evento enviado no início de uma atualização.
- #end_update# : Evento enviado no final de uma atualização.
- #begin_restore# : Evento enviado no início de uma restauração.
- #end_restore# : Evento enviado no final de uma restauração.
- #user_connect# : Login do usuário

Você também pode disparar um cenário quando uma variável é atualizada, colocando : #variável (nome_da_variável) # onde usando a API HTTP descrita [aquEu](https://jeedom.github.io/core/fr_FR/api_http).

### Operadores de comparação e links entre condições

Você pode usar qualquer um dos seguintes símbolos para comparações em condições :

- == : Igual a.
- \> : Estritamente maior que.
- \>= : Maior onde igual a.
- < : Estritamente menor que.
- <= : Menor onde igual a.
- != : Diferente de, não é igual a.
- correspondências : Contém. Ex : `[Banheiro] [Hidrometria] [condição] corresponde a" / we / "`.
- não (... corresponde ...) : Não contém. Ex :  `not ([Banheiro] [Hidrometria] [estado] corresponde a" / we / ")`.

Você pode combinar qualquer comparação com os seguintes operadores :

- && / ET / e / AND / e : et,
- \|| / OU / onde / OU / onde : ou,
- \|^ / XOR / xor : onde exclusivo.

### Tags

Uma tag é substituída durante a execução do cenário por seu valor. Você pode usar as seguintes tags :

> **Tip**
>
> Para exibir os zeros à esquerda, use a função Data (). Veja [aquEu](http://php.net/manual/fr/function.date.php).

- #seconde# : Segundo atual (sem zeros à esquerda, ex : 6 para 08:07:06).
- #hour# : Hora atual no formato 24h (sem zeros à esquerda). Ex : 8 para 08:07:06 onde 17 para 17:15.
- #hour12# : Hora atual no formato de 12 horas (sem zeros à esquerda). Ex : 8 para 08:07:06.
- #minute# : Minuto atual (sem zeros à esquerda). Ex : 7 para 08:07:06.
- #day# : Dia atual (sem zeros à esquerda). Ex : 6 em 07/07/2017.
- #month# : Mês atual (sem zeros à esquerda). Ex : 7 em 07/07/2017.
- #year# : Ano atual.
- #time# : Hora e minuto atuais. Ex : 1715 para 17h15.
- #timestamp# : Número de segundos desde 1 de janeiro de 1970.
- #date# : Dia e mês. Atenção, o primeiro número é o mês. Ex : 1215 para 15 de dezembro.
- #week# : Número da semana.
- #sday# : Nome do dia da semana. Ex : Sábado.
- #nday# : Número do dia de 0 (domingo) a 6 (sábado).
- #smonth# : Nome do mês. Ex : Janeiro.
- #IP# : IP interno da Jeedom.
- #hostname# : Nome da máquina Jeedom.
- #trigger # (obsoleto, melhor usar trigger ()) : Talvez o nome do comando que inicionde o cenário :
    - 'API 'se o lançamento foEu acionado pela API,
    - 'agendar 'se foEu iniciado pela programação,
    - 'usuário 'se foEu iniciado manualmente,
    - 'Começo 'para um lançamento quando o Jeedom iniciar.
- #trigger_value # (obsoleto, melhor usar triggerValue ()) : Para o valor do comando que aciononde o cenário

Você também tem as seguintes tags adicionais se seu cenário foEu acionado por uma interação :

- #query# : Interação que aciononde o cenário.
- #profil# : Perfil do usuário que aciononde o cenário (pode estar vazio).

> **Important**
>
> Quando um cenário é acionado por uma interação, é necessariamente executado no modo rápido. Portanto, no segmento de interação e não em um segmento separado.

### Funções de cálculo

Várias funções estão disponíveis para o equipamento :

- average (order, period) and averageBetween (order, start, end) : Dê a média do pedido ao longo do período (período=[mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- min (ordem, período) e minBetween (ordem, início, fim) : Dê o pedido mínimo durante o período (período=[mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- max (ordem, período) e maxBetween (ordem, início, fim) : Forneça o máximo do pedido durante o período (período=[mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- duração (ordem, valor, período) e duração entre (ordem, valor, início, fim) : Indique a duração em minutos durante os quais o equipamento teve o valor escolhido durante o período (período=[mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- statistics (ordem, cálculo, período) e statisticsBetween (ordem, cálculo, início, fim) : Forneça o resultado de diferentes cálculos estatísticos (soma, contagem, padrão, variação, média, mín., Máx.) Ao longo do período (período=[mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- tendência (ordem, período, limite) : Dá a tendência do pedido ao longo do período (período=[mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- stateDuration (comando) : Dá a duração em segundos desde a última alteração no valor.
    -1 : Não existe histórico onde valor não existe no histórico.
    -2 : O pedido não está registrado.

- lastChangeStateDuration (comando, valor) : Dá a duração em segundos desde a última mudança de estado para o valor passado no parâmetro.
    -1 : Não existe histórico onde valor não existe no histórico.
    -2 O pedido não está registrado

- lastStateDuration (comando, valor) : Dá a duração em segundos durante os quais o equipamento teve o valor escolhido pela última vez.
    -1 : Não existe histórico onde valor não existe no histórico.
    -2 : O pedido não está registrado.

- idade (pedido) : Dá a idade em segundos do valor do comando (collecDate)
    -1 : O comando não existe onde não é do tipo info.

- stateChanges (comando,[valor], período) e stateChangesBetween (comando, [valor], início, fim) : Forneça o número de alterações de estado (em direção a um determinado valor, se indicado, onde no total, se não) durante o período (período = [mês, dia, hora, min] onde [expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)) onde entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- lastBetween (comando, início, fim) : Fornece o último valor registrado para o equipamento entre os 2 terminais solicitados (no formato Ymd H:i:s onde [Expressão PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- variável (variável, padrão) : Recupera o valor de uma variável onde o valor desejado por padrão.

- cenário : Retorna o status do cenário.
    1 : Contínuo,
    0 : Preso,
    -1 : Inválido,
    -2 : O cenário não existe,
    -3 : Estado não é consistente.
    Para ter o nome "humano" do cenário, você pode usar o botão dedicado à direita da pesquisa de cenário.

- lastScenarioExecution (cenário) : Dá a duração em segundos desde o último lançamento do cenário.
    0 : O cenário não existe

- collectDate (cmd,[formato]) : Retorna a data dos últimos dados para o comando dado no parâmetro, o 2º parâmetro opcional permite especificar o formato de retorno (detalhes [aquEu](http://php.net/manual/fr/function.date.php)).
    -1 : Não foEu possível encontrar o comando,
    -2 : O comando não é do tipo info.

- valueDate (cmd,[formato]) : Retorna a data dos últimos dados para o comando dado no parâmetro, o 2º parâmetro opcional permite especificar o formato de retorno (detalhes [aquEu](http://php.net/manual/fr/function.date.php)).
    -1 : Não foEu possível encontrar o comando,
    -2 : O comando não é do tipo info.

- eqEnable (equipamento) : Retorna o status do equipamento.
    -2 : O equipamento não pode ser encontrado,
    1 : O equipamento está ativo,
    0 : O equipamento está inativo.

- valor (cmd) : Retorna o valor de um pedido, se não for fornecido automaticamente pelo Jeedom (caso ao armazenar o nome do pedido em uma variável)

- tag (montag, [padrão]) : Usado para recuperar o valor de uma tag onde o valor padrão, se ele não existir.

- nome (tipo, comando) : Usado para recuperar o nome do pedido, equipamento onde objeto. Tipo : cmd, eqLogic onde objeto.

- lastCommunication (equipamento,[formato]) : Retorna a data da última comunicação para o equipamento dado como parâmetro; o 2º parâmetro opcional permite especificar o formato de retorno (detalhes [aquEu](http://php.net/manual/fr/function.date.php)) Um retorno de -1 significa que o equipamento não pode ser encontrado.

- color_gradient (start_colour, end_colour, min_value, max_value, value) : Retorna uma cor calculada com relação ao valor no intervalo color_Começo / color_end. O valor deve estar entre min_value e max_value.

Os períodos e intervalos dessas funções também podem ser usados com [Expressões PHP](http://php.net/manual/fr/datetime.formats.relative.php) como por exemplo :

- Agora : agora.
- Hoje : 00:00 hoje (permite, por exemplo, obter resultados para o dia entre 'Hoje' e 'Agora').
- Segunda-feira passada : segunda-feira passada às 00:00.
- 5 dias atrás : 5 dias atrás.
- Ontem meio-dia : ontem ao meio dia.
- Etc.

AquEu estão exemplos práticos para entender os valores retornados por essas diferentes funções :

| Soquete com valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos)    |
|--------------------------------------|--------------------------------------|
| média (captura, período)             | Retorna a média de 0 e 1 (pode  |
|                                      | ser influenciado pela pesquisa)      |
| averageBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o pedido médio entre 1 de janeiro de 2015 e 15 de janeiro de 2015                       |
| min (captura, período)                 | Retorna 0 : o plugue foEu extinto durante o período              |
| minBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o pedido mínimo entre 1 de janeiro de 2015 e 15 de janeiro de 2015                       |
| max (captura, período)                 | Retorna 1 : o plugue estava bem iluminado no período              |
| maxBetween (\# [Banheiro] [Hidrometria] [Umidade] \#, 01-01-2015 00:00:00,2015-01-15 00:00:00) | Retorna o máximo do pedido entre 1 de janeiro de 2015 e 15 de janeiro de 2015                       |
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
| cenário (\# [Banheiro] [Ove] [àutomático] \#) | Retorna 1 em andamento, 0 se parado e -1 se desativado, -2 se o cenário não existir e -3 se o estado não for consistente                         |
| lastScenarioExecution (\# [Banheiro] [Ove] [àutomático] \#)   | Retorna 300 se o cenário foEu iniciado pela última vez há 5 minutos                                  |
| collectDate (\# [Banheiro] [Hidrometria] [Umidade] \#)     | Devoluções 01-01-2015 17:45:12          |
| valueDate (\# [Banheiro] [Hidrometria] [Umidade] \#) | Devoluções 01-01-2015 17:50:12          |
| eqEnable (\# [Nenhum] [Basílica] \#)       | Retorna -2 se o equipamento não for encontrado, 1 se o equipamento estiver ativo e 0 se estiver inativo          |
| tag (montag, toto)                   | Retorna o valor de "montag" se existir, caso contrário, retorna o valor "para"                               |
| nome (eqLogic, \# [Banheiro] [Hidrometria] [Umidade] \#)     | Retorna Hidrometria                  |


### Funções matemáticas

Uma caixa de ferramentas de funções genéricas também pode ser usada para realizar conversões

onde cálculos :

- `rand (1,10)` : Dê um número aleatório de 1 a 10.
- `randText (texto1; texto2; texto ... ..)` : Permite retornar um dos textos aleatoriamente (separe os textos por um;). Não há limite no número de textos.
- `randomColor (min, max)` : Dá uma cor aleatória entre 2 limites (0 => vermelho, 50 => verde, 100 => azul).
- `gatilho (comando)` : Permite descobrir o gatilho para o cenário onde saber se foEu o comando passado como um parâmetro que aciononde o cenário.
- `triggerValue (command)` : Usado para descobrir o valor do gatilho do cenário.
- `round (valor, [decimal])` : Arredonda acima, número [decimal] de casas decimais após o ponto decimal.
- `ímpar (valor)` : Permite saber se um número é ímpar onde não. Retorna 1 se ímpar 0, caso contrário.
- `mediana (comando1, comando2….commandN) ` : Retorna a mediana dos valores.
- `avg (comando1, comando2….commandN) `: Retorna a média dos valores.
- `time_op (hora, valor)` : Permite executar operações dentro do prazo, com Tempo = Tempo (ex : 1530) e value = value para adicionar onde subtrair em minutos.
- `time_between (hora, início, fim)` : Utilizado para testar se um tempo está entre dois valores com `Tempo = time` (ex : 1530), `Começo = time`,` end = time`. Os valores inicial e final podem chegar à meia-noite.
- `time_diff (data1, data2 [, formato, rodada])` : Usado para descobrir a diferença entre duas datas (as datas devem estar no formato AAAà / MM / DD HH:MM:SS). Por padrão, o método retorna a diferença em dia (s). Você pode perguntar em segundos (s), minutos (m), horas (h). Exemplo em segundos `time_diff (02-02 2019 14:55:00.2019-02-25 14:55:00,s)`. à diferença é retornada em absoluto, a menos que você especifique `f` (sf, mf, hf, df). Você também pode usar `dhms`, que não retornará exemplo` 7d 2h 5min 46s`. O parâmetro round, opcional, arredondado para x dígitos após o ponto decimal (2 por padrão). Ex: `time_diff (2020-02-21 20:55:28,2020-02-28 23:01:14, df, 4) `.
- `formatTime (time)` : Permite formatar o retorno de uma string `# Tempo #`.
- `floor (Tempo / 60)` : Converte segundos em minutos onde minutos em horas (piso (tempo / 3600) por segundos em horas).
- `convertDuration (segundos)` : Converte segundos em d / h / min / s.

E exemplos práticos :


| Exemplo de função                  | Resultado retornado                    |
|--------------------------------------|--------------------------------------|
| randText (é # [sala] [olho] [temperatura] #; à temperatura é # [sala] [olho] [temperatura] #; Atualmente, temos # [sala] [olho] [temperatura] #) | a função retornará um desses textos aleatoriamente a cada execução.                           |
| randomColor (40,60)                 | Retorna uma cor aleatória próxima ao verde.
| gatilho (# [Banheiro] [Hidrometria] [Umidade] #)   | 1 se estiver bom \# \ [Banheiro \] \ [Hidrometria \] \ [Umidade \] \# que inicionde o cenário caso contrário 0  |
| triggerValue (# [Banheiro] [Hidrometria] [Umidade] #) | 80 se a hidrometria de \# \ [Banheiro \] \ [Hidrometria \] \ [Umidade \] \# for 80%.                         |
| redondo (# [Banheiro] [Hidrometria] [Umidade] # / 10) | Retorna 9 se a porcentagem de umidade e 85                     |
| ímpar (3)                             | Retorna 1                            |
| mediana (15,25,20)                   | Retorna 20
| média (10,15,18)                      | Retorna 14.3                     |
| time_op (# Tempo #, -90)               | se forem 16h50, retorne : 1 650 - 1 130 = 1520                          |
| formatTime (1650)                   | Retorna 16:50                        |
| de piso (130/60)                      | Retorna 2 (minutos se 130s onde horas se 130m)                      |
| convertDuration (3600)              | Retorna 1h 0min 0s                      |
| convertDuration (duration (# [Heating] [Module Boiler] [State] #, 1, primeiro dia deste mês) * 60) | Retorna o tempo de ignição em Dias / Horas / minutos do tempo de transição para o estado 1 do módulo desde o 1º dia do mês |


### Pedidos específicos

Além dos comandos de automação residencial, você tem acesso às seguintes ações :

- **Pause** (dormir) : Pausa x segundo (s).
- **variable** (variável) : Criação / modificação de uma variável onde o valor de uma variável.
- **Remover variável** (delete_variable) : Permite excluir uma variável.
- **Cenas** (cenário) : Permite controlar cenários. à parte de tags permite enviar tags para o cenário, ex. : montag = 2 (tenha cuidado, use apenas letras de a a z. Sem letras maiúsculas, sem acentos e sem caracteres especiais). Recuperamos a tag no cenário de destino com a função tag (montag). O comando "Redefinir para SI" permite redefinir o status de "SI" (esse status é usado para a não repetição das ações de um "SI" se você passar pela segunda vez consecutiva)
- **Stop** (parar) : Pára o script.
- **Attendre** (espera) : Aguarde até que a condição seja válida (máximo de 2h), o tempo limite será em segundos.
- **VaEu o projeto** (gotodesign) : Alterar o design exibido em todos os navegadores pelo design solicitado.
- **Adicionar um registro** (log) : Permite adicionar uma mensagem no log.
- **Criar mensagem** (mensagem) : Adicionar uma mensagem ao centro de mensagens.
- **Activar / Desactivar Hide / Show equipamentos** (equipamento) : Permite modificar as propriedades de equipamento visível / invisível, ativo / inativo.
- **Aplicar** (pergunte) : Permite indicar a Jeedom que é necessário fazer uma pergunta ao usuário. à resposta é armazenada em uma variável, então você só precisa testar seu valor.
    No momento, apenas plugins sms, slack, telegram e snips são compatíveis, assim como o aplicativo móvel.
    Atenção, esta função está bloqueando. Enquanto não houver resposta onde o tempo limite não for atingido, o cenário aguarda.
- **Pare Jeedom** (jeedom_poweroff) : Peça ao Jeedom para desligar.
- **Retornar um texto / um dado** (cenário_retorno) : Retorna um texto onde um valor para uma interação por exemplo.
- **ícone** (ícone) : Permite alterar o ícone de representação do cenário.
- **Alerte** (alerta) : Exibe uma pequena mensagem de alerta em todos os navegadores que têm uma página Jeedom aberta. Além disso, você pode escolher 4 níveis de alerta.
- **Pop-up** (pop-up) : Permite exibir um pop-up que deve ser absolutamente validado em todos os navegadores que possuem uma página jeedom aberta.
- **Rapport** (relatório) : Permite exportar uma visualização em formato (PDF, PNG, JPEG onde SVG) e enviá-la usando um comando do tipo mensagem. Observe que, se seu acesso à Interne estiver em HTTPS não assinado, essa funcionalidade não funcionará. HTTP onde HTTPS assinado é necessário.
- **Excluir bloco IN / à agendado** (remove_inat) : Apagar a programação de todos os blocos dentro e à Cenário.
- **Evento** (evento) : Permite inserir um valor em um comando de tipo de informação arbitrariamente.
- **Tag** (tag) : Permite adicionar / modificar uma tag (a tag existe apenas durante a execução atual do cenário, diferente das variáveis que sobrevivem ao final do cenário).
- **Coloração de ícones do painel** (setColoredIcon) : permite ativar onde não a coloração de ícones no painel.

### Modelo cenário

Essa funcionalidade permite transformar um cenário em um modelo para, por exemplo, aplicá-lo a outro Jeedom.

Clicando no botão **template** na parte superior da página, você abre a janela de gerenciamento de modelos.

à partir daí, você tem a possibilidade :

- Envie um modelo para Jeedom (arquivo JSON recuperado anteriormente).
- Consulte a lista de cenários disponíveis no mercado.
- Crie um modelo a partir do cenário atual (não esqueça de dar um nome).
- Para consultar os modelos atualmente presentes no seu Jeedom.

Ao clicar em um modelo, você pode :

- **Partager** : Compartilhe o modelo no mercado.
- **Supprimer** : Excluir modelo.
- **Baixar** : Obtenha o modelo como um arquivo JSON para enviá-lo para outro Jeedom, por exemplo.

Abaixo, você tem a parte para aplicar seu modelo ao cenário atual.

Como, de um Jeedom para outro onde de uma instalação para outro, os comandos podem ser diferentes, o Jeedom solicita a correspondência dos comandos entre os presentes durante a criação do modelo e os presentes em casa. Você só precisa preencher a correspondência dos pedidos e aplicar.

### Adição da função php

> **IMPORTANT**
>
> à adição da função PHP é reservada para usuários avançados. O menor erro pode ser fatal para o seu Jeedom.

#### Configurar

Vá para a configuração do Jeedom, então OS / DB e inicie o editor de arquivos.

Vá para a pasta de dados, php e clique no arquivo user.function.class.php.

É nesta * classe * que você pode adicionar suas funções, você encontrará um exemplo de uma função básica.

> **IMPORTANT**
>
> Se você tiver alguma dúvida, sempre poderá reverter para o arquivo original, copiando o conteúdo de user.function.class.sample.php em user.function.class.php
