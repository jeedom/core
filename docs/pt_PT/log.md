# Logs
**Análise → Logs**

Logs são arquivos de log, permitindo que você siga o que está acontecendo em sua automação residencial. Na maioria dos casos, os logs serão usados apenas para depuração e solução de problemas pela equipe de suporte.

> **Dica**
>
> Quando a página é aberta, o primeiro log disponível é exibido.

A página Logs é bastante simples :
À esquerda, uma lista de logs disponíveis, com um campo de pesquisa para filtrar o nome dos logs.
5 botões superior direito :

- **Pesquisa** : Permite filtrar a exibição do log atual.
- **Pausar / retomar** : Pausar / retomar a atualização em tempo real do log atual.
- **Baixar** : Faça o download do log atual.
- **Vazio** : Limpe o log atual.
- **Remover** : Excluir o log atual. Se o Jeedom precisar, ele será recriado automaticamente.
- **Excluir todos os logs** : Excluir todos os logs presentes.

> **Dica**
>
> Observe que o log http.erro não pode ser excluído. É essencial que se você o excluir (na linha de comando, por exemplo), ele não será recriado, você deverá reiniciar o sistema.

## Tempo real

O log "Evento" é um pouco especial. Primeiro, para que ele funcione, ele deve estar no nível de informações ou depuração, depois lista todos os eventos ou ações que acontecem na automação residencial. Para acessá-lo, você deve ir para a página de log ou em Análise → Tempo real.

Depois de clicar nele, você obtém uma janela que é atualizada em tempo real e mostra todos os eventos da sua automação residencial.

No canto superior direito, você tem um campo de pesquisa (funciona apenas se não estiver em pausa) e um botão para pausar (útil para copiar / colar, por exemplo)).
