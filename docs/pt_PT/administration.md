É aqui que a maioria dos parâmetros de configuração está localizada.
Embora muitos, eles são pré-configurados por padrão.

A página é acessível por **Administração → Configuração**.

Geral 
=======

Nesta guia, encontramos informações gerais sobre o Jeedom :

-   **Nome do seu Jeedom** : Identifique seu Jeedom,
    especialmente no mercado. Pode ser reutilizado em cenários
    ou identificar um backup.

-   **Sistema** : Tipo de hardware no qual o sistema está instalado onde
    seu Jeedom está girando.

-   **Key instalação** : Chave de hardware do seu Jeedom on
    o mercado. Se o seu Jeedom não aparecer na lista do seu
    Jeedom no mercado, é aconselhável clicar no botão
    **Restabelecer**.

-   **Idioma** : Idioma usado no seu Jeedom.

-   **Gerar traduções** : Gerar traduções,
    tenha cuidado, isso pode tornar seu sistema mais lento. Opção mais útil
    Para desenvolvedores.

-   **Duração das sessões (hora)** : Vida útil das sessões
    PHP, não é recomendável tocar nesse parâmetro.

-   **Data e hora** : Escolha o seu fuso horário. Você pode
    clique em **Sincronização Time Force** restaurar
    um mau momento exibido no canto superior direito.

-   **Servidor de tempo opcional** : Indica qual servidor de horário deve
    ser usado se você clicar **Forçar sincronização de
    a hora**. (ser reservado para especialistas)

-   **Ignorar verificar o tempo** : diz a Jeedom para não
    verifique se o tempo é consistente entre si e o sistema em
    o que acontece. Pode ser útil, por exemplo, se você não estiver conectando
    sem Jeedom para a Internet e que não possui bateria PSTN no
    material usado.

API 
===

Aqui está a lista das diferentes chaves de API disponíveis em
seu Jeedom. O núcleo possui duas chaves de API :

-   um general : tanto quanto possível, evite usá-lo,

-   e outro para profissionais : usado para gerenciamento
    do parque. Pode estar vazio.

-   Então você encontrará uma chave de API por plug-in que precisa dela.

Para cada chave de API de plug-in, bem como para HTTP, JsonRPC e APIs
TTS, você pode definir seu escopo :

-   **Desativado** : A chave da API não pode ser usada,

-   **Branco IP** : apenas uma lista de IPs é autorizada (consulte
    Administração → Configuração → Redes),

-   **Localhost** : somente solicitações do sistema no qual está
    Jeedom instalado são permitidos,

-   **Ativado** : sem restrições, qualquer sistema com acesso
    seu Jeedom poderá acessar esta API.

&gt;\_OS / DB 
===========

Duas partes reservadas para especialistas estão presentes nesta guia.

> **IMPORTANTE**
>
> Atenção : Se você modificar o Jeedom com uma dessas duas soluções,
> o suporte pode se recusar a ajudá-lo.

-   **&gt;\_System** : Permite o acesso a uma interface
    administração do sistema. É um tipo de console shell em
    onde você pode executar os comandos mais úteis, incluindo
    para obter informações sobre o sistema.

-   **Banco de dados** : Permite acesso ao banco de dados
    de Jeedom. Você pode iniciar comandos no campo
    de cima. Dois parâmetros são exibidos abaixo para obter informações :

    -   **Usuário** : Nome de usuário usado por Jeedom em
        o banco de dados,

    -   **Senha** : senha de acesso ao banco de dados
        usado por Jeedom.

Segurança 
========

LDAP 
----

-   **Habilitar a autenticação LDAP** : habilitar a autenticação para
    através de um AD (LDAP)

-   **Anfitrião** : servidor que hospeda o AD

-   **Domínio** : domínio do seu anúncio

-   **DN base** : Base DN do seu AD

-   **Nome de Usuário** : nome de usuário para o Jeedom
    conectar ao AD

-   **Senha** : senha para o Jeedom se conectar ao AD

-   **Usuário pesquisar Campos** : campos de pesquisa de
    login do usuário. Geralmente uid para LDAP, samaccountname para
    Windows AD

