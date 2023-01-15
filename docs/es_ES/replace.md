 # Remplacer
**Herramientas → Reemplazar**

Esta herramienta permite reemplazar rápidamente equipos y controles, por ejemplo, en el caso de un cambio de complemento o de un módulo.

Al igual que las opciones de reemplazo en la configuración avanzada de un comando, reemplaza los comandos en los escenarios y otros, pero también permite transferir las propiedades del equipo y los comandos.

## Filtres

Puede mostrar solo ciertos equipos para una mayor legibilidad, filtrando por objeto o por complemento.

> En el caso de sustitución de equipo por equipo de otro complemento, seleccione los dos complementos.

## Options

> **Observación**
>
> Si ninguna de estas opciones está marcada, el reemplazo equivale a usar la función *Reemplace este comando con el comando* en configuración avanzada.

- **Copiar la configuración del dispositivo de origen** :
Para cada equipo, se copiará desde el origen al destino (lista no exhaustiva) :
	* El objeto padre,
	* Las categorias,
	* Los propietarios *activo* y *visible*,
	* Comentarios y etiquetas,
	* Orden (Tablero),
	* El ancho y la altura (panel de mosaico),
	* Configuración de la curva de mosaico,
	* Parámetros opcionales,
	* La configuración de visualización de la tabla,
	* el tipo genérico,
	* La propiedad *se acabó el tiempo*
	* La configuración *autorefrescar*,
	* Alertas de batería y comunicación,

El equipo de origen también será reemplazado por el equipo de destino en el **Diseño** y los **Puntos de vista**.


*Este equipo también será reemplazado por el equipo de destino en Diseños y Vistas.*

- **Ocultar equipo fuente** : Permite hacer invisible el equipo de origen una vez reemplazado por el equipo de destino.

- **Copie la configuración del comando fuente** :
Para cada pedido, se copiará desde el origen al destino (lista no exhaustiva) :
	* La propiedad *visible*,
	* Orden (Tablero),
	* L'historisation,
	* Los widgets Dashboard y Mobile utilizados,
	* El tipo genérico,
	* Parámetros opcionales,
	* Las configuraciones *JeedomPreExecCmd* y *jeedomPostExecCmd* (acción),
	* Configuraciones de acción de valor (información),
	* Icono,
	* La activación y el directorio en *Línea de tiempo*,
	* Las configuraciones de *cálculo* y *redondo*,
	* La configuración de influxDB,
	* La configuración del valor de repetición,
	* Alertas,

- **Eliminar el historial de comandos de destino** : Elimina los datos del historial de comandos de destino.

- **Copiar historial de comandos de origen** : Copie el historial de comandos de origen en el comando de destino.



## Remplacements

El botón **Filtrar** En la parte superior derecha te permite visualizar todo el equipamiento, siguiendo los filtros por objeto y por plugin.

Para cada equipo :

- Marque la casilla al principio de la línea para activar su reemplazo.
- Seleccione a la derecha el equipo por el cual será reemplazado.
- Haga clic en su nombre para ver sus comandos e indique qué comandos los reemplazan. Al elegir un equipo, la herramienta rellena previamente estas opciones si encuentra un pedido del mismo tipo y el mismo nombre en el equipo de destino.


> **Observación**
>
> Cuando indica un dispositivo de destino en un dispositivo de origen, este dispositivo de destino está deshabilitado en la lista.
