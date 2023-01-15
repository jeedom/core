# Editor de massa
**Configurações → Sistema → Configuração | OS / DB**

Esta ferramenta permite que você edite um grande número de equipamentos, comandos, objetos ou cenários. É completamente genérico e usa automaticamente o esquema e a estrutura do banco de dados Jeedom. Assim, ele suporta plug-ins e a configuração de seus equipamentos.

> **Aviso**
>
> Se esta ferramenta for muito fácil de usar, ela se destina a usuários avançados. Na verdade, é de fato muito simples alterar qualquer parâmetro em dezenas de dispositivos ou centenas de comandos e, portanto, tornar certas funções inoperantes, veja até mesmo o Core.

## Utilisation

A parte *Filtrado* permite que você selecione o que deseja editar e, em seguida, adicione filtros de seleção de acordo com seus parâmetros. Um botão de teste permite, sem qualquer modificação, mostrar os itens selecionados pelos filtros inseridos.

A parte *Editando* permite que você altere os parâmetros desses itens.

- **Coluna** : Contexto.
- **Valor** : O valor do parâmetro.
- **Valor Json** : A propriedade do parâmetro / valor se for do tipo json (chave-> valor).

### Exemples:

#### Renomear um grupo de cenário

- No jogo *Filtrado*, selecionar **Cenário**.
- Clique no botão **+** para adicionar um filtro.
- Neste filtro, selecione a coluna *grupo*, e destacar o nome do grupo a ser renomeado.
- Clique no botão *Teste* para mostrar os cenários deste grupo.
- No jogo *Editando*, selecionar coluna *grupo*, então coloque o nome que você deseja no valor.
- Clique em **Executar** canto superior direito.

#### Tornar todo o equipamento de um objeto / sala invisível:

- No jogo *Filtrado*, selecionar **Equipamento**.
- Clique no botão **+** para adicionar um filtro.
- Neste filtro, selecione a coluna *object_id*, e em valor o id do objeto em questão (visível em Ferramentas / Objetos, Visão Geral).
- Clique no botão *Teste* para mostrar os cenários deste grupo.
- No jogo *Editando*, selecionar coluna *é visível*, em seguida, insira o valor 0.
- Clique em **Executar** canto superior direito.