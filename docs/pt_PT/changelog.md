# Registro de alterações Jeedom V4.1

## 4.1.0

- Síntese : Adicionando uma nova página **Home → Resumo** oferecendo uma síntese visual global das peças.
- Pesquisa : Adição de um mecanismo de pesquisa em **Ferramentas → Pesquisa**.
- Dashboard : Modo de edição agora inserindo o bloco movido.
- Dashboard : Agora podemos clicar no *time* widgets de ações de tempo para abrir a janela do histórico do comando info vinculado.
- Dashboard : O tamanho do bloco de um novo equipamento se adapta ao seu conteúdo.
- Dashboard : Adição de um botão para filtrar os itens exibidos por categoria.
- Dashboard : Ctrl Clique em uma informação para abrir a janela do histórico com todos os comandos históricos do equipamento visíveis no bloco. Ctrl Clique em uma legenda para exibir apenas esta, Alt Clique para exibir todas.
- Dashboard : Capacidade de desfocar imagens de fundo (Configuração -> Interface).
- Ferramentas / Widgets : A função *Aplicar em* mostra os comandos vinculados marcados, desmarcando um aplicará o widget principal padrão a este comando.
- Widgets : Capacidade de adicionar classe css a um widget (consulte a documentação do widget).
- Widgets : Adicionando um widget principal *sliderVertical*.
- Update Center : As atualizações são verificadas automaticamente quando a página é aberta, se for 120 minutos mais antiga.
- Update Center : A barra de progresso está agora na guia *Núcleo e plugins*, e o log aberto por padrão na guia *Informations*.
- Update Center : Se você abrir outro navegador durante uma atualização, a barra de progresso e o log indicarão.
- Update Center : Se a atualização terminar corretamente, é exibida uma janela pedindo para recarregar a página.
- Atualizações principais : Implementação de um sistema para limpar arquivos Core não utilizados antigos.
- Páginas de Widgets / Objetos / Cenários / Interações / Plugins :
	- Ctrl Clic / Clic Center em um equipamento de widget, objeto, cenário, interação, plug-in : Abre em uma nova guia.
	- Ctrl Clic / Clic Center também disponível em seus menus de contexto (nas guias).
- Nova página ModalDisplay:
	- Menu Análise : Ctrl Clique / Clique em Central no *Tempo real* : Abra a janela em uma nova guia, em tela cheia.
	- Menu Ferramentas : Ctrl Clique / Clique em Central no *Notes*, *Testador de expressão*, *Variables*, *Recherche* : Abra a janela em uma nova guia, em tela cheia.
- Cenas : Adicionando um mecanismo de pesquisa (à esquerda do botão Executar).
- Cenas : Adição da função de idade (fornece a idade do valor da ordem).
- Cenas : *stateChanges()* agora aceite o período *today* (da meia-noite até agora), *yesterday* e *day* (por 1 dia).
- Cenas : Funções *estatísticas (), média (), max (), min (), tendência (), duração ()* : Bugfix ao longo do período *yesterday*, e aceite agora *day* (por 1 dia).
- Cenas : Possibilidade de desativar o sistema de cotação automática (Configurações → Sistema → Configuração : Pedidos).
- Cenas : Visualizando um *warning* se nenhum gatilho estiver configurado.
- Cenas : Correção de bug no bloco copiar / colar.
- Cenas : Copiar / colar do bloco entre diferentes cenários.
- Cenas : As funções desfazer / refazer estão agora disponíveis na forma de botões (ao lado do botão de criação do bloco).
- Janela Variáveis de Cenário : ordenação alfabética na abertura.
- Análise / História : Ctrl Clique em uma legenda para exibir apenas esse histórico, Alt Clique para exibir todos eles.
- Análise / História : As opções *agrupamento, tipo, variação, escada* estão ativos apenas com uma única curva exibida.
- Análise / História : Agora podemos usar a opção *Aire* com a opção *Escalier*.
- Ver : possibilidade de colocar cenários.
- História : Integração da linha do tempo no DB por motivos de confiabilidade.
- História : Gerenciamento de várias linhas do tempo.
- História : Revisão gráfica do cronograma.
- Resumo Automation : Equipamentos de plug-in desativados e seus controles não têm mais os ícones à direita (configuração do equipamento e configuração avançada).
- Resumo Automation : Capacidade de pesquisar nas categorias de equipamentos.
- Resumo Automation : Possibilidade de mover várias peças de equipamento de um objeto para outro.
- Resumo Automation : Possibilidade de selecionar todo o equipamento de um objeto.
- Mecanismo de tarefas : Na guia *Demônio*, plugins desativados não aparecem mais.
- Configuração : A guia *Informations* agora está na guia *Geral*.
- Configuração : A guia *Commandes* agora está na guia *Equipements*.
- Janela de configuração avançada de equipamentos : Alteração dinâmica da configuração do quadro de distribuição.
- Equipamento : Nova categoria *Ouvrant*.
- Sobre a janela : Adição de atalhos ao Changelog e FAQ.<br/><br/>
- WebApp : Integração da nova página Resumo.
- WebApp : Na página Cenários, um clique no título do cenário exibe seu log.
- WebApp : Agora podemos selecionar / copiar parte de um log.
- WebApp : Na pesquisa em um log, adição de um botão x para cancelar a pesquisa.
- WebApp : Persistência da alternância do tema (8h).
- WebApp : Em um design, um clique com três dedos retorna à página inicial.
- WebApp : Exibição de cenários por grupo.
- WebApp : Muitas correções de bugs (interface do usuário, retrato / paisagem iOS, etc.).<br/><br/>
- Documentação : Adaptações de acordo com v4 e v4.1.
- Documentação : Nova página *Atalhos de teclado / mouse* incluindo um resumo de todos os atalhos no Jeedom. Acessível no documento do Painel ou nas Perguntas frequentes.
- Correções de bugs e otimizações.
- Lib : Atualizar o HighStock v7.1.2 a v8.0.4.
- Lib : Atualizar o jQuery v3.4.1 a v3.5.1.


