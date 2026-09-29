# API HTTP

Jeedom pone a disposición de desarrolladores y usuarios una API completa que permite controlar Jeedom desde cualquier objeto conectado.

Hay dos API disponibles: una orientada a desarrolladores que se controla mediante JSON-RPC 2.0 y otra a través de URL y solicitudes HTTP.

Esta API se utiliza muy fácilmente mediante sencillas solicitudes HTTP a través de una URL.

> **Nota**
>
> En toda esta documentación, \#IP\_JEEDOM\# corresponde a tu URL de acceso a Jeedom. Se trata (salvo que estés conectado a tu red local) de la dirección de Internet que utilizas para acceder a Jeedom desde fuera.

> **Nota**
>
> En toda esta documentación, \#API\_KEY\# corresponde a tu clave API, específica para tu instalación. Para encontrarla, debes ir al menú «General» → «Configuración» → pestaña «General».

> **Nota**
>
> En las solicitudes POST, cada parámetro de consulta puede enviarse en el cuerpo de la solicitud en formato form-data o x-www-form-urlencoded.
> Los parámetros de consulta y el contenido del cuerpo de la solicitud pueden utilizarse conjuntamente, pero hay que tener en cuenta que los parámetros de consulta tienen prioridad sobre el contenido del cuerpo de la solicitud.

## Guion

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&id=\#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&id=#ID#&action=#ACTION#)

- **id**: corresponde al ID de tu escenario. El ID se encuentra en la página del escenario en cuestión, en «Herramientas» → «Escenarios», una vez seleccionado el escenario, junto al nombre de la pestaña «General». Otra forma de encontrarlo: en «Herramientas» → «Escenarios», haz clic en «Vista general».
- **acción**: corresponde a la acción que quieres aplicar. Los comandos disponibles son: «start», «stop», «disable» y «enable» para iniciar, detener, desactivar o activar el escenario, respectivamente.
- **etiquetas** \[opcional\]: si la acción es «start», puedes pasar etiquetas al escenario (consulta la documentación sobre escenarios) en el formato tags=toto%3D1%20tata%3D2 (ten en cuenta que %20 corresponde a un espacio y %3D a =).

> **Nota**
>
> No intentes utilizar «php://input» para pasar datos a tu script; para eso están las etiquetas.

##  Información/Acción de control

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&id=\#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

- **id**: corresponde al identificador de lo que quieres controlar o de lo que deseas recibir información.

La forma más sencilla de obtener esta URL es ir a la página **Análisis → Resumen de domótica**, buscar el comando y, a continuación, abrir su configuración avanzada (el icono del «engranaje»); allí verás una URL que ya contiene toda la información necesaria según el tipo y el subtipo del comando.

> **Nota**
>
> El campo \#ID\# permite enviar varios comandos a la vez. Para ello, hay que pasar una matriz en formato JSON (p. ej., %5B12,58,23%5D; ten en cuenta que \[ y \] deben codificarse, de ahí el uso de %5B y %5D). La respuesta de Jeedom será un JSON.

> **Nota**
>
> Los parámetros deben codificarse para las URL. Puedes utilizar una herramienta, [aquí](https://meyerweb.com/eric/tools/dencoder/).

## Interacción

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

- **consulta**: pregunta que se le plantea a Jeedom.
- **utf8** \[opcional\]: indica a Jeedom si debe codificar la consulta en utf8 antes de intentar responderla.
- **emptyReply** \[opcional\]: 0 para que Jeedom responda aunque no lo haya entendido; 1 en caso contrario.
- **perfil** \[opcional\]: nombre de usuario de la persona que inicia la interacción.
- **reply\_cmd** \[opcional\]: ID del comando que se utilizará para responder a la solicitud.

## Mensaje

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=message&category=\#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

- **categoría**: categoría del mensaje que se va a añadir al centro de mensajes.
- **mensaje**: mensaje en cuestión; recuerda codificar bien el mensaje (el espacio se convierte en %20, el signo igual en %3D…). Puedes utilizar una herramienta, [aquí](https://meyerweb.com/eric/tools/dencoder/).

## Objeto

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Devuelve la lista de todos los objetos de Jeedom en formato JSON.

## Equipamiento

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object\_id=\#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_id=#OBJECT_ID#)

- **object\_id**: ID del objeto del que se quieren recuperar los dispositivos.

## Pedido

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic\_id=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_id=#EQLOGIC_ID#)

- **eqLogic\_id**: ID del dispositivo del que se quieren recuperar los comandos.

## Datos completos

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Devuelve todos los objetos, equipos y comandos (y su valor, si se trata de información) en formato JSON.

## Variable

Esta es la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALOR*

- **name**: nombre de la variable cuyo valor se desea obtener (lectura del valor).
- **valor** \[opcional\]: si se especifica «valor», la variable adoptará ese valor (escritura de un valor).
