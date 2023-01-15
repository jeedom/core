# Tipos de equipamento
**Ferramentas → Tipos de equipamento**

Os sensores e atuadores no Jeedom são gerenciados por plug-ins, que criam equipamentos com comandos *Em formação* (sensor) ou *Ações* (atuador do). Isso torna possível disparar ações com base na mudança de certos sensores, como acender uma luz na detecção de movimento. Mas o Jeedom Core e plug-ins como *Móvel*, *Homebridge*, *Casa inteligente do Google*, *Alexa Casa Inteligente* etc, não sei o que é este equipamento : Uma tomada, uma luz, uma veneziana, etc.

Para superar esse problema, especialmente com assistentes de voz (*Acenda a luz da sala*), Core introduziu o **Tipos Genéricos**, usado por esses plugins.

Isso torna possível identificar uma peça de equipamento por *A luz da sala* por exemplo.

Na maioria das vezes os tipos genéricos são definidos automaticamente ao configurar o seu módulo (inclusão no Z-wave por exemplo). Mas pode haver momentos em que você precisa reconfigurá-los. A configuração destes Tipos Genéricos pode ser feita diretamente em certos plugins, ou por comando em *Configuração avançada* disso.

Esta página permite que esses Tipos Genéricos sejam configurados de forma mais direta e simples, e ainda oferece atribuição automática uma vez que os dispositivos tenham sido atribuídos corretamente.

![Tipos de equipamento](./images/coreGenerics.gif)

## Tipo de equipamento

Esta página oferece armazenamento por tipo de equipamento : Soquete, luz, obturador, termostato, câmera, etc. Inicialmente, a maior parte do seu equipamento será classificado em **Equipamento sem tipo**. Para atribuir um tipo a eles, você pode movê-los para outro tipo ou clicar com o botão direito no equipamento para movê-lo diretamente. O tipo de equipamento não é realmente útil em si, sendo o mais importante os tipos de pedido. Você pode, portanto, ter um Equipamento sem um Tipo, ou um Tipo que não corresponda necessariamente aos seus comandos. Você pode, é claro, misturar tipos de controles no mesmo equipamento. Por enquanto, é mais um armazenamento, uma organização lógica, que talvez servirá em versões futuras.

> **Dica**
>
> - Quando você move o equipamento no jogo **Equipamento sem tipo**, Jeedom sugere que você remova os tipos genéricos de seus pedidos.
> - Você pode mover vários equipamentos de uma vez marcando as caixas de seleção à esquerda deles.

## Tipo de comando

Uma vez que um item do equipamento é posicionado na posição correta *Gentil*, clicando nele você acessa a lista de seus pedidos, colorida de forma diferente se for um *Em formação* (Azul) ou um *Ações* (Orange).

Ao clicar com o botão direito em um pedido, você pode atribuir a ele um tipo genérico correspondente às especificações desse pedido (tipo de informação / ação, numérico, subtipo binário, etc).

> **Dica**
>
> - O menu contextual de comandos exibe o tipo de equipamento em negrito, mas ainda permite atribuir qualquer Tipo Genérico de qualquer tipo de equipamento.

Em cada dispositivo, você tem dois botões :

- **Tipos de automóveis** : Esta função abre uma janela que oferece os Tipos Genéricos apropriados de acordo com o tipo de equipamento, as especificações do pedido e seu nome. Você pode então ajustar as propostas e desmarcar o aplicativo para certos comandos antes de aceitar ou não. Esta função é compatível com a seleção por caixas de seleção.

- **Tipos de redefinição** : Esta função remove os tipos genéricos de todos os comandos do equipamento.

> **Atenção**
>
> Nenhuma alteração é feita antes de salvar, com o botão no canto superior direito da página.

## Tipos e cenários genéricos

Na v4.2, o Core integrou os tipos genéricos nos cenários. Você pode, assim, acionar um cenário se uma lâmpada acender em uma sala, se for detectado movimento na casa, desligar todas as luzes ou fechar todas as venezianas com uma única ação, etc. Além disso, se você adicionar um equipamento, basta indicar os tipos corretos em seus pedidos, não será necessário editar tais cenários.

#### Desencadear

