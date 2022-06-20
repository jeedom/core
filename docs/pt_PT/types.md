# Tipos de equipamento
**Ferramentas → Tipos de equipamento**

Os sensores e atuadores no Jeedom são gerenciados por plug-ins, que criam equipamentos com comandos ** (sensor) ou ** (atuador do). Isso torna possível disparar ações com base na mudança de certos sensores, como acender uma luz na detecção de movimento. Mas o Jeedom Core e plug-ins como *Móvel*, *Homebridge*, *Casa inteligente do Google*, *Alexa Casa Inteligente* etc, não sei o que é este equipamento : Uma tomada, uma luz, uma veneziana, etc.

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

Uma vez que um item do equipamento é posicionado na posição correta *Gentil*, clicando nele você acessa a lista de seus pedidos, colorida de forma diferente se for um ** (Azul) ou um ** (Orange).

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
#[Salão][Lumiere Canapé][]#
#[Salão][Lumiere Salon][]#
#[Salão][Lumiere Angle][]#
`` ``

Ou mais simplesmente, crie uma ação `genericType` com` LIGHT_ON` no `Salon`. Se amanhã você adicionar uma luz em sua sala, não há necessidade de retocar seus cenários !


## Lista de tipos de núcleo genérico

> **Dica**
>
> - Você pode encontrar essa lista diretamente no Jeedom, nesta mesma página, com o botão **Listagem** canto superior direito.

