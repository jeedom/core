# Widgets

Um widget é a representação gráfica de um pedido. Cada widget é específico para o tipo e subtipo do comando ao qual deve ser aplicado, bem como a versão a partir da qual o Jeedom é acessado *(desktop ou celular)*.

## Widgets padrão

Antes de olhar para a customização de widgets, vamos descobrir as possibilidades oferecidas por certos widgets presentes por padrão no Core Jeedom.

### Équipements

Os dispositivos (ou blocos) têm certos parâmetros de configuração acessíveis através da configuração avançada do dispositivo, guia "Exibir" → "**Parâmetros opcionais no bloco**".

##### Parâmetros opcionais)

- **dashboard_class / mobile_class** : permite adicionar uma classe ao equipamento. Por exemplo, `col2` para dispositivos em versão móvel permitirá dobrar a largura do widget.

### HygroThermographe

Este widget é um pouco especial porque é um widget de vários comandos, ou seja, ele reúne o valor de vários comandos. Aqui ele assume comandos de temperatura e umidade. Para configurá-lo, você deve atribuir o widget aos controles de temperatura e umidade do seu equipamento.

![WidgE HygroThermographe](./images/widgets3.png)

##### Parâmetros opcionais)

- **escala** *(échelle)* : Permite que você altere o tamanho do widget, preenchendo o parâmetro **escala** para `0.5`, o widget será 2 vezes menor.

>**IMPORTANTE**      
>É ABSOLUTAMENTE necessário que os tipos genéricos sejam indicados; `Temperatura` no controle de temperatura e` Umidade` no controle de umidade (isso é configurado na configuração avançada do controle, guia de configuração).

>**OBSERVAÇÃO**      
> Atenção em um design, é especialmente importante não fazer um pedido sozinho com esse widget que não funcionará, pois é um widget usando o valor de vários comandos; é absolutamente necessário colocar o widget completo

### Multiline

Este widget é usado para exibir o conteúdo de uma informação / outro comando em várias linhas.

##### Parâmetros opcionais)

- **altura máxima** *(altura máxima)* : Permite que você defina a altura máxima do widget (um elevador *(scrollbar)* aparecerá na lateral se o texto ultrapassar).

### Botão deslizante

Widget para controle de ação / cursor com botão "**+**" e um botão "**-**" permitindo agir com precisão sobre um valor.

##### Parâmetros opcionais)

- **passos** *(pas)* : Permite que você defina a etapa de mudança de valor *(0,5 por padrão)*.

### Rain

Widget para exibir os níveis de água.

![WidgE Rain](./images/widgets4.png)

##### Parâmetros opcionais)

- **escala** *(échelle)* : Permite que você altere o tamanho do widget, preenchendo o parâmetro **escala** para `0.5`, o widget será 2 vezes menor.
- **showRange** : Defina como `1` para exibir os valores mínimo e máximo do comando.
- **animar** : Desative a animação do widget com um valor de `0`.

### Ativar / desativar ícone de alternância

Sobre widgets para interruptores *(ligar / desligar, ligar / desligar, abrir / fechar, etc...)*, pode ser considerado mais agradável visualmente exibir apenas um ícone refletindo o status do dispositivo a ser controlado.

Esta possibilidade pode ser usada tanto com widgets padrão quanto com widgets personalizados.

Para isso, é necessário levar em consideração 2 pré-requisitos :

- O **2 comandos de ação / falha** deve estar vinculado a um pedido **info / binário** que irá armazenar o estado atual do dispositivo.

>**Exemplo**      
>![Widget Alternar Link](./images/widgets5.png)

>**Conselho**     
>Desmarque *"Afficher"* do comando info / binário que não precisa ser exibido.

- Para que o Jeedom Core seja capaz de identificar qual comando corresponde a qual ação, é essencial respeitar a seguinte nomenclatura para **2 comandos de ação / falha** :
```
    'on':'on',
    'off':'off',
    'monter':'on',
    'descendre':'off',
    'ouvrir':'on',
    'ouvrirStop':'on',
    'ouvert':'on',
    'fermer':'off',
    'activer':'on',
    'desactiver':'off',
    'desabilitar':'off',
    'lock':'on',
    'unlock':'off',
    'marche':'on',
    'arret':'off',
    'parar':'off',
    'stop':'off',
    'go':'on'
```

>**Truque**      
>Desde que o nome padronizado permaneça legível, é possível adaptar a nomenclatura, por exemplo *open_volet* Ou *shutter_close*, *caminhada_2* E *parada_2*, etc..

## Widgets personalizados

A página Widgets, acessível a partir do menu **Ferramentas → Widgets**, permite que você adicione widgets personalizados além daqueles disponíveis por padrão no Jeedom.

Existem dois tipos de widgets personalizados :

