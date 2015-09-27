<?php
/**
 * Plugin Name: Plugin de gestion d'anomalies
 * Description: Mini gestionnaires d'anomalies pour le Travail pratique 1 du cours IGL711
 * License: �ducation
 * Version: 0.0.1
 * Author: Julien Aspirot
 */

/**
 * Anomalies-plugin.php
 * @author Julien Aspirot <julien.aspirot@usherbrooke.ca>
 * @copyright �quipe 2 - IGL711
 */

//S�curit� en cas d'acc�s direct
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Lorsque le plugin est activ� la fonction installation est appell�
register_activation_hook( __FILE__, installation ) ;

/**
 * Fonction installation()
 * S'occupe d'inclure les fichiers importants du plugin.
 */
function installation()
{
    //create_bd.php s'occupe de b�tir l'infrastructure de notre base de donn�es
    include_once(plugin_dir_path(__FILE__) . 'includes/create_bd.php');
}

//Inclusion de tous les fichiers n�cessaires
include_once(plugin_dir_path(__FILE__) . 'includes/create_bd.php'); //S'assure que les tables sont pr�sentes


////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Ajout d'un shortcode [mga_shortcode_ajout] permettant de g�n�rer le code necessaire � l'ajout des anomalies
add_shortcode( 'mga_shortcode_ajout','mga_shortcode_ajout' );

/**
 * Fonction mga_shortcode_ajout()
 * S'occupe de g�n�rer tout le code html, css et php pour l'ajout d'une anomalie.
 */
function mga_shortcode_ajout(){
    ob_start();
    echo '<div class="support_bs">';
    include_once( plugin_dir_path(__FILE__) . 'includes/create_new_ticket.php' );
    echo '</div>';
    return ob_get_clean();
}

//Ajout d'un shortcode [mga_shortcode_listing] permettant de g�n�rer le code necessaire au listing des anomalies
add_shortcode( 'mga_shortcode_listing','mga_shortcode_listing' );

/**
 * Fonction mga_shortcode_listing()
 * S'occupe de g�n�rer tout le code html, css et php pour la table d'�num�ration des anomalies.
 */
function mga_shortcode_listing(){
    ob_start();
    echo '<div class="support_bs">';
    include_once( plugin_dir_path(__FILE__) . 'includes/listing_anomalies.php' );
    echo '</div>';
    return ob_get_clean();
}

//Ajout d'un shortcode [mga_shortcode_single] permettant de g�n�rer le code necessaire voir et modifier les d�tails d'une anomalie
add_shortcode( 'mga_shortcode_single','mga_shortcode_single' );

/**
 * Fonction mga_shortcode_single()
 * S'occupe de g�n�rer tout le code html, css et php pour afficher les d�tails d'une anomalie.
 */
function mga_shortcode_single(){
    ob_start();
    echo '<div class="support_bs">';
    include_once( plugin_dir_path(__FILE__) . 'includes/single_anomalie.php' );
    echo '</div>';
    return ob_get_clean();
}

//Ajout d'actions afin de bien lier tous les fichiers de style et javascript que nous allons ajouter dans le header
add_action( 'wp_enqueue_scripts', 'loadScripts' );
add_action( 'wp_enqueue_style', 'loadScripts' );

/**
 * Fonction loadScripts()
 * S'occupe de lier tous les fichiers de style .css et les fichiers javascripts que nous allons utiliser.
 */
function loadScripts(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_style('bootstrap', plugin_dir_url( __FILE__ ) . 'asset/js/bootstrap/css/bootstrap.css');
    wp_enqueue_style('display_ticket', plugin_dir_url( __FILE__ ) . 'asset/css/display_ticket.css');
    wp_enqueue_style('public', plugin_dir_url( __FILE__ ) . 'asset/css/public.css');
    wp_enqueue_script('bootstrap', plugin_dir_url( __FILE__ ) . 'asset/js/bootstrap/js/bootstrap.min.js');
}












