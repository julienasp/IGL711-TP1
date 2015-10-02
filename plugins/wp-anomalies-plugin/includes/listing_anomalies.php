<?php
/**
 * \file listing_anomalies.php
 * Contient le code php qui 'fetch' les anomalies et le code HTML pour afficher la liste d'anomalie.
 * \author Julien Aspirot <julien.aspirot@usherbrooke.ca>
 * \brief     Contient le code php qui 'fetch' les anomalies et le code HTML pour afficher la liste d'anomalie
 * \date 26/09/2015
 * \copyright Équipe 2 - IGL711
 *
 */

require_once(dirname(__FILE__)."/functions.php");

//Sécurité en cas d'accès direct
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * \var $wpdb
 * \brief  Variable global de l'objet Wordpress DateBase
 */
global $wpdb;


/**
 * \var $current_user
 * \brief  Variable global pour avoir les informations de l'utilisateur courant
 */
global $current_user;
get_currentuserinfo();

//Permet de determiner la page courante avec l'argument noPage reçu en HTTP GET
$current_page= max( 1, ( isset( $_GET['noPage'] ) ) ? $_GET['noPage'] : 1 );

$total_pages=ceil(get_count_anomalies($wpdb)/10);
// La variable ticket contient tous les tuples d'anomalies pour le noPage reçu en HTTP GET
$tickets = get_anomalies_from_page($wpdb, $current_page);

//La suite consiste à l'utilisation de la variable tickets et ses informations dans le template HTML ci-dessous
?>
<div class="table-responsive">
    <table class="table table-striped table-hover" style="margin-top: 10px;">
        <tr>
            <th>#</th>
            <th>Status</th>
            <th>Sujets</th>
            <th class="category">Categories</th>
            <th class='priority'>Prioritées</th>
            <th>Dernières MàJ</th>
        </tr>
        <?php
        foreach ($tickets as $ticket){
            $raised_by=$ticket->guest_name;
            $modified=getModificationTimeDescription($ticket);
            $status_color=getStatusColor($ticket);
            $priority_color=getPriorityColor($ticket);

            echo "<tr class='".$status_color."' style='cursor:pointer;' onclick='window.location.href=\"" . get_permalink($GLOBALS['page_detail_id']) . "?ticket_id=" .$ticket->id."\";'>";
            echo "<td>".$ticket->id."</td>";
            echo "<td><span class='label label-".$status_color."' style='font-size: 13px;'>".ucfirst($ticket->status)."<span></td>";
            echo "<td>".substr($ticket->sujet, 0,20)."...</td>";
            echo "<td class='category'>".$ticket->category."</td>";
            echo "<td class='priority'><span class='label label-".$priority_color."' style='font-size: 13px;'>".$ticket->priority."</span></td>";
            echo "<td>".$modified."</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <?php
    $prev_page_no=$current_page-1;
    $prev_class=(!$prev_page_no)?'disabled':'';
    $next_page_no=$current_page+1;
    $next_class=($total_pages==$current_page)?'disabled':'';
    ?>
    <ul class="pager" style="<?php echo ($total_pages < 2)? 'display: none;':'';?>">
        <li class="previous <?php echo $prev_class;?>"><a href="<?php echo the_permalink() . '?noPage='. $prev_page_no; ?>">&larr; Précédent</a></li>
        <li><?php echo $current_page;?> de <?php echo $total_pages;?> Pages</li>
        <li class="next <?php echo $next_class;?>"><a href="<?php echo the_permalink() . '?noPage='. $next_page_no; ?>" >Suivant &rarr;</a></li>
    </ul>
    <div style="text-align: center;<?php echo ($total_pages==0)? '':'display: none;';?>">Aucune anomalies</div>
    <hr style="<?php echo ($total_pages==0)? '':'display: none;';?>">
</div>

