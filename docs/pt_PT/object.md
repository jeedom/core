# Objets
**Ferramentas → Objetos**

O **Objetos** permitem definir a estrutura em árvore da sua automação residencial.

Todo o equipamento que você cria deve pertencer a um objeto e, portanto, é mais facilmente identificável. Dizemos então que o objeto é o **pai** equipamento.

Para dar livre escolha à personalização, você pode nomear esses objetos como desejar. Normalmente, definiremos as diferentes partes de sua casa, como o nome dos quartos (esta também é a configuração recomendada).

![Objetos](./images/object_intro.gif)

## Gestion

Você tem duas opções :
- **Adicionar** : Crie um novo objeto.
- **Visão global** : Exibe a lista de objetos criados e suas configurações.

## Meus objetos

Depois de criar um objeto, ele aparecerá nesta parte.

> **Gorjeta**
>
> Você pode abrir um objeto fazendo :
> - Clique em um deles.
> - Ctrl Clic ou Clic Center para abri-lo em uma nova guia do navegador.

Você tem um mecanismo de pesquisa para filtrar a exibição de objetos. A tecla Escape cancela a pesquisa.
À direita do campo de pesquisa, três botões encontrados em vários lugares no Jeedom:

- A cruz para cancelar a pesquisa.
- A pasta aberta para desdobrar todos os painéis e exibir todos os objetos.
- A pasta fechada para dobrar todos os painéis.

Uma vez na configuração de um objeto, você tem um menu contextual com o botão direito do mouse nas guias do objeto. Você também pode usar um Ctrl Click ou Center Click para abrir diretamente outro objeto em uma nova guia do navegador.

## Guia Objeto

Ao clicar em um objeto, você acessa sua página de configuração. Quaisquer que sejam as alterações que você fizer, não se esqueça de salvar suas alterações.

Aqui estão as diferentes características para configurar um objeto :

#### Configurações :

- **Nome do objeto** : O nome do seu objeto.
- **Objeto pai** : Indica o pai do objeto atual, isso permite definir uma hierarquia entre os objetos. Por exemplo : A sala está relacionada ao apartamento. Um objeto pode ter apenas um pai, mas vários objetos podem ter o mesmo pai.
- **Visível** : Marque esta caixa para tornar este objeto visível.
- **Esconder o painel de instrumentos** : Marque esta caixa para ocultar o objeto no painel. Ele ainda é mantido na lista, o que permite que seja exibido, mas apenas explicitamente.
- **Ocultar no resumo** : Marque esta caixa para ocultar o objeto no resumo'. Ele ainda é mantido na lista, o que permite que seja exibido, mas apenas explicitamente.
- **Ação de síntese** : Aqui você pode indicar uma vista ou desenho para ir ao clicar no objeto do Resumo. *Padrão : Painel de controle*.

#### Exibição :

- **Ícone** : Permite escolher um ícone para o seu objeto.
- **Cores personalizadas** : Ativa a consideração dos dois parâmetros de cores personalizadas abaixo.
- **Cor tag** : Permite escolher a cor do objeto e o equipamento a ele conectado.
- **Texto tag Cor** : Permite escolher a cor do texto do objeto. Este texto estará sobre o **Cor tag**. Você escolhe uma cor para tornar o texto legível.
- **Somente na síntese** : Permite que você coloque uma imagem para a Síntese sem que seja usada como imagem de fundo, especialmente na página *Painel de controle* deste objeto.
- **Cenário** : Você tem a opção de fazer upload de uma imagem ou excluí-la. No formato jpeg, essa imagem será a imagem de plano de fundo do objeto quando você a exibir no Painel. Também será usado para a miniatura da peça no Synthesis.

> **Gorjeta**
>
> Você pode alterar a ordem de exibição dos objetos no Painel. Na visão geral (ou no Resumo da automação residencial), selecione seu objeto com o mouse, arrastando e soltando, para dar um novo local.

> **Gorjeta**
>
> Você pode ver um gráfico representando todos os elementos do Jeedom anexados a esse objeto clicando no botão **Conexões**, canto superior direito.

> **Gorjeta**
>
> Quando um dispositivo é criado e nenhum pai foi definido, ele terá como pai : **Não**.

## Guia Resumo

Resumos são informações globais, atribuídas a um objeto, que são exibidas em particular no Painel (ao lado do nome do objeto) e no resumo (na miniatura do objeto).


### Quadro de avisos

As colunas representam os resumos atribuídos ao objeto atual. Três linhas são propostas para você :

- **-Se no resumo geral** : Marque a caixa se desejar que o resumo seja exibido na barra de menus do Jeedom.
- **Esconder no ambiente de trabalho** : Marque a caixa se não desejar que o resumo apareça ao lado do nome do objeto no Painel.
- **Esconder móvel** : Marque a caixa se você não deseja que o resumo apareça ao vê-lo em um celular.

### Commandes

Cada guia representa um tipo de resumo definido na configuração do Jeedom. Clique em **Adicionar comando** para que seja levado em consideração no resumo. Você tem a opção de selecionar o comando de qualquer equipamento Jeedom, mesmo que ele não tenha esse objeto como pai.

> **Gorjeta**
>
> Se você deseja adicionar um tipo de resumo ou configurar o método de cálculo do resultado, a unidade, o ícone e o nome de um resumo, deve-se acessar a configuração geral do Jeedom : **Configurações → Sistema → Configuração : Guia Resumos**.

## Guia de resumo por dispositivo

Esta página exibe todos os equipamentos tendo o objeto como pai. Em cada dispositivo, clicando sobre ele, é apresentada a lista de comandos de informação do dispositivo, à direita os meios de atribuir este comando a um ou mais resumos do objeto.

Se um ou mais resumos já estiverem definidos, o seletor aparece em laranja, com os tipos de resumos marcados à direita.

## Visão global

A visão geral permite visualizar todos os objetos no Jeedom, bem como suas configurações :

- **EU IRIA** : Object ID.
- **Objeto** : Nome do objeto.
- **Pai** : Nome do objeto pai.
- **Visível** : Visibilidade do objeto.
- **Mascarar** : Indica se o objeto está oculto no painel.
- **Resumo definido** : Indica o número de pedidos por resumo. O que está em azul é levado em consideração no resumo global.
- **Resumo do painel oculto** : Indica resumos ocultos no painel.
- **Resumo para celular oculto** : Mostrar resumos ocultos no celular.
