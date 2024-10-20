<?php 

function register_cpt_event(){

    $labels = [
        'name' => 'Eventos',
        'singular_name' => 'Evento',
        'menu_name' => 'Eventos',
        'all_items' => 'Todos los eventos',
        'add_new_item' => 'Añadir nuevo evento',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => [
            'title', 'editor', 'excerpt', 'thumbnail', 
        ],
    ];

    register_post_type( 'event', $args );

}
add_action('init', 'register_cpt_event');

?>