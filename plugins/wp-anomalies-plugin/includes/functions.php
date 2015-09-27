<?php
/**
 * Created by PhpStorm.
 * User: Éric Bergeron
 * Date: 2015-09-27
 * Time: 15:46
 */

/**
 * functions.php
 * @author Éric Bergeron <eric.master.bergeron@usherbrooke.ca>
 * @copyright Équipe 2 - IGL711
 * Contient des fonctions utiles
 */


/**
 * Retourne une couleur correspondante au status d'une issue
 * @param $ticket mixed Objet ticket contenant les informations de l'issue
 * @return string Couleur correspondante
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
 * Retourne une couleur correspondante à la priorité d'une issue
 * @param $ticket mixed Objet ticket contenant les informations de l'issue
 * @return string Couleur correspondante
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
 * Retourne une description du moment de la dernière modification d'un objet
 * @param $object mixed Objet ticket contenant les informations de l'issue
 * @return string Description du moment de la dernière modification
 */
function getModificationTimeDescription($object){
    if ($object->date_modified_month)  $modification_time='il y a ' .$object->date_modified_month . '  mois';
    else if ($object->date_modified_day) $modification_time='il y a ' .$object->date_modified_day.' jours';
    else if ($object->date_modified_hour)$modification_time='il y a ' .$object->date_modified_hour.' heures';
    else if ($object->date_modified_min) $modification_time='il y a ' .$object->date_modified_min.' minutes';
    else $modification_time='il y a ' .$object->date_modified_sec.' secondes';
    return $modification_time;
}