O **Objetos** permitem definir a estrutura em árvore da sua automação residencial.
Todo o equipamento que você cria deve pertencer a um objeto e
será mais facilmente identificável. Dizemos então que o objeto
é o **Pai** equipamento. O gerenciamento de objetos está acessível
do menu **Ferramentas → Objetos**.

Para dar livre escolha à personalização, você pode nomear esses
objetos como você deseja. Geralmente, definiremos os diferentes
partes de sua casa, como os nomes dos quartos (isto é
Configuração recomendada).

Gestão 
=======

Você tem duas opções :

-   **Adicionar** : Crie um novo objeto.

-   **Visão global** : Exibe a lista de objetos criados
    bem como sua configuração.

Meus objetos 
==========

Depois de criar um objeto, ele aparecerá nesta parte.

Guia Objeto 
------------

Ao clicar em um objeto, você acessa sua página de configuração. O que
quaisquer que sejam as alterações feitas, não esqueça de salvar no
fin.

Aqui estão as diferentes características para configurar um objeto :

-   **Nome do objeto** : O nome do seu objeto.

-   **Pai** : Indica o pai do objeto atual, isso permite
    definir uma hierarquia entre objetos. Por exemplo : A sala tem
    ser pai do apartamento. Um objeto pode ter apenas um pai
    mas vários objetos podem ter o mesmo pai.

-   **Visivél** : Marque esta caixa para tornar este objeto visível.

-   **Esconder o painel de instrumentos** : Marque esta caixa para ocultar
    o objeto no painel. Ainda é mantido no
    lista, que permite exibi-lo, mas apenas
    explicitamente.

-   **ícone** : Permite escolher um ícone para o seu objeto.

-   **Cor tag** : Permite escolher a cor do objeto e
    equipamento ligado a ele.

-   **Texto tag Cor** : Permite escolher a cor do texto
    do objeto. Este texto estará sobre o **Cor tag**. Para você
    escolher uma cor para tornar o texto legível.

-   **Cor do texto de resumo** : Permite escolher a cor do
    resultados do resumo do objeto no painel.

-   **Tamanho no painel (1 a 12))** : Permite definir a largura
    a exibição desse objeto no painel. Por exemplo : Se você
    coloque `6` em dois objetos consecutivos na lista, então
    estarão lado a lado no painel. Se você colocar 3 a 4
    objetos que se seguem, eles também estarão lado a lado.

> **Dica**
>
> Você pode alterar a ordem de exibição dos objetos no Painel.
> No menu, à esquerda da sua página, use as setas verticais
> arraste e solte para dar a eles um novo lugar.

> **Dica**
>
> Você pode ver um gráfico representando todos os elementos do Jeedom
> anexado a este objeto clicando no botão **Conexões**, em às
> Direito.

> **Dica**
>
> Quando um dispositivo é criado e nenhum pai foi definido, ele
> terá como pai : **Nemhum** .

Guia Resumo 
-------------

Resumos são informações globais, atribuídas a um objeto, que
são exibidos em particular no painel ao lado do nome do último.

### Quadro de avisos 

As colunas representam os resumos atribuídos ao objeto atual. Três
linhas são propostas para você :

-   **-Se no resumo geral** : Marque a caixa se você
    deseja que o resumo seja exibido na barra de menus
    de Jeedom.

-   **Esconder no ambiente de trabalho** : Marque a caixa se você não quiser
    o resumo é exibido ao lado do nome do objeto no painel.

-   **Esconder móvel** : Marque a caixa se você não quiser
    o resumo é exibido quando você o exibe de um celular.

### Comandos 

Cada guia representa um tipo de resumo definido na configuração
de Jeedom. Clique em **Adicionar comando** para que seja
levado em consideração no resumo. Você tem a opção de selecionar o
encomendar qualquer equipamento Jeedom, mesmo que não seja para
pai deste objeto.

> **Dica**
>
> Se você deseja adicionar um tipo de resumo ou configurar o
> método de cálculo do resultado, a unidade, o ícone e o nome de um resumo,
> você tem que ir para a configuração geral do Jeedom :
> **Administração → Configuração → Guia Resumos**.

Visão global 
==============

A visão geral permite visualizar todos os objetos em
Jeedom, bem como sua configuração :

-   **ID** : Object ID.

-   **Objeto** : Nome do objeto.

-   **Pai** : Nome do objeto pai.

-   **Visivél** : Visibilidade do objeto.

-   **Mascarado** : Indica se o objeto está oculto no painel.

-   **Resumo definido** : Indica o número de pedidos por resumo. Isto
    que está em azul é levado em consideração no resumo global.

-   **Resumo do painel oculto** : Mostrar resumos ocultos em
    O painel.

-   **Resumo para celular oculto** : Mostrar resumos ocultos em
    o celular.


