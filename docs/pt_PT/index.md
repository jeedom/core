# 2N

#Descrição 

Plugin para interfones 2N.



# Pré-requisitos

 - Descubra o endereço IP do seu intercomunicador 2N,
 - Ter o plugin da câmera instalado,
 - Criou uma conta de usuário através da interface do seu intercomunicador 2N.



# Installation

Depois de baixar o plugin, você deve primeiro ativá-lo, como qualquer plugin Jeedom.
Devemos iniciar o demônio : verifique se o daemon está OK.



# Configuration

Para um novo dispositivo 2N, você deve se conectar à interface 2N, acessível através do endereço IP deste (para descobrir o IP do seu dispositivo, você pode instalar o software 2N Network Scanner, que detectará os dispositivos 2N. Presente na sua rede

Por padrão, o nome de usuário e a senha do seu dispositivo 2N são : admin, 2n.

Uma vez conectado à interface, você precisará:


Ative as opções para acessar os serviços de API :
![config](../images/2nAPI.png)


Crie uma conta com direitos para serviços de API :
![config](../images/2nUser.png)


Configure os interruptores em seu dispositivo :
![config](../images/2nSwitch.png)


Crie um usuário para atribuir seus códigos de acesso ao interfone :
![config](../images/2nUsers.png)


Configurar usuário :
![config](../images/2nConfigUser.png)




Feito isso, você pode criar seu equipamento no Jeedom com a senha e o nome de usuário da conta 2n configurada com direitos API (ver acima).
Nos menus suspensos, escolha os módulos instalados ou não no seu interfone : Câmera, leitor de impressão digital, módulo anti-rasgo.


![config](../images/2nCrea.png)



Atribua a ele um pai e torne-o visível e ativo.

Se você tiver uma câmera em seu equipamento, um objeto de câmera será criado por meio do plug-in Câmera; você terá que configurá-lo para que apareça no seu painel.



>**IMPORTANTE**
>
> Você deve reiniciar o daemon após criar um equipamento, para atribuir a ele um identificador para solicitações de API.
> ![config](../images/2nDemon.png)




# Comandos e informações do painel 


Estado :

- Por padrão, os estados do switch estão vinculados aos seus comandos de ação; clicar no botão ativa o botão (veremos o ícone do botão mudar de cor).
- O estado das chaves volta para aqueles disponíveis no seu dispositivo (de 1 a 4).


- Chamada fornece o status de comunicação se você receber uma chamada de outro dispositivo 2n (chegando, recebido etc).

- Corte indica se ocorreu um rasgo (para modelos equipados com o módulo anti-rasgo).

- Bluetooth_tel_mobile sinaliza a autenticação do leitor Bluetooth.


- Os sinais de ruído aumentaram a detecção do nível de ruído.

- Leitor de cartão : exibe o número do cartão RFID configurado.


- Código de entrada : exibe o código inserido no seu intercomunicador.


- last_button pressionado : mostra o último pressionado no seu interfone.

- Pegada : exibe o ID da pessoa registrada (para equipamentos equipados com o módulo de impressão digital).



- Estado da porta indica um problema com o status da porta.

- Movimento, relata a detecção de movimento por meio de uma câmera (apenas para modelos equipados com câmera).

- Unauthorized_opening, sinaliza uma abertura de porta não autorizada (para modelos equipados apenas com entrada digital e um botão start).
- Open_long, indica uma abertura da porta muito longa ou uma falha em fechar a porta dentro do tempo alocado (para modelos equipados com entrada digital apenas).



Pedidos :

- Switch_ state (switch id) : permite que você ligue ou desligue o switch cujo id corresponde e tenha um feedback de status de seu switch.





Informação adicional :

Ative os logs no modo Debug para ter mais informações sobre os eventos detectados pelo seu interfone