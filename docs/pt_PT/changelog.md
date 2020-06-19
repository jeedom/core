
Registro de Alterações Jeedom V4
=========

4.0.58
=====

- Correções de bugs em widgets de tempo

4.0.57
=====

- Reforçar a segurança dos cookies
- Uso de cromo (se instalado) para relatórios
- Corrigido um problema com o cálculo da hora do estado nos widgets se o fuso horário do jeedom não fosse o mesmo do navegador
- Correções de bugs

4.0.55
=======

- O novo dns (\*. Eu.jeedom.link) se torna o DNS primário (o DNS antigo ainda funciona)

4.0.54
=====

- Início da atualização para o novo site de documentação

4.0.53
=====

- Bug fix.

4.0.52
=====

- Correção de bug (a atualização deve ser feita se você estiver na versão 4.0.51).

4.0.51
=====

- Correções de bugs.
- Otimização do futuro sistema DNS.

4.0.49
=====

- Possibilidade de escolher o mecanismo Jeedom TTS e possibilidade de ter plug-ins que oferecem um novo mecanismo TTS.
- Suporte aprimorado para visualização na web no aplicativo móvel.
- Correções de bugs.
- Atualizando o documento.

4.0.47
=====

- Melhoria do testador de expressão.
- Atualizando o repositório no smart.
- Correções de bugs.

4.0.44
=====

- Traduções melhoradas.
- Correções de bugs.
- Restauração aprimorada de backup em nuvem.
- Agora, a restauração na nuvem repatria apenas o backup local, deixando a opção de fazer o download ou restaurá-lo.

4.0.43
=====

- Traduções melhoradas.
- Correções de bugs em modelos de cenário.

