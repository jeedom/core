# Historique
**Análise → História**

Parte importante no software : a parte da historização, uma lembrança verdadeira. É possível no Jeedom registrar qualquer comando do tipo de informação (binário ou digital). Isso permitirá, por exemplo, registrar uma curva de temperatura, consumo, aberturas de uma porta, etc.

![Histórico](./images/history.gif)

### Princípio de historização

### Archivage

O arquivamento de dados permite que a Jeedom reduza a quantidade de dados mantidos na memória. Isso permite não usar muito espaço e não diminui a velocidade do sistema. De fato, se você mantiver todas as medidas, isso fará com que mais pontos sejam exibidos e, portanto, poderá aumentar consideravelmente os tempos para renderizar um gráfico. Se houver muitos pontos, pode até causar uma falha na exibição do gráfico.

O arquivamento é uma tarefa que começa à noite e compacta os dados recuperados durante o dia. Por padrão, o Jeedom recupera todos os dados antigos de 2 horas e faz pacotes de 1 hora (uma média, um mínimo ou um máximo, dependendo das configurações). Portanto, aqui temos dois parâmetros, um para o tamanho do pacote e outro para saber quando fazê-lo (por padrão, são pacotes de 1 hora com dados com mais de 2 horas)).

> **Dica**
>
> Se você seguiu bem, deve ter alta precisão nas últimas 2 horas. No entanto, quando me conecto às 17h, tenho uma precisão nas últimas 17 horas. Para que ? De fato, para evitar consumir recursos desnecessariamente, a tarefa de arquivamento ocorre apenas uma vez por dia, à noite.

> **Importante**
>
> Obviamente, esse princípio de arquivamento se aplica apenas a pedidos digitais. Em pedidos de tipo binário, Jeedom mantém apenas as datas de mudança de estado.

### Visualizando um Gráfico

Existem várias maneiras de acessar o histórico :

- Clicando no comando desejado em um widget,
- Acessando a página de histórico que permite sobrepor diferentes curvas e combinar estilos (área, curva, barra),
- No celular, enquanto permanece pressionado no widget em questão,
- Colocando uma área de gráfico em uma visualização (veja abaixo),
- Inserindo um gráfico em um Design.

Do Core v4.2, também é possível exibir uma curva na parte inferior do ladrilho de um dispositivo.

## Historique

Se você exibir um gráfico na página de histórico, terá acesso a várias opções de exibição, acima do gráfico :

- **Período** : O período de exibição, incluindo dados históricos entre essas duas datas. Por padrão, dependendo da configuração *Gráficos padrão de exibição Período* Em *Configurações → Sistema → Configuração / Equipamento*.
- **Grupo** : Oferece várias opções de agrupamento (soma por hora etc.).).
- **Tipo de exibição** : Exibir em *Linha*, *Área*, Ou *Haste*. Opção salva no pedido e usada no Painel.
- **Variação** : Exibe a diferença de valor do ponto anterior. Opção salva no pedido e usada no Painel.
- **Escadaria** : Exibe a curva como uma escada ou uma exibição contínua. Opção salva no pedido e usada no Painel.
- **Comparar** : Compare a curva entre diferentes períodos.

> **Dica**
>
> Para evitar qualquer erro de manuseio, essas opções salvas nos comandos só ficam ativas quando uma única curva é exibida.
> 
Na parte superior onde as curvas são exibidas, também existem várias opções :

À esquerda:

- **Zoom** : Uma área de atalho que permite ajustar o zoom horizontal para a duração desejada, se os dados forem carregados.

A direita:

- **Eixos verticais visíveis** : Permite-lhe ocultar ou mostrar todos os eixos verticais.
- **Escala do eixo vertical** : Permite ativar ou não a escala de cada eixo vertical independentemente dos demais.
- **Agrupamento de eixos verticais por unidades** : Permite agrupar a escala das curvas e eixos verticais de acordo com sua unidade. Todas as curvas com a mesma unidade terão a mesma escala.
- **Opacidade das curvas sob o mouse** : Permite-lhe desactivar o realce da curva quando é apresentado um valor no cursor do rato. Por exemplo, quando duas curvas não têm seus valores ao mesmo tempo.

Sob as curvas, você também pode usar o menu contextual em cada legenda para isolar uma curva, exibir / ocultar seu eixo, alterar sua cor, ...

### Gráfico em vistas e desenhos

Você também pode exibir os gráficos nas visualizações (veremos aqui as opções de configuração e não como fazê-lo, para isso, é necessário ir à documentação das visualizações ou desenhos em função). Aqui estão as opções :

Depois que os dados são ativados, você pode escolher :
- **Cor** : A cor da curva.
- **Tipo** : O tipo de gráfico (área, linha ou coluna).
- **Escada** : Como você pode colocar várias curvas (dados) no mesmo gráfico, é possível distinguir as escalas (direita ou esquerda)).
- **Escadaria** : Exibe a curva como uma escada ou uma exibição contínua.
- **Pilha** : Empilhe os valores das curvas (veja abaixo o resultado).
- **Variação** : Exibe a diferença de valor do ponto anterior.

### Opção na página do histórico

A página de histórico fornece acesso a algumas opções adicionais

#### História calculado

Permite exibir uma curva de acordo com um cálculo em vários comandos (você pode fazer praticamente qualquer coisa, + - / \* valor absoluto ... consulte a documentação do PHP para algumas funções). Por exemplo :

`abs(*\ [Jardim \] \ [Higrometria \] \ [Temperatura \]* - *\ [Espaço vital]] [Higrometria \] \ [Temperatura \]*)`

Você também tem acesso a um gerenciamento de fórmulas de cálculo que permite salvá-las para reapresentá-las mais facilmente.

> **Dica**
>
> Depois de salvar os cálculos, eles estão disponíveis à esquerda em **Meus cálculos**.

#### Histórico de pedidos

Na frente de cada dado que pode ser exibido, você encontrará dois ícones :

- **Lata de lixo** : Permite excluir os dados gravados; ao clicar, o Jeedom pergunta se deseja excluir os dados antes de uma determinada data ou todos os dados.
- **Arrow** : Permite a exportação CSV de dados históricos.

### Remoção de valor inconsistente

Às vezes você pode ter valores inconsistentes nos gráficos. Isso geralmente ocorre devido à preocupação em interpretar o valor. É possível excluir ou alterar o valor do ponto em questão, clicando nele diretamente no gráfico; Além disso, você pode ajustar o mínimo e o máximo permitido para evitar problemas futuros.