- Widgets *Essencial* baseado em modelo. Esses widgets são gerenciados pelo Jeedom Core e, portanto, monitorados pela equipe de desenvolvimento. Sua compatibilidade é garantida com futuras evoluções do Jeedom.
- Widgets *Terceiro* baseado no código do usuário. Ao contrário dos widgets principais, a equipe de desenvolvimento da Jeedom não tem controle sobre o código inserido nesses widgets, sua compatibilidade com desenvolvimentos futuros não é garantida. Esses widgets, portanto, precisam ser mantidos pelo usuário.

### Gestion

![Widgets](./images/widgets.png)

Você tem quatro opções :
- **Adicionar** : Permite que você adicione um widget *Essencial*.
- **Importar** : Permite que você importe um widget como um arquivo json exportado anteriormente.
- **Código** : Acesse a página de edição do widget *Terceiro*.
- **Substituição** : Abre uma janela que permite substituir um widget por outro em todos os dispositivos que o utilizam.

### Meus widgets

Nesta parte você encontrará todos os widgets que criou, classificados por tipo.

![Mes Widgets](./images/widgets1.png)

> **Truque**      
> Você pode abrir um widget fazendo :
> - `Clique` em um deles.
> - `Ctrl + Click` ou` Click + Center` para abri-lo em uma nova guia do navegador.

O mecanismo de pesquisa permite filtrar a exibição de widgets de acordo com diferentes critérios (nome, tipo, subtipo, etc...). A tecla `Esc` cancela a pesquisa.

![Recherche Widgets](./images/widgets2.png)

À direita do campo de pesquisa, três botões que podem ser encontrados em vários lugares no Jeedom:

- **A Cruz** para cancelar a busca.
- **O arquivo aberto** para desdobrar todos os painéis e exibir widgets.
- **O arquivo fechado** para recolher todos os painéis e ocultar widgets.

Uma vez na página de configuração de um widget, um menu de contexto é acessível por `` Clique com o botão direito '' nas guias do widget. Você também pode usar `Ctrl + Click` ou` Click + Center` para abrir outro widget diretamente em uma nova aba do navegador.

### Criando um widget

Uma vez na página **Ferramentas → Widgets** você tem que clicar no botão "**Adicionar**" e dê um nome ao seu novo widget.

Após :
- Você escolhe se isso se aplica a um pedido de tipo **Ação** Ou **Informação**.
- Dependendo da escolha anterior, você terá que **escolha o subtipo** da ordem.
- Finalmente **o modelo** entre aqueles que estarão disponíveis de acordo com as escolhas anteriores.
- Uma vez que o modelo foi escolhido, Jeedom exibe as opções de configuração para ele abaixo.

### Os modelos

#### Definição de um template

Para simplificar, seu código (HTML / JS), integrado ao Core, algumas partes são configuráveis pelo usuário através da interface gráfica do menu **Widgets**. A partir da mesma base de dados e tendo em conta os elementos que introduzirá no template, o Core irá gerar widgets únicos correspondentes ao display que deseja obter.

Dependendo do tipo de widget, geralmente você pode personalizar os ícones, colocar as imagens de sua escolha e / ou incorporar código HTML.

Existem dois tipos de modelo :

- O "**simples**" : como um ícone / imagem para o "**Nós**" e um ícone / imagem para o "**Desligado**".
- O "**multiestado**" : Isso torna possível definir, por exemplo, uma imagem se o comando tiver o valor "**XX**" e outro tão maior que "**AA**" ou se menos que "**ZZ**". Também funciona para valores de texto, uma imagem se o valor for "**totó**", outro se "**estrondo**" E assim por diante...

#### Remplacement

Isso é chamado de modelo simples, aqui você só precisa dizer que o "**Nós**" corresponde a tal ícone / imagem *(usando o botão de escolha)*, O "**Desligado**" para esse outro ícone / imagem, etc...      

A Caixa **Widget de tempo**, se disponível, exibe a duração desde a última mudança de estado no widget.

Para modelos usando imagens, você pode configurar a largura do widget em pixels dependendo do suporte (**Largura da área de trabalho** E **Largura móvel**). Diferentes imagens também podem ser selecionadas de acordo com o tema ativo do Jeedom *(claro ou escuro)*.

>**Truque**     
>Para usuários avançados, é possível colocar tags nos valores de substituição e especificar seu valor na configuração avançada do comando.    
>Se, por exemplo, em **Largura da área de trabalho** você coloca como valor `#largeur_desktop#` (**tenha cuidado para colocar o** `#` **autour**) puis dans la configuratinós avancée d'une commande, onglE affichage → "**Paramètres optionnels widget**" vous ajoutez O paramètre `largeur_desktop` (**sans les** `#`) e dê a ele o valor "**90**", este widget personalizado neste comando terá 90 pixels de largura. Isso permite que você adapte o tamanho do widget a cada pedido sem ter que fazer um widget específico a cada vez.

#### Test

