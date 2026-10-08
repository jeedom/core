# Editor masivo
**Ajustes → Sistema → Configuración | SO/BD**

Esta herramienta permite editar un gran número de dispositivos, comandos, objetos o escenarios. Es totalmente genérica y adopta automáticamente el esquema y la estructura de la base de datos de Jeedom. De este modo, es compatible con los complementos y la configuración de sus dispositivos.

> **Atención**
>
> Aunque esta herramienta resulta bastante fácil de usar, está dirigida a usuarios avanzados. De hecho, es muy sencillo modificar cualquier parámetro en decenas de dispositivos o cientos de comandos y, por lo tanto, dejar inoperativas ciertas funciones, o incluso el Core.

## Uso

La sección *Filtro* te permite seleccionar lo que deseas editar y, a continuación, añadir filtros de selección según sus parámetros. Un botón de prueba te permite, sin realizar ningún cambio, ver los elementos seleccionados por los filtros indicados.

La sección *Edición* permite modificar los parámetros de estos elementos.

- **Columna**: Parámetro.
- **Valor**: El valor del parámetro.
- **Valor JSON**: La propiedad del parámetro o su valor si es de tipo JSON (clave->valor).

### Ejemplos:

#### Cambiar el nombre de un grupo de escenarios

- En la sección *Filtro*, selecciona **Escenario**.
- Haz clic en el botón **+** para añadir un filtro.
- En este filtro, selecciona la columna *grupo* y, como valor, el nombre del grupo que deseas renombrar.
- Haz clic en el botón *Prueba* para ver los escenarios de este grupo.
- En la sección *Edición*, selecciona la columna *group* y, a continuación, introduce el nombre que desees en el campo «Valor».
- Haz clic en **Ejecutar**, en la esquina superior derecha.

#### Ocultar todos los dispositivos de un objeto o una habitación:

- En la sección *Filtro*, selecciona **Equipos**.
- Haz clic en el botón **+** para añadir un filtro.
- En este filtro, selecciona la columna *object_id* y, como valor, introduce el ID del objeto en cuestión (visible en Herramientas/Objetos, Vista general).
- Haz clic en el botón *Prueba* para ver los escenarios de este grupo.
- En la sección *Edición*, selecciona la columna *isvisible* e introduce el valor 0.
- Haz clic en **Ejecutar**, en la esquina superior derecha.
