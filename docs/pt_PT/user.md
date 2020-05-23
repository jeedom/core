É aqui que poderemos definir a lista de usuários
permissão para se conectar ao Jeedom, mas também seus direitos
d'administrateur

Acessível pela administração → Usuários.

No canto superior direito, você tem um botão para adicionar um usuário, um
para salvar e um botão para abrir um acesso ao suporte.

Abaixo você tem uma tabela :

-   **Nome de Usuário** : ID do usuário

-   **Ativos** : permite desativar a conta

-   **Apenas local** : autorizar login do usuário
    somente se estiver na rede local Jeedom

-   **Perfis** : Permite escolher o perfil do usuário :

    -   **Administrador** : obtém todos os direitos sobre Jeedom

    -   **Usuário** : pode ver o painel, visualizações,
        design, etc. e atuar em equipamentos / controles. No entanto,
        ele não terá acesso à configuração de controles / equipamentos
        nem para a configuração do Jeedom.

    -   **Usuário limitado** : o usuário vê apenas o
        equipamento autorizado (configurável com o botão "Gerenciar"
        direitos")

-   **Chave de API** : Chave de API pessoal do usuário

-   **Autenticação dupla** : indica se a autenticação dupla
    está ativo (OK) ou não (NOK)

-   **Data da última conexão** : data da última conexão de
    o usuário na Jeedom. Observe que esta é a data da conexão
    real, portanto, se você salvar seu computador, a data de
    a conexão não é atualizada toda vez que você volta a ela.

-   **Alterar senha** : permite alterar a senha de
    l'utilisateur

-   **Remover** : Remover usuário

-   **Regenerar chave de API** : Regenerar chave de API do usuário

-   **Gerenciar direitos** : permite gerenciar com precisão os direitos de
    usuário (atenção os perfis devem estar em
    "Usuário limitado")

Gerenciamento de direitos 
==================

Ao clicar em "Gerenciar direitos", uma janela é exibida, permitindo que você
gerenciar com precisão os direitos do usuário. A primeira guia exibe
o equipamento diferente. O segundo apresenta os cenários.

> **IMPORTANTE**
>
> O perfil deve ser limitado, caso contrário, nenhuma restrição será colocada aqui
> será levado em consideração

Você obtém uma tabela que permite, para cada equipamento e cada
cenário, defina direitos do usuário :

-   **Nemhum** : o usuário não vê o equipamento / cenário

-   **Visualização** : o usuário vê o equipamento / cenário, mas não vê
    não pode agir sobre isso

-   **Visualização e execução** : o usuário vê
    equipamento / cenário e pode atuar sobre ele (acenda uma lâmpada, jogue
    o script etc.)


