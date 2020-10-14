# Registro de alterações Jeedom V4.1

## 4.1.0

- **Síntese** : Adicionando uma nova página **Home → Resumo** oferecendo um resumo visual global das peças, com acesso rápido aos resumos.
- **Pesquisa** : Adição de um mecanismo de pesquisa em **Ferramentas → Pesquisa**.
- **Painel de instrumentos** : Modo de edição agora inserindo o bloco movido.
- **Painel de instrumentos** : Modo de edição: os ícones de atualização do equipamento são substituídos por um ícone que permite acesso à sua configuração, graças a um novo modal simplificado.
- **Painel de instrumentos** : Agora podemos clicar no *Tempo* widgets de ações de tempo para abrir a janela do histórico do comando info vinculado.
- **Painel de instrumentos** : O tamanho do bloco de um novo equipamento se adapta ao seu conteúdo.
- **Painel de instrumentos** : Adicionar (voltar!) Um botão para filtrar os itens exibidos por categoria.
- **Painel de instrumentos** : Ctrl Clique em uma informação para abrir a janela do histórico com todos os comandos históricos do equipamento visíveis no bloco. Ctrl Clique em uma legenda para exibir apenas esta, Alt Clique para exibir todas.
- **Painel de instrumentos** : Redesenho da exibição da árvore de objetos (seta à esquerda da pesquisa).
- **Painel de instrumentos** : Capacidade de desfocar imagens de fundo (Configuração -> Interface).
- **Ferramentas / Widgets** : A função *Aplicar em* mostra os comandos vinculados marcados, desmarcando um aplicará o widget principal padrão a este comando.
- **Widgets** : Adicionando um widget principal *sliderVertical*.
- **Update Center** : As atualizações são verificadas automaticamente quando a página é aberta, se for 120 minutos mais antiga.
- **Update Center** : A barra de progresso está agora na guia *Núcleo e plugins*, e o log aberto por padrão na guia *Informação*.
- **Update Center** : Se você abrir outro navegador durante uma atualização, a barra de progresso e o log indicarão.
- **Update Center** : Se a atualização terminar corretamente, é exibida uma janela pedindo para recarregar a página.
- **Atualizações principais** : Implementação de um sistema para limpar arquivos Core não utilizados antigos.
- **Cenas** : Adicionando um mecanismo de pesquisa (à esquerda do botão Executar).
- **Cenas** : Adição da função de idade (fornece a idade do valor da ordem).
- **Cenas** : *stateChanges()* agora aceite o período *Hoje* (meia-noite até agora), *ontem* e *dia* (por 1 dia).
- **Cenas** : FUNÇÕES *estatísticas (), média (), max (), min (), tendência (), duração()* : Bugfix ao longo do período *ontem*, e aceite agora *dia* (por 1 dia).
- **Cenas** : Possibilidade de desativar o sistema de cotação automática (Configurações → Sistema → Configuração : Commandes).
- **Cenas** : Visualizando um *Aviso* se nenhum gatilho estiver configurado.
- **Cenas** : Correção de bug de *selecionar* em bloco copiar / colar.
- **Cenas** : Copiar / colar do bloco entre diferentes cenários.
- **Cenas** : As funções desfazer / refazer estão agora disponíveis como botões (ao lado do botão de criação de bloco).
- **Cenas** :  adição de "Exportação histórica" (exportHistory)
- **Janela Variáveis de Cenário** : Ordenação alfabética na abertura.
- **Janela Variáveis de Cenário** : Os cenários usados pelas variáveis agora são clicáveis, com a abertura da pesquisa na variável.
- **Análise / História** : Ctrl Clique em uma legenda para exibir apenas esse histórico, Alt Clique para exibir todos eles.
- **Análise / História** : As opções *agrupamento, tipo, variação, escada* estão ativos apenas com uma única curva exibida.
- **Análise / História** : Agora podemos usar a opção *área* com a opção *Escada*.
- **Análise / História** : Possibilidade de comparar um histórico de acordo com o período atual.
- **Análise / Logs** : Nova fonte de tipo monoespaçado para logs.
- **Vista** : Possibilidade de colocar cenários.
- **Vista** : Modo de edição agora inserindo o bloco movido.
- **Vista** : Modo de edição: os ícones de atualização do equipamento são substituídos por um ícone que permite acesso à sua configuração, graças a um novo modal simplificado.
- **Vista** : A ordem de exibição agora é independente da ordem no painel.
- **Cronograma** : Separação das páginas de histórico e cronograma.
- **Cronograma** : Integração da linha do tempo no DB por motivos de confiabilidade.
- **Cronograma** : Gerenciamento de várias linhas do tempo.
- **Cronograma** : Completo redesenho gráfico da linha do tempo (Desktop / Mobile).
- **Resumo Global** : Visualização Resumo, suporte para resumos de um objeto diferente ou com um objeto raiz vazio (Desktop e WebApp).
- **Resumo Automation** : Equipamentos de plug-in desativados e seus controles não têm mais os ícones à direita (configuração do equipamento e configuração avançada).
- **Resumo Automation** : Capacidade de pesquisar nas categorias de equipamentos.
- **Resumo Automation** : Possibilidade de mover várias peças de equipamento de um objeto para outro.
- **Resumo Automation** : Possibilidade de selecionar todo o equipamento de um objeto.
- **Mecanismo de tarefas** : Na guia *Demônio*, plugins desativados não aparecem mais.
- **Relatório** : O uso de *cromo* se disponível.
- **Relatório** : Possibilidade de exportar cronogramas.
- **Configuração** : A guia *Informação* agora está na guia *Geral*.
- **Configuração** : A guia *Comandos* agora está na guia *Instalações*.
- **Janela de configuração avançada de equipamentos** : Alteração dinâmica da configuração do quadro de distribuição.
- **Instalações** : Nova categoria *Abertura*.
- **Instalações** : Possibilidade de inverter comandos do tipo cursor (informação e ação)
- **Instalações** : Possibilidade de adicionar css de classe a um bloco (consulte a documentação do widget).
- **Sobre a janela** : Adição de atalhos ao Changelog e FAQ.
- Páginas de Widgets / Objetos / Cenários / Interações / Plugins :
	- Ctrl Clic / Clic Center em um equipamento de widget, objeto, cenário, interação, plug-in : Abre em uma nova guia.
	- Ctrl Clic / Clic Center também disponível em seus menus de contexto (nas guias).
