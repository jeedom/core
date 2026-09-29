# Preguntas frecuentes
**Configuración → Versión: Preguntas frecuentes**

### ¿Jeedom requiere una suscripción?
No, Jeedom se puede utilizar plenamente sin necesidad de ninguna suscripción. Sin embargo, existen servicios disponibles para las copias de seguridad o las llamadas y los SMS, pero son totalmente opcionales.

### ¿Utiliza Jeedom servidores externos para funcionar?
No, Jeedom no utiliza una infraestructura de tipo «nube». Todo se realiza de forma local y no necesitas nuestros servidores para que tu instalación funcione. Solo servicios como el Market, la copia de seguridad en línea o el DNS de Jeedom requieren el uso de nuestros servidores.

### ¿Hay alguna aplicación móvil específica?
Jeedom cuenta con una versión móvil adaptada para su uso en móviles y tabletas. También hay una aplicación nativa para Android e iOS.

### ¿Cuáles son los datos de acceso para iniciar sesión por primera vez?
La primera vez que inicie sesión en Jeedom (y también posteriormente, si no los ha modificado), el nombre de usuario y la contraseña predeterminados son admin/admin. Se recomienda encarecidamente que, en el primer inicio de sesión, modifique estos datos de acceso para mayor seguridad.

### Ya no consigo conectarme a mi Jeedom
Desde Jeedom 3.2 ya no es posible conectarse de forma remota con las credenciales «admin/admin» por razones obvias de seguridad. Las credenciales «admin/admin» solo funcionan de forma local. Ten en cuenta que, si accedes a través del DNS, incluso de forma local, se te identificará necesariamente como un usuario remoto. Otro punto a tener en cuenta: por defecto, solo se reconocen como locales las direcciones IP del rango 192.168.*.* o 127.0.0.1. Esto se configura en la administración de Jeedom, en la sección de seguridad y, a continuación, en «IP blancas». Si, a pesar de todo, sigues sin poder conectarte, debes utilizar el procedimiento de restablecimiento de contraseña; consulta [aquí](https://doc.jeedom.com/howto/es_ES/reset.password).

### No veo todos mis dispositivos en el panel de control
A menudo, esto se debe a que los dispositivos están asignados a un objeto que no es el hijo ni el propio objeto del primer objeto seleccionado a la izquierda en el árbol (puedes configurarlo en tu perfil).

### ¿Tiene la interfaz de Jeedom atajos?
Sí, la lista de atajos de teclado y ratón es [aquí](shortcuts.md).

### ¿Se puede cambiar el orden de los mandos de un equipo?
Sí, es posible; solo tienes que arrastrar y soltar los controles de tu objeto en su configuración.

### ¿Se puede personalizar el estilo de los widgets?
Para cada comando, puedes elegir cómo mostrarlo entre los distintos widgets del Core, o crear uno nuevo en Herramientas → Widgets.

### ¿Se puede incluir varias veces el mismo equipo en un diseño?
No, eso no es posible, pero puedes duplicarlo gracias al complemento virtual.

### ¿Cómo se modifica un dato erróneo del historial?
Basta con hacer clic en el punto correspondiente de un gráfico histórico del pedido. Si dejas el campo en blanco, el valor se eliminará.

### ¿Cuánto tiempo dura una copia de seguridad?
No hay una duración estándar, depende del sistema y del volumen de datos que haya que guardar, pero puede tardar más de 5 minutos, lo cual es normal.

### ¿Dónde se guardan las copias de seguridad de Jeedom?
Se encuentran en la carpeta /var/www/html/backup

### ¿Se puede configurar Jeedom con https?
Sí: o bien tienes un paquete Power o superior, en cuyo caso te
basta con utilizar el [DNS Jeedom](https://doc.jeedom.com/howto/es_ES/mise_en_place_dns_jeedom). Ya sea con un DNS y si sabes cómo configurar un certificado válido; en ese caso, se trata de una instalación estándar de un certificado.

### ¿Cómo conectarse por SSH?
Aquí tienes una [documentación](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), sección «En Windows: Putty». El «hostname» es la dirección IP de tu Jeedom, y los datos de acceso son:

- Nombre de usuario: «root», contraseña: «Mjeedom96»
- Nombre de usuario: «jeedom», contraseña: «Mjeedom96»
- O lo que hayas instalado si lo has hecho tú mismo

Ten en cuenta que, al escribir la contraseña, no verás que se escriba nada en la pantalla; es normal.

### ¿Cómo replantear los derechos?
En SSH, ejecuta:

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

### ¿Cómo se actualiza Jeedom mediante SSH?
En SSH, ejecuta:

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

### ¿Es la aplicación web compatible con Symbian?
La aplicación web requiere un smartphone compatible con HTML5 y CSS3. Por lo tanto, lamentablemente no es compatible con Symbian.

### ¿En qué plataformas puede funcionar Jeedom?
Para que Jeedom funcione, se necesita una plataforma Linux con permisos de root o un sistema tipo Docker. Por lo tanto, no funciona en una plataforma Android pura.

### ¿No puedo actualizar cierto plugin? «Error al descargar el archivo. Inténtalo de nuevo más tarde (tamaño inferior a 100 bytes)...»?
Esto puede deberse a varias causas; hay que:

- Comprueba que tu Jeedom siga conectado al Market (en la página de administración de Jeedom, en la sección de actualizaciones, hay un botón de prueba).
- Comprueba que la cuenta de Market haya comprado efectivamente el plugin en cuestión.
- Comprueba que tengas suficiente espacio en Jeedom (la página de estado te lo indicará).
- Comprueba que tu versión de Jeedom sea compatible con el complemento.

### Tengo la mente en blanco
Hay que conectarse por SSH a Jeedom y ejecutar el script de autodiagnóstico:
``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```
Si surge algún problema, el script intentará solucionarlo. Si no lo consigue, te lo indicará.

También puedes consultar el archivo de registro /var/www/html/log/http.error. Muy a menudo, este indica cuál es el problema.

### Tengo un problema con mi nombre de usuario en la base de datos
Hay que reiniciar los siguientes:

``` {.bash}
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p
echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p
cd /var/www/html
sudo cp core/config/common.config.sample.php core/config/common.config.php
sudo sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php
sudo chown www-data:www-data core/config/common.config.php
```

### Tengo \{\{…​\}\} por todas partes
La causa más frecuente es el uso de un complemento en versión beta y Jeedom en versión estable, o al revés. Para obtener los detalles del error, hay que consultar el archivo de registro http.error (en /var/www/html/log).

### Cuando realizo un comando, hay un indicador que gira sin parar
Una vez más, esto suele deberse a que el plugin está en fase beta, mientras que Jeedom está en versión estable. Para ver el error, hay que pulsar F12 y luego ir a la consola.

### Ya no tengo acceso a Jeedom, ni a través de la interfaz web ni desde la consola por SSH
Este error no se debe a Jeedom, sino a un problema del sistema.
Si el problema persiste tras una reinstalación, se recomienda ponerse en contacto con el servicio técnico para descartar un fallo de hardware. Aquí tienes la [documentación](https://doc.jeedom.com/installation/es_ES/recovery) para el Smart

### Mi escenario no se detiene nunca/no se detiene
Es recomendable revisar los comandos ejecutados por el escenario, ya que a menudo el problema se debe a un comando que no se completa.

### Tengo problemas de inestabilidad o errores 504
Comprueba si tu sistema de archivos está dañado; en SSH, el comando es: ```sudo dmesg | grep error```.

### Me aparece el siguiente error: SQLSTATE\[HY000\] \[2002\] No se puede conectar al servidor MySQL local a través del socket '/var/run/mysqld/mysqld.sock'
Esto se debe a que MySQL se ha detenido, lo cual no es normal; los casos más habituales son:

- Falta de espacio en el sistema de archivos (se puede comprobar ejecutando el comando «df -h» a través de SSH)
- Problema de corrupción de uno o varios archivos, lo que suele ocurrir tras un cierre incorrecto de Jeedom (corte de corriente).
- Problemas de memoria: el sistema carece de memoria y cierra el proceso que más recursos consume (a menudo, la base de datos). Esto se puede comprobar en la administración del sistema operativo y, a continuación, en «dmesg», donde debería aparecer un cierre provocado por «oom». Si es así, hay que reducir el consumo de Jeedom desactivando algunos complementos.

Por desgracia, no hay muchas soluciones si se trata del segundo caso; lo mejor es recuperar una copia de seguridad (disponible en /var/www/html/backup por defecto), reinstalar Jeedom y restaurar la copia de seguridad. También puedes comprobar por qué MySQL no se inicia desde una consola SSH:
``` {.bash}
sudo su -
service mysql stop
mysqld --verbose
```
O consulta el registro: /var/log/mysql/error.log

### Los botones «Apagar» y «Reiniciar» no funcionan
En una instalación «hazlo tú mismo» es normal. En SSH, hay que ejecutar el comando visudo y, al final del archivo, debes añadir: www-data ALL=(ALL)
NOPASSWD: ALL.

``` {.bash}
sudo service apache2 restart
```

### No veo algunos complementos del Market
Esto suele ocurrir si tu Jeedom no es compatible con el complemento. Por lo general, una actualización de Jeedom soluciona el problema.

### Tengo un dispositivo en tiempo de espera, pero no lo veo en el panel de control
Las alertas se clasifican por prioridad, de menor a mayor: tiempo de espera, aviso de batería, peligro de batería, alerta de aviso, alerta de peligro

### ¿Mi Jeedom sigue mostrando «Iniciando» incluso después de una hora?
Si estás montando tu propio sistema y utilizas Debian 9 o una versión posterior, comprueba que no se haya producido una actualización de Apache y, por lo tanto, que no haya vuelto a aparecer privateTmp (lo puedes comprobar ejecutando `ls /tmp` y comprueba si hay una carpeta «private\*Apache». Si es así, debes hacer lo siguiente:
```
mkdir /etc/systemd/system/apache2.service.d
echo "[Service]" > /etc/systemd/system/apache2.service.d/privatetmp.conf
echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf
```

### Tengo un problema con la hora en mis historiales
Intenta vaciar la caché de Chrome, ya que la visualización del historial se calcula en función de la hora del navegador.

### Me aparece el error «Se ha detectado un problema de red, reinicio de la red».
Jeedom no encuentra la pasarela o no consigue hacer ping a ella. Por lo general, esto ocurre si el router ADSL se reinicia (especialmente los Livebox) y Jeedom no se ha reiniciado o lo ha hecho más rápido que el router. Por seguridad, te indica que ha detectado un problema y reinicia el proceso de conexión a la red. Puedes desactivar este mecanismo accediendo a la configuración de Jeedom y desactivando la gestión de redes por parte de Jeedom.

### Me aparece el mensaje «Error al realizar la copia de seguridad de la base de datos. Comprueba que mysqldump esté presente».
Esto significa que Jeedom no consigue hacer una copia de seguridad de la base de datos, lo que podría indicar un problema de corrupción de la base de datos y del sistema de archivos. Por desgracia, no existe ningún comando milagroso para solucionarlo. Lo mejor es iniciar una copia de seguridad y analizar el registro de la misma. Entre los casos conocidos de problemas tenemos:

- una tabla de la base de datos está dañada => esto no pinta bien; hay que intentar repararla y, si no funciona, volver a partir de la última copia de seguridad válida (si la tienes en una tarjeta SD, es un buen momento para cambiarla)
- No hay suficiente espacio en el sistema de archivos => consulta la página de estado; allí podrás verlo.

### Me aparecen errores del tipo «Class 'eqLogic' not found», parece que faltan algunos archivos o me sale una página en blanco
Es un error bastante grave; lo más sencillo es hacer
```
mkdir -p /root/tmp/
cd /root/tmp
wget https://github.com/jeedom/core/archive/master.zip
unzip master.zip
cp -R /root/tmp/core-master/* /var/www/html
rm -rf /root/tmp/core-master
```

### Me aparece el error MYSQL_ATTR_INIT_COMMAND en scenario_execution
En la administración de Jeedom, en la sección OS/DB y, a continuación, en la consola del sistema, hay que hacer lo siguiente:
```
yes | sudo apt install -y php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc php-common php-dev php-zip php-ssh2 php-mbstring php-ldap
```

### No consigo instalar las dependencias de un complemento; me aparece un error del tipo: «E: dpkg se ha interrumpido. Es necesario utilizar «sudo dpkg --configure -a» para solucionar el problema» o «E: No se ha podido obtener el bloqueo /var/lib/dpkg/lock».

Se necesita:

- reiniciar Jeedom
- Accede a la configuración del dispositivo (botón con el icono de rueda dentada en la esquina superior derecha y, a continuación, «Configuración» en la versión 3 o «Ajustes» -> «Sistema» -> «Configuración» en la versión 4).
- Ve a la pestaña «SO/BD»
- Iniciar la administración del sistema
- haz clic en «dpkg configure»
- esperar 10 minutos
- reiniciar las dependencias del complemento que se ha bloqueado

### Me aparece este error al instalar las dependencias de un complemento: «from pip._internal import main»

Hay que ejecutar lo siguiente en la consola del sistema de Jeedom o mediante SSH:

````
sudo easy_install pip
sudo easy_install3 pip
````

A continuación, reiniciar las dependencias


### Desde la versión 4.2, ya no puedo mostrar iframes

La versión Core 4.2 mejora considerablemente la seguridad de Jeedom. Si realmente (con pleno conocimiento de causa) necesitas volver a una versión no segura de tu Jeedom:
Ve a **Ajustes -> Sistema -> Configuración** y, a continuación, a **SO/BD**, abre la consola de administración del sistema y haz clic en **Apache no seguro**. Se recomienda reiniciar Jeedom tras realizar este cambio.

### Desde la versión 4.2, algunos complementos han dejado de funcionar y en la consola del navegador (tecla F12) me aparecen errores 403.

Esto se debe a las medidas de seguridad de Apache, que obligan a los desarrolladores de complementos a colocar los archivos adecuados en los directorios correctos para limitar la superficie de ataque de Jeedom. Estas medidas de seguridad se configuran en el archivo .htaccess (que se sobrescribe con cada actualización del núcleo). Puedes crear un archivo .htaccess_custom con tus propias reglas que, si existe, se utilizará en lugar del archivo .htaccess del núcleo.
