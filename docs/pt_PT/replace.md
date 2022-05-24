# Remplacer
**Ferramentas → Substituir**

Esta ferramenta permite substituir rapidamente equipamentos e comandos, por exemplo no caso de mudança de plugin, ou de um módulo.

Assim como as opções de substituição na configuração avançada de um comando, ele substitui os comandos nos cenários e outros, mas também permite transferir as propriedades do equipamento e os comandos.

## Filtres

Você pode exibir apenas determinados equipamentos para maior legibilidade, filtrando por objeto ou por plugin.

> No caso de substituição de equipamento por equipamento de outro plugin, selecione os dois plugins.

## Options

> **Observação**
>
> Se nenhuma dessas opções estiver marcada, a substituição equivale ao uso da função *Substitua este comando pelo comando* em configuração avançada.

- **Copie a configuração dos dispositivos de origem** :
Para cada equipamento, será copiado da fonte para o destino:
	* O objeto pai
	* A propriedade *visível*
	* A propriedade *de ativos*
	* Encomenda (Painel)
	* A largura e a altura (Painel de mosaico)
	* Parâmetros de curva de bloco
	* Parâmetros opcionais
	* A configuração de exibição da tabela
	* o tipo genérico
	* As categorias
	* Comentários e etiquetas
	* A propriedade *tempo esgotado*
	* A configuração *atualização automática*
Este equipamento também será substituído pelo equipamento alvo em Desenhos e Vistas.

- **Ocultar dispositivos de origem** : Permite tornar o equipamento de origem invisível uma vez substituído pelo equipamento de destino.
- **Copiar histórico de comandos de origem** : Copie o histórico do comando de origem para o comando de destino.
- **Copie a configuração dos comandos de origem** :
Para cada comando, será copiado da origem para o destino:
	* A propriedade *visível*
	* Encomenda (Painel)
	* L'historisation
	* Os widgets Dashboard e Mobile usados
	* O tipo genérico
	* Parâmetros opcionais
	* As configurações *jeedomPreExecCmd* e *jeedomPostExecCmd* (ação)
	* Configurações de ação de valor (informações)
	* ícone
	* A ativação e o diretório em *Linha do tempo*
	* As configurações de *Cálculo* e *redondo*
	* A configuração do valor de repetição


## Remplacements

O botão **Filtro** No canto superior direito permite visualizar todos os equipamentos, seguindo os filtros por objeto e por plugin.

Para cada equipamento :

- Marque a caixa no início da linha para ativar sua substituição.
- Selecione à direita o equipamento pelo qual será substituído.
- Clique em seu nome para ver seus comandos e indique quais comandos os substituem. Ao escolher um equipamento, a ferramenta preenche essas opções se encontrar um pedido do mesmo tipo e mesmo nome no equipamento de destino.


> **Observação**
>
> Quando você indica um dispositivo de destino em um dispositivo de origem, esse dispositivo de destino é desabilitado na lista.
