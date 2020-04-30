# Interações
****

.

 :

-  : .
-  : .
-  : . .
-  : . .

.

> **Tip**
>
>  :
> - .
> - Ctrl Clic ou Clic Center para abri-lo em uma nova guia do navegador.

. A tecla Escape cancela a pesquisa.
À direita do campo de pesquisa, três botões encontrados em vários lugares no Jeedom:
- A cruz para cancelar a pesquisa.
- .
- A pasta fechada para dobrar todos os painéis.

. .

## Interações

 :

- **Ajouter** : .
- **Regenerar** : Recréer toutes les interactions (peut être très long &gt; 5mn).
- **Tester** : .

> **Tip**
>
> .

## Princípio

 : .

.

.

## Configuração

 :

- **Phrases** : .
- **Enregistrer** : .
- **Supprimer** : .
- **Dupliquer** : .

### Guia Geral

- **Nom** : .
- **Groupe** : .
- **Actif** : .
- **Demande** : .
- **Synonyme** : .
- **Réponse ** : .
- **Aguarde antes de responder (s)** : . .
- **Conversão binária** : .
- **Usuários autorizados** :  |).

### 

- **Limite para digitar comandos** : .
- **Limite para os comandos que o subtipo** : .
- **Limite para os comandos dessa unidade** : .
- **** : .
- **** : .
- **** : .
- **Equipamentos limite** : .

### 

.

#### Exemplos

> **Note**
>
> .

#### 

. .

. .

![interact004](../images/interact004.png)

. .

 :

![interact005](../images/interact005.png)

.

> **Tip**
>
> .

#### 

.

 **\#commande\#** e **\#equipement\#**. .

![interact006](../images/interact006.png)

. .

. . .

![interact007](../images/interact007.png)

. ". .

 :

![interact008](../images/interact008.png)

. ". *" ***=*** "**"***,*** "*.  "*|*" .

. . .

![interact009](../images/interact009.png)

". . .

. . .

![interact010](../images/interact010.png)

. .

### 

. . .

.  : .

![interact011](../images/interact011.png)

.

![interact012](../images/interact012.png)

### Conversão binária

. .

![interact013](../images/interact013.png)

. .  **** .

 **Conversão binária**  :  "|" . .

> **Warning**
>
> .

### Usuários autorizados

 "|".

Exemplo : |personne2

.

