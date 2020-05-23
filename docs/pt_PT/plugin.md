O submenu Gerenciamento de plug-ins permite manipular plug-ins, exceto
saber : baixar, atualizar e ativá-los, etc

Gerenciamento de plug-in 
===================

Você pode acessar a página de plugins em Plugins → Gerenciar
plugins. Depois de clicar nele, encontramos a lista de
plugins em ordem alfabética e um link para o mercado. Plugins
desativados estão acinzentados.

> **Dica**
>
> Como em muitos lugares em Jeedom, coloque o mouse na extremidade esquerda
> abre um menu de acesso rápido (você pode
> do seu perfil, deixe-o sempre visível). Aqui, o menu
> permite que a lista de plugins seja classificada por categorias.

Ao clicar em um plug-in, você acessa sua configuração. Lá em cima você
encontre o nome do plug-in e, entre parênteses, seu nome no Jeedom
(ID) e, finalmente, o tipo de versão instalada (estável, beta).

> **IMPORTANTE**
>
> Ao baixar um plug-in, ele é desativado por padrão.
> Então você tem que ativá-lo sozinho.

No canto superior direito, alguns botões :

-   **Documentação** : Permite acesso direto à página de
    documentação do plugin

-   **Changelog** : Vamos ver o log de alterações do plugin, se existir

-   **Enviar para o mercado** : permite enviar o plugin no Market
    (disponível apenas se você é o autor)

-   **Detalhes** : Permite encontrar a página do plug-in no mercado

-   **Remover** : Remova o plugin do seu Jeedom. Tenha cuidado, isso
    também remove permanentemente todos os equipamentos deste plugin

Abaixo à esquerda, há uma área de status com :

-   **Estado** : Permite ver o status do plug-in (ativo / inativo))

-   **Versão** : A versão do plug-in instalado

-   **Ação** : Permite ativar ou desativar o plug-in

-   **Versão Jeedom** : Versão mínima do Jeedom necessária
    para a operação do plugin

-   **Licença** : Indica a licença do plug-in que geralmente será
    AGPL

À direita, encontramos a zona de registro e vigilância que permite definir 

-   o nível de logs específicos para o plug-in (encontramos essa mesma possibilidade em
Administração → Configuração na guia Logs, na parte inferior da página)

-   veja os logs do plugin

-   Batimento cardíaco : a cada 5 minutos, o Jeedom verifica se pelo menos um dispositivo de plug-in se comunicou nos últimos X minutos (se você deseja desativar a funcionalidade, basta colocar 0)

-   Reiniciar demônio : se o batimento cardíaco der errado, o Jeedom reiniciará o daemon

Se o plug-in tiver dependências e / ou um daemon, essas áreas
adicionais são exibidos nas áreas mencionadas acima.

Dependências :

-   **Nome** : Geralmente será local

-   **Estado** : dirá se as dependências estão OK ou KO

-   **Instalação** : instalará ou reinstalará
    dependências (se você não fizer isso manualmente e elas forem
    KO, Jeedom vai cuidar de si mesmo depois de um tempo)

-   **última Instalação** : data da última instalação do
    Dependências

Demônio :

-   **Nome** : Geralmente será local

-   **Estado** : dirá se o demônio está OK ou KO

-   **Configuração** : ficará bem se todos os critérios para o demônio
    voltas são cumpridas ou causam bloqueio

