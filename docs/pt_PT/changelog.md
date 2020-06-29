
Changelog
=========

3.3.51
======

- Corrigido um problema ao calcular o horário do estado nos widgets se o fuso horário do Jeedom não fosse o mesmo do navegador

3.3.50
=====

- Correção de um problema ao parar a versão beta do DNS
- Melhoria do cálculo de acesso interno / externo (remoção da porta, se for padrão para o protocolo)

3.3.49
=====

- Início da atualização para o novo site de documentação

3.3.48
=====

- Correção de bug (atualização a ser absolutamente feita se você estiver no 3.3.47)

3.3.47
=====

- Correções de bugs
- Otimização do futuro sistema DNS

3.3.45
=====

- Correção de bug no aplicativo da web

3.3.44
=====

- Rotação automática da chave API de usuários administrativos a cada 3 meses. Posso desativá-lo (mas não é recomendado) no gerenciamento de usuários. Observe que esta atualização lança uma rotação de chaves de API para usuários administradores.
- Capacidade de inserir informações globais para sua casa na administração Jeedom (posição geográfica, altitude ...) para evitar a necessidade de digitá-las novamente em plug-ins ao criar equipamentos.
- Atualizando o repositório no smart
- Migração para o novo sistema de backup em nuvem (o sistema antigo permanecerá ativo por 1 semana e, se necessário, você poderá solicitar a disponibilidade dos backups antigos para suporte, passar esse período, o sistema antigo será excluído)
- Migração para o novo sistema de monitoramento (o sistema antigo permanecerá ativo por 1 semana, após o qual será excluído)

3.3.39
=====

- Nome da variável alterado $ key para $ key2 no evento de classe
- Limpar o código de plug-in / widget / cenário enviando para o mercado (economiza alguns segundos na exibição de plug-ins))
- Correção de um aviso na função lastBetween
- Melhor consideração dos widgets de plug-ins
- Otimização do cálculo de integridade no swap

>**IMPORTANTE**
>
>Esta atualização corrige uma preocupação que pode impedir a gravação de qualquer histórico a partir de 1 de janeiro de 2020. É mais do que altamente recomendável

3.3.38
=====

- Adição de compatibilidade global do DNS Jeedom com uma conexão à Internet 4G. (Importante se você usa o DNS Jeedom é que você tem uma conexão 4G, você deve verificar na configuração do DNS Jeedom a caixa correspondente).
- Correções ortográficas.
- Correção de segurança

3.3.37
=====

- Correções de bugs

3.3.36
=====

- Adição de arredondamento no número de dias desde a última troca de bateria
- Correções de bugs

3.3.35
=====

- Correções de bugs
- Possibilidade de instalar plugins diretamente do mercado

3.3.34
=====

- Corrigido um erro que poderia impedir o status da bateria de voltar
- Correção de um bug nas tags nas interações
- O status "timeout" (não comunicação) do equipamento agora tem prioridade sobre o status "aviso" ou "perigo""
- Correção de bug em backups na nuvem

3.3.33
=====

- Correções de bugs

3.3.32
=====

- Correções de bugs
- Suporte móvel para sliders em designs
- SMART : otimização do gerenciamento de swap

3.3.31
=====

- Correções de bugs

3.3.30
=====

- Correção de um bug na exibição de sessões do usuário
- Atualização da documentação
- Remoção da atualização de gráficos em tempo real, após vários bugs relatados
- Correção de um bug que poderia impedir a exibição de certos logs
- Correção de um bug no serviço de monitoramento
- Correção de um bug na página "Análise de equipamento", a data de atualização da bateria agora está correta 
- Melhoria da ação remove_inat nos cenários

3.3.29
=====

- Correção do desaparecimento da data da última verificação de atualização
- Corrigido um erro que poderia bloquear backups na nuvem
- Correção de um erro no cálculo do uso das variáveis se elas estiverem no formato : variável (toto, mavaleur)


3.3.28
=====

- Corrigido um erro infinito na roda na página de atualizações
- Várias correções e otimizações

3.3.27
=====

- Correção de um erro na tradução dos dias em francês
- Estabilidade aprimorada (reinício automático do serviço MySql e watchdog para verificar a hora de início)
- Correções de bugs
- Desativando ações em pedidos ao editar designs, visualizações ou painéis

3.3.26
=====

- Correções de bugs
- Correção de um bug no multi-lançamento do cenário
- Correção de um erro nos alertas sobre o valor dos pedidos

3.3.25
=====

- Correções de bugs
- Alternando a linha do tempo para o modo de tabela (devido a erros na biblioteca Jeedom independente)
- Adição de classes para suporte a cores no plugin mode


3.3.24
=====

-   Correção de um bug na exibição do número de atualizações
-	Foi removida a edição do código HTML da configuração avançada de comandos devido a muitos erros
-	Correções de bugs
-	Melhoria da janela de seleção de ícones
-	Atualização automática da data de troca da bateria se a bateria for mais de 90% e 10% maior que o valor anterior
-	Adição de botão na administração para redefinir os direitos e iniciar uma verificação Jeedom (direita, cron, banco de dados...)
-	Remoção de opções avançadas de visibilidade para equipamentos no painel / visualização / design / dispositivos móveis. Agora, se você deseja ver ou não o equipamento no painel / dispositivo móvel, marque ou não a caixa de visibilidade geral. Para vistas e design, basta colocar ou não o equipamento nele

