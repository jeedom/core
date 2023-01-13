# Tipos de equipamento
**Ferramentas → Tipos de equipamento**

Os sensores e atuadores no Jeedom são gerenciados por plug-ins, que criam equipamentos com comandos ** (sensor) ou ** (atuador do). Isso torna possível disparar ações com base na mudança de certos sensores, como acender uma luz na detecção de movimento. Mas o Jeedom Core e plug-ins como **, **, *Casa inteligente do Google*, *Alexa Casa Inteligente* etc, não sei o que é este equipamento : Uma tomada, uma luz, uma veneziana, etc.

Para superar esse problema, especialmente com assistentes de voz (*Acenda a luz da sala*), Core introduziu o **Tipos Genéricos**, usado por esses plugins.

Isso torna possível identificar uma peça de equipamento por *A luz da sala* por exemplo.

Na maioria das vezes os tipos genéricos são definidos automaticamente ao configurar o seu módulo (inclusão no Z-wave por exemplo). Mas pode haver momentos em que você precisa reconfigurá-los. A configuração destes Tipos Genéricos pode ser feita diretamente em certos plugins, ou por comando em *Configuração avançada* disso.

Esta página permite que esses Tipos Genéricos sejam configurados de forma mais direta e simples, e ainda oferece atribuição automática uma vez que os dispositivos tenham sido atribuídos corretamente.

![Tipos de equipamento](./images/coreGenerics.gif)

## Tipo de equipamento

Esta página oferece armazenamento por tipo de equipamento : Soquete, luz, obturador, termostato, câmera, etc. Inicialmente, a maior parte do seu equipamento será classificado em **Equipamento sem tipo**. Para atribuir um tipo a eles, você pode movê-los para outro tipo ou clicar com o botão direito no equipamento para movê-lo diretamente. O tipo de equipamento não é realmente útil em si, sendo o mais importante os tipos de pedido. Você pode, portanto, ter um Equipamento sem um Tipo, ou um Tipo que não corresponda necessariamente aos seus comandos. Você pode, é claro, misturar tipos de controles no mesmo equipamento. Por enquanto, é mais um armazenamento, uma organização lógica, que talvez servirá em versões futuras.

> ****
>
> - Quando você move o equipamento no jogo **Equipamento sem tipo**, Jeedom sugere que você remova os tipos genéricos de seus pedidos.
> - Você pode mover vários equipamentos de uma vez marcando as caixas de seleção à esquerda deles.

## Tipo de comando

Uma vez que um item do equipamento é posicionado na posição correta **, clicando nele você acessa a lista de seus pedidos, colorida de forma diferente se for um ** (Azul) ou um ** (Orange).

Ao clicar com o botão direito em um pedido, você pode atribuir a ele um tipo genérico correspondente às especificações desse pedido (tipo de informação / ação, numérico, subtipo binário, etc).

> ****
>
> - O menu contextual de comandos exibe o tipo de equipamento em negrito, mas ainda permite atribuir qualquer Tipo Genérico de qualquer tipo de equipamento.

Em cada dispositivo, você tem dois botões :

- **Tipos de automóveis** : Esta função abre uma janela que oferece os Tipos Genéricos apropriados de acordo com o tipo de equipamento, as especificações do pedido e seu nome. Você pode então ajustar as propostas e desmarcar o aplicativo para certos comandos antes de aceitar ou não. Esta função é compatível com a seleção por caixas de seleção.

- **Tipos de redefinição** : Esta função remove os tipos genéricos de todos os comandos do equipamento.

> ****
>
> Nenhuma alteração é feita antes de salvar, com o botão no canto superior direito da página.

## Tipos e cenários genéricos

Na v4.2, o Core integrou os tipos genéricos nos cenários. Você pode, assim, acionar um cenário se uma lâmpada acender em uma sala, se for detectado movimento na casa, desligar todas as luzes ou fechar todas as venezianas com uma única ação, etc. Além disso, se você adicionar um equipamento, basta indicar os tipos corretos em seus pedidos, não será necessário editar tais cenários.

#### Desencadear

Você pode acionar um cenário a partir de sensores. Por exemplo, se você tiver detectores de movimento em casa, pode criar um cenário de alarme com cada detector acionando : ``#[Salão][Move Salon][Presence]# == 1`,`#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. Nesse cenário, você precisará de todos os detectores de movimento e, se adicionar um, terá que adicioná-lo aos gatilhos. Lógica.

Os tipos genéricos permitem que você use um único gatilho : ``#genericType(PRESENCE)# == . Aqui, nenhum objeto é indicado, então o menor movimento em toda a casa irá desencadear o cenário. Se você adicionar um novo detector na casa, não há necessidade de editar o (s) cenário (s)).

