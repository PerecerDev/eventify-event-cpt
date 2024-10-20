<?php

function get_or_create_term( $name, $taxonomy, $parent = 0 ) {
    $name = sanitize_text_field( $name );

    $terms = get_terms( array(
        'taxonomy'   => $taxonomy,
        'name'       => $name,
        'parent'     => $parent,
        'hide_empty' => false,
    ) );

    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        return $terms[0]->term_id;
    }

    $term = wp_insert_term( $name, $taxonomy, array( 'parent' => $parent ) );

    if ( is_wp_error( $term ) ) {
        error_log( 'Error al crear el término: ' . $name . ' en la taxonomía: ' . $taxonomy . '. Error: ' . $term->get_error_message() );
        return false;
    }

    return $term['term_id'];
}

function event_exists_by_title($title) {
    $query = new WP_Query([
        'post_type'      => 'event',
        'post_status'    => 'publish',
        'title'          => $title,
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);

    return $query->have_posts();
}
function import_events_from_json() {
    $json_file_path = plugin_dir_path( __FILE__ ) . 'eventData.json';
    
    if ( ! file_exists( $json_file_path ) ) {
        error_log( 'Archivo JSON no encontrado: ' . $json_file_path );
        wp_redirect( admin_url('edit.php?post_type=event&import=failed') );
        exit;
    }

    $json_data = file_get_contents( $json_file_path );
    if ( false === $json_data ) {
        error_log( 'Error al leer el archivo JSON: ' . $json_file_path );
        wp_redirect( admin_url('edit.php?post_type=event&import=failed') );
        exit;
    }

    $events = json_decode( $json_data, true );
    if ( null === $events ) {
        error_log( 'Error al decodificar el archivo JSON: ' . $json_file_path );
        wp_redirect( admin_url('edit.php?post_type=event&import=failed') );
        exit;
    }

    if ( ! empty( $events['events'] ) ) {
        foreach ( $events['events'] as $event_data ) {

            // Verificar si el evento ya existe
            if ( ! event_exists_by_title( $event_data['title'] ) ) {

                $post_id = wp_insert_post([
                    'post_title'   => wp_strip_all_tags( $event_data['title'] ),
                    'post_content' => $event_data['description'],
                    'post_excerpt' => $event_data['excerpt'],
                    'post_status'  => 'publish',
                    'post_type'    => 'event',
                ]);

                if ( is_wp_error( $post_id ) ) {
                    error_log( 'Error al crear el post: ' . $event_data['title'] . '. Error: ' . $post_id->get_error_message() );
                    continue;
                }

                $category_ids = [];

                if ( ! empty( $event_data['categories'] ) && is_array( $event_data['categories'] ) ) {
                    foreach ( $event_data['categories'] as $category ) {
                        if ( ! is_array( $category ) ) {
                            error_log( 'Formato de categoría inválido para el evento: ' . $event_data['title'] );
                            continue;
                        }

                        $parent_name = isset( $category['parent'] ) ? $category['parent'] : '';
                        $child_name  = isset( $category['child'] ) ? $category['child'] : '';

                        if ( empty( $parent_name ) ) {
                            error_log( 'Categoría padre faltante para el evento: ' . $event_data['title'] );
                            continue;
                        }

                        $parent_term_id = get_or_create_term( $parent_name, 'category' );

                        if ( ! empty( $parent_term_id ) ) {
                            if ( ! empty( $child_name ) ) {
                                $child_term_id = get_or_create_term( $child_name, 'category', $parent_term_id );

                                if ( is_wp_error( $child_term_id ) ) {
                                    error_log( 'No se pudo obtener o crear la subcategoría: ' . $child_name . ' bajo el padre: ' . $parent_name . ' para el evento: ' . $event_data['title'] );
                                    continue;
                                }

                                $category_ids[] = $child_term_id;
                            } else {
                                $category_ids[] = $parent_term_id;
                            }
                        } 
                    }

                    if ( ! empty( $category_ids ) ) {
                        $set_terms_result = wp_set_object_terms( $post_id, $category_ids, 'category' );

                        if ( is_wp_error( $set_terms_result ) ) {
                            error_log( 'Error al asignar categorías al post: ' . $event_data['title'] . '. Error: ' . $set_terms_result->get_error_message() );
                        }
                    }
                }

                if ( ! empty( $event_data['tags'] ) && is_array( $event_data['tags'] ) ) {
                    $set_tags_result = wp_set_post_tags( $post_id, $event_data['tags'] );

                    if ( is_wp_error( $set_tags_result ) ) {
                        error_log( 'Error al asignar etiquetas al post: ' . $event_data['title'] . '. Error: ' . $set_tags_result->get_error_message() );
                    }
                }

                update_post_meta( $post_id, '_event_thumbnail', sanitize_text_field( $event_data['thumbnail'] ) );
                update_post_meta( $post_id, '_event_date', sanitize_text_field( $event_data['date'] ) );
                update_post_meta( $post_id, '_event_time', sanitize_text_field( $event_data['time'] ) );
                update_post_meta( $post_id, '_event_location', sanitize_text_field( $event_data['location'] ) );
                update_post_meta( $post_id, '_event_price', sanitize_text_field( $event_data['price'] ) );
                update_post_meta( $post_id, '_event_url_tickets', esc_url( $event_data['url_buy_ticket'] ) );
                update_post_meta( $post_id, '_tickets_left', intval( $event_data['tickets_left'] ) );
                update_post_meta( $post_id, '_main_event', $event_data['main_event'] ? '1' : '0' );
            }
        }
    }

    // Redirigir después de la importación
    //wp_redirect( admin_url('edit.php?post_type=event&import=success') );
    wp_redirect( admin_url('edit.php?post_type=event') );
    
    exit;
}

/**
 * Maneja la acción personalizada para importar eventos.
 */
function handle_import_events_action() {

    // Ejecuta la función de importación
    import_events_from_json();
}
add_action( 'admin_action_import_events_from_json', 'handle_import_events_action' );

/**
 * Añade un botón "Importar" en la página de listado de eventos.
 */
function add_import_button_to_cpt() {
    global $typenow;

    if ( $typenow == 'event' ) {
        // URL para activar la función de importación
        $import_url = admin_url( 'admin.php?action=import_events_from_json' );

        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    // Verifica si el botón "Añadir nuevo evento" existe
    var addNewButton = $('.wrap .page-title-action');
    if (addNewButton.length) {
        // Añadir el botón "Importar" después del botón "Añadir nuevo evento"
        addNewButton.after(
            '<a href="<?php echo esc_url( $import_url ); ?>" class="page-title-action button-primary" style="margin-left: 10px;">Importar Datos de Ejemplo</a>'
        );
    }
});
</script>
<?php
    }
}
add_action( 'admin_head', 'add_import_button_to_cpt' );

/**
 * Muestra un mensaje de éxito después de la importación.
 */
function show_import_success_message() {
    if ( isset( $_GET['import'] ) && $_GET['import'] === 'success' ) {
        echo '<div class="notice notice-success is-dismissible">
                <p>Importación de eventos completada con éxito.</p>
              </div>';
    } elseif ( isset( $_GET['import'] ) && $_GET['import'] === 'failed' ) {
        echo '<div class="notice notice-error is-dismissible">
                <p>Error al importar los eventos. Revisa los logs para más detalles.</p>
              </div>';
    }
}
add_action( 'admin_notices', 'show_import_success_message' );



//add_action( 'admin_init', 'import_events_from_json' );


?>