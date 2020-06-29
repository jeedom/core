Descrição 
===========

Esta página permite reunir em uma única página os diferentes
elementos configurados em seu Jeedom. Também dá acesso a
funções de organização de equipamentos e controles, a seu
configuração avançada, bem como possibilidades de configuração
d'affichage.

Esta página é acessível por **Ferramentas → Resumo da automação residencial**.

O topo da página 
------------------

No topo da página, encontramos : \* **Número de objetos** : Nombre
total de objetos configurados em nosso Jeedom, contando os elementos
inativo. \* **Número de equipamentos** : O mesmo vale para o equipamento. \*
**Número de pedidos** : O mesmo vale para pedidos. \* **Inativo** :
Marque esta caixa se desejar que os itens inativos estejam bem
exibido nesta página. \* **Pesquisa** : Procure um
elemento particular. Pode ser o nome de um equipamento, um pedido
ou o nome do plugin pelo qual o equipamento foi criado.

Você também tem um botão "Histórico de exclusões", que permite exibir o histórico de pedidos, equipamentos, objetos, visualização, design, design 3d, cenário e usuário excluído.

Quadros de objetos 
----------------

Abaixo há um quadro por objeto. Em cada quadro, encontramos
a lista de equipamentos (em azul) que têm esse objeto como pai. O
primeiro quadro **Nemhum** representa equipamento que não possui
pai afetado. Para cada objeto, próximo ao seu rótulo, três botões
estão disponíveis. Da esquerda para a direita :

-   O primeiro é usado para abrir a página de configuração do objeto em um
    nova aba,

-   O segundo fornece algumas informações sobre o objeto,

-   o último permite exibir ou ocultar a lista de equipamentos
    atribuído a ele.

> **Dica**
>
> A cor de fundo dos quadros de objetos depende da cor escolhida em
> configuração de objeto.

> **Dica**
>
> Ao clicar / soltar no equipamento, você pode alterar sua
> encomendar ou mesmo atribuí-los a outro objeto. É da ordem
> estabelecido nesta página que a exibição do painel é calculada.

Os equipamentos 
---------------

Em cada equipamento encontramos :

-   Um **caixa de seleção** para selecionar o equipamento (você pode
    selecione vários). Se pelo menos um dispositivo estiver selecionado
    você tem botões de ação que aparecem no canto superior esquerdo
    PARA **Remover**, devolver **Visivél**/**Invisível**,
    **Ativos**/**Inativo** equipamento selecionado.

-   O **Nome** equipamento.

-   O **Tipo** equipamento : Identificador do plug-in ao qual
    pertence.

-   **Inativo** (pequena cruz) : Significa que o equipamento está inativo
    (se não estiver lá, o equipamento está ativo).

-   **Invisível** (olho cruzado) : Significa que o equipamento está invisível
    (se não estiver lá, o equipamento é visível).

-   **Link externo** (quadrado com flecha) : Permite abrir em um
    nova aba a página de configuração do equipamento.

-   **Configuração avançada** (roda dentada) : abre o
    janela de configuração avançada de equipamentos.

-   **Lista de comandos** (a flecha) : permite expandir a lista de
    comandos (em fundo laranja).

Se você expandir a lista de comandos, cada bloco laranja corresponderá a
um pedido do seu equipamento (um novo clique na pequena seta
equipamento pode escondê-los).

Se você clicar duas vezes no pedido ou clicar no pequeno
roda dentada isso abrirá sua janela de configuração.

Configuração avançada de equipamentos 
=====================================

> **Dica**
>
> É possível acessar (se o plugin o suportar) diretamente para
> nesta janela da página de configuração do equipamento em
> clicando no botão de configuração avançada

A janela de **Configuração avançada de equipamentos** permite que o
modificar. Primeiro, no canto superior direito, alguns botões
disponível :

-   **Conexões** : Exibe os links do equipamento com o
    objetos, comandos, cenários, variáveis, interações ... na forma
    gráfico (neste, um clique duplo em um elemento leva você a
    sua configuração).

-   **Log** : exibe os eventos do equipamento em questão.

-   **Informação** : exibe as propriedades brutas do equipamento.

-   **Registro** : Salve as modificações feitas
    no equipamento.

-   **Remover** : Remova o equipamento.

Guia Informações 
-------------------

A guia **Informação** contém as informações gerais de
o equipamento e seus controles :

-   **ID** : Identificador exclusivo no banco de dados Jeedom.

-   **Nome** : Nome de equipamentos.

