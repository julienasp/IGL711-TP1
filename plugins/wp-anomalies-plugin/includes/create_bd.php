<?php
/**
 * \file create_bd.php
 * Contient le code pour la création des tables dans la BD de wordpress.
 * \author Julien Aspirot <julien.aspirot@usherbrooke.ca>
 * \brief  Contient le code pour la création des tables dans la BD de wordpress.
 * \date 26/09/2015
 * \copyright Équipe 2 - IGL711
 *
 */

//Sécurité en cas d'accès direct
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//Variable global pour l'accès à la base de données
global $wpdb;

//Création des tables pour la gestion des anomalies

//On valide que la table mga_anomalies n'existe pas et si c'est le cas alors on la fabrique
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

//On valide que la table mga_categories_anomalie n'existe pas et si c'est le cas alors on la fabrique
if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}mga_categories_anomalie'") != $wpdb->prefix . 'mga_categories_anomalie'){
    $wpdb->query("CREATE TABLE {$wpdb->prefix}mga_categories_anomalie (
	id integer not null auto_increment,
	name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,

	PRIMARY KEY (id)
	);");

	//Insersion des catégories de base. Soit Général, Logiciel et Autre
    $wpdb->insert($wpdb->prefix.'mga_categories_anomalie',array('name'=>'General'));
    $wpdb->insert($wpdb->prefix.'mga_categories_anomalie',array('name'=>'Logiciel'));
    $wpdb->insert($wpdb->prefix.'mga_categories_anomalie',array('name'=>'Autre'));
}

//On valide que la table mga_anomalie_commentaires n'existe pas et si c'est le cas alors on la fabrique
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
