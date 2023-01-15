# Tipos de equipamento
**Ferramentas → Tipos de equipamento**

Os sensores e atuadores no Jeedom são gerenciados por plug-ins, que criam equipamentos com comandos *Em formação* (sensor) ou *Estoque* (atuador do). Isso torna possível disparar ações com base na mudança de certos sensores, como acender uma luz na detecção de movimento. Mas o Jeedom Core e plug-ins como *Móvel*, *Homebridge*, *Casa inteligente do Google*, *Alexa Casa Inteligente* etc, não sei o que é este equipamento : Uma tomada, uma luz, uma veneziana, etc.

Para superar esse problema, especialmente com assistentes de voz (*Acenda a luz da sala*), Core introduziu o **Tipos Genéricos**, usado por esses plugins.

Isso torna possível identificar uma peça de equipamento por *A luz da sala* por exemplo.

Na maioria das vezes os tipos genéricos são definidos automaticamente ao configurar o seu módulo (inclusão no Z-wave por exemplo). Mas pode haver momentos em que você precisa reconfigurá-los. A configuração destes Tipos Genéricos pode ser feita diretamente em certos plugins, ou por comando em *Configuração avançada* disso.

Esta página permite que esses Tipos Genéricos sejam configurados de forma mais direta e simples, e ainda oferece atribuição automática uma vez que os dispositivos tenham sido atribuídos corretamente.

![Tipos de equipamento](./images/coreGenerics.gif)

## Tipo de equipamento

Esta página oferece armazenamento por tipo de equipamento : Soquete, luz, obturador, termostato, câmera, etc. Inicialmente, a maior parte do seu equipamento será classificado em **Equipamento sem tipo**. Para atribuir um tipo a eles, você pode movê-los para outro tipo ou clicar com o botão direito no equipamento para movê-lo diretamente. O tipo de equipamento não é realmente útil em si, sendo o mais importante os tipos de pedido. Você pode, portanto, ter um Equipamento sem um Tipo, ou um Tipo que não corresponda necessariamente aos seus comandos. Você pode, é claro, misturar tipos de controles no mesmo equipamento. Por enquanto, é mais um armazenamento, uma organização lógica, que talvez servirá em versões futuras.

> **Gorjeta**
>
> - Quando você move o equipamento no jogo **Equipamento sem tipo**, Jeedom sugere que você remova os tipos genéricos de seus pedidos.
> - Você pode mover vários equipamentos de uma vez marcando as caixas de seleção à esquerda deles.

## Tipo de comando

Uma vez que um item do equipamento é posicionado na posição correta *Gentil*, clicando nele você acessa a lista de seus pedidos, colorida de forma diferente se for um *Em formação* (Azul) ou um *Estoque* (Orange).

Ao clicar com o botão direito em um pedido, você pode atribuir a ele um tipo genérico correspondente às especificações desse pedido (tipo de informação / ação, numérico, subtipo binário, etc).

> **Gorjeta**
>
> - O menu contextual de comandos exibe o tipo de equipamento em negrito, mas ainda permite atribuir qualquer Tipo Genérico de qualquer tipo de equipamento.

Em cada dispositivo, você tem dois botões :

- **Tipos de automóveis** : Esta função abre uma janela que oferece os Tipos Genéricos apropriados de acordo com o tipo de equipamento, as especificações do pedido e seu nome. Você pode então ajustar as propostas e desmarcar o aplicativo para certos comandos antes de aceitar ou não. Esta função é compatível com a seleção por caixas de seleção.

- **Tipos de redefinição** : Esta função remove os tipos genéricos de todos os comandos do equipamento.

> **Aviso**
>
> Nenhuma alteração é feita antes de salvar, com o botão no canto superior direito da página.

## Tipos e cenários genéricos

Na v4.2, o Core integrou os tipos genéricos nos cenários. Você pode, assim, acionar um cenário se uma lâmpada acender em uma sala, se for detectado movimento na casa, desligar todas as luzes ou fechar todas as venezianas com uma única ação, etc. Além disso, se você adicionar um equipamento, basta indicar os tipos corretos em seus pedidos, não será necessário editar tais cenários.

#### Desencadear

Você pode acionar um cenário a partir de sensores. Por exemplo, se você tiver detectores de movimento em casa, pode criar um cenário de alarme com cada detector acionando : ``#[Salão][Move Salon][Presence]# == 1`,`#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. Nesse cenário, você precisará de todos os detectores de movimento e, se adicionar um, terá que adicioná-lo aos gatilhos. Lógica.