-   **ID lógico** : Identificador de equipamento lógico (pode
    estar vazio).

-   **Object ID** : Identificador exclusivo do objeto pai (pode
    estar vazio).

-   **Data de criação** : Data de criação do equipamento.

-   **Ativar** : Marque a caixa para ativar o equipamento (não esqueça
    salvar).

-   **Visivél** : Marque a caixa para tornar o equipamento visível (sem
    esqueça de salvar).

-   **Tipo** : Identificador do plug-in pelo qual ele foi criado.

-   **Tentativa fracassada** : Número de tentativas de comunicação
    consecutivo com falha do equipamento.

-   **Data da última chamada** : Data da última
    comunicação de equipamentos.

-   **Última atualização** : Data da última chamada
    com equipamento.

-   **Etiquetas** : etiquetas de equipamento, a serem separadas por ','. Ele permite que o painel faça filtros personalizados

Abaixo, você encontrará uma tabela com a lista de comandos para
o equipamento com, para cada um, um link para sua configuração.

Guia Exibir 
----------------

Na aba **Visualizando**, você poderá configurar alguns
exibir o comportamento do bloco no painel, as visualizações, o
design, bem como móvel.

### Widget 

-   **Visivél** : Marque a caixa para tornar o equipamento visível.

-   **Mostrar nome** : Marque a caixa para exibir o nome de
    equipamento na telha.

-   **Do nome de exibição do objeto** : Marque a caixa para exibir o nome
    do objeto pai do equipamento, próximo ao bloco.

-   **Cor de fundo** : Marque a caixa para manter a cor de fundo
    por padrão (dependendo do **Categoria** do seu equipamento, consulte
    **Administração → Configuração → Cores**). Se você desmarcar isso
    caixa, você pode escolher outra cor. Você também pode
    marque uma nova caixa **Transparente** fazer o
    Fundo transparente.

-   **Opacidade** : Opacidade da cor de fundo do bloco.

-   **Cor do texto** : Marque a caixa para manter a cor do
    texto padrão.

-   **Fronteiras** : Marque a caixa para manter a borda padrão.
    Caso contrário, você deve colocar o código CSS, a propriedade `border` (ex :
    `3px blue tracejado` para uma borda pontilhada de 3px em azul).

-   **Arestas arredondadas** (em px) : Marque a caixa para manter
    o arredondamento padrão. Caso contrário, você deve colocar o código CSS, propriedade
    "raio da borda" (ex : ``10px``)

### Parâmetros opcionais na telha 

Abaixo, encontramos parâmetros opcionais de exibição que
pode aplicar-se ao equipamento. Esses parâmetros são compostos por um nome e
vale a pena. Basta clicar em **Adicionar** aplicar um
novo. Para equipamentos, apenas o valor **estilo** é para o
momento utilizado, permite inserir código CSS no equipamento em
question.

> **Dica**
>
> Não se esqueça de salvar após qualquer modificação.

Guia Layout 
------------------

Esta parte permite que você escolha entre o arranjo padrão de
comandos (lado a lado no widget) ou no modo de tabela. Existe
nada para definir no modo padrão. Aqui estão as opções disponíveis no modo
**Mesa** :

-   **Número de linhas**

-   **Número de Colunas**

-   **Centro nas caixas** : Marque a caixa para centralizar o
    comandos nas caixas.

-   **Estilo geral de caixas (CSS)** : Permite definir o estilo
    geral no código CSS.

-   **Estilo de tabela (CSS)** : Permite definir o estilo do
    apenas mesa.

Abaixo para cada caixa, o **Configuração detalhada** permite que você
isto :

-   **Caixa de texto** : Adicione texto além do comando (ou
    sozinho, se não houver ordem na caixa).

-   **Estilo do caso (CSS)** : Altere o estilo CSS específico do
    caixa (cuidado, este substitui e substitui o CSS geral
    caixas).

> **Dica**
>
> Em uma caixa na tabela, se você quiser colocar 2 comandos, um em
> abaixo do outro, não se esqueça de adicionar um retorno ao
> linha após a estréia no **Configuração avançada** disso.

Guia Alertas 
--------------

Esta guia fornece informações sobre a bateria do
equipamento e definir alertas em relação a ele. Aqui estão os
tipos de informações que podem ser encontradas :

-   **Tipo de bateria**,

-   **Comentários mais recentes**,

-   **Nível restante**, (se é claro que seu equipamento funciona
    na bateria).

Abaixo, você também pode definir limites de alerta específicos para
bateria para este equipamento. Se você deixar as caixas vazias, elas serão
os limites padrão que serão aplicados.

