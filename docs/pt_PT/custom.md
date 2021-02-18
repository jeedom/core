# A personalização avançada
**Configurações → Sistema → Personalização avançada**

Esta página, reservada para especialistas, permite adicionar scripts CSS ou JS ao Jeedom, que serão executados em cada página.

Você pode adicionar suas próprias funções JS e adicionar ou modificar classes CSS.

As duas partes, JS e CSS, são diferenciadas de acordo com a exibição para computador ou celular.

## Ressources

[CSS: Folhas de estilo em cascata](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Dicas para personalizar a interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## Em caso de problema

Injetar JS e / ou CSS pode tornar o Jeedom inoperante.

Nesse caso, duas soluções:

- Abra um navegador no modo de recuperação : `IP / index.php?rescue=1`
- Conecte-se no SSH e exclua os arquivos de personalização : `desktop / custopn` e` mobile / custom`

