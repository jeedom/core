# Changelog Jeedom V4.4


### 4.4 : Pré-requisitos

- Debian 10 Buster
- PHP 7.3

### 4.4 : Notícias / Melhorias

- **Histórico** : O modal de histórico e a página de histórico permitem usar botões *Semana, Mês, Ano* para recarregar dinamicamente um histórico maior.
- **Janela de seleção de imagem** : Adicionado um menu de contexto para enviar imagens e criar, renomear ou excluir uma pasta.
- **Janela de seleção de ícones** : Capacidade de adicionar um parâmetro `path` ao chamar `jeedomUtils.chooseIcon` por um plug-in para exibir apenas seus ícones.
- **Painel** : Capacidade de exibir vários objetos lado a lado *(Configurações → Sistema → Configuração / Interface)*.
- **Painel** : A janela de edição do bloco Edit Mode permite que os comandos sejam renomeados.
- **Painel** : No layout da tabela, possibilidade de inserir atributos HTML *(colspan/rowspan em particular)* para cada célula.
- **Equipamento** : Capacidade de desativar os modelos de widget de plug-ins que os utilizam para retornar à exibição padrão do Jeedom *(janela de configuração do dispositivo)*.
- **Equipamento** : O equipamento tornado inativo desaparece automaticamente de todas as páginas. O equipamento reativado reaparece no painel se o objeto pai já estiver presente.
- **Equipamento** : Equipamentos tornados invisíveis desaparecem automaticamente do painel. O equipamento reexibido reaparece no painel se o objeto pai já estiver presente.
- **Análise > Equipamento / Equipamento em alerta** : Dispositivos que entram em alerta aparecem automaticamente e os que saem de alerta desaparecem automaticamente.
- **Centro de mensagens** : Mensagens principais sobre anomalia agora informam uma ação, por exemplo, um link para abrir o cenário ofensivo, ou equipamento, configuração de plug-in, etc.
- **Objeto** : Excluir ou criar um resumo resulta na atualização do resumo global e do assunto.
- **Ferramentas > Substituir** : Esta ferramenta agora oferece um modo *Copiar*, permitindo copiar as configurações de equipamentos e comandos, sem substituí-los nos cenários e outros.
- **Linha do tempo** : A linha do tempo agora carrega os primeiros 35 eventos. Os seguintes eventos são carregados na rolagem na parte inferior da página.
- **Administração** : Possibilidade de diferenciar ações em caso de erro ou alerta de comando.
- **Administração** : Capacidade de definir widgets de comando padrão.
- **Painel** : possibilidade na página de configuração do objeto para pedir jeedom para reordenar o equipamento de acordo com seu uso.
- **Tema** : Possibilidade de escolher o tema diretamente do url (adicionando &theme=Dark ou &theme=Light).
- **Relatório** : Possibilidade de escolher o tema durante um relatório em uma página jeedom.
- **Menu Jeedom** : Um atraso de 0.25s foi introduzido na abertura de submenus.

### 4.4 : Autre

- **Essencial** : Início do desenvolvimento em js puro, sem jQuery. Ver [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.4).
- **Essencial** : Listagem mais detalhada de dispositivos USB.
- **Essencial** : Um menu contextual foi adicionado em diferentes lugares ao nível das caixas de seleção para selecionar todas, nenhuma ou inverter a seleção *(consulte [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.4))*.
- **Livre** : Atualizar Highchart v9.3.2 a v10.3.2 (O módulo *calibre sólido* não é mais importado).

### 4.4 : Remarques

