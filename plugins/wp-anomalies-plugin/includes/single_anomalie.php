<?php
/**
 * Created by PhpStorm.
 * User: JUASP-G73
 * Date: 9/27/2015
 * Time: 12:14 AM
 */


global $wpdb;

if(!is_numeric($_GET['ticket_id'])) die(); //sql injection

if( isset($_POST['action']) && $_POST['action'] == 'majAnomalie')
{
    //Màj de l'anomalie
    $values=array(
        'status'=>$_POST['reply_ticket_status'],
        'cat_id'=>$_POST['reply_ticket_category'],
        'update_time'=>current_time('mysql', 1),
        'priority'=>$_POST['reply_ticket_priority']
    );
    $wpdb->update($wpdb->prefix.'mga_anomalies',$values,array('id' => $_POST['ticket_id']));

    //create thread
    $values=array(
        'anomalie_id'=>$_POST['ticket_id'],
        'body'=>(strlen($_POST['replyBody']) > 0) ? htmlspecialchars($_POST['replyBody'],ENT_QUOTES) : "Mise à jour des détails.",
        'guest_name' => 'Responsable',
        'create_time'=>current_time('mysql', 1),
        'created_by'=>$_POST['user_id']
    );
    $wpdb->insert($wpdb->prefix.'mga_anomalie_commentaires',$values);
}

$sql="select *
		FROM {$wpdb->prefix}mga_anomalies WHERE id=".$_GET['ticket_id'];
$ticket = $wpdb->get_row( $sql );

$sql="select *,
		TIMESTAMPDIFF(MONTH,create_time,UTC_TIMESTAMP()) as date_modified_month,
		TIMESTAMPDIFF(DAY,create_time,UTC_TIMESTAMP()) as date_modified_day,
		TIMESTAMPDIFF(HOUR,create_time,UTC_TIMESTAMP()) as date_modified_hour,
 		TIMESTAMPDIFF(MINUTE,create_time,UTC_TIMESTAMP()) as date_modified_min,
 		TIMESTAMPDIFF(SECOND,create_time,UTC_TIMESTAMP()) as date_modified_sec
		FROM {$wpdb->prefix}mga_anomalie_commentaires WHERE anomalie_id=".$_GET['ticket_id'].' ORDER BY create_time DESC' ;

$threads= $wpdb->get_results( $sql );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mga_categories_anomalie" );
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

        $modified='';
        if ($thread->date_modified_month) $modified='il y a ' . $thread->date_modified_month.' mois';
        else if ($thread->date_modified_day) $modified='il y a ' . $thread->date_modified_day.' jours';
        else if ($thread->date_modified_hour) $modified='il y a ' . $thread->date_modified_hour.' heures';
        else if ($thread->date_modified_min) $modified='il y a ' . $thread->date_modified_min.' minutes';
        else $modified='il y a ' . $thread->date_modified_sec.' secondes';

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
