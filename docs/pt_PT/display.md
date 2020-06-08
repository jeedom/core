# Resumo Automation
**Análise → Resumo da automação residencial**

Esta página permite reunir em uma única página os diferentes elementos configurados no seu Jeedom. Também fornece acesso a funções para organizar equipamentos e controles, a sua configuração avançada e a exibir possibilidades de configuração.

## Informations

No topo da página, encontramos :
- **Número de objetos** : Número total de objetos configurados em nosso Jeedom, incluindo elementos inativos.
- **Número de equipamentos** : O mesmo para o equipamento.
- **Número de pedidos** : Mesmo para pedidos.
- **Inativo** : Marque esta caixa se desejar que os itens inativos sejam exibidos nesta página.
- **Pesquisa** : Procure um item em particular. Pode ser o nome de um dispositivo, um pedido ou o nome do plug-in pelo qual o dispositivo foi criado.
- **Exportação de CSV** : Permite exportar todos os objetos, equipamentos e seus comandos para um arquivo CSV.

Você também tem uma guia **Histórico**, exibindo o histórico de pedidos, equipamentos, objetos, visualizações, design, design 3D, cenários e usuários excluídos.

## Quadros de objetos

Abaixo há um quadro por objeto. Em cada quadro, encontramos a lista de equipamentos que têm esse objeto como pai.
O primeiro quadro **Nemhum** representa dispositivos que não possuem pai atribuído.

Para cada objeto, próximo ao seu rótulo, dois botões estão disponíveis.
- O primeiro é usado para abrir a página de configuração do objeto em uma nova guia.
- O segundo fornece algumas informações sobre o objeto,

> **Dica**
>
> A cor do plano de fundo dos quadros do objeto depende da cor escolhida na configuração do objeto.

> **Dica**
>
> Com um clique e soltar nos objetos ou equipamentos, você pode alterar a ordem deles ou até atribuí-los a outro objeto. É da ordem estabelecida nesta página que a exibição do Painel é calculada.

## Os equipamentos

Em cada equipamento encontramos :

- Um **caixa de seleção** para selecionar o equipamento (você pode selecionar vários). Se pelo menos um dispositivo estiver selecionado, você terá botões de ação que aparecem no canto superior esquerdo para **Remover**, devolver **Visivél**/**Invisível**,  **Ativos**/**Inativo** equipamento selecionado.
- L'**ID** equipamento.
- O **Tipo** equipamento : Identificador do plug-in ao qual ele pertence.
- O **Nome** equipamento.
- **Inativo** (pequena cruz) : Significa que o equipamento está inativo (se não estiver lá, o equipamento está ativo).
- **Invisível** (olho cruzado) : Significa que o equipamento está invisível (se não estiver lá, o equipamento está visível).

Se o plug-in do equipamento estiver desativado, os dois ícones à direita não aparecerão:
- **Link externo** (quadrado com flecha) : Permite abrir em uma nova aba a página de configuração do equipamento.
- **Configuração avançada** (roda dentada) : abre a janela de configuração avançada do equipamento.

> Ao clicar na linha que contém o nome do equipamento, você exibirá todos os comandos para este equipamento. Ao clicar em um pedido, você acessará a janela de configuração do pedido.

## Configuração avançada de equipamentos

> **Dica**
>
> É possível acessar (se o plugin o suportar) diretamente a esta janela a partir da página de configuração do equipamento, clicando no botão de configuração avançada

A janela de **Configuração avançada de equipamentos** permite modificá-lo. Primeiro, no canto superior direito, alguns botões disponíveis :

- **Informação** : exibe as propriedades brutas do equipamento.
- **Conexões** : Permite exibir os links do equipamento com os objetos, comandos, cenários, variáveis, interações ... em forma gráfica (neste, um clique duplo em um elemento o levará à sua configuração).
- **Log** : exibe os eventos do equipamento em questão.
- **Salvar** : Salve as modificações feitas no equipamento.
- **Remover** : Remova o equipamento.

