# A personalização avançada
**Configurações → Sistema → Personalização avançada**

Aqui você pode gerenciar funções **JavaScript** e regras **CSS** aplicado em desktop ou celular.

> **Aviso**
>
> O uso de regras CSS inadequadas pode interromper a exibição do seu Jeedom. Funções js usadas incorretamente podem causar danos significativos a vários componentes de sua instalação. Lembre-se de gerar e terceirizar um backup antes de usar essas funções.

Esta função usa um modo particular do editor de arquivos Core com dois locais:

- desktop / custom : Pode conter os dois arquivos **custom.js** e **CSS customizado** que será carregado pelo Core na versão Desktop.
- móvel / personalizado : Pode conter os dois arquivos **custom.js** e **CSS customizado** que será carregado pelo Core na versão Mobile.

Na barra de menu do editor de arquivos Core, um botão **Habilitado** Onde **Desativado** diz se o Core deve carregá-los ou não. Esta opção também está disponível em **Configurações → Sistema → Configuração** Guia Interface.

> **Observação**
>
> Quando esta página é lançada, a estrutura em árvore é criada automaticamente, assim como os 4 arquivos com um comentário na 1ª linha incluindo a versão do Core que os criou.

## Ressources

[CSS: Folhas de estilo em cascata](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Dicas para personalizar a interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## Em caso de problema

Injetar JS e / ou CSS pode tornar o Jeedom inoperante.

Nesse caso, duas soluções:

- Abra um navegador no modo de recuperação : `IP / index.php?rescue=1`
- Conecte-se no SSH e exclua os arquivos de personalização : `desktop / custom` e` mobile / custom`

