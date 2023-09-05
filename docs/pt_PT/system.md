# Systeme

Página reservada para usuários avançados, permite iniciar comandos SSH diretamente do Jeedom

## Adicionando comando

É possível adicionar comandos personalizados para isso a partir do editor Jeedom (Configuração -> OS/DB -> Editor de arquivos). Você deve criar um arquivo `systemCustomCmd.json` em `dados`. O arquivo deve ter o seguinte formato : 
```
[
   {
      "cmd":"meu grande pedido",
      "name":"nome do meu pedido"
   },
   {
      "cmd":"meu super pedido 2",
      "name":"nome do meu pedido 2"
   }
]
```