4.0.0
=====
- Redesign completo de temas (Core 2019 Light / Dark / Legacy).
- Possibilidade de alterar o tema automaticamente de acordo com o tempo.
- No celular, o tema pode mudar dependendo do brilho (requer a ativação de *sensor extra genérico* no chrome, página chrome://flags).<br/><br/>
- Melhoria e reorganização do menu principal.
- Menu de plugins : A lista de categorias e plugins agora está classificada em ordem alfabética.
- Menu Ferramentas : Adição de um botão para acessar o testador de expressão.
- Menu Ferramentas : Adição de um botão para acessar as variáveis.<br/><br/>
- Os campos de pesquisa agora suportam acentos.
- Os campos de pesquisa (Painel, cenários, objetos, widgets, interações, plug-ins) agora estão ativos quando a página é aberta, permitindo que você digite uma pesquisa diretamente.
- Adicione um botão X nos campos de pesquisa para cancelar a pesquisa.
- Durante uma pesquisa, a tecla *escapar* cancelar pesquisa.
- Painel de instrumentos : No modo de edição, o campo de pesquisa e seus botões são desativados e se tornam fixos.
- Painel de instrumentos : No modo de edição, clique em um botão *expandir* à direita dos objetos redimensiona os ladrilhos do objeto para a altura do mais alto. Ctrl + clique reduz para a altura mais baixa.
- Painel de instrumentos : A execução do comando em um bloco agora é sinalizada pelo botão *Atualizar*. Se não houver nenhum no bloco, ele aparecerá durante a execução.
- Painel de instrumentos : Os blocos indicam um comando info (histórico, que abrirá a janela Histórico) ou ação em foco.
- Painel de instrumentos : A janela do histórico agora permite abrir esse histórico em Análise / Histórico.
- Painel de instrumentos : A janela Histórico mantém sua posição / dimensões quando outro histórico é reaberto.
- Janela Configuração de Comando: Ctrl + clique em "Salvar" fecha a janela após.
- Janela Configuração do equipamento: Ctrl + clique em "Salvar" fecha a janela após.
- Adicionando informações de uso ao excluir equipamentos.
- Objetos : Adicionada opção para usar cores personalizadas.
- Objetos : Adicionar menu de contexto nas guias (troca rápida de objeto).
- Interações : Adicionar menu de contexto nas guias (mudança rápida de interação).
- Plugins : Adicionar menu de contexto nas guias (troca rápida de equipamento).
- Plugins : Na página de gerenciamento de plug-ins, um ponto laranja indica plug-ins não estáveis.
- Melhorias na tabela com opção de filtro e classificação.
- Capacidade de atribuir um ícone a uma interação.
- Agora, cada página do Jeedom tem um título no idioma da interface (guia do navegador).
- Prevenção de preenchimento automático no código de acesso dos campos'.
- Gerenciamento de funções *Página anterior / Página seguinte* navegador.<br/><br/>
- Widgets : Redesenho do sistema de widgets (menu Ferramentas / Widgets).
- Widgets : Capacidade de substituir um widget por outro em todos os comandos que o utilizam.
- Widgets : Capacidade de atribuir um widget a vários comandos.
- Widgets : Adicionar widget numérico de informações horizontais.
- Widgets : Adicionando um widget vertical numérico de informações.
- Widgets : Adição de um widget de bússola / vento numérico de informações (obrigado @thanaus).
- Widgets : Adição de um widget de chuva numérica de informações (obrigado @thanaus)
- Widgets : Exibição do widget de obturador de informações / ações proporcional ao valor.<br/><br/>
- Configuração : Melhoria e reorganização de guias.
- Configuração : Adicionando muitos *dicas* (aide).
- Configuração : Adicionando um mecanismo de pesquisa.
- Configuração : Adicionando um botão para esvaziar o cache do widget (guia Cache).
- Configuração : Adicione uma opção para desativar o cache do widget (guia Cache).
- Configuração : Capacidade de centralizar o conteúdo dos blocos verticalmente (guia Interface).
- Configuração : Adição de um parâmetro para a limpeza global dos históricos (guia Pedidos).
- Configuração : Mudança de #message# à #subject# em Configuração / Logs / Mensagens para evitar duplicação da mensagem.
- Configuração : Possibilidade nos resumos de adicionar uma exclusão dos pedidos que não foram atualizados por mais de XX minutos (exemplo para o cálculo das médias de temperatura, se um sensor não elevar nada por mais de 30 minutos, ele será excluído do cálculo)<br/><br/>
- Cenas : A coloração dos blocos não é mais aleatória, mas por tipo de bloco.
- Cenas : Possibilidade por Ctrl + clique no botão *execução* salve-o, inicie-o e exiba o log (se o nível do log não estiver em *Nemhum*).
- Cenas : Confirmação de exclusão de bloco. Ctrl + clique para evitar confirmação.
- Cenas : Adição de uma função de pesquisa nos blocos de código. Pesquisa : Ctrl + F e Enter, próximo resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- Cenas : Capacidade de condensar blocos.
- Cenas : A ação 'Adicionar bloco' alterna para a guia Cenário, se necessário.
- Cenas : Novas funções de copiar / colar em bloco. Ctrl + clique para cortar / substituir.
- Cenas : Um novo bloco não é mais adicionado no final do cenário, mas após o bloco em que você estava antes de clicar, determinado pelo último campo em que você clicou.
- Cenas : Implementação de um sistema Desfazer / Refazer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Cenas : Excluir compartilhamento de cenário.
- Cenas : Melhoria da janela de gerenciamento de modelos de cenário.<br/><br/>
- Análise / Equipamento : Adição de um mecanismo de pesquisa (guia Baterias, pesquisa de nomes e pais).
- Análise / Equipamento : Agora é possível clicar na área de calendário / dias do equipamento para acessar diretamente a (s) troca (s) da bateria).
- Análise / Equipamento : Adição de um campo de pesquisa.<br/><br/>
- Update Center : Aviso na guia 'Núcleo e plug-ins' e / ou 'Outros' se houver uma atualização disponível. Mude para 'Outros' se necessário.
- Update Center : diferenciação por versão (estável, beta, ...).
- Update Center : adição de uma barra de progresso durante a atualização.<br/><br/>
- Resumo Automation : O histórico de exclusão agora está disponível em uma guia (Resumo - Histórico).
- Resumo Automation : Revisão completa, possibilidade de encomendar objetos, equipamentos, pedidos.
- Resumo Automation : Adição de IDs de equipamentos e pedidos, no display e na pesquisa.
- Resumo Automation : Exportação CSV do objeto pai, ID, equipamento e seu ID, comando.
- Resumo Automation : Possibilidade de tornar visível ou não um ou mais pedidos.<br/><br/>
- Projeto : Capacidade de especificar a ordem (posição) de *Projetos* e *Projetos 3D* (Editar, configurar design).
- Projeto : Adição de um campo CSS personalizado nos elementos do *Projeto*.
- Projeto : Deslocamento das opções de exibição em Design da configuração avançada, nos parâmetros de exibição do *Projeto*. Isso para simplificar a interface e permitir que diferentes parâmetros sejam *Projeto*.
- Projeto : Movendo e redimensionando componentes no *Projeto* leva em consideração seu tamanho, com ou sem magnetização.<br/><br/>
- Redução geral (estilos css / inline, refatoração etc.) e melhorias de desempenho.
- Remova o Font Awesome 4 para manter apenas o Font Awesome 5.
- Atualização de libs : jquery 3.4.1, CodeMiror 5.46.0, editor de tabelas 2.31.1.
- Várias correções de bugs.
- Adicionando um sistema de configuração em massa (usado na página Equipamento para configurar Alertas de Comunicação neles)
- Adição de compatibilidade global do DNS Jeedom com uma conexão à Internet 4G.
- Correção de segurança

>**IMPORTANTE**
>
>Se, após a atualização, houver um erro no painel, tente reiniciar sua caixa para levar em consideração as novas adições de componentes.

>**IMPORTANTE**
>
>O plug-in de widget não é compatível com esta versão do Jeedom e não será mais suportado (porque as funções foram assumidas internamente no núcleo). Mais informações [aqui](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

