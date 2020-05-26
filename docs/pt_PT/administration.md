# Configuration
**Configurações → Sistema → Configuração**

É aqui que a maioria dos parâmetros de configuração é encontrada.
Embora muitos, a maioria dos parâmetros esteja configurada por padrão.


## Guia Geral

Nesta guia, encontramos informações gerais sobre o Jeedom :

- **Nome do seu Jeedom** : Identifique seu Jeedom, especialmente no mercado. Pode ser reutilizado em cenários ou para identificar um backup.
- **Idioma** : Idioma usado no seu Jeedom.
- **Sistema** : Tipo de hardware no qual o sistema em que o Jeedom é executado está instalado.
- **Gerar traduções** : Gere traduções, cuidado, isso pode tornar seu sistema mais lento. Opção especialmente útil para desenvolvedores.
- **Data e hora** : Escolha o seu fuso horário. Você pode clicar em **Sincronização Time Force** para restaurar a hora errada exibida no canto superior direito.
- **Servidor de tempo opcional** : Indica qual servidor de horário deve ser usado se você clicar em **Sincronização Time Force** (ser reservado para especialistas).
- **Ignorar verificar o tempo** : diz ao Jeedom para não verificar se o tempo é consistente entre si e o sistema em que está sendo executado. Pode ser útil, por exemplo, se você não conectar o Jeedom à Internet e ele não tiver uma bateria PSTN no equipamento usado.
- **Sistema** : Indica o tipo de hardware no qual o Jeedom está instalado.
- **Key instalação** : Chave de hardware do seu Jeedom no mercado. Se o seu Jeedom não aparecer na lista do seu Jeedom no mercado, é recomendável clicar no botão **Restabelecer**.
- **Última data conhecida** : Data registrada pela Jeedom, usada após uma reinicialização para sistemas sem bateria PSTN.

## Guia Interface

Nesta guia, você encontrará os parâmetros de personalização de exibição.

### Temas

- **Área de trabalho clara e escura** : Permite escolher um tema claro e escuro para a área de trabalho.
- **Celular claro e escuro** : mesmo que acima para a versão Mobile.
- **Limpar tema de / para** : Permite definir um período de tempo durante o qual o tema claro escolhido anteriormente será usado. No entanto, marque a opção **Alternar tema com base no tempo**.
- **Sensor de brilho**   : Somente interface móvel, requer ativação *sensor extra genérico* no chrome, página chrome://flags.
- **Ocultar imagens de fundo** : Permite ocultar as imagens de plano de fundo encontradas nos cenários, objetos, páginas de interações etc.

### Tuiles

- **Ladrilhos não horizontais** : Restringe a largura dos blocos a cada x pixels.
- **Ladrilhos não verticais** : Restringe a altura dos blocos a cada x pixels.
- **Ladrilhos de margem** : Espaço vertical e horizontal entre blocos, em pixels.

### Personnalisation

- **Ativar** : Ative o uso das opções abaixo.
- **Transparência** : Exibe blocos do painel e algum conteúdo com transparência. 1 : totalmente opaco, 0 : totalmente transparente.
- **Arredondado** : Exibe elementos da interface com ângulos arredondados. 0 : sem arredondamento, 1 : arredondamento máximo.
- **Desativar sombras** : Desativa sombras de blocos no painel, menus e certos elementos da interface.



## Guia Redes

É absolutamente necessário configurar corretamente esta parte importante do Jeedom, caso contrário, muitos plugins podem não funcionar. Existem duas maneiras de acessar o Jeedom : L'**Acesso interno** (da mesma rede local que Jeedom) e l'**Acesso externo** (de outra rede, especialmente da Internet).

> **IMPORTANTE**
>
> Esta parte existe apenas para explicar à Jeedom seu ambiente :
> alterar a porta ou o IP nesta guia não alterará a porta ou o IP da Jeedom, na verdade. Para isso, é necessário conectar-se no SSH e editar o arquivo / etc / network / interfaces para IP e os arquivos etc / apache2 / sites-available / default e etc / apache2 / sites-available / default\_ssl (para HTTPS).
> No entanto, no caso de manuseio inadequado do seu Jeedom, a equipe do Jeedom não pode ser responsabilizada e pode recusar qualquer solicitação de suporte.