3.3.22
=====

- Correções de bugs
- Substituição aprimorada de pedidos (em visualizações, plano e plano3d)
- Corrigido um bug que poderia impedir a abertura de certos equipamentos de plug-ins (alarme ou tipo virtual))

3.3.21
=====

- Corrigido um erro em que a exibição da hora podia exceder 24h
- Correção de um bug na atualização de resumos de design
- Correção de um bug no gerenciamento dos níveis de alertas em determinados widgets durante a atualização do valor
- Exibição fixa de equipamento desativado em alguns plugins
- Correção de um erro ao indicar troca de bateria no Jeedom
- Exibição aprimorada de logs ao atualizar o Jeedom
- Correção de bug ao atualizar uma variável (que nem sempre iniciava os cenários ou não acionava uma atualização dos comandos em todos os casos)
- Corrigido um erro nos backups em nuvem ou a duplicidade não estava sendo instalada corretamente
- Melhoria do TTS interno em Jeedom
- Melhoria do sistema de verificação de sintaxe cron


3.3.20
=====

- Correção de um bug nos cenários ou eles podem permanecer bloqueados em "em andamento" enquanto estão desativados
- Corrigido um problema ao iniciar um cenário não planejado
- Correção de bug de fuso horário

3.3.19
=====
- Correções de bugs (especialmente durante a atualização)


3.3.18
=====
- Correções de bugs

3.3.17
=====

- Correção de um erro nos backups do samba

3.3.16
=====

-   Capacidade de excluir uma variável.
-   Adicionando uma tela 3D (beta)
-   Redesenho do sistema de backup em nuvem (backup incremental e criptografado).
-   Adicionando um sistema integrado de anotações (em Análise -> Nota).
-   Adição da noção de tag no equipamento (pode ser encontrada na configuração avançada do equipamento).
-   Adição de um sistema de histórico para exclusão de pedidos, equipamentos, objetos, visualização, design, design 3d, cenário e usuário.
-   Adição da ação jeedom_reboot para iniciar uma reinicialização do Jeedom.
-   Adicionar opção na janela de geração do cron.
-   Uma mensagem é adicionada agora se uma expressão inválida for encontrada ao executar um cenário.
-   Adicionando um comando nos cenários : value (order) permite ter o valor de um pedido se não for fornecido automaticamente pelo jeedom (caso ao armazenar o nome do pedido em uma variável).
-   Adição de um botão para atualizar as mensagens do centro de mensagens.
-   Inclua na configuração da ação no valor de um comando um botão para procurar uma ação interna (cenário, pausa...).
-   Adição de uma ação "Redefinir para zero do IS" nos cenários
-   Capacidade de adicionar imagens em segundo plano nas visualizações
-   Capacidade de adicionar imagens de plano de fundo em objetos
-   As informações de atualização disponíveis agora estão ocultas para usuários não administradores
-   Suporte aprimorado para () no cálculo de expressões
-   Possibilidade de editar os cenários no modo texto / json
-   Inclusão na página de funcionamento de uma verificação de espaço livre para o Jeedom tmp
-   Capacidade de adicionar opções nos relatórios
-   Adição de uma pulsação pelo plug-in e reinicialização automática do daemon em caso de problemas
-   Adição de ouvintes na página do mecanismo de tarefas
-   Optimisations
-   Possibilidade de consultar os logs na versão móvel (wepapp)
-   Adição de uma tag de ação nos cenários (consulte a documentação)
-   Possibilidade de ter uma visualização em tela cheia adicionando "& fullscreen = 1" no URL
-   Adição de lastCommunication nos cenários (para ter a última data de comunicação de um equipamento)
-   Atualização em tempo real de gráficos (simples, não calculados ou cronogramas)
-   Capacidade de excluir um elemento da configuração do design
-   Possibilidade de ter um relatório sobre o nível da bateria (relatório do equipamento)
-   Agora, os widgets de cenário são exibidos por padrão no painel
-   Altere o tom dos widgets pelas horizontais 25 a 40, vertical 5 a 20 e margem 1 a 4 (você pode redefinir os valores antigos na configuração de jeedom, guia widget)
-   Possibilidade de colocar um ícone nos cenários
-   Adição de gerenciamento de daemon no mecanismo de tarefas
-   Adição da função color_gradient nos cenários

3.2.16
=====

- Correção de um bug durante a instalação de dependência de determinados plugins no smart

3.2.15
=====

- Correção de um erro ao salvar o equipamento

3.2.14
=====

- Preparação para evitar um erro ao mudar para 3.3.X
- Correção de um problema ao solicitar suporte para plug-ins de terceiros

3.2.12
=====

- Correções de bugs
- Optimisations

3.2.11
=====

- Correções de bugs.

3.2.10
=====

- Correções de bugs.
- Sincronização aprimorada com o mercado.
- Melhoria do processo de atualização, em particular na cópia de arquivos, que agora verifica o tamanho do arquivo copiado.
- Correções de erros nas funções stateDuration, lastStateDuration e lastChangeStateDuration (obrigado @kiboost).
- Otimização do cálculo do gráfico de links e uso de variáveis.
- Melhoria da janela de detalhes da tarefa cron, que agora exibe o cenário, bem como a ação que será executada para as tarefas em doin (obrigado @kiboost).