> **Painel**
>
> No **Painel** e a **Visualizações**, Núcleo v4.4 agora redimensiona automaticamente os blocos para criar uma grade perfeita. As unidades (menor altura e menor largura de um ladrilho) desta grade são definidas em **Configurações → Sistema → Configuração / Interface** por valores *Passo vertical (mínimo 100)* e *Passo horizontal (mínimo 110)*. O valor que *Margem* definindo o espaço entre as telhas.
> Os ladrilhos se adaptam às dimensões da grade e podem ser feitos uma, duas vezes etc. esses valores em altura ou largura. Certamente será necessário passar [Modo de edição do painel](https://doc.jeedom.com/pt_PT/core/4.4/dashboard#Mode%20%C3%A9dition) para ajustar o tamanho de alguns blocos após a atualização.


> **Widgets**
>
> Widgets principais foram reescritos em js/css puro. Você terá que editar o Painel *(Edite, em seguida, botão ⁝ nos ladrilhos)* e use a opção *Quebra de linha depois* em certos comandos para encontrar o mesmo aspecto visual.
> Todos os widgets Core agora suportam a exibição *Tempo*, adicionando um parâmetro opcional *Tempo* / *duração* Onde *encontro*.

> **Caixa de diálogo**
>
> Todas as caixas de diálogo (bootstrap, bootbox, jQuery UI) foram migradas para uma Core lib interna (jeeDialog) especialmente desenvolvida. Caixas de diálogo redimensionáveis agora têm um botão para alternar *tela cheia*.


# Changelog Jeedom V4.3

## 4.3.15

- Proibição da tradução do Jeedom pelos navegadores (evita erros do tipo market.repo.php não encontrado).
- Otimização da função de substituição.

## 4.3.14

- Carga reduzida no DNS.

## 4.3.13

- Correção de bug em **Ferramentas / Substituir**.

## 4.3.12

- Otimização nos históricos.
- Resumo da correção de bug no celular.
- Correção de bug do widget do obturador móvel.
- Curvas de bloco de bugfix com informações binárias.

## 4.3.11

- Autorização de resposta livre em *perguntar* se você colocar * no campo de respostas possíveis.
- **Análise / História** : Correção de bug na comparação do histórico (bug introduzido na versão 4.3.10).
- **Síntese** : L'*Ação da Síntese* de um objeto agora é suportado na versão móvel.
- Correção de históricos ao usar a função de agregação.
- Corrigido um bug na instalação de um plugin por outro plugin (Ex : mqtt2 instalado por zwavejs).
- Corrigido um bug no histórico em que o valor 0 poderia sobrescrever o valor anterior.

## 4.3.10

- **Análise / História** : Erros corrigidos na exclusão do histórico.
- Exibição de valor fixo na janela de configuração de comando.
- Adicionadas informações e controle da ferramenta de substituição.

## 4.3.9

- Edição de blocos aprimorada.
- Visibilidade aprimorada das caixas de seleção temáticas Escuro e Claro.
- Corrigido o empilhamento de histórico.
- Otimização do gerenciamento de mudança de tempo (obrigado @jpty).
- Correções de bugs e melhorias.

## 4.3.8

- Bugfix.
- Segurança de perguntas aprimorada ao usar a função generateAskResponseLink por plugins : uso de um token exclusivo (não há mais envio da chave da API principal) e bloqueio da resposta apenas entre as opções possíveis.
- Corrigido um bug que impedia a instalação do jeedom.
- Corrigido um bug no influxdb.


## 4.3.7

- Correções de bugs (afetando um futuro plugin em desenvolvimento).
- Corrigidos erros de exibição em alguns widgets com base na unidade.
- Descrição adicionada **fonte** para ações de mensagem (consulte [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.3)).

## 4.3.6

- Conversão de unidade removida por segundos (s)).
- Remoção do menu de atualização do sistema operacional para caixas Jeedom (as atualizações do sistema operacional são gerenciadas pelo Jeedom SAS).
- Corrigido um bug no modal de configuração do histórico.
- Adicionando uma ação *Mudar tema* para cenários, ações de valor, ações pré/pós exec : Permite alterar o tema da interface imediatamente, no escuro, claro ou outro (alternar).

## 4.3.5

- Bugfix.

## 4.3.4

- Corrigido um problema com imagens de fundo.
- Corrigido um bug com o widget de número padrão.
- Corrigido erro de inclusão com alguns plugins (*nozes* por exemplo).

## 4.3.3

- Verificação de versão nodejs/npm aprimorada.

## 4.3.2

- Corrigido um problema ao exibir o status de um comando info na configuração avançada do comando se o valor for 0.

## 4.3.1

### 4.3 : Pré-requisitos

- Debian 10 Buster
- PHP 7.3

