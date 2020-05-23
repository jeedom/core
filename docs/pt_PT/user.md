# Utilisateurs
**Configurações → Sistema → Usuários**

Esta página permite definir a lista de usuários autorizados a se conectar ao Jeedom, bem como seus direitos de administrador.

Na página você tem três botões :

- Adicionar Usuário.
- Salvar.
- Acesso aberto ao suporte.

## Lista de usuários

- **Nome de Usuário** : ID do usuário.
- **Ativos** : Permite desativar a conta sem excluí-la.
- **Local** : Permite a conexão do usuário apenas se ele estiver na rede local Jeedom.
- **Perfil** : Permite escolher o perfil do usuário :
    - **Administrador** : O usuário obtém todos os direitos (edição / consulta) no Jeedom.
    - **Usuário** : O usuário pode ver Painel, visualizações, designs etc. e atuar em equipamentos / controles. No entanto, ele não terá acesso à configuração dos controles / equipamentos nem à configuração do Jeedom.
    - **Usuário limitado** : O usuário vê apenas o equipamento autorizado (configurável com o botão "Direitos"").
- **Chave de API** : Chave de API pessoal do usuário.
- **Autenticação dupla** : Indica se a autenticação dupla está ativa (OK) ou não (NOK).
- **Data da última conexão** : Data do último login do usuário. Observe que esta é a data real da conexão; portanto, se você salvar seu computador, a data da conexão não será atualizada sempre que você retornar.
- **Direitos** : Modificar direitos de usuário.
- **Senha** : Permite alterar a senha do usuário.
- **Remover** : Remover usuário.
- **Regenerar chave de API** : Regenerar chave de API do usuário.
- **Gerenciar direitos** : Permite gerenciar com precisão os direitos do usuário (observe que o perfil deve estar em "usuário limitado"").

## Gerenciamento de direitos

Ao clicar em "Direitos", uma janela é exibida, permitindo que você gerencie os direitos do usuário com precisão. A primeira guia exibe os diferentes equipamentos. O segundo apresenta os cenários.

> **IMPORTANTE**
>
> O perfil deve ser limitado, caso contrário, nenhuma restrição colocada aqui será levada em consideração.

Você obtém uma tabela que permite, para cada dispositivo e cada cenário, definir os direitos do usuário :
- **Nemhum** : o usuário não vê o equipamento / cenário.
- **Visualização** : o usuário vê o equipamento / cenário, mas não pode agir sobre ele.
- **Visualização e execução** : o usuário vê o equipamento / cenário e pode agir sobre ele (acender uma lâmpada, iniciar o cenário etc.)).

## Sessões ativas))

Exibe as sessões do navegador ativas no seu Jeedom, com informações do usuário, seu IP e desde quando. Você pode desconectar o usuário usando o botão **Desligar**.

## Dispositivo (s) registrado (s))

Liste os periféricos (computadores, celulares etc.) que registraram sua autenticação no seu Jeedom.
Você pode ver qual usuário, seu IP, quando e excluir o registro deste dispositivo.

> **NOTA**
>
> O mesmo usuário pode ter registrado dispositivos diferentes. Por exemplo, seu computador desktop, laptop, celular etc.