Isso é chamado de modelos de vários estados *(vários estados)*. Em vez de colocar uma imagem para o "**Nós** e / ou para o "**Desligado** como no caso anterior, você atribuirá um ícone de acordo com a validação de uma condição *(test)*. Se isso for verdade, o widget exibirá o ícone / imagem em questão.

Como antes, diferentes imagens podem ser selecionadas dependendo do tema ativo no Jeedom e na caixa **Widget de tempo** mostra a duração desde a última mudança de estado.

Os testes estão no formato : `#value# == 1`, `#value#`será automaticamente substituído pelo valor atual do comando. Você também pode fazer, por exemplo :

- `#value# > 1'
- `#value# >= 1 && #value# <= 5`
- `#value# == 'toto'`

>**OBSERVAÇÃO**     
>É essencial mostrar os apóstrofos (**'**) em torno do texto para comparar se o valor é texto *(info / outro)*.

>**OBSERVAÇÃO**     
>Para usuários avançados, também é possível usar funções javascript, como `#value#.match ("^ plop") `, aqui testamos se o texto começa com` plop`.

>**OBSERVAÇÃO**     
>É possível exibir o valor do comando no widget especificando `#value#`no código HTML do teste. Para exibir a unidade, adicione `#unite#`.

## Widget de código

### Tags

No modo de código, você tem acesso a diferentes tags para pedidos, aqui está uma lista (não necessariamente exaustiva)) :

- **#name#** : nome do comando
- **#valueName#** : nome do valor do pedido e = #name# quando é um comando de tipo de informação
- **#minValue#** : valor mínimo que o comando pode assumir (se o comando for do tipo slider)
- **#maxValue#** : valor máximo que pode assumir o comando (se o comando for do tipo slider)
- **#hide_name#** : vazio ou oculto se o usuário pediu para ocultar o nome do widget, para colocá-lo diretamente em uma tag de classe
- **#id#** : ID do pedido
- **#state#** : valor do comando, vazio para um comando do tipo de ação se não estiver vinculado a um comando de status
- **#uid#** : identificador exclusivo para esta geração do widget (se houver várias vezes o mesmo comando, caso de designs:  somente esse identificador é realmente único)
- **#valueDate#** : data do valor do pedido
- **#collectDate#** : data de coleta do pedido
- **#alertLevel#** : nível de alerta (consulte [aqui](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) para a lista)
- **#hide_history#** : se o histórico (máximo, mínimo, média, tendência) deve ser oculto ou não. Quanto ao #hide_name# está vazio ou oculto e, portanto, pode ser usado diretamente em uma classe. IMPORTANTE se essa tag não for encontrada no seu widget, as tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# E #tendance# não será substituído pelo Jeedom.
- **#minHistoryValue#** : valor mínimo durante o período (período definido na configuração do Jeedom pelo usuário)
- **#averageHistoryValue#** : valor médio ao longo do período (período definido na configuração do Jeedom pelo usuário)
- **#maxHistoryValue#** : valor máximo durante o período (período definido na configuração do Jeedom pelo usuário)
- **#tendance#** : tendência no período (período definido na configuração do Jeedom pelo usuário). Atenção, a tendência é diretamente uma classe de ícone : fas fa-seta para cima, fas fa-seta para baixo ou fas fa-minus

### Atualizar valores

Quando um novo valor o Jeedom irá pesquisar na página se o comando está lá e no Jeedom.cmd.atualizar se houver uma função de atualização. Se sim, chama-o com um único argumento, que é um objeto no formulário :

```
{valor_exibição: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#'}
```

Aqui está um exemplo simples de código javascript para colocar no seu widget :

```
<script>
    jeedom.cmd.addUpdateFunction('#id#', função (_options) {
      if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
        cmd.setAttribute('title', '{{Data do valor}}: ' + _opções.valorData + '<br>{{Data da coleta}}: ' + _opções.collectDate)
        cmd.querySelector('.value').innerHTML = _options.display_value
        cmd.querySelector('.unit').innerHTML = _options.unit
      }
    }
    jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', valor_exibição: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
</script>
```

Aqui estão duas coisas importantes :

```
jeedom.cmd.addUpdateFunction('#id#', função (_options) {
  if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
    cmd.setAttribute('title', '{{Data do valor}}: ' + _opções.valorData + '<br>{{Data da coleta}}: ' + _opções.collectDate)
    cmd.querySelector('.value').innerHTML = _options.display_value
    cmd.querySelector('.unit').innerHTML = _options.unit
  }
}
```
A função é chamada durante uma atualização do widget. Em seguida, ele atualiza o código html do widget_template.

```
jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', valor_exibição: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
```
 A chamada para esta função para a inicialização do widget.

### Exemples

 Você encontrará [aqui](https://github.com/Jeedom/core/tree/master/core/template) exemplos de widgets (no painel e nas pastas móveis)