### 4.3 : Notícias / Melhorias

- **Ferramentas / Cenários** : Modal para edição ctrl+click em campos editáveis de blocos/ações.
- **Ferramentas / Cenários** : Adição de um menu contextual em um cenário para tornar ativo/inativo, alterar grupo, alterar objeto pai.
- **Ferramentas / Objetos** : Adicionado um menu contextual em um objeto para gerenciar a visibilidade, alterar o objeto pai e mover.
- **Ferramentas / Substituir** : Nova ferramenta para substituição de equipamentos e comandos.
- **Análise / Cronograma** : Adicionado um campo de pesquisa para filtrar a exibição.
- **Usuários** : Adicionado um botão para copiar os direitos de um usuário limitado para outro.
- **Relatório** : Capacidade de relatar sobre a saúde de Jeedom.
- **Relatório** : Capacidade de relatar equipamentos alertados.
- **Atualizar** : Capacidade de ver do Jeedom os pacotes OS / PIP2 / PIP3 / NodeJS que podem ser atualizados e iniciar a atualização (cuidado com a função arriscada e em beta).
- **Comando de alerta** : Adicionada uma opção para receber uma mensagem em caso de fim de alerta.
- **Plug-ins** : Possibilidade de desabilitar a instalação de dependências por plugin.
- **Otimização** : jeeFrontEnd{}, jeephp2js{}, pequenas correções de bugs e otimizações.

### 4.3 : WebApp

- Integração de notas.
- Possibilidade de mostrar os mosaicos apenas numa coluna (definindo na configuração do separador interface do jeedom).

### 4.3 : Autre

- **Livre** : Atualizar fonte impressionante 5.13.1 a 5.15.4.

### 4.3 : Notes

- Para usuários que usam menus em seus designs no formulário :

``<a onClick="planHeader_id=15; displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

Você deve agora usar:

``<a onClick="jeephp2js.planHeader_id=15; jeeFrontEnd.plan.displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

Vejo [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.3).

Postagem do blog [aqui](https://blog.jeedom.com/6739-jeedom-4-3/)

# Changelog Jeedom V4.2

## 4.2.21

- Corrigido um bug nos resumos.

## 4.2.20

- Adicionado um sistema para corrigir pacotes pip durante uma instalação incorreta.

## 4.2.19

- Adicionado gerenciamento de versão para pacotes python (permite corrigir o problema com o plugin zigbee).

## 4.2.18

- Atualização do nodejs.

## 4.2.17

- Núcleo de correção de bugs : Acesso limitado do usuário a designs e visualizações.
- IU de correção de bug : Exibição de blocos A no Chrome.
- Correção de bug : Link para documentação quando o plug-in estiver em beta.

## 4.2.16

- Núcleo de correção de bugs : Cenas : Mesclar itens colados em alguns casos.
- Núcleo de correção de bugs : Criação de arquivos com editor de arquivos.
- Correção de bug : Maior atraso para entrar em contato com o serviço de monitoramento (permite aliviar a carga nos servidores em nuvem).

## 4.2.15

- IU de correção de bug : Cenas : Adicionando a ação *tipo genérico* no modo de seleção.
- Núcleo de correção de bugs : Corrigido o atraso nos históricos calculados.
- Correção de bug : Instalação de dependências de plugin zigbee.

## 4.2.14

- IU de correção de bug : Pesquisa removida ativando a opção de log bruto.
- IU de correção de bug : Não foi possível baixar o log vazio.
- IU de correção de bug : Widget cmd.action.slider.value

- Núcleo de correção de bugs : Tamanho das imagens de fundo em relação ao tamanho do design.
- Núcleo de correção de bugs : Corrigido um problema com as chaves de API ainda desabilitadas.

## 4.2.13

- IU de correção de bug : Opção *Ocultar na área de trabalho* resumos.
- IU de correção de bug : Historiques: Respeite as escalas ao aplicar zoom.

- Núcleo de correção de bugs : Corrigiu um problema de tamanho de backup com o plugin Atlas.

- Melhoria : Criação de chaves de API por padrão inativa (se a solicitação de criação não vier do plugin).
- Melhoria : Tamanho de backup adicionado na página de gerenciamento de backup.

## 4.2.12

- IU de correção de bug : Exibindo a pasta de uma ação na linha do tempo.

- Núcleo de correção de bugs : Exibição da chave de API de cada plugin na página de configuração.
- Núcleo de correção de bugs : Adicionar opção *Hora* em um gráfico em Design.
- Núcleo de correção de bugs : Curva de ladrilhos com valor negativo.
- Núcleo de correção de bugs : Erro 403 na reinicialização.

- Melhoria : Exibição do valor do gatilho no log do cenário.

## 4.2.11

- IU de correção de bug : Posição no resumo de automação residencial de objetos recém-criados.
- IU de correção de bug : Problemas de exibição de design 3D.

- Núcleo de correção de bugs : Novas propriedades de resumo indefinidas.
- Núcleo de correção de bugs : Atualizar valor ao clicar no intervalo de widgets *Controle deslizante*.
- Núcleo de correção de bugs : Editando arquivo vazio (0b).
- Núcleo de correção de bugs : Preocupações de detectar o IP real do cliente através do DNS Jeedom. Uma reinicialização da caixa é recomendada após a atualização para que isso seja ativado.

## 4.2.9

- IU de correção de bug : Correção de widget *padrão numérico* (cmdName muito longo).
- IU de correção de bug : Passando variáveis css *--url-iconsDark* e *--url-iconsLight* em absoluto (Bug Safari MacOS).
- IU de correção de bug : Posição das notificações em *centro superior*.

- Núcleo de correção de bugs : Etapa padrão para widgets *Controle deslizante* em 1.
- Núcleo de correção de bugs : A atualização da página indica *Em andamento* sobre *ERRO DE ATUALIZAÇÃO* (atualização de log).
- Núcleo de correção de bugs : Modificação do valor de um histórico.
- Núcleo de correção de bugs : Problemas corrigidos com a instalação de dependências python.

- Melhoria : Novas opções em gráficos de design para agrupamento de escala e eixo Y.

- Essencial : Atualização de biblioteca *elFinder* 2.1.59 -> 2.1.60

## 4.2.8

- IU de correção de bug : Resumo de automação residencial, histórico de exclusão claro.
- IU de correção de bug : Opção *Não exibir mais* no modal *primeiro usuário*.
- IU de correção de bug : Curva em blocos de fundo em uma vista.
- IU de correção de bug : Histórias, escala de eixos quando reduzido.
- IU de correção de bug : Históricos, empilhamento em visualizações.
- IU de correção de bug : Exibição do nome de usuário ao excluir.
- IU de correção de bug : Opções para exibir números sem *ícone se nulo*.

- Núcleo de correção de bugs : Verifique o mod_alias do Apache.

- Melhoria : Opção na configuração para autorizar datas futuras nos históricos.

## 4.2.0

### 4.2 : Pré-requisitos

- Debian 10 Buster
- PHP 7.3

### 4.2 : Notícias / Melhorias

- **Síntese** : Possibilidade de configurar objetos para ir a um *Projeto* ou um *visto* desde a síntese.
- **Painel** : A janela de configuração do dispositivo (modo de edição) agora permite que você configure widgets móveis e tipos genéricos.
- **Widgets** : Internacionalização de Widgets de terceiros (código do usuário). Vejo [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.2).
- **Análise / História** : Possibilidade de comparar uma história ao longo de um determinado período.
- **Análise / História** : Exibição de múltiplos eixos em Y. Opção de cada eixo ter escala própria, agrupada por unidade ou não.
- **Análise / História** : Possibilidade de ocultar os eixos Y. Menu contextual nas legendas apenas com visualização, ocultação do eixo, mudança de cor da curva.
- **Análise / História** : Cálculos de histórico salvos agora são exibidos acima da lista de comandos, da mesma forma que estes.
- **Análise / Equipamento** : Pedidos órfãos agora exibem seu nome e data de exclusão se ainda estiverem no histórico de exclusão, bem como um link para o cenário ou equipamento afetado.
- **Análise / Logs** : Numeração da linha de registro. Possibilidade de exibir o log bruto.
- **Histórico** : Coloração de logs de acordo com certos eventos. Possibilidade de exibir o log bruto.
- **Resumos** : Possibilidade de definir um ícone diferente quando o resumo for nulo (sem venezianas abertas, sem luz acesa, etc).
- **Resumos** : Possibilidade de nunca mostrar o número à direita do ícone, ou apenas se for positivo.
- **Resumos** : A alteração do parâmetro de resumo na configuração e nos objetos agora está visível, sem esperar por uma alteração no valor de resumo.
- **Resumos** : Agora é possível configurar [ações em resumos](/pt_PT/concept/summary#Actions sobre résumés) (ctrl + clique em um resumo) graças aos virtuais.
- **Relatório** : Visualizar arquivos PDF.
- **Tipos de equipamento** : [Nova página](/pt_PT/core/4.2/types) **Ferramentas → Tipos de equipamento** permitindo que tipos genéricos sejam atribuídos a dispositivos e comandos, com suporte para tipos dedicados a plug-ins instalados (ver [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.2)).
- **Seleção de ilustrações** : Nova janela global para a escolha de ilustrações *(ícones, imagens, planos de fundo)*.
- **Exibir mesa** : Adição de um botão à direita da pesquisa nas páginas *Objetos* *Cenários* *Interações* *Widgets* e *Plug-ins* para mudar para o modo de mesa. Isso é armazenado por um cookie ou em **Configurações → Sistema → Configuração / Interface, Opções**. Os plugins podem usar esta nova função do Core. Vejo [Desenvolvedor de documentos](https://doc.jeedom.com/pt_PT/dev/core4.2).
- **Configuração do equipamento** : Possibilidade de configurar uma curva de histórico na parte inferior do ladrilho de um dispositivo.
- **Encomendado** : Possibilidade de fazer um cálculo em uma ação de comando do tipo deslizante antes da execução do comando.
- **Plugins / Gerenciamento** : Exibição da categoria do plugin e um link para abrir diretamente sua página sem passar pelo menu Plugins.
- **Cenas** : Função de fallback de código (*dobradura de código*) no *Blocos de Código*. Atalhos Ctrl + Y e Ctrl + I.
- **Cenas** : Copiar / colar e desfazer / refazer correção de bug (reescrita completa).
- **Cenas** : Adicionando funções de cálculo ``averageTemporal(commande,période)`` E ``averageTemporalBetween(commande,start,end)`` permitindo obter a média ponderada pela duração ao longo do período.
- **Cenas** : Adicionado suporte para tipos genéricos em cenários.
	- Desencadear : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0``
	- E SE ``genericType(LIGHT_STATE,#[Salon]#) > 0``
	- Ações ``genericType``
- **Objetos** : Plugins agora podem solicitar parâmetros específicos para objetos.
- **Usuários** : Plugins agora podem solicitar parâmetros específicos para usuários.
- **Usuários** : Capacidade de gerenciar os perfis de diferentes usuários Jeedom a partir da página de gerenciamento de usuários.
- **Usuários** : Capacidade de ocultar objetos / visualização / design / design 3D para usuários limitados.
- **Centro de Atualizações** : Centro de atualização agora exibe a data da última atualização.
- **Adicionar o usuário realizando uma ação** : Além das opções de execução do comando de id e nome de usuário para lançar a ação (visível no log de eventos por exemplo)
- **Documentação e plug-in de changelog beta** : Documentação e gerenciamento de changelog para plug-ins em beta. Atenção, em beta o changelog não é datado.
- **Em geral** : Integração do plugin JeeXplorer no Core. Agora usado para código de widget e personalização avançada.
- **Configuração** : Nova opção de configuração / interface para não colorir o título do banner do equipamento.
- **Configuração** : Possibilidade de configurar papéis de parede nas páginas Dashboard, Analysis, Tools e sua opacidade de acordo com o tema.
- **Configuração**: Adicionando Jeedom DNS baseado em Wireguard em vez de Openvpn (Administração / redes). Mais rápido e estável, mas ainda em teste. Por favor, note que atualmente não é compatível com Jeedom Smart.
- **Configuração** : Configurações OSDB: Adição de uma ferramenta para edição em massa de equipamentos, comandos, objetos, cenários.
- **Configuração** : Configurações OSDB: Adicionar um construtor de consulta SQL dinâmica.
- **Configuração**: Capacidade de desabilitar o monitoramento de nuvem (Administração / Atualizações / Mercado).
- **jeeCLI** : Adição de ``jeeCli.php`` na pasta core / php do Jeedom para gerenciar algumas funções de linha de comando.
- *Grandes melhorias na interface em termos de desempenho / capacidade de resposta. jeedomUtils {}, jeedomUI {}, menu principal reescrito em css puro, remoção de initRowWorflow (), simplificação do código, correções de css para telas pequenas, etc.*

### 4.2 : Widgets principais

- As configurações de widgets para a versão Mobile agora estão acessíveis a partir da janela de configuração do equipamento no modo Dashboard Edit.
- Os parâmetros opcionais disponíveis nos widgets agora são exibidos para cada widget, seja na configuração do comando ou no modo de edição do painel.
- Muitos widgets principais agora aceitam configurações de cores opcionais. (controle deslizante horizontal e vertical, medidor, bússola, chuva, obturador, controle deslizante de modelos, etc.).
- Widgets principais com exibição de um *Tempo* agora suporta um parâmetro opcional **Tempo : encontro** para exibir uma data relativa (ontem às 16h48, segunda-feira passada às 14h, etc).
- Widgets do tipo cursor (ação) agora aceitam um parâmetro opcional *degraus* para definir a etapa de mudança no cursor.
- O widget **ação.slider.value** agora está disponível no desktop, com um parâmetro opcional *noslider*, o que o torna um *entrada* simples.
- O widget **info.numeric.default** (*Medidor*) foi refeito em puro css e integrado em dispositivos móveis. Eles são, portanto, agora idênticos em computadores e dispositivos móveis.

### 4.2 : Backup na nuvem

Adicionamos uma confirmação da senha do backup na nuvem para evitar erros de entrada (como lembrete, o usuário é o único a saber essa senha, em caso de esquecimento, Jeedom não pode recuperá-la nem acessar os backups. Nuvem do usuário).

>**IMPORTANTE**
>
> Após a atualização, você DEVE ir para Configurações → Sistema → guia Atualização de configuração / Mercado e inserir a confirmação da senha do backup em nuvem para que isso possa ser feito.

### 4.2 : Segurança

- Para aumentar significativamente a segurança da solução Jeedom, o sistema de acesso a arquivos mudou. Antes de certos arquivos serem proibidos em certos locais. De v4.2, os arquivos são explicitamente permitidos por tipo e localização.
- Mudança no nível da API, anteriormente "tolerante" se você chegou com a chave do núcleo indicando o plugin XXXXX. Este não é mais o caso, você deve chegar com a chave correspondente ao plugin.
- Na API http, você pode indicar um nome de plugin em tipo, isso não é mais possível. O tipo correspondente ao tipo de solicitação (cenário, eqLogic, cmd, etc.) deve corresponder ao plugin. Por exemplo, para o plugin virtual que você tinha ``type=virtual`` no url, agora é necessário substituir por ``plugin=virtualEtype=event``.
- Reforço de sessões : Mude para sha256 com 64 caracteres em modo estrito.

A equipe da Jeedom está ciente de que essas mudanças podem ter um impacto e ser embaraçosas para você, mas não podemos comprometer a segurança.
Os plugins devem respeitar as recomendações sobre a estrutura em árvore de pastas e arquivos : [Médico](https://doc.jeedom.com/pt_PT/dev/plugin_template).

[Blog: Jeedom 4 introdução.2 : Segurança](https://blog.jeedom.com/6165-introduction-jeedom-4-2-la-securite/)

# Registro de alterações Jeedom V4.1

## 4.1.28

- Harmonização de modelos de widget para comandos de ação / padrão

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
- Bugfix de elementos *imagem* em um design.
- Adicionadas opções de agrupamento por tempo para gráficos em visualizações.
- Conservação do contexto de síntese ao clicar nos resumos.
- Centralização de imagens de síntese.

## 4.1.0

### 4.1 : Pré-requisitos

- Debian 10 Buster

### 4.1 : Notícias / Melhorias

- **Síntese** : Adicionando uma nova página **Home → Resumo** oferecendo um resumo visual global das peças, com acesso rápido aos resumos.
- **Pesquisar** : Adição de um mecanismo de pesquisa em **Ferramentas → Pesquisa**.
- **Painel** : Modo de edição agora inserindo o bloco movido.
- **Painel** : Modo de edição: os ícones de atualização do equipamento são substituídos por um ícone que permite acesso à sua configuração, graças a um novo modal simplificado.
- **Painel** : Agora podemos clicar no *Tempo* widgets de ações de tempo para abrir a janela do histórico do comando info vinculado.
- **Painel** : O tamanho do bloco de um novo equipamento se adapta ao seu conteúdo.
- **Painel** : Adicionar (voltar!) Um botão para filtrar os itens exibidos por categoria.
- **Painel** : Ctrl Clique em uma informação para abrir a janela do histórico com todos os comandos históricos do equipamento visíveis no bloco. Ctrl Clique em uma legenda para exibir apenas esta, Alt Clique para exibir todas.
- **Painel** : Redesenho da exibição da árvore de objetos (seta à esquerda da pesquisa).
- **Painel** : Capacidade de desfocar imagens de fundo (Configuração -> Interface).
- **Ferramentas / Widgets** : A função *Aplicar em* mostra os comandos vinculados marcados, desmarcando um aplicará o widget principal padrão a este comando.
- **Widgets** : Adicionando um widget principal *controle deslizante Vertical*.
- **Widgets** : Adicionando um widget principal *binárioSwitch*.
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
- **Cenas** : Visualizando um *aviso* se nenhum gatilho estiver configurado.
- **Cenas** : Correção de bug de *selecionar* em bloco copiar / colar.
- **Cenas** : Copiar / colar do bloco entre diferentes cenários.
- **Cenas** : As funções desfazer / refazer estão agora disponíveis como botões (ao lado do botão de criação de bloco).
- **Cenas** :  adição de "Exportação histórica" (exportHistory)
- **Janela Variáveis de Cenário** : Ordenação alfabética na abertura.
- **Janela Variáveis de Cenário** : Os cenários usados pelas variáveis agora são clicáveis, com a abertura da pesquisa na variável.
- **Análise / História** : Ctrl Clique em uma legenda para exibir apenas esse histórico, Alt Clique para exibir todos eles.
- **Análise / História** : As opções *agrupamento, tipo, variação, escada* estão ativos apenas com uma única curva exibida.
- **Análise / História** : Agora podemos usar a opção *Área* com a opção *Escadas*.
- **Análise / Logs** : Nova fonte de tipo monoespaçado para logs.
- **Visto** : Possibilidade de colocar cenários.
- **Visto** : Modo de edição agora inserindo o bloco movido.
- **Visto** : Modo de edição: os ícones de atualização do equipamento são substituídos por um ícone que permite acesso à sua configuração, graças a um novo modal simplificado.
- **Visto** : A ordem de exibição agora é independente da ordem no painel.
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
- **Relatório** : O uso de *cromada* se disponível.
- **Relatório** : Possibilidade de exportar cronogramas.
- **Configuração** : Aba *Em formação* agora está na guia *Em geral*.
- **Configuração** : Aba *Pedidos* agora está na guia *Equipamento*.
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
	- Menu Ferramentas : Ctrl Clique / Clique em Central no *Classificações*, *Testador de expressão*, *Variáveis*, *Pesquisar* : Abra a janela em uma nova guia, em tela cheia.
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
- **Livre** : Atualizar o HighStock v7.1.2 a v8.2.0.
- **Livre** : Atualizar o jQuery v3.4.1 a v3.5.1.
- **Livre** : Atualizar fonte Awesome 5.9.0 a 5.13.1.
- **API** :  adição de uma opção para proibir uma chave de API de um plugin de executar métodos centrais (geral)
- Protegendo solicitações Ajax.
- Protegendo chamadas de API.
- Correções de bugs.
- Inúmeras otimizações de desempenho de desktop / dispositivos móveis.

### 4.1 : Changements
- A função **cenário-> getHumanName()** da classe de cenário php não retorna mais *[objeto] [grupo] [nome]* mas *[grupo] [objeto] [nome]*.
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
- Painel : No modo de edição, o controle de pesquisa e seus botões são desativados e se tornam fixos.
- Painel : No modo de edição, um clique de um botão *expandir* à direita dos objetos redimensiona os ladrilhos do objeto para a altura do mais alto. Ctrl + clique os reduz à altura do mais baixo.
- Painel : A execução do comando em um bloco agora é sinalizada pelo botão *atualizar*. Se não houver nenhum no bloco, ele aparecerá durante a execução.
- Painel : Os blocos indicam um comando de informação (historizado, que abrirá a janela Histórico) ou ação ao passar o mouse.
- Painel : A janela de histórico agora permite que você abra este histórico em Análise / Histórico.
- Painel : A janela de histórico mantém sua posição / dimensões ao reabrir outro histórico.
- Janela de configuração de comando: Ctrl + clique em "Salvar" fecha a janela após.
- Janela de configuração de equipamento: Ctrl + clique em "Salvar" fecha a janela após.
- Adicionar informações de uso ao excluir um dispositivo.
- Objetos : Adicionada opção para usar cores personalizadas.
- Objetos : Adição de um menu contextual nas guias (mudança rápida de objeto).
- Interações : Adição de um menu contextual nas guias (mudança rápida de interação).
- Plug-ins : Adição de um menu contextual nas abas (troca rápida de equipamento).
- Plug-ins : Na página de gerenciamento de plug-ins, um ponto laranja indica que os plug-ins em versão não estável.
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
- Configuração : Adição de muitos *dicas de ferramentas* (aide).
- Configuração : Adicionar um motor de pesquisa.
- Configuração : Adicionado um botão para esvaziar o cache de widgets (guia Cache).
- Configuração : Adicionada uma opção para desativar o cache de widgets (guia Cache).
- Configuração : Possibilidade de centrar verticalmente o conteúdo dos tiles (guia Interface).
- Configuração : Adicionou um parâmetro para a eliminação global de logs (guia Pedidos).
- Configuração : Mudança de #message# em #subject# em Configuração / Logs / Mensagens para evitar a duplicação da mensagem.
- Configuração : Possibilidade nos resumos de adicionar uma exclusão de pedidos que não foram atualizados por mais de XX minutos (exemplo para o cálculo de médias de temperatura se um sensor não reportou nada por mais de 30min será excluído do cálculo)<br/><br/>
- Cenas : A colorização dos blocos não é mais aleatória, mas por tipo de bloco.
- Cenas : Possibilidade ao fazer um Ctrl + clique no botão *execução* salve-o, execute-o e exiba o log (se o nível de log não estiver ativado *Nenhum*).
- Cenas : Confirmação de exclusão de bloco. Ctrl + clique para evitar a confirmação.
- Cenas : Adição de uma função de pesquisa em blocos de código. Pesquisar : Ctrl + F, em seguida, Enter, próximo resultado : Ctrl + G, resultado anterior : Ctrl+Shift+G
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
- Projeto : Possibilidade de especificar a ordem (posição) do *Desenhos* e *Projetos 3D* (Editar, Configurar Design).
- Projeto : Adição de um campo CSS personalizado nos elementos do *Projeto*.
- Projeto : Movidas as opções de exibição em Design da configuração avançada, nos parâmetros de exibição do *Projeto*. Isso para simplificar a interface e permitir ter diferentes parâmetros por *Projeto*.
- Projeto : Movendo e redimensionando componentes em *Projeto* leva seu tamanho em consideração, com ou sem magnetização.<br/><br/>
- Adição de um sistema de configuração em massa (usado na página Equipamento para configurar alertas de comunicações neles)

### 4.0 : Autres

- **Livre** : Atualize o jquery 3.4.1
- **Livre** : Atualizar CodeMiror 5.46.0
- **Livre** : Atualizar o tablesorter 2.31.1
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