3.2.9
=====

- Correções de bugs
- Correção de um bug nos ícones do editor de arquivos e no testador de expressões
- Correções de erros nos ouvintes
- Adição de um alerta se um plug-in bloquear crons
- Correção de um bug no sistema de monitoramento em nuvem se a versão do agente for menor que 3.X.X

3.2.8
=====

- Correções de bugs
- Adição de uma opção na administração Jeedom para especificar o intervalo de ip local (útil em instalações do tipo docker)
- Correção de um erro no cálculo do uso de variáveis
- Adição de um indicador na página de integridade, indicando o número de processos mortos por falta de memória (no geral, indica que o jeedom está muito carregado)
- Editor de arquivo aprimorado

3.2.7
=====

- Correções de bugs
- Atualização do Documentos
- Possibilidade de usar as tags nas condições dos blocos "A" e "IN"
- Correção de erros de categorias de mercado para widgets / scripts / cenários...

3.2.6.
=====

- Correções de bugs
- Atualização do Documentos
- Padronização dos nomes de certas ordens nos cenários
- Otimização de desempenho

3.2.5
=====

- Correções de bugs
- Reativação de interações (inativa por causa da atualização)

3.2.4
=====

- Correções de bugs
- Correção de um bug em determinado modal em espanhol
- Correção de um erro de cálculo no time_diff
- Preparação para o futuro sistema de alerta

3.2.3.
=====

-   Correção de erros nas funções mín. / Máx....
-   Exportação aprimorada de gráficos e exibição no modo de tabela

3.2.2.
=====

