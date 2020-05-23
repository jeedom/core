# FAQ
**Configurações → Versão : Faq**

### Jeedom requer uma assinatura ?
Não, o Jeedom é totalmente utilizável sem a necessidade de qualquer assinatura. No entanto, existem serviços oferecidos para backups ou chamadas / SMS, mas que são realmente opcionais.

### O Jeedom usa servidores externos para executar ?
Não, o Jeedom não usa a infraestrutura do tipo "Cloud"". Tudo é feito localmente e você não precisa de nossos servidores para que sua instalação funcione. Somente serviços como Market, backup online ou DNS Jeedom requerem o uso de nossos servidores.

### Existe um aplicativo móvel dedicado ?
O Jeedom possui uma versão móvel adequada para uso em celulares e tablets. Há também um aplicativo nativo para Android e iOS.

### Quais são as credenciais para efetuar login pela primeira vez ?
Quando você faz login no Jeedom pela primeira vez (e mesmo depois se você não os tiver alterado), o nome de usuário e a senha padrão são admin / admin. Na primeira conexão, é altamente recomendável modificar esses identificadores para obter mais segurança.

### Não consigo mais me conectar ao meu Jeedom
Desde o Jeedom 3.2 não é mais possível conectar-se a admin / admin remotamente por razões óbvias de segurança. As credenciais de administrador / administrador funcionam apenas localmente. Atenção, se você passar pelo DNS, mesmo localmente, será necessariamente identificado como remoto. Outro ponto padrão somente IP em 192.168.*.* ou 127.0.0.1 são reconhecidos como locais. Ele é configurado na administração da parte de segurança Jeedom e depois no IP "branco". Se, apesar de tudo, você ainda não conseguir se conectar, use o procedimento de redefinição de senha (consulte os tutoriais / como)

### Não vejo todo o meu equipamento no painel
Geralmente, isso se deve ao fato de o equipamento estar atribuído a um objeto que não é o filho ou o próprio objeto do primeiro objeto selecionado à esquerda na árvore (você pode configurá-lo em seu perfil).

### A interface Jeedom possui atalhos ?
Sim, a lista de atalhos de teclado / mouse é [aqui](shortcuts.md).

### Podemos reordenar pedidos de equipamentos ?
Sim, é possível, basta arrastar e soltar os comandos do seu objeto em sua configuração.

### Podemos editar o estilo dos widgets ?
Para cada comando, você pode escolher sua exibição entre diferentes widgets principais ou criar um com Ferramentas → Widgets.

### Podemos colocar o mesmo equipamento mais de uma vez em um projeto ?
Não, não é possível, mas você pode duplicá-lo graças ao plugin virtual.

### Como alterar dados históricos incorretos ?
Basta, em uma curva histórica da ordem, clicar no ponto em questão. Se você deixar o campo em branco, o valor será excluído.

### Quanto tempo leva um backup ?
Não existe uma duração padrão, depende do sistema e do volume de dados a serem copiados, mas pode levar mais de 5 minutos, isso é normal.

### Onde estão os backups de Jeedom ?
Eles estão na pasta / var / www / html / backup