### 4.0.53

- Bug fix.

### 4.0.52

- Correção de bug (atualização a ser feita absolutamente se você estiver na versão 4.0.51).

### 4.0.51

- Correções de bugs.
- Otimização do futuro sistema DNS.

### 4.0.49

- Possibilidade de escolher o mecanismo Jeedom TTS e possibilidade de ter plug-ins que oferecem um novo mecanismo TTS.
- Suporte aprimorado para visualização na web no aplicativo móvel.
- Correções de bugs.
- Atualizando o documento.

### 4.0.47

- Melhoria do testador de expressão.
- Atualizando o repositório no smart.
- Correções de bugs.

### 4.0.44

- Traduções melhoradas.
- Correções de bugs.
- Restauração aprimorada de backup em nuvem.
- Agora, a restauração na nuvem repatria apenas o backup local, deixando a opção de fazer o download ou restaurá-lo.

### 4.0.43

- Traduções melhoradas.
- Correções de bugs em modelos de cenário.

## 4.0.0
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
- Durante uma pesquisa, a tecla *echap* cancelar pesquisa.
- Dashboard : No modo de edição, o campo de pesquisa e seus botões são desativados e se tornam fixos.
- Dashboard : No modo de edição, clique em um botão *expand* à direita dos objetos redimensiona os ladrilhos do objeto para a altura do mais alto. Ctrl + clique reduz para a altura mais baixa.
- Dashboard : A execução do comando em um bloco agora é sinalizada pelo botão *refresh*. Se não houver nenhum no bloco, ele aparecerá durante a execução.
- Dashboard : Os blocos indicam um comando info (histórico, que abrirá a janela Histórico) ou ação em foco.
- Dashboard : A janela do histórico agora permite abrir esse histórico em Análise / Histórico.
- Dashboard : A janela Histórico mantém sua posição / dimensões quando outro histórico é reaberto.
- Janela Configuração de Comando: Ctrl + clique em "Salvar" fecha a janela após.
- Janela Configuração do equipamento: Ctrl + clique em "Salvar" fecha a janela após.
- Adicionando informações de uso ao excluir equipamentos.
- Objetos : Adicionada opção para usar cores personalizadas.
- Objetos : Adicionar menu de contexto nas guias (mudança rápida de objeto).
- Interações : Adicionar menu de contexto nas guias (alteração rápida da interação).
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
- Widgets : Adicionando um widget de chuva numérico de informações (obrigado @thanaus)
- Widgets : Exibição do widget de obturador de informações / ações proporcional ao valor.<br/><br/>
- Configuração : Melhoria e reorganização de guias.
- Configuração : Adicionando muitos *tooltips* (ajuda).
- Configuração : Adicionando um mecanismo de pesquisa.
- Configuração : Adicionando um botão para esvaziar o cache do widget (guia Cache).
- Configuração : Adicionada opção para desativar o cache do widget (guia Cache).
- Configuração : Capacidade de centralizar o conteúdo dos blocos verticalmente (guia Interface).
- Configuração : Adição de um parâmetro para a limpeza global dos históricos (comandos da guia).
- Configuração : Mudança de #message# às #subject# em Configuração / Logs / Mensagens para evitar duplicação da mensagem.
- Configuração : Possibilidade nos resumos de adicionar uma exclusão dos pedidos que não foram atualizados por mais de XX minutos (exemplo para o cálculo das médias de temperatura se um sensor não elevar nada por mais de 30 minutos, ele será excluído do cálculo )<br/><br/>
- Cenas : A coloração dos blocos não é mais aleatória, mas por tipo de bloco.
- Cenas : Possibilidade por Ctrl + clique no botão *execução* salve-o, inicie-o e exiba o log (se o nível do log não estiver em *Aucun*).
- Cenas : Confirmação de exclusão de bloco. Ctrl + clique para evitar confirmação.
- Cenas : Adição de uma função de pesquisa nos blocos de código. Pesquisar : Ctrl + F e Enter, próximo resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- Cenas : Capacidade de condensar blocos.
- Cenas : A ação 'Adicionar bloco' alterna para a guia Cenário, se necessário.
- Cenas : Novas funções de copiar / colar em bloco. Ctrl + clique para cortar / substituir.
- Cenas : Um novo bloco não é mais adicionado no final do cenário, mas após o bloco em que você estava antes de clicar, determinado pelo último campo em que você clicou.
- Cenas : Implementação de um sistema Desfazer / Refazer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Cenas : Excluir compartilhamento de cenário.
- Cenas : Melhoria da janela de gerenciamento de modelos de cenário.<br/><br/>
- Análise / Equipamento : Adição de um mecanismo de pesquisa (guia Baterias, pesquisa por nomes e pais).
- Análise / Equipamento : A área de calendário / dias do equipamento agora pode ser clicada para acessar diretamente a troca de baterias..
- Análise / Equipamento : Adição de um campo de pesquisa.<br/><br/>
- Update Center : Aviso na guia 'Núcleo e plug-ins' e / ou 'Outros' se houver uma atualização disponível. Mude para 'Outros' se necessário.
- Update Center : diferenciação por versão (estável, beta, ...).
- Update Center : adição de uma barra de progresso durante a atualização.<br/><br/>
- Resumo Automation : O histórico de exclusão agora está disponível em uma guia (Resumo - Histórico).
- Resumo Automation : Revisão completa, possibilidade de encomendar objetos, equipamentos, pedidos.
- Resumo Automation : Adição de IDs de equipamentos e pedidos, no display e na pesquisa.
- Resumo Automation : Exportação CSV do objeto pai, ID, equipamento e seu ID, comando.
- Resumo Automation : Possibilidade de tornar visível ou não um ou mais pedidos.<br/><br/>
- Desenho : Capacidade de especificar a ordem (posição) de *Designs* e *Projetos 3D* (Editar, configurar design).
- Desenho : Adição de um campo CSS personalizado nos elementos do *design*.
- Desenho : Deslocamento das opções de exibição em Design da configuração avançada, nos parâmetros de exibição do *Design*. Isso para simplificar a interface e permitir que diferentes parâmetros sejam *Design*.
- Desenho : Movendo e redimensionando componentes no *Design* leva em consideração seu tamanho, com ou sem magnetização.<br/><br/>
- Redução geral (estilos css / inline, refatoração etc.) e melhorias de desempenho.
- Remova o Font Awesome 4 para manter apenas o Font Awesome 5.
- Atualização de libs : jquery 3.4.1, CodeMiror 5.46.0, editor de tabelas 2.31.1.
- Várias correções de bugs.
- Adicionando um sistema de configuração em massa (usado na página Equipamento para configurar o Alerta de Comunicação neles)

>**IMPORTANT**
>
>Se, após a atualização, houver um erro no painel, tente reiniciar sua caixa para levar em consideração as novas adições de componentes..

>**IMPORTANT**
>
>O plugin do widget não é compatível com esta versão do Jeedom e não será mais suportado (porque as funções foram assumidas internamente no núcleo). Mais informações [aqui](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).