-   **Filtro (opcional)** : filtro no AD (para gerenciar
    grupos por exemplo)

-   **Permitir REMOTE\_USER** : Ative REMOTE\_USER (usado no SSO
    Por exemplo)

Logar 
---------

-   **Número de falhas tolerada** : define o número de tentativas
    permitido antes de banir o IP

-   **Tempo máximo entre falhas (em segundos)** : tempo máximo
    para que 2 tentativas sejam consideradas sucessivas

-   **Duração do banimento (em segundos), -1 para o infinito** : tempo de
    Proibição de IP

-   **IP "branco"** : lista de IPs que nunca podem ser banidos

-   **Remover IPs banidos** : Limpe a lista de IPs
    atualmente banido

A lista de IPs banidos está na parte inferior desta página. Você encontrará lá
IP, data da proibição e data final da proibição
agendado.

Redes 
=======

É absolutamente necessário configurar corretamente essa parte importante do
Jeedom, caso contrário, muitos plugins podem não funcionar. Ele
é possível acessar o Jeedom de duas maneiras diferentes : L'**acesso
interne** (da mesma rede local que Jeedom) e l'**acesso
externe** (de outra rede, especialmente da Internet).

> **IMPORTANTE**
>
> Esta parte existe apenas para explicar à Jeedom seu ambiente :
> alterar a porta ou o IP nesta guia não altera o
> Porta Jeedom ou IP, na verdade. Para fazer isso, você deve fazer login
> SSH e edite o arquivo / etc / network / interfaces para o IP e
> etc / apache2 / sites-available / arquivos padrão e
> etc / apache2 / sites-available / default\_ssl (para HTTPS).No entanto, em
> Se o seu Jeedom for maltratado, a equipe do Jeedom não
> responsabilizado e pode recusar qualquer pedido de
> Suporte.

-   **Acesso interno** : informações para ingressar no Jeedom de um
    mesmo equipamento de rede que o Jeedom (LAN)

    -   **OK / NOK** : indica se a configuração de rede interna é
        correcte

    -   **Protocolo** : o protocolo a ser usado, geralmente HTTP

    -   **URL ou endereço IP** : IP Jeedom para entrar

    -   **Porta** : a porta da interface da web Jeedom, geralmente 80.
        Observe que alterar a porta aqui não altera a porta real do
        Jeedom que permanecerá o mesmo

    -   **Complemento** : o fragmento de URL adicional (exemplo
        : / Jeedom) para acessar o Jeedom.

-   **Acesso externo** : informações para alcançar Jeedom de fora
    rede local. A ser concluído apenas se você não estiver usando DNS
    Jeedom

    -   **OK / NOK** : indica se a configuração de rede externa é
        correcte

    -   **Protocolo** : protocolo usado para acesso ao ar livre

    -   **URL ou endereço IP** : IP externo, se for fixo. Caso contrário,
        forneça o URL apontando para o endereço IP externo da sua rede.

    -   **Complemento** : o fragmento de URL adicional (exemplo
        : / Jeedom) para acessar o Jeedom.

> **Dica**
>
> Se você estiver em HTTPS, a porta é 443 (por padrão) e em HTTP o
> porta é 80 (padrão). Para usar HTTPS de fora,
> um plugin letsencrypt já está disponível no mercado.

> **Dica**
>
> Para descobrir se você precisa definir um valor no campo
> **Complemento**, olha, quando você entra no Jeedom em
> seu navegador da Internet, se você precisar adicionar / jeedom (ou outro
> coisa) após o IP.

-   **Gerenciamento avançado** : Esta parte pode não aparecer, em
    dependendo da compatibilidade com o seu hardware. Você encontrará lá
    a lista de suas interfaces de rede. Você pode dizer a Jeedom
    para não monitorar a rede clicando em **desativar o
    gerenciamento de rede por Jeedom** (verifique se o Jeedom não está conectado ao
    sem rede). Você também pode especificar o intervalo de ip local no formato 192.168.1.* (para ser usado apenas em instalações do tipo docker)