Os tipos genéricos permitem que você use um único gatilho : ``#genericType(PRESENCE)# == 1`. Aqui, nenhum objeto é indicado, então o menor movimento em toda a casa irá desencadear o cenário. Se você adicionar um novo detector na casa, não há necessidade de editar o (s) cenário (s)).

Aqui, um gatilho para acender uma luz na sala de estar : ``#genericType(LIGHT_STATE,#[Salão]#)# > 0`

#### Expression

Se, em um cenário, você quiser saber se uma luz está acesa na sala de estar, você pode fazer :

SE `#[Salão][Lumiere Canapé][Estado]# == 1 OU #[Salão][Lumiere Salon][Estado]# == 1 OU #[Salão][Lumiere Angle][Estado]# == 1`

Ou mais simplesmente : IF `genericType (LIGHT_STATE,#[Salão]#) > 0` ou se uma ou mais luzes estiverem acesas na sala de estar.

Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


#### Action

Se você deseja acender todas as luzes da sala de estar, pode criar uma ação leve:

`` ``
#[Salão][Lumiere Canapé][Nós]#
#[Salão][Lumiere Salon][Nós]#
#[Salão][Lumiere Angle][Nós]#
`` ``

Ou mais simplesmente, crie uma ação `genericType` com` LIGHT_ON` no `Salon`. Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


## Lista de tipos de núcleo genérico

> **Gorjeta**
>
> - Você pode encontrar essa lista diretamente no Jeedom, nesta mesma página, com o botão **Listagem** canto superior direito.

| **Outro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CRONÔMETRO | Cronômetro de estado | Em formação | numeric
| TIMER_STATE | Status do temporizador (pausa ou não) | Em formação | binário, numérico
| DEFINIR TEMPORIZADOR | Cronômetro | Estoque | slider
| TIMER_PAUSE | Pausar cronômetro | Estoque | other
| TIMER_RESUME | Resumo do cronômetro | Estoque | other

