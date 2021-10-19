# Tipos de equipamento
**Ferramentas → Tipos de equipamento**

Os sensores e atuadores no Jeedom são gerenciados por plug-ins, que criam equipamentos com comandos *Informações* (sensor) ou *Açao* (atuador do). Isso torna possível disparar ações com base na mudança de certos sensores, como acender uma luz na detecção de movimento. Mas o Jeedom Core e plug-ins como *Móvel*, *Homebridge*, *Google Smarthome*, *Alexa* etc, não sei o que é este equipamento : Uma tomada, uma luz, uma veneziana, etc.

Para superar esse problema, especialmente com assistentes de voz (*Acenda a luz da sala*), Core introduziu o **Tipos Genéricos**, usado por esses plugins.

Isso torna possível identificar uma peça de equipamento por *A luz da sala* por exemplo.

Tipos genéricos também são integrados em cenários. Você pode, assim, acionar um cenário se uma lâmpada acender em uma sala, se for detectado movimento na casa, desligar todas as luzes ou fechar todas as venezianas com uma única ação, etc. Além disso, se você adicionar um equipamento, basta indicar esses tipos, não será necessário editar tais cenários.

A configuração destes Tipos Genéricos pode ser feita diretamente em certos plugins, ou por comando em *Configuração avançada* disso.

Esta página permite que esses Tipos Genéricos sejam configurados diretamente, de forma mais direta e simples, e ainda oferece atribuição automática uma vez que os dispositivos tenham sido atribuídos corretamente.

![Tipos de equipamento](./images/coreGenerics.gif)

## Tipo de equipamento

Esta página oferece armazenamento por tipo de equipamento : Soquete, luz, obturador, termostato, câmera, etc. Inicialmente, a maior parte do seu equipamento será classificado em **Equipamento sem tipo**. Para atribuir um tipo a eles, você pode movê-los para outro tipo ou clicar com o botão direito no equipamento para movê-lo diretamente.

> **Gorjeta**
>
> - Quando você move equipamentos no jogo **Equipamento sem tipo**, Jeedom sugere que você remova os tipos genéricos de seus pedidos.
> - Você pode mover vários equipamentos de uma vez marcando as caixas de seleção à esquerda deles.

## Tipo de comando

Uma vez que um item do equipamento é posicionado na posição correta *Modelo*, clicando nele você acessa a lista de seus pedidos, colorida de forma diferente se for um *Informações* (Azul) ou um *Açao* (Orange).

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


## Lista de tipos de núcleo genérico

| **Outro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CRONÔMETRO | Timer de estado (não gerenciado pelo aplicativo móvel) | Informações | numeric
| TIMER_STATE | Status do temporizador (pausa ou não) (não gerenciado pelo aplicativo móvel) | Informações | binário, numérico
| DEFINIR TEMPORIZADOR | Timer (não gerenciado pelo aplicativo móvel) | Açao | slider
| TIMER_PAUSE | Temporizador de pausa (não gerenciado pelo aplicativo móvel) | Açao | other
| TIMER_RESUME | Resumo do cronômetro (não gerenciado pelo aplicativo móvel) | Açao | other

| **Bateria (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERIA | Bateria (não gerenciado pelo aplicativo móvel) | Informações | numeric
| BATTERY_CHARGING | Carregamento da bateria (não gerenciado pelo aplicativo móvel) | Informações | binary

| **Câmera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Url da câmera | Informações | string
| CAMERA_RECORD_STATE | Status de gravação da câmera | Informações | binary
| CAMERA_UP | Movimento da câmera para cima | Açao | other
| CAMERA_DOWN | Movimento da câmera para baixo | Açao | other
| CAMERA_LEFT | Movimento da câmera para a esquerda | Açao | other
| CAMERA_RIGHT | Movimento da câmera para a direita | Açao | other
| CAMERA_ZOOM | Zoom da câmera para frente | Açao | other
| CAMERA_DEZOOM | Zoom da câmera para trás | Açao | other
| CAMERA_STOP | Parar câmera | Açao | other
| CAMERA_PRESET | Predefinição da câmera | Açao | other
| CAMERA_RECORD | Gravação de câmera | Açao |
| CAMERA_TAKE | Câmera instantânea | Açao |

| **Aquecimento (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status de aquecimento do fio piloto | Informações | binary
| HEATING_ON | Botão LIGADO de aquecimento do fio piloto | Açao | other
| HEATING_OFF | Botão de aquecimento do fio piloto DESLIGADO | Açao | other
| HEATING_OTHER | Botão do fio piloto de aquecimento | Açao | other

| **Eletricidade (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POTÊNCIA | Energia elétrica | Informações | numeric
| CONSUMO | Consumo de eletricidade (não gerenciado pelo aplicativo móvel) | Informações | numeric
| VOLTAGEM | Voltage (não gerenciado pelo aplicativo móvel) | Informações | numeric
| REINÍCIO | Reiniciar (não gerenciado pelo Application Mobile) | Açao | other

| **Ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | Temperatura | Informações | numeric
| QUALIDADE DO AR | Qualidade do ar | Informações | numeric
| BRILHO | Brilho | Informações | numeric
| PRESENÇA | Presença | Informações | binary
| FUMAÇA | Detecção de fumaça | Informações | binary
| UMIDADE | Umidade | Informações | numeric
| UV | UV (não gerenciado pelo aplicativo móvel) | Informações | numeric
| CO2 | CO2 (ppm) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| CO | CO (ppm) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| BARULHO | Som (dB) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| PRESSÃO | Pressão (não gerenciado pelo aplicativo móvel) | Informações | numeric
| VAZAMENTO DE ÁGUA | Vazamento de água (não gerenciado pelo aplicativo móvel) | Informações |
| FILTER_CLEAN_STATE | Status do filtro (não gerenciado pelo aplicativo móvel) | Informações | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDADE | Profundidade | Informações | numeric
| DISTÂNCIA | Distância | Informações | numeric
| BOTÃO | Botão | Informações | binário, numérico
| GENERIC_INFO |  Genérico | Informações |
| GENERIC_ACTION |  Genérico | Açao | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado claro | Informações | binário, numérico
| LIGHT_BRIGHTNESS | Brilho da luz (não gerenciado pelo aplicativo móvel) | Informações | numeric
| COR CLARA | Cor clara | Informações | string
| LIGHT_STATE_BOOL | Estado leve (binário) (não gerenciado pelo aplicativo móvel) | Informações | binary
| LIGHT_COLOR_TEMP | Temperatura da luz de cor (não gerenciado pelo aplicativo móvel) | Informações | numeric
| LIGHT_TOGGLE | Alternar luz | Açao | other
| LUZES LIGADAS | Botão de luz ligado | Açao | other
| LUZ APAGADA | Botão de luz apagado | Açao | other
| LIGHT_SLIDER | Luz deslizante | Açao | slider
| LIGHT_SET_COLOR | Cor clara | Açao | color
| LIGHT_MODE | Modo de luz | Açao | other
| LIGHT_SET_COLOR_TEMP | Temperatura da luz de cor (não gerenciado pelo aplicativo móvel) | Açao |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo de status | Informações | string
| MODE_SET_STATE | Modo de mudança | Açao | other

| **Multimídia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume | Informações | numeric
| MEDIA_STATUS | Status (não gerenciado pelo aplicativo móvel) | Informações | string
| MEDIA_ALBUM | Álbum (não gerenciado por Application Mobile) | Informações | string
| MEDIA_ARTIST | Artista (não gerenciado pelo Application Mobile) | Informações | string
| MEDIA_TITLE | Título (não gerenciado pelo aplicativo móvel) | Informações | string
| MEDIA_POWER | Power (não gerenciado pelo Application Mobile) | Informações | string
| CANAL | Cadeia | Informações | numérico, string
| MEDIA_STATE | Status (não gerenciado pelo aplicativo móvel) | Informações | binary
| SET_VOLUME | Volume | Açao | slider
| SET_CHANNEL | Cadeia | Açao | outro controle deslizante
| MEDIA_PAUSE | Pausa | Açao | other
| MEDIA_RESUME | Lendo | Açao | other
| MEDIA_STOP | Pare | Açao | other
| MEDIA_NEXT | Seguindo | Açao | other
| MEDIA_PREVIOUS | Anterior | Açao | other
| MEDIA_ON | Ligado (não gerenciado pelo aplicativo móvel) | Açao | other
| MEDIA_OFF | Desligado (não gerenciado pelo aplicativo móvel) | Açao | other
| MEDIA_MUTE | Mudo (não gerenciado pelo aplicativo móvel) | Açao | other
| MEDIA_UNMUTE | Sem mudo (não gerenciado pelo aplicativo móvel) | Açao | other

| **Tempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Temperatura do tempo (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_TEMPERATURE_MAX_2 | Condição climática d + 1 max d + 2 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| VELOCIDADE DO VENTO | Vento (velocidade) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| RAIN_TOTAL | Chuva (acumulação) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| RAIN_CURRENT | Chuva (mm / h) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_ID_4 | Condição climática (id) d + 4 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_4 | Condição climática d + 4 (não gerenciado pelo aplicativo móvel) | Informações | string
| WEATHER_TEMPERATURE_MAX_4 | Temperatura máxima do tempo d + 4 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_TEMPERATURE_MIN_4 | Temperatura do tempo min d + 4 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_ID_3 | Condição climática (id) d + 3 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_3 | Condição climática d + 3 (não gerenciado pelo aplicativo móvel) | Informações | string
| WEATHER_TEMPERATURE_MAX_3 | Temperatura máxima do tempo d + 3 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_TEMPERATURE_MIN_3 | Temperatura do tempo min d + 3 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_ID_2 | Condição climática (id) d + 2 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_2 | Condição climática d + 2 (não gerenciado pelo aplicativo móvel) | Informações | string
| WEATHER_TEMPERATURE_MIN_2 | Temperatura do tempo min d + 2 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_HUMIDITY | Umidade do tempo (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_ID_1 | Condição climática (id) d + 1 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_1 | Condição climática d + 1 (não gerenciado pelo aplicativo móvel) | Informações | string
| WEATHER_TEMPERATURE_MAX_1 | Temperatura máxima do tempo d + 1 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_TEMPERATURE_MIN_1 | Temperatura do tempo min d + 1 (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION_ID | Condição climática (id) (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_CONDITION | Condições meteorológicas (não gerenciado pelo aplicativo móvel) | Informações | string
| WEATHER_TEMPERATURE_MAX | Temperatura máxima do tempo (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_TEMPERATURE_MIN | Temperatura mínima do tempo (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_SUNRISE | Clima do pôr do sol (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_SUNSET | Tempo do nascer do sol (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_WIND_DIRECTION | Previsão do tempo na direção do vento (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_WIND_SPEED | Previsão do tempo para a velocidade do vento (não gerenciado pelo aplicativo móvel) | Informações | numeric
| WEATHER_PRESSURE | Pressão do clima (não gerenciado pelo aplicativo móvel) | Informações | numeric
| DIREÇÃO DO VENTO | Vento (direção) (não gerenciado pelo aplicativo móvel) | Informações | numeric

| **Abrindo (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Bloqueio de estado | Informações | binary
| BARRIER_STATE | Estado do portal (abertura) | Informações | binary
| GARAGE_STATE | Estado de garagem (abertura) | Informações | binary
| ABERTURA | Porta | Informações | binary
| OPENING_WINDOW | Janela | Informações | binary
| LOCK_OPEN | Botão de bloqueio aberto | Açao | other
| LOCK_CLOSE | Botão de bloqueio Fechar | Açao | other
| GB_OPEN | Botão de abertura do portão ou garagem | Açao | other
| GB_CLOSE | Botão de fechamento do portão ou garagem | Açao | other
| GB_TOGGLE | Botão de alternância de portão ou garagem | Açao | other

| **Soquete (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Soquete de estado | Informações | numérico, binário
| ENERGY_ON | No Soquete de Botão | Açao | other
| ENERGY_OFF | Botão de soquete desligado | Açao | other
| ENERGY_SLIDER | Soquete deslizante | Açao |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base estadual (não gerenciado por aplicativo móvel) | Informações | binary
| DOCA | Retornar à base (não gerenciado pelo aplicativo móvel) | Açao | other

| **Segurança (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Estado da sereia | Informações | binary
| ALARM_STATE | Status do alarme (não gerenciado pelo aplicativo móvel) | Informações | binário, string
| ALARM_MODE | Modo de alarme (não gerenciado pelo aplicativo móvel) | Informações | string
| ALARM_ENABLE_STATE | Status de alarme ativado (não gerenciado pelo aplicativo móvel) | Informações | binary
| ENCHENTE | Enchente | Informações | binary
| SABOTAR | Sabotar | Informações | binary
| CHOQUE | Choque | Informações | binário, numérico
| SIREN_OFF | Botão da sirene desligado | Açao | other
| SIREN_ON | Botão de sirene ligado | Açao | other
| ALARM_ARMED | Alarme armado (não gerenciado pelo aplicativo móvel) | Açao | other
| ALARM_RELEASED | Alarme liberado (não gerenciado pelo aplicativo móvel) | Açao | other
| ALARM_SET_MODE | Modo de alarme (não gerenciado pelo aplicativo móvel) | Açao | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Status do termostato (BINÁRIO) (apenas para termostato de plug-in) | Informações |
| THERMOSTAT_TEMPERATURE | Termostato de temperatura ambiente | Informações | numeric
| THERMOSTAT_SETPOINT | Termostato de ponto de ajuste | Informações | numeric
| THERMOSTAT_MODE | Modo do termostato (apenas para termostato de plug-in) | Informações | string
| THERMOSTAT_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Informações | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Termostato de temperatura externa (apenas para termostato de plug-in) | Informações | numeric
| THERMOSTAT_STATE_NAME | Status do termostato (HUMANO) (apenas para termostato de plug-in) | Informações | string
| THERMOSTAT_HUMIDITY | Termostato de umidade ambiente (não gerenciado pelo aplicativo móvel) | Informações | numeric
| HUMIDITY_SETPOINT | Definir umidade (não gerenciado pelo aplicativo móvel) | Informações | slider
| THERMOSTAT_SET_SETPOINT | Termostato de ponto de ajuste | Açao | slider
| THERMOSTAT_SET_MODE | Modo do termostato (apenas para termostato de plug-in) | Açao | other
| THERMOSTAT_SET_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Açao | other
| THERMOSTAT_SET_UNLOCK | Desbloquear termostato (apenas para termostato de plug-in) | Açao | other
| HUMIDITY_SET_SETPOINT | Definir umidade (não gerenciado pelo aplicativo móvel) | Açao | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Status de velocidade do ventilador (não gerenciado pelo aplicativo móvel) | Informações | numeric
| ROTATION_STATE | Rotação de estado (não gerenciado pelo aplicativo móvel) | Informações | numeric
| VELOCIDADE DO VENTILADOR | Velocidade do ventilador (não gerenciado pelo aplicativo móvel) | Açao | slider
| ROTAÇÃO | Rotação (não gerenciado pelo aplicativo móvel) | Açao | slider

| **Painel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Painel de status | Informações | binário, numérico
| FLAP_BSO_STATE | Painel de status do BSO | Informações | binário, numérico
| FLAP_UP | Botão Pane Up | Açao | other
| FLAP_DOWN | Botão do painel para baixo | Açao | other
| FLAP_STOP | Botão de parada do obturador | Açao |
| FLAP_SLIDER | Painel de botões deslizantes | Açao | slider
| FLAP_BSO_UP | Botão para cima do painel BSO | Açao | other
| FLAP_BSO_DOWN | Botão para baixo do painel BSO | Açao | other