# Dashboard
**Página inicial → Painel**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

O painel é uma das páginas principais do Jeedom, ele exibe um relatório de toda a sua automação residencial.
Este relatório (diferente das visualizações e designs) é gerado automaticamente pela Jeedom e inclui todos os objetos visíveis e seus equipamentos.

![Painel de controle](../images/doc-dashboard-legends.png)

- 1 : Menu principal do Jeedom.
- 2 : Resumo Global [Documentação abstrata.](/pt_PT/concept/summary).
- 3 : Hora do navegador, atalho para a Linha do tempo.
- 4 : Botão para acessar a documentação da página atual.
- 5 : Nome do seu Jeedom, atalho para configuração.
- 6 : Modo de edição (reordenar / redimensionar blocos).
- 7 : Filtrar por categorias.
- 8 : Objeto : Ícone, nome e resumo e seus equipamentos.
- 9 : Ladrilho para equipamento.
- 10 : Widget de pedidos.

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

- Você tem no canto superior esquerdo um pequeno ícone para exibir a árvore de objetos ao pairar.
- Um campo de pesquisa permite procurar equipamentos por nome, categoria, plug-in, tag, etc.
- O ícone à direita do campo de pesquisa é usado para filtrar os equipamentos exibidos de acordo com sua categoria. Um clique no centro permite selecionar rapidamente uma única categoria.
- Na extrema direita, um botão permite que você alterne para o modo de edição, para modificar a ordem dos blocos (clique e solte no widget) ou redimensioná-los. Você também pode reorganizar a ordem dos pedidos em um bloco.

- Ao clicar no resumo de um objeto, você filtra para exibir apenas o equipamento relacionado a esse objeto e relacionado a esse resumo.

- Um clique em um pedido de tipo de informação exibe o histórico do pedido (se for histórico).
- Um Ctrl + Clique em um comando de tipo de informação exibe o histórico de todos os comandos (históricos) desse bloco.
- Um clique na informação *Tempo* de um comando de ação exibe o histórico do comando (se for historizado).


## Modo de edição

No modo de edição (*o lápis no canto superior direito*), você pode alterar o tamanho dos ladrilhos e sua organização no painel.

os ícones de atualização do equipamento são substituídos por um ícone que permite acessar suas configurações. Este ícone abre uma janela de edição com os parâmetros de exibição do equipamento e seus controles.

![Modo de edição](./images/EditDashboardModal.gif)

Em cada objeto, à direita de seu nome e resumo, dois ícones permitem que você alinhe a altura de todos os ladrilhos do objeto na parte superior ou inferior.

## Barra de menus do Jeedom

> **Dica**
>
> - Clique no relógio (barra de menus) : Abra a linha do tempo.
> - Clique no nome do Jeedom (barra de menus) : Abre Configurações → Sistema → Configuração.
> - Clique em ? (Barra de menus) : Abrir ajuda na página atual.
> - Escape em um campo de pesquisa : Limpe o campo e cancele esta pesquisa.
