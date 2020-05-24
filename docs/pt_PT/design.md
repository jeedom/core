# Design
**Página inicial → Design**

Esta página permite configurar a exibição de toda a sua automação residencial de uma maneira muito fina.
Leva tempo, mas seu único limite é a sua imaginação.

> **Dica**
>
> É possível ir diretamente a um design graças ao submenu.

> **IMPORTANTE**
>
> Todas as ações são executadas clicando com o botão direito do mouse nesta página, tenha cuidado em fazê-lo bem no design. Ao criar, você deve fazê-lo no meio da página (para ter certeza de estar no design).

No menu (clique direito), encontramos as seguintes ações :

- **Projetos** : Exibe a lista de seus designs e permite que você os acesse.
- **Edição** : Mudar para o modo de edição.
- **Tela cheia** : Permite usar a página da Web inteira, o que removerá o menu Jeedom da parte superior.
- **Adicionar gráfico** : Adicionar um gráfico.
- **Adicionar text / html** : Permite adicionar texto ou código html / JavaScript.
- **Ajouter Scénario** : Adicionar um cenário.
- **Adicionar link**
    - **Rumo a vista** : Adicionar um link a uma visualização.
    - **Para projetar** : Adicionar um link a outro design.
- **Adicionar equipamento** : Adiciona equipamento.
- **Adicionar comando** : Adicionar um pedido.
- **Adicionar imagem / câmera** : Permite adicionar uma imagem ou um fluxo de uma câmera.
- **Adicionar área** : Permite adicionar uma zona transparente clicável que poderá executar uma série de ações durante um clique (dependendo ou não do status de outro comando).
- **Adicionar resumo** : Adiciona informações de um objeto ou resumo geral.
- **Visualizando**
    - **Não** : Não exibe nenhuma grade.
    - **10x10** : Exibe uma grade 10 por 10.
    - **15x15** : Exibe uma grade de 15 por 15.
    - **20x20** : Exibe uma grade de 20 por 20.
    - **Magnetizar os elementos** : Adiciona magnetização entre os elementos para facilitar a aderência deles.
    - **Alinhar à grade** : Adicione uma magnetização dos elementos à grade (atenção : dependendo do zoom do elemento, essa funcionalidade pode mais ou menos funcionar).
    - **Ocultar destaque do item** : Ocultar realce em torno dos itens.
- **Limpar projeto** : Remover desenho.
- **Criar um design** : Permite adicionar um novo design.
- **Projeto duplicado** : Duplica o design atual.
- **Cenografia** : Acesso à configuração do design.
- **Salvar** : Salve o design (observe, também há backups automáticos durante determinadas ações).

> **IMPORTANTE**
>
> A configuração dos elementos de design é feita com um clique neles.

## Configuração do projeto

Encontrado aqui :

- **Geral**
    - **Nome** : O nome do seu design.
    - **Posição** : A posição do design no menu. Permite que você encomende os desenhos.
    - **Fundo transparente** : Torna o plano de fundo transparente. Atenção, se a caixa estiver marcada, a cor do plano de fundo não será usada.
    - **Cor de fundo** : Cor do plano de fundo do design.
    - **Código de acesso* : Código de acesso ao seu design (se vazio, nenhum código é necessário).
    - **ícone** : Um ícone para ele (aparece no menu de opções de design).
    - **Imagem**
        - **Enviar** : Permite adicionar uma imagem de plano de fundo ao design.
        - **Excluir imagem** : Excluir imagem.
- **Tamanhos**
    - **Tamanho (LxA))** : Permite definir o tamanho em pixels do seu design.

## Configuração geral de elementos

> **NOTA**
>
> Dependendo do tipo de item, as opções podem mudar.

### Configurações comuns de exibição

- **Profundidade** : Permite escolher o nível de profundidade
- **Posição X (%)** : Coordenada horizontal do elemento.
- **Posição Y (%)** : Coordenada vertical do elemento.
- **Largura (px)** : Largura do elemento em pixels.
- **Altura (px)** : Altura do elemento em pixels.