Você também pode gerenciar o tempo limite, em minutos, do equipamento. Por
Por exemplo, 30 informa a jeedom que se o equipamento não tiver se comunicado
por 30 minutos, então você deve colocá-lo em alerta.

> **Dica**
>
> Os parâmetros globais estão em **Administração → Configuração → Logs**
> (onde **Instalações**)

Guia de comentários 
------------------

Permite que você escreva um comentário sobre o equipamento (data de
trocar a bateria, por exemplo).

Configuração avançada de um pedido 
====================================

Primeiro, no canto superior direito, alguns botões disponíveis :

-   **Teste** : Permite testar o comando.

-   **Conexões** : Exibe os links do equipamento com o
    objetos, comandos, cenários, variáveis, interações…. sob
    forma gráfica.

-   **Log** : Exibe os eventos do equipamento em questão.

-   **Informação** : Exibe as propriedades brutas do equipamento.

-   Aplicar a \* : Aplique a mesma configuração em
    pedidos múltiplos.

-   **Registro** : Salve as alterações feitas em
    o equipamento

> **Dica**
>
> Em um gráfico, um clique duplo em um elemento leva você ao seu
> Configuração.

> **NOTA**
>
> Dependendo do tipo de pedido, as informações / ações exibidas
> pode mudar.

Guia Informações 
-------------------

A guia **Informação** contém informações gerais sobre o
Ordem :

-   **ID** : Identificador exclusivo no banco de dados.

-   **ID lógico** : Identificador lógico do comando (pode
    estar vazio).

-   **Nome** : Nome do comando.

-   **Tipo** : Tipo de comando (ação ou informação).

-   **Subtipo** : Subtipo de comando (binário, digital etc.)).