-   **Mercado de proxy** : permite acesso remoto ao seu Jeedom sem ter que
    precisa de um DNS, um IP fixo ou para abrir as portas da sua caixa
    Internet

    -   **Usando o DNS Jeedom** : ativa o Jeedom DNS (atenção
        isso requer pelo menos um service pack)

    -   **Status de DNS** : Status HTTP DNS

    -   **Gestão** : permite parar e reiniciar o serviço DNS

> **IMPORTANTE**
>
> Se você não conseguir que o DNS Jeedom funcione, verifique o
> configuração do firewall e filtro dos pais da sua caixa da Internet
> (no livebox você precisa, por exemplo, do firewall em médio).

Cores 
========

A coloração dos widgets é realizada de acordo com a categoria a
qual equipamento pertence. Entre as categorias, encontramos o
aquecimento, segurança, energia, luz, automação, multimídia, outros…

Para cada categoria, podemos diferenciar as cores da versão
versão desktop e móvel. Então podemos mudar :

-   a cor de fundo dos widgets,

-   a cor do comando quando o widget é do tipo gradual (por
    exemplo luzes, persianas, temperaturas).

Ao clicar na cor, uma janela é aberta, permitindo que você escolha
cor. A cruz ao lado da cor retorna ao parâmetro
Por padrão.

Na parte superior da página, você também pode configurar a transparência de
widgets globalmente (esse será o padrão. Ele é
então é possível modificar esse widget de valor por widget). Para não
não coloque transparência, deixe 1.0 .

> **Dica**
>
> Não se esqueça de salvar após qualquer modificação.

Comandos 
=========

Muitos pedidos podem ser registrados. Então em
Análise → Histórico, você obtém gráficos representando suas
use. Essa guia permite definir parâmetros globais para
histórico de pedidos.

Histórico 
----------

-   **Mostrar estatísticas sobre os widgets** : Permite exibir
    estatísticas do widget. O widget deve ser
    compatível, que é o caso da maioria. Também é necessário que o
    comando digital.

-   **Período de cálculo para min, max, média (em horas)** : Período
    cálculo estatístico (24h por padrão). Não é possível
    colocar menos de uma hora.

-   **Período de cálculo da tendência (em horas)** : Período de
    cálculo de tendência (2h por padrão). Não é possível
    colocar menos de uma hora.

-   **Atraso antes do arquivamento (em horas)** : Indica o atraso antes
    O Jeedom não arquiva dados (24h por padrão). Ou seja, o
    dados históricos devem ter mais de 24 horas para serem arquivados
    (como lembrete, o arquivamento será médio ou máximo
    ou o mínimo de dados durante um período que corresponda à
    tamanho do pacote).

-   **Arquivar por pacote a partir de (em horas)** : Este parâmetro fornece
    precisamente o tamanho dos pacotes (1 hora por padrão). Significa por
    Por exemplo, o Jeedom levará períodos de 1 hora, média e
    armazene o novo valor calculado excluindo o
    valores médios.

-   **Limite de cálculo de tendência baixo** : Este valor indica o
    valor a partir do qual Jeedom indica que a tendência é de
    para baixo. Deve ser negativo (padrão -0,1).

-   **Cálculo do limiar acima da tendência** : A mesma coisa para a ascensão.

-   **Gráficos padrão de exibição Período** : Período que é
    usado por padrão quando você deseja exibir o histórico
    de um pedido. Quanto menor o período, mais rápido o Jeedom será
    para exibir o gráfico solicitado.