| **Outro (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CRONÔMETRO | Cronômetro de estado |  | numeric
| TIMER_STATE | Status do temporizador (pausa ou não) |  | binário, numérico
| DEFINIR TEMPORIZADOR | Cronômetro |  | slider
| TIMER_PAUSE | Pausar cronômetro |  | other
| TIMER_RESUME | Resumo do cronômetro |  | other

| **Bateria (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATERIA | Bateria |  | numeric
| BATTERY_CHARGING | Carregamento de bateria |  | binary

| **Câmera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Url da câmera |  | string
| CAMERA_RECORD_STATE | Status de gravação da câmera |  | binary
| CAMERA_UP | Movimento da câmera para cima |  | other
| CAMERA_DOWN | Movimento da câmera para baixo |  | other
| CAMERA_LEFT | Movimento da câmera para a esquerda |  | other
| CAMERA_RIGHT | Movimento da câmera para a direita |  | other
| CAMERA_ZOOM | Zoom da câmera para frente |  | other
| CAMERA_DEZOOM | Zoom da câmera para trás |  | other
| CAMERA_STOP | Parar câmera |  | other
| CAMERA_PRESET | Predefinição da câmera |  | other
| CAMERA_RECORD | Gravação de câmera |  |
| CAMERA_TAKE | Câmera instantânea |  |

| **Aquecimento (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status de aquecimento do fio piloto |  | binary
| AQUECIMENTO_ON | Botão LIGADO de aquecimento do fio piloto |  | other
| AQUECIMENTO_OFF | Botão de aquecimento do fio piloto DESLIGADO |  | other
| HEATING_OTHER | Botão do fio piloto de aquecimento |  | other

| **Eletricidade (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POTÊNCIA | Energia elétrica |  | numeric
| CONSUMO | Consumo de energia |  | numeric
| VOLTAGEM | Tensão |  | numeric
| REINÍCIO | Reiniciar |  | other

| **Ambiente (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURA | TEMPERATURA |  | numeric
| QUALIDADE DO AR | Qualidade do ar |  | numeric
| BRILHO | Brilho |  | numeric
| PRESENÇA | PRESENÇA |  | binary
| FUMAÇA | Detecção de fumaça |  | binary
| UMIDADE | Umidade |  | numeric
| UV | UV |  | numeric
| CO2 | CO2 (ppm) |  | numeric
| CO | CO (ppm) |  | numeric
| RUÍDO | Som (dB) |  | numeric
| PRESSÃO | Pressão |  | numeric
| VAZAMENTO DE ÁGUA | Vazamento de água |  |
| FILTER_CLEAN_STATE | Estado do filtro |  | binary

| **Genérico (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| PROFUNDIDADE | Profundidade |  | numeric
| DISTÂNCIA | Distância |  | numeric
| BOTÃO | Botão |  | binário, numérico
| GENERIC_INFO |  Genérico |  |
| GENERIC_ACTION |  Genérico |  | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Estado claro |  | binário, numérico
| LIGHT_BRIGHTNESS | Brilho da luz |  | numeric
| COR CLARA | Cor clara |  | string
| LIGHT_STATE_BOOL | Estado Leve (Binário) |  | binary
| LIGHT_COLOR_TEMP | Cor da temperatura da luz |  | numeric
| LIGHT_TOGGLE | Alternar luz |  | other
| LUZES LIGADAS | Botão de luz ligado |  | other
| LUZ APAGADA | Botão de luz apagado |  | other
| LIGHT_SLIDER | Luz deslizante |  | slider
| LIGHT_SET_COLOR | Cor clara |  | color
| LIGHT_MODE | Modo de luz |  | other
| LIGHT_SET_COLOR_TEMP | Cor da temperatura da luz |  |

| **Modo (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modo de status |  | string
| MODE_SET_STATE | Modo de mudança |  | other

| **Multimídia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume |  | numeric
| MEDIA_STATUS | Status |  | string
| MEDIA_ALBUM | Álbum |  | string
| MEDIA_ARTIST | Artista |  | string
| MEDIA_TITLE | Título |  | string
| MEDIA_POWER | Poder |  | string
| CANAL | Corrente |  | numérico, string
| MEDIA_STATE | Estado |  | binary
| SET_VOLUME | Volume |  | slider
| SET_CHANNEL | Corrente |  | outro controle deslizante
| MEDIA_PAUSE | Pausa |  | other
| MEDIA_RESUME | Leitura |  | other
|  | Pare |  | other
|  |  |  | other
|  | Anterior |  | other
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  | Sem mudo |  | other

| **Tempo (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Temperatura do tempo |  | numeric
|  | Condição climática d + 1 max d + 2 |  | numeric
| VELOCIDADE DO VENTO | Velocidade do vento) |  | numeric
|  | Chuva (acumulação) |  | numeric
|  | Chuva (mm / h) |  | numeric
|  | Condição climática (id) d + 4 |  | numeric
|  | Condição climática d + 4 |  | string
|  | Temperatura máxima do tempo d + 4 |  | numeric
|  | Temperatura do tempo min d + 4 |  | numeric
|  | Condição climática (id) d + 3 |  | numeric
|  | Condição climática d + 3 |  | string
|  | Temperatura máxima do tempo d + 3 |  | numeric
|  | Temperatura do tempo min d + 3 |  | numeric
|  | Condição climática (id) d + 2 |  | numeric
|  | Condição climática d + 2 |  | string
|  | Temperatura do tempo min d + 2 |  | numeric
|  | Umidade do tempo |  | numeric
|  | Condição climática (id) d + 1 |  | numeric
|  | Condição climática d + 1 |  | string
|  | Temperatura máxima do tempo d + 1 |  | numeric
|  | Temperatura do tempo min d + 1 |  | numeric
|  | Condições meteorológicas (id) |  | numeric
|  | Condição do tempo |  | string
| X | Temperatura máxima do tempo |  | numeric
|  | Temperatura mínima do tempo |  | numeric
|  | Clima de pôr do sol |  | numeric
|  | Clima do nascer do sol |  | numeric
|  | Tempo de direção do vento |  | numeric
|  | Tempo de velocidade do vento |  | numeric
|  | Pressão do Tempo |  | numeric
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
|  | Status do termostato (BINÁRIO) (apenas para termostato de plug-in) |  |
|  | Termostato de temperatura ambiente |  | numeric
|  | Termostato de ponto de ajuste |  | numeric
|  | Modo do termostato (apenas para termostato de plug-in) |  | string
| TERMOSTATO_LOCK | Termostato de bloqueio (apenas para termostato de plug-in) |  | binary
|  | Termostato de temperatura externa (apenas para termostato de plug-in) |  | numeric
|  | Status do termostato (HUMANO) (apenas para termostato de plug-in) |  | string
| TERMOSTATO_HUMIDITY | Termostato de umidade ambiente |  | numeric
|  | Definir umidade |  | slider
|  | Termostato de ponto de ajuste |  | slider
|  | Modo do termostato (apenas para termostato de plug-in) |  | other
|  | Termostato de bloqueio (apenas para termostato de plug-in) |  | other
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
