Informa todas as tarefas de aplicativos Jeedom executadas no
garçom. Este menu deve ser usado conscientemente ou no
solicitar suporte técnico.

> **IMPORTANTE**
>
> Em caso de manuseio incorreto nesta página, qualquer solicitação de
> suporte pode ser negado você.

Para acessá-lo, vá para **Administração → Mecanismo de Tarefas**
:

# Cron

No canto superior direito, você tem :

-   **Desativar o sistema cron** : um botão para desativar ou
    reative todas as tarefas (se você desabilitar todas, mais
    nada funcionará no seu Jeedom)

-   **Legal** : um botão para atualizar a tabela de tarefas

-   **Adicionar** : um botão para adicionar um trabalho cron

-   **Registro** : um botão para salvar suas alterações.

Abaixo você tem a tabela de todas as tarefas existentes
(tenha cuidado, algumas tarefas podem iniciar subtarefas, por isso é
É altamente recomendável nunca modificar informações sobre este
página). Nesta tabela, encontramos :

-   **\#** : ID da tarefa, pode ser útil para vincular um
    processo em execução e o que realmente faz

-   **Ação** : um botão para iniciar ou parar a tarefa em função
    seu status e um botão para visualizar o cron em detalhes (conforme armazenado no banco de dados)

-   **Ativos** : indica se a tarefa está ativa (pode ser iniciada
    por Jeedom) ou não

-   **PID** : Indica o ID do processo atual

-   **Demônio** : se esta caixa for "sim", a tarefa deve sempre
    estar em progresso. Em seguida, você encontra a frequência do demônio, é
    aconselhado a nunca tocar nesse valor e, especialmente, nunca
    diminua

-   **único** : se for "sim", a tarefa será iniciada uma vez
    então excluirá

-   **Class** : Classe PHP chamada para executar a tarefa (pode
    estar vazio)

-   **Função** : Função PHP chamada na classe chamada (ou não
    se a turma estiver vazia)

-   **Programação** : programando a tarefa no formato CRON

-   **Tempo limite** : Tempo máximo de execução da tarefa. Se o
    Se a tarefa for um demônio, ela será automaticamente interrompida e
    reiniciado no final do tempo limite

-   **último lançamento** : Data do último lançamento da tarefa

-   **Última duração** : última vez para concluir a tarefa (um
    demon sempre estará no zero, então não se preocupe com outras tarefas
    pode estar em 0s)

-   **Estado** : status atual da tarefa (como lembrete, uma tarefa daemon
    ainda está em "run")

-   **Remoção** : Excluir tarefa


# Listener

Os ouvintes são apenas visíveis na leitura e permitem que você veja as funções chamadas em um evento (atualização de um comando...)
