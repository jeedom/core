# Registro de cambios de Jeedom V4.6

## 4.6.1

- Corrección de la edición de un escenario cuyo tiempo de espera es *null* en la base de datos

## 4.6.0

### Nuevas funciones

- Incorporación de un bloque «Mientras» en los escenarios ([Detalles](https://github.com/jeedom/core/pull/3234){:target="_blank"})
- Nuevo método de traducción de la interfaz ([Detalles](https://github.com/jeedom/core/pull/3251){:target="_blank"})
- Unificación de los antiguos widgets horarios *(`timeXxxx`)* a los widgets estándar con parámetros `time` correspondiente ([Detalles](https://github.com/jeedom/core/pull/3332){:target="_blank"})
- [Avanzado] Añadir un `healthcheck` para instalaciones en Docker ([Detalles](https://github.com/jeedom/core/pull/2998){:target="_blank"})

### Correcciones

- Corrección de la actualización automática de los gráficos ([Detalles](https://github.com/jeedom/core/pull/3178){:target="_blank"})
- Corrección de la función matemática `randText` ([Detalles](https://github.com/jeedom/core/pull/3197){:target="_blank"})
- Mejora de la fiabilidad en el uso de acciones específicas fuera de un escenario ([Detalles](https://github.com/jeedom/core/pull/3228){:target="_blank"})
- Corrección de la selección del intervalo de fechas *(Zoom)* con agrupación en el historial ([Detalles](https://github.com/jeedom/core/pull/3242){:target="_blank"})
- Mejor gestión de la limpieza de los registros de los complementos ([Detalles](https://github.com/jeedom/core/pull/3245){:target="_blank"})
- Corrección del paso de etiquetas al ejecutar un escenario sobre sí mismo ([Detalles](https://github.com/jeedom/core/pull/3255){:target="_blank"})
- Protección contra inyecciones de comandos en la API de TTS ([Detalles](https://github.com/jeedom/core/pull/3261){:target="_blank"})
- Protección contra inyecciones SQL en la gestión de vistas ([Detalles](https://github.com/jeedom/core/pull/3267){:target="_blank"})
- Protección contra inyecciones SQL en el archivo de historiales ([Detalles](https://github.com/jeedom/core/pull/3268){:target="_blank"})
- Corrección de la visibilidad del campo «carpeta» de la línea de tiempo en los escenarios ([Detalles](https://github.com/jeedom/core/pull/3305){:target="_blank"})
- Se ha corregido un error que podía vaciar aleatoriamente los registros de los escenarios ([Detalles](https://github.com/jeedom/core/pull/3316){:target="_blank"})
- Armonización de la duración máxima de ejecución de los bloques de escenarios «Bucle» y «Mientras» y de las acciones «Esperar» y «Pausa» *(1 hora como máximo)* ([Detalles](https://github.com/jeedom/core/pull/3341){:target="_blank"})
- Eliminación de las advertencias injustificadas del verificador de expresiones ([Detalles](https://github.com/jeedom/core/pull/3349){:target="_blank"})
- Corrección de la visualización de las unidades en la lista de comandos ([Detalles](https://github.com/jeedom/core/pull/3362){:target="_blank"})
- Corrección de los botones de acceso al registro de cambios del núcleo en el centro de actualizaciones ([Detalles](https://github.com/jeedom/core/pull/3368){:target="_blank"})
- [Avanzado] Corrección de errores en la configuración del proxy ([Detalles](https://github.com/jeedom/core/pull/3238){:target="_blank"})
- [Avanzado] Corrección de las actualizaciones a través de la API ([Detalles](https://github.com/jeedom/core/pull/3352){:target="_blank"})
- [Varios] Numerosas optimizaciones y correcciones de código, tanto en la interfaz (`Javascript`) como del funcionamiento del núcleo (`PHP`)

### Documentación

- Generación automática de notas de versión a medida que se realizan las integraciones ([Detalles](https://github.com/jeedom/core/pull/3278){:target="_blank"})
- Actualización de la documentación sobre escenarios con el bloque «Mientras» y la duración máxima de ejecución ([Detalles](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- Documentación de los widgets totalmente reescrita y ampliada ([Detalles](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- [Desarrolladores] Incorporación de PHPDoc en los archivos de clase ([Detalles](https://github.com/jeedom/core/pull/3365){:target="_blank"})

>**INFORMACIÓN**
>
>Esta versión también introduce una nueva organización en el desarrollo de Jeedom, que a partir de ahora se basa en tres ramas principales: `develop` *(integración continua)* → `release` *(próxima versión estable)* → `master` *(estable)*. Las ramas antiguas `alpha`, `beta` y `V4-stable` se eliminarán próximamente.\
>Documentación [Prueba beta de Jeedom](https://doc.jeedom.com/contribute/es_ES/beta){:target="_blank"}, [Colaborar en la documentación](https://doc.jeedom.com/contribute/es_ES/doc){:target="_blank"} y [Contribuir al núcleo o a los complementos](https://doc.jeedom.com/contribute/es_ES/core){:target="_blank"} se han reescrito en consecuencia.