Você pode acionar um cenário a partir de sensores. Por exemplo, se você tiver detectores de movimento em casa, pode criar um cenário de alarme com cada detector acionando : ``#[Salão][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. Nesse cenário, você precisará de todos os detectores de movimento e, se adicionar um, terá que adicioná-lo aos gatilhos. Lógica.

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

> **Dica**
>
> - Você pode encontrar essa lista diretamente no Jeedom, nesta mesma página, com o botão **Listagem** canto superior direito.

| **Outro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CRONÔMETRO | Cronômetro de estado | Em formação | numeric
| TIMER_STATE | Status do temporizador (pausa ou não) | Em formação | binário, numérico
| DEFINIR TEMPORIZADOR | Cronômetro | Ações | slider
| TIMER_PAUSE | Pausar cronômetro | Ações | other
| TIMER_RESUME | Resumo do cronômetro | Ações | other

| **Bateria (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERIA | Bateria | Em formação | numeric
| BATTERY_CHARGING | Carregamento de bateria | Em formação | binary

| **Câmera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Url da câmera | Em formação | string
| CAMERA_RECORD_STATE | Status de gravação da câmera | Em formação | binary
| CAMERA_UP | Movimento da câmera para cima | Ações | other
| CAMERA_DOWN | Movimento da câmera para baixo | Ações | other
| CAMERA_LEFT | Movimento da câmera para a esquerda | Ações | other
| CAMERA_RIGHT | Movimento da câmera para a direita | Ações | other
| CAMERA_ZOOM | Zoom da câmera para frente | Ações | other
| CAMERA_DEZOOM | Zoom da câmera para trás | Ações | other
| CAMERA_STOP | Parar câmera | Ações | other
| CAMERA_PRESET | Predefinição da câmera | Ações | other
| CAMERA_RECORD | Gravação de câmera | Ações |
| CAMERA_TAKE | Câmera instantânea | Ações |

| **Aquecimento (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status de aquecimento do fio piloto | Em formação | binary
| AQUECIMENTO_ON | Botão LIGADO de aquecimento do fio piloto | Ações | other
| AQUECIMENTO_OFF | Botão de aquecimento do fio piloto DESLIGADO | Ações | other
| HEATING_OTHER | Botão do fio piloto de aquecimento | Ações | other

| **Eletricidade (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Poder | Energia elétrica | Em formação | numeric
| CONSUMO | Consumo de energia | Em formação | numeric
| VOLTAGEM | Tensão | Em formação | numeric
| REINÍCIO | Reiniciar | Ações | other

| **Ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | TEMPERATURA | Em formação | numeric
| QUALIDADE DO AR | Qualidade do ar | Em formação | numeric
| BRILHO | Brilho | Em formação | numeric
| PRESENÇA | PRESENÇA | Em formação | binary
| FUMAÇA | Detecção de fumaça | Em formação | binary
| UMIDADE | Umidade | Em formação | numeric
| UV | UV | Em formação | numeric
| CO2 | CO2 (ppm) | Em formação | numeric
| CO | CO (ppm) | Em formação | numeric
| RUÍDO | Som (dB) | Em formação | numeric
| PRESSÃO | Pressão | Em formação | numeric
| VAZAMENTO DE ÁGUA | Vazamento de água | Em formação |
| FILTER_CLEAN_STATE | Estado do filtro | Em formação | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDADE | Profundidade | Em formação | numeric
| DISTÂNCIA | DISTÂNCIA | Em formação | numeric
| BOTÃO | Botão | Em formação | binário, numérico
| GENERIC_INFO |  Genérico | Em formação |
| GENERIC_ACTION |  Genérico | Ações | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado claro | Em formação | binário, numérico
| LIGHT_BRIGHTNESS | Brilho da luz | Em formação | numeric
| COR CLARA | Cor clara | Em formação | string
| LIGHT_STATE_BOOL | Estado Leve (Binário) | Em formação | binary
| LIGHT_COLOR_TEMP | Cor da temperatura da luz | Em formação | numeric
| LIGHT_TOGGLE | Alternar luz | Ações | other
| LUZES LIGADAS | Botão de luz ligado | Ações | other
| LUZ APAGADA | Botão de luz apagado | Ações | other
| LIGHT_SLIDER | Luz deslizante | Ações | slider
| LIGHT_SET_COLOR | Cor clara | Ações | color
| LIGHT_MODE | Modo de luz | Ações | other
| LIGHT_SET_COLOR_TEMP | Cor da temperatura da luz | Ações |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo de status | Em formação | string
| MODE_SET_STATE | Modo de mudança | Ações | other

| **Multimídia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Volume | Volume | Em formação | numeric
| MEDIA_STATUS | Status | Em formação | string
| MEDIA_ALBUM | Álbum | Em formação | string
| MEDIA_ARTIST | Artista | Em formação | string
| MEDIA_TITLE | Título | Em formação | string
| MEDIA_POWER | Poder | Em formação | string
| CANAL | Corrente | Em formação | numérico, string
| MEDIA_STATE | Estado | Em formação | binary
| SET_VOLUME | Volume | Ações | slider
| SET_CHANNEL | Corrente | Ações | outro controle deslizante
| MEDIA_PAUSE | Pausa | Ações | other
| MEDIA_RESUME | Leitura | Ações | other
| MEDIA_STOP | Pare | Ações | other
| MEDIA_NEXT | Próximo | Ações | other
| MEDIA_PREVIOUS | Anterior | Ações | other
| MEDIA_ON | Nós | Ações | other
| MEDIA_OFF | Desligado | Ações | other
| MEDIA_MUTE | Mudo | Ações | other
| MEDIA_UNMUTE | Sem mudo | Ações | other

| **Tempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Temperatura do tempo | Em formação | numeric
| WEATHER_TEMPERATURE_MAX_2 | Condição climática d + 1 max d + 2 | Em formação | numeric
| VELOCIDADE DO VENTO | Velocidade do vento) | Em formação | numeric
| RAIN_TOTAL | Chuva (acumulação) | Em formação | numeric
| RAIN_CURRENT | Chuva (mm / h) | Em formação | numeric
| WEATHER_CONDITION_ID_4 | Condição climática (id) d + 4 | Em formação | numeric
| WEATHER_CONDITION_4 | Condição climática d + 4 | Em formação | string
| WEATHER_TEMPERATURE_MAX_4 | Temperatura máxima do tempo d + 4 | Em formação | numeric
| WEATHER_TEMPERATURE_MIN_4 | Temperatura do tempo min d + 4 | Em formação | numeric
| WEATHER_CONDITION_ID_3 | Condição climática (id) d + 3 | Em formação | numeric
| WEATHER_CONDITION_3 | Condição climática d + 3 | Em formação | string
| WEATHER_TEMPERATURE_MAX_3 | Temperatura máxima do tempo d + 3 | Em formação | numeric
| WEATHER_TEMPERATURE_MIN_3 | Temperatura do tempo min d + 3 | Em formação | numeric
| WEATHER_CONDITION_ID_2 | Condição climática (id) d + 2 | Em formação | numeric
| WEATHER_CONDITION_2 | Condição climática d + 2 | Em formação | string
| WEATHER_TEMPERATURE_MIN_2 | Temperatura do tempo min d + 2 | Em formação | numeric
| WEATHER_HUMIDITY | Umidade do tempo | Em formação | numeric
| WEATHER_CONDITION_ID_1 | Condição climática (id) d + 1 | Em formação | numeric
| WEATHER_CONDITION_1 | Condição climática d + 1 | Em formação | string
| WEATHER_TEMPERATURE_MAX_1 | Temperatura máxima do tempo d + 1 | Em formação | numeric
| WEATHER_TEMPERATURE_MIN_1 | Temperatura do tempo min d + 1 | Em formação | numeric
| WEATHER_CONDITION_ID | Condições meteorológicas (id) | Em formação | numeric
| WEATHER_CONDITION | Condição do tempo | Em formação | string
| WEATHER_TEMPERATURE_MAX | Temperatura máxima do tempo | Em formação | numeric
| WEATHER_TEMPERATURE_MIN | Temperatura mínima do tempo | Em formação | numeric
| WEATHER_SUNRISE | Clima de pôr do sol | Em formação | numeric
| WEATHER_SUNSET | Clima do nascer do sol | Em formação | numeric
| WEATHER_WIND_DIRECTION | Tempo de direção do vento | Em formação | numeric
| WEATHER_WIND_SPEED | Tempo de velocidade do vento | Em formação | numeric
| WEATHER_PRESSURE | Pressão do Tempo | Em formação | numeric
| DIREÇÃO DO VENTO | Direção do vento) | Em formação | numeric

| **Abrindo (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Bloqueio de estado | Em formação | binary
| BARRIER_STATE | Estado do portal (abertura) | Em formação | binary
| GARAGE_STATE | Estado de garagem (abertura) | Em formação | binary
| ABERTURA | Portão | Em formação | binary
| OPENING_WINDOW | Janela | Em formação | binary
| LOCK_OPEN | Botão de bloqueio aberto | Ações | other
| LOCK_CLOSE | Botão de bloqueio Fechar | Ações | other
| GB_OPEN | Botão de abertura do portão ou garagem | Ações | other
| GB_CLOSE | Botão de fechamento do portão ou garagem | Ações | other
| GB_TOGGLE | Botão de alternância de portão ou garagem | Ações | other

| **Soquete (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Soquete de estado | Em formação | numérico, binário
| ENERGY_ON | No Soquete de Botão | Ações | other
| ENERGY_OFF | Botão de soquete desligado | Ações | other
| ENERGY_SLIDER | Soquete deslizante | Ações |

| **Robô (código: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base estadual | Em formação | binary
| DOCA | De volta à base | Ações | other

| **Segurança (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Estado da sereia | Em formação | binary
| ALARM_STATE | Status de Alarme | Em formação | binário, string
| ALARM_MODE | Modo de Alarme | Em formação | string
| ALARM_ENABLE_STATE | Status de alarme ativado | Em formação | binary
| ENCHENTE | Enchente | Em formação | binary
| SABOTAR | SABOTAR | Em formação | binary
| CHOQUE | Choque | Em formação | binário, numérico
| SIREN_OFF | Botão da sirene desligado | Ações | other
| SIREN_ON | Botão de sirene ligado | Ações | other
| ALARME_ARMED | Alarme armado | Ações | other
| ALARM_RELEASED | Alarme liberado | Ações | other
| ALARM_SET_MODE | Modo de Alarme | Ações | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Status do termostato (BINÁRIO) (apenas para termostato de plug-in) | Em formação |
| THERMOSTAT_TEMPERATURE | Termostato de temperatura ambiente | Em formação | numeric
| THERMOSTAT_SETPOINT | Termostato de ponto de ajuste | Em formação | numeric
| THERMOSTAT_MODE | Modo do termostato (apenas para termostato de plug-in) | Em formação | string
| TERMOSTATO_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Em formação | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Termostato de temperatura externa (apenas para termostato de plug-in) | Em formação | numeric
| THERMOSTAT_STATE_NAME | Status do termostato (HUMANO) (apenas para termostato de plug-in) | Em formação | string
| TERMOSTATO_HUMIDITY | Termostato de umidade ambiente | Em formação | numeric
| HUMIDITY_SETPOINT | Definir umidade | Em formação | slider
| THERMOSTAT_SET_SETPOINT | Termostato de ponto de ajuste | Ações | slider
| THERMOSTAT_SET_MODE | Modo do termostato (apenas para termostato de plug-in) | Ações | other
| THERMOSTAT_SET_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Ações | other
| THERMOSTAT_SET_UNLOCK | Desbloquear termostato (apenas para termostato de plug-in) | Ações | other
| HUMIDITY_SET_SETPOINT | Definir umidade | Ações | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Status da velocidade do ventilador | Em formação | numeric
| ROTATION_STATE | Rotação de estado | Em formação | numeric
| VELOCIDADE DO VENTILADOR | Velocidade do ventilador | Ações | slider
| RODAR | RODAR | Ações | slider

| **Painel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Painel de status | Em formação | binário, numérico
| FLAP_BSO_STATE | Painel de status do BSO | Em formação | binário, numérico
| FLAP_UP | Botão Pane Up | Ações | other
| FLAP_DOWN | Botão do painel para baixo | Ações | other
| FLAP_STOP | Botão de parada do obturador | Ações |
| FLAP_SLIDER | Painel de botões deslizantes | Ações | slider
| FLAP_BSO_UP | Botão para cima do painel BSO | Ações | other
| FLAP_BSO_DOWN | Botão para baixo do painel BSO | Ações | other