-   Remoção do antigo sistema de atualização de widgets (descontinuado desde a versão 3.0). Atenção, se o seu widget não usar o novo sistema, existe o risco de mau funcionamento (duplicação deste neste caso). Widget de exemplo [aqui](https://github.com/jeedom/core/tree/beta/core/template/dashboard)
-   Possibilidade de exibir os gráficos em forma de tabela ou exportá-los em csv ou xls

-   Os usuários agora podem adicionar sua própria função php para cenários. Consulte a documentação dos cenários para implementação

-   JEED-417 : adição de uma função time_diff nos cenários

-   Adição de um atraso configurável antes da resposta nas interações (permite aguardar o retorno do status, por exemplo)

-   JEED-365 : Remoção do "comando de informações do usuário" a ser substituído por ações na mensagem. Permite iniciar vários comandos diferentes, iniciar um cenário ... Atenção, se você tivesse um "comando de informações do usuário", ele deverá ser reconfigurado.

-   Adicione uma opção para abrir facilmente um acesso ao suporte (na página do usuário e ao abrir um ticket)

-   Correção de um bug de direitos após a restauração de um backup

-   Atualizando traduções

-   Atualização da biblioteca (jquery e highcharts)

-   Possibilidade de proibir uma ordem nas interações
    automatique

-   Interações automáticas aprimoradas

-   Correção de bug no gerenciamento de sinônimos de interações

-   Adição de um campo de pesquisa do usuário para conexões LDAP / AD
    (torna o Jeedom AD compatível)

-   Correções ortográficas (graças a dab0u por seu enorme trabalho)

-   JEED-290 : Não podemos mais nos conectar com identificadores por
    padrão (admin / admin) remotamente, somente a rede local está autorizada

-   JEED-186 : Agora podemos escolher a cor de fundo no
    designs

-   Para o bloco A, possibilidade de definir uma hora entre as 12h01 e as 12h59
    simplesmente colocando os minutos (ex 30 para 00:30)

-   Adicionando sessões e dispositivos ativos registrados no
    página de perfil do usuário e página de gerenciamento
    utilisateurs

-   JEED-284 : conexão permanente agora depende de uma chave
    único usuário e dispositivo (em vez de usuário)

-   JEED-283 : adicionando um modo *resgate* para jeedom adicionando & rescue = 1
    na url

-   JEED-8 : adição do nome do cenário no título da página durante o
    a edição

-   Otimização de mudanças organizacionais (tamanho dos widgets,
    posição do equipamento, posição dos controles) no painel
    e as vistas. Atenção agora as modificações não são
    salvo somente ao sair do modo de edição.

-   JEED-18 : Adicionando logs ao abrir um ticket para suportar

-   JEED-181 : adição de um comando de nome nos cenários para ter
    o nome do pedido ou equipamento ou objeto

-   JEED-15 : Adicione bateria e alerta no aplicativo da web

-   Correção de bugs para mover objetos de design no Firefox

-   JEED-19 : Durante uma atualização, agora é possível
    atualize o script de atualização antes de atualizar

-   JEED-125 : link adicionado para redefinir a documentação
    senha

-   JEED-2 : Gerenciamento de tempo aprimorado durante uma reinicialização

-   JEED-77 : Adição de gerenciamento de variáveis na API http

-   JEED-78 : adição da função de tag para cenários. Tenha cuidado lá
    nos cenários que usam as tags passam de \#montag\#
    marcar (montag)

-   JEED-124 : Corrigir o gerenciamento de tempos limite do cenário

-   Correções de bugs

-   Capacidade de desativar uma interação

-   Adicionando um editor de arquivos (reservado para
    usuários experientes)

-   Adição de genéricos Tipos "State Light" (Binário), "Light
    Temperatura da cor "(informações)," Temperatura da cor clara "(Ação)

-   Capacidade de tornar as palavras obrigatórias em uma interação

3.1.7
=====

-   Correções de bugs (especialmente em logs e
    funções estatísticas)

-   Melhoria do sistema de atualização com uma página de notas
    versão (que você deve verificar antes de cada atualização
    Dia !!!!)

-   Correção de um bug que recuperou os logs durante restaurações

3.1
===

-   Correções de bugs

-   Otimização global do Jeedom (nas classes de carregamento de
    plugins, tempo quase dividido por 3)

-   Suporte do Debian 9

-   Modo Onepage (mudança de página sem recarregar a página inteira, apenas
    a parte que muda)

-   Adicione uma opção para ocultar objetos no painel, mas que
    vamos sempre tê-los na lista

-   Clique duas vezes em um nó no gráfico do link (exceto para
    variáveis) traz em sua página de configuração

-   Capacidade de colocar o texto à esquerda / direita / centro no
    designs para elementos de texto / visualização / design

-   Adicionando resumos de objetos no painel (lista de objetos
    para a esquerda)

-   Adicionar interações do tipo "notifique-me"

-   Revisão da página inicial do cenário

-   Adicionar histórico de comandos para comandos SQL ou do sistema
    na interface Jeedom

-   Possibilidade de ter gráficos de históricos de pedidos em
    webapp (mantendo pressionado o comando)

-   Adição do andamento da atualização do aplicativo da web

-   Recuperação em caso de erro de atualização do webapp

-   Eliminação de cenários "simples" (redundantes com a configuração
    pedidos avançados)

-   Adicione hachura nos gráficos para distinguir os dias

-   Redesign da página de interações

-   Redesign da página de perfil

-   Redesign da página de administração

-   Adicionando uma "integridade" a objetos

-   Correção de bug no nível da bateria do equipamento

-   Adição de método no núcleo para o gerenciamento de comandos mortos
    (deve então ser implementado no plugin)

-   Possibilidade de registrar comandos de texto

-   Na página de histórico, agora você pode fazer o gráfico
    de um cálculo

-   Adicionando um gerenciamento de fórmula de cálculo para históricos

-   Atualização de toda a documentação :

    -   Todos os documentos foram revisados

    -   Exclusão de imagens para facilitar a atualização e
        multilingue

-   Mais opções são possíveis nas configurações de tamanho da zona no
    vues

-   Possibilidade de escolher a cor do texto do resumo do objeto

-   Adição de uma ação remove\_inat nos cenários que permitem
    cancelar toda a programação dos blocos DANS / A

-   Capacidade de design de widgets ao passar o mouse para escolher
    posição do widget

-   Adicionando um parâmetro de resposta\_cmd nas interações para especificar
    o ID do comando a ser usado para responder

-   Adicionando uma linha do tempo na página de histórico (atenção deve ser
    ativado em cada comando e / ou cenário que você deseja
    veja apareça)

-   Possibilidade de esvaziar os eventos da linha do tempo

-   Possibilidade de esvaziar os IPs banidos

-   Correção / aprimoramento do gerenciamento de contas de usuário

    -   Capacidade de excluir uma conta de administrador básica

    -   Impedindo que o último administrador volte ao normal

    -   Segurança adicionada para impedir a exclusão da conta com
        qual deles está conectado

-   Possibilidade na configuração avançada de equipamentos para colocar
    o layout dos comandos nos widgets no modo de tabela em
    escolhendo para cada pedido a caixa ou colocá-lo

-   Capacidade de reorganizar widgets de equipamentos de
    painel (no modo de edição, clique com o botão direito do mouse no widget)

-   Alterar o tom dos widgets (de 40 \*80 a 10 \*10) Tenha cuidado
    impactará o layout em seu painel / visualização / design

-   Possibilidade de atribuir um tamanho de 1 a 12 a objetos no
    dashboard

-   Capacidade de lançar independentemente ações de cenário (e
    plug-in modo / alarme, se compatível) em paralelo com os outros

-   Possibilidade de adicionar um código de acesso a um design

-   Adição de um cão de guarda independente da Jeedom para verificar o status de
    MySql e Apache

3.0.11
======

-   Correção de bugs nas solicitações de tempo limite "ask"

3.0.10
======

-   Correção de bug na interface para configurar interações

3.0
===

-   Supressão do modo escravo

-   Capacidade de desencadear um cenário em uma mudança de
    variable

-   Atualizações variáveis agora acionam a atualização
    pedidos de equipamentos virtuais (você precisa da versão mais recente
    plugin)

-   Possibilidade de ter um ícone nos comandos do tipo de informação

-   Capacidade de comandos para exibir o nome e o ícone

-   Adição de uma ação de "alerta" em cenários : mensagem em
    jeedom

-   Adição de uma ação "pop-up" em cenários : mensagem para validar

-   Os widgets de comando agora podem ter um método
    atualização que evita uma chamada ajax para o Jeedom

-   Agora, os widgets de cenário são atualizados sem chamadas ajax
    para obter o widget

-   O resumo global e as peças agora são atualizados sem apelação
    ajax

-   Um clique em um elemento de um resumo de automação residencial leva você a uma visualização
    detalhado disso

-   Agora você pode inserir resumos de tipo
    texte

-   Mudança do controle deslizante de bootstraps para controle deslizante
    evento de controle deslizante duplo)

