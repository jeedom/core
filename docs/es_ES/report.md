# Informe
**Análisis → Informe**

Esta página permite ver todos los informes que se han generado mediante la acción «report» (véase la documentación de los escenarios).

## Principio

Un informe es una captura de pantalla de la interfaz de Jeedom en un momento determinado.

> **Nota**
>
> Esta captura está adaptada para que no aparezca la barra de menú ni otros elementos innecesarios en este tipo de uso.

Puedes hacerlo en vistas, diseños, páginas del panel...

La generación se activa a partir de un escenario con el comando «report».
Puedes elegir que te envíen este informe mediante un servicio de mensajería (correo electrónico, Telegram, etc.).

## Uso

Su uso es muy sencillo. Selecciona a la izquierda lo que quieras ver:

- Informes de vistas.
- Informes sobre diseños.
- Informes de los paneles de complementos.
- Informes sobre los equipos (para obtener un resumen del estado de la batería de cada módulo).

A continuación, selecciona el nombre del informe en cuestión. Verás entonces todas las fechas de los informes disponibles.

> **Importante**
>
> Por defecto, los informes con más de 30 días de antigüedad se eliminan automáticamente. Puedes configurar este plazo en los ajustes de Jeedom.

Una vez seleccionado el informe, podrás verlo, descargarlo o eliminarlo.

También puedes eliminar todas las copias de seguridad de un informe concreto

## Preguntas frecuentes

> Si te aparece un error del tipo «Detalles:»
> *cutycapt: error al cargar las bibliotecas compartidas: libEGL.so: no se puede abrir el archivo de objeto compartido: No existe tal archivo o directorio*
> Hay que acceder mediante SSH o ir a Ajustes → Sistema → Configuración: SO/BD / Administración del sistema y hacer lo siguiente:
> ```sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so```
> ```sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so```
