# Editor de massa
**Configurações → Sistema → Configuração | OS / DB**

Esta ferramenta permite que você edite um grande número de equipamentos, comandos, objetos ou cenários. É completamente genérico e usa automaticamente o esquema e a estrutura do banco de dados Jeedom. Assim, ele suporta plug-ins e a configuração de seus equipamentos.

> ****
>
> Se esta ferramenta for muito fácil de usar, ela se destina a usuários avançados. Na verdade, é de fato muito simples alterar qualquer parâmetro em dezenas de dispositivos ou centenas de comandos e, portanto, tornar certas funções inoperantes, veja até mesmo o Core.

## Utilisation

A parte ** permite que você selecione o que deseja editar e, em seguida, adicione filtros de seleção de acordo com seus parâmetros. Um botão de teste permite, sem qualquer modificação, mostrar os itens selecionados pelos filtros inseridos.

A parte ** permite que você altere os parâmetros desses itens.

- **** : Contexto.
- **** : O valor do parâmetro.
- **Valor Json** : A propriedade do parâmetro / valor se for do tipo json (chave-> valor).

### Exemples:

#### Renomear um grupo de cenário

- No jogo **, selecionar **Cenário**.
- Clique no botão **** para adicionar um filtro.
- Neste filtro, selecione a coluna **, e destacar o nome do grupo a ser renomeado.
- Clique no botão ** para mostrar os cenários deste grupo.
- No jogo **, selecionar coluna **, então coloque o nome que você deseja no valor.
- Clique em **Executar** canto superior direito.

#### Tornar todo o equipamento de um objeto / sala invisível:

- No jogo **, selecionar ****.
- Clique no botão **** para adicionar um filtro.
- Neste filtro, selecione a coluna **, e em valor o id do objeto em questão (visível em Ferramentas / Objetos, Visão Geral).
- Clique no botão ** para mostrar os cenários deste grupo.
- No jogo **, selecionar coluna **, em seguida, insira o valor 0.
- Clique em **Executar** canto superior direito.