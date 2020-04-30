Jeedom ofrece a los desarrolladores y usuarios una API
completo para que pueda controlar Jeedom desde cualquier objeto
connecté.

Hay dos API disponibles. : un piloto orientado al desarrollador
JSON RPC 2.0 y otro a través de URL y solicitud HTTP.

Esta API se usa muy fácilmente mediante solicitudes HTTP simples a través de
URL.

> **Note**
>
> Para toda esta documentación, \#IP\_JEEDOM \# corresponde a su url
> acceso a Jeedom. Esto es (a menos que esté conectado a su red
> local) de la dirección de Internet que utiliza para acceder a Jeedom
> desde afuera.

> **Note**
>
> Para toda esta documentación, \#API\_KEY \# corresponde a su clave
> API, específica para su instalación. Para encontrarlo, tienes que ir a
> el menú "General" → "Configuración" → pestaña "General"".

Guión 
========

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&id=\#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&id=#ID#&action=#ACTION#)

-   **id** : coincide con su identificación de escenario. La identificaciónentificación está en el
    página del escenario en cuestión, en &quot;herramientas&quot; → &quot;Escenarios&quot;, una vez que
    escenario seleccionado, junto al nombre de la pestaña &quot;General&quot;. otro
    manera de encontrarlo : en &quot;Herramientas&quot; → &quot;Escenarios&quot;, haga clic en
    "Resumen".

-   **action** : corresponde a la acción que desea aplicar. la
    los pedidos disponibles son : "iniciar "," detener "," desactivar "y
    "activar "para iniciar, detener, desactivar o
    activar el escenario.

-   **tags** \ [Opcional \] : si la acción es &quot;inicio&quot;, puede omitir
    etiquetas para el escenario (ver documentación sobre escenarios) en
    las etiquetas de formulario = toto% 3D1% 20tata% 3D2 (tenga en cuenta que% 20 corresponde a un
    espacio y% 3D a =)

Comando de información / acción 
====================

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&id=\#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

-   **id** : corresponde a la identificaciónentificación de lo que desea conducir o desde qué
    desea recibir información

La forma más fácil de obtener esta URL es ir a la página Herramientas →
Resumen de automatización del hogar, para buscar el comando y luego abrir su configuración
avanzado (el icono de &quot;engranaje&quot;) y allí verá una URL que contiene
ya todo lo que necesita dependiendo del tipo y subtipo de
commande.

> **Note**
>
> Es posible que el campo \#ID \# coloque varios comandos
> de una vez. Para hacer esto, debe pasar una matriz en json (ex
> % 5B12,58,23% 5D, tenga en cuenta que \ [y \] deben estar codificados, de ahí que% 5B
> y% 5D). El regreso de Jeedom será un json

> **Note**
>
> Los parámetros deben estar codificados para url, puede usar
> un utensilio, [aquí](https://meyerweb.com/eric/tools/dencoder/)

Interacción 
===========

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

-   **query** : pregunta para hacerle a Jeedom

-   **utf8** \ [Opcional \] : le dice a Jeedom si codifica la consulta
    en utf8 antes de intentar responder

-   **emptyReply** \ [Opcional \] : 0 para que Jeedom responda incluso si
    no entendí, 1 de lo contrario

-   **profile** \ [Opcional \] : nombre de usuario de la persona
    interacción desencadenante

-   **responder\_cmd** \ [Opcional \] : ID de comando para usar para
    satisfacer la demanda

Mensaje 
=======

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=message&category=\#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

-   **category** : categoría de mensaje para agregar al centro de mensajes

-   **message** : mensaje en cuestión, tenga cuidado de pensar en la codificación
    el mensaje (el espacio se convierte en% 20, =% 3D ...). Puedes usar un
    herramienta, [aquí](https://meyerweb.com/eric/tools/dencoder/)

Objeto 
=====

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Devuelve en json la lista de todos los objetos Jeedom

Equipo 
==========

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&el objeto\_id=\#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_id=#OBJECT_ID#)

-   **el objeto\_id** : ID del objeto del que queremos recuperar
    comodidades

Orden 
========

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic\_id=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_id=#EQLOGIC_ID#)

-   **eqLogic\_id** : Identificación del equipo del que queremos recuperar
    comandos

Datos completos 
=========

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Devuelve todos los objetos, equipos, comandos (y su valor si esto
son informaciones) en json

Variable 
========

Aquí está la URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

-   **name** : nombre de la variable cuyo valor se desea (lectura de
    el valor)

-   **value** \ [Opcional \] : si se especifica &quot;valor&quot;, entonces la variable
    tomará este valor (escribir un valor)