-   **URL direta** : Fornece o URL para acessar este equipamento. (clique
    (copie o endereço do link) A URL iniciará o comando para um
    **Ação** e retorne as informações para um **Informações**.

-   **Unidade** : Unidade de controle.

-   **Comando provocando uma atualização** : Dá o identificador de um
    outro comando que, se esse outro comando for alterado, forçará o
    atualização da ordem exibida.

-   **Visivél** : Marque esta caixa para tornar o comando visível.

-   **Siga na Timeline** : Marque esta caixa para ter esta
    O comando é visível na linha do tempo quando é usado.

-   **Proibir a interação automática em** : proíbe-os
    interações automáticas neste comando

-   **ícone** : Permite alterar o ícone do comando.

Você também tem três outros botões laranja embaixo :

-   **Este comando substitui o ID** : Substitua um ID de
    ordem pela ordem em questão. Útil se você excluiu um
    equipamento em Jeedom e você tem cenários que usam
    comandos a partir dele.

-   **Este comando substitui o comando** : Substitua um pedido por
    o comando atual.

-   **Substituir esse comando pelo comando** : O reverso substitui
    a ordem por outra ordem.

> **NOTA**
>
> Esse tipo de ação substitui comandos em todo o Jeedom
> (cenário, interação, comando, equipamento.)

Abaixo, você encontrará a lista de diferentes equipamentos,
comandos, cenários ou interações que usam este comando. Um
clique nele para ir diretamente à sua configuração
respective.

Guia Configuração 
--------------------

### Para um pedido de tipo de informação : 

-   **Cálculo e arredondamento**

    -   **Fórmula de cálculo (\#value\# por valor)** : Deixa
        faça uma operação sobre o valor do pedido antes
        Tratamento Jeedom, exemplo : ``#value# - 0.2` para entrincheirar
        0,2 (deslocamento em um sensor de temperatura).

    -   **Arredondado (número após ponto decimal)** : Permite arredondar o
        valor do pedido (exemplo : colocar 2 para transformar
        16.643345 em 16,64).

-   **Tipo genérico** : Permite configurar o tipo genérico do
    (o Jeedom tenta encontrá-lo sozinho no modo automático).
    Esta informação é usada pelo aplicativo móvel.

-   **Ação em valor, se** : Vamos fazer tipos de
    mini cenários. Você pode, por exemplo, dizer que se o valor vale a pena
    mais de 50 por 3 minutos, você deve executar uma ação dessas. Que
    permite, por exemplo, apagar uma luz X minutos depois
    está aceso.

-   **Histórico**

    -   **Historicizar** : Marque a caixa para ter os valores para este
        pedido seja gravado. (Veja **Análise → História**)

    -   **Suavização moda** : Modo de **alisamento** ou d'**arquivamento**
        permite escolher como arquivar os dados. Por padrão,
        é um **Média**. Também é possível escolher o
        **Máximo**, O **Mínimo**, onde **Nemhum**. **Nemhum** deixa
        diga ao Jeedom que ele não deve arquivar neste
        ordem (durante o primeiro período de 5 minutos e com o
        tarefa de arquivamento). Essa opção é perigosa porque o Jeedom
        mantenha tudo : então haverá muito mais
        dados armazenados.

    -   **Limpar histórico se tiver mais de** : Vamos dizer para
        Jeedom para excluir todos os dados anteriores a um
        certo período. Pode ser útil para não manter
        dados, se não for necessário e, portanto, limitar a quantidade
        de informações registradas por Jeedom.

-   **Gerenciando valores**

    -   **Valor proibido** : Se o comando pegar um desses valores,
        Jeedom o ignora antes de aplicá-lo.

    -   **Valor de feedback estado** : Retorna o comando para
        esse valor depois de um tempo.

    -   **Duração antes do retorno do status (min)** : Tempo antes de voltar para
        valor acima.

-   **Outro**

    -   **Gestão de valores repetidos** : Em automático se o
        comando sobe duas vezes o mesmo valor em uma linha e, em seguida, Jeedom
        não levará em consideração a 2ª subida (evite desencadear
        várias vezes em um cenário, a menos que o comando seja
        tipo binário). Você pode forçar o valor a repetir ou
        bani-lo completamente.

    -   **URL impulso** : Permite adicionar um URL para chamar em caso de
        atualização do pedido. Você pode usar tags
        Seguinte : ``#value#`para o valor do pedido '#cmd_name#``
        para o nome do comando, `#cmd_id#`para o identificador exclusivo
        do comando, `#humanname#`para o nome completo do comando
        (ex : ``#[SalO de bain][Hydrometrie][Humidité]#`),`#eq_name#`para o nome do equipamento

### Para um comando de ação : 

-   **Tipo genérico** : Permite configurar o tipo genérico do
    (o Jeedom tenta encontrá-lo sozinho no modo automático).
    Esta informação é usada pelo aplicativo móvel.

-   **Confirmar a ação** : Marque esta caixa para o Jeedom solicitar
    confirmação quando a ação é iniciada a partir da interface
    deste comando.

-   **Código de acesso** : Permite definir um código que o Jeedom solicitará
    quando a ação é iniciada a partir da interface deste comando.

-   **Ação antes de executar o comando** : Permite adicionar
    Comandos **antes** cada execução da ordem.

-   **Ação após a execução da ordem** : Permite adicionar
    Comandos **depois** cada execução da ordem.

Guia Alertas 
--------------

Permite definir um nível de alerta (**Aviso** onde **Perigo**) en
dependendo de certas condições. Por exemplo, se `valor> 8` para 30
minutos, então o equipamento pode ficar em alerta **Aviso**.

> **NOTA**
>
> Na página **Administração → Configuração → Logs**, Você pode
> configure um comando de tipo de mensagem que permitirá que o Jeedom o obtenha
> avise se o limite de aviso ou perigo for atingido.

Guia Exibir 
----------------

Nesta parte, você poderá configurar certos comportamentos
exibição do widget no painel, visualizações, design e
mobile.

-   **Widget** : Permite escolher o widget no dekstop ou no celular (em
    Observe que você precisa do plug-in do widget e também pode fazer isso
    a partir dele).

-   **Visivél** : Marque para tornar o comando visível.

-   **Mostrar nome** : Marque para fazer o nome do
    comando, dependendo do contexto.

-   **Imprimir o nome eo ícone** : Marque para tornar o ícone visível
    além do nome do comando.

-   **Envoltório forçado antes Widget** : Tick **antes
    Widget** onde **após o widget** para adicionar uma quebra de linha
    antes ou depois do widget (para forçar, por exemplo, uma exibição em
    coluna de comandos de equipamentos diferentes em vez de linhas
    Por padrão)

Abaixo, encontramos parâmetros opcionais de exibição que
pode mudar para o widget. Esses parâmetros dependem do widget em questão,
então você tem que olhar para o cartão dele no mercado para conhecê-los.

> **Dica**
>
> Não se esqueça de salvar após qualquer modificação.

Guia Código 
-----------

Permite modificar o código do widget apenas para o comando atual.

> **NOTA**
>
> Se você deseja modificar o código, não esqueça de marcar a caixa
> **Ativar personalização do widget**
