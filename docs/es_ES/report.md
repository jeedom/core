Esta página le permite ver todos los informes generados por la acción del informe (consulte la documentación del escenario).

# ¿Qué es un informe? ?

Un informe es una captura de pantalla de la interfaz Jeedom en un instante T (la captura se adapta para no tomar la barra de menú y otros elementos innecesarios en este tipo de uso).

Puede hacer esto en vistas, diseño, página de panel....

Se activa desde una secuencia de comandos con el comando de informe, puede elegir que se le envíe este informe mediante un comando de mensaje (correo electrónico, telegrama....)

# Utilisation

Su uso es muy simple, selecciona si quieres ver :

-	ver informes
-	informes de degins
-	informes del panel de complementos
- Informes de equipos (para un resumen de la batería de cada módulo)

Luego selecciona el nombre del informe en cuestión y verá todas las fechas de los informes en la memoria

> **Importante**
>
> La eliminación automática se realiza de forma predeterminada para informes de más de 30 días (puede configurar este período en la configuración de Jeedom)

Una vez que el informe seleccionado puede verlo aparecer, vuelva a descargarlo o elimínelo.

También puede eliminar todas las copias de seguridad de un informe determinado

# FAQ

> **Si tienes un error de Detalles : cutycapt: error al cargar bibliotecas compartidas: libEGL.so: no se puede abrir el archivo de objeto compartido: No existe tal archivo o directorio**
>
> Es necesario en ssh o en Administración -&gt; Configuración -&gt; OS / DB -&gt; Sistema -&gt; Administración para hacer :
>sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so
>sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so