> **NOTA**
>
> O primeiro parâmetro **Mostrar estatísticas sobre os widgets** est
> possível, mas desativado por padrão porque aumenta significativamente a
> tempo de exibição do painel. Se você ativar esta opção, por exemplo
> Por padrão, o Jeedom conta com dados das últimas 24 horas para
> calcular essas estatísticas. O método de cálculo de tendências é baseado
> no cálculo dos mínimos quadrados (ver
> [aqui](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> para o detalhe).

Empurrão 
----

**URL esforço global** : permite adicionar um URL para chamar em caso de
atualização do pedido. Você pode usar as seguintes tags :
**\#value\#** para o valor do pedido, **\#cmd\_name\#** para o
nome do comando, **\#cmd\_id\#** para o identificador exclusivo do
commande, **\#humanname\#** para o nome completo do pedido (ex :
\#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#), ``#eq_name#`para o nome do equipamento

Cobertura 
=====

Permite monitorar e agir no cache Jeedom :

-   **Estatística** : Número de objetos atualmente armazenados em cache

-   **Cache de limpo** : Forçar a exclusão de objetos que não são
    mais útil. Jeedom faz isso automaticamente todas as noites.

-   **Vazio todos os dados de cache** : Esvazie a tampa completamente.
    Observe que isso pode causar perda de dados !

-   **Tempo de pausa para o longo polling** : Quantas vezes
    A Jeedom verifica se há algum evento pendente para os clientes
    (interface web, aplicação móvel). Quanto menor o tempo, mais
    a interface será atualizada rapidamente, em troca disso
    usa mais recursos e, portanto, pode retardar o Jeedom.

Interações 
============

Essa guia permite definir parâmetros globais relativos a
interações que você encontrará em Ferramentas → Interações.

> **Dica**
>
> Para ativar o log de interação, vá para a guia
> Administração → Configuração → Logs e marque **Depurar** na lista
> de baixo. Atenção : os logs serão muito detalhados !

Geral 
-------

Aqui você tem três parâmetros :

-   **Sensibilidade** : existem 4 níveis de correspondência (sensibilidade
    varia de 1 (corresponde exatamente) a 99)

    -   por 1 palavra : o nível de correspondência para interações em
        uma palavra

    -   2 palavras : o nível de correspondência para interações em
        duas palavras

    -   3 palavras : o nível de correspondência para interações em
        três palavras

    -   mais de 3 palavras : o nível de correspondência para interações
        mais de três palavras

-   **Não responda se a interação não está incluído** : Por padrão
    Jeedom responde "eu não entendi" se nenhuma interação
    não corresponde. É possível desativar esta função para
    que Jeedom não responde nada. Marque a caixa para desativar
    a resposta.

-   **Regex de exclusão geral para interações** : deixa
    definir uma regexp que, se corresponder a uma interação,
    excluirá automaticamente esta frase da geração (reservada
    para especialistas). Para mais informações, consulte as explicações no
    capítulo **Exclusão regexp** documentação sobre
    interações.

Interação automática, contextual e aviso 
-----------------------------------------------------

-   O **interações automáticas** permitir que o Jeedom tente
    entender uma solicitação de interação mesmo se não houver
    de definido. Ele procurará um objeto e / ou nome do equipamento
    e / ou para tentar responder da melhor maneira possível.

-   O **interações contextuais** permitir que você encadeie
    várias solicitações sem repetir tudo, por exemplo :

    -   *Jeedom mantendo o contexto :*

        -   *Você* : Quanto ele está na sala ?

        -   *Jeedom* : Temperatura 25.2 ° C

        -   *Você* : e na sala de estar ?

        -   *Jeedom* : Temperatura 27.2 ° C

    -   *Faça duas perguntas em uma :*

        -   *Você* : Como é no quarto e na sala de estar ?

        -   *Jeedom* : Temperatura 23.6 ° C, Temperatura 27.2 ° C

-   Interações de tipo **Avise-me** vamos perguntar
    Jeedom para notificá-lo se um pedido exceder / descer ou vale um
    certo valor.

    -   *Você* : Notifique-me se a temperatura da sala exceder 25 ° C ?

    -   *Jeedom* : OK (* Assim que a temperatura da sala exceda 25 ° C,
        Jeedom lhe dirá, apenas uma vez*)

> **NOTA**
>
> Por padrão, o Jeedom responderá pelo mesmo canal que você
> costumava pedir-lhe para notificá-lo. Se ele não encontrar um
> não, ele usará o comando padrão especificado neste
> separador : **Ordem de devolução padrão**.

Aqui estão as diferentes opções disponíveis :

-   **Activar interacções automatizados** : Marque para ativar
    interações automáticas.

-   **Ativar respostas contextuais** : Marque para ativar
    interações contextuais.

-   **Prioridade resposta contextual se a sentença começa** : Si
    a frase começa com a palavra que você digita aqui, Jeedom
    priorize uma resposta contextual (você pode colocar
    várias palavras separadas por **;** ).

-   **Cortar uma interacção 2 se contiver** : A mesma coisa para
    o detalhamento de uma interação contendo várias perguntas. Você
    dê aqui as palavras que separam as diferentes perguntas.

-   **Ativar interações "Notifique-me""** : Marque para ativar
    Interações de tipo **Avise-me**.

-   **Resposta "Diga-me" se a frase começar com** : Se o
    Se a frase começar com essa (s) palavra (s), o Jeedom tentará fazer uma
    tipo de interação **Avise-me** (você pode colocar vários
    palavras separadas por **;** ).

-   **Ordem de devolução padrão** : Ordem de devolução padrão
    para uma interação de tipo **Avise-me** (usado, em particular,
    se você programou o alerta pela interface móvel)

-   **Sinônimo de objetos** : Lista de sinônimos para objetos
    (ex : rdc|térreo|subterrâneo|banheiro baixo|Casa de banho).

-   **Sinônimo de equipamento** : Lista de sinônimos para
    Os equipamentos.

-   **Sinônimo de pedidos** : Lista de sinônimos para
    pedidos.

-   **Sinônimo de resumos** : Lista de sinônimos para resumos.

-   **Sinônimo máximo controle deslizante** : Sinônimo para colocar um
    comando tipo deslizante máximo (ex abre para abre o obturador
    o quarto ⇒ 100% do obturador do quarto).

-   **Sinônimo controle deslizante Mínimo** : Sinônimo para colocar um
    comando do tipo slider no mínimo (ex fecha para fechar o obturador
    o quarto ⇒ componente do quarto a 0%).

Cores 
--------

Esta parte permite definir as cores às quais o Jeedom associará
palavras vermelho / azul / preto… Para adicionar uma cor :

-   Clique no botão **+**, certo,

-   Dê um nome à sua cor,

-   Escolha a cor associada clicando na caixa à direita.

Relações 
========

Configurar a geração e gerenciamento de relatórios

-   **Tempo limite após a geração da página (em ms)** : Prazo
    aguarde após carregar o relatório para tirar a "foto", em
    alterar se o seu relatório estiver incompleto, por exemplo.

-   **Limpar relatórios mais antigos de (dias)** : Define o
    número de dias antes da exclusão de um relatório (os relatórios levam
    um pouco de espaço, então tome cuidado para não colocar muito
    conservação).

Conexões 
=====

Configurar gráficos de link. Esses links permitem que você
veja, na forma de um gráfico, os relacionamentos entre objetos,
equipamentos, objetos, etc.

-   **Profundidade para cenários** : Usado para definir, quando
    exibindo um gráfico de links de um cenário, o número
    número máximo de elementos a serem exibidos (quanto mais elementos, maior a
    o gráfico demorará a gerar e mais difícil será a leitura).

-   **Profundidade para objetos** : O mesmo para objetos.

-   **Profundidade de equipamentos** : O mesmo para o equipamento.

-   **Profundidade para encomendas** : Mesmo para pedidos.

-   **Profundidade para variáveis** : O mesmo para variáveis.

-   **Prerender parâmetro** : Vamos agir no layout
    do gráfico.

-   **Parâmetro renderizar** : Same.

Sumários 
=======

Adicionar resumos de objetos. Esta informação é exibida
no topo, à direita, na barra de menus do Jeedom ou ao lado do
Objetos :

-   **Chave** : Chave para o resumo, especialmente para não tocar.

-   **Nome** : Nome do resumo.

-   **Cálculo** : Método de cálculo, pode ser do tipo :

    -   **Soma** : somar os diferentes valores,

    -   **Média** : valores médios,

    -   **Texto** : exibir o valor literalmente (especialmente para aqueles
        do tipo string).

-   **ícone** : Ícone Resumo.

-   **Unidade** : Unidade de resumo.

-   **Método de contagem** : Se você contar dados binários,
    você deve definir esse valor como binário, por exemplo, se contar o
    número de luzes acesas, mas você só tem o valor de
    dimmer (0 a 100), então você tem que colocar binário, como este Jeedom
    considere que, se o valor for maior que 1, a lâmpada
    está ligado.

-   **Mostrar se o valor é 0** : Marque esta caixa para exibir o
    valor, mesmo quando é 0.

-   **Link para um virtual** : Comece a criar pedidos virtuais
    tendo como valor os do resumo.

-   **Excluir resumo** : O último botão, na extrema direita, permite
    para excluir o resumo da linha.

Toras 
====

Cronograma 
--------

-   **O número máximo de eventos** : Define o número máximo para
    mostrar na linha do tempo.

-   **Excluir todos os eventos** : Esvazie a linha do tempo de
    todos os seus eventos gravados.

Mensagens 
--------

-   **Adicione uma mensagem para cada erro nos logs** : se um plugin
    ou o Jeedom grava uma mensagem de erro em um log, o Jeedom adiciona
    automaticamente uma mensagem no centro de mensagens (pelo menos
    você tem certeza de não perder).

-   **Ação na mensagem** : Permite que você execute uma ação ao adicionar uma mensagem ao centro de mensagens. Você tem 2 tags para essas ações : 
        - #message# : mensagem em questão
        - #plugin# : plugin que acionou a mensagem

Notificações 
-------

-   **Adicione uma mensagem a cada tempo limite** : Adicione uma mensagem no
    centro de mensagens se o equipamento cair **Tempo limite**.

-   **Ordem de tempo limite** : Comando de tipo **Mensagem** usar
    se houver um equipamento **Tempo limite**.

-   **Adicione uma mensagem a cada bateria em Aviso** : Adicione um
    mensagem no centro de mensagens se um dispositivo tiver seu nível de
    bateria em **Aviso**.

-   **Comando da bateria em Aviso** : Comando de tipo **Mensagem**
    a ser usado se o equipamento estiver com a bateria **Aviso**.

-   **Adicione uma mensagem a cada bateria em perigo** : Adicione um
    mensagem no centro de mensagens se um dispositivo tiver seu nível de
    bateria em **Perigo**.

-   **Comando na bateria em perigo** : Comando de tipo **Mensagem** à
    use se o equipamento estiver no nível da bateria **Perigo**.

-   **Adicione uma mensagem a cada aviso** : Adicione uma mensagem no
    centro de mensagens se um pedido entrar em alerta **Aviso**.

-   **Comando no aviso** : Comando de tipo **Mensagem** usar
    se um pedido entrar em alerta **Aviso**.

-   **Adicione uma mensagem a cada Perigo** : Adicione uma mensagem no
    centro de mensagens se um pedido entrar em alerta **Perigo**.

-   **Comando sobre Perigo** : Comando de tipo **Mensagem** usar se
    um pedido entra em alerta **Perigo**.

Log 
---

-   **Log Motor** : Permite alterar o mecanismo de log para, por
    Por exemplo, envie-os para um demônio syslog (d).

-   **Logs de formato** : Formato de log a ser usado (Cuidado : ça
    não afeta os logs do daemon).

-   **O número máximo de linhas em um arquivo de log** : Define o
    número máximo de linhas em um arquivo de log. É recomendado
    para não tocar nesse valor, porque um valor muito grande poderia
    preencha o sistema de arquivos e / ou torne o Jeedom incapaz
    para exibir o log.

-   **Nível de log padrão** : Quando você seleciona "Padrão",
    para o nível de um log no Jeedom, esse é o que será
    então usado.

Abaixo, você encontrará uma tabela para gerenciar com precisão o
nível de log dos elementos essenciais do Jeedom, bem como do
plugins.

Instalações 
===========

-   **Falha Contagem off equipamentos** : Nombre
    falha de comunicação com o equipamento antes da desativação do
    este (uma mensagem avisará se isso acontecer).

-   **Limiares da bateria** : Permite gerenciar limites de alerta globais
    nas baterias.

Atualização e arquivos 
=======================

Atualização do Jeedom 
---------------------

-   **Fonte atualização** : Escolha a fonte para atualizar o
    Núcleo Jeedom.

-   **Core Version** : Versão principal a recuperar.

-   **Verificar atualizações automaticamente** : Indique se
    você precisa procurar automaticamente se houver novas atualizações
    (tenha cuidado para evitar sobrecarregar o mercado, o tempo de
    verificação pode mudar).

Depósitos 
----------

Os depósitos são espaços de armazenamento (e serviço) para poder
mover backups, recuperar plugins, recuperar núcleo
Jeedom, etc.

### Ficheiro 

Depósito usado para ativar o envio de plugins por arquivos.

### Github 

Depósito usado para conectar o Jeedom ao Github.

-   **Token** : Token para acesso ao depósito privado.

-   **Usuário ou organização do repositório principal da Jeedom** : Nom
    o usuário ou a organização no github para o núcleo.

-   **Nome do repositório para o núcleo Jeedom** : Nome do repositório para core.

-   **Indústria do núcleo Jeedom** : Ramificação do repositório principal.

### Mercado 

Depósito usado para conectar o Jeedom ao mercado, é altamente recomendado
usar este repositório. Atenção : qualquer solicitação de suporte pode ser
recusado se você usar um depósito diferente deste.

-   **Morada** : Endereço do mercado.

-   **Nome de Usuário** : Seu nome de usuário no mercado.

-   **Senha** : Sua senha do Market.

-   **Nome da [nuvem de backup]** : Nome do seu backup na nuvem (a atenção deve ser exclusiva para cada Jeedom em risco de colidir entre eles)

-   **Senha da [nuvem de backup]** : Senha de backup na nuvem. IMPORTANTE, você não deve perdê-lo, não há como recuperá-lo. Sem ele, você não poderá restaurar o seu Jeedom

-   **[Nuvem de backup] Backup completo de frequência** : Frequência de backup completo na nuvem. Um backup completo é maior que um incremental (que envia apenas as diferenças). Recomenda-se fazer 1 por mês

### Samba 

Depósito para enviar automaticamente um backup Jeedom para
uma participação do Samba (ex : NAS Synology).

-   **\ [Backup \] IP** : IP do servidor Samba.

-   **\ [Backup \] Usuário** : Nome de usuário para login
    (conexões anônimas não são possíveis). Deve haver
    que o usuário tenha direitos de leitura e gravação no
    diretório de destino.

-   **\ [Backup \] Senha** : Senha do usuário.

-   **\ [Backup \] Compartilhamento** : Maneira de compartilhar (tenha cuidado
    parar no nível de compartilhamento).

-   **Caminho \ [Backup \]** : Caminho no compartilhamento (para definir
    relativo), deve existir.

> **NOTA**
>
> Se o caminho para a pasta de backup do samba for :
> \\\\ 192.168.0.1 \\ Backups \\ Domótica \\ Jeedom Then IP = 192.168.0.1
> , Partilha = //192.168.0.1 / Backups, Caminho = Domótica / Jeedom

> **NOTA**
>
> Ao validar o compartilhamento Samba, conforme descrito acima,
> uma nova forma de backup aparece na seção
> Administração → Backups Jeedom. Ao ativá-lo, o Jeedom continuará
> quando é enviado automaticamente no próximo backup. Um teste é
> possível executando um backup manual.

> **IMPORTANTE**
>
> Pode ser necessário instalar o pacote smbclient para o diretório
> obras de depósito.

> **IMPORTANTE**
>
> O protocolo Samba possui várias versões, o v1 está comprometido 
> segurança e em alguns NAS, você pode forçar o cliente a usar a v2
> ou v3 para conectar. Então, se você tiver um erro de negociação de protocolo
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE, existe uma boa chance de que o NAS listado
> a restrição esteja em vigor. Você deve modificar no SO do seu Jeedom
> o arquivo / etc / samba / smb.conf e adicione essas duas linhas a ele :
> protocolo máximo do cliente = SMB3
> protocolo min de cliente = SMB2
> O smbclient do lado do Jeedom usará v2 em que v3 e colocando SMB3 nos dois
> SMB3. Cabe a você adaptar de acordo com as restrições no servidor NAS ou outro servidor Samba

> **IMPORTANTE**
>
> Jeedom deve ser o único a gravar nesta pasta e deve estar vazio
> por padrão (ou seja, antes de configurar e enviar o
> primeiro backup, a pasta não deve conter nenhum arquivo ou
> dossier).

### URL  

-   **URL principal do Jeedom**

-   **URL da versão principal do Jeedom**


