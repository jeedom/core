# Widgets
**Ferramentas → Widgets**

A página de widgets permite criar widgets personalizados para o seu Jeedom.

Existem dois tipos de widgets personalizados :

- Widgets baseados em um modelo (gerenciado pelo Jeedom Core).
- Widgets baseados no código do usuário.

> **NOTA**
>
> Se os widgets baseados em modelo são integrados ao Core e, portanto, monitorados pela equipe de desenvolvimento, este último não tem como garantir a compatibilidade de widgets com base no código do usuário, de acordo com os desenvolvimentos do Jeedom.

## Gestion

Você tem quatro opções :
- **Adicionar** : Permite criar um novo widget.
- **Importar** : Permite importar um widget como um arquivo json exportado anteriormente.
- **CÓDIGO** : Abre um editor de arquivos para editar widgets de código.
- **Substituição** : Abre uma janela que permite substituir um widget por outro em todos os dispositivos que o utilizam.

## Meus widgets

Depois de criar um widget, ele aparecerá nesta parte.

> **Dica**
>
> Você pode abrir um widget fazendo :
> - Clique em um deles.
> - Ctrl Clic ou Clic Center para abri-lo em uma nova guia do navegador.

Você tem um mecanismo de pesquisa para filtrar a exibição de widgets. A tecla Escape cancela a pesquisa.
À direita do campo de pesquisa, três botões encontrados em vários lugares no Jeedom:

- A cruz para cancelar a pesquisa.
- A pasta aberta para desdobrar todos os painéis e exibir todos os widgets.
- A pasta fechada para dobrar todos os painéis.

Uma vez na configuração de um widget, você tem um menu contextual com o botão direito do mouse nas guias do widget. Você também pode usar um Ctrl Click ou o Clic Center para abrir diretamente outro widget em uma nova guia do navegador.


## Principe

Mas o que é um modelo ?
Para simplificar, é um código (aqui html / js) integrado ao Core, algumas partes configuráveis pelo usuário com a interface gráfica do Core.

Dependendo do tipo de widget, geralmente você pode personalizar ícones ou colocar imagens de sua escolha.

## Os modelos

Existem dois tipos de modelos :

- O "**simples**" : Digite um ícone / imagem para "on" e um ícone / imagem para "off""
- O "**multiestado**" : Isso permite definir, por exemplo, uma imagem se o comando estiver definido como "XX" e outro se> como "YY" e novamente se <para "ZZ". Ou até mesmo uma imagem se o valor for "toto", outra se "plop" e assim por diante.

## Criando um widget

Uma vez na página Ferramentas -> Widget, clique em "Adicionar" e dê um nome ao seu novo widget.

Em seguida :
- Você escolhe se aplica a um pedido de ação ou tipo de informação.
- Dependendo da sua escolha anterior, você terá que escolher o subtipo do comando (binário, digital, outro...).
- Finalmente, o modelo em questão (planejamos colocar exemplos de renderizações para cada modelo).
- Depois que o modelo foi escolhido, o Jeedom oferece as opções para configurá-lo.

### Remplacement

É o que se chama de widget simples. Aqui, basta dizer que o "on" corresponde a esse ícone / imagem (com o botão escolher), o "off" é aquele etc. Então, dependendo do modelo, você pode oferecer a largura (largura) e a altura (altura). Isso é válido apenas para imagens.

>**NOTA**
>Lamentamos os nomes em inglês, isso é uma restrição do sistema de modelos. Essa escolha garante uma certa velocidade e eficiência, tanto para você quanto para nós. Não tivemos escolha

>**Dicas**
>Para usuários avançados, é possível nos valores de substituição colocar tags e especificar seu valor na configuração avançada do comando, exibição da guia e "Widget de Parâmetros Opcionais". Por exemplo, se na largura você coloca como valor #width# (tenha cuidado para colocar o # autour) au lieu d'un chiffre, dans "Paramètres optionnels widget" vous pouvez ajouter width (sans les #) e dar valor. Isso permite alterar o tamanho da imagem de acordo com a ordem e, portanto, evita que você crie um widget diferente para cada tamanho de imagem desejado

### Test

Isso é chamado de parte de vários estados; geralmente, como para widgets simples, você pode escolher "altura" / "largura" para as imagens, apenas abaixo da parte de teste.

É bem simples. Em vez de colocar uma imagem para "on" e / ou "off", como no caso anterior, você deve fazer um teste antes de fazer. Se isso for verdade, o widget exibirá o ícone / imagem em questão.

Os testes estão no formato : #value# == 1, #value# será substituído automaticamente pelo sistema pelo valor atual do pedido. Você também pode fazer, por exemplo :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**NOTA**
>É importante observar o 'ao redor do texto para comparar se o valor é um texto

>**NOTA**
>Para usuários avançados, também é possível usar funções do tipo javascript aqui #value#.match ("^ plop"), aqui testamos se o texto começa com plop

>**NOTA**
>É possível exibir o valor do comando no widget, colocando, por exemplo, próximo ao código HTML do ícone #value#

## Descrição dos widgets

Vamos descrever aqui alguns widgets que têm um funcionamento um tanto particular.

### Equipement

Os equipamentos têm certos parâmetros de configuração :

- dashboard_class / mobile_class : permite adicionar uma classe ao equipamento. Por exemplo, col2 para equipamentos na versão móvel, que permite dobrar a largura do widget

### Configurações frequentes

