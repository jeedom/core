# API HTTP

Jeedom proporciona a los desarrolladores y usuarios una API completa para que puedan controlar Jeedom desde cualquier objeto conectado.

Hay dos API disponibles. : un piloto JSON RPC 2 orientado al desarrollador.0 y otro a través de URL y solicitud HTTP.

Esta API es muy fácil de usar mediante simples solicitudes HTTP a través de URL.

> **nota**
>
> Para toda esta documentación, \ #IP \ _JEEDOM \ # corresponde a su URL de acceso de Jeedom. Esta es (a menos que esté conectado a su red local) la dirección de Internet que utiliza para acceder a Jeedom desde afuera.

> **nota**
>
> Para toda esta documentación, \ #API \ _KEY \ # corresponde a su clave API, específica para su instalación. Para encontrarlo, vaya al menú &quot;General&quot; → &quot;Configuración&quot; → pestaña &quot;General&quot;.

## guión

Aquí está la URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&identificación=\#ID\#&acción=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&identificación=#ID#&acción=#ACTION#)

- **identificación** : coincidentificacióne con su identificación de escenario. El ID se puede encontrar en la página del escenario en cuestión, en &quot;Herramientas&quot; → &quot;Escenarios&quot;, una vez que se ha seleccionado el escenario, junto al nombre de la pestaña &quot;General&quot;. Otra forma de encontrarlo : en &quot;Herramientas&quot; → &quot;Escenarios&quot;, haga clic en &quot;Descripción general&quot;.
- **acción** : corresponde a la acción que desea aplicar. Los comandos disponibles son : &quot;iniciar&quot;, &quot;detener&quot;, &quot;desactivar&quot; y &quot;activar&quot; para iniciar, detener, desactivar o activar el escenario, respectivamente.
- **etiquetas** \ [Opcional \] : si la acción es &quot;inicio&quot;, puede pasar etiquetas al escenario (consulte la documentación sobre los escenarios) en el formulario etiquetas = toto% 3D1% 20tata% 3D2 (tenga en cuenta que% 20 corresponde a un espacio y% 3D a =).

##  Comando de información / acción

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&identificación=\#ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&identificación=#ID#)

- **identificación** : corresponde a la identificaciónentificación de lo que desea controlar o del que desea recibir información.

La forma más fácil de obtener esta URL es ir a la página **Análisis → Resumen de domótica**, para buscar el pedidentificacióno, luego para abrir su configuración avanzada (el ícono de &quot;engranaje&quot;) y verá una URL que ya contiene todo lo que necesita según el tipo y el subtipo del pedidentificacióno.

> **nota**
>
> Es posible que el campo \ #ID \ # coloque varios comandos a la vez. Para hacer esto, debe pasar una matriz en json (por ejemplo,% 5B12,58,23% 5D, tenga en cuenta que \ [y \] deben estar codificados, de ahí que% 5B y% 5D). El regreso de Jeedom será un json.

> **nota**
>
> Los parámetros deben estar codificados para url. Puede usar una herramienta, [aquí] (https://meyerweb.com/eric/tools/dencoder/).

## interacción

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&pregunta=\#QUERY\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&pregunta=#QUERY#)

- **pregunta** : pregunta para hacerle a Jeedom.
- **utf8** \ [Opcional \] : le dice a Jeedom si codifica la consulta en utf8 antes de intentar responder.
- **emptyReply** \ [Opcional \] : 0 para que Jeedom responda incluso si no entendió, 1 de lo contrario.
- **perfil** \ [Opcional \] : nombre de usuario de la persona que inicia la interacción.
- **responder \ _cmd** \ [Opcional \] : ID de pedidentificacióno que se utilizará para responder a la solicitud.

## mensaje

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=mensaje&categoría=\#CATEGORY\#&mensaje=\#MESSAGE\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=mensaje&categoría=#CATEGORY#&mensaje=#MESSAGE#)

- **categoría** : categoría de mensaje para agregar al centro de mensajes.
- **mensaje** : mensaje en cuestión, tenga cuidentificaciónado de pensar en codificar el mensaje (el espacio se convierte en% 20, =% 3D ...). Puede usar una herramienta, [aquí] (https://meyerweb.com/eric/tools/dencoder/).

## objeto

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Devuelve en json la lista de todos los objetos Jeedom.

## equipo

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&el objeto \ _identificación=\#OBJECT\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_identificación=#OBJECT_ID#)

- **el objeto \ _identificación** : Identificación del objeto cuyo equipo queremos recuperar.

## orden

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic \ _identificación=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_identificación=#EQLOGIC_ID#)

- **eqLogic \ _identificación** : Identificación del equipo del que se deben recuperar los pedidentificaciónos.

## Datos completos

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Devuelve todos los objetos, equipos, comandos (y su valor si son información) en json.

## variable

Aquí está la URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&nombre=\#NAME\#&valor=](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&nombre=#NAME#&valor=)*VALUE*

- **nombre** : nombre de la variable cuyo valor se desea (lectura del valor).
- **valor** \ [Opcional \] : si se especifica &quot;valor&quot;, la variable tomará este valor (escribir un valor).
