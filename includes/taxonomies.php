<?php 

function register_taxonomy_event(){
    
    $labels = array(
        'name' => 'Categorías de eventos',
        'singular_name' => 'Categoría de evento',
    );

    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true,
    ];

    register_taxonomy( 'event_categories', 'event', $args );

}
add_action( 'init', 'register_taxonomy_event');

?>