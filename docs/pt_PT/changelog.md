# Registro de alterações Jeedom V4.5

# 4.5

- Possibilidade de tornar as colunas da tabela redimensionáveis (por enquanto apenas a lista de variáveis, isto será estendido a outras tabelas se necessário) [LINK](https://github.com/jeedom/core/issues/2499)
- Adicionado um alerta se o espaço em disco do jeedom estiver muito baixo (a verificação é feita uma vez por dia) [LINK](https://github.com/jeedom/core/issues/2438)
- Adicionado um botão na janela de configuração do pedido no campo de cálculo de valor para buscar um pedido [LINK](https://github.com/jeedom/core/issues/2776)
- Capacidade de ocultar determinados menus para usuários com direitos limitados [LINK](https://github.com/jeedom/core/issues/2651)
- Os gráficos são atualizados automaticamente quando novos valores chegam [LINK](https://github.com/jeedom/core/issues/2749)
- Jeedom adiciona automaticamente a altura da imagem ao criar widgets para evitar problemas de sobreposição no celular [LINK](https://github.com/jeedom/core/issues/2539)
- Redesenho da parte de backup em nuvem [LINK](https://github.com/jeedom/core/issues/2765)
- **DEV** Configurando um sistema de filas para execução de ações [LINK](https://github.com/jeedom/core/issues/2489)
- Tags de cenário agora são específicas para a instância do cenário (se você tiver dois lançamentos de cenário muito próximos, as tags deste último não sobrescreverão mais o primeiro) [LINK](https://github.com/jeedom/core/issues/2763)
- Mudança para a parte de gatilho dos cenários : [LINK](https://github.com/jeedom/core/issues/2414)
  - ``triggerId()`` agora está obsoleto e será removido em futuras atualizações principais
  - ``trigger()`` agora está obsoleto e será removido em futuras atualizações principais
  - ``triggerValue()`` agora está obsoleto e será removido em futuras atualizações principais
  - ``#trigger#`` : Talvez :
    - ``api`` se o lançamento foi acionado pela API,
    - ``TYPEcmd`` se a inicialização foi acionada por um comando, com TYPE substituído pelo ID do plugin (por exemplo, virtualCmd),
    - ``schedule`` se foi lançado por programação,
    - ``user`` se foi iniciado manualmente,
    - ``start`` para um lançamento na startup Jeedom.
  - ``#trigger_id#`` : Se for um comando que desencadeou o cenário então esta tag tem o valor do id do comando que o desencadeou
  - ``#trigger_name#`` : Se for um comando que disparou o cenário então esta tag terá o valor do nome do comando (na forma [objeto][equipamento][comando])
  - ``#trigger_value#`` : Se for um comando que acionou o cenário então esta tag terá o valor do comando que acionou o cenário
- Gerenciamento aprimorado de plug-ins no github (sem mais dependências de uma biblioteca de terceiros) [LINK](https://github.com/jeedom/core/issues/2567)
- Removendo o antigo sistema de cache. [LINK](https://github.com/jeedom/core/pull/2799)
- Possibilidade de deletar os blocos IN e A enquanto espera por outro cenário [LINK](https://github.com/jeedom/core/pull/2379)
- Corrigido um bug no Safari em filtros com acentos [LINK](https://github.com/jeedom/core/pull/2754)
- Corrigido um bug na geração de informações de tipo genérico em cenários [LINK](https://github.com/jeedom/core/pull/2806)
- Adicionada confirmação ao abrir o acesso ao suporte na página de gerenciamento de usuários [LINK](https://github.com/jeedom/core/pull/2809)
- Melhoria do sistema cron para evitar algumas falhas de inicialização [LINK](https://github.com/jeedom/core/commit/533d6d4d508ffe5815f7ba6355ec45497df73313)
- Adição de cenários de condições maiores ou iguais e menores ou iguais ao assistente de condições [LINK](https://github.com/jeedom/core/issues/2810)
- Capacidade de excluir pedidos da análise de pedidos mortos [LINK](https://github.com/jeedom/core/issues/2812)
- Corrigido um bug na numeração do número de linhas nas tabelas [LINK](https://github.com/jeedom/core/commit/0e9e44492e29f7d0842b2c9b3df39d0d98957c83)
- Adicionado mapa de rua aberto.org em domínios externos permitidos por padrão [LINK](https://github.com/jeedom/core/commit/2d62c64f0bd1958372844f6859ef691f88852422)
- Atualização automática do arquivo de segurança do Apache ao atualizar o núcleo [LINK](https://github.com/jeedom/core/issues/2815)
- Corrigido um aviso nas visualizações [LINK](https://github.com/jeedom/core/pull/2816)
- Corrigido um bug no valor de seleção do widget padrão [LINK](https://github.com/jeedom/core/pull/2813)
- Corrigido um bug se um comando excedesse seu mínimo ou máximo, o valor mudava para 0 (em vez de mínimo/máximo) [LINK](https://github.com/jeedom/core/issues/2819)
- Corrigido um bug na exibição do menu de configurações em determinados idiomas [LINK](https://github.com/jeedom/core/issues/2821)
- Possibilidade nos acionadores do cenário programado utilizar cálculos/comando/tag/fórmula resultando no horário de lançamento no formato Gi (hora sem zero inicial e minuto, exemplo para 9h15 => 9h15 ou para 23h40 => 23h40) [LINK](https://github.com/jeedom/core/pull/2808)
- Possibilidade de colocar uma imagem personalizada do equipamento nos plugins (caso o plugin suporte), para isso basta colocar a imagem em `data/img` no formato `eqLogic`#id#.png` com #id# o id do equipamento (você pode encontrá-lo na configuração avançada do equipamento) [LINK](https://github.com/jeedom/core/pull/2802)
- Adicionando o nome do usuário que lançou o cenário na tag ``#trigger_value#`` [LINK](https://github.com/jeedom/core/pull/2382)
- Corrigido um erro que poderia ocorrer se você saísse do painel antes de ele terminar de carregar [LINK](https://github.com/jeedom/core/pull/2827)
- Corrigido um bug na página de substituição ao filtrar objetos [LINK](https://github.com/jeedom/core/issues/2833)
- Abertura aprimorada do changelog principal no iOS (não mais em um pop-up) [LINK](https://github.com/jeedom/core/issues/2835)
- Melhoria da janela de criação avançada de widgets [LINK](https://github.com/jeedom/core/pull/2836)
- Melhorou a janela de configuração de comando avançada [LINK](https://github.com/jeedom/core/pull/2837)
- Corrigido um bug na criação de widgets [LINK](https://github.com/jeedom/core/pull/2838)
- Corrigido um bug na página do cenário e na janela de adição de ação que não funcionava mais [LINK](https://github.com/jeedom/core/issues/2839)
- Corrigido um bug que poderia alterar a ordem dos comandos ao editar o painel [LINK](https://github.com/jeedom/core/issues/2841)
- Corrigido um erro de javascript nos logs [LINK](https://github.com/jeedom/core/issues/2840)
- Adicionando segurança à codificação json em ajax para evitar erros devido a caracteres inválidos [LINK](https://github.com/jeedom/core/commit/0784cbf9e409cfc50dd9c3d085c329c7eaba7042)
- Se um comando do equipamento for do tipo genérico “Bateria” e possuir a unidade “%” então o núcleo atribuirá automaticamente o nível da bateria do equipamento ao valor do comando [LINK](https://github.com/jeedom/core/issues/2842)
- Melhoria de textos e correção de erros [LINK](https://github.com/jeedom/core/pull/2834)
- Ao instalar dependências npm, o cache é limpo antes [LINK](https://github.com/jeedom/core/commit/1a151208e0a66b88ea61dca8d112d20bb045c8d9)

>**IMPORTANTE**
>
> Devido à mudança do mecanismo de cache nesta atualização, todo o cache será perdido, não se preocupe, o cache será reconstruído sozinho. O cache contém, entre outras coisas, os valores dos comandos que serão atualizados automaticamente quando os módulos aumentarem de valor. Observe que se você tiver virtuais com valor fixo (o que não é bom se não mudar então terá que usar variáveis) então terá que salvá-los novamente para recuperar o valor.