Aqui, um gatilho para acender uma luz na sala de estar : ``#genericType(,#[Salão]#)# > 

#### Expression

Se, em um cenário, você quiser saber se uma luz está acesa na sala de estar, você pode fazer :

SE `#[Salão][Lumiere Canapé][]# ==  #[Salão][Lumiere Salon][]# ==  #[Salão][Lumiere Angle][]# == 

Ou mais simplesmente : IF `genericType (LIGHT_STATE,#[Salão]#) > 0` ou se uma ou mais luzes estiverem acesas na sala de estar.

Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


#### Action

Se você deseja acender todas as luzes da sala de estar, pode criar uma ação leve:

`` ``
#[Salão][Lumiere Canapé][]#
#[Salão][Lumiere Salon][]#
#[Salão][Lumiere Angle][]#
`` ``

Ou mais simplesmente, crie uma ação `genericType` com` LIGHT_ON` no `Salon`. Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


## Lista de tipos de núcleo genérico

> ****
>
> - Você pode encontrar essa lista diretamente no Jeedom, nesta mesma página, com o botão **** canto superior direito.

| **Outro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Cronômetro de estado |  | numeric
|  | Status do temporizador (pausa ou não) |  | binário, numérico
| DEFINIR TEMPORIZADOR |  |  | slider
|  | Pausar cronômetro |  | other
|  | Resumo do cronômetro |  | other

| **Bateria (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
| BATERIA_CARREGANDO | Carregamento de bateria |  | binary

| **Câmera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Url da câmera |  | string
|  | Status de gravação da câmera |  | binary
|  | Movimento da câmera para cima |  | other
|  | Movimento da câmera para baixo |  | other
|  | Movimento da câmera para a esquerda |  | other
|  | Movimento da câmera para a direita |  | other
|  | Zoom da câmera para frente |  | other
|  | Zoom da câmera para trás |  | other
|  | Parar câmera |  | other
|  | Predefinição da câmera |  | other
|  | Gravação de câmera |  |
|  | Câmera instantânea |  |

| **Aquecimento (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status de aquecimento do fio piloto |  | binary
| AQUECIMENTO_ON | Botão LIGADO de aquecimento do fio piloto |  | other
| AQUECIMENTO_OFF | Botão de aquecimento do fio piloto DESLIGADO |  | other
|  | Botão do fio piloto de aquecimento |  | other

| **Eletricidade (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Energia elétrica |  | numeric
|  | Consumo de energia |  | numeric
|  |  |  | numeric
|  | Reiniciar |  | other

| **Ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | TEMPERATURA |  | numeric
| QUALIDADE DO AR | Qualidade do ar |  | numeric
|  |  |  | numeric
|  | PRESENÇA |  | binary
|  | Detecção de fumaça |  | binary
|  |  |  | numeric
|  |  |  | numeric
|  | ) |  | numeric
|  | ) |  | numeric
|  | Som (dB) |  | numeric
|  |  |  | numeric
| VAZAMENTO DE ÁGUA | Vazamento de água |  |
|  | Estado do filtro |  | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
|  |  |  | numeric
|  |  |  | binário, numérico
|  |  Genérico |  |
|  |  Genérico |  | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Estado claro |  | binário, numérico
|  | Brilho da luz |  | numeric
| COR CLARA | Cor clara |  | string
|  | Estado Leve (Binário) |  | binary
|  | Cor da temperatura da luz |  | numeric
|  | Alternar luz |  | other
| LUZES LIGADAS | Botão de luz ligado |  | other
| LUZ APAGADA | Botão de luz apagado |  | other
|  | Luz deslizante |  | slider
|  | Cor clara |  | color
|  | Modo de luz |  | other
|  | Cor da temperatura da luz |  |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Modo de status |  | string
|  | Modo de mudança |  | other

| **Multimídia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
|  |  |  | string
|  |  |  | string
|  |  |  | string
|  |  |  | string
|  |  |  | string
|  |  |  | numérico, string
|  |  |  | binary
|  |  |  | slider
|  |  |  | outro controle deslizante
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  | Anterior |  | other
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  | Sem mudo |  | other

| **Tempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPO_TEMPERATURA | Temperatura do tempo |  | numeric
| TEMPO_TEMPERATURE_MAX_2 | Condição climática d + 1 max d + 2 |  | numeric
| VELOCIDADE DO VENTO | Velocidade do vento) |  | numeric
|  | Chuva (acumulação) |  | numeric
|  | Chuva (mm / h) |  | numeric
|  | Condição climática (id) d + 4 |  | numeric
| TEMPO_CONDIÇÃO_4 | Condição climática d + 4 |  | string
| TEMPO_TEMPERATURE_MAX_4 | Temperatura máxima do tempo d + 4 |  | numeric
| TEMPO_TEMPERATURE_MIN_4 | Temperatura do tempo min d + 4 |  | numeric
|  | Condição climática (id) d + 3 |  | numeric
| TEMPO_CONDIÇÃO_3 | Condição climática d + 3 |  | string
| TEMPO_TEMPERATURE_MAX_3 | Temperatura máxima do tempo d + 3 |  | numeric
| TEMPO_TEMPERATURE_MIN_3 | Temperatura do tempo min d + 3 |  | numeric
|  | Condição climática (id) d + 2 |  | numeric
| TEMPO_CONDIÇÃO_2 | Condição climática d + 2 |  | string
| TEMPO_TEMPERATURE_MIN_2 | Temperatura do tempo min d + 2 |  | numeric
| TEMPO_UMIDADE | Umidade do tempo |  | numeric
|  | Condição climática (id) d + 1 |  | numeric
| TEMPO_CONDIÇÃO_1 | Condição climática d + 1 |  | string
| TEMPO_TEMPERATURE_MAX_1 | Temperatura máxima do tempo d + 1 |  | numeric
| TEMPO_TEMPERATURE_MIN_1 | Temperatura do tempo min d + 1 |  | numeric
|  | Condições meteorológicas (id) |  | numeric
| TEMPO_CONDIÇÃO | Condição do tempo |  | string
| X | Temperatura máxima do tempo |  | numeric
|  | Temperatura mínima do tempo |  | numeric
| TEMPO_SUNRISE | Clima de pôr do sol |  | numeric
| TEMPO_SUNSET | Clima do nascer do sol |  | numeric
|  | Tempo de direção do vento |  | numeric
|  | Tempo de velocidade do vento |  | numeric
| TEMPO_PRESSÃO | Pressão do Tempo |  | numeric
| DIREÇÃO DO VENTO | Direção do vento) |  | numeric