### Guia Informações

A guia **Informação** contém informações gerais sobre o equipamento e seus controles :

- **ID** : Identificador exclusivo no banco de dados Jeedom.
- **Nome** : Nome de equipamentos.
- **ID lógico** : Identificador lógico do equipamento (pode estar vazio).
- **Object ID** : Identificador exclusivo do objeto pai (pode estar vazio).
- **Data de criação** : Data de criação do equipamento.
- **Ativar** : Marque a caixa para ativar o equipamento (não esqueça de salvar).
- **Visivél** : Marque a caixa para tornar o equipamento visível (não esqueça de salvar).
- **Tipo** : Identificador do plug-in pelo qual ele foi criado.
- **Tentativa fracassada** : Número de falhas consecutivas de tentativas de comunicação com o equipamento.
- **Data da última chamada** : Data da última comunicação do equipamento.
- **Última atualização** : Data da última comunicação com o equipamento.
- **Etiquetas** : etiquetas de equipamento, a serem separadas por ','. Permite no painel criar filtros personalizados

Abaixo você encontrará uma tabela com a lista de comandos do equipamento com, para cada um, um link para sua configuração.

### Guia Exibir

Na aba **Visualizando**, você poderá configurar certos comportamentos de exibição de bloco no painel ou no celular.

#### Widget

-  **Visivél** : Marque a caixa para tornar o equipamento visível.
- **Mostrar nome** : Marque a caixa para exibir o nome do equipamento no bloco.
- **Do nome de exibição do objeto** : Marque a caixa para exibir o nome do objeto pai do equipamento, próximo ao bloco.

### Parâmetros opcionais na telha

Abaixo, há parâmetros opcionais de exibição que podem ser aplicados ao equipamento. Esses parâmetros são compostos de um nome e um valor. Basta clicar em **Adicionar** aplicar um
novo. Para equipamentos, apenas o valor **estilo** atualmente utilizado, permite inserir código CSS no equipamento em questão.

> **Dica**
>
> Não se esqueça de salvar após qualquer modificação.

### Guia Layout

Esta parte permite escolher entre o layout padrão dos comandos (lado a lado no widget) ou no modo de tabela. Nada para definir no modo padrão. Aqui estão as opções disponíveis no modo
**Mesa** :
- **Número de linhas**
- **Número de Colunas**
- **Centro nas caixas** : Marque a caixa para centralizar os pedidos nas caixas.
- **Estilo geral de caixas (CSS)** : Permite definir o estilo geral no código CSS.
- **Estilo de tabela (CSS)** : Permite definir apenas o estilo da tabela.

Abaixo para cada caixa, o **Configuração detalhada** permite que você
isto :
- **Caixa de texto** : Adicione texto além do comando (ou sozinho, se não houver comando na caixa).
- **Estilo do caso (CSS)** : Modifique o estilo CSS específico da caixa (observe que ele substitui e substitui o CSS geral das caixas).

> **Dica**
>
> Em uma caixa da tabela, se você deseja colocar 2 comandos um abaixo do outro, não se esqueça de adicionar uma quebra de linha após o primeiro no **Configuração avançada** disso.

### Guia Alertas

Essa guia permite que você tenha informações sobre a bateria do equipamento e defina alertas em relação a ele. Aqui estão os tipos de informações que podem ser encontradas :

- **Tipo de bateria**,
- **Comentários mais recentes**,
- **Nível restante**, (se é claro que seu equipamento funciona com bateria).

Abaixo, você também pode definir os limites de alerta de bateria específicos para este equipamento. Se você deixar as caixas vazias, esses serão os limites padrão que serão aplicados.

Você também pode gerenciar o tempo limite, em minutos, do equipamento. Por exemplo, 30 informa ao jeedom que se o equipamento não se comunicar por 30 minutos, ele deve ser colocado em alerta.

> **Dica**
>
> Os parâmetros globais estão em **Configurações → Sistema → Configuração : Toras** onde **Instalações**