-   Salvamento automático de visualizações ao clicar no botão "veja o
    Resultado"

-   Possibilidade de ter os documentos localmente

-   Desenvolvedores de terceiros podem adicionar seu próprio sistema de
    gerenciamento de tickets

-   Redesenho da configuração dos direitos do usuário (tudo está no
    página de gerenciamento de usuários)

-   Atualização de libs : jquery (em 3.0), jquery móvel, hightstock
    e classificador de tabelas, incrível para fontes

-   Grande melhoria nos projetos:

    -   Todas as ações agora estão acessíveis a partir de um
        clique direito

    -   Possibilidade de adicionar um único pedido

    -   Capacidade de adicionar um fluxo de imagem ou vídeo

    -   Capacidade de adicionar zonas (local clicável) :

        -   Área do tipo macro : lança uma série de ações durante um
            clique nele

        -   Área do tipo binário : lança uma série de ações durante um
            clique nele, dependendo do status de um pedido

        -   Área do tipo de widget : exibe um widget ao clicar ou passar o mouse
            da área

    -   Otimização geral de código

    -   Possibilidade de exibir uma grade e escolher sua
        tamanho (10x10,15x15 ou 30x30)

    -   Possibilidade de ativar uma magnetização dos widgets na grade

    -   Possibilidade de ativar uma magnetização dos widgets entre eles

    -   Certos tipos de widgets agora podem ser duplicados

    -   Capacidade de bloquear um item

-   Agora, os plug-ins podem usar sua chave de API
    propre

-   Adicionando interações automáticas, o Jeedom tentará entender
    sentença, execute a ação e responda

-   Adicionado gerenciamento de demônios na versão móvel

-   Adição de gerenciamento de cron na versão móvel

-   Adição de determinadas informações de saúde na versão móvel

-   Adicionando módulos em alerta à página da bateria

-   Objetos sem um widget são ocultados automaticamente no painel

-   A adição de um botão na configuração avançada de um
    equipamento / de um comando para ver os eventos de
    esse aqui

-   Os gatilhos para um cenário agora podem ser
    conditions