-   **(Reiniciar** : Permite iniciar ou reiniciar o demônio

-   **Parar** : permite parar o demônio (apenas no caso
    o gerenciamento automático está desativado)

-   **Gerenciamento automático** : permite ativar ou desativar o gerenciamento
    automático (que permite que o Jeedom gerencie o daemon e o
    reviver se necessário. Salvo indicação em contrário, é aconselhável
    deixe o gerenciamento automático ativo)

-   **último lançamento** : Data do último lançamento do daemon

> **Dica**
>
> Alguns plugins possuem uma parte de configuração. Se sim,
> aparecerá sob as dependências e zonas daemon descritas acima.
> Nesse caso, consulte a documentação do plug-in em
> pergunta sobre como configurá-lo.

Abaixo, há uma área de funcionalidade. Isso permite que você veja
se o plug-in usar uma das principais funções do Jeedom, como :

-   **Interagir** : Interações específicas

-   **Cron** : Um cron por minuto

-   **Cron5** : Um cron a cada 5 minutos

-   **Cron15** : Um cron a cada 15 minutos

-   **Cron30** : Um cron a cada 30 minutos

-   **CronHourly** : Um cron a cada hora

-   **CronDaily** : Um cron diário

> **Dica**
>
> Se o plug-in usa uma dessas funções, você pode especificamente
> proibi-lo de fazer isso desmarcando a caixa "ativar" que será
> presente seguinte.

Finalmente, podemos encontrar uma seção do painel que ativará ou
desativar a exibição do painel no painel ou no celular se o
plugin oferece um.

Instalação de plugins 
========================

Para instalar um novo plugin, basta clicar no botão
"Market "(e que o Jeedom está conectado à Internet). Após um curto período de
carregando você receberá a página.

> **Dica**
>
> Você deve ter inserido as informações da sua conta do Market em
> administração (Configuração → Atualizações → guia Mercado) para
> encontre os plugins que você já comprou, por exemplo.

No topo da janela você tem filtros :

-   **Gratuito / Pago** : exibe apenas livre ou
    os pagantes.

-   **Oficial / Recomendado** : exibe apenas plugins
    funcionários ou consultores

-   **Instalado / Não instalado** : exibe apenas plugins
    instalado ou não instalado

-   **Menu suspenso Categoria** : apenas exibe
    certas categorias de plugins

-   **Pesquisa** : permite procurar um plug-in (em nome ou
    descrição disso)

-   **Nome de Usuário** : exibe o nome de usuário usado para o
    conexão com o mercado e o status da conexão

> **Dica**
>
> A cruz pequena redefine o filtro em questão

Depois de encontrar o plugin desejado, basta clicar em
este para trazer o seu cartão. Esta folha oferece muito
informações sobre o plug-in, incluindo :

-   Se for oficial / recomendado ou se for obsoleto (você realmente precisa
    evite instalar plug-ins obsoletos)

-   4 ações :

    -   **Instale estável** : permite instalar o plugin no seu
        Versão estável

    -   **Instalar beta** : permite instalar o plugin no seu
        versão beta (apenas para testadores beta)

    -   **Instalar pro** : permite instalar a versão pro (muito
        pouco usado)

    -   **Remover** : se o plug-in estiver instalado atualmente, esse
        para excluí-lo

Abaixo, você encontrará a descrição do plugin, a compatibilidade
(se o Jeedom detectar uma incompatibilidade, ele será notificado), os avisos
no plugin (você pode classificá-lo aqui) e informações
complementar (o autor, a pessoa que fez a última atualização
dia, link para o documento, número de downloads). À direita
você encontrará um botão "Changelog" que permite que você tenha tudo
histórico de alterações, um botão "Documentação" que retorna
para a documentação do plugin. Então você tem o idioma disponível
e as várias informações na data da última versão estável.

> **IMPORTANTE**
>
> Não é realmente recomendável colocar um plugin beta em um
> Jeedom não beta, muitos problemas operacionais podem
> resultado.

> **IMPORTANTE**
>
> Alguns plugins são cobrados; nesse caso, a folha de plugins será
> vai oferecer para comprá-lo. Feito isso, aguarde um
> dez minutos (tempo de validação do pagamento), depois retorne
> na folha de plug-ins para instalá-lo normalmente.

> **Dica**
>
> Você também pode adicionar um plugin ao Jeedom a partir de um arquivo ou
> de um repositório do Github. Isso requer, na configuração de
> Jeedom, ative a função apropriada em "Atualizações e
> fichiers". Então será possível, colocando o mouse completamente
> esquerda e, exibindo o menu da página do plug-in, clique em
> em "Adicionar de outra fonte". Você pode então escolher o
> arquivo de origem "". Atenção, no caso de adição por um arquivo
> zip, o nome do zip deve ser o mesmo que o ID do plug-in e de
> abrindo o ZIP, uma pasta plugin\_info deve estar presente.
