Jeedom que requiere una suscripción?
=====================================

No, Jeedom es plenamente utilizable sin necesidad de ningún
suscripción de ningún tipo. Sin embargo, hay servicios disponibles para
copias de seguridad o de llamada / SMS, pero sigue siendo muy
opcional.

Jeedom que utiliza servidores externos para operar?
================================================== ============

No, Jeedom no utiliza este tipo de infraestructura "nube". Todo se hace
local y no necesita nuestros servidores a su
La instalación funciona. Sólo los servicios como el mercado, la
copia de seguridad en línea o DNS Jeedom requieren el uso de nuestra
servidores.

Podemos cambiar el orden de los equipos?
==================================================

Sí es posible, basta con arrastrar / soltar los controles de su
objeto en su configuración.

Puede editar el estilo de los widgets?
=====================================

Sí es posible, ya sea a través del plugin de flash, o
utilizando la página de visualización general →

Podemos poner varias veces el mismo equipo en un diseño?
================================================== ==============

No, esto no es posible, pero se puede duplicar a través
plug-in virtual.

Cómo cambiar un historial de datos erróneos?
================================================== ==

Un poco más de una curva histórico de la orden, haga clic en el
pregunta punta en. Si deja los campos en blanco, entonces el valor
serán eliminados.

¿Cuánto tiempo hace una copia de seguridad?
======================================

No hay una duración estándar, dependiendo del volumen del sistema
los datos de copia de seguridad, pero puede tomar más de 5 minutos se
normal.

¿Hay una aplicación móvil dedicado?
========================================

Jeedom tiene una versión móvil para su uso en el móvil y
tableta. También hay una aplicación nativa para Android y iOS.

¿Cuáles son las credenciales para conectar la primera vez?
================================================== ==============

Cuando se conecta por primera vez a Jeedom (y más allá si lo hace
no han cambiado), el nombre de usuario y la contraseña predeterminada
son admin / admin. En la primera conexión, es fuertemente
recomendadas para cambiar estos identificadores de seguridad.

Podemos poner Jeedom https?
================================

Sí: \ * O tienes una fuente de alimentación o más, en cuyo caso se
Sólo tiene que utilizar el Jeedom DNS. \ * O bien tienen un DNS y sabes
establecer un certificado válido, en este caso se trata de una instalación
certificado estándar.

Cómo revisar los derechos?
====================================

SSH hizo:

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

¿Dónde están las copias de seguridad Jeedom?
==========================================

Ellos están en el directorio / var / www / html / copia de seguridad

Cómo actualizar Jeedom SSH?
=====================================

SSH hizo:

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

Webapp es compatible Symbian?
=======================================

La aplicación web requiere un teléfono inteligente que soporta HTML5 y CSS3. Ella
Así que por desgracia no es compatible con Symbian.

¿En qué plataformas Jeedom puede funcionar?
================================================== ==

Para el trabajo Jeedom, necesita una plataforma Linux con los derechos
raíz o un sistema típico ventana acoplable. Por lo que no funciona en una
plataforma Android puro.
