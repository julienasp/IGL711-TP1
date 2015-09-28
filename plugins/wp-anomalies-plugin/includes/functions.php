<?php

/**
 * \file functions.php
 * \brief Contient des fonctions utiles
 * \author Éric Bergeron <eric.master.bergeron@usherbrooke.ca>
 * \copyright Équipe 2 - IGL711
 * \date 26/09/2015
 */


/**
 * \brief Retourne une couleur correspondante au status d'une issue
 * \param mixed $ticket contenant les informations de l'issue
 * \return string Couleur correspondante
 */
function getStatusColor($ticket){
    $status_color='';
    switch ($ticket->status){
        case 'nouveau': $status_color='danger';break;
        case 'assigné': $status_color='warning';break;
        case 'rejeté': $status_color='info';break;
        case 'fermé': $status_color='success';break;
    }
    return $status_color;
}
/**
 * \fn getPriorityColor($ticket)
 * \brief Retourne une couleur correspondante à la priorité d'une issue
 * \param Object $ticket contenant les informations de l'issue
 * \return string Couleur correspondante
 */
function getPriorityColor($ticket){
    $priority_color='';
    switch ($ticket->priority){
        case '1': $priority_color='danger';break;
        case '2': $priority_color='warning';break;
        case '3': $priority_color='info';break;
    }
    return $priority_color;
}


/**
 * \fn getModificationTimeDescription($object)
 * \brief Retourne une description du moment de la dernière modification d'un objet
 * \param Object $object contenant les informations de l'issue
 * \return string Description du moment de la dernière modification
 */
function getModificationTimeDescription($object){
    if ($object->date_modified_month)  $modification_time='il y a ' .$object->date_modified_month . '  mois';
    else if ($object->date_modified_day) $modification_time='il y a ' .$object->date_modified_day.' jours';
    else if ($object->date_modified_hour)$modification_time='il y a ' .$object->date_modified_hour.' heures';
    else if ($object->date_modified_min) $modification_time='il y a ' .$object->date_modified_min.' minutes';
    else $modification_time='il y a ' .$object->date_modified_sec.' secondes';
    return $modification_time;
}

/**
 * \fn get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() )
 * \brief S'occupe de générer un avatar via le web avec un url
 * \param string $email <The email address>
 * \param string $s <Size in pixels, defaults to 80px [ 1 - 2048 ]>
 * \param string $d <Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]>
 * \param string $r <Maximum rating (inclusive) [ g | pg | r | x ]>
 * \param boole $img <True to return a complete IMG tag False for just the URL>
 * \param array $atts <Optional, additional key/value attributes to include in the IMG tag>
 * \return String containing either just a URL or a complete image tag
 * \source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}