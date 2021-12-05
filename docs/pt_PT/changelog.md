# Changelog Jeedom V4.2

## 4.2.0

### 4.2 : Pré-requisitos

- Debian 10 Buster
- Php 7.3

### 4.2 : Notícias / Melhorias

- **Síntese** : Possibilidade de configurar objetos para ir a um *Projeto* ou um *Visão* desde a síntese.
- **Painel de controle** : A janela de configuração do dispositivo (modo de edição) agora permite que você configure widgets móveis e tipos genéricos.
- **Widgets** : Internacionalização de Widgets de terceiros (código do usuário). Vejo [Doc dev](https://doc.jeedom.com/pt_PT/dev/core4.2).
- **Análise / História** : Possibilidade de comparar uma história ao longo de um determinado período.
- **Análise / História** : Exibição de vários eixos Y com sua própria escala.
- **Análise / Equipamento** : Pedidos órfãos agora exibem seu nome e data de exclusão se ainda estiverem no histórico de exclusão, bem como um link para o cenário ou equipamento afetado.
- **Análise / Logs** : Numeração da linha de registro. Possibilidade de exibir o log bruto.
- **Histórico** : Coloração de logs de acordo com certos eventos. Possibilidade de exibir o log bruto.
- **Resumos** : Possibilidade de definir um ícone diferente quando o resumo for nulo (sem venezianas abertas, sem luz acesa, etc).
- **Resumos** : Possibilidade de nunca mostrar o número à direita do ícone, ou apenas se for positivo.
- **Resumos** : A alteração do parâmetro de resumo na configuração e nos objetos agora está visível, sem esperar por uma alteração no valor de resumo.
- **Resumos** : Agora é possível configurar [ações em resumos](/pt_PT/concept/summary#Actions sur résumés) (ctrl + clique em um resumo) graças aos virtuais.
- **Tipos de equipamento** : [Nova página](/pt_PT/core/4.2/types) **Ferramentas → Tipos de equipamento** permitindo que tipos genéricos sejam atribuídos a dispositivos e comandos, com suporte para tipos dedicados a plug-ins instalados (ver [Doc dev](https://doc.jeedom.com/pt_PT/dev/core4.2)).
- **Seleção de ilustrações** : Nova janela global para a escolha de ilustrações *(ícones, imagens, planos de fundo)*.
- **Exibir mesa** : Adição de um botão à direita da pesquisa nas páginas *Objetos* *Cenários* *Interações* *Widgets* e *Plugins* para mudar para o modo de mesa. Isso é armazenado por um cookie ou em **Configurações → Sistema → Configuração / Interface, Opções**. Os plugins podem usar esta nova função do Core. Vejo [Doc dev](https://doc.jeedom.com/pt_PT/dev/core4.2).
- **Configuração do equipamento** : Possibilidade de configurar uma curva de histórico na parte inferior do ladrilho de um dispositivo.
- **Encomendado** : Possibilidade de fazer um cálculo em uma ação de comando do tipo deslizante antes da execução do comando.
- **Plugins / Gerenciamento** : Exibição da categoria do plugin e um link para abrir diretamente sua página sem passar pelo menu Plugins.
- **Cenas** : Função de fallback de código (*dobradura de código*) no *Blocos de Código*. Atalhos Ctrl + Y e Ctrl + I.
- **Cenas** : Copiar / colar e desfazer / refazer correção de bug (reescrita completa).
- **Cenas** : Adicionando funções de cálculo ````averageTemporal(commande,période)```` E ````averageTemporalBetween(commande,start,end)```` permitindo obter a média ponderada pela duração ao longo do período.
- **Cenas** : Adicionado suporte para tipos genéricos em cenários.
	- Desencadear : ``#genericType(LIGHT_STATE,#[Salão]#)# > 0`
	- IF `genericType (LIGHT_STATE,#[Salão]#) > 0`
	- Ação `GenericType`
- **Objetos** : Plugins agora podem solicitar parâmetros específicos para objetos.
- **Comercial** : Plugins agora podem solicitar parâmetros específicos para usuários.
- **Comercial** : Capacidade de gerenciar os perfis de diferentes usuários Jeedom a partir da página de gerenciamento de usuários.
- **Comercial** : Capacidade de ocultar objetos / visualização / design / design 3D para usuários limitados.
- **Centro de Atualizações** : Centro de atualização agora exibe a data da última atualização.
- **Adicionar o usuário realizando uma ação** : Além das opções de execução do comando de id e nome de usuário para lançar a ação (visível no log de eventos por exemplo)
- **Documentação e plug-in de changelog beta** : Documentação e gerenciamento de changelog para plug-ins em beta. Atenção, em beta o changelog não é datado.
- **Geral** : Integração do plugin JeeXplorer no Core. Agora usado para código de widget e personalização avançada.
- **Configuração** : Nova opção de configuração / interface para não colorir o título do banner do equipamento.
- **Configuração** : Possibilidade de configurar papéis de parede nas páginas Dashboard, Analysis, Tools e sua opacidade de acordo com o tema.
- **Configuração**: Adicionando Jeedom DNS baseado em Wireguard em vez de Openvpn (Administração / redes). Mais rápido e estável, mas ainda em teste. Por favor, note que atualmente não é compatível com Jeedom Smart.
- **Configuração** : Configurações OSDB: Adição de uma ferramenta para edição em massa de equipamentos, comandos, objetos, cenários.
- **Configuração** : Configurações OSDB: Adicionar um construtor de consulta SQL dinâmica.
- **Configuração**: Possibilidade de desativar o monitoramento de nuvem (Administração / Atualizações / Mercado).
- **jeeCLI** : Adição de ````jeeCli.php```` na pasta core / php do Jeedom para gerenciar algumas funções de linha de comando.
- *Grandes melhorias na interface em termos de desempenho / capacidade de resposta. jeedomUtils {}, jeedomUI {}, menu principal reescrito em css puro, remoção de initRowWorflow (), simplificação do código, correções de css para telas pequenas, etc.*

### 4.2 : Widgets principais

- As configurações de widgets para a versão Mobile agora estão acessíveis a partir da janela de configuração do equipamento no modo Dashboard Edit.
- Os parâmetros opcionais disponíveis nos widgets agora são exibidos para cada widget, seja na configuração do comando ou no modo de edição do painel.
- Muitos widgets principais agora aceitam configurações de cores opcionais. (controle deslizante horizontal e vertical, medidor, bússola, chuva, obturador, controle deslizante de modelos, etc.).
- Widgets principais com exibição de um *Tempo* agora suporta um parâmetro opcional **Tempo : datado** para exibir uma data relativa (ontem às 16h48, segunda-feira passada às 14h, etc).
- Widgets do tipo cursor (ação) agora aceitam um parâmetro opcional *degrau* para definir a etapa de mudança no cursor.
- O widget **action.slider.value** agora está disponível no desktop, com um parâmetro opcional *noslider*, o que o torna um *entrada* simples.
- O widget **info.numeric.default** (*Medidor*) foi refeito em puro css e integrado em dispositivos móveis. Eles são, portanto, agora idênticos em computadores e dispositivos móveis.

### 4.2 : Backup na nuvem

Adicionamos uma confirmação da senha do backup na nuvem para evitar erros de entrada (como lembrete, o usuário é o único a saber essa senha, em caso de esquecimento, Jeedom não pode recuperá-la nem acessar os backups. Nuvem do usuário).

>**IMPORTANTE**
>
> Após a atualização, você DEVE ir para Configurações → Sistema → guia Atualização de configuração / Mercado e inserir a confirmação da senha do backup em nuvem para que isso possa ser feito.

### 4.2 : Segurança

- Para aumentar significativamente a segurança da solução Jeedom, o sistema de acesso a arquivos mudou. Antes de certos arquivos serem proibidos em certos locais. De v4.2, os arquivos são explicitamente permitidos por tipo e localização.
- Mudança no nível da API, anteriormente "tolerante" se você chegou com a chave do núcleo indicando o plugin XXXXX. Este não é mais o caso, você deve chegar com a chave correspondente ao plugin.
- Na API http, você pode indicar um nome de plugin em tipo, isso não é mais possível. O tipo correspondente ao tipo de solicitação (cenário, eqLogic, cmd, etc.) deve corresponder ao plugin. Por exemplo, para o plugin virtual que você tinha ````type=virtual```` no url, agora é necessário substituir por ````plugin=virtualEtype=event````.
- Reforço de sessões : Mude para sha256 com 64 caracteres em modo estrito.
- O cookie "fique conectado" (3 meses no máximo) agora é "one shot", renovado a cada uso.

A equipe da Jeedom está ciente de que essas mudanças podem ter um impacto e ser embaraçosas para você, mas não podemos comprometer a segurança.
Os plugins devem respeitar as recomendações sobre a estrutura em árvore de pastas e arquivos : [Doc](https://doc.jeedom.com/pt_PT/dev/plugin_template).

# Registro de alterações Jeedom V4.1

## 4.1.27

- Correção de uma violação de segurança, obrigado @Maxime Rinaudo e @Antoine Cervoise da Synacktiv (www.synacktiv.com)

## 4.1.26

- Corrigido um problema de instalação de dependência apt no Smart devido à mudança de certificado em vamos criptografar.

## 4.1.25

- Corrigido o problema de instalação de dependência do apt.

## 4.1.24

- Revisão da opção de configuração do comando **Gerenciando a repetição de valores** quem se torna **Repita valores idênticos (Sim|Non)**. [Veja o artigo do blog para mais detalhes](https://blog.jeedom.com/5414-nouvelle-gestion-de-la-repetition-des-valeurs/)

## 4.1.23

- Bugs corrigidos no arquivamento de histórico
- Corrigido um problema de cache que pode desaparecer durante uma reinicialização
- Corrigido um bug no gerenciamento de repetições de comandos binários : em certos casos se o equipamento enviar duas vezes 1 ou 0 seguidos, apenas a primeira subida foi levada em consideração. Por favor, note que esta correção de bug pode levar a uma sobrecarga da CPU. Portanto, é necessário atualizar os plug-ins também (Philips Hue em particular) para outros casos (acionamento de múltiplos cenários, enquanto este não era o caso antes da atualização). Dúvida sobre a repetição de valores (configuração avançada do comando) e mude para "nunca repetir" para encontrar a operação anterior.

## 4.1.22

- Adição de um sistema que permite ao Jeedom SAS comunicar mensagens a todos os Jeedom
- Alternando Jeedom DNS para o modo de alta disponibilidade

## 4.1.20

- Correção de bug na rolagem horizontal em designs.
- Correção de bug para rolagem nas páginas de plugins de equipamentos.
- Correção de bug das configurações de cor nos links de visualização / design em um desenho.
- Correção de bugs e otimização da linha do tempo.
- Revisão com três dedos de designs móveis agora limitada a perfis de administrador.

## 4.1.19

- Correção de bug na exclusão de zona em uma visualização.
- Correção de erro de js que pode aparecer em navegadores antigos.
- Bugfix cmd.info.numeric.default.html se o comando não estiver visível.
- Página de login de correção de bug.

## 4.1.18

- Correção de bug histórico em designs.
- Busca de correção de bug em Análise / Histórico.
- Correção de bug na pesquisa de uma variável, link para um dispositivo.
- Correção de bugs de resumos coloridos na síntese.
- Correção de bug em comentários de cenário com json.
- Correção de bug em atualizações de resumo em visualizações do modo Painel.
- Bugfix de elementos *cenário* em um design.
- Adicionadas opções de agrupamento por tempo para gráficos em visualizações.
- Conservação do contexto de síntese ao clicar nos resumos.
- Centralização de imagens de síntese.

## 4.1.0

### 4.1 : Pré-requisitos

- Debian 10 Buster

### 4.1 Notícias / Melhorias

- **Síntese** : Adicionando uma nova página **Home → Resumo** oferecendo um resumo visual global das peças, com acesso rápido aos resumos.
- **Pesquisa** : Adição de um mecanismo de pesquisa em **Ferramentas → Pesquisa**.
- **Painel de controle** : Modo de edição agora inserindo o bloco movido.
- **Painel de controle** : Modo de edição: os ícones de atualização do equipamento são substituídos por um ícone que permite acesso à sua configuração, graças a um novo modal simplificado.
- **Painel de controle** : Agora podemos clicar no *Tempo* widgets de ações de tempo para abrir a janela do histórico do comando info vinculado.
- **Painel de controle** : O tamanho do bloco de um novo equipamento se adapta ao seu conteúdo.
- **Painel de controle** : Adicionar (voltar!) Um botão para filtrar os itens exibidos por categoria.
- **Painel de controle** : Ctrl Clique em uma informação para abrir a janela do histórico com todos os comandos históricos do equipamento visíveis no bloco. Ctrl Clique em uma legenda para exibir apenas esta, Alt Clique para exibir todas.
- **Painel de controle** : Redesenho da exibição da árvore de objetos (seta à esquerda da pesquisa).
- **Painel de controle** : Capacidade de desfocar imagens de fundo (Configuração -> Interface).
- **Ferramentas / Widgets** : A função *Aplicar em* mostra os comandos vinculados marcados, desmarcando um aplicará o widget principal padrão a este comando.
- **Widgets** : Adicionando um widget principal *sliderVertical*.
- **Widgets** : Adicionando um widget principal *binarySwitch*.
- **Update Center** : As atualizações são verificadas automaticamente quando a página é aberta, se for 120 minutos mais antiga.
- **Update Center** : A barra de progresso está agora na guia *Núcleo e plugins*, e o log aberto por padrão na guia *Em formação*.
- **Update Center** : Se você abrir outro navegador durante uma atualização, a barra de progresso e o log indicarão.
- **Update Center** : Se a atualização terminar corretamente, é exibida uma janela pedindo para recarregar a página.
- **Atualizações principais** : Implementação de um sistema para limpar arquivos Core não utilizados antigos.
- **Cenas** : Adicionando um mecanismo de pesquisa (à esquerda do botão Executar).
- **Cenas** : Adição da função de idade (fornece a idade do valor da ordem).
- **Cenas** : *stateChanges()* agora aceite o período *hoje* (meia-noite até agora), *ontem* e *dia* (por 1 dia).
- **Cenas** : Funções *estatísticas (), média (), max (), min (), tendência (), duração()* : Bugfix ao longo do período *ontem*, e aceite agora *dia* (por 1 dia).
- **Cenas** : Possibilidade de desativar o sistema de cotação automática (Configurações → Sistema → Configuração : Equipements).
- **Cenas** : Visualizando um *Atenção* se nenhum gatilho estiver configurado.
- **Cenas** : Correção de bug de *selecionar* em bloco copiar / colar.
- **Cenas** : Copiar / colar do bloco entre diferentes cenários.
- **Cenas** : As funções desfazer / refazer estão agora disponíveis como botões (ao lado do botão de criação de bloco).
- **Cenas** :  adição de "Exportação histórica" (exportHistory)
- **Janela Variáveis de Cenário** : Ordenação alfabética na abertura.
- **Janela Variáveis de Cenário** : Os cenários usados pelas variáveis agora são clicáveis, com a abertura da pesquisa na variável.
- **Análise / História** : Ctrl Clique em uma legenda para exibir apenas esse histórico, Alt Clique para exibir todos eles.
- **Análise / História** : As opções *agrupamento, tipo, variação, escada* estão ativos apenas com uma única curva exibida.
- **Análise / História** : Agora podemos usar a opção *Área* com a opção *Escadaria*.
- **Análise / Logs** : Nova fonte de tipo monoespaçado para logs.
- **Visão** : Possibilidade de colocar cenários.
- **Visão** : Modo de edição agora inserindo o bloco movido.
- **Visão** : Modo de edição: os ícones de atualização do equipamento são substituídos por um ícone que permite acesso à sua configuração, graças a um novo modal simplificado.
- **Visão** : A ordem de exibição agora é independente da ordem no painel.
- **Linha do tempo** : Separação das páginas de histórico e cronograma.
- **Linha do tempo** : Integração da linha do tempo no DB por motivos de confiabilidade.
- **Linha do tempo** : Gerenciamento de várias linhas do tempo.
- **Linha do tempo** : Completo redesenho gráfico da linha do tempo (Desktop / Mobile).
- **Resumo Global** : Visualização Resumo, suporte para resumos de um objeto diferente ou com um objeto raiz vazio (Desktop e WebApp).
- **Ferramentas / Objetos** : Nova aba *Resumo por equipamento*.
- **Resumo Automation** : Equipamentos de plug-in desativados e seus controles não têm mais os ícones à direita (configuração do equipamento e configuração avançada).
- **Resumo Automation** : Capacidade de pesquisar nas categorias de equipamentos.
- **Resumo Automation** : Possibilidade de mover várias peças de equipamento de um objeto para outro.
- **Resumo Automation** : Possibilidade de selecionar todo o equipamento de um objeto.
- **Mecanismo de tarefas** : Na guia *Demônio*, plugins desativados não aparecem mais.
- **Relatório** : O uso de *cromo* se disponível.
- **Relatório** : Possibilidade de exportar cronogramas.
- **Configuração** : A guia *Em formação* agora está na guia *Geral*.
- **Configuração** : A guia *Pedidos* agora está na guia *Equipamento*.
- **Janela de configuração avançada de equipamentos** : Alteração dinâmica da configuração do quadro de distribuição.
- **Equipamento** : Nova categoria *Abertura*.
- **Equipamento** : Possibilidade de inverter comandos do tipo cursor (informação e ação)
- **Equipamento** : Possibilidade de adicionar css de classe a um bloco (consulte a documentação do widget).
- **Sobre a janela** : Adição de atalhos ao Changelog e FAQ.
- Páginas de Widgets / Objetos / Cenários / Interações / Plugins :
	- Ctrl Clic / Clic Center em um equipamento de widget, objeto, cenário, interação, plug-in : Abre em uma nova guia.
	- Ctrl Clic / Clic Center também disponível em seus menus de contexto (nas guias).
- Nova página ModalDisplay :
	- Menu Análise : Ctrl Clique / Clique em Central no *Tempo real* : Abra a janela em uma nova guia, em tela cheia.
	- Menu Ferramentas : Ctrl Clique / Clique em Central no *Notas*, *Testador de expressão*, *Variáveis*, *Pesquisa* : Abra a janela em uma nova guia, em tela cheia.
- Bloco de código, editor de arquivos, personalização avançada : Adaptação tema escuro.
- Janela de seleção de imagem aprimorada.

### 4.1 : WebApp
- Integração da nova página Resumo.
- Na página Cenários, um clique no título do cenário exibe seu log.
- Agora podemos selecionar / copiar parte de um log.
- Na pesquisa em um log, adição de um botão x para cancelar a pesquisa.
- Persistência da alternância do tema (8h).
- Em um design, um clique com três dedos retorna à página inicial.
- Exibição de cenários por grupo.
- Nova fonte de tipo monoespaçado para logs.
- Muitas correções de bugs (UI, retrato / paisagem iOS, etc.).

### 4.1 : Autres
- **Documentação** : Adaptações de acordo com v4 e v4.1.
- **Documentação** : Nova página *Atalhos de teclado / mouse* incluindo um resumo de todos os atalhos no Jeedom. Acessível no documento do Painel ou nas Perguntas frequentes.
- **Lib** : Atualizar o HighStock v7.1.2 a v8.2.0.
- **Lib** : Atualizar o jQuery v3.4.1 a v3.5.1.
- **Lib** : Atualizar fonte Awesome 5.9.0 a 5.13.1.
- **API** :  adição de uma opção para proibir uma chave de API de um plugin de executar métodos centrais (geral)
- Protegendo solicitações Ajax.
- Protegendo chamadas de API.
- Correções de bugs.
- Inúmeras otimizações de desempenho de desktop / dispositivos móveis.

### 4.1 : Changements
- A função **cenário-> getHumanName()** da classe de cenário php não retorna mais *[objeto] [grupo] [nome]* Mas *[grupo] [objeto] [nome]*.
- A função **cenário-> byString()** agora deve ser chamado com a estrutura *[grupo] [objeto] [nome]*.
- Funções **rede-> getInterfaceIp () rede-> getInterfaceMac () rede-> getInterfaces()** foram substituídos por **rede-> getInterfacesInfo()**


# Changelog Jeedom V4.0

## 4.0.62

- Nova migração buster + kernel para smart e Pro v2
- Verificação da versão do sistema operacional durante atualizações importantes do Jeedom


## 4.0.61

- Corrigido um problema ao aplicar um modelo de cenário
- Adição de uma opção para desativar a verificação SSL durante a comunicação com o mercado (não recomendado, mas útil em certas configurações de rede específicas)
- Corrigido um problema com o histórico de arquivamento se o modo de suavização fosse para sempre
- Correções de bugs
- Correção do comando trigger () em cenários para que ele retorne o nome do gatilho (sem o #) em vez do valor, para o valor você deve usar triggerValue()

## 4.0.60

- Remoção do novo sistema DNS em eu.jeedom.link seguindo muitos operadores que proíbem fluxos http2 permanentes

## 4.0.59

- Bugs corrigidos em widgets de tempo
- Aumento no número de senhas incorretas antes do banimento (evita problemas com o webapp ao girar chaves de API)

## 4.0.57

- Reforço da segurança do cookie
- Usando cromo (se instalado) para relatórios
- Corrigido um problema com o cálculo do tempo de estado em widgets se o fuso horário jeedom não for o mesmo do navegador
- Bugfix

## 4.0.55

- O novo dns (\*. Eu.jeedom.link) torna-se o DNS primário (o DNS antigo ainda funciona)

## 4.0.54

- Início da atualização do novo site de documentação

## 4.0.53

- Correção de bug.

## 4.0.52

- Correção de bug (atualização para fazer absolutamente se você estiver na versão 4.0.51).

## 4.0.51

- Bugfix.
- Otimização do futuro sistema DNS.

## 4.0.49

- Possibilidade de escolher o motor Jeedom TTS e possibilidade de ter plugins que oferecem um novo motor TTS.
- Suporte aprimorado de webview no aplicativo móvel.
- Bugfix.
- Atualização de doc.

## 4.0.47

- Testador de expressão aprimorado.
- Atualização do repositório no smart.
- Bugfix.

## 4.0,44

- Traduções melhoradas.
- Bugfix.
- Restauração de backup em nuvem aprimorada.
- A restauração da nuvem agora recupera apenas o backup local, deixando a opção de fazer o download ou restaurá-lo.

## 4.0.43

- Traduções melhoradas.
- Bugs corrigidos em modelos de cenário.

## 4.0.0

### 4.0 : Pré-requisitos

- Debian 9 Stretch

### 4.0 : Notícias / Melhorias

- Redesenho completo do tema (Core 2019 Light / Dark / Legacy).
- Possibilidade de mudar o tema automaticamente dependendo do tempo.
- No celular, o tema pode mudar dependendo do brilho (Requer para ativar *sensor extra genérico* no cromo, página do cromo://flags).<br/><br/>
- Melhoria e reorganização do menu principal.
- Menu de plugins : A lista de categorias e plugins agora está classificada em ordem alfabética.
- Menu Ferramentas : Adicione um botão para acessar o testador de expressão.
- Menu Ferramentas : Adicionando um botão para acessar as variáveis.<br/><br/>
- Os campos de pesquisa agora suportam acentos.
- Os campos de pesquisa (painel, cenários, objetos, widgets, interações, plug-ins) agora estão ativos ao abrir a página, permitindo que você digite uma pesquisa diretamente.
- Adicionado um botão X nos campos de pesquisa para cancelar a pesquisa.
- Durante uma pesquisa, a chave *escapar* cancelar pesquisa.
- Painel de controle : No modo de edição, o controle de pesquisa e seus botões são desativados e se tornam fixos.
- Painel de controle : No modo de edição, um clique de um botão *expandir* à direita dos objetos redimensiona os ladrilhos do objeto para a altura do mais alto. Ctrl + clique os reduz à altura do mais baixo.
- Painel de controle : A execução do comando em um bloco agora é sinalizada pelo botão *refrescar*. Se não houver nenhum no bloco, ele aparecerá durante a execução.
- Painel de controle : Os blocos indicam um comando de informação (historizado, que abrirá a janela Histórico) ou ação ao passar o mouse.
- Painel de controle : A janela de histórico agora permite que você abra este histórico em Análise / Histórico.
- Painel de controle : A janela de histórico mantém sua posição / dimensões ao reabrir outro histórico.
- Janela de configuração de comando: Ctrl + clique em "Salvar" fecha a janela após.
- Janela de configuração de equipamento: Ctrl + clique em "Salvar" fecha a janela após.
- Adicionar informações de uso ao excluir um dispositivo.
- Objetos : Adicionada opção para usar cores personalizadas.
- Objetos : Adição de um menu contextual nas guias (mudança rápida de objeto).
- Interações : Adição de um menu contextual nas guias (mudança rápida de interação).
- Plugins : Adição de um menu contextual nas abas (troca rápida de equipamento).
- Plugins : Na página de gerenciamento de plug-ins, um ponto laranja indica que os plug-ins em versão não estável.
- Melhorias na tabela com filtro e opção de classificação.
- Possibilidade de atribuir um ícone a uma interação.
- Cada página do Jeedom agora tem um título no idioma da interface (guia do navegador).
- Prevenção de preenchimento automático em campos 'Código de acesso''.
- Gestão de funções *Página anterior / próxima página* do navegador.<br/><br/>
- Widgets : Redesenho do sistema de widgets (menu Ferramentas / Widgets).
- Widgets : Possibilidade de substituir um widget por outro em todos os comandos que o utilizam.
- Widgets : Possibilidade de atribuir widgets a vários comandos.
- Widgets : Adicionar um widget de informação numérica horizontal.
- Widgets : Adicionar um widget de informação numérica vertical.
- Widgets : Adição de um widget de bússola / vento numérico (obrigado @thanaus).
- Widgets : Adicionado um widget numérico de informações de chuva (obrigado @thanaus)
- Widgets : Exibição do widget do obturador de informações / ação proporcional ao valor.<br/><br/>
- Configuração : Melhoria e reorganização de guias.
- Configuração : Adição de muitos *dicas* (aide).
- Configuração : Adicionar um motor de pesquisa.
- Configuração : Adicionado um botão para esvaziar o cache de widgets (guia Cache).
- Configuração : Adicionada uma opção para desativar o cache de widgets (guia Cache).
- Configuração : Possibilidade de centrar verticalmente o conteúdo dos tiles (guia Interface).
- Configuração : Adicionou um parâmetro para a eliminação global de logs (guia Pedidos).
- Configuração : Mudança de #message# em #subject# em Configuração / Logs / Mensagens para evitar a duplicação da mensagem.
- Configuração : Possibilidade nos resumos de adicionar uma exclusão de pedidos que não foram atualizados por mais de XX minutos (exemplo para o cálculo de médias de temperatura se um sensor não reportou nada por mais de 30min será excluído do cálculo)<br/><br/>
- Cenas : A colorização dos blocos não é mais aleatória, mas por tipo de bloco.
- Cenas : Possibilidade ao fazer um Ctrl + clique no botão *execução* salve-o, execute-o e exiba o log (se o nível de log não estiver ativado *Não*).
- Cenas : Confirmação de exclusão de bloco. Ctrl + clique para evitar a confirmação.
- Cenas : Adição de uma função de pesquisa em blocos de código. Pesquisa : Ctrl + F, em seguida, Enter, próximo resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G
- Cenas : Possibilidade de condensar os blocos.
- Cenas : A ação 'Adicionar bloco' muda para a guia Cenário, se necessário.
- Cenas : Novas funções de copiar / colar em bloco. Ctrl + clique para cortar / substituir.
- Cenas : Um novo bloco não é mais adicionado no final da linha do tempo, mas após o bloco em que você estava antes de clicar, determinado pelo último campo que você clicou.
- Cenas : Configurando um sistema Desfazer / Refazer (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Cenas : Remover compartilhamento de cenário.
- Cenas : Melhoria da janela de gerenciamento do modelo de cenário.<br/><br/>
- Análise / Equipamento : Adição de um motor de busca (guia Baterias, pesquisa por nomes e pais).
- Análise / Equipamento : A zona do dia do calendário / equipamento agora pode ser clicada para acessar diretamente as trocas de bateria).
- Análise / Equipamento : Adicionando um campo de pesquisa.<br/><br/>
- Update Center : Aviso na guia 'Core e plug-ins' e / ou 'Outros' se uma atualização estiver disponível. Mude para 'Outros' se necessário.
- Update Center : diferenciação por versão (estável, beta, ...).
- Update Center : adição de uma barra de progresso durante a atualização.<br/><br/>
- Resumo Automation : O histórico de exclusões agora está disponível em uma guia (Resumo - Histórico).
- Resumo Automation : Redesenho completo, possibilidade de solicitar objetos, equipamentos, pedidos.
- Resumo Automation : Adicionando equipamentos e IDs de pedido, para exibir e pesquisar.
- Resumo Automation : Exportação CSV de objeto pai, id, equipamento e seu id, comando.
- Resumo Automation : Possibilidade de tornar visível ou não um ou mais comandos.<br/><br/>
- Projeto : Possibilidade de especificar a ordem (posição) do *Desenhos* e *Designs 3D* (Editar, Configurar Design).
- Projeto : Adição de um campo CSS personalizado nos elementos do *Projeto*.
- Projeto : Movidas as opções de exibição em Design da configuração avançada, nos parâmetros de exibição do *Projeto*. Isso para simplificar a interface e permitir ter diferentes parâmetros por *Projeto*.
- Projeto : Movendo e redimensionando componentes em *Projeto* leva seu tamanho em consideração, com ou sem magnetização.<br/><br/>
- Adição de um sistema de configuração em massa (usado na página Equipamento para configurar alertas de comunicações neles)

### 4.0 : Autres

- **Lib** : Atualize o jquery 3.4.1
- **Lib** : Atualizar CodeMiror 5.46.0
- **Lib** : Atualizar o tablesorter 2.31.1
- Iluminação geral (estilos css / inline, refatoração, etc.) e melhorias de desempenho.
- Adição de compatibilidade global do Jeedom DNS com uma conexão de internet 4G.
- Inúmeras correções de bugs.
- Correções de segurança.

### 4.0 : Changements

- Remova Font Awesome 4 para manter apenas Font Awesome 5.
- O plugin do widget não é compatível com esta versão do Jeedom e não será mais suportado (porque os recursos foram levados internamente no núcleo). Mais Informações [aqui](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

>**IMPORTANTE**
>
> Se após a atualização você tiver um erro no Dashboard, tente reiniciar sua caixa para que ela leve em consideração as novas adições de componentes.
