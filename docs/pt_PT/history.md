# Historique
**Análise → História**

Parte importante no software : a parte da historização, uma lembrança verdadeira. É possível no Jeedom registrar qualquer comando do tipo de informação (binário ou digital). Isso permitirá, por exemplo, registrar uma curva de temperatura, consumo ou abertura de porta, etc

### Principe

Aqui é descrito o princípio de historicização de Jeedom. Você só precisa entender isso se estiver com problemas de histórico ou quiser alterar as configurações de histórico. As configurações padrão são boas na maioria dos casos.

### Archivage

O arquivamento de dados permite que o Jeedom reduza a quantidade de dados armazenados na memória. Isso permite não usar muito espaço e não diminui a velocidade do sistema. De fato, se você mantiver todas as medidas, isso fará com que mais pontos sejam exibidos e, portanto, poderá aumentar consideravelmente os tempos para renderizar um gráfico. Se houver muitos pontos, pode até causar uma falha na exibição do gráfico.

O arquivamento é uma tarefa que começa à noite e compacta os dados recuperados durante o dia. Por padrão, o Jeedom recupera todos os dados antigos de 2 horas e faz pacotes de 1 hora (uma média, um mínimo ou um máximo, dependendo das configurações). Portanto, aqui temos dois parâmetros, um para o tamanho do pacote e outro para saber quando fazê-lo (por padrão, são pacotes de 1 hora com dados com mais de 2 horas)).

> **Dica**
>
> Se você seguiu bem, deve ter alta precisão nas últimas 2 horas. No entanto, quando me conecto às 17h, tenho uma precisão nas últimas 17 horas. Porque ? De fato, para evitar consumir recursos desnecessariamente, a tarefa de arquivamento ocorre apenas uma vez por dia, à noite.

> **IMPORTANTE**
>
> Obviamente, esse princípio de arquivamento se aplica apenas a comandos do tipo digital; nos comandos do tipo binário, o Jeedom mantém apenas as datas de mudança de estado.

### Visualizando um Gráfico

Existem várias maneiras de acessar o histórico :

- Clicando no comando desejado em um widget,
- Ao acessar a página de histórico, que permite sobrepor diferentes curvas e combinar estilos (área, curva, barra),
- No celular, enquanto permanece pressionado no widget em questão,
- Colocando uma área gráfica em uma visualização (veja abaixo).

## Guia Histórico

Se você exibir um gráfico na página de histórico, terá acesso a várias opções de exibição :

Encontramos no canto superior direito o período de exibição (aqui na última semana, porque, por padrão, quero que seja apenas uma semana - veja 2 parágrafos acima), depois vêm os parâmetros da curva (esses parâmetros são mantidos de um monitor para outro, então você só precisa configurá-los uma vez).

- **Escada** : Exibe a curva como uma escada ou uma exibição contínua.
- **Mudança** : Exibe a diferença de valor do ponto anterior.
- **Linha** : Exibe o gráfico como linhas.
- **área** : Exibe o gráfico como uma área.
- **Coluna**\* : Exibe o gráfico como barras.

> **Dica**
>
> Se você exibir várias curvas ao mesmo tempo:
> - Clique em uma legenda abaixo do gráfico para exibir / ocultar esta curva.
> - Ctrl Clique em uma legenda para exibir apenas esta.
> - Alt Clique em uma legenda permite exibir todos eles.


### Gráfico em vistas e desenhos

Você também pode exibir os gráficos nas visualizações (veremos aqui as opções de configuração e não como fazê-lo, para isso, é necessário ir à documentação das visualizações ou desenhos em função). Aqui estão as opções :

Depois que os dados são ativados, você pode escolher :
- **Cor** : A cor da curva.
- **Tipo** : O tipo de gráfico (área, linha ou coluna).
- **Escala** : Como você pode colocar várias curvas (dados) no mesmo gráfico, é possível distinguir as escalas (direita ou esquerda)).
- **Escada** : Exibe a curva como uma escada ou uma exibição contínua.
- **Pilha** : Empilhe os valores das curvas (veja abaixo o resultado).
- **Mudança** : Exibe a diferença de valor do ponto anterior.

### Opção na página do histórico

A página de histórico fornece acesso a algumas opções adicionais

#### História calculado

Permite exibir uma curva de acordo com um cálculo em vários comandos (você pode fazer quase tudo, + - / \* valor absoluto ... consulte a documentação do PHP para determinadas funções).
Ex :
abs(*\ [Jardim \] \ [Higrometria \] \ [Temperatura \]* - *\ [Espaço vital]] [Higrometria \] \ [Temperatura \]*)

Você também tem acesso a um gerenciamento de fórmulas de cálculo que permite salvá-las para facilitar a exibição novamente.

> **Dica**
>
> Basta clicar no nome do objeto para desdobrar e exibir os comandos históricos que podem ser exibidos.

#### Histórico de pedidos

Na frente de cada dado que pode ser exibido, você encontrará dois ícones :

- **Lixeira** : Permite excluir os dados gravados; ao clicar, o Jeedom pergunta se deseja excluir os dados antes de uma determinada data ou todos os dados.
- **Arrow** : Permite a exportação CSV de dados históricos.

### Remoção de valor inconsistente

Às vezes você pode ter valores inconsistentes nos gráficos. Isso geralmente ocorre devido à preocupação em interpretar o valor. É possível excluir ou alterar o valor do ponto em questão, clicando nele diretamente no gráfico; Além disso, você pode ajustar o mínimo e o máximo permitido para evitar problemas futuros.

## Guia Linha do tempo

A linha do tempo exibe certos eventos em sua automação residencial em forma cronológica.

Para vê-los, você deve primeiro ativar o rastreamento na linha do tempo dos comandos ou cenários desejados, para que esses eventos ocorram.

- **Cenas** : Diretamente na página do cenário ou na página de resumo do cenário para fazê-lo em massa".
- **Ordem** : Na configuração avançada do comando ou na configuração do histórico para fazê-lo em "massa".

A linha do tempo *Principal* sempre contém todos os eventos. No entanto, você pode filtrar a linha do tempo *Ficheiro*. Em cada local em que você ativa a linha do tempo, você terá um campo para inserir o nome de uma pasta, existente ou não.
Você pode filtrar a linha do tempo por esta pasta selecionando-a à esquerda do botão *Legal*.

> **NOTA**
>
> Se você não usar mais uma pasta, ela aparecerá na lista enquanto existirem eventos vinculados a essa pasta. Ele desaparecerá da lista por si só.

> **Dica**
>
> Você tem acesso às janelas de resumo do cenário ou de configuração do histórico diretamente da página da linha do tempo.

Depois de ativar o rastreamento na linha do tempo dos comandos e cenários desejados, você poderá vê-los aparecer na linha do tempo.

> **IMPORTANTE**
>
> Você precisa aguardar novos eventos após ativar o rastreamento na linha do tempo antes de vê-los aparecer.

### Affichage

A linha do tempo exibe uma tabela de eventos registrados em três colunas:

- A data e hora do evento,
- O tipo de evento: Um comando de informação ou ação, ou um cenário, com o plug-in de comando para comandos.
- O nome do objeto pai, o nome e, dependendo do tipo, estado ou gatilho.

- Um evento do tipo de comando exibe um ícone à direita para abrir a configuração do comando.
- Um evento do tipo cenário exibe dois ícones à direita para ir para o cenário ou abrir o log do cenário.