- **Acesso interno** : informações para ingressar na Jeedom a partir de equipamentos na mesma rede que a Jeedom (LAN)
    - **OK / NOK** : indica se a configuração interna da rede está correta.
    - **Protocolo** : o protocolo a ser usado, geralmente HTTP.
    - **URL ou endereço IP** : IP Jeedom para entrar.
    - **Porta** : a porta da interface da web Jeedom, geralmente 80.
        Observe que alterar a porta aqui não altera a porta Jeedom real, que permanecerá a mesma.
    - **Complemento** : o fragmento de URL adicional (exemplo : / Jeedom) para acessar o Jeedom.

- **Acesso externo** : informações para acessar o Jeedom de fora da rede local. A ser concluído apenas se você não estiver usando o Jeedom DNS.
    - **OK / NOK** : indica se a configuração de rede externa está correta.
    - **Protocolo** : protocolo usado para acesso ao ar livre.
    - **URL ou endereço IP** : IP externo, se fixo. Caso contrário, forneça o URL apontando para o endereço IP externo da sua rede.
    - **Complemento** : o fragmento de URL adicional (exemplo : / Jeedom) para acessar o Jeedom.

- **Proxy para o mercado** : ativação de proxy.
    - Marque a caixa ativar proxy.
    - **Endereço de proxy** : Digite o endereço do proxy,
    - **Porta proxy** : Digite a porta do proxy,
    - **Entrar** : Digite o login do proxy,
    - **Senha** : Digite a senha.

> **Dica**
>
> Se você estiver em HTTPS, a porta é 443 (padrão) e em HTTP, a porta é 80 (padrão)). Para usar HTTPS de fora, um plug-in letsencrypt agora está disponível no mercado.

> **Dica**
>
> Para descobrir se você precisa definir um valor no campo **Complemento**, veja, quando você faz login no Jeedom no seu navegador da Internet, se precisar adicionar / Jeedom (ou qualquer outra coisa) após o IP.

- **Gerenciamento avançado** : Esta parte pode não aparecer, dependendo da compatibilidade com o seu hardware.
    Você encontrará a lista de suas interfaces de rede. Você pode dizer ao Jeedom para não monitorar a rede clicando em **desativar o gerenciamento de rede Jeedom** (verifique se o Jeedom não está conectado a nenhuma rede). Você também pode especificar o intervalo de ip local no formato 192.168.1.* (para ser usado apenas em instalações do tipo docker).
- **Mercado de proxy** : permite acesso remoto ao seu Jeedom sem a necessidade de um DNS, um IP fixo ou abrir as portas da sua caixa da Internet.
    - **Usando o DNS Jeedom** : ativa o DNS Jeedom (observe que isso requer pelo menos um service pack).
    - **Status de DNS** : Status HTTP DNS.
    - **Gestão** : permite parar e reiniciar o serviço DNS Jeedom.

> **IMPORTANTE**
>
> Se você não conseguir que o DNS Jeedom funcione, verifique a configuração do firewall e do filtro dos pais da sua caixa da Internet (na caixa viva você precisa, por exemplo, do firewall em nível médio).
- **Duração das sessões (hora)** : vida útil das sessões PHP, não é recomendável tocar nesse parâmetro.

## Guia Logs

### Timeline

- **O número máximo de eventos** : Define o número máximo de eventos a serem exibidos na linha do tempo.
- **Excluir todos os eventos** : Esvaziar a linha do tempo de todos os seus eventos registrados.

### Messages

- **Adicione uma mensagem para cada erro nos logs** : se um plug-in ou Jeedom grava uma mensagem de erro em um log, o Jeedom adiciona automaticamente uma mensagem no centro de mensagens (pelo menos você tem certeza de que não a perdeu).
- **Ação na mensagem** : Permite que você execute uma ação ao adicionar uma mensagem ao centro de mensagens. Você tem 2 tags para essas ações :
        - #subject# : mensagem em questão.
        - #plugin# : plugin que acionou a mensagem.

### Alertes