| **Bateria (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERIA | Bateria | Em formação | numeric
| BATERIA_CARREGANDO | Carregamento de bateria | Em formação | binary

| **Câmera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Url da câmera | Em formação | string
| CAMERA_RECORD_STATE | Status de gravação da câmera | Em formação | binary
| CAMERA_UP | Movimento da câmera para cima | Estoque | other
| CAMERA_DOWN | Movimento da câmera para baixo | Estoque | other
| CAMERA_LEFT | Movimento da câmera para a esquerda | Estoque | other
| CAMERA_RIGHT | Movimento da câmera para a direita | Estoque | other
| CAMERA_ZOOM | Zoom da câmera para frente | Estoque | other
| CAMERA_DEZOOM | Zoom da câmera para trás | Estoque | other
| CAMERA_STOP | Parar câmera | Estoque | other
| CAMERA_PRESET | Predefinição da câmera | Estoque | other
| CAMERA_RECORD | Gravação de câmera | Estoque |
| CAMERA_TAKE | Câmera instantânea | Estoque |

| **Aquecimento (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status de aquecimento do fio piloto | Em formação | binary
| AQUECIMENTO_ON | Botão LIGADO de aquecimento do fio piloto | Estoque | other
| AQUECIMENTO_OFF | Botão de aquecimento do fio piloto DESLIGADO | Estoque | other
| HEATING_OTHER | Botão do fio piloto de aquecimento | Estoque | other

| **Eletricidade (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POTÊNCIA | Energia elétrica | Em formação | numeric
| CONSUMO | Consumo de energia | Em formação | numeric
| TENSÃO | Tensão | Em formação | numeric
| REINÍCIO | Reiniciar | Estoque | other

| **Ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | TEMPERATURA | Em formação | numeric
| QUALIDADE DO AR | Qualidade do ar | Em formação | numeric
| BRILHO | Brilho | Em formação | numeric
| PRESENÇA | PRESENÇA | Em formação | binary
| FUMAÇA | Detecção de fumaça | Em formação | binary
| UMIDADE | Umidade | Em formação | numeric
| Ultravioleta | Ultravioleta | Em formação | numeric
| CO2 | CO2 (ppm) | Em formação | numeric
| CO | CO (ppm) | Em formação | numeric
| BARULHO | Som (dB) | Em formação | numeric
| PRESSÃO | Pressão | Em formação | numeric
| VAZAMENTO DE ÁGUA | Vazamento de água | Em formação |
| FILTER_CLEAN_STATE | Estado do filtro | Em formação | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDADE | Profundidade | Em formação | numeric
| DISTÂNCIA | Distância | Em formação | numeric
| BOTÃO | Botão | Em formação | binário, numérico
| GENERIC_INFO |  Genérico | Em formação |
| GENERIC_ACTION |  Genérico | Estoque | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado claro | Em formação | binário, numérico
| LIGHT_BRIGHTNESS | Brilho da luz | Em formação | numeric
| COR CLARA | Cor clara | Em formação | string
| LIGHT_STATE_BOOL | Estado Leve (Binário) | Em formação | binary
| LIGHT_COLOR_TEMP | Cor da temperatura da luz | Em formação | numeric
| LIGHT_TOGGLE | Alternar luz | Estoque | other
| LUZES LIGADAS | Botão de luz ligado | Estoque | other
| LUZ APAGADA | Botão de luz apagado | Estoque | other
| LIGHT_SLIDER | Luz deslizante | Estoque | slider
| LIGHT_SET_COLOR | Cor clara | Estoque | color
| LIGHT_MODE | Modo de luz | Estoque | other
| LIGHT_SET_COLOR_TEMP | Cor da temperatura da luz | Estoque |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo de status | Em formação | string
| MODE_SET_STATE | Modo de mudança | Estoque | other

| **Multimídia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume | Em formação | numeric
| MEDIA_STATUS | Status | Em formação | string
| MEDIA_ALBUM | Álbum | Em formação | string
| MEDIA_ARTIST | Artista | Em formação | string
| MEDIA_TITLE | Título | Em formação | string
| MEDIA_POWER | Poder | Em formação | string
| CANAL | Corrente | Em formação | numérico, string
| MEDIA_STATE | Estado | Em formação | binary
| SET_VOLUME | Volume | Estoque | slider
| SET_CHANNEL | Corrente | Estoque | outro controle deslizante
| MEDIA_PAUSE | Pausa | Estoque | other
| MEDIA_RESUME | Lendo | Estoque | other
| MEDIA_STOP | Pare | Estoque | other
| MEDIA_NEXT | Próximo | Estoque | other
| MEDIA_PREVIOUS | Anterior | Estoque | other
| MEDIA_ON | Nós | Estoque | other
| MEDIA_OFF | Desligado | Estoque | other
| MEDIA_MUTE | Mudo | Estoque | other
| MEDIA_UNMUTE | Sem mudo | Estoque | other

| **Tempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPO_TEMPERATURA | Temperatura do tempo | Em formação | numeric
| TEMPO_TEMPERATURE_MAX_2 | Condição climática d + 1 max d + 2 | Em formação | numeric
| VELOCIDADE DO VENTO | Velocidade do vento) | Em formação | numeric
| RAIN_TOTAL | Chuva (acumulação) | Em formação | numeric
| RAIN_CURRENT | Chuva (mm / h) | Em formação | numeric
| WEATHER_CONDITION_ID_4 | Condição climática (id) d + 4 | Em formação | numeric
| TEMPO_CONDIÇÃO_4 | Condição climática d + 4 | Em formação | string
| TEMPO_TEMPERATURE_MAX_4 | Temperatura máxima do tempo d + 4 | Em formação | numeric
| TEMPO_TEMPERATURE_MIN_4 | Temperatura do tempo min d + 4 | Em formação | numeric
| WEATHER_CONDITION_ID_3 | Condição climática (id) d + 3 | Em formação | numeric
| TEMPO_CONDIÇÃO_3 | Condição climática d + 3 | Em formação | string
| TEMPO_TEMPERATURE_MAX_3 | Temperatura máxima do tempo d + 3 | Em formação | numeric
| TEMPO_TEMPERATURE_MIN_3 | Temperatura do tempo min d + 3 | Em formação | numeric
| WEATHER_CONDITION_ID_2 | Condição climática (id) d + 2 | Em formação | numeric
| TEMPO_CONDIÇÃO_2 | Condição climática d + 2 | Em formação | string
| TEMPO_TEMPERATURE_MIN_2 | Temperatura do tempo min d + 2 | Em formação | numeric
| TEMPO_UMIDADE | Umidade do tempo | Em formação | numeric
| WEATHER_CONDITION_ID_1 | Condição climática (id) d + 1 | Em formação | numeric
| TEMPO_CONDIÇÃO_1 | Condição climática d + 1 | Em formação | string
| TEMPO_TEMPERATURE_MAX_1 | Temperatura máxima do tempo d + 1 | Em formação | numeric
| TEMPO_TEMPERATURE_MIN_1 | Temperatura do tempo min d + 1 | Em formação | numeric
| WEATHER_CONDITION_ID | Condições meteorológicas (id) | Em formação | numeric
| TEMPO_CONDIÇÃO | Condição do tempo | Em formação | string
| WEATHER_TEMPERATURE_MAX | Temperatura máxima do tempo | Em formação | numeric
| WEATHER_TEMPERATURE_MIN | Temperatura mínima do tempo | Em formação | numeric
| TEMPO_SUNRISE | Clima de pôr do sol | Em formação | numeric
| TEMPO_SUNSET | Clima do nascer do sol | Em formação | numeric
| WEATHER_WIND_DIRECTION | Tempo de direção do vento | Em formação | numeric
| WEATHER_WIND_SPEED | Tempo de velocidade do vento | Em formação | numeric
| TEMPO_PRESSÃO | Pressão do Tempo | Em formação | numeric
| DIREÇÃO DO VENTO | Direção do vento) | Em formação | numeric

| **Abrindo (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Bloqueio de estado | Em formação | binary
| BARRIER_STATE | Estado do portal (abertura) | Em formação | binary
| GARAGE_STATE | Estado de garagem (abertura) | Em formação | binary
| ABERTURA | Porta | Em formação | binary
| OPENING_WINDOW | Janela | Em formação | binary
| LOCK_OPEN | Botão de bloqueio aberto | Estoque | other
| LOCK_CLOSE | Botão de bloqueio Fechar | Estoque | other
| GB_OPEN | Botão de abertura do portão ou garagem | Estoque | other
| GB_CLOSE | Botão de fechamento do portão ou garagem | Estoque | other
| GB_TOGGLE | Botão de alternância de portão ou garagem | Estoque | other

| **Soquete (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Soquete de estado | Em formação | numérico, binário
| ENERGY_ON | No Soquete de Botão | Estoque | other
| ENERGY_OFF | Botão de soquete desligado | Estoque | other
| ENERGY_SLIDER | Soquete deslizante | Estoque |

| **Robô (código: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base estadual | Em formação | binary
| DOCA | De volta à base | Estoque | other

| **Segurança (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Estado da sereia | Em formação | binary
| ALARM_STATE | Status de Alarme | Em formação | binário, string
| ALARM_MODE | Modo de Alarme | Em formação | string
| ALARM_ENABLE_STATE | Status de alarme ativado | Em formação | binary
| ENCHENTE | Enchente | Em formação | binary
| SABOTAR | Sabotar | Em formação | binary
| CHOQUE | Choque | Em formação | binário, numérico
| SIREN_OFF | Botão da sirene desligado | Estoque | other
| SIREN_ON | Botão de sirene ligado | Estoque | other
| ALARME_ARMED | Alarme armado | Estoque | other
| ALARM_RELEASED | Alarme liberado | Estoque | other
| ALARM_SET_MODE | Modo de Alarme | Estoque | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TERMOSTAT_STATE | Status do termostato (BINÁRIO) (apenas para termostato de plug-in) | Em formação |
| TERMOSTAT_TEMPERATURE | Termostato de temperatura ambiente | Em formação | numeric
| TERMOSTAT_SETPOINT | Termostato de ponto de ajuste | Em formação | numeric
| THERMOSTAT_MODE | Modo do termostato (apenas para termostato de plug-in) | Em formação | string
| TERMOSTATO_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Em formação | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Termostato de temperatura externa (apenas para termostato de plug-in) | Em formação | numeric
| THERMOSTAT_STATE_NAME | Status do termostato (HUMANO) (apenas para termostato de plug-in) | Em formação | string
| TERMOSTATO_HUMIDITY | Termostato de umidade ambiente | Em formação | numeric
| HUMIDITY_SETPOINT | Definir umidade | Em formação | slider
| THERMOSTAT_SET_SETPOINT | Termostato de ponto de ajuste | Estoque | slider
| THERMOSTAT_SET_MODE | Modo do termostato (apenas para termostato de plug-in) | Estoque | other
| TERMOSTAT_SET_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Estoque | other
| THERMOSTAT_SET_UNLOCK | Desbloquear termostato (apenas para termostato de plug-in) | Estoque | other
| HUMIDITY_SET_SETPOINT | Definir umidade | Estoque | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Status da velocidade do ventilador | Em formação | numeric
| ROTATION_STATE | Rotação de estado | Em formação | numeric
| VELOCIDADE DO VENTILADOR | Velocidade do ventilador | Estoque | slider
| RODAR | Rodar | Estoque | slider

| **Painel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Painel de status | Em formação | binário, numérico
| FLAP_BSO_STATE | Painel de status do BSO | Em formação | binário, numérico
| FLAP_UP | Botão Pane Up | Estoque | other
| FLAP_DOWN | Botão do painel para baixo | Estoque | other
| FLAP_STOP | Botão de parada do obturador | Estoque |
| FLAP_SLIDER | Painel de botões deslizantes | Estoque | slider
| FLAP_BSO_UP | Botão para cima do painel BSO | Estoque | other
| FLAP_BSO_DOWN | Botão para baixo do painel BSO | Estoque | other
