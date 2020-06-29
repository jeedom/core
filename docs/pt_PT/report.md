Esta página permite ver todos os relatórios que foram gerados pela ação do relatório (consulte a documentação do cenário).

# O que é um relatório ?

Um relatório é uma captura de tela da interface Jeedom em um instante T (a captura é adaptada para não assumir a barra de menus e outros elementos desnecessários nesse tipo de uso).

Você pode fazer isso em vistas, design, página do painel....

É acionado a partir de um script com o comando report, você pode optar por enviar esse relatório usando um comando de mensagem (email, telegrama....)

# Utilisation

Seu uso é muito simples, você seleciona se deseja ver :

-	Ver relatórios
-	relatórios de deginas
-	relatórios do painel de plug-ins
- Relatórios de equipamentos (para um resumo da bateria de cada módulo)

Depois, você seleciona o nome do relatório em questão e verá todas as datas dos relatórios na memória

> **IMPORTANTE**
>
> A exclusão automática é feita por padrão para relatórios com mais de 30 dias (você pode configurar esse período na configuração do Jeedom)

Depois que o relatório selecionado, você pode vê-lo aparecer, faça o download novamente ou exclua-o.

Você também pode excluir todos os backups de um determinado relatório

# FAQ

> **Se você tiver um erro de detalhes : cutycapt: erro ao carregar bibliotecas compartilhadas: libEGL.so: não é possível abrir o arquivo de objeto compartilhado: Esse arquivo ou diretório não existe**
>
> É necessário no ssh ou em Administração -> Configuração -> OS / DB -> Sistema -> Administração para executar :
>sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so
>sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so
