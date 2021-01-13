# Gerenciamento de plug-in
**Plugins → Gerenciamento de plugins**

Esta página fornece acesso às configurações de plugins.
Você também pode manipular os plugins, a saber : baixar, atualizar e ativá-los,…

Existe, portanto, uma lista de plugins em ordem alfabética e um link para o mercado.
- Os plug-ins desativados ficam acinzentados.
- Plugins que não estão na versão *estábulo* temos um ponto laranja na frente do nome.

Ao clicar em um plug-in, você acessa sua configuração. Na parte superior, você encontra o nome do plug-in e, entre parênteses, seu nome em Jeedom (ID) e, finalmente, o tipo de versão instalada (estável, beta).

> **Importante**
>
> Ao baixar um plug-in, ele é desativado por padrão. Então você tem que ativá-lo sozinho.

## Gestion

Aqui você tem três botões :

- **Sincronizar mercado** : Se você instalar um plugin de um navegador da web em sua conta do Market (além do Jeedom), você pode forçar uma sincronização para instalá-lo.
- **Mercado** : Abra o Jeedom Market, para selecionar um plugin e instalá-lo em seu Jeedom.
- **Plugins** : Você pode instalar um plugin aqui a partir de uma fonte Github, Samba, ...

### Sincronizar mercado

Em um navegador, vá para o [Mercado](https://market.jeedom.com).
Faça login em sua conta.
Clique em um plug-in e escolha *Instale estável* ou *Instalar beta* (se sua conta do Market permitir).

Se sua conta do Market estiver configurada corretamente em seu Jeedom (Configuração → Atualizações / Market → guia Market), você pode clicar em *Sincronizar mercado* ou espere que ele se acalme por conta própria.

### Market

Para instalar um novo plugin, basta clicar no botão "Mercado" (e o Jeedom está conectado à Internet). Após um curto período de carregamento, você receberá a página.

> **Dica**
>
> Você deve inserir as informações da sua conta do Market na administração (Configuração → Atualizações / Market → guia Market) para encontrar os plug-ins que você já comprou, por exemplo.

No topo da janela você tem filtros :
- **Gratuito / Pago** : exibe apenas gratuito ou pago.
- **Oficial / Recomendado** : exibe apenas plugins oficiais ou recomendados.
- **Menu suspenso Categoria** : exibe apenas determinadas categorias de plugins.
- **Pesquisa** : permite procurar um plugin (no nome ou na descrição dele)).
- **Nome de Usuário** : exibe o nome de usuário usado para conectar-se ao Market, bem como o status da conexão.

> **Dica**
>
> A cruz pequena redefine o filtro em questão

Depois de encontrar o plug-in desejado, basta clicar nele para abrir o arquivo. Esta folha fornece muitas informações sobre o plug-in, em particular :

- Se for oficial / recomendado ou se for obsoleto (você definitivamente deve evitar instalar plug-ins obsoletos).
- 4 ações :
    - **Instale estável** : permite instalar o plugin em sua versão estável.
    - **Instalar beta** : permite instalar o plugin em sua versão beta (apenas para betatesters).
    - **Instalar pro** : permite instalar a versão pro (muito pouco usado).
    - **Retirar** : se o plug-in estiver instalado no momento, esse botão permite removê-lo.

Abaixo, você encontrará a descrição do plug-in, a compatibilidade (se o Jeedom detectar uma incompatibilidade, ele o notificará), as opiniões sobre o plug-in (você pode anotá-lo aqui) e informações adicionais (o autor, a pessoa que fez a atualização mais recente, um link para o documento, o número de downloads). À direita, você encontrará um botão "Changelog" que permite ter todo o histórico de modificações, um botão "Documentação" que se refere à documentação do plugin. Então você tem o idioma disponível e as várias informações na data da última versão estável.

> **Importante**
>
> Não é realmente recomendável colocar um plug-in beta em um Jeedom não beta, pois muitos problemas operacionais podem resultar.

> **Importante**
>
> Alguns plugins são cobrados; nesse caso, o plug-in oferecerá que você o compre. Depois de concluído, é necessário aguardar cerca de dez minutos (tempo de validação do pagamento) e retornar ao arquivo do plug-in para instalá-lo normalmente.

### Plugins

Você pode adicionar um plugin ao Jeedom a partir de um arquivo ou de um repositório Github. Para fazer isso, você deve, na configuração do Jeedom, ativar a função apropriada na seção "Atualizações / Mercado"".