-   Clique duas vezes na linha de comando (na página
    agora abre a configuração avançada do
    celle-ci

-   Possibilidade de proibir certos valores para um pedido (no
    configuração avançada dele)

-   Adição de campos de configuração no feedback automático de status
    (ex retorne a 0 após 4 min) na configuração avançada de um
    commande

-   Adicionando uma função valueDate nos cenários (consulte
    documentação do cenário)

-   Possibilidade em cenários de modificar o valor de um pedido
    com o evento action ""

-   Adição de um campo de comentário na configuração avançada de um
    équipement

-   Adição de um sistema de alerta para pedidos com 2 níveis :
    alerta e perigo. A configuração está na configuração
    comandos avançados (apenas o tipo de informação, é claro). Você pode
    veja os módulos em alerta na página Análise → Equipamento. Você
    pode configurar as ações em alerta na página de
    configuração geral do Jeedom

-   Adição de uma área de "tabela" nas vistas que permite exibir um ou mais
    várias colunas por caixa. As caixas também suportam código HTML

-   O Jeedom agora pode ser executado sem direitos de root (experimental).
    Tenha cuidado, porque sem direitos de root você terá que iniciar manualmente
    scripts para dependências de plugins

-   Otimização de cálculos de expressão (cálculo apenas de tags
    se presente na expressão)

-   Adição na API de função para acessar o resumo (global
    e objeto)

-   Capacidade de restringir o acesso a cada chave de API com base em
    l'IP

-   Possibilidade na história de fazer agrupamentos por hora ou
    Ano

-   O tempo limite no comando wait agora pode ser um cálculo

-   Correção de um bug se houver "nos parâmetros de uma ação

-   Alterne para sha512 para o hash da senha (sha1
    sendo comprometido)

-   Corrigido um erro no gerenciamento de cache que fazia crescer
    indefinidamente

-   Correção do acesso ao documento de plugins de terceiros, se eles não tiverem
    nenhum documento local

-   As interações podem levar em consideração a noção de contexto (em
    função da solicitação anterior e da função anterior)

-   Possibilidade de ponderar palavras de acordo com seu tamanho para
    análise de entendimento

-   Agora os plug-ins podem adicionar interações

-   Agora as interações podem retornar arquivos, além de
    a resposta

-   Possibilidade de ver na página de configuração dos plugins o
    funcionalidade destes (interagir, cron…) e desativá-los
    unitairement

-   Interações automáticas podem retornar valores de
    Sumários

-   Capacidade de definir sinônimos para objetos, equipamentos,
    comandos e resumos que serão usados nas respostas
    contextual e resumos

-   O Jeedom sabe como gerenciar várias interações vinculadas (contextualmente)
    em um. Eles devem ser separados por uma palavra-chave (por padrão e).
    Exemplo : "Quanto custa no quarto e na sala de estar? "Ou
    "Ligue a luz da cozinha e do quarto."

-   O status dos cenários na página de edição agora está definido como
    dia dinamicamente

-   Possibilidade de exportar uma visualização em PDF, PNG, SVG ou JPEG com o
    Comando "report" em um cenário

-   Possibilidade de exportar um design em PDF, PNG, SVG ou JPEG com o
    Comando "report" em um cenário

-   Possibilidade de exportar um painel de um plugin em PDF, PNG, SVG ou JPEG
    com o comando "report" em um cenário

-   Adicionando uma página de gerenciamento de relatórios (para baixar novamente ou
    exclua-os)

-   Correção de um bug na data da última escalação de um evento
    para alguns plugins (alarme)

-   Corrigido o erro de exibição com o Chrome 55

-   Otimização de backup (em um RPi2, o tempo é dividido por 2)

-   Otimização de catering

-   Otimização do processo de atualização

-   Padronização do tmp jeedom, agora tudo está em / tmp / jeedom

-   Possibilidade de ter um gráfico dos diferentes links de um cenário,
    equipamento, objeto, comando ou variável

-   Capacidade de ajustar a profundidade dos gráficos do link
    função do objeto original

-   Possibilidade de ter logs de cenário em tempo real (diminui a velocidade
    a execução dos cenários)

-   Capacidade de passar tags ao iniciar um cenário

-   Otimização do carregamento de cenários e páginas usando
    ações com opção (tipo de configuração do plug-in ou modo de alarme)

2.4.6
=====

-   Melhoria da gestão da repetição dos valores de
    commandes

2.4.5
=====

-   Correções de bugs

-   Verificação otimizada de atualização

2.4
---

-   Otimização geral

    -   Agrupamento de consultas SQL

    -   Excluir solicitações desnecessárias

    -   Cache Pid, status e último lançamento de cenários

    -   Cache Pid, status e último lançamento de crons

    -   Em 99% dos casos, mais pedidos de escrita na base em
        operação nominal (portanto, exceto a configuração Jeedom,
        modificações, instalação, atualização)

-   Supressão de fail2ban (porque facilmente ignorado enviando um
    endereço IP falso), isso acelera o Jeedom

-   Adição nas interações de uma opção sem categoria para que
    podemos gerar interações em equipamentos sem
    Categoria

-   Adição nos cenários de um botão de escolha de equipamento no
    comandos de controle deslizante

-   Atualização do Bootstrap na 2.3.7

-   A adição da noção de resumo da automação residencial (permite conhecer um
    Em um único tiro, o número de luzes acesas, as portas abertas, o
    persianas, janelas, energia, detecções de movimento…).
    Tudo isso está configurado na página de gerenciamento de objetos

-   Adicionando pedidos pré e pós a um pedido. Permite disparar
    o tempo todo uma ação antes ou depois de outra ação. Também pode
    permitir sincronização de equipamentos, por exemplo, que 2
    luzes sempre acendem junto com a mesma intensidade.

-   Otimização do ouvinte

-   Inclua modal para exibir informações brutas (atributo de
    objeto na base) de um equipamento ou ordem

-   Capacidade de copiar o histórico de um pedido para outro
    commande

-   Capacidade de substituir um pedido por outro em todos os Jeedom
    (mesmo que o comando a ser substituído não exista mais)

2.3
---

-   Correção de filtros no mercado

-   Correção de caixas de seleção na página para editar visualizações (em um
    área de gráficos)

-   Correção do histórico da caixa de seleção, visível e reversa no
    painel de controle

-   Correção de um problema com a tradução de javascripts

-   Adicionando uma categoria de plug-in : objeto de comunicação

-   Adicionando GENERIC\_TYPE

-   Remoção de filtros novos e principais no curso de plugins
    do mercado

-   Renomeando a categoria padrão no curso dos plug-ins do
    mercado em "Principais e novos"

-   Correção de filtros gratuitos e pagos no curso de plugins
    do mercado

-   Correção de um bug que pode levar à duplicação das curvas
    na página do histórico

-   Correção de um bug no valor do tempo limite dos cenários

-   corrigido um bug na exibição de widgets em visualizações que
    pegou a versão do painel

-   Correção de um bug nos desenhos que poderiam usar o
    configuração de widgets do painel em vez de designs

-   Correção de erros de backup / restauração se o nome do jeedom
    contém caracteres especiais

-   Otimização da organização da lista de tipos genéricos

-   Exibição aprimorada da configuração avançada de
    équipements

-   Correção da interface de acesso de backup de

-   Salvando a configuração durante o teste de mercado

-   Preparação para a remoção do bootstrapswtich nos plugins

-   Correção de um bug no tipo de widget solicitado para designs
    (painel em vez de dplan)

-   correção de bug no manipulador de eventos

-   alternância aleatória do backup noturno (entre 2h10 e 3h59) para
    evitar preocupações com sobrecarga de mercado

-   Corrigir mercado de widgets

-   Correção de um bug no acesso ao mercado (timeout)

-   Correção de um bug na abertura de tickets

-   Corrigido um erro de página em branco durante a atualização se o
    / tmp é muito pequeno (cuidado, a correção entra em vigor em
    atualização n + 1)

-   Adicionando uma tag *jeedom\_name* nos cenários (dê o nome
    jeedom)

-   Correções de bugs

-   Mova todos os arquivos temporários para / tmp

-   Melhor envio de plugins (dos2unix automático em
    arquivos \*. sh)

-   Redesign da página de log

-   Adição de um tema darksobre para celular

-   Capacidade para os desenvolvedores adicionarem opções
    configuração do widget em widgets específicos (tipo sonos,
    koubachi e outros)

-   Otimização de logs (obrigado @ kwizer15)

-   Capacidade de escolher o formato do log

-   Várias otimizações do código (obrigado @ kwizer15)

-   Passagem no módulo de conexão com o mercado (permitirá ter
    uma jeedom sem link para o mercado)

-   Adição de um "repo" (conexão do tipo módulo de conexão com
    mercado) (permite enviar um zip contendo o plug-in)

-   A adição de um "repo" do github (permite usar o github como fonte de
    plugin, com sistema de gerenciamento de atualizações)

-   Adição de um "repo" de URL (permite usar o URL como fonte de plug-in)

-   Adição de um "repositório" do Samba (utilizável para enviar backups em um
    servidor samba e recuperar plugins)

-   Adição de um "repositório" de FTP (utilizável para enviar backups em um
    Servidor FTP e recuperar plugins)

-   Aditamento a certos "acordos de recompra" da possibilidade de recuperar o núcleo de
    jeedom

-   Adicionando testes automáticos de código (obrigado @ kwizer15)

-   Capacidade de mostrar / ocultar painéis de plugins no celular e
    ou desktop (cuidado agora, por padrão, os painéis estão ocultos)

-   Capacidade de desativar atualizações de plug-ins (bem como
    o processo de cheking)

-   Capacidade de forçar a versificação das atualizações de plugins

-   Pequena reformulação do centro de atualização

-   Possibilidade de desativar a verificação automática de atualização
    jour

-   Corrigido um erro que redefinia todos os dados para 0 após uma
    Reiniciar

-   Possibilidade de configurar diretamente o nível de log de um plug-in
    na página de configuração

-   Possibilidade de consultar os logs de um plugin diretamente no
    página de configuração dele

-   Supressão do início de depuração de demônios, mantendo o nível
    dos logs do daemon é igual ao do plug-in

-   Limpeza de terceiros Lib

-   Supressão da voz responsiva (função mencionada nos cenários que
    trabalhou menos e menos bem)

-   Correção de várias vulnerabilidades de segurança

-   A adição de um modo síncrono nos cenários (anteriormente
    modo rápido)

-   Possibilidade de inserir manualmente a posição dos widgets em% em
    os desenhos

-   Redesign da página de configuração de plug-ins

-   Capacidade de configurar a transparência dos widgets

-   Adicionada ação jeedom\_poweroff nos cenários para parar
    jeedom

-   Retorno do cenário de ação\_return para retornar a um
    interação (ou outra) de um cenário

-   Passando por longas pesquisas para atualizar a interface a tempo
    real

-   Correção de um bug durante a atualização de vários widgets

-   Otimização da atualização de widgets de comando e equipamento

-   Adicionando uma tag *begin\_backup*, *end\_backup*, *begin\_update*,
    *fim\_update*, *begin\_restore*, *end\_restore* nos cenários

2.2
---

-   Correções de bugs

-   Simplificação do acesso às configurações de plugins de
    a página de saúde

-   Adição de um ícone indicando se o daemon foi iniciado na depuração ou não

-   Adição de uma página de configuração do histórico global
    (acessível a partir da página de histórico)

-   Correção de bug do Docker

-   Capacidade de permitir que um usuário se conecte apenas a
    de uma estação na rede local

-   Redesenho da configuração de widgets (tenha cuidado
    certamente retomar a configuração de alguns widgets)

-   Reforço do tratamento de erros em widgets

-   Capacidade de reordenar visualizações

-   Revisão do gerenciamento de temas

2.1
---

-   Redesenho do sistema de cache Jeedom (uso de
    doutrina oculta). Isso permite, por exemplo, conectar o Jeedom a um
    servidor redis ou memcached. Por padrão, o Jeedom usa um sistema de
    arquivos (e não mais o banco de dados MySQL, que permite baixar um
    bit), está em / tmp, portanto, é recomendável que você
    possui mais de 512 MB de RAM para montar o / tmp em tmpfs (na RAM para
    mais rápido e menos desgaste no cartão SD, eu
    recomendar um tamanho de 64MB). Tenha cuidado ao reiniciar
    Como o cache é esvaziado, você precisa aguardar o
    comunicação de todas as informações

-   Redesenho do sistema de log (uso de monolog) que permite
    integração com sistemas de log (tipo syslog (d))

-   Otimização do carregamento do painel

-   Corrigido muitos avisos

-   Possibilidade durante uma chamada de API a um cenário para passar tags
    na url

-   Suporte Apache

-   Otimização do docker com suporte oficial ao docker

-   Otimização para sinologia

-   Suporte + otimização para php7

-   Redesenho do menu Jeedom

-   Excluir toda a parte de gerenciamento de rede : wifi, ip fixo…
    (certamente voltará como um plugin). ATENÇÃO, este não é o
    modo mestre / escravo jeedom que é excluído

-   Indicação de bateria removida nos widgets

-   Adição de uma página que resume o status de todos os equipamentos
    batterie

-   Redesenho do DNS Jeedom, uso do openvpn (e, portanto, do
    Openvpn plugin)

-   Atualizar todas as bibliotecas

-   Interação : adição de um sistema de análise (permite
    remover interações com erros de sintaxe de tipo grande «
    o quarto »)

-   Supressão da atualização da interface por nodejs (altere para
    puxando cada segundo na lista de eventos)

-   Possibilidade de aplicativos de terceiros solicitarem através da API
    eventos

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    várias ações e também a adição de todas as ações possíveis
    nos cenários (tenha cuidado, pode demorar
    reconfigurar após a atualização)

-   Capacidade de desativar um bloco em um cenário

-   Adição para desenvolvedores de um sistema de ajuda de dicas de ferramentas. Você deve
    sur un label mettre la classe « help » e mettre un attribut
    ajuda de dados com a mensagem de ajuda desejada. Isso permite que o Jeedom
    adicione automaticamente um ícone no final do seu marcador « ? » et
    ao passar o mouse para exibir o texto de ajuda

-   Mudança no processo principal de atualização, não solicitamos mais
    o arquivo no Market, mas agora no Github agora

-   Adição de um sistema centralizado para instalar dependências em
    plugins

-   Redesign da página de gerenciamento de plug-ins

-   Adicionando endereços MAC das diferentes interfaces

-   Adicionada conexão de autenticação dupla

-   Remoção de conexão hash (por motivos de segurança)

-   Adicionando um sistema de administração de SO

-   Adição de widgets Jeedom padrão

-   Adicionando um sistema beta para encontrar o IP do Jeedom na rede
    (você precisa conectar o Jeedom na rede, depois ir ao mercado e
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Adição à página de cenários de um testador de expressão

-   Revisão do sistema de compartilhamento de cenários

2.0
---

-   Redesenho do sistema de cache Jeedom (uso de
    doutrina oculta). Isso permite, por exemplo, conectar o Jeedom a um
    servidor redis ou memcached. Por padrão, o Jeedom usa um sistema de
    arquivos (e não mais o banco de dados MySQL, que permite baixar um
    bit), está em / tmp, portanto, é recomendável que você
    possui mais de 512 MB de RAM para montar o / tmp em tmpfs (na RAM para
    mais rápido e menos desgaste no cartão SD, eu
    recomendar um tamanho de 64MB). Tenha cuidado ao reiniciar
    Como o cache é esvaziado, você precisa aguardar o
    comunicação de todas as informações

-   Redesenho do sistema de log (uso de monolog) que permite
    integração com sistemas de log (tipo syslog (d))

-   Otimização do carregamento do painel

-   Corrigido muitos avisos

-   Possibilidade durante uma chamada de API a um cenário para passar tags
    na url

-   Suporte Apache

-   Otimização do docker com suporte oficial ao docker

-   Otimização para sinologia

-   Suporte + otimização para php7

-   Redesenho do menu Jeedom

-   Excluir toda a parte de gerenciamento de rede : wifi, ip fixo…
    (certamente voltará como um plugin). ATENÇÃO, este não é o
    modo mestre / escravo jeedom que é excluído

-   Indicação de bateria removida nos widgets

-   Adição de uma página que resume o status de todos os equipamentos
    batterie

-   Redesenho do DNS Jeedom, uso do openvpn (e, portanto, do
    Openvpn plugin)

-   Atualizar todas as bibliotecas

-   Interação : adição de um sistema de análise (permite
    remover interações com erros de sintaxe de tipo grande «
    o quarto »)

-   Supressão da atualização da interface por nodejs (altere para
    puxando cada segundo na lista de eventos)

-   Possibilidade de aplicativos de terceiros solicitarem através da API
    eventos

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    várias ações e também a adição de todas as ações possíveis
    nos cenários (tenha cuidado, pode demorar
    reconfigurar após a atualização)

-   Capacidade de desativar um bloco em um cenário

-   Adição para desenvolvedores de um sistema de ajuda de dicas de ferramentas. Você deve
    sur un label mettre la classe « help » e mettre un attribut
    ajuda de dados com a mensagem de ajuda desejada. Isso permite que o Jeedom
    adicione automaticamente um ícone no final do seu marcador « ? » et
    ao passar o mouse para exibir o texto de ajuda

-   Mudança no processo principal de atualização, não solicitamos mais
    o arquivo no Market, mas agora no Github agora

-   Adição de um sistema centralizado para instalar dependências em
    plugins

-   Redesign da página de gerenciamento de plug-ins

-   Adicionando endereços MAC das diferentes interfaces

-   Adicionada conexão de autenticação dupla

-   Remoção de conexão hash (por motivos de segurança)

-   Adicionando um sistema de administração de SO

-   Adição de widgets Jeedom padrão

-   Adicionando um sistema beta para encontrar o IP do Jeedom na rede
    (você precisa conectar o Jeedom na rede, depois ir ao mercado e
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Adição à página de cenários de um testador de expressão

-   Revisão do sistema de compartilhamento de cenários
