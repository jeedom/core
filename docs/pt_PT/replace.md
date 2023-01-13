 # Remplacer
**Ferramentas → Substituir**

Esta ferramenta permite substituir rapidamente equipamentos e comandos, por exemplo no caso de mudança de plugin, ou de um módulo.

Assim como as opções de substituição na configuração avançada de um comando, ele substitui os comandos nos cenários e outros, mas também permite transferir as propriedades do equipamento e os comandos.

## Filtres

Você pode exibir apenas determinados equipamentos para maior legibilidade, filtrando por objeto ou por plugin.

> No caso de substituição de equipamento por equipamento de outro plugin, selecione os dois plugins.

## Options

> ****
>
> Se nenhuma dessas opções estiver marcada, a substituição equivale ao uso da função *Substitua este comando pelo comando* em configuração avançada.

- **Copiar configuração do dispositivo de origem** :
Para cada equipamento, será copiado da fonte para o destino (lista não exaustiva) :
	* O objeto pai,
	* As categorias,
	* Propriedades **  **,
	* Comentários e etiquetas,
	* Encomenda (Painel),
	* A largura e a altura (painel de ladrilhos),
	* Configurações de curva de ladrilho,
	* Parâmetros opcionais,
	* A configuração de exibição da tabela,
	* o tipo genérico,
	* A propriedade **
	* A configuração **,
	* Alertas de bateria e comunicação,

O equipamento de origem também será substituído pelo equipamento de destino no **** e a ****.


*Este equipamento também será substituído pelo equipamento alvo em Desenhos e Vistas.*

- **Ocultar equipamento de origem** : Permite tornar o equipamento de origem invisível uma vez substituído pelo equipamento de destino.

- **Copiar configuração do comando de origem** :
Para cada pedido, será copiado da origem para o destino (lista não exaustiva) :
	* A propriedade **,
	* Encomenda (Painel),
	* L'historisation,
	* Os widgets Dashboard e Mobile usados,
	* O tipo genérico,
	* Parâmetros opcionais,
	* As configurações **  *jeedomPostExecCmd* (ação),
	* Configurações de ação de valor (informações),
	* ícone,
	* A ativação e o diretório em **,
	* As configurações de **  **,
	* A configuração do influxDB,
	* A configuração do valor de repetição,
	* Alertas,

- **Excluir histórico de comandos de destino** : Exclui os dados do histórico de comandos de destino.

- **Copiar histórico de comandos de origem** : Copie o histórico do comando de origem para o comando de destino.



## Remplacements

O botão **** No canto superior direito permite visualizar todos os equipamentos, seguindo os filtros por objeto e por plugin.

Para cada equipamento :

- Marque a caixa no início da linha para ativar sua substituição.
- Selecione à direita o equipamento pelo qual será substituído.
- Clique em seu nome para ver seus comandos e indique quais comandos os substituem. Ao escolher um equipamento, a ferramenta preenche essas opções se encontrar um pedido do mesmo tipo e mesmo nome no equipamento de destino.


> ****
>
> Quando você indica um dispositivo de destino em um dispositivo de origem, esse dispositivo de destino é desabilitado na lista.
