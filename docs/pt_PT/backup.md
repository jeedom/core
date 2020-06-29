Jeedom tem a possibilidade de ser salvo e restaurado de ou de
de diferentes locais.

Configuração 
=============

Acessível a partir de **Administração → Backups**, esta página permite que o
gerenciamento de backup.

Você encontrará, à esquerda, os parâmetros e botões de ação. No
certo, esse é o status em tempo real da ação atual (backup
ou restauração), se você lançou um.

**Backups** 
---------------

-   **Backups** : Permite iniciar um backup manualmente e
    imediatamente (útil se você quiser fazer uma alteração crítica.
    Isso permitirá que você volte). Você também tem um
    para iniciar um backup sem enviar o arquivo para o
    nuvem (requer assinatura, veja abaixo). Enviando um
    o backup na nuvem pode demorar um pouco. Esta opção
    assim evita uma perda excessiva de tempo.

-   **Backups Local** : Indica a pasta na qual
    Jeedom copia backups. Recomenda-se não
    mude. Se você estiver em um caminho relativo, sua origem é
    onde o Jeedom está instalado.

-   **Número de dias de armazenamento de backups** : Número de
    dias de backup para manter. Passado esse período, o
    backups serão excluídos. Cuidado para não colocar um número
    dias muito altos, caso contrário, seu sistema de arquivos pode
    estar saturado.

-   **Tamanho total máximo de backups (MB)** : Permite limitar
    o local ocupado por todos os backups na pasta
    backup. Se esse valor for excedido, o Jeedom excluirá o
    backups mais antigos até ficar abaixo do
    tamanho máximo. No entanto, manterá pelo menos um backup.

**Backups locais** 
-----------------------

-   **Backups disponíveis** : Lista de backups disponíveis.

-   **Restaurar backup** : Inicia a restauração do backup
    selecionado acima.

-   **Remover backup** : Excluir backup selecionado
    acima, apenas na pasta local.

-   **Enviar cópia de segurança** : Permite enviar para o
    salve um arquivo no computador que você
    atualmente em uso (permite, por exemplo, restaurar um arquivo
    recuperado anteriormente em um novo Jeedom ou reinstalação).

-   **De backup de download** : Permite baixar para o seu
    computador o arquivo de backup selecionado acima.

**Backups de mercado** 
----------------------

-   **Envio de backups** : Instrui o Jeedom a enviar o
    backups na nuvem do Market, cuidado com o fato de ter
    conseguiu a assinatura.

-   **Enviar cópia de segurança** : Permite enviar um
    arquivo de backup localizado no seu computador.

-   **Backups disponíveis** : Lista de backups
    nuvem disponível.

-   **Restaurar backup** : Lança a restauração de um
    Backup em nuvem.

**Backups do Samba** 
---------------------

-   **Envio de backups** : Instrui o Jeedom a enviar o
    backups no compartilhamento samba configurados aqui
    Administração → Configuração → guia Atualizações.

-   **Backups disponíveis** : Lista de backups
    samba disponível.

-   **Restaurar backup** : Inicia a restauração do backup
    samba selecionado acima.

> **IMPORTANTE**
>
> Os backups de Jeedom devem absolutamente cair em uma pasta apenas para ele !!! Ele excluirá tudo o que não for um backup jeedom da pasta


O que é salvo ? 
==============================

Durante um backup, o Jeedom fará backup de todos os seus arquivos e do
Banco de dados. Isso, portanto, contém toda a sua configuração
(equipamentos, controles, histórico, cenários, design etc.).

Em termos de protocolos, apenas o Z-Wave (OpenZwave) é um pouco
diferente porque não é possível salvar as inclusões.
Eles estão diretamente incluídos no controlador, então você precisa
mantenha o mesmo controlador para encontrar seus módulos Zwave.

> **NOTA**
>
> O sistema no qual o Jeedom está instalado não é copiado. Se
> você modificou os parâmetros deste sistema (em particular via SSH),
> cabe a você encontrar uma maneira de recuperá-los em caso de problemas.

Backup em nuvem 
================

O backup na nuvem permite que o Jeedom envie seus backups
diretamente no mercado. Isso permite que você os restaure facilmente
e certifique-se de não perdê-los. O mercado mantém os últimos 6
backups. Para se inscrever basta ir à sua página
**Perfil** no mercado e depois na guia **meus backups**. Vous
pode, nesta página, recuperar um backup ou comprar um
assinatura (por 1, 3, 6 ou 12 meses).

> **Dica**
>
> Você pode personalizar o nome dos arquivos de backup em
> da guia **Minhas jeedoms**, evitando no entanto os personagens
> exótico.

Frequência de backups automáticos 
======================================

O Jeedom executa um backup automático todos os dias no mesmo
hora. É possível modificar isso, a partir do "Mecanismo
tarefas "(a tarefa é denominada **Backup Jeedom**), Mas isso não
recomendado. De fato, é calculado em relação à carga do
Market.
