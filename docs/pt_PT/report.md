# Rapport
**Análise → Relatório**

Esta página permite ver todos os relatórios que foram gerados pela ação do relatório (consulte a documentação do cenário).

## Principe

Um relatório é uma captura de tela da interface Jeedom por vez t.

> **NOTA**
>
> Essa captura é adaptada para não levar a barra de menus e outros elementos desnecessários a esse tipo de uso.

Você pode fazer isso em vistas, designs, páginas do painel....

A geração é acionada a partir de um cenário com o comando report.
Você pode optar por receber esse relatório usando um comando de mensagem (email, telegrama etc.)).

## Utilisation

Seu uso é muito simples. Selecione à esquerda se quiser ver :

- Ver relatórios.
- Relatórios de design.
- Relatórios do painel de plug-ins.
- Relatórios de equipamentos (para um resumo da bateria de cada módulo).

Em seguida, selecione o nome do relatório em questão. Você verá todas as datas dos relatórios disponíveis.

> **IMPORTANTE**
>
> A exclusão automática é feita por padrão para relatórios com mais de 30 dias. Você pode configurar esse atraso na configuração do Jeedom.

Depois que o relatório é selecionado, você pode visualizá-lo, fazer o download ou excluí-lo.

Você também pode excluir todos os backups de um determinado relatório

## FAQ

> Se você tiver um erro de detalhes :
> *cutycapt: erro ao carregar bibliotecas compartilhadas: libEGL.so: não é possível abrir o arquivo de objeto compartilhado: Esse arquivo ou diretório não existe*
> No ssh ou em Configurações → Sistema → Configuração : A administração do OS / DB / System não :
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so``````
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so``````
