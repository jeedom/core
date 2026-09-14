# Copias de seguridad
**Ajustes → Sistema → Copias de seguridad**

Jeedom ofrece la posibilidad de realizar copias de seguridad y restauraciones desde o hacia diferentes ubicaciones.
Esta página permite gestionar las copias de seguridad y las restauraciones.


A la izquierda encontrarás los ajustes y los botones de acción. A la derecha, aparece el estado en tiempo real de la acción en curso (copia de seguridad o restauración), si has iniciado alguna.

## Copias de seguridad

- **Copias de seguridad**: Permite iniciar una copia de seguridad de forma manual e inmediata (útil si quieres realizar un cambio crítico; esto te permitirá volver atrás). También dispones de un botón para iniciar una copia de seguridad sin enviar el archivo a la nube (requiere una suscripción; ver más abajo). El envío de una copia de seguridad a la nube puede llevar cierto tiempo. Por lo tanto, esta opción permite evitar una pérdida de tiempo excesiva.

- **Ubicación de las copias de seguridad**: Indica la carpeta en la que Jeedom copia las copias de seguridad. Se recomienda no cambiarla. Si se trata de una ruta relativa, su origen es la ubicación en la que está instalado Jeedom.

- **Número de días de almacenamiento de las copias de seguridad**: número de días durante los que se conservarán las copias de seguridad. Una vez transcurrido este plazo, las copias de seguridad se eliminarán. Ten cuidado de no establecer un número de días demasiado elevado, ya que, de lo contrario, tu sistema de archivos podría saturarse.

- **Tamaño total máximo de las copias de seguridad (MB)**: Permite limitar el espacio que ocupan todas las copias de seguridad en la carpeta de copias de seguridad. Si se supera este valor, Jeedom eliminará las copias de seguridad más antiguas hasta que el tamaño total vuelva a estar por debajo del máximo. No obstante, conservará al menos una copia de seguridad.

## Copias de seguridad locales

- **Copias de seguridad disponibles**: Lista de copias de seguridad disponibles.

- **Restaurar la copia de seguridad**: Inicia la restauración de la copia de seguridad seleccionada anteriormente.

- **Eliminar la copia de seguridad**: Elimina la copia de seguridad seleccionada anteriormente, solo en la carpeta local.

- **Enviar una copia de seguridad**: Permite enviar a la carpeta de copias de seguridad un archivo que se encuentre en el ordenador que se está utilizando actualmente (por ejemplo, permite restaurar un archivo recuperado anteriormente en un nuevo Jeedom o tras una reinstalación).

- **Descargar la copia de seguridad**: Permite descargar en tu ordenador el archivo de la copia de seguridad seleccionada anteriormente.

## Copias de seguridad de Market

- **Envío de copias de seguridad**: Indica a Jeedom que envíe las copias de seguridad a la nube del Market; ten en cuenta que es necesario haber contratado la suscripción.

- **Enviar una copia de seguridad**: permite enviar a la nube un archivo de copia de seguridad que se encuentra en tu ordenador.

- **Copias de seguridad disponibles**: Lista de copias de seguridad en la nube disponibles.

- **Restaurar la copia de seguridad**: Inicia la restauración de una copia de seguridad en la nube.

## Copias de seguridad de Samba

- **Envío de copias de seguridad**: Indica a Jeedom que envíe las copias de seguridad al recurso compartido de Samba configurado aquí: Ajustes → Sistema → Configuración: Actualizaciones.

- **Copias de seguridad disponibles**: Lista de copias de seguridad de Samba disponibles.

- **Restaurar la copia de seguridad**: Inicia la restauración de la copia de seguridad de Samba seleccionada anteriormente.

> **IMPORTANTE**
>
> Las copias de seguridad de Jeedom deben guardarse obligatoriamente en una carpeta exclusiva para ellas. El programa eliminará de la carpeta todo lo que no sea una copia de seguridad de Jeedom.


# ¿Qué se guarda?

Al realizar una copia de seguridad, Jeedom guardará todos sus archivos y la base de datos. Por lo tanto, esto incluye toda tu configuración (dispositivos, comandos, historiales, escenarios, diseño, etc.).

En cuanto a los protocolos, solo Z-Wave (OpenZwave) es un poco diferente, ya que no es posible guardar las inclusiones. Estas se incluyen directamente en el controlador, por lo que hay que conservar el mismo controlador para poder volver a encontrar los módulos Z-Wave.

> **Nota**
>
> El sistema en el que está instalado Jeedom no tiene copia de seguridad. Si has modificado los parámetros de este sistema (especialmente a través de SSH), tendrás que buscar una forma de recuperarlos en caso de que surja algún problema. Del mismo modo, las dependencias tampoco tienen copia de seguridad, por lo que habrá que reinstalarlas tras una restauración.

# Copia de seguridad en la nube

La copia de seguridad en la nube permite a Jeedom enviar tus copias de seguridad directamente al Market. Esto te permite restaurarlas fácilmente y asegurarte de que no las pierdas. El Market conserva las últimas 6 copias de seguridad. Para suscribirte, solo tienes que ir a tu página de **perfil** en el Market y, a continuación, a la pestaña **mis copias de seguridad**. Desde esta página, puedes recuperar una copia de seguridad o comprar una suscripción (por 1, 3, 6 o 12 meses).

> **Consejo**
>
> Puedes personalizar el nombre de los archivos de copia de seguridad desde la pestaña **Mis Jeedoms**, aunque debes evitar el uso de caracteres especiales.

# Frecuencia de las copias de seguridad automáticas

Jeedom realiza una copia de seguridad automática todos los días a la misma hora. Es posible modificarla desde el «Motor de tareas» (la tarea se denomina **Jeedom backup**), pero no es recomendable. De hecho, se calcula en función de la carga del Market.

# Preguntas frecuentes

>**No consigo restaurar la copia de seguridad que he recuperado desde Safari**
>
>Por defecto, Safari descomprime los archivos tar.gz (en tar), lo que hace que Jeedom ya no pueda utilizar la copia de seguridad; hay que volver a comprimirla (gzip) en formato tar.gz
