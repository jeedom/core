A página Análise de equipamento permite que você veja muitas informações
relacionados ao equipamento centralmente :

-   O estado das suas baterias

-   Módulos em alerta

-   Ações definidas

-   Alertas definidos

-   Ordens órfãs

A guia Baterias 
==================

Você pode ver nesta guia a lista de seus módulos na bateria,
o nível restante (a cor do ladrilho depende desse nível), a
tipo e número de baterias a serem inseridas no módulo, o tipo de
módulo, bem como a data em que as informações sobre o nível da bateria
foi atualizado. Você também pode ver se um limite específico foi
ambiente de trabalho para o módulo específico (representado por uma mão)

> **Dica**
>
> Os limites de alerta / aviso nos níveis da bateria são
> globalmente configurável na configuração Jeedom
> (Administração → guia Equipamento) ou por equipamento na página
> configuração avançada destes na guia alertas.

Módulos na guia de alerta 
==========================

Nesta guia, você verá em tempo real os módulos em alerta. O
alertas podem ser de diferentes tipos :

-   Tempo limite (configurado na guia de alertas definidos)

-   Bateria em aviso ou em perigo

-   comando em aviso ou perigo (configurável nos parâmetros
    comandos avançados)

Outros tipos de alertas podem ser encontrados aqui.
Cada alerta será representado pela cor do bloco (o nível
alerta) e um logotipo no canto superior esquerdo (o tipo de alerta)

> **Dica**
>
> Aqui serão exibidos todos os módulos em alerta, mesmo aqueles configurados em
> "Não visível". No entanto, é interessante notar que, se o módulo
> é "visível", o alerta também será visível no painel (em
> o objeto em questão)

A guia Ações definidas 
=========================

Essa guia permite visualizar as ações definidas diretamente em um
comando De fato, podemos fazer pedidos diferentes e
pode ser difícil lembrar tudo. Essa guia existe para isso
e sintetiza várias coisas :

-   ações no estado (encontradas em parâmetros avançados
    comandos info e usados para executar um ou mais
    ações sobre o valor de um pedido - imediatamente ou após
    um atraso)

-   confirmações de ação (configuráveis no mesmo local em um
    informações de comando e permitindo solicitar uma confirmação para
    executar uma ação)

-   confirmações com código (o mesmo que acima, mas com
    digitando um código)

-   ações pré e pós (sempre configuráveis no mesmo local no
    um comando de ação e permitindo executar um ou mais outros
    ações antes ou depois da ação em questão)

> **Dica**
>
> A tabela permite ver muito textualmente as ações
> definido. Outros tipos de ações definidas podem ser adicionados.

A guia Alertas definidos 
=========================

Essa guia permite que você veja todos os alertas definidos,
encontre em uma tabela as seguintes informações, se existirem :

-   Alertas de atraso de comunicação

-   Os limites específicos da bateria definidos em um dispositivo

-   Os vários alertas de perigo e comandos de aviso

A guia Ordens Órfãs 
=============================

Essa guia permite ver rapidamente se você tem alguma
comandos órfãos usados pelo Jeedom. Um pedido
órfão é um comando usado em algum lugar, mas que não existe mais.
Vamos encontrar aqui todos esses comandos, como por exemplo :

-   Comandos órfãos usados no corpo de um cenário

-   aqueles usados para desencadear um cenário

E usado em muitos outros lugares como (não exaustivo) :

-   interações

-   Configurações do Jeedom

-   Na ação anterior ou posterior a um pedido

-   Em ação no status do pedido

-   Em alguns plugins

> **Dica**
>
> A tabela fornece uma visão muito textual dos comandos
> órfãos. Seu objetivo é poder identificar rapidamente todos os
> Comandos "órfãos" em todos os plugins Jeedom e. É
> algumas áreas podem não ser analisadas, a tabela será
> ser cada vez mais exaustivo ao longo do tempo.
