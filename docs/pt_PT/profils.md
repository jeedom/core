# Preferências
**Configurações → Preferências**

A página Preferências permite configurar determinados comportamentos Jeedom específicos do usuário.

## Guia Preferências

### Interface

Define certos comportamentos da interface

- **Painel Objetos no Painel** : Exibe o painel de objetos (à esquerda) no Painel, sem precisar clicar no botão dedicado.
- **Página padrão** : Página a ser exibida por padrão para exibição ao conectar-se a computadores ou dispositivos móveis.
- **Objeto padrão** : Objeto a ser exibido por padrão na chegada ao Painel / dispositivo móvel.

- **Modo de exibição padrão** : Exibir para exibir por padrão na chegada ao painel / dispositivo móvel.
- **Desdobrar o painel de vista** : Usado para tornar o menu de visualização (à esquerda) visível nas visualizações por padrão.

- **Projeto padrão** : Design a ser exibido por padrão na chegada ao Painel / dispositivo móvel.
- **Design em tela cheia** : Exibição padrão em tela cheia na chegada dos projetos.

- **Design 3D padrão** : Design 3D a ser exibido por padrão ao chegar no Painel / dispositivo móvel.
- **Design 3D em tela cheia** : Exibição padrão em tela cheia na chegada em projetos 3D.

### Notifications

- **Comando de notificação do usuário** : Comando padrão para se juntar a você (comando do tipo de mensagem).

## Guia Segurança

- **Autenticação em duas etapas** : permite configurar a autenticação em 2 etapas (como lembrete, é um código que muda a cada X segundos que é exibido em um aplicativo móvel, digite *google authentificator*). Observe que a autenticação dupla só será solicitada para conexões externas.Para conexões locais, o código não será solicitado.

  **IMPORTANTE** se durante a configuração da autenticação dupla houver um erro, verifique se o Jeedom (consulte a página de integridade) e o telefone estão ao mesmo tempo (1 min de diferença é suficiente para que não funcione).

- **Senha** : Permite alterar sua senha (não esqueça de redigitá-la abaixo).

- **Hash Usuário** : Sua chave de API do usuário.

### Sessões ativas

Aqui você tem a lista de suas sessões atualmente conectadas, seu ID, seu IP e a data da última comunicação. Ao clicar em "Desconectar", isso desconectará o usuário. Atenção, se estiver em um dispositivo registrado, isso também excluirá a gravação.

### Dispositivo registrado

Aqui você encontra a lista de todos os dispositivos registrados (que se conectam sem autenticação) ao seu Jeedom, bem como a data do último uso.
Aqui você pode excluir o registro de um dispositivo. Atenção, ele não o desconecta, mas apenas impede sua reconexão automática.