### Supprimer

Remover item

### Dupliquer

Permite duplicar o elemento

### Verrouiller

Permite bloquear o elemento para que ele não seja mais móvel ou redimensionável.

## Graphique

### Configurações de exibição específicas

- **Período** : Permite escolher o período de exibição
- **Mostrar legenda** : Exibe a legenda.
- **Mostrar Navigator** : Exibe o navegador (segundo gráfico mais claro abaixo do primeiro).
- **Ver a selecção tempo** : Exibe o seletor de período no canto superior esquerdo.
- **Mostrar barra de rolagem** : Exibe a barra de rolagem.
- **Fundo transparente** : Torna o plano de fundo transparente.
- **Fronteira** : Permite adicionar uma borda, tenha cuidado, a sintaxe é HTML (tenha cuidado, você deve usar a sintaxe CSS, por exemplo : sólido 1px preto).

### Configuração avançada

Permite escolher os comandos para grapher.

## Texto / html

### Configurações de exibição específicas

- **ícone** : Ícone exibido na frente do nome do design.
- **Cor de fundo** : permite alterar a cor do plano de fundo ou torná-la transparente; não se esqueça de alterar "Padrão" para NÃO.
- **Cor do texto** : permite alterar a cor dos ícones e textos (tenha cuidado para definir Padrão como Não)..
- **Arredonde os ângulos** : permite arredondar os ângulos (não esqueça de colocar%, ex 50%).
- **Fronteira** : permite adicionar uma borda, cuidado com a sintaxe é HTML (você deve usar a sintaxe CSS, por exemplo : sólido 1px preto).
- **Tamanho da fonte** : permite alterar o tamanho da fonte (ex 50%, você deve colocar o sinal de%).
- **Alinhamento de texto** : permite escolher o alinhamento do texto (esquerda / direita / centralizado)).
- **Gordura** : texto em negrito.
- **Texto** : Texto em código HTML que estará no elemento.

> **IMPORTANTE**
>
> Se você colocar o código HTML (especialmente o Javascript), verifique-o antes, pois pode ocorrer se houver algum erro ou substituir um componente Jeedom que trava completamente o design e ele só precisará excluí-lo diretamente no banco de dados.

## Cenas

*Nenhuma configuração de exibição específica*

## Lien

### Configurações de exibição específicas

- **Nome** : Nome do link (texto exibido).
- **Link** : Link para o design ou exibição em questão.
- **Cor de fundo** : Permite alterar a cor do plano de fundo ou torná-la transparente; não se esqueça de alterar "Padrão" para NÃO.
- **Cor do texto** : Permite alterar a cor dos ícones e textos (tenha cuidado para definir Padrão como Não).
- **Arredonde os ângulos (não esqueça de colocar%, ex 50%)** : Permite arredondar os ângulos, não se esqueça de colocar o%.
- **Fronteira (atenção sintaxe CSS, ex : sólido 1px preto)** : Permite adicionar uma borda, cuidado com a sintaxe é HTML.
- **Tamanho da fonte (ex 50%, você deve colocar o sinal de%)** : Permite alterar o tamanho da fonte.
- **Alinhamento de texto** : Permite escolher o alinhamento do texto (esquerda / direita / centralizado)).
- **Gordura** : Texto em negrito.

## Equipement

### Configurações de exibição específicas

- **Do nome de exibição do objeto** : Marque para exibir o nome do objeto pai do dispositivo.
- **Ocultar nome** : Marque para ocultar o nome do equipamento.
- **Cor de fundo** : Permite escolher uma cor de fundo personalizada, exibir o equipamento com um fundo transparente ou usar a cor padrão.
- **Cor do texto** : Permite escolher uma cor de plano de fundo personalizada ou usar a cor padrão.
- **Arredondado** : Valor em pixels do arredondamento dos ângulos do bloco do equipamento.
- **Fronteira** : Definição CSS de uma borda de bloco de equipamento. Ex : 1px preto sólido.
- **Opacidade** : Opacidade do ladrilho do equipamento, entre 0 e 1. Atenção : uma cor de fundo deve ser definida.
- **CSS personalizado** : Regras CSS a serem aplicadas no equipamento.
- **Aplicar css personalizado em** : Seletor de CSS no qual aplicar CSS personalizado.

