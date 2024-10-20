<?php 

function load_event_template( $template ) {
    if ( is_singular( 'event' ) ) {
        $plugin_template = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-event.php';

        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        } 
    }
    return $template;
}
add_filter( 'template_include', 'load_event_template' );

?>