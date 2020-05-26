# Sauvegardes
**Configurações → Sistema → Backups**

O Jeedom oferece a possibilidade de ser salvo e restaurado de ou de diferentes locais.
Esta página permite o gerenciamento de backups, restaura.


Você encontrará, à esquerda, os parâmetros e botões de ação. À direita está o status em tempo real da ação em andamento (backup ou restauração), se você tiver lançado um.

## Sauvegardes

- **Backups** : Permite iniciar um backup manual e imediatamente (útil se você quiser fazer uma alteração crítica. Isso permitirá que você volte). Você também tem um botão para iniciar um backup sem enviar o arquivo para a nuvem (requer uma assinatura, veja abaixo). O envio de um backup para a nuvem pode demorar um pouco. Esta opção evita, portanto, perda excessiva de tempo.

- **Backups Local** : Indica a pasta na qual o Jeedom copia os backups. Recomenda-se não alterá-lo. Se você estiver em um caminho relativo, sua origem é onde o Jeedom está instalado.

- **Número de dias de armazenamento de backups** : Número de dias de backup a serem mantidos. Após esse período, os backups serão excluídos. Cuidado para não passar muitos dias, caso contrário, seu sistema de arquivos pode estar saturado.

- **Tamanho total máximo de backups (MB)** : Limita o espaço ocupado por todos os backups na pasta de backup. Se esse valor for excedido, o Jeedom excluirá os backups mais antigos até que caiam abaixo do tamanho máximo. No entanto, manterá pelo menos um backup.

## Backups locais

- **Backups disponíveis** : Lista de backups disponíveis.

- **Restaurar backup** : Inicia a restauração do backup selecionado acima.

- **Remover backup** : Exclua o backup selecionado acima, apenas na pasta local.

- **Enviar cópia de segurança** : Permite enviar um arquivo morto localizado no computador que você está usando no momento para a pasta de backups (por exemplo, para restaurar um arquivo morto recuperado anteriormente em um novo Jeedom ou para reinstalar).

- **De backup de download** : Faça o download do arquivo do backup selecionado acima para o seu computador.

## Backups de mercado

- **Envio de backups** : Diga à Jeedom para enviar backups para a nuvem do Market. Observe que você deve ter se inscrito.

- **Enviar cópia de segurança** : Envie um arquivo de backup localizado no seu computador para a nuvem.

- **Backups disponíveis** : Lista de backups em nuvem disponíveis.

- **Restaurar backup** : Inicia a restauração de um backup na nuvem.

## Backups do Samba

- **Envio de backups** : Diz ao Jeedom para enviar os backups para o compartilhamento de samba configurado aqui Configurações → Sistema → Configuração : Atualizações.

- **Backups disponíveis** : Lista de backups de samba disponíveis.

- **Restaurar backup** : Inicia a restauração do backup samba selecionado acima.

> **IMPORTANTE**
>
> Os backups de Jeedom devem absolutamente cair em uma pasta apenas para ele !!! Ele excluirá tudo o que não for um backup jeedom da pasta


# O que é salvo ?

Durante um backup, o Jeedom fará backup de todos os seus arquivos e do banco de dados. Portanto, contém toda a sua configuração (equipamentos, controles, histórico, cenários, design etc.).).

No nível do protocolo, apenas o Z-Wave (OpenZwave) é um pouco diferente porque não é possível salvar as inclusões. Eles estão diretamente incluídos no controlador, portanto, você deve manter o mesmo controlador para encontrar seus módulos Zwave.

> **NOTA**
>
> Não é feito backup do sistema no qual o Jeedom está instalado. Se você alterou as configurações deste sistema (inclusive via SSH), cabe a você encontrar uma maneira de recuperá-las se tiver alguma dúvida.

# Backup em nuvem

O backup na nuvem permite que a Jeedom envie seus backups diretamente ao mercado. Isso permite que você os restaure facilmente e certifique-se de não perdê-los. O Market mantém os últimos 6 backups. Para se inscrever basta ir à sua página **Perfil** no mercado e depois na guia **meus backups**. Você pode, nesta página, recuperar um backup ou comprar uma assinatura (por 1, 3, 6 ou 12 meses).

> **Dica**
>
> Você pode personalizar o nome dos arquivos de backup na guia **Minhas jeedoms**, evitando no entanto os personagens exóticos.

# Frequência de backups automáticos

Jeedom executa um backup automático todos os dias no mesmo horário. É possível modificá-lo, no "Mecanismo de tarefas" (a tarefa é denominada **Backup Jeedom**), mas não é recomendado. De fato, é calculado em relação à carga do mercado.
