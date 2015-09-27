<?php
/**
 * Created by PhpStorm.
 * User: JUASP-G73
 * Date: 9/26/2015
 * Time: 7:09 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//Gestion pour la BD
global $wpdb;

//Création des tables pour la gestion des anomalies
if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}mga_anomalies'") != $wpdb->prefix . 'mga_anomalies'){
    $wpdb->query("CREATE TABLE {$wpdb->prefix}mga_anomalies (
	id integer not null auto_increment,
	version float,
	sujet TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	description_courte TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	created_by integer,
	guest_name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	guest_email TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	type TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	status TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	cat_id integer,
	create_time datetime,
	update_time datetime,
	priority TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,

	PRIMARY KEY (id)
	);");
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}mga_categories_anomalie'") != $wpdb->prefix . 'mga_categories_anomalie'){
    $wpdb->query("CREATE TABLE {$wpdb->prefix}mga_categories_anomalie (
	id integer not null auto_increment,
	name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,

	PRIMARY KEY (id)
	);");

    $wpdb->insert($wpdb->prefix.'mga_categories_anomalie',array('name'=>'General'));
    $wpdb->insert($wpdb->prefix.'mga_categories_anomalie',array('name'=>'Logiciel'));
    $wpdb->insert($wpdb->prefix.'mga_categories_anomalie',array('name'=>'Autre'));
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}mga_anomalie_commentaires'") != $wpdb->prefix . 'mga_anomalie_commentaires'){
	$wpdb->query("CREATE TABLE {$wpdb->prefix}mga_anomalie_commentaires (
	id integer not null auto_increment,
	anomalie_id integer,
	body LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	create_time datetime,
	created_by integer,
	guest_name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	guest_email TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,

	PRIMARY KEY (id)
	);");
}
//Fin de la création des tables pour la BD