### Exclusão regexp

 [](https://fr.wikipedia.org/wiki/Expression_rationnelle) . .

 :
- ".
- ".

. . .

. . .

. . .

![interact014](../images/interact014.png)

. . .

 :

- .
- .
- .
- .

".

. .  :

/\^(https?:??

.

![interact015](../images/interact015.png)

".

![interact016](../images/interact016.png)

.

. .

 :

- <http://www.commentcamarche.net/contents/585-javascript-l-objet-regexp>
- <https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>
- <https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/les-expressions-regulieres-partie-1-2>

### 

.

![interact021](../images/interact021.png)

.

###  ?

#### 

- "
- "
- #"

![interact017](../images/interact017.png)

. *|*"

#### 

-  |#"
- "
- 
- ".

![interact018](../images/interact018.png)

. .

### 

#### 

. .

.

![interact019](../images/interact019.png)

-  : " | | . .
- ".  : 
- .

#### 

.

Adicionando um sinônimo, digamos ao Jeedom que um comando chamado "X" também pode ser chamado de "Y" e, portanto, em nossa sentença, se tivermos "ativado y", o Jeedom sabe que está ativado x. Este método é muito conveniente para renomear nomes de comandos que, quando exibidos na tela, são escritos de maneira não natural, vocalmente ou em uma frase escrita como "ON"". Um botão escrito assim é completamente lógico, mas não no contexto de uma frase.

Também podemos adicionar um filtro  para remover alguns comandos. Usando o exemplo simples, vemos as frases "bateria" ou "latência", que nada têm a ver com nossa interação temperatura / umidade / luz.

![interact020](../images/interact020.png)

Então podemos ver uma regexp :

**(batterie|latence|pression|vitesse|consommation)**

Isso permite que você exclua todos os comandos que possuem uma dessas palavras em suas frases

> **Note**
>
> O regexp aqui é uma versão simplificada para fácil utilização. Portanto, podemos usar expressões tradicionais ou expressões simplificadas, como neste exemplo.

### Controlar um dimmer ou um termostato (controle deslizante)

#### 

É possível controlar uma lâmpada como uma porcentagem (dimmer) ou um termostato com as interações. Aqui está um exemplo para controlar seu dimmer em uma lâmpada com interações :

![interact022](../images/interact022.png)

Como podemos ver, existe aqui no pedido a tag **\#consigne\#** (você pode colocar o que deseja), que é usado no controle do inversor para aplicar o valor desejado. Para fazer isso, temos 3 partes : \* Solicitação : em que criamos uma tag que representará o valor que será enviado para a interação. \* Resposta : reutilizamos a tag da resposta para garantir que Jeedom entendeu corretamente a solicitação. \* Ação : colocamos uma ação na lâmpada que queremos acionar e no valor passamos nossa instrução tag **.

> **Note**
>
> Podemos usar qualquer tag, exceto as já usadas pelo Jeedom, pode haver várias para controlar, por exemplo, vários comandos. Observe também que todas as tags são passadas para os cenários iniciados pela interação (no entanto, é necessário que o cenário esteja em "Executar em primeiro plano").

#### 

Podemos querer controlar todos os comandos de tipo de cursor com uma única interação. Com o exemplo a seguir, poderemos controlar várias unidades com uma única interação e, portanto, gerar um conjunto de sentenças para controlá-las..

![interact033](../images/interact033.png)

Nesta interação, não temos comando na parte de ação, deixamos o Jeedom gerar a partir de tags a lista de frases. Podemos ver a tag **\#slider\#**. É imperativo usar essa tag para obter instruções em um comando de interação múltipla, que pode não ser a última palavra da frase. Também podemos ver no exemplo que podemos usar na resposta uma tag que não faz parte da solicitação. A maioria das tags disponíveis nos cenários também está disponível nas interações e, portanto, pode ser usada em uma resposta.

Resultado da interação :

![interact034](../images/interact034.png)

Podemos ver que a tag **\#equipement\#** que não é usado na solicitação está bem concluído na resposta.

### Controlar a cor de uma faixa de LED

É possível controlar um comando de cor pelas interações, pedindo ao Jeedom, por exemplo, para acender uma faixa de LED azul. Esta é a interação a ser feita :

![interact023](../images/interact023.png)

Até agora nada complicado, no entanto, você deve ter configurado as cores no Jeedom para que funcione; vá ao menu → Configuração (canto superior direito) e depois na seção "Configuração das interações"" :

![interact024](../images/interact024.png)

Como podemos ver na captura de tela, não há cores configuradas, então você deve adicionar cores com o "+" à direita. O nome da cor, é o nome que você passará para a interação e, na parte direita (coluna "código HTML"), clicando na cor preta, podemos escolher uma nova cor.

![interact025](../images/interact025.png)

Podemos adicionar quantos quisermos, podemos colocar qualquer nome como qualquer outro, para que possamos imaginar atribuir uma cor ao nome de cada membro da família.

Uma vez configurado, você diz "Ilumine a árvore em verde", o Jeedom pesquisará na solicitação uma cor e aplicará à ordem.
### Use juntamente com um cenário

#### 

É possível acoplar uma interação a um cenário para executar ações um pouco mais complexas do que a execução de uma ação simples ou uma solicitação de informações..

![interact026](../images/interact026.png)

Este exemplo, portanto, permite iniciar o cenário que está vinculado na parte da ação; é claro que podemos ter vários.

### Programando uma ação com interações

As interações fazem muitas coisas em particular. Você pode programar uma ação dinamicamente. Exemplo : "Liga o calor às 22 para 14:50". Para que nada mais simples, basta usar as tags \#time \# (se definir uma hora precisa) ou \#duration \# (pois no X tempo, por exemplo, em 1 hora) :

![interact23](../images/interact23.JPG)

> **Note**
>
> Você notará na resposta que a tag \#value \# que contém, no caso de uma interação agendada, o tempo efetivo de programação
