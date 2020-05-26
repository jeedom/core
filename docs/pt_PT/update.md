# Update Center
**Configurações → Sistema → Centro de Atualização**


O **Update Center** permite atualizar todas as funcionalidades do Jeedom, incluindo o software principal e seus plugins.
Outras funções de gerenciamento de extensão estão disponíveis (excluir, reinstalar, verificar etc.).).


## Funções da página

Na parte superior da página, independentemente da guia, estão os botões de controle.

A Jeedom se conecta periodicamente ao mercado para ver se há atualizações disponíveis. A data da última verificação é indicada no canto superior esquerdo da página.

Na abertura da página, se essa verificação tiver mais de duas horas, a Jeedom refaz automaticamente uma verificação.
Você também pode usar o botão **Verificar atualizações** Para fazer isso manualmente.
Se você deseja executar uma verificação manual, pode pressionar o botão "Verificar atualizações".

O botão **Salvar** deve ser usado quando você alterar as opções na tabela abaixo, para especificar não atualizar determinados plugins, se necessário.

## Atualizar o núcleo

O botão **Atualizar** permite atualizar o Core, plugins ou ambos.
Depois de clicar nele, você obtém essas opções diferentes :
- **Pré-atualização** : Permite atualizar o script de atualização antes de aplicar as novas atualizações. Geralmente usado a pedido do suporte.
- **Salvar antes** : Faça backup do Jeedom antes de atualizar.
- **Plugins de atualização** : Permite incluir plugins na atualização.
- **Atualizar o núcleo** : Permite incluir o kernel Jeedom (o Core) na atualização.

- **Modo forçado** : Execute a atualização no modo forçado, ou seja, mesmo se houver um erro, o Jeedom continuará e não restaurará o backup. (Este modo desabilita o salvamento !).
- **Update para reaplicar** : Permite reaplicar uma atualização. (NB : Nem todas as atualizações podem ser reaplicadas.)

> **IMPORTANTE**
>
> Antes de uma atualização, por padrão, o Jeedom fará um backup. No caso de um problema ao aplicar uma atualização, o Jeedom restaurará automaticamente o backup feito pouco antes. Esse princípio é válido apenas para atualizações do Jeedom e não para atualizações de plugins.

> **Dica**
>
> Você pode forçar uma atualização do Jeedom, mesmo que ele não ofereça um.

## Guias Core e Plugins

A tabela contém as versões dos núcleos e plug-ins instalados.

Os plug-ins têm um crachá ao lado do nome, especificando sua versão, de cor verde *Estável*, ou laranja em *beta* ou outro.

- **Estado** : OK ou NOK.
- **Nome** : Nome e origem do plug-in
- **Versão** : Indica a versão precisa do Core ou plugin.
- **Opções** : Marque esta caixa se não desejar que este plugin seja atualizado durante a atualização global (Button **Atualizar**).

Em cada linha, você pode usar as seguintes funções:

- **Reinstalar** : Forçar reassentamento.
- **Remover** : Permite desinstalá-lo.
- **Verificar** : Consulte a fonte de atualizações para descobrir se uma nova atualização está disponível.
- **Atualizar** : Permite atualizar o elemento (se houver uma atualização).
- **Changelog** : Permite acesso à lista de alterações na atualização.

> **IMPORTANTE**
>
> Se o registro de alterações estiver vazio, mas você ainda tiver uma atualização, isso significa que a documentação foi atualizada. Portanto, não é necessário solicitar mudanças ao desenvolvedor, pois não há necessariamente. (geralmente é uma atualização da tradução, documentação).
> O desenvolvedor do plugin também pode, em alguns casos, fazer correções simples, o que não requer necessariamente a atualização do registro de alterações.

> **Dica**
>
> Quando você inicia uma atualização, uma barra de progresso aparece acima da tabela. Evite outras manipulações durante a atualização.

## Guia Informações

Durante ou após uma atualização, essa guia permite que você leia o log dessa atualização em tempo real.

> **NOTA**
>
> Esse log normalmente termina com *[FINALIZAR SUCESSO DE ATUALIZAÇÃO]*. Pode haver algumas linhas de erro nesse tipo de log, no entanto, a menos que haja um problema real após a atualização, nem sempre é necessário entrar em contato com o suporte para isso.

## Atualização da linha de comando

É possível atualizar o Jeedom diretamente no SSH.
Uma vez conectado, este é o comando para executar :

``````sudo php /var/www/html/install/update.php``````

Os possíveis parâmetros são :

- **modo** : `force ', para iniciar uma atualização no modo forçado (ignora erros).
- **Versão** : Rastreamento de número da versão, para reaplicar as alterações desta versão.

Aqui está um exemplo de sintaxe para fazer uma atualização forçada reaplicando as alterações desde o 4.0.04 :

``````sudo php  /var/www/html/install/update.php mode=force version=4.0.04``````

Atenção, após uma atualização na linha de comando, é necessário reaplicar os direitos na pasta Jeedom :

``````sudo chown -R www-data:www-data /var/www/html``````
