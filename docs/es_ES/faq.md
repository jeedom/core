# FAQ
**Configuración → Versión : Preguntas más frecuentes**

### Jeedom requiere una suscripción ?
No, Jeedom es totalmente utilizable sin necesidad de suscripción. Sin embargo, hay servicios ofrecidos para copias de seguridad o llamadas / SMS, pero que son realmente opcionales.

### ¿Jeedom utiliza servidores externos para ejecutar ?
No, Jeedom no utiliza la infraestructura de tipo "Cloud"". Todo se hace localmente y no necesita nuestros servidores para que su instalación funcione. Solo los servicios como Market, respaldo en línea o Jeedom DNS requieren el uso de nuestros servidores.

### ¿Existe una aplicación móvil dedicada? ?
Jeedom tiene una versión móvil adecuada para usar en dispositivos móviles y tabletas. También hay una aplicación nativa para Android e iOS.

### ¿Cuáles son las credenciales para iniciar sesión por primera vez? ?
Cuando inicia sesión en Jeedom por primera vez (e incluso después si no los ha cambiado), el nombre de usuario y la contraseña predeterminados son admin / admin. En la primera conexión, se recomienda encarecidamente modificar estos identificadores para mayor seguridad.

### Ya no puedo conectarme a mi Jeedom
Desde Jeedom 3.2 ya no es posible conectarse con admin / admin de forma remota por razones obvias de seguridad. Las credenciales de administrador / administrador solo funcionan localmente. Atención, si pasas por el DNS, incluso localmente, necesariamente estás identificado como remoto. Otro punto predeterminado solo IP en 192.168.*.* o 127.0.0.1 son reconocidos como locales. Está configurado en la administración de la parte de seguridad de Jeedom y luego IP "blanco". Si, a pesar de todo, aún no puede conectarse, debe usar el procedimiento de restablecimiento de contraseña, consulte [aquí](https://doc.jeedom.com/es_ES/howto/reset.password).

### No veo todo mi equipo en el Tablero
A menudo, esto se debe al hecho de que el equipo está asignado a un objeto que no es el hijo o el objeto mismo del primer objeto seleccionado a la izquierda en el árbol (puede configurarlo en su perfil).

### La interfaz de Jeedom tiene accesos directos ?
Sí, la lista de atajos de teclado / mouse es [aquí](shortcuts.md).

### ¿Podemos reordenar pedidos de equipos? ?
Sí, es posible, solo arrastre y suelte los comandos de su objeto en su configuración.

### ¿Podemos editar el estilo de los widgets? ?
Para cada comando, puede elegir su visualización entre diferentes widgets Core o crear uno con Herramientas → Widgets.

### ¿Podemos poner el mismo equipo más de una vez en un diseño? ?
No, no es posible, pero puedes duplicarlo gracias al complemento virtual.

### Cómo cambiar datos históricos incorrectos ?
Es suficiente, en una curva histórica del pedido, hacer clic en el punto en cuestión. Si deja el campo en blanco, el valor se eliminará.

### ¿Cuánto dura una copia de seguridad? ?
No hay una duración estándar, depende del sistema y del volumen de datos a respaldar, pero puede tomar más de 5 minutos, esto es normal.

### ¿Dónde están las copias de seguridad de Jeedom? ?
Están en la carpeta / var / www / html / backup

### ¿Podemos poner Jeedom en https? ?
Si : O tienes una fuente de alimentación o más, en este caso
solo usa el [DNS de Jeedom](https://jeedom.github.io/documentation/howto/es_ES/mise_en_place_dns_jeedom). Con un DNS y sabes cómo configurar un certificado válido, en este caso es una instalación estándar de un certificado.

### Cómo conectarse en SSH ?
Aquí hay un [documentación](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), "Windows : Putty". El &quot;nombre de host&quot; es la ip de su Jeedom, los identificadores son :

- Nombre de usuario : "root ", contraseña : "Mjeedom96"
- Nombre de usuario : "jeedom ", contraseña : "Mjeedom96"
- O lo que pones en la instalación si estás en bricolaje

Tenga en cuenta que cuando escribe la contraseña no verá nada escrito en la pantalla, esto es normal.

### Cómo restablecer derechos ?
En SSH hacer :

`` `{.bash}
sudo su -
chmod -R 775 / var / www / html
chown -R www-data:www-data / var / www / html
`` ''

### Cómo actualizar Jeedom en SSH ?
En SSH hacer :

`` `{.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 / var / www / html
chown -R www-data:www-data / var / www / html
`` ''

### ¿Es compatible con Webapp Symbian? ?
La aplicación web requiere un teléfono inteligente compatible con HTML5 y CSS3. Por lo tanto, lamentablemente no es compatible con Symbian.

### ¿En qué plataformas se puede ejecutar Jeedom? ?
Para que Jeedom funcione, necesita una plataforma Linux con derechos de root o un sistema de tipo docker. Por lo tanto, no funciona en una plataforma Android pura.

### No puedo actualizar cierto complemento &quot;No se pudo descargar el archivo. Vuelva a intentarlo más tarde (tamaño inferior a 100 bytes))..." ?
Esto puede deberse a varias cosas, :

- Compruebe que su Jeedom todavía está conectado al mercado (en la página de administración de Jeedom, parte actualizada, tiene un botón de prueba).
- Verifique que la cuenta de mercado haya comprado el complemento en cuestión.
- Comprueba que tienes espacio en Jeedom (la página de salud te dirá).
- Verifique que su versión de Jeedom sea compatible con el complemento.

### Tengo una pagina en blanco
Es necesario conectarse en SSH a Jeedom y lanzar el script de autodiagnóstico :
`` `{.bash}
sudo chmod + x / var / www / html / health.sh; sudo /var/www/html/health.sh
`` ''
Si hay un problema, el script intentará corregirlo. Si no puede, te dirá.

También puede consultar el registro /var/www/html/log/http.error. Muy a menudo, esto indica la preocupación.

### Tengo un problema con el identificador de BDD
Estos deben restablecerse :

`` `{.bash}
bdd_password = $ (cat / dev / urandom | tr -cd &#39;a-f0-9' | cabeza -c 15)
echo "DROP USER &#39;jeedom&#39; @ &#39;localhost'" | mysql -uroot -p
echo "CREAR USUARIO &#39;jeedom&#39; @ &#39;localhost&#39; IDENTIFICADO POR &#39;$ {bdd_password}&#39;;" | mysql -uroot -p
echo &quot;CONCEDE TODOS LOS PRIVILEGIOS EN Jeedom.* TO &#39;jeedom&#39; @ &#39;localhost&#39;;" | mysql -uroot -p
cd / usr / share / nginx / www / jeedom
sudo cp core / config / common.config.sample.php core / config / common.config.php
sudo sed -i -e "s /#PASSWORD#/ $ {bdd_password} / g "core / config / common.config.php
sudo chown www-data:www-data core / config / common.config.php
`` ''

### Tengo \ {\ {… \} \} en todas partes
La causa más frecuente es el uso de un complemento en la versión beta y Jeedom en estable, o al revés. Para obtener los detalles del error, debe mirar el registro http.error (en / var / www / html / log).

### Al realizar el pedido tengo una rueda que gira sin parar
Nuevamente, esto a menudo se debe a un complemento en beta mientras Jeedom está en estable. Para ver el error, debes hacer F12 y luego consola.

### Ya no tengo acceso a Jeedom, ni a través de la interfaz web ni en la consola a través de SSH
Este error no se debe a Jeedom, sino a un problema con el sistema.
Si esto persiste después de una reinstalación, es aconsejable consultar con el servicio postventa por problemas de hardware. Aquí esta el [documentación](https://doc.jeedom.com/es_ES/installation/smart) para inteligente

### Mi escenario ya no se detiene
Es recomendable mirar los comandos ejecutados por el escenario, a menudo proviene de un comando que no termina.

### Tengo inestabilidades o errores 504
Compruebe si su sistema de archivos no está dañado, en SSH el comando es : `` ''sudo dmesg | grep error`` ''.

### Tengo el siguiente error : SQLSTATE \ [HY000 \] \ [2002 \] No se puede conectar al servidor MySQL local a través del socket &#39;/var/run/mysqld/mysqld.sock'
Esto se debe a que MySQL se detuvo, no es normal, los casos comunes son :

- Falta de espacio en el sistema de archivos (se puede verificar haciendo el comando "df -h", en SSH)
- Problema de corrupción de archivo (s), que a menudo ocurre después de un cierre inseguro de Jeedom (falla de energía)
- Problemas de memoria, el sistema carece de memoria y mata el proceso que más consume (a menudo la base de datos). Esto se puede ver en la administración del sistema operativo y luego en dmesg, debería ver una muerte por "oom". Si este es el caso, reduzca el consumo de Jeedom desactivando los complementos.

Desafortunadamente, no hay mucha solución si es el segundo caso, lo mejor es recuperar una copia de seguridad (disponible en / var / www / html / backup por defecto), reinstalar Jeedom y restaurar la copia de seguridad. También puede ver por qué MySQL no quiere arrancar desde una consola SSH :
`` `{.bash}
sudo su -
servicio de parada mysql
mysqld --verbose
`` ''
O consultar el log : /var/log/mysql/error.log

### Los botones de apagado / reinicio no funcionan
En una instalación de bricolaje es normal. En SSH, debe realizar el comando visudo y al final del archivo debe agregar : www-data ALL = (ALL)
NOPASSWD: TODAS.

`` `{.bash}
servicio sudo apache2 reiniciar
`` ''

### No veo algunos complementos del mercado
Este tipo de caso ocurre si su Jeedom no es compatible con el complemento. En general, una actualización de Jeedom soluciona el problema.

### Tengo un equipo de tiempo de espera pero no lo veo en el Tablero
Las alertas se clasifican por prioridad, desde las menos importantes hasta las más importantes : tiempo de espera, advertencia de batería, peligro de batería, alerta de advertencia, alerta de peligro

### My Jeedom muestra permanentemente &quot;Iniciando&quot; incluso después de 1 hora ?
Si está en bricolaje y en Debian 9 o más, verifique que no haya habido una actualización de Apache y, por lo tanto, la devolución de privateTmp (visible haciendo `ls / tmp` y vea si hay una carpeta privada \* Apache). Si es el caso, es necesario hacer :
`` ''
mkdir /etc/systemd/system/apache2.service.d
echo &quot;[Servicio]&quot;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
echo &quot;PrivateTmp = no&quot; &gt;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
`` ''

### Tengo una preocupación por el tiempo en mi historia
Intente borrar el caché de Chrome, la visualización de los historiales se calcula en relación con el tiempo del navegador.

### Tengo el error "Problemas de red detectados, reinicio de red"
Jeedom no puede encontrar o no puede hacer ping a la puerta de enlace. En general, sucede si el cuadro adsl se reinicia (en particular, liveboxes) y Jeedom no se ha reiniciado o se ha reiniciado más rápido que el cuadro. Por seguridad, le dice que ha encontrado un problema y relanza el proceso de conexión de red. Puede desactivar este mecanismo yendo a la configuración de Jeedom y desactivando la administración de red de Jeedom.

### Recibo el mensaje &quot;No se pudo hacer una copia de seguridad de la base de datos. Comprueba que mysqldump está presente."
Significa que Jeedom no puede hacer una copia de seguridad de la base de datos, lo que puede sugerir un problema con la corrupción de la base de datos y del sistema de archivos. Desafortunadamente no hay un comando milagroso para corregir. Lo mejor es iniciar una copia de seguridad y analizar el registro de la misma. En casos conocidos de inquietudes tenemos:

- una tabla base corrupta => hay un mal comienzo que debemos ver para tratar de reparar y si no comienza desde la última copia de seguridad válida (si está en la tarjeta SD, es el momento adecuado para cambiarla))
- no hay suficiente espacio en el sistema de archivos =&gt; mira la página de salud esto puede decirte

### Tengo errores de tipo &quot;Clase &#39;eqLogic&#39; no encontrada&quot;, parece que faltan archivos o tengo una página en blanco
Es un error bastante grave, lo más sencillo es hacer
`` ''
mkdir -p / root / tmp /
cd / root / tmp
wget https://github.com/jeedom/core/archive/master.zip
descomprimir master.zip
cp -R / root / tmp / core-master / * / var / www / html
rm -rf / root / tmp / core-master
`` ''

### Tengo el error en backdrop_execution MYSQL_ATTR_INIT_COMMAND
En la administración de Jeedom parte OS / DB, entonces en la consola del sistema es necesario hacer :
`` ''
si | sudo apt install -y php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc php-common php-dev php-zip php-ssh2 php-mbstring php-ldap
`` ''

### No puedo instalar las dependencias del complemento. Tengo un error del tipo : "E: dpkg ha sido descatalogado. Il est nécessaire d'utiliser « sudo dpkg --configure -a » pour corriger le problème." ou "E: No se pudo obtener lock / var / lib / dpkg / lock"

Hay que :

- reiniciar Jeedom
- vaya a la administración de la misma (botón de la rueda con muesca en la parte superior derecha y luego configuración en v3 o Configuración -> Sistema -> Configuración en v4)
- ir a la pestaña OS / DB
- iniciar la administración del sistema
- haga clic en configurar Dpkg
- espera 10min
- relanzar las dependencias de los complementos de bloqueo

### Tengo este error al instalar dependencias de complementos : "de pip._ principal de importación interna"

Es necesario en la consola del sistema de Jeedom o en ssh hacer

`` ''''
sudo easy_install pip
sudo easy_install3 pip
`` ''''

Luego relanzar las dependencias


### Desde el 4.2, ya no puedo mostrar iframe

Núcleo 4.2 mejora enormemente la seguridad de Jeedom. Si realmente (a sabiendas) necesita volver a una versión insegura de su Jeedom :
Ir a **Configuración -> Sistema -> Configuración** luego en **OS / DB**, inicie la consola de administración del sistema y haga clic en **Apache no es seguro**. Se recomienda reiniciar Jeedom después de este cambio.

### Desde el 4.2, algunos complementos ya no funcionan y en la consola del navegador (tecla F12) tengo errores 403

Esto se debe a la seguridad de Apache, que requiere que los desarrolladores de complementos coloquen los archivos correctos en los directorios correctos para limitar la superficie de ataque de Jeedom. Esta seguridad se realiza en el archivo .htaccess (se sobrescribe cada vez que se actualiza el núcleo). Puedes hacer un archivo .htaccess_custom con sus propias reglas que, si existen, se usarán en lugar del archivo .htaccess desde Core.