- **Adicione uma mensagem a cada tempo limite** : Adicione uma mensagem no centro de mensagens se um dispositivo cair **Tempo limite**.
- **Ordem de tempo limite** : Comando de tipo **Mensagem** para ser usado se um equipamento estiver em **Tempo limite**.
- **Adicione uma mensagem a cada bateria em Aviso** : Adicione uma mensagem no centro de mensagens se um dispositivo estiver com o nível de bateria **Aviso**.
- **Comando da bateria em Aviso** : Comando de tipo **Mensagem** a ser usado se o equipamento estiver com a bateria **Aviso**.
- **Adicione uma mensagem a cada bateria em perigo** : Adicione uma mensagem no centro de mensagens se um dispositivo estiver com o nível de bateria **Perigo**.
- **Comando na bateria em perigo** : Comando de tipo **Mensagem** a ser usado se o equipamento estiver com a bateria **Perigo**.
- **Adicione uma mensagem a cada aviso** : Adicione uma mensagem no centro de mensagens se um pedido entrar em alerta **Aviso**.
- **Comando no aviso** : Comando de tipo **Mensagem** usar se um pedido entrar em alerta **Aviso**.
- **Adicione uma mensagem a cada Perigo** : Adicione uma mensagem no centro de mensagens se um pedido entrar em alerta **Perigo**.
- **Comando sobre Perigo** : Comando de tipo **Mensagem** usar se um pedido entrar em alerta **Perigo**.

### Logs

- **Log Motor** : Permite alterar o mecanismo de log para, por exemplo, enviá-los para um daemon syslog (d).
- **Logs de formato** : Formato de log a ser usado (Cuidado : isso não afeta os logs do daemon).
- **O número máximo de linhas em um arquivo de log** : Define o número máximo de linhas em um arquivo de log. Recomenda-se não tocar nesse valor, pois um valor muito grande pode preencher o sistema de arquivos e / ou tornar o Jeedom incapaz de exibir o log.
- **Nível de log padrão** : Quando você seleciona "Padrão", para o nível de um log no Jeedom, isso será usado.

Abaixo, você encontrará uma tabela para gerenciar com precisão o nível de log de elementos essenciais do Jeedom, bem como o de plugins.

## Guia Pedidos

Muitos pedidos podem ser registrados. Assim, em Análise → Histórico, você obtém gráficos representando seu uso. Essa guia permite definir parâmetros globais para o log de comandos.

### Historique

- **Mostrar estatísticas sobre os widgets** : Ver estatísticas sobre widgets. O widget deve ser compatível, como é o caso da maioria. O comando também deve ser do tipo numérico.
- **Período de cálculo para min, max, média (em horas)** : Período de cálculo estatístico (24h por padrão). Não é possível demorar menos de uma hora.
- **Período de cálculo da tendência (em horas)** : Período de cálculo de tendência (2h por padrão). Não é possível demorar menos de uma hora.
- **Atraso antes do arquivamento (em horas)** : Indica o atraso antes do Jeedom arquivar dados (24h por padrão). Isso significa que os dados históricos devem ter mais de 24 horas para serem arquivados (como lembrete, o arquivamento terá uma média ou captará o máximo ou o mínimo dos dados durante um período que corresponde ao tamanho dos pacotes).
- **Arquivar por pacote a partir de (em horas)** : Este parâmetro fornece o tamanho do pacote (1 hora por padrão). Isso significa, por exemplo, que o Jeedom levará períodos de 1 hora, em média, e armazenará o novo valor calculado excluindo os valores médios.
- **Limite de cálculo de tendência baixo** : Este valor indica o valor a partir do qual Jeedom indica que a tendência é descendente. Deve ser negativo (padrão -0,1).
- **Cálculo do limiar acima da tendência** : A mesma coisa para a ascensão.
- **Gráficos padrão de exibição Período** : Período usado por padrão quando você deseja exibir o histórico de um pedido. Quanto menor o período, mais rápido o Jeedom exibirá o gráfico solicitado.

> **NOTA**
>
> O primeiro parâmetro **Mostrar estatísticas sobre os widgets** é possível, mas desativado por padrão, pois aumenta significativamente o tempo de exibição do painel. Se você ativar esta opção, por padrão, o Jeedom confiará nos dados das últimas 24 horas para calcular essas estatísticas.
> O método de cálculo de tendência é baseado no cálculo de mínimos quadrados (consulte [aqui](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s) para o detalhe).

### Push

- **URL esforço global** : permite adicionar um URL para chamar no caso de uma atualização do pedido. Você pode usar as seguintes tags :
**\#value\#** para o valor do pedido, **\#cmd\_name\#** para o nome do comando,
**\#cmd\_id\#** para o identificador exclusivo do pedido,
**\#humanname\#** para o nome completo do pedido (ex : \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#),
**\#eq_name\#** para o nome do equipamento

## Guia Resumos

Adicionar resumos de objetos. Essas informações são exibidas no canto superior direito da barra de menus do Jeedom ou ao lado de objetos :

- **Chave** : Chave para o resumo, especialmente para não tocar.
- **Nome** : Nome do resumo.
- **Cálculo** : Método de cálculo, pode ser do tipo :
    - **Soma** : somar os diferentes valores,
    - **Média** : valores médios,
    - **Texto** : exibir o valor literalmente (especialmente para aqueles do tipo string).
- **ícone** : Ícone Resumo.
- **Unidade** : Unidade de resumo.
- **Método de contagem** : Se você contar dados binários, terá que colocar esse valor em binário, por exemplo, se contar o número de luzes acesas, mas apenas o valor do dimmer (0 a 100), precisará colocar binários, como o que Jeedom considerou se o valor for maior que 1, a lâmpada estará acesa.
- **Mostrar se o valor é 0** : Marque esta caixa para exibir o valor, mesmo quando for 0.
- **Link para um virtual** : Lança a criação de pedidos virtuais que têm como valor aqueles do resumo.
- **Excluir resumo** : O último botão, na extrema direita, exclui o resumo da linha.

## Guia Equipamento

- **Falha Contagem off equipamentos** : Número de falhas de comunicação com o equipamento antes da desativação do equipamento (uma mensagem avisará se isso acontecer).
- **Limiares da bateria** : Permite gerenciar os limites de alerta global nas pilhas.

## Guia Relatórios

Configurar a geração e gerenciamento de relatórios

- **Tempo limite após a geração da página (em ms)** : Tempo de espera após carregar o relatório para tirar a "foto", para alterar se o seu relatório estiver incompleto, por exemplo.
- **Limpar relatórios mais antigos de (dias)** : Define o número de dias antes de excluir um relatório (os relatórios ocupam um pouco de espaço, portanto, tenha cuidado para não colocar muita conservação).

## Guia Links

Configurar gráficos de link. Esses links permitem ver, na forma de gráfico, as relações entre objetos, equipamentos, objetos, etc.

- **Profundidade para cenários** : Permite definir, ao exibir um gráfico de links de um cenário, o número máximo de elementos a serem exibidos (quanto mais elementos houver, mais lento será o gráfico para gerar e mais difícil será a leitura).
- **Profundidade para objetos** : O mesmo para objetos.
- **Profundidade de equipamentos** : O mesmo para o equipamento.
- **Profundidade para encomendas** : Mesmo para pedidos.
- **Profundidade para variáveis** : O mesmo para variáveis.
- **Prerender parâmetro** : Permite que você atue no layout do gráfico.
- **Parâmetro renderizar** : Same.

## Guia Interações

Essa guia permite definir parâmetros globais relativos às interações que você encontrará em Ferramentas → Interações.

> **Dica**
>
> Para ativar o log de interação, vá para a guia Configurações → Sistema → Configuração : Logs e verifique **Depurar** na lista inferior. Atenção : os logs serão muito detalhados !

### Geral

Aqui você tem três parâmetros :

- **Sensibilidade** : existem 4 níveis de correspondência (a sensibilidade vai de 1 (corresponde exatamente) a 99)
    -   por 1 palavra : nível de correspondência para interações com uma única palavra.
    -   2 palavras : o nível de correspondência para interações de duas palavras.
    -   3 palavras : o nível de correspondência para interações de três palavras.
    -   mais de 3 palavras : nível de correspondência para interações com mais de três palavras.
- **Não responda se a interação não está incluído** : por padrão, o Jeedom responde "eu não entendi" se nenhuma interação corresponde. É possível desativar esta operação para que o Jeedom não responda. Marque a caixa para desativar a resposta.
- **Regex de exclusão geral para interações** : permite definir um regexp que, se corresponder a uma interação, excluirá automaticamente esta frase da geração (reservada a especialistas). Para mais informações, consulte as explicações no capítulo **Exclusão regexp** documentação sobre interações.

