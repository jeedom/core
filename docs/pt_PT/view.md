# Vues
**Página inicial → Visualizar**

As visualizações permitem criar uma visualização personalizada.
Não é tão poderoso quanto os designs, mas permite em poucos minutos ter uma exibição mais personalizada.

> **Dica**
>
> Você pode escolher a visualização padrão no seu perfil ao clicar no menu de visualização.

## Principe

Também podemos colocar widgets, gráficos (que podem ser compostos por vários dados) ou zonas de tabela (que contêm os widgets de comandos).

Nesta página, existe um botão no canto superior esquerdo para mostrar ou ocultar a lista de visualizações, bem como o botão para adicionar uma (o Jeedom solicitará seu nome e enviará para a página de edição) :

> **Dica**
>
> Você pode modificar esta opção no seu perfil para que a lista de visualizações fique visível por padrão.

## Adicionando / Editando uma visualização

O princípio é bastante simples : uma visão é composta de zonas (você pode colocar quantas quiser). Cada zona é do tipo gráfico, widget ou tabela, dependendo do tipo em que você pode colocar equipamentos, controle ou widgets gráficos.

> **Dica**
>
> Você pode mover a ordem das zonas arrastando e soltando.

- À esquerda da página, encontramos a lista de visualizações e um botão adicionar.
- Um botão no canto superior direito permite editar a visualização atual.
- No centro, você tem um botão para renomear uma vista, um botão para adicionar uma área, um botão para ver o resultado, um botão para salvar e um botão para excluir a vista.

Depois de clicar no botão adicionar zona, o Jeedom solicitará seu nome e tipo.
Em cada zona, você tem as seguintes opções gerais :

- **Largeur** : Define a largura da área (somente no modo área de trabalho).
- **Editar** : Permite alterar o nome da zona.
- **Remover** : Excluir a zona.

### Área do tipo de widget

Uma área de tipo de widget permite adicionar widgets :

- **Adicionar Widget** : Adicionar / editar widgets para exibir na área.

> **Dica**
>
> Você pode excluir um widget diretamente clicando na lixeira em frente a ele.

> **Dica**
>
> Você pode alterar a ordem dos widgets na área, arrastando e soltando.

Depois que o botão Adicionar widget é pressionado, você obtém uma janela solicitando que o widget adicione

### Área do tipo gráfico

Uma área do tipo gráfico permite adicionar gráficos à sua visualização, e possui as seguintes opções :

- **Período** : Permite escolher o período para exibição dos gráficos (30 min, 1 dia, 1 semana, 1 mês, 1 ano ou todos).
- **Adicionar curva** : Adicionar / editar gráficos.

Quando você pressiona o botão "Adicionar curva", o Jeedom exibe a lista de comandos históricos e você pode escolher os que deseja adicionar, uma vez concluído, você tem acesso às seguintes opções :

- **Lixeira** : Remover comando do gráfico.
- **Nome** : Nome do comando para desenhar.
- **Cor** : Cor da curva.
- **Tipo** : Tipo de curva.
- **Grupo** : Permite agrupar dados (tipo máximo por dia).
- **Escala** : Escala (direita ou esquerda) da curva.
- **Escada** : Exibe a curva da escada.
- **Pilha** : Empilha a curva com as outras curvas de tipo.
- **Mudança** : Desenhar apenas variações com o valor anterior.

> **Dica**
>
> Você pode alterar a ordem dos gráficos na área, arrastando e soltando.

### Área do tipo matriz

Aqui você tem os botões :

- **Adicionar coluna** : Adicione uma coluna à tabela.
- **Adicionar linha** : Adicione uma linha à tabela.

> **NOTA**
>
> É possível reorganizar as linhas arrastando e soltando, mas não as colunas.

Depois de adicionar suas linhas / colunas, você pode adicionar informações nas caixas :

- **Texto** : apenas texto para escrever.
- **html** : qualquer código html (é possível javascript, mas é fortemente desencorajado).
- **widget de comando** : o botão à direita permite escolher o comando a ser exibido (observe que isso exibe o widget do comando).


