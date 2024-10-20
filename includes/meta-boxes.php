<?php

//Register Meta Boxes for CPT Event
function add_metaboxes_event() {
    global $post;

    if ( 'event' === $post->post_type ) {
        add_meta_box(
            'event_basic_info',
            'Información Básica del Evento',
            'event_basic_info_callback',
            'event',
            'normal',
            'high'
        );

        add_meta_box(
            'event_additional_info',
            'Detalles del Evento',
            'event_additional_info_callback',
            'event',
            'normal',
            'high'
        );

        add_meta_box(
            'event_status_info',
            'Disponibilidad y Estado',
            'event_status_info_callback',
            'event',
            'normal',
            'high'
        );

        add_meta_box(
            'event_thumbnail_info',
            'Imagen Destacada del Evento',
            'event_thumbnail_info_callback',
            'event',
            'side',
            'low'
        );
    }
}
add_action('add_meta_boxes', 'add_metaboxes_event');


function event_basic_info_callback( $post ) {
    $event_date = get_post_meta( $post->ID, '_event_date', true );
    $event_time = get_post_meta( $post->ID, '_event_time', true );
    $event_location = get_post_meta( $post->ID, '_event_location', true );

    ?>
<p>
    <label for="event_date"><strong>Fecha del Evento:</strong></label><br />
    <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" />
</p>
<p>
    <label for="event_time"><strong>Hora del Evento:</strong></label><br />
    <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr( $event_time ); ?>" />
</p>
<p>
    <label for="event_location"><strong>Lugar del Evento:</strong></label><br />
    <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr( $event_location ); ?>" />
</p>
<?php
}

function event_additional_info_callback( $post ) {
    $event_price = get_post_meta( $post->ID, '_event_price', true );
    $event_url_tickets = get_post_meta( $post->ID, '_event_url_tickets', true );

    ?>
<p>
    <label for="event_price"><strong>Precio del Evento:</strong></label><br />
    <input type="number" id="event_price" name="event_price" step="0.01"
        value="<?php echo esc_attr( $event_price ); ?>" />
</p>
<p>
    <label for="event_url_tickets"><strong>URL venta de entradas:</strong></label><br />
    <input type="url" id="event_url_tickets" name="event_url_tickets"
        value="<?php echo esc_attr( $event_url_tickets ); ?>" />
</p>
<?php
}

function event_status_info_callback( $post ) {
    $tickets_left = get_post_meta( $post->ID, '_tickets_left', true );
    $main_event = get_post_meta( $post->ID, '_main_event', true );
    ?>
<p>
    <label for="tickets_left"><strong>Entradas restantes:</strong></label><br />
    <input type="number" id="tickets_left" name="tickets_left" value="<?php echo esc_attr( $tickets_left ); ?>" />
</p>
<p>
    <label for="main_event"><strong>¿Es Evento Principal?</strong></label><br />
    <input type="checkbox" id="main_event" name="main_event" value="1" <?php checked( $main_event, '1' ); ?> />
</p>
<?php
}

function event_thumbnail_info_callback( $post ) {
    
    $thumbnail = "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Placeholder_view_vector.svg/991px-Placeholder_view_vector.svg.png";

    if ( $thumbnail ) {
        echo '<img src="' . esc_url( $thumbnail ) . '" alt="Imagen Destacada" style="max-width:100%;" />';
    } else {
        echo '<p>No hay una imagen destacada asignada a este evento.</p>';
    }
}

function save_custom_fields_event( $post_id ) {
    if ( 'event' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['event_date'] ) ) {
        $event_date = sanitize_text_field( $_POST['event_date'] );
        update_post_meta( $post_id, '_event_date', $event_date );
    }

    if ( isset( $_POST['event_time'] ) ) {
        $event_time = sanitize_text_field( $_POST['event_time'] );
        update_post_meta( $post_id, '_event_time', $event_time );
    }

    if ( isset( $_POST['event_location'] ) ) {
        $event_location = sanitize_text_field( $_POST['event_location'] );
        update_post_meta( $post_id, '_event_location', $event_location );
    }

    if ( isset( $_POST['event_price'] ) ) {
        $event_price = sanitize_text_field( $_POST['event_price'] );
        update_post_meta( $post_id, '_event_price', $event_price );
    }

    if ( isset( $_POST['event_url_tickets'] ) ) {
        $event_url_tickets = esc_url_raw( $_POST['event_url_tickets'] );
        update_post_meta( $post_id, '_event_url_tickets', $event_url_tickets );
    }

    if ( isset( $_POST['tickets_left'] ) ) {
        $tickets_left = intval( $_POST['tickets_left'] );
        update_post_meta( $post_id, '_tickets_left', $tickets_left );
    } else {
        update_post_meta( $post_id, '_tickets_left', '0' );
    }

    if ( isset( $_POST['main_event'] ) ) {
        update_post_meta( $post_id, '_main_event', '1' );
    } else {
        update_post_meta( $post_id, '_main_event', '0' );
    }
}
add_action( 'save_post', 'save_custom_fields_event' );
?>