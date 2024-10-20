# REQUISITOS MÍNIMOS

[x] Crear una nueva tipología de contenido CPT llamada 'Eventos'
[x] Categorías para eventos
[x] Añadir mínimo 3 campos personalizados (Fecha, Lugar, URL)
[x] Crear taxonomía de categorías
[x] Los eventos pueden tener más de 1 categoría

[x] Crear Bloque Gutenberg personalizado con el listado de eventos
[x] El listado incluye: Título, Categorías, Campos personalizados, Extracto
[x] Paginación de 5 eventos por página
[x] Filtrado por categorías


# PRUEBA TÉCNICA

Contexto:
Eventify, plugin de gestión de eventos

Se separa la lógica de la prueba en 2 plugins:
Eventify y Eventify Block Pack

* Eventify - eventify-event-cpt
Crea el CPT y todo lo relacionado con el almacenamiento y la gestión de la información de los Eventos.

* Eventify Block Pack - eventify-block-pack
Registra una nueva categoría de Bloques Gutenberg en el editor y añade 2 bloques para su uso.
Para la prueba, solo 1 está en uso (Event List).

# Para empezar a utilizar, es necesario descargar los plugins e instalarlos manualmente en el apartado de Plugins de Wordpress. Una vez instalados, deben ser activados.
# Una vez activados, aparecerá un nuevo elemento de menu llamado 'Eventos'. Accede a 'Todos los eventos' y junto al botón de añadir una nueva entrada, aparecerá un botón para importar datos de ejemplo. Pulsar y esperar a que se carguen los datos de los eventos.
# Crear una nueva entrada llamada Listado de Eventos. En ella, añadir el Bloque 'Event List' y guardar. Acceder a la página creada para ver filtro de categoría y paginación.

## REPOS
- Eventify CPT: https://github.com/PerecerDev/eventify-event-cpt
- Eventify Block Pack: https://github.com/PerecerDev/eventify-block-pack/