- Nova página ModalDisplay :
	- Menu Análise : Ctrl Clique / Clique em Central no *Tempo real* : Abra a janela em uma nova guia, em tela cheia.
	- Menu Ferramentas : Ctrl Clique / Clique em Central no *Anotações*, *Testador de expressão*, *Variáveis*, *Pesquisa* : Abra a janela em uma nova guia, em tela cheia.
- Bloco de código, editor de arquivos, personalização avançada : Adaptação tema escuro.<br/><br/>
- **WebApp** : Integração da nova página Resumo.
- **WebApp** : Na página Cenários, um clique no título do cenário exibe seu log.
- **WebApp** : Agora podemos selecionar / copiar parte de um log.
- **WebApp** : Na pesquisa em um log, adição de um botão x para cancelar a pesquisa.
- **WebApp** : Persistência da alternância do tema (8h).
- **WebApp** : Em um design, um clique com três dedos retorna à página inicial.
- **WebApp** : Exibição de cenários por grupo.
- **WebApp** : Nova fonte de tipo monoespaçado para logs.
- **WebApp** : Muitas correções de bugs (UI, retrato / paisagem iOS, etc.).<br/><br/>
- **Documentação** : Adaptações de acordo com v4 e v4.1.
- **Documentação** : Nova página *Atalhos de teclado / mouse* incluindo um resumo de todos os atalhos no Jeedom. Acessível no documento do Painel ou nas Perguntas frequentes.
- **Lib** : Atualizar o HighStock v7.1.2 a v8.2.0.
- **Lib** : Atualizar o jQuery v3.4.1 a v3.5.1.
- **Lib** : Atualizar fonte Awesome 5.9.0 a 5.13.1.
- Protegendo solicitações Ajax.
- Correções de bugs.
- Inúmeras otimizações de desempenho de desktop / dispositivos móveis.


>**IMPORTANTE**
>
> A inversão dos comandos binários não inverte mais apenas o widget, mas o valor do comando, será necessário, portanto, reconfigurar corretamente os cenários e os resumos que utilizam comandos do tipo binário invertido

[Changelog v4.0](/pt_PT/core/4.0/changelog)