### Interação automática, contextual e aviso

-   O **interações automáticas** permitir que o Jeedom tente entender uma solicitação de interação, mesmo que não haja nenhuma definida. Ele procurará um nome de objeto e / ou equipamento e / ou ordem para tentar responder da melhor forma possível.

-   O **interações contextuais** permitem encadear várias solicitações sem repetir tudo, por exemplo :
    - *Jeedom mantendo o contexto :*
        - *Você* : Quanto ele está na sala ?
        - *Jeedom* : Temperatura 25.2 ° C
        - *Você* : e na sala de estar ?
        - *Jeedom* : Temperatura 27.2 ° C
    - *Faça duas perguntas em uma :*
        - *Você* : Como é no quarto e na sala de estar ?
        - *Jeedom* : Temperatura 23.6 ° C, Temperatura 27.2 ° C
-   Interações de tipo **Avise-me** permita que a Jeedom avise se um pedido excede / cai ou vale algum valor.
    - *Você* : Notifique-me se a temperatura da sala exceder 25 ° C ?
    - *Jeedom* : Ok (*Assim que a temperatura da sala exceder 25 ° C, Jeedom dirá, uma vez*)

> **NOTA**
>
> Por padrão, o Jeedom responderá pelo mesmo canal que você usou para pedir para notificá-lo. Se não encontrar um, utilizará o comando padrão especificado nesta guia : **Ordem de devolução padrão**.

Aqui estão as diferentes opções disponíveis :

- **Activar interacções automatizados** : Marque para ativar interações automáticas.
- **Ativar respostas contextuais** : Marque para ativar interações contextuais.
- **Prioridade resposta contextual se a sentença começa** : Se a frase começar com a palavra que você preenche aqui, o Jeedom priorizará uma resposta contextual (você pode colocar várias palavras separadas por **;** ).
- **Cortar uma interacção 2 se contiver** : O mesmo vale para a divisão de uma interação que contém várias perguntas. Aqui você fornece as palavras que separam as diferentes perguntas.
- **Ativar interações "Notifique-me""** : Marque para ativar interações de tipo **Avise-me**.
- **Resposta "Diga-me" se a frase começar com** : Se a frase começar com esta (s) palavra (s), o Jeedom procurará fazer uma interação do tipo **Avise-me** (você pode colocar várias palavras separadas por **;** ).
- **Ordem de devolução padrão** : Comando de retorno padrão para interação de tipo **Avise-me** (usado, em particular, se você programou o alerta pela interface móvel)
- **Sinônimo de objetos** : Lista de sinônimos para objetos (ex : rdc|térreo|subterrâneo|banheiro baixo|Casa de banho).
- **Sinônimo de equipamento** : Lista de sinônimos para equipment.
- **Sinônimo de pedidos** : Lista de sinônimos para comandos.
- **Sinônimo de resumos** : Lista de sinônimos para resumos.
- **Sinônimo máximo controle deslizante** : Sinônimo para colocar um comando do tipo slider ao máximo (ex abre para abre o obturador do quarto ⇒ obturador do quarto a 100%).
- **Sinônimo controle deslizante Mínimo** : Sinônimo para colocar um comando do tipo slider no mínimo (ex fecha para fechar a persiana do quarto ⇒ persiana da sala a 0%).

## Guia Segurança

### LDAP

- **Habilitar a autenticação LDAP** : habilitar a autenticação por meio de um AD (LDAP).
- **Anfitrião** : servidor que hospeda o AD.
- **Domínio** : domínio do seu anúncio.
- **DN base** : Base DN do seu AD.
- **Nome de Usuário** : nome de usuário para o Jeedom para entrar no AD.
- **Senha** : senha para o Jeedom se conectar ao AD.
- **Usuário pesquisar Campos** : campos de pesquisa de login do usuário. Geralmente uid para LDAP, SamAccountName para Windows AD.
- **Filtro de administradores (opcional)** : os administradores filtram no AD (para gerenciamento de grupo, por exemplo)
- **Filtro de usuário (opcional)** : filtro de usuário no AD (para gerenciamento de grupo, por exemplo)
- **Filtro de usuário limitado (opcional)** : filtrar usuários limitados no AD (para gerenciamento de grupos, por exemplo)
- **Permitir REMOTE\_USER** : Ative REMOTE\_USER (usado no SSO, por exemplo).

### Connexion

- **Número de falhas tolerada** : define o número de tentativas sucessivas permitidas antes de banir o IP
- **Tempo máximo entre falhas (em segundos)** : tempo máximo para 2 tentativas serem consideradas sucessivas
- **Duração do banimento (em segundos), -1 para o infinito** : Tempo de proibição de IP
- **IP "branco"** : lista de IPs que nunca podem ser banidos
- **Remover IPs banidos** : Limpe a lista de IPs atualmente banidos

A lista de IPs banidos está na parte inferior desta página. Você encontrará o IP, a data da proibição e a data de término programada da proibição.

## Guia Atualizar / Mercado

### Atualização do Jeedom

- **Fonte atualização** : Escolha a fonte de atualização principal do Jeedom.
- **Core Version** : Versão principal a recuperar.
- **Verificar atualizações automaticamente** : Indique se deve verificar automaticamente se há novas atualizações (tenha cuidado para evitar sobrecarregar o mercado, o tempo de verificação pode mudar).

### Depósitos

Os repositórios são espaços de armazenamento (e serviço) para poder mover backups, recuperar plugins, recuperar o núcleo do Jeedom, etc.

### Fichier

Depósito usado para ativar o envio de plugins por arquivos.

#### Github

Depósito usado para conectar o Jeedom ao Github.

- **Token** : Token para acesso ao depósito privado.
- **Usuário ou organização do repositório principal da Jeedom** : Nome de usuário ou organização no github para o núcleo.
- **Nome do repositório para o núcleo Jeedom** : Nome do repositório para core.
- **Indústria do núcleo Jeedom** : Ramificação do repositório principal.

#### Market

Depósito usado para conectar o Jeedom ao mercado, é altamente recomendável usar esse depósito. Atenção : qualquer solicitação de suporte poderá ser recusada se você usar um depósito diferente deste.

- **Morada** : Morada du Mercado.(https://www.Jeedom.com/market).
- **Nome de Usuário** : Seu nome de usuário no mercado.
- **Senha** : Sua senha do Market.
- **Nome da [nuvem de backup]** : Nome do seu backup na nuvem (a atenção deve ser exclusiva para cada Jeedom em risco de colidir entre eles).
- **Senha da [nuvem de backup]** : Senha de backup na nuvem. IMPORTANTE, você não deve perdê-lo, não há como recuperá-lo. Sem ele, você não poderá mais restaurar seu Jeedom.
- **[Nuvem de backup] Backup completo de frequência** : Frequência de backup completo na nuvem. Um backup completo é maior que um incremental (que envia apenas as diferenças). Recomenda-se fazer 1 por mês.

#### Samba

Depósito que permite enviar automaticamente um backup do Jeedom em um compartilhamento Samba (ex : NAS Synology).

- **\ [Backup \] IP** : IP do servidor Samba.
- **\ [Backup \] Usuário** : Nome de usuário para conexão (conexões anônimas não são possíveis). O usuário deve ter direitos de leitura e gravação no diretório de destino.
- **\ [Backup \] Senha** : Senha do usuário.
- **\ [Backup \] Compartilhamento** : Caminho para o compartilhamento (tenha cuidado para parar no nível de compartilhamento).
- **Caminho \ [Backup \]** : Caminho no compartilhamento (para colocar em relativo), isso deve existir.

> **NOTA**
>
> Se o caminho para a pasta de backup do samba for :
> \\\\ 192.168.0.1 \\ Backups \\ Domótica \\ Jeedom Then IP = 192.168.0.1, Compartilhando = //192.168.0.1 / Backups, Caminho = Domótica / Jeedom

> **NOTA**
>
> Ao validar o compartilhamento Samba, como descrito acima, uma nova forma de backup aparece na seção Configurações → Sistema → Backups do Jeedom. Ao ativá-lo, o Jeedom o enviará automaticamente durante o próximo backup. Um teste é possível executando um backup manual.

> **IMPORTANTE**
>
> Pode ser necessário instalar o pacote smbclient para o repositório funcionar.

> **IMPORTANTE**
>
> O protocolo Samba possui várias versões, a v1 está comprometida em termos de segurança e, em alguns NAS, você pode forçar o cliente a usar a v2 ou v3 para conectar-se. Então, se você tiver um erro *falha na negociação do protocolo: NT_STATUS_INVAID_NETWORK_RESPONSE* existe uma boa chance de que, no lado do NAS, a restrição esteja em vigor. Você deve modificar o arquivo / etc / samba / smb no seu Jeedom OS.conf e adicione essas duas linhas a ele :
> protocolo máximo do cliente = SMB3
> protocolo min de cliente = SMB2
> O smbclient do lado do Jeedom usará v2 em que v3 e colocando SMB3 em ambos apenas SMB3. Cabe a você adaptar de acordo com as restrições no servidor NAS ou outro servidor Samba

> **IMPORTANTE**
>
> O Jeedom deve ser o único a escrever nesta pasta e deve estar vazio por padrão (ou seja, antes da configuração e do envio do primeiro backup, a pasta não deve conter nenhum arquivo ou pasta).

#### URL

- **URL principal do Jeedom**
- **URL da versão principal do Jeedom**

## Guia Cache

Permite monitorar e agir no cache Jeedom :

- **Estatística** : Número de objetos atualmente armazenados em cache.
- **Cache de limpo** : Forçar a exclusão de objetos que não são mais úteis. Jeedom faz isso automaticamente todas as noites.
- **Vazio todos os dados de cache** : Esvazie a tampa completamente.
    Observe que isso pode causar perda de dados !
- **Limpe o cache do widget** : Limpe o cache dedicado aos widgets.
- **Desativar cache do widget** : Marque a caixa para desativar os caches do widget.
- **Tempo de pausa para o longo polling** : Frequência com que o Jeedom verifica se há eventos pendentes para os clientes (interface da web, aplicativo móvel etc.)). Quanto menor o tempo, mais rápida será a atualização da interface, no entanto, ela usa mais recursos e, portanto, pode reduzir a velocidade do Jeedom.