### Guia de comentários

Permite que você escreva um comentário sobre o equipamento.

## Configuração avançada de um pedido

Primeiro, no canto superior direito, alguns botões disponíveis :

- **Teste** : Permite testar o comando.
- **Conexões** : Permite exibir os links do equipamento com objetos, comandos, cenários, variáveis, interações. em forma gráfica.
- **Log** : Exibe os eventos do equipamento em questão.
- **Informação** : Exibe as propriedades brutas do equipamento.
-  **Aplicar** : Permite que a mesma configuração seja aplicada a vários comandos.
- **Salvar** : Salve as modificações feitas no equipamento.

> **Dica**
>
> Em um gráfico, um clique duplo em um elemento leva você à sua configuração.

> **NOTA**
>
> Dependendo do tipo de pedido, as informações / ações exibidas podem mudar.

### Guia Informações

A guia **Informação** contém informações gerais sobre o pedido :

- **ID** : Identificador exclusivo no banco de dados.
- **ID lógico** : Identificador lógico do comando (pode estar vazio).
- **Nome** : Nome do comando.
- **Tipo** : Tipo de comando (ação ou informação).
- **Subtipo** : Subtipo de comando (binário, digital etc.)).
- **URL direta** : Fornece a URL para acessar este equipamento. (clique com o botão direito do mouse, copie o endereço do link) O URL iniciará o comando para um **Ação** e retorne as informações para um **Informações**.
- **Unidade** : Unidade de controle.
- **Comando provocando uma atualização** : Fornece o identificador de outro comando que, se esse outro comando for alterado, forçará a atualização do comando exibido.
- **Visivél** : Marque esta caixa para tornar o comando visível.
- **Siga na Timeline** : Marque esta caixa para tornar este comando visível na linha do tempo quando ele é usado. Você pode especificar uma linha do tempo específica no campo que aparece se a opção estiver marcada.
- **Proibir a interação automática em** : proíbe interações automáticas neste comando
- **ícone** : Permite alterar o ícone do comando.

Você também tem três outros botões laranja embaixo :

- **Este comando substitui o ID** : Permite substituir um ID do pedido pelo pedido em questão. Útil se você excluiu um equipamento no Jeedom e possui cenários que usam comandos dele.
- **Este comando substitui o comando** : Substituir comando pelo comando atual.
- **Substituir esse comando pelo comando** : Por outro lado, substitua o comando por outro comando.

> **NOTA**
>
> Esse tipo de ação substitui comandos em todos os lugares do Jeedom (cenário, interação, comando, equipamento….).

Abaixo, você encontrará a lista dos diferentes equipamentos, comandos, cenários ou interações que usam este comando. Clique nele para ir diretamente para a respectiva configuração.

### Guia Configuração

#### Para um pedido de tipo de informação :

- **Cálculo e arredondamento**
    - **Fórmula de cálculo (\#value\# por valor)** : Permite que você faça uma operação com o valor do pedido antes do processamento por Jeedom, exemplo : ``#value# - 0.2` para subtrair 0.2 (deslocamento em um sensor de temperatura).
    - **Arredondado (número após ponto decimal)** : Usado para arredondar o valor do comando (exemplo : colocar 2 para transformar 16.643.345 em 16,64).
- **Tipo genérico** : Permite configurar o tipo genérico do comando (o Jeedom tenta encontrá-lo sozinho no modo automático). Esta informação é usada pelo aplicativo móvel.
- **Ação em valor, se** : Vamos fazer algum tipo de mini cenários. Você pode, por exemplo, dizer que, se o valor vale mais de 50 por 3 minutos, você deve executar essa ação. Isso permite, por exemplo, apagar uma luz X minutos depois de acender.

- **Histórico**
    - **Historicizar** : Marque a caixa para ter os valores deste comando registrados. (Veja **Análise → História**)
    - **Suavização moda** : Modo de **alisamento** ou d'**arquivamento** permite escolher como arquivar os dados. Por padrão, este é um **Média**. Também é possível escolher o **Máximo**, O **Mínimo**, onde **Nemhum**. **Nemhum** vamos dizer ao Jeedom que ele não deve executar o arquivamento neste comando (durante o primeiro período de 5 minutos e com a tarefa de arquivamento). Essa opção é perigosa porque o Jeedom mantém tudo : portanto, haverá muito mais dados mantidos.
    - **Limpar histórico se tiver mais de** : Vamos dizer ao Jeedom para excluir todos os dados anteriores a um determinado período. Pode ser prático não manter dados se não for necessário e, portanto, limitar a quantidade de informações registradas pela Jeedom.

- **Gerenciando valores**
    - **Valor proibido** : Se o comando pegar um desses valores, o Jeedom o ignorará antes de aplicá-lo.
    - **Valor de feedback estado** : Retorna o comando para esse valor após um certo tempo.
    - **Duração antes do retorno do status (min)** : Tempo antes de retornar ao valor acima.

- **Outro**
    - **Gestão de valores repetidos** : Em automático, se o comando subir duas vezes o mesmo valor em uma linha, o Jeedom não levará em consideração a segunda subida (evita desencadear um cenário várias vezes, a menos que o comando seja do tipo binário). Você pode forçar a repetição do valor ou proibi-lo completamente.
    - **URL impulso** : Permite adicionar um URL para chamar em caso de atualização do pedido. Você pode usar as seguintes tags : ``#value#`para o valor do pedido '#cmd_name#`para o nome do comando,`#cmd_id#`para o identificador exclusivo da ordem,`#humanname#`para o nome completo do comando (ex : ``#[SalO de bain][Hydrometrie][Humidité]#`),`#eq_name#`para o nome do equipamento.

#### Para um comando de ação :

-  **Tipo genérico** : Permite configurar o tipo genérico do comando (o Jeedom tenta encontrá-lo sozinho no modo automático). Esta informação é usada pelo aplicativo móvel.
- **Confirmar a ação** : Marque esta caixa para que o Jeedom solicite confirmação quando a ação for iniciada a partir da interface deste comando.
- **Código de acesso** : Permite definir um código que o Jeedom solicitará quando a ação for iniciada a partir da interface deste comando.
- **Ação antes de executar o comando** : Adicionar comandos **antes** cada execução da ordem.
- **Ação após a execução da ordem** : Adicionar comandos **depois** cada execução da ordem.

### Guia Alertas

Permite definir um nível de alerta (**Aviso** onde **Perigo**) dependendo de certas condições. Por exemplo, se `valor> 8` por 30 minutos, o equipamento pode ficar em alerta **Aviso**.

> **NOTA**
>
> Na página **Configurações → Sistema → Configuração : Toras**, você pode configurar um comando de tipo de mensagem que permitirá que o Jeedom o notifique se o limite de aviso ou perigo for atingido.

### Guia Exibir

Nesta parte, você poderá configurar determinados comportamentos de exibição do widget no Painel, visualizações, design e no celular.

- **Widget** : Permite escolher o widget no computador ou no celular (observe que você precisa do plug-in do widget e também pode fazê-lo a partir dele)).
- **Visivél** : Marque para tornar o comando visível.
- **Mostrar nome** : Marque para tornar o nome do comando visível, dependendo do contexto.
- **Imprimir o nome eo ícone** : Marque para tornar o ícone visível, além do nome do comando.
- **Envoltório forçado antes Widget** : Tick **antes do widget** onde **após o widget** adicionar uma quebra de linha antes ou depois do widget (para forçar, por exemplo, uma exibição em coluna dos diferentes comandos do equipamento em vez das linhas padrão)

Abaixo, existem parâmetros de exibição opcionais que podem ser passados para o widget. Esses parâmetros dependem do widget em questão, portanto, é necessário examinar o arquivo no Market para conhecê-los.

> **Dica**
>
> Não se esqueça de salvar após qualquer modificação.
