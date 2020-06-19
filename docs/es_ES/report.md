# Rapport
**Análisis → Informe**

Esta página le permite ver todos los informes generados por la acción del informe (consulte la documentación del escenario).

## Principe

Un informe es una captura de pantalla de la interfaz Jeedom en un momento t.

> **Nota**
>
> Esta captura está adaptada para no tomar la barra de menú y otros elementos innecesarios en este tipo de uso.

Puede hacer esto en vistas, diseños, páginas de paneles....

La generación se desencadena desde un escenario con el comando de informe.
Puede elegir que le enviemos este informe mediante un comando de mensaje (correo electrónico, telegrama, etc).

## Utilisation

Su uso es muy sencillo. Seleccione a la izquierda si quiere ver :

- Ver informes.
- Informes de diseño.
- Informes del panel de complementos.
- Informes de equipos (para un resumen de la batería de cada módulo).

Luego seleccione el nombre del informe en cuestión. Luego verá todas las fechas de los informes disponibles.

> **Importante**
>
> La eliminación automática se realiza de forma predeterminada para informes de más de 30 días. Puede configurar este retraso en la configuración de Jeedom.

Una vez que se selecciona el informe, puede verlo, descargarlo o eliminarlo.

También puede eliminar todas las copias de seguridad de un informe determinado

## FAQ

> Si tienes un error de Detalles :
> *cutycapt: error al cargar bibliotecas compartidas: libEGL.so: no se puede abrir el archivo de objeto compartido: No existe tal archivo o directorio*
> En ssh o en Preferencias → Sistema → Configuración : OS / DB / Administración del sistema hacer :
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so``````
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so``````
