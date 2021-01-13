# Vues
**Página inicial → Visualizar**

As visualizações permitem criar visualizações personalizadas.
Não é tão poderoso quanto os designs, mas permite em poucos minutos uma exibição mais personalizada que o Dashboard, com equipamentos de diferentes objetos, gráficos ou controles.

> **Dica**
>
> Você pode escolher a visualização padrão no seu perfil ao clicar no menu de visualização.

## Principe

Você também pode colocar blocos de equipamentos, gráficos (que podem ser compostos por vários dados) ou zonas de tabela (que contêm os widgets dos comandos).

Em uma visualização, encontramos :

- Um botão no canto superior esquerdo para mostrar ou ocultar a lista de Visualizações, bem como o botão para adicionar um.
- O lápis à direita para editar a ordem e o tamanho do equipamento, da mesma maneira que o Dashboard.
- Um botão *Edição completa* permitindo editar as zonas e elementos da Visualização.

> **Dica**
>
> Você pode, em seu perfil, modificar esta opção para que a lista de Visualizações fique visível por padrão.

## Adicionando / Editando uma visualização

O princípio é bastante simples : uma vista é composta de áreas. Cada zona é do tipo *gráfico*, *ferramenta* ou *borda*. Dependendo desse tipo, você pode adicionar gráficos, equipamentos ou comandos a ele.

- À esquerda da página, encontramos a lista de visualizações e um botão de criação.
- Um botão no canto superior direito permite editar a Visualização Atual (Configuração).
- Um botão para adicionar uma zona. Ser-lhe-á pedido o nome e o tipo de zona.
- Um botão *Visualização de resultados*, para sair do modo de edição completo e exibir esta tela.
- Um botão que permite salvar esta visualização.
- Um botão que permite excluir esta visualização.

> **Dica**
>
> Você pode mover a ordem das zonas arrastando e soltando.

Em cada zona, você tem as seguintes opções gerais :

- **Largura** : Define a largura da área (somente no modo área de trabalho). 1 para a largura de 1/12 do navegador, 12 para a largura total.
- Um botão que permite adicionar um elemento a esta zona, dependendo do tipo de zona (veja abaixo).
- **Editar** : Permite alterar o nome da zona.
- **Retirar** : Excluir a zona.

### Zona de tipo de equipamento

Uma zona de tipo de equipamento permite que você adicione equipamentos :

- **Adicionar equipamento** : Permite adicionar / modificar equipamentos a serem exibidos na área.

> **Dica**
>
> Você pode excluir um item de equipamento diretamente clicando na lata de lixo à esquerda dele.

> **Dica**
>
> É possível alterar a ordem das peças na área arrastando e soltando.


### Área do tipo gráfico

Uma área do tipo gráfico permite adicionar gráficos à sua visualização, e possui as seguintes opções :

- **Período** : Permite escolher o período para exibição dos gráficos (30 min, 1 dia, 1 semana, 1 mês, 1 ano ou todos).
- **Adicionar curva** : Adicionar / editar gráficos.

Quando você pressiona o botão **Adicionar curva**, O Jeedom exibe a lista de comandos históricos e você pode escolher o que deseja adicionar. Depois de concluído, você terá acesso às seguintes opções :

- **Lixeira** : Remover comando do gráfico.
- **Último nome** : Nome do comando para desenhar.
- **Cor** : Cor da curva.
- **Tipo** : Tipo de curva.
- **Grupo** : Permite agrupar dados (tipo máximo por dia).
- **Escada** : Escala (direita ou esquerda) da curva.
- **Escadaria** : Exibe a curva da escada.
- **Pilha** : Empilha a curva com as outras curvas de tipo.
- **Variação** : Desenhar apenas variações com o valor anterior.

> **Dica**
>
> Você pode alterar a ordem dos gráficos na área, arrastando e soltando.

### Área do tipo matriz

Aqui você tem os botões :

- **Adicionar coluna** : Adicione uma coluna à tabela.
- **Adicionar linha** : Adicione uma linha à tabela.

> **Nota**
>
> É possível reorganizar as linhas arrastando e soltando, mas não as colunas.

Depois de adicionar suas linhas / colunas, você pode adicionar informações nas caixas :

- Um texto.
- Código HTML (javascript possível, mas fortemente desencorajado).
- O widget de um pedido : O botão à direita permite escolher o comando a ser exibido.
