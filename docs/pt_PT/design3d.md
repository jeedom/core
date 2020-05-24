# Projeto 3D
**Página inicial → Design3D**

Esta página permite criar uma visualização em 3D da sua casa que pode reagir dependendo do estado das várias informações na sua automação residencial.


> **Dica**
>
> É possível ir diretamente para um design 3D graças ao submenu.

## Importando o modelo 3D

> **IMPORTANTE**
>
> Você não pode criar seu modelo 3D diretamente no Jeedom, isso deve ser feito usando software de terceiros. Nous recommandons le très bon SweetHome3d (http://www.sweethome3d.com/fr/).

Depois que seu modelo 3D for criado, ele deverá ser exportado no formato OBJ. Se você usa o SweetHome3d, isso é feito no menu "Visualização em 3D" e, em seguida, "Exportar para o formato OBJ". Em seguida, pegue todos os arquivos gerados e coloque-os em um arquivo zip (pode haver muitos arquivos devido às texturas).

> **IMPORTANTE**
>
> Os arquivos devem estar na raiz do zip e não em uma subpasta.

> **Atenção**
>
> Um modelo 3D é bastante imponente (isso pode representar várias centenas de Mo). Quanto maior, maior o tempo de renderização no Jeedom.

Após a exportação do seu modelo 3D, você deve criar um novo design 3D no Jeedom. Para isso, é necessário entrar no modo de edição, clicando no pequeno lápis à direita, depois clique no +, dê um nome a este novo design 3D e valide.

Jeedom mudará automaticamente para o novo design 3D, você deve retornar ao modo de edição e clicar nas pequenas rodas dentadas.

Você pode nessa tela :

- Mude o nome do seu design
- Adicione um código de acesso
- Escolha ícone
- Importe seu modelo 3D

Clique no botão "enviar" no nível "Modelo 3D" e selecione seu arquivo zip

> **Atenção**
>
> Jeedom autoriza a importação de um arquivo de 150mo no máximo !

> **Atenção**
>
> Você deve ter um arquivo zip.

> **Dica**
>
> Depois que o arquivo for importado (pode ser bastante longo, dependendo do tamanho do arquivo), você precisará atualizar a página para ver o resultado (F5).


## Configuração de elementos

> **IMPORTANTE**
>
> A configuração só pode ser feita no modo de edição.

Para configurar um elemento no design 3D, clique duas vezes no elemento que você deseja configurar. Isso exibirá uma janela onde você pode :

- Indique um tipo de link (atualmente apenas o equipamento existe)
- Digite o link para o elemento em questão. Aqui você só pode colocar um link para um dispositivo no momento. Isso permite ao clicar no item para abrir o equipamento
- Definir especificidade: existem vários que veremos logo depois, isso permite especificar o tipo de equipamento e, portanto, a exibição de informações

### Luz

- Estado : O controle do status da luz pode ser binário (0 ou 1), digital (0 a 100%) ou colorido
- Poder : potência da lâmpada (observe que isso pode não refletir a realidade))

### Texte

- Texto : texto a ser exibido (você pode colocar comandos lá, o texto será atualizado automaticamente quando for alterado)
- Tamanho do texto
- Cor do texto
- Transparência texto : de 0 (invisível) a 1 (visível)
- Cor de fundo
- Transparência em segundo plano : de 0 (invisível) a 1 (visível)
- Cor fronteira
- Transparência nas fronteiras : de 0 (invisível) a 1 (visível)
- Espaço acima do objeto : permite indicar o espaçamento do texto comparado ao elemento

### Porta / janela

#### Porta / janela

- Estado : Status da porta / janela, 1 fechado e 0 aberto
- Rotation
	- Ativar : ativa a rotação da porta / janela ao abrir
	- Abertura : o melhor é testar para que ele corresponda à sua porta / janela
- Translation
	- Ativar : ativa a tradução ao abrir (porta deslizante / janela))
	- Significado : direção na qual a porta / janela deve se mover (você tem para cima / baixo / direita / esquerda)
	- Repetir : por padrão, a porta / janela se move uma vez sua dimensão na direção especificada, mas você pode aumentar esse valor
- Ocultar quando a porta / janela está aberta
	- Ativar : Oculta o elemento se a Porta / Janela estiver aberta
- Couleur
	- Cor aberta : se assinalar, o elemento assumirá esta cor se a porta / janela estiver aberta
	- Cor fechada : se marque o elemento terá esta cor se a porta / janela estiver fechada

#### Volet

- Estado : status do obturador, 0 aberto outro valor fechado
- Ocultar quando o obturador estiver aberto
	- Ativar : ocultar o elemento se o obturador estiver aberto
- Couleur
	- Cor fechada : se marque o elemento terá esta cor se o obturador estiver fechado

### Cor condicional

Se a condição for válida, permite atribuir a cor escolhida ao elemento. Você pode colocar quantas cores / condições desejar.

> **Dica**
>
> As condições são avaliadas em ordem; a primeira, verdadeira, será adotada; as seguintes, portanto, não serão avaliadas