## Guia API

Aqui você encontra a lista das diferentes chaves de API disponíveis no seu Jeedom. O núcleo possui duas chaves de API :

-   um general : tanto quanto possível, evite usá-lo,
-   e outro para profissionais : usado para gerenciamento de frota. Pode estar vazio.
-   Então você encontrará uma chave de API por plug-in que precisa dela.

Para cada chave de plug-in de API, bem como para APIs HTTP, JsonRPC e TTS, é possível definir seu escopo :

- **Desativado** : A chave da API não pode ser usada,
- **Branco IP** : apenas uma lista de IPs é autorizada (consulte Configurações → Sistema → Configuração : Redes),
- **Localhost** : somente solicitações do sistema no qual o Jeedom está instalado são permitidas,
- **Ativado** : sem restrições, qualquer sistema com acesso ao seu Jeedom poderá acessar esta API.

## Onglet &gt;\_OS/DB

> **IMPORTANTE**
>
> Essa guia está reservada para especialistas.
> Se você modificar o Jeedom com uma dessas duas soluções, o suporte poderá se recusar a ajudá-lo.

- **Geral** :
    - **Verificação geral** : Vamos lançar o teste de consistência Jeedom.
- **&gt;\_System** :
    - **Administração** : Fornece acesso a uma interface de administração do sistema. É um tipo de console shell no qual você pode iniciar os comandos mais úteis, em particular para obter informações sobre o sistema.
    - Restabelecimento de direitos : Permite reaplicar os direitos corretos nos diretórios e arquivos do Jeedom Core.
- **Editor de arquivo** : Permite o acesso a vários arquivos do sistema operacional e para editar ou excluir ou criá-los.
- **Banco de dados** :
    - **Administração** : Permite acesso ao banco de dados Jeedom. Você pode então iniciar comandos no campo superior.
    - **Verificação** : Permite iniciar uma verificação no banco de dados Jeedom e corrigir erros, se necessário
    - **Limpeza** : Inicia uma verificação de banco de dados e limpa todas as entradas não utilizadas.
    - **Usuário** : Nome de usuário usado por Jeedom no banco de dados,
    - **Senha** : senha para acessar o banco de dados usado pelo Jeedom.