Atenção, no caso de adicionar por um arquivo zip, o nome do zip deve ser o mesmo que o ID do plug-in e, ao abrir o ZIP, uma pasta\_info do plugin deve estar presente.



## Meus plugins

Ao clicar no ícone de um plugin, você abre sua página de configuração.

> **Dica**
>
> Você pode pressionar Ctrl ou Click Center para abrir sua configuração em uma nova guia do navegador.

### No canto superior direito, alguns botões :

- **Documentação** : Permite acesso direto à página de documentação do plug-in.
- **Changelog** : Vamos ver o log de alterações do plugin, se existir.
- **Detalhes** : Permite encontrar a página do plug-in no mercado.
- **Retirar** : Remova o plugin do seu Jeedom. Observe que isso também remove permanentemente todos os equipamentos deste plugin.

### Abaixo à esquerda, há uma área **Estado** com :

- **Status** : Permite ver o status do plug-in (ativo / inativo)).
- **Categoria** : A categoria do plugin, indicando em qual submenu encontrá-lo.
- **Autor** : O autor do plugin, link para o mercado e os plugins deste autor.
- **Licença** : Indica a licença do plug-in, que geralmente será AGPL.

- **Açao** : Permite ativar ou desativar o plug-in. O botão **Abrir** Permite que você vá diretamente para a página do plugin.
- **Versão** : A versão do plug-in instalado.
- **Pré-requisitos** : Indica a versão mínima do Jeedom necessária para o plug-in.


### À direita, encontramos a área **Log e monitoramento** o que permite definir :

- O nível de logs específicos para o plug-in (encontramos essa mesma possibilidade em Administração → Configuração na guia logs, na parte inferior da página).
- Exibir logs do plug-in.
- Batimento cardiaco : A cada 5 minutos, o Jeedom verifica se pelo menos um dispositivo de plug-in se comunicou nos últimos X minutos (se você deseja desativar a funcionalidade, basta colocar 0).
- Reiniciar demônio : Se o hertbeat der errado, o Jeedom reiniciará o daemon.

Se o plug-in tiver dependências e / ou um daemon, essas áreas adicionais serão exibidas abaixo das áreas mencionadas acima.

### Dependências :

- **Último nome** : Geralmente será local.
- **Status** : Status de dependência, OK ou NOK.
- **Instalação** : Permite instalar ou reinstalar dependências (se você não fizer isso manualmente e elas estiverem NOK, o Jeedom cuidará de si mesmo depois de um tempo).
- **última Instalação** : Data da última instalação da dependência.

### Demônio :

- **Último nome** : Geralmente será local.
- **Status** : Status do daemon, OK ou NOK.
- **Configuração** : OK, se todos os critérios para o demônio funcionar forem atendidos ou fornecer a causa do bloqueio.
- **(Reiniciar** : Permite iniciar ou reiniciar o demônio.
- **Parar** : Usado para parar o daemon (apenas no caso em que o gerenciamento automático está desativado).
- **Gerenciamento automático** : Habilita ou desabilita o gerenciamento automático (que permite que o Jeedom gerencie o próprio daemon e reinicie-o, se necessário. Salvo indicação em contrário, é recomendável deixar o gerenciamento automático ativo).
- **último lançamento** : Data do último lançamento do daemon.

> **Dica**
>
> Alguns plugins possuem uma parte de configuração. Se for esse o caso, ele aparecerá nas zonas de dependência e daemon descritas acima.
> Nesse caso, consulte a documentação do plug-in em questão para saber como configurá-lo.

### Abaixo, há uma área de funcionalidade. Isso permite que você veja se o plug-in usa uma das principais funções do Jeedom, como :

- **Interagir** : Interações específicas.
- **Cron** : Um cron por minuto.
- **Cron5** : Um cron a cada 5 minutos.
- **Cron10** : Um cron a cada 10 minutos.
- **Cron15** : Um cron a cada 15 minutos.
- **Cron30** : Um cron a cada 30 minutos.
- **CronHourly** : Um cron a cada hora.
- **CronDaily** : Um cron diário.
- **deadcmd** : Um cron para comandantes mortos.
- **saúde** : Uma saúde cron.

> **Dica**
>
> Se o plug-in usa uma dessas funções, você pode proibi-lo especificamente desmarcando a caixa "ativar" que estará presente ao lado dele.

### Panel

Podemos encontrar uma seção Painel que habilitará ou desabilitará a exibição do painel no painel ou no celular, se o plug-in oferecer um.


