<?php
/**
 * Plugin Name: Plugin de gestion d'anomalies
 * Description: Mini gestionnaires d'anomalies pour le Travail pratique 1 du cours IGL711
 * License: éducation
 * Version: 0.0.1
 * Author: Julien Aspirot
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


register_activation_hook( __FILE__, installation ) ;
function installation()
{
    include_once(plugin_dir_path(__FILE__) . 'includes/create_bd.php');
}

//Inclusion de tous les fichiers nécessaires
include_once(plugin_dir_path(__FILE__) . 'includes/create_bd.php'); //S'assure que les tables sont présentes

add_shortcode( 'mga_shortcode_ajout','mga_shortcode_ajout' );

function mga_shortcode_ajout(){
    ob_start();
    echo '<div class="support_bs">';
    include_once( plugin_dir_path(__FILE__) . 'includes/create_new_ticket.php' );
    echo '</div>';
    return ob_get_clean();
}

add_shortcode( 'mga_shortcode_listing','mga_shortcode_listing' );

function mga_shortcode_listing(){
    ob_start();
    echo '<div class="support_bs">';
    include_once( plugin_dir_path(__FILE__) . 'includes/listing_anomalies.php' );
    echo '</div>';
    return ob_get_clean();
}

add_shortcode( 'mga_shortcode_single','mga_shortcode_single' );

function mga_shortcode_single(){
    ob_start();
    echo '<div class="support_bs">';
    include_once( plugin_dir_path(__FILE__) . 'includes/single_anomalie.php' );
    echo '</div>';
    return ob_get_clean();
}

add_action( 'wp_enqueue_scripts', 'loadScripts' );
add_action( 'wp_enqueue_style', 'loadScripts' );

function loadScripts(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_style('bootstrap', plugin_dir_url( __FILE__ ) . 'asset/js/bootstrap/css/bootstrap.css');
    wp_enqueue_style('display_ticket', plugin_dir_url( __FILE__ ) . 'asset/css/display_ticket.css');
    wp_enqueue_style('public', plugin_dir_url( __FILE__ ) . 'asset/css/public.css');
    wp_enqueue_script('bootstrap', plugin_dir_url( __FILE__ ) . 'asset/js/bootstrap/js/bootstrap.min.js');
}