### Commandes

A lista de comandos presentes no equipamento permite que, para cada comando, você:
- Ocultar nome do comando.
- Ocultar comando.
- Exibir o pedido com um plano de fundo transparente.

### Configuração avançada

Exibe a janela de configuração avançada do equipamento (consulte a documentação **Resumo Automation**).

## Commande

*Nenhuma configuração de exibição específica*

### Configuração avançada

Exibe a janela de configuração avançada do equipamento (consulte a documentação **Resumo Automation**).

## Imagem / Câmera

### Configurações de exibição específicas

- **Display** : Define o que você deseja exibir, imagem estática ou transmitir a partir de uma câmera.
- **Imagem** : Envie a imagem em questão (se você escolheu uma imagem).
- **Câmera** : Câmera a ser exibida (se você escolher a câmera).

## Zone

### Configurações de exibição específicas

- **Sala de jantar** : É aqui que você escolhe o tipo de área : Macro simples, macro binária ou Widget ao passar o mouse.

### único macro

Nesse modo, um clique na zona executa uma ou mais ações. Aqui você só precisa indicar a lista de ações a serem executadas ao clicar na área.

### Macro binário

Nesse modo, o Jeedom executará as ações Ativar ou Desativar, dependendo do status do comando que você indicar. Ex : se o comando vale 0, o Jeedom executará as ações On, caso contrário, executará as ações Off

- **Informação binária** : Comando que fornece o status para verificar para decidir qual ação executar (Ativado ou Desativado).

Você apenas tem que colocar as ações a fazer para o On e para o Off.

### Viaduto widget

Nesse modo, ao passar o mouse ou clicar na área Jeedom, você exibirá o widget em questão.

- **Equipamento** : Widget a ser exibido ao passar o mouse ou clicar em.
- **Mostrar no viaduto** : Se marcado, exibe o widget em foco.
- **Vista em um clique** : Se marcado, o widget será exibido no clique.
- **Posição** : Permite escolher onde o widget será exibido (por padrão, no canto inferior direito).

## Resumo

### Configurações de exibição específicas

- **Link** : Permite indicar o resumo a ser exibido (Geral para o global, caso contrário, indique o assunto).
- **Cor de fundo** : Permite alterar a cor do plano de fundo ou torná-la transparente; não se esqueça de alterar "Padrão" para NÃO.
- **Cor do texto** : Permite alterar a cor dos ícones e textos (tenha cuidado para definir Padrão como Não).
- **Arredonde os ângulos (não esqueça de colocar%, ex 50%)** : Permite arredondar os ângulos, não se esqueça de colocar o%.
- **Fronteira (atenção sintaxe CSS, ex : sólido 1px preto)** : Permite adicionar uma borda, cuidado com a sintaxe é HTML.
- **Tamanho da fonte (ex 50%, você deve colocar o sinal de%)** : Permite alterar o tamanho da fonte.
- **Gordura** : Texto em negrito.

## FAQ

>**Não consigo mais editar meu design**
>Se você colocou um widget ou uma imagem que ocupa quase todo o design, clique fora do widget ou imagem para acessar o menu clicando com o botão direito do mouse.

>**Excluir um design que não funciona mais**
>Na parte da administração e no OS / DB, faça "select * from planHeader", recupere o ID do design em questão e faça "delete from planHeader where id=#TODO#" e "excluir do plano em que planHeader_id=#todo#" substituindo bem #TODO# pelo ID do design encontrado anteriormente.
