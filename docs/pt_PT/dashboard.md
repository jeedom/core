# Dashboard
**Página inicial → Painel**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

O painel é uma das páginas principais do Jeedom, exibe um relatório de toda a sua automação residencial.
Este relatório (diferente das visualizações e designs) é gerado automaticamente pela Jeedom e inclui todos os objetos visíveis e seus equipamentos.

> **Dica**
>
> A ordem de exibição dos objetos no Painel é visível em **Análise → Resumo da automação residencial**. Você pode modificar esta ordem nesta página arrastando e soltando.

Para que o equipamento apareça no painel, ele deve :
- Seja ativo.
- Seja visível.
- Ter como objeto pai um objeto visível no Painel.

Na primeira aparição do equipamento no painel, o Jeedom tenta dimensionar corretamente seu bloco para exibir todos os comandos e seus widgets.
Para manter um painel equilibrado, você pode alternar para o modo Editar com o lápis no canto superior direito da barra de pesquisa, para redimensionar e / ou reordenar os ladrilhos do equipamento.

Movendo o mouse sobre um pedido, um marcador colorido aparece na parte inferior esquerda do bloco:
- Azul para um pedido info. Se estiver logado, um clique nele abre a janela de log.
- Laranja para um comando de ação. Um clique acionará a ação.

Além disso, você pode clicar no título do ladrilho (o nome do equipamento) para abrir diretamente a página de configuração deste equipamento.

> **Dica**
>
> É possível ir diretamente para um único objeto na sua automação residencial, através do menu **Página inicial → Painel de controle → Nome do objeto**.
> Isso permite que você tenha apenas o equipamento que lhe interessa e carregue a página mais rapidamente.

- Você tem no topo um pequeno ícone para mostrar / ocultar a árvore de objetos.
- O segundo ícone à esquerda permite exibir apenas as informações dos resumos dos objetos.
- No meio, um campo de pesquisa permite pesquisar equipamentos por nome, categoria, plug-in, tag, etc.
- À direita, um botão permite alternar para o modo de edição, modificar a ordem dos blocos (clicar e soltar no widget) ou redimensioná-los. Você também pode reorganizar a ordem dos pedidos em um bloco,
- Ao clicar no resumo de um objeto, você filtra para exibir apenas o equipamento relacionado a esse objeto e relacionado a esse resumo.
- Um clique em um pedido de tipo de informação exibe o histórico do pedido (se for histórico).

> **Dica**
>
> É possível, a partir do seu perfil, configurar o Jeedom para que a árvore de objetos e / ou os cenários fiquem visíveis por padrão quando você chegar ao Painel.

> **Dica**
>
> No celular, pressionar um comando de tipo de informação exibe um menu que permite exibir o histórico do pedido ou colocar um alerta nele para que o Jeedom o avise (uma vez) assim que que o valor passa um certo limite.


## Modo de edição

No modo de edição (*o lápis no canto superior direito*), você pode alterar o tamanho dos ladrilhos e sua organização no painel.

Você também pode editar o layout interno dos controles no bloco :

- Reorganize-os arrastando e soltando.
- Ou clicando com o botão direito do mouse no widget. Você então acessa :
    - **Configuração avançada** : dá acesso à configuração avançada do comando.
    - **Padrão** : layout padrão, tudo é automático com apenas a possibilidade de reorganizar a ordem dos pedidos.
    - **Mesa** : permite colocar os comandos em uma tabela : colunas e linhas são adicionadas e excluídas com o botão direito do mouse e, em seguida, basta mover os comandos nas caixas desejadas. Você pode fazer vários pedidos por caixa
    - **Adicionar coluna** : adicione uma coluna à tabela (acessível apenas se você estiver no layout da tabela)
    - **Adicionar linha** : adicione uma linha à tabela (acessível apenas se você estiver no layout da tabela)
    - **Excluir coluna** : remover uma coluna da tabela (acessível apenas se você estiver no layout da tabela)
    - **Eliminar linha** : excluir uma linha da tabela (acessível apenas se você estiver no layout da tabela)

À direita de cada objeto, um ícone permite que você :

- Clique em : Todas as peças deste objeto adotam uma altura igual à peça mais alta.

## Barra de menus do Jeedom

> **Dica**
>
> - Clique no relógio (barra de menus) : Abra a linha do tempo.
> - Clique no nome do Jeedom (barra de menus) : Abre Configurações → Sistema → Configuração.
> - Clique em ? (Barra de menus) : Abre a ajuda na página atual.
> - Escape em um campo de pesquisa : Limpe o campo e cancele esta pesquisa .
