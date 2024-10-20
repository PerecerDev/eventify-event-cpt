<?php 

/*
* Plugin Name: Eventify
* Description: Crea y administra eventos con Eventify. Registra el CPT base de Eventos.
* Version: 1.0
* Author: Alex Perecer
*/

if ( ! defined( 'ABSPATH' ) ) exit;

//Include required files
require_once plugin_dir_path( __FILE__ ) . 'includes/post-type.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/template.php';

//Import example data
require_once plugin_dir_path( __FILE__ ) . 'mocks/import_event_data.php';

?>