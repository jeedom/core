# Análise de equipamentos
**Análise → Equipamento**

A página Análise de equipamento permite visualizar muitas informações relacionadas ao equipamento de forma centralizada :

- O estado das suas baterias
- Módulos em alerta
- Ações definidas
- Alertas definidos
- Ordens órfãs

## Guia Pilhas


Você pode ver nesta guia a lista dos módulos de bateria, o nível restante (a cor do ladrilho depende desse nível), o tipo e o número de baterias que devem ser colocadas no módulo, o tipo de módulo também que a data em que as informações sobre o nível da bateria foram atualizadas. Você também pode ver se um limite específico foi definido para o módulo específico (representado por uma mão)

> **Dica**
>
> Os limites de alerta / aviso nos níveis da bateria podem ser configurados globalmente na configuração do Jeedom (Configurações → Sistemas → Configuração : Equipamento) ou por equipamento na página de configuração avançada na guia alertas.

## Módulos na guia de alerta

Nesta guia, você verá em tempo real os módulos em alerta. Os alertas podem ser de diferentes tipos :

- Tempo limite (configurado na guia de alertas definidos).
- Bateria em aviso ou em perigo.
- Comando de aviso ou perigo (configurável em parâmetros de comando avançados).

Outros tipos de alertas podem ser encontrados aqui.
Cada alerta será representado pela cor do bloco (o nível de alerta) e um logotipo no canto superior esquerdo (o tipo de alerta).

> **Dica**
>
> Aqui serão exibidos todos os módulos em alerta, mesmo aqueles configurados em "não visível"". No entanto, é interessante notar que, se o módulo estiver "visível", o alerta também será visível no painel (no objeto em questão)).

## Guia Ações definidas

Essa guia permite visualizar as ações definidas diretamente em um pedido. De fato, podemos colocar comandos diferentes e pode ser difícil lembrar de tudo. Essa guia existe para isso e sintetiza várias coisas :

- Ações no status (encontradas nos parâmetros avançados dos comandos info e permitindo que uma ou mais ações sejam executadas no valor de um pedido - imediatamente ou após um atraso).
- Confirmações de ações (configuráveis no mesmo local em um comando info e permitindo solicitar uma confirmação para executar uma ação).
- Confirmações com código (o mesmo que acima, mas com a inserção de um código).
- Ações pré e pós (sempre configuráveis no mesmo local em um comando de ação e permitindo executar uma ou mais outras ações antes ou depois da ação em questão).

> **Dica**
>
> A tabela fornece uma visão muito textual das ações definidas. Outros tipos de ações definidas podem ser adicionados.

## Guia Alertas Definidos

Essa guia permite ver todos os alertas definidos; você encontrará em uma tabela as seguintes informações, se existirem :

- Alertas de atraso de comunicação.
- Os limites específicos da bateria definidos em um dispositivo.
- Os vários alertas de perigo e comandos de aviso.

## Guia Pedidos Órfãos

Essa guia permite ver rapidamente se você possui comandos órfãos usados pelo Jeedom. Um comando órfão é um comando usado em algum lugar, mas que não existe mais. Vamos encontrar aqui todos esses comandos, como por exemplo :

- Comandos órfãos usados no corpo de um cenário.
- aqueles usados para desencadear um cenário.

E usado em muitos outros lugares como (não exaustivo) :
- Interações.
- Configurações do Jeedom.
- Na ação anterior ou posterior a um pedido.
- Em ação no status do pedido.
- Em alguns plugins.

> **Dica**
>
> A tabela fornece uma visão muito textual dos comandos órfãos. Seu objetivo é ser capaz de identificar rapidamente todos os pedidos "órfãos" através de todos os plugins Jeedom e. Pode ser que algumas áreas não sejam analisadas; a tabela será cada vez mais exaustiva ao longo do tempo.
