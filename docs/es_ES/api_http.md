Jeedom ofrece a los desarrolladores y usuarios una API
Completar la orden de dirigir Jeedom de cualquier objeto
iniciado sesión.

Dos APIs están disponibles: un desarrollador orientado a que el conductor
JSON RPC 2.0 y otro a través de URL y petición HTTP.

Esta API es fácil de usar con las peticiones HTTP a través de simples
URL.

> **Nota**
>
> En esta documentación \ # ip \ _JEEDOM \ # es su URL
> El acceso a Jeedom. Esto es (a menos que esté conectado a la red
> Dirección local) de Internet que se utiliza para acceder Jeedom
> Desde el exterior.

> **Nota**
>
> En esta documentación \ #API \ _key \ # es la clave
> API específica para su instalación. Para encontrarlo, vaya a
> Menú "General" → "Ajustes" → pestaña "General".

guión
========

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = escenario & id = \ #ID \ # & action = \ #ACTION \ #?] (Http: // # # IP_JEEDOM / core / api / jeeApi.php? apikey apikey = # # & type = & id = escenario # ID # & action = aCCIÓN # #)

-   ** ** Identificación: es el ID de su escenario. ID está en la
    página del escenario relevante, en "Herramientas" → "Escenarios", una vez que
    escenario seleccionado, junto al nombre de la pestaña "General". otro
    forma de recuperarla: "Herramientas" → "escenarios", haga clic
    "Información general".

-   ** ** Acción: corresponde a la acción que desea aplicar. la
    Los comandos disponibles son: "Inicio", "stop", "off" y
    "Activar" para comenzar a detener, respectivamente, desactivar o
    activar el escenario.

-   **etiquetas** \[opcional\] : si la acción es "start", puede pasar
    des tags au scénario (voir la documentation sur les scénarios) sous
    la forma etiquetas=toto% 3D1%20tata%3D2 (tenga en cuenta que el 20% corresponde a una
    El espacio y el% = 3D)

Información / Control de Acción
====================

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = cmd & id = \ #ID \ #?] (Http: // # # IP_JEEDOM / jeedom /core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

-   ** ** Identificación: es el identificador que desea controlar o cual
    que desea recibir la información

La forma más fácil de obtener esta URL es ir a la página de Herramientas →
Resumen de domótica, buscar el coamando y abrir su configuración
(el icono "rueda") y allí verá una URL que contiene
ya todo lo que se necesita dependiendo del tipo y subtipo de los
comandos.

> **Nota**
>
> Es posible que el campo \#ID\# pueda contener varios comandos.
> repentinamente. Para hacer esto, es necesario pasar una tabla en json (ex
> %5B12,58,23%5D, tenga en cuenta que \[ y \] debe ser codificado, de ahí el %5B
> y %5D). El retorno de Jeedom será un json

> **Nota**
>
> Los parámetros deben estar codificados para la url. Puede utilizar la
> siguiente herramienta,[aquí](https://meyerweb.com/eric/tools/dencoder/)

interacción
===========

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = interactuar y consulta = \ #query \ #?] (Http: // # # IP_JEEDOM / jeedom /core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

-   ** ** consulta: pregunta a Jeedom

-   Utf8 ** ** \ [opcional \]: Indica si Jeedom para codificar consulta
    UTF-8 antes de tratar de responder

-   EmptyReply ** ** \ [opcional \]: 0 para Jeedom responde incluso si él
    no entender, de lo contrario 1

-   ** ** Perfil \ [opcional \] nombre de usuario de la persona
    la activación de la interacción

-   **respuesta \ _cmd** \ [opcional \]: Identificación del comando de
    satisfacer la demanda

mensaje
=======

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = Y = categoría de mensaje \ #category \ # & message = \ #message \ #] (Http: //#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

-   ** ** categoría: categoría del mensaje para agregar al centro de mensajes

-   ** ** mensaje: el mensaje en cuestión, tenga cuidado para pensar en la codificación
    Mensaje (espacio se convierte en 20%,% = 3D ...). Puede utilizar una
    herramienta, [aquí] (https://meyerweb.com/eric/tools/dencoder/)

objeto
=====

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = objeto?] (Http: // # # IP_JEEDOM / jeedom / core / api / jeeApi .php? apikey apikey = # # & type = objeto)

Devuelve la lista de todos los objetos JSON de Jeedom

equipo
==========

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = & eqLogic objeto \ _id = \ #OBJECT \ _ID \ #?] (Http: // # IP_JEEDOM # / jeedom / core / api / jeeApi.php? apikey apikey = # # & type = eqLogic y object_id = # # oBJECT_ID)

-   **objeto \ _id**: ID del objeto que se va recuperando
    comodidades

orden
========

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = comando y eqLogic \ _id = \ #EQLOGIC \ _ID \ #?] (Http: // # IP_JEEDOM # / jeedom / core / api / jeeApi.php? apikey apikey = # # & type = comando y eqLogic_id = # # eQLOGIC_ID)

-   **eqLogic \ _id**: Identificación de equipos que deben ser recuperada
    comandos

Los datos completa
=========

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = fullData?] (Http: // # # IP_JEEDOM / jeedom / core / api / jeeApi .php? apikey apikey = # # & type = fullData)

Devuelve todos los objetos, equipos, órdenes (y valor si
son información) en JSON

variable
========

Aquí está la URL =
[Http: // \ # ip \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = & nombre de variable = \ #NOMBRE \ # & valor =] (http: // # # IP_JEEDOM /jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

-   ** ** Nombre: nombre de la variable que va a ser el valor (lectura
    el valor)

-   ** ** valor \ [opcional \] Si no se especifica el "valor", entonces la variable
    tomar este valor (valor de escritura)


