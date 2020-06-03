# API HTTP

Jeedom proporciona a los desarrolladores y usuarios una API completa para que puedan controlar Jeedom desde cualquier objeto conectado.

Hay dos API disponibles : un piloto JSON RPC 2 orientado al desarrollador.0 y otro a través de URL y solicitud HTTP.

Esta API es muy fácil de usar mediante simples solicitudes HTTP a través de URL.

> **Nota**
>
> Para toda esta documentación, \#IP\_JEEDOM\# corresponde a su URL de acceso de Jeedom. Esta es (a menos que esté conectado a su red local) la dirección de Internet que utiliza para acceder a Jeedom desde afuera.

> **Nota**
>
> Para toda esta documentación, \#API\_KEY\# corresponde a su clave API, específica a su instalación. Para encontrarlo, vaya al menú "General" → "Configuración" → pestaña "General"".

## Guión

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = escenario & id = \#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = escenario e id=#ID#&action=#ACTION#)

- **identificación** : coincide con su id de escenario. El ID se puede encontrar en la página de escenario relevante, en "Herramientas" → "Escenarios", una vez que se ha seleccionado el escenario, junto al nombre de la pestaña "General"". Otra forma de encontrarlo : en "Herramientas" → "Escenarios", haga clic en "Descripción general".
- **acción** : corresponde a la acción que desea aplicar. Los comandos disponibles son : "iniciar "," detener "," desactivar "y" activar "para iniciar, detener, desactivar o activar el escenario, respectivamente.
- **etiquetas** \ [Opcional \] : si la acción es "inicio", puede pasar etiquetas al escenario (consulte la documentación sobre los escenarios) en el formulario etiquetas = toto% 3D1% 20tata% 3D2 (tenga en cuenta que% 20 corresponde a un espacio y% 3D a = ).

##  Comando de información / acción

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cmd & id = \#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cmd e id=#ID#)

- **identificación** : corresponde a la identificación de lo que desea controlar o del que desea recibir información.

La forma más fácil de obtener esta URL es ir a la página **Análisis → Resumen de domótica**, para buscar el pedido y luego abrir su configuración avanzada (el ícono de "engranaje") y allí, verá una URL que ya contiene todo lo que necesita según el tipo y el subtipo del pedido.

> **Nota**
>
> Es posible para el campo \#ID\# hacer múltiples pedidos a la vez. Para hacer esto, debe pasar una matriz en json (por ejemplo,% 5B12,58,23% 5D, tenga en cuenta que \ [y \] deben estar codificados, de ahí que% 5B y% 5D). El regreso de Jeedom será un json.

> **Nota**
>
> Los parámetros deben estar codificados para url. Puede usar una herramienta, [aquí](https://meyerweb.com/eric/tools/dencoder/).

## Interaction

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = interactuar y consultar = \#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = interactuar y consultar=#QUERY#)

- **pregunta** : pregunta para hacerle a Jeedom.
- **utf8** \ [Opcional \] : le dice a Jeedom si codifica la consulta en utf8 antes de intentar responder.
- **vacío Respuesta** \ [Opcional \] : 0 para que Jeedom responda incluso si no entendió, 1 de lo contrario.
- **perfil** \ [Opcional \] : nombre de usuario de la persona que inicia la interacción.
- **responder\_cmd** \ [Opcional \] : ID de pedido que se utilizará para responder a la solicitud.

## Message

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = mensaje y categoría = \#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = mensaje y categoría=#CATEGORY#&message=#MESSAGE#)

- **categoría** : categoría de mensaje para agregar al centro de mensajes.
- **Mensaje** : mensaje en cuestión, tenga cuidado de pensar en codificar el mensaje (el espacio se convierte en% 20, =% 3D). Puedes usar una herramienta, [aquí](https://meyerweb.com/eric/tools/dencoder/).

## Objet

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = object)

Devuelve en json la lista de todos los objetos Jeedom.

## Equipement

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = eqLogic & object\_id = \#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = eqLogic & object_id=#OBJECT_ID#)

- **el objeto\_id** : Identificación del objeto cuyo equipo queremos recuperar.

## Commande

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = comando & eqLogic\_id = \#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = comando & eqLogic_id=#EQLOGIC_ID#)

- **eqLogic\_id** : Identificación del equipo del que se deben recuperar los pedidos.

## Datos completos

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = fullData)

Devuelve todos los objetos, equipos, comandos (y su valor si son información) en json.

## Variable

Voaquí l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = variable & name = \#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = variable & name=#NAME#&value=)*Valor*

- **nombre** : nombre de la variable cuyo valor se desea (lectura del valor).
- **valor** \ [Opcional \] : si se especifica "valor", la variable tomará este valor (escribir un valor).
