# Tipos de equipamento
**Ferramentas → Tipos de equipamento**

Os sensores e atuadores no Jeedom são gerenciados por plug-ins, que criam equipamentos com comandos *Informação* (sensor) ou *Ação* (atuador do). Isso torna possível disparar ações com base na mudança de certos sensores, como acender uma luz na detecção de movimento. Mas o Jeedom Core e plug-ins como *Móvel*, *Homebridge*, *Casa inteligente do Google*, *Alexa Casa Inteligente* etc, não sei o que é este equipamento : Uma tomada, uma luz, uma veneziana, etc.

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

Uma vez que um item do equipamento é posicionado na posição correta *Tipo*, clicando nele você acessa a lista de seus pedidos, colorida de forma diferente se for um *Informação* (Azul) ou um *Ação* (Orange).

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

Você pode acionar um cenário a partir de sensores. Por exemplo, se você tiver detectores de movimento em casa, pode criar um cenário de alarme com cada detector acionando : `#[Salão][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. Nesse cenário, você precisará de todos os detectores de movimento e, se adicionar um, terá que adicioná-lo aos gatilhos. Lógica.

Os tipos genéricos permitem que você use um único gatilho : `#genericType(PRESENCE)# == 1'. Aqui, nenhum objeto é indicado, então o menor movimento em toda a casa irá desencadear o cenário. Se você adicionar um novo detector na casa, não há necessidade de editar o (s) cenário (s)).

Aqui, um gatilho para acender uma luz na sala de estar : `#genericType(LIGHT_STATE,#[Salão]#)# > 0`

#### Expression

Se, em um cenário, você quiser saber se uma luz está acesa na sala de estar, você pode fazer :

SE `#[Salão][Lumiere Canapé][Estado]# == 1 OU #[Salão][Lumiere Salon][Estado]# == 1 OU #[Salão][Lumiere Angle][Estado]# == 1'

Ou mais simplesmente : IF `genericType (LIGHT_STATE,#[Salão]#) > 0` ou se uma ou mais luzes estiverem acesas na sala de estar.

Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


#### Action

Se você deseja acender todas as luzes da sala de estar, pode criar uma ação leve:

```
#[Salão][Lumiere Canapé][Nós]#
#[Salão][Lumiere Salon][Nós]#
#[Salão][Lumiere Angle][Nós]#
```

Ou mais simplesmente, crie uma ação `genericType` com` LIGHT_ON` no `Salon`. Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


## Lista de tipos de núcleo genérico

> **Dica**
>
> - Você pode encontrar essa lista diretamente no Jeedom, nesta mesma página, com o botão **Lista** canto superior direito.

| **Outro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPORIZADOR | Cronômetro de estado | Informação | numeric
| TIMER_STATE | Status do temporizador (pausa ou não) | Informação | binário, numérico
| DEFINIR TEMPORIZADOR | Temporizador | Ação | slider
| TIMER_PAUSE | Pausar cronômetro | Ação | other
| TIMER_RESUME | Resumo do cronômetro | Ação | other

| **Bateria (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERIA | Bateria | Informação | numeric
| BATTERY_CHARGING | Carregamento de bateria | Informação | binary

| **Câmera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Url da câmera | Informação | string
| CÂMERA_RECORD_STATE | Status de gravação da câmera | Informação | binary
| CÂMERA_UP | Movimento da câmera para cima | Ação | other
| CÂMERA_DOWN | Movimento da câmera para baixo | Ação | other
| CÂMERA_ESQUERDA | Movimento da câmera para a esquerda | Ação | other
| CÂMERA_RIGHT | Movimento da câmera para a direita | Ação | other
| CÂMERA_ZOOM | Zoom da câmera para frente | Ação | other
| CÂMERA_DEZOOM | Zoom da câmera para trás | Ação | other
| CÂMERA_STOP | Parar câmera | Ação | other
| CÂMERA_PRESET | Predefinição da câmera | Ação | other
| CÂMERA_RECORD | Gravação de câmera | Ação |
| CÂMERA_TAKE | Câmera instantânea | Ação |

| **Aquecimento (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status de aquecimento do fio piloto | Informação | binary
| AQUECIMENTO_ON | Botão LIGADO de aquecimento do fio piloto | Ação | other
| AQUECIMENTO_OFF | Botão de aquecimento do fio piloto DESLIGADO | Ação | other
| HEATING_OTHER | Botão do fio piloto de aquecimento | Ação | other

| **Eletricidade (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Poder | Energia elétrica | Informação | numeric
| CONSUMO | Consumo de energia | Informação | numeric
| TENSÃO | Tensão | Informação | numeric
| REINÍCIO | Reiniciar | Ação | other

| **Ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | TEMPERATURA | Informação | numeric
| QUALIDADE DO AR | Qualidade do ar | Informação | numeric
| BRILHO | Brilho | Informação | numeric
| PRESENÇA | PRESENÇA | Informação | binary
| FUMAÇA | Detecção de fumaça | Informação | binary
| UMIDADE | Umidade | Informação | numeric
| Ultravioleta | Ultravioleta | Informação | numeric
| CO2 | CO2 (ppm) | Informação | numeric
| CO | CO (ppm) | Informação | numeric
| BARULHO | Som (dB) | Informação | numeric
| PRESSÃO | Pressão | Informação | numeric
| VAZAMENTO DE ÁGUA | Vazamento de água | Informação |
| FILTER_CLEAN_STATE | Estado do filtro | Informação | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDADE | Profundidade | Informação | numeric
| DISTÂNCIA | DISTÂNCIA | Informação | numeric
| BOTÃO | Botão | Informação | binário, numérico
| GENERIC_INFO |  Genérico | Informação |
| AÇÃO_GENÉRICA |  Genérico | Ação | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado claro | Informação | binário, numérico
| LUZ_BRILHO | Brilho da luz | Informação | numeric
| COR CLARA | Cor clara | Informação | string
| LIGHT_STATE_BOOL | Estado Leve (Binário) | Informação | binary
| LIGHT_COLOR_TEMP | Cor da temperatura da luz | Informação | numeric
| LIGHT_TOGGLE | Alternar luz | Ação | other
| LUZES LIGADAS | Botão de luz ligado | Ação | other
| LUZ APAGADA | Botão de luz apagado | Ação | other
| LIGHT_SLIDER | Luz deslizante | Ação | slider
| LIGHT_SET_COLOR | Cor clara | Ação | color
| MODO_LEVE | Modo de luz | Ação | other
| LIGHT_SET_COLOR_TEMP | Cor da temperatura da luz | Ação |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo de status | Informação | string
| MODO_SET_STATE | Modo de mudança | Ação | other

| **Multimídia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Volume | Volume | Informação | numeric
| MEDIA_STATUS | Status | Informação | string
| MEDIA_ALBUM | Álbum | Informação | string
| MEDIA_ARTISTA | Artista | Informação | string
| MEDIA_TITLE | Título | Informação | string
| MEDIA_POWER | Poder | Informação | string
| CANAL | Corrente | Informação | numérico, string
| MEDIA_STATE | Estado | Informação | binary
| SET_VOLUME | Volume | Ação | slider
| SET_CHANNEL | Corrente | Ação | outro controle deslizante
| MEDIA_PAUSE | Quebrar | Ação | other
| MEDIA_RESUME | Leitura | Ação | other
| MEDIA_STOP | Parar | Ação | other
| MEDIA_NEXT | Seguindo | Ação | other
| MEDIA_PREVIOUS | Anterior | Ação | other
| MEDIA_ON | Nós | Ação | other
| MÍDIA_DESLIGADA | Desligado | Ação | other
| MEDIA_MUTE | Mudo | Ação | other
| MEDIA_UNMUTE | Sem mudo | Ação | other

| **Tempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPO_TEMPERATURA | Temperatura do tempo | Informação | numeric
| TEMPO_TEMPERATURA_MAX_2 | Condição climática d + 1 max d + 2 | Informação | numeric
| VELOCIDADE DO VENTO | Velocidade do vento) | Informação | numeric
| CHUVA_TOTAL | Chuva (acumulação) | Informação | numeric
| RAIN_CURRENT | Chuva (mm / h) | Informação | numeric
| TEMPO_CONDIÇÃO_ID_4 | Condição climática (id) d + 4 | Informação | numeric
| TEMPO_CONDIÇÃO_4 | Condição climática d + 4 | Informação | string
| TEMPO_TEMPERATURA_MAX_4 | Temperatura máxima do tempo d + 4 | Informação | numeric
| TEMPO_TEMPERATURA_MIN_4 | Temperatura do tempo min d + 4 | Informação | numeric
| TEMPO_CONDIÇÃO_ID_3 | Condição climática (id) d + 3 | Informação | numeric
| TEMPO_CONDIÇÃO_3 | Condição climática d + 3 | Informação | string
| TEMPO_TEMPERATURA_MAX_3 | Temperatura máxima do tempo d + 3 | Informação | numeric
| TEMPO_TEMPERATURA_MIN_3 | Temperatura do tempo min d + 3 | Informação | numeric
| TEMPO_CONDIÇÃO_ID_2 | Condição climática (id) d + 2 | Informação | numeric
| TEMPO_CONDIÇÃO_2 | Condição climática d + 2 | Informação | string
| TEMPO_TEMPERATURA_MIN_2 | Temperatura do tempo min d + 2 | Informação | numeric
| TEMPO_UMIDADE | Umidade do tempo | Informação | numeric
| TEMPO_CONDIÇÃO_ID_1 | Condição climática (id) d + 1 | Informação | numeric
| TEMPO_CONDIÇÃO_1 | Condição climática d + 1 | Informação | string
| TEMPO_TEMPERATURA_MAX_1 | Temperatura máxima do tempo d + 1 | Informação | numeric
| TEMPO_TEMPERATURA_MIN_1 | Temperatura do tempo min d + 1 | Informação | numeric
| TEMPO_CONDIÇÃO_ID | Condições meteorológicas (id) | Informação | numeric
| TEMPO_CONDIÇÃO | Condição do tempo | Informação | string
| TEMPO_TEMPERATURA_MAX | Temperatura máxima do tempo | Informação | numeric
| TEMPO_TEMPERATURA_MIN | Temperatura mínima do tempo | Informação | numeric
| TEMPO_NASCER DO SOL | Clima de pôr do sol | Informação | numeric
| TEMPO_PÔR DO SOL | Clima do nascer do sol | Informação | numeric
| TEMPO_VENTO_DIRECTION | Tempo de direção do vento | Informação | numeric
| TEMPO_VENTO_VELOCIDADE | Tempo de velocidade do vento | Informação | numeric
| TEMPO_PRESSURE | Pressão do Tempo | Informação | numeric
| DIREÇÃO DO VENTO | Direção do vento) | Informação | numeric

| **Abrindo (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Bloqueio de estado | Informação | binary
| BARRIER_STATE | Estado do portal (abertura) | Informação | binary
| GARAGE_STATE | Estado de garagem (abertura) | Informação | binary
| ABERTURA | Porta | Informação | binary
| ABERTURA_JANELA | Janela | Informação | binary
| LOCK_OPEN | Botão de bloqueio aberto | Ação | other
| LOCK_CLOSE | Botão de bloqueio Fechar | Ação | other
| GB_OPEN | Botão de abertura do portão ou garagem | Ação | other
| GB_CLOSE | Botão de fechamento do portão ou garagem | Ação | other
| GB_TOGGLE | Botão de alternância de portão ou garagem | Ação | other

| **Soquete (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGIA_ESTADO | Soquete de estado | Informação | numérico, binário
| ENERGIA_LIGADA | No Soquete de Botão | Ação | other
| ENERGIA_DESLIGADA | Botão de soquete desligado | Ação | other
| ENERGY_SLIDER | Soquete deslizante | Ação |

| **Robô (código: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base estadual | Informação | binary
| DOCA | De volta à base | Ação | other

| **Segurança (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Estado da sereia | Informação | binary
| ALARME_STATE | Status de Alarme | Informação | binário, string
| ALARME_MODE | Modo de Alarme | Informação | string
| ALARME_ENABLE_STATE | Status de alarme ativado | Informação | binary
| ENCHENTE | Enchente | Informação | binary
| SABOTAR | SABOTAR | Informação | binary
| CHOQUE | Choque | Informação | binário, numérico
| SIRENE_DESLIGADA | Botão da sirene desligado | Ação | other
| SIRENE_ON | Botão de sirene ligado | Ação | other
| ALARME_ARMED | Alarme armado | Ação | other
| ALARME_LIBERADO | Alarme liberado | Ação | other
| ALARME_SET_MODE | Modo de Alarme | Ação | other

| **Termostato (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TERMOSTATO_ESTADO | Status do termostato (BINÁRIO) (apenas para termostato de plug-in) | Informação |
| TERMOSTATO_TEMPERATURA | Termostato de temperatura ambiente | Informação | numeric
| TERMOSTATO_SETPOINT | Termostato de ponto de ajuste | Informação | numeric
| TERMOSTATO_MODE | Modo do termostato (apenas para termostato de plug-in) | Informação | string
| TERMOSTATO_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Informação | binary
| TERMOSTATO_TEMPERATURA_EXTERIOR | Termostato de temperatura externa (apenas para termostato de plug-in) | Informação | numeric
| TERMOSTATO_STATE_NAME | Status do termostato (HUMANO) (apenas para termostato de plug-in) | Informação | string
| TERMOSTATO_HUMIDITY | Termostato de umidade ambiente | Informação | numeric
| HUMIDIDADE_SETPOINT | Definir umidade | Informação | slider
| TERMOSTATO_SET_SETPOINT | Termostato de ponto de ajuste | Ação | slider
| TERMOSTATO_SET_MODE | Modo do termostato (apenas para termostato de plug-in) | Ação | other
| TERMOSTATO_SET_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) | Ação | other
| TERMOSTATO_SET_UNLOCK | Desbloquear termostato (apenas para termostato de plug-in) | Ação | other
| HUMIDIDADE_SET_SETPOINT | Definir umidade | Ação | slider

| **Ventilador (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Status da velocidade do ventilador | Informação | numeric
| ROTATION_STATE | Rotação de estado | Informação | numeric
| VELOCIDADE DO VENTILADOR | Velocidade do ventilador | Ação | slider
| ROTAÇÃO | ROTAÇÃO | Ação | slider

| **Painel (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Painel de status | Informação | binário, numérico
| FLAP_BSO_STATE | Painel de status do BSO | Informação | binário, numérico
| FLAP_UP | Botão Pane Up | Ação | other
| FLAP_DOWN | Botão do painel para baixo | Ação | other
| FLAP_STOP | Botão de parada do obturador | Ação |
| FLAP_SLIDER | Painel de botões deslizantes | Ação | slider
| FLAP_BSO_UP | Botão para cima do painel BSO | Ação | other
| FLAP_BSO_DOWN | Botão para baixo do painel BSO | Ação | other
