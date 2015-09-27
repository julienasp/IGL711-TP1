<?php
/**
 * Created by PhpStorm.
 * User: JUASP-G73
 * Date: 9/27/2015
 * Time: 12:14 AM
 */

/**
 * single_anomalie.php
 * @author Julien Aspirot <julien.aspirot@usherbrooke.ca>
 * @copyright Équipe 2 - IGL711
 */

require_once(dirname(__FILE__)."/functions.php");

/**
 * @var objet $wpdb        Variable global de l'objet Wordpress DateBase
 *
*/
global $wpdb;

//On s'assure que l'argument reçu en HTTP est bien numeric
if(!is_numeric($_GET['ticket_id'])) die(); //sql injection

//On regarde s'il s'agit d'une MàJ de l'anomalie
if( isset($_POST['action']) && $_POST['action'] == 'majAnomalie')
{
    //Màj de l'anomalie
    //Variable values contient un array assosiatif de forme attribut => valeur
    $values=array(
        'status'=>$_POST['reply_ticket_status'],
        'cat_id'=>$_POST['reply_ticket_category'],
        'update_time'=>current_time('mysql', 1),
        'priority'=>$_POST['reply_ticket_priority']
    );

    //On effectue une MàJ dans la base de données pour la table mga_anomalies
    $wpdb->update($wpdb->prefix.'mga_anomalies',$values,array('id' => $_POST['ticket_id']));

    //Ajout d'un commentaire
    //Variable values contient un array assosiatif de forme attribut => valeur
    $values=array(
        'anomalie_id'=>$_POST['ticket_id'],
        'body'=>(strlen($_POST['replyBody']) > 0) ? htmlspecialchars($_POST['replyBody'],ENT_QUOTES) : "Mise à jour des détails.",
        'guest_name' => 'Responsable',
        'create_time'=>current_time('mysql', 1),
        'created_by'=>$_POST['user_id']
    );

    //On effectue une insersion dans la base de données pour la table mga_anomalie_commentaires
    $wpdb->insert($wpdb->prefix.'mga_anomalie_commentaires',$values);
}

//Ici nous allons "FETCHER" les informations dont nous avons besoins, donc les détails de l'anomalie, et les commentaires en lien avec cette dernière
//Requête SQL pour avoir les détails de l'anomalie avec le id reçu en HTTP GET
$sql="select *
		FROM {$wpdb->prefix}mga_anomalies WHERE id=".$_GET['ticket_id'];

// La variable ticket contient tous les informations relative au tuple de la base de données en lien avec le id reçu en HTTP GET
$ticket = $wpdb->get_row( $sql );

//Requête SQL pour avoir les commentaires en lien avec l'anomalie
$sql="select *,
		TIMESTAMPDIFF(MONTH,create_time,UTC_TIMESTAMP()) as date_modified_month,
		TIMESTAMPDIFF(DAY,create_time,UTC_TIMESTAMP()) as date_modified_day,
		TIMESTAMPDIFF(HOUR,create_time,UTC_TIMESTAMP()) as date_modified_hour,
 		TIMESTAMPDIFF(MINUTE,create_time,UTC_TIMESTAMP()) as date_modified_min,
 		TIMESTAMPDIFF(SECOND,create_time,UTC_TIMESTAMP()) as date_modified_sec
		FROM {$wpdb->prefix}mga_anomalie_commentaires WHERE anomalie_id=".$_GET['ticket_id'].' ORDER BY create_time DESC' ;

// La variable threads contient tous les commentaires en lien avec l'anomalie
$threads= $wpdb->get_results( $sql );

//La variable categories contient tous les categories présente dans la table mga_categories_anomalie
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mga_categories_anomalie" );

//La suite consiste à l'utilisation des variables ticket, threads et categories et leurs informations dans le template HTML ci-dessous
?>
<button class="btn btn-primary" style="margin-top: 10px;" onclick="window.location.href='<?php echo get_permalink(9);?>'">« Retour à la liste d'anomalies</button><br>
<h3>[Anomalie #<?php echo $_GET['ticket_id'];?>] <?php echo stripcslashes($ticket->sujet);?></h3>
<h4><?php echo $ticket->description_courte;?></h4>
<h6>Version: <?php echo $ticket->version;?></h6>

<form id="frmThreadReply" method="post" action="" onsubmit="replyTicket(event,this);">
    <input type="hidden" name="action" value="replyTicket">
    <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id'];?>">
    <input type="hidden" name="user_id" value="<?php echo $current_user->ID;?>">
    <input type="hidden" name="type" value="user">
    <input type="hidden" name="guest_name" value="">
    <input type="hidden" name="guest_email" value="">
    <div id="theadReplyContainer" style="width: 95%;">
        <textarea id="replyBody" name="replyBody" style="width: 95%;overflow-y:hidden;" onkeyup='this.rows = (this.value.split("\n").length||1);'></textarea>
        <table style="width: 95%" id="frontEndReplyTbl">
            <tr>
                <td>Status:</td>
                <td>
                    <select id="reply_ticket_status" name="reply_ticket_status" style="margin-top: 10px;">
                        <option value="nouveau" <?php echo ($ticket->status=='nouveau')?'selected="selected"':'';?>>Nouveau</option>
                        <option value="rejeté" <?php echo ($ticket->status=='rejeté')?'selected="selected"':'';?>>Rejeté</option>
                        <option value="assigné" <?php echo ($ticket->status=='assigné')?'selected="selected"':'';?>>Assigné</option>
                        <option value="fermé" <?php echo ($ticket->status=='fermé')?'selected="selected"':'';?>>Fermé</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Category:</td>
                <td>
                    <select id="reply_ticket_category" name="reply_ticket_category" style="margin-top: 10px;">
                        <?php
                        foreach ($categories as $category){
                            $selected=($category->id==$ticket->cat_id)?'selected="selected"':'';
                            echo '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Priority:</td>
                <td>
                    <select id="reply_ticket_priority" name="reply_ticket_priority" style="margin-top: 10px;">
                        <option value="1" <?php echo ($ticket->priority=='1')?'selected="selected"':'';?>>1</option>
                        <option value="2" <?php echo ($ticket->priority=='2')?'selected="selected"':'';?>>2</option>
                        <option value="3" <?php echo ($ticket->priority=='3')?'selected="selected"':'';?>>3</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="button" class="btn btn-success" value="Reset" onClick="this.form.reset()" />
        <input type="submit" class="btn btn-success" value="Submit Reply">&nbsp;
    </div>
    <input type="hidden" name="action" value="majAnomalie">
    <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id'] ;?>">
</form>
<div class="threadContainer" style="width: 95%;">
    <table class="replyUserInfo" style="width: 95%;margin-top: -9px;">
        <tr>
            <td style="width: 60px;padding-top: 9px;vertical-align:top;"><img src="<?php echo get_gravatar($ticket->guest_email,60);?>"></td>
            <td style="padding-left: 5px;vertical-align:top;">
                <div class="threadUserName"><?php echo $ticket->guest_name;?></div>
                <div><small class="threadUserType"><?php echo $ticket->guest_email;?></small></div>
            </td>
        </tr>
    </table>
    <div class="threadBody"><?php echo $ticket->description;?></div>
</div>
<?php foreach ($threads as $thread){?>
    <div class="threadContainer" style="width: 95%;">
        <?php
        $user_name=$thread->guest_name;
        $user_email=$thread->guest_email;

        $modified=getModificationTimeDescription($thread);

        $body=stripcslashes($thread->body);
        $body = preg_replace("/(\r\n|\n|\r)/", '<br>', $body);
        ?>
        <table class="replyUserInfo" style="width: 95%;margin-top: -9px;">
            <tr>
                <td style="width: 60px;padding-top: 9px;vertical-align:top;"><img src="<?php echo get_gravatar($user_email,60);?>"></td>
                <td style="padding-left: 5px;vertical-align:top;">
                    <div class="threadUserName"><?php echo $user_name;?></div>
                    <div><small class="threadUserType"><?php echo $user_email;?></small></div>
                </td>
            </tr>
        </table>
        <div class="threadBody"><?php echo $body;?></div>
    </div>
<?php }?>



<?php
/**
 * Fonction get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() )
 * S'occupe de générer un avatar via le web avec un url
 * @param string $email <The email address>
 * @param string $s <Size in pixels, defaults to 80px [ 1 - 2048 ]>
 * @param string $d <Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]>
 * @param string $r <Maximum rating (inclusive) [ g | pg | r | x ]>
 * @param boole $img <True to return a complete IMG tag False for just the URL>
 * @param array $atts <Optional, additional key/value attributes to include in the IMG tag>
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
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
?>