### Podemos colocar o Jeedom em https ?
Sim : Você tem um pacote de força ou mais, nesse caso, você
basta usar o [DNS Jeedom](https://jeedom.github.io/documentation/howto/pt_PT/mise_en_place_dns_jeedom). Com um DNS e você sabe como configurar um certificado válido, nesse caso, é uma instalação padrão de um certificado.

### Como se conectar no SSH ?
Aqui está um [Documentação](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), "Windows : Putty". O "hostname" sendo o ip do seu Jeedom, os identificadores sendo :

- Nome de usuário : "root ", senha : "Mjeedom96"
- Nome de usuário : "jeedom ", senha : "Mjeedom96"
- Ou o que você coloca na instalação se estiver em DIY

Observe que quando você escreve a senha, não verá nada escrito na tela, isso é normal.

### Como redefinir direitos ?
No ssh do :

`` `{.bash}
sudo su -
chmod -R 775 / var / www / html
chown -R www-data:www-data / var / www / html
`` ''

### Como atualizar o Jeedom no SSH ?
No ssh do :

`` `{.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 / var / www / html
chown -R www-data:www-data / var / www / html
`` ''

### O Symbian é compatível com Webapp ?
O aplicativo da web requer um smartphone compatível com HTML5 e CSS3. Por conseguinte, infelizmente, não é compatível com Symbian.

### Em que plataformas o Jeedom pode ser executado ?
Para que o Jeedom funcione, você precisa de uma plataforma linux com direitos de root ou um sistema do tipo docker. Portanto, não funciona em uma plataforma Android pura.

### Não consigo atualizar o plug-in "Falha ao baixar o arquivo. Tente novamente mais tarde (tamanho menor que 100 bytes))..." ?
Isso pode ser devido a várias coisas, :

- Verifique se o seu Jeedom ainda está conectado ao mercado (na página de administração do Jeedom, parte atualizada, você tem um botão de teste).
- Verifique se a conta do mercado comprou o plug-in em questão.
- Verifique se você tem espaço no Jeedom (a página de saúde informará).
- Verifique se a sua versão do Jeedom é compatível com o plugin.

### Eu tenho uma página em branco
É necessário conectar-se no SSH ao Jeedom e iniciar o script de auto-diagnóstico :
`` `{.bash}
sudo chmod + x / var / www / html / saúde.sh; sudo /var/www/html/health.sh
`` ''
Se houver algum problema, o script tentará corrigi-lo. Se não puder, dirá a você.

Você também pode consultar o log /var/www/html/log/http.error. Muitas vezes, isso indica a preocupação.

### Estou com um problema no identificador BDD
Estes devem ser redefinidos :

`` `{.bash}
bdd_password = $ (cat / dev / urandom | tr -cd 'a-f0-9' | cabeça -c 15)
echo "DROP USER 'jeedom' @ 'localhost'" | mysql -uroot -p
echo "CREATE USER 'jeedom' @ 'localhost' IDENTIFICADO POR '$ {bdd_password}';" | mysql -uroot -p
eco "CONCEDE TODOS OS PRIVILÉGIOS NO jeedom.* TO 'jeedom' @ 'localhost';" | mysql -uroot -p
cd / usr / share / nginx / www / jeedom
sudo cp core / config / common.config.sample.php core / config / common.config.php
sudo sed -i -e "s /#PASSWORD#/ $ {bdd_password} / g "core / config / common.config.php
sudo chown www-data:www-data core / config / common.config.php
`` ''

### Eu tenho \ {\ {… \} \} em todo lugar
A causa mais frequente é o uso de um plug-in na versão beta e o Jeedom na versão estável, ou o contrário. Para obter os detalhes do erro, você deve examinar o log http.erro (em / var / www / html / log).

### Ao fazer o pedido, tenho uma roda que gira sem parar
Novamente, isso geralmente ocorre devido a um plug-in na versão beta, enquanto o Jeedom está estável. Para ver o erro, você deve executar F12 e console.

### Não tenho mais acesso ao Jeedom, nem pela interface da web nem no console via SSH
Este erro não se deve ao Jeedom, mas a um problema com o sistema.
Se isso persistir após a reinstalação, é recomendável verificar com o serviço pós-venda se há problemas de hardware. Aqui está o [Documentação](https://jeedom.github.io/documentation/howto/pt_PT/recovery_mode_jeedom_smart) para Smart

### Meu cenário não para mais
É aconselhável olhar para os comandos executados pelo cenário, geralmente vem de um comando que não termina.

### Tenho instabilidades ou erros 504
Verifique se o seu sistema de arquivos não está corrompido, no SSH o comando é : `` ''sudo dmesg | grep error`` ''.

### Eu tenho o seguinte erro : SQLSTATE \ [HY000 \] \ [2002 \] Não é possível conectar ao servidor MySQL local através do soquete '/var/run/mysqld/mysqld.sock'
Isso ocorre porque o MySQL parou, não é normal, os casos comuns são :

- Falta de espaço no sistema de arquivos (pode ser verificado fazendo o comando "df -h", no SSH)
- Problema de corrupção de arquivo (s), que geralmente ocorre após um desligamento não seguro do Jeedom (falha de energia)
- A memória está preocupada, o sistema não possui memória e mata o processo que mais consome (geralmente o banco de dados). Isso pode ser visto na administração do sistema operacional e, em seguida, dmesg, você deve ver uma morte por "oom". Se for esse o caso, reduza o consumo do Jeedom desativando plugins.

Infelizmente, não há muita solução se for o segundo caso, o melhor é recuperar um backup (disponível em / var / www / html / backup por padrão), reinstalar o Jeedom e restaurar o backup. Você também pode ver por que o MySQL não deseja inicializar a partir de um console SSH :
`` `{.bash}
sudo su -
serviço de parada do mysql
mysqld --verbose
`` ''
Ou consulte o log : /var/log/mysql/error.log

### Os botões Desligar / Reiniciar não funcionam
Em uma instalação DIY é normal. No SSH, você deve fazer o comando visudo e, no final do arquivo, adicionar : www-data ALL = (ALL)
NOPASSWD: TODOS.

`` `{.bash}
serviço sudo reinício apache2
`` ''

### Não vejo alguns plugins do Market
Esse tipo de caso acontece se o seu Jeedom não for compatível com o plugin. Em geral, uma atualização Jeedom corrige o problema.

### Tenho equipamento de tempo limite, mas não o vejo no painel
Os alertas são classificados por prioridade, do menos importante ao mais importante : tempo limite, aviso de bateria, perigo de bateria, alerta de alerta, alerta de perigo

### O My Jeedom exibe permanentemente "Inicializando" mesmo após 1 hora ?
Se você está no DIY e no Debian 9 ou mais, verifique se não houve uma atualização do Apache e, portanto, o retorno do privateTmp (visível fazendo `ls / tmp` e veja se há uma pasta particular \* Apache). Se for esse o caso, você tem que fazer :
`` ''
mkdir /etc/systemd/system/apache2.service.d
echo "[Service]"> /etc/systemd/system/apache2.service.d/privatetmp.conf
echo "PrivateTmp = no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf
`` ''

### Eu tenho uma preocupação de tempo na minha história
Tente limpar o cache do Chrome, a exibição dos históricos é calculada em relação ao tempo do navegador.

### Estou com o erro "Problemas de rede detectados, reinicialização da rede"
Jeedom não pode encontrar ou não pode executar ping no gateway. Em geral, isso acontece se a caixa adsl for reiniciada (em particular, caixas ativas) e o Jeedom não tiver sido reiniciado ou reiniciado mais rapidamente que a caixa. Por questões de segurança, ele diz que encontrou um problema e relança o processo de conexão de rede. Você pode desativar esse mecanismo acessando a configuração do Jeedom e desativando o gerenciamento de rede pelo Jeedom.

### Recebo a mensagem "Falha ao fazer backup do banco de dados. Verifique se o mysqldump está presente."
Isso significa que o Jeedom não pode fazer backup do banco de dados, o que pode sugerir um problema com a corrupção do banco de dados e do sistema de arquivos. Infelizmente, não existe um comando milagroso para corrigir. O melhor é iniciar um backup e analisar o log dele. Em casos conhecidos de preocupações, temos:

- uma tabela base corrompida => há um início ruim que devemos tentar para reparar e, se não iniciar a partir do último backup bom (se você estiver no cartão SD, é o momento certo para alterá-lo)
- não há espaço suficiente no sistema de arquivos => veja a página de integridade, isso pode lhe dizer

### Estou com erros do tipo "Classe 'eqLogic' não encontrado", os arquivos parecem estar ausentes ou tenho uma página em branco
É um erro bastante sério, o mais simples é
`` ''
mkdir -p / root / tmp /
cd / root / tmp
wget https://github.com/jeedom/core/archive/master.zip
descompacte master.zip
cp -R / root / tmp / core-master / * / var / www / html
rm -rf / root / tmp / núcleo-mestre
`` ''

### Estou com o erro no cenário_execução MYSQL_ATTR_INIT_COMMAND
Na administração do Jeedom parte OS / DB, no console do sistema, é necessário fazer :
`` ''
sim | O comando sudo apt-install -y php-mysql-php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc php-common php-dev php-zip php-zip php-ssh2 php-mbstring php-ldap
`` ''

### Não consigo instalar as dependências do plug-in. Tenho um erro do tipo : "E: dpkg foi descontinuado. Il est nécessaire d'utiliser « sudo dpkg --configure -a » pour corriger le problème." ou "E: Não foi possível obter o bloqueio / var / lib / dpkg / lock"

Você deve :

- reiniciar o Jeedom
- vá para a administração dele (botão de roda dentada no canto superior direito e configuração na v3 ou Configuração -> Sistema -> Configuração na v4)
- vá para a guia OS / DB
- iniciar administração do sistema
- clique em Dpkg configure
- aguarde 10min
- relançar as dependências dos plugins de bloqueio

### Eu tenho esse erro ao instalar dependências de plug-in : "de pip._ importação interna principal"

É necessário no console do sistema do Jeedom ou no ssh para fazer 

`` ''``
sudo easy_install pip
sudo easy_install3 pip
`` ''``

Em seguida, reinicie as dependências