| **Abrindo (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Bloqueio de estado |  | binary
|  | Estado do portal (abertura) |  | binary
|  | Estado de garagem (abertura) |  | binary
|  |  |  | binary
|  | Janela |  | binary
|  | Botão de bloqueio aberto |  | other
|  | Botão de bloqueio Fechar |  | other
|  | Botão de abertura do portão ou garagem |  | other
|  | Botão de fechamento do portão ou garagem |  | other
|  | Botão de alternância de portão ou garagem |  | other

| **Soquete (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Soquete de estado |  | numérico, binário
|  | No Soquete de Botão |  | other
|  | Botão de soquete desligado |  | other
|  | Soquete deslizante |  |

| **Robô (código: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Base estadual |  | binary
|  | De volta à base |  | other

| **Segurança (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Estado da sereia |  | binary
|  | Status de Alarme |  | binário, string
|  | Modo de Alarme |  | string
|  | Status de alarme ativado |  | binary
|  |  |  | binary
|  |  |  | binary
|  |  |  | binário, numérico
|  | Botão da sirene desligado |  | other
|  | Botão de sirene ligado |  | other
| ALARME_ARMED | Alarme armado |  | other
|  | Alarme liberado |  | other
|  | Modo de Alarme |  | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TERMOSTAT_STATE | Status do termostato (BINÁRIO) (apenas para termostato de plug-in) |  |
| TERMOSTAT_TEMPERATURE | Termostato de temperatura ambiente |  | numeric
| TERMOSTAT_SETPOINT | Termostato de ponto de ajuste |  | numeric
|  | Modo do termostato (apenas para termostato de plug-in) |  | string
| TERMOSTATO_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) |  | binary
|  | Termostato de temperatura externa (apenas para termostato de plug-in) |  | numeric
|  | Status do termostato (HUMANO) (apenas para termostato de plug-in) |  | string
| TERMOSTATO_HUMIDITY | Termostato de umidade ambiente |  | numeric
|  | Definir umidade |  | slider
|  | Termostato de ponto de ajuste |  | slider
|  | Modo do termostato (apenas para termostato de plug-in) |  | other
| TERMOSTAT_SET_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) |  | other
|  | Desbloquear termostato (apenas para termostato de plug-in) |  | other
|  | Definir umidade |  | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status da velocidade do ventilador |  | numeric
|  | Rotação de estado |  | numeric
| VELOCIDADE DO VENTILADOR | Velocidade do ventilador |  | slider
|  |  |  | slider

| **Painel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Painel de status |  | binário, numérico
|  | Painel de status do BSO |  | binário, numérico
|  | Botão Pane Up |  | other
|  | Botão do painel para baixo |  | other
|  | Botão de parada do obturador |  |
|  | Painel de botões deslizantes |  | slider
|  | Botão para cima do painel BSO |  | other
|  | Botão para baixo do painel BSO |  | other