- Widget de tempo : exibe o tempo desde que o sistema esteve no estado de exibição.
- Nós : ícone para exibir se o equipamento estiver ligado / 1.
- Fora : ícone para exibir se o equipamento estiver desligado / 0.
- Luz acesa : ícone para exibir se o equipamento está ligado / 1 e o tema é claro (se vazio, o Jeedom assume a imagem escura).
- Luz apagada : ícone para exibir se o equipamento está desligado / 0 e o tema é claro (se vazio, o Jeedom retira o img da escuridão).
- Escuro em : ícone para exibir se o equipamento está ligado / 1 e o tema está escuro (se vazio, o Jeedom acende a luz img).
- Escuro : ícone para exibir se o equipamento está desligado / 0 e o tema está escuro (se vazio, o Jeedom retira a luz img).
- Largura da área de trabalho : largura da imagem na área de trabalho em px (basta colocar o número e não o px). Importante apenas que a largura é solicitada, o Jeedom calculará a altura para não distorcer a imagem.
- Largura móvel : largura da imagem no celular em px (basta colocar o número e não o px). Importante apenas que a largura é solicitada, o Jeedom calculará a altura para não distorcer a imagem.

### HygroThermographe

Este widget é um pouco especial, pois é um widget com vários comandos, ou seja, reúne no visor o valor de vários comandos. Aqui ele assume comandos de temperatura e umidade.

Para configurá-lo, é bastante simples: você deve atribuir o widget ao controle de temperatura do seu equipamento e ao controle de umidade.

>**IMPORTANTE**
>É absolutamente necessário que seus pedidos tenham o tipo genérico de temperatura no controle de temperatura e umidade no controle de umidade (isso é configurado na configuração avançada da configuração da guia de comandos).

##### Parâmetros opcionais)

- Escala : Permite alterar seu tamanho, por exemplo, definindo a escala como 0.5 será 2 vezes menor.

>**NOTA**
> Atenção em um design, é especialmente importante não fazer um pedido sozinho com esse widget que não funcionará, pois é um widget usando o valor de vários comandos; é absolutamente necessário colocar o widget completo

### Multiline

##### Parâmetros opcionais)

- maxHeight : Permite definir sua altura máxima (barra de rolagem ao lado se o texto exceder esse valor).

### Botão deslizante

##### Parâmetros opcionais)

- passo : Permite ajustar a etapa de uma ação do botão (0.5 por padrão).

### Rain

##### Parâmetros opcionais)

- Escala : Permite alterar seu tamanho, por exemplo, definindo a escala como 0.5 será 2 vezes menor.
- showRange : Exibe os valores mínimo / máximo do comando.


## Widget de código

### Tags

No modo de código, você tem acesso a diferentes tags para pedidos, aqui está uma lista (não necessariamente exaustiva)) :

- #name# : nome do comando
- #valueName# : nome do valor do pedido e = #name# quando é um comando de tipo de informação
- #minValue# : valor mínimo que o comando pode assumir (se o comando for do tipo slider)
- #maxValue# : valor máximo que pode assumir o comando (se o comando for do tipo slider)
- #hide_name# : vazio ou oculto se o usuário pediu para ocultar o nome do widget, para colocá-lo diretamente em uma tag de classe
- #id# : ID do pedido
- #state# : valor do comando, vazio para um comando do tipo de ação se não estiver vinculado a um comando de status
- #uid# : identificador exclusivo para esta geração do widget (se houver várias vezes o mesmo comando, caso de designs:  somente esse identificador é realmente único)
- #valueDate# : data do valor do pedido
- #collectDate# : data de coleta do pedido
- #alertLevel# : nível de alerta (consulte [aqui](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) para a lista)
- #hide_history# : se o histórico (máximo, mínimo, média, tendência) deve ser oculto ou não. Quanto ao #hide_name# está vazio ou oculto e, portanto, pode ser usado diretamente em uma classe. IMPORTANTE se essa tag não for encontrada no seu widget, as tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# e #tendance# não será substituído pelo Jeedom.
- #minHistoryValue# : valor mínimo durante o período (período definido na configuração do Jeedom pelo usuário)
- #averageHistoryValue# : valor médio ao longo do período (período definido na configuração do Jeedom pelo usuário)
- #maxHistoryValue# : valor máximo durante o período (período definido na configuração do Jeedom pelo usuário)
- #tendance# : tendência no período (período definido na configuração do Jeedom pelo usuário). Atenção, a tendência é diretamente uma classe de ícone : fas fa-arrow-up, fas fa-arrow-down ou fas fa-minus

### Atualizar valores

Quando um novo valor Jeedom procurará na página html, se o comando estiver lá e em Jeedom.cmd.atualizar se houver uma função de atualização. Se sim, chama-o com um único argumento, que é um objeto no formulário :

`` ''
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
`` ''

Aqui está um exemplo simples de código javascript para colocar no seu widget :

`` ''
<script>
    Jeedom.cmd.update ['#id#'] = função (_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Data da coleta : '+ _options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
</script>
`` ''

Aqui estão duas coisas importantes :

`` ''
Jeedom.cmd.update ['#id#'] = função (_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Data da coleta : '+ _options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
`` ''
A função chamada ao atualizar o widget. Em seguida, ele atualiza o código html do widget_template.

`` ''
Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
`` ''
 A chamada para esta função para a inicialização do widget.

 Você encontrará [aqui](https://github.com/Jeedom/core/tree/V4-stable/core/template) exemplos de widgets (no painel e nas pastas móveis)
