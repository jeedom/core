Parte importante no software : a parte da historização, real
lembrança disso. É possível em Jeedom historiar qualquer
qual comando do tipo de informação (binário ou digital). Que você
permitirá, por exemplo, histórico de uma curva de temperatura,
aberturas de consumo ou porta

Princípio 
========

Aqui é descrito o princípio de historicização de Jeedom. Não é
É necessário entender que, se você tiver alguma dúvida
ou deseja alterar as configurações de
historização. As configurações padrão são adequadas para a maioria
cas.

Arquivamento 
---------

O arquivamento de dados permite que o Jeedom reduza a quantidade de dados
mantido na memória. Isso permite não usar muito espaço e
para não desacelerar o sistema. De fato, se você mantiver todo o
medidas, isso mostra mais pontos a serem exibidos e, portanto, pode
aumentar drasticamente os tempos para criar um gráfico. Caso
muitos pontos, pode até travar
exibição de gráfico.

O arquivamento é uma tarefa que começa à noite e compacta
dados recuperados durante o dia. Por padrão, o Jeedom recupera tudo
Dados 2h mais antigos e cria pacotes de 1h (um
média, mínima ou máxima, dependendo das configurações). Então nós temos
aqui 2 parâmetros, um para o tamanho do pacote e outro para saber
quando fazê-lo (por padrão, são pacotes
1 hora com dados com mais de 2 horas de antiguidade).

> **Dica**
>
> Se você seguiu bem, deve ter uma alta precisão no
> Apenas nas últimas 2 horas. No entanto, quando eu entro às 17h,
> Eu tenho um esclarecimento nas últimas 17 horas. Porque ? De fato,
> para evitar consumir recursos desnecessariamente, a tarefa que torna
> o arquivamento ocorre apenas uma vez por dia, à noite.

> **IMPORTANTE**
>
> Obviamente, esse princípio de arquivamento se aplica apenas a pedidos de
> tipo digital; nos comandos do tipo binário, o Jeedom não mantém
> que as datas de mudança de estado.

Visualizando um Gráfico 
========================

Existem várias maneiras de acessar o histórico :

-   Colocando uma área gráfica em uma visualização (veja abaixo),

-   Clicando no comando desejado em um widget,

-   indo para a página de histórico que permite sobrepor
    diferentes curvas e combinar estilos (área, curva, barra)

-   No celular, enquanto permanece pressionado no widget em questão

Se você exibir um gráfico pela página histórica ou clicando em
o widget, você tem acesso a várias opções de exibição :

Encontramos no canto superior direito o período de exibição (aqui no último
semana porque, por padrão, quero que seja apenas uma semana - veja
2 parágrafos acima), então vêm os parâmetros da curva
(esses parâmetros são mantidos de uma exibição para outra; então você não
do que configurá-los uma vez).

-   **Escada** : exibe a curva como um
    escada ou exibição contínua.

-   **Mudança** : exibe a diferença no valor de
    ponto anterior.

-   **Linha** : Exibe o gráfico como linhas.

-   **área** : Exibe o gráfico como uma área.

-   **Coluna**\* : Exibe o gráfico como barras.

Gráfico em vistas e desenhos 
=====================================

Você também pode exibir os gráficos nas visualizações (veremos aqui
as opções de configuração e não como fazê-lo, para fazer isso
renderizar visualizações ou designs com base na documentação). Aqui está
as opções :

Depois que os dados são ativados, você pode escolher :

-   **Cor** : A cor da curva.

-   **Tipo** : O tipo de gráfico (área, linha ou coluna).

-   **Escala** : já que você pode colocar várias curvas (dados)
    no mesmo gráfico, é possível distinguir as escalas
    (direita ou esquerda).

-   **Escada** : exibe a curva como um
    escada ou exibição contínua

-   **Pilha** : permite empilhar os valores das curvas (veja em
    abaixo para o resultado).

-   **Mudança** : exibe a diferença no valor de
    ponto anterior.

Opção na página do histórico 
===============================

A página de histórico fornece acesso a algumas opções adicionais

História calculado 
------------------

Usado para exibir uma curva com base em um cálculo em vários
comando (você pode praticamente fazer tudo, + - / \* valor absoluto ... veja
Documentação do PHP para determinadas funções). Ex :
abs(*\ [Jardim \] \ [Higrometria \] \ [Temperatura \]* - *\ [Espaço de
vida \] \ [Higrometria \] \ [Temperatura \]*)

Você também tem acesso a um gerenciamento de fórmulas de cálculo que permite
salve-os para facilitar a visualização

> **Dica**
>
> Basta clicar no nome do objeto para desdobrar;
> aparecem os comandos históricos que podem ser representados graficamente.

Histórico de pedidos 
----------------------

Na frente de cada dado que pode ser representado graficamente, você encontrará dois ícones :

-   **Lixeira** : permite excluir os dados gravados; quando
    clique, Jeedom pergunta se é necessário excluir os dados antes de um
    determinada data ou todos os dados.

-   **Arrow** : Permite a exportação CSV de dados históricos.

Remoção de valor inconsistente 
=================================

Às vezes você pode ter valores inconsistentes no
gráficos. Isso geralmente ocorre devido a uma preocupação com a interpretação do
valor. É possível excluir ou alterar o valor do ponto pressionando
pergunta, clicando diretamente no gráfico; de
Além disso, você pode definir o mínimo e o máximo permitido para
evitar problemas futuros.

Cronograma 
========

A linha do tempo exibe certos eventos em sua automação residencial no formato
chronologique.

Para vê-los, você deve primeiro ativar o rastreamento na linha do tempo de
comandos ou cenários desejados :

-   **Cenas** : diretamente na página do cenário ou no
    página de resumo do cenário para fazê-lo em "massa"

-   **Ordem** : na configuração avançada do comando,
    seja na configuração da história para fazê-lo em "massa"

> **Dica**
>
> Você tem acesso às janelas de resumo dos cenários ou do
> configuração do histórico diretamente da página
> Cronograma.

Depois de ativar o rastreamento na linha do tempo do pedido e
cenários desejados, você pode vê-los aparecer na linha do tempo.

> **IMPORTANTE**
>
> Você precisa aguardar novos eventos após ativar o rastreamento
> na linha do tempo antes de vê-los aparecer.

Os cartões na linha do tempo exibem :

-   **Comando de ação** : em fundo vermelho, um ícone à direita permite
    exibe a janela de configuração avançada do comando

-   **Comando Info** : em fundo azul, um ícone à direita permite
    exibe a janela de configuração avançada do comando

-   **Cenas** : em fundo cinza, você tem 2 ícones : um para exibir
    o log do cenário e um para ir para o cenário


