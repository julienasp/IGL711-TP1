<?php
/**
 * Created by PhpStorm.
 * User: JUASP-G73
 * Date: 9/26/2015
 * Time: 6:50 PM
 */

/**
 * create_new_ticket.php
 * @author Julien Aspirot <julien.aspirot@usherbrooke.ca>
 * @copyright Équipe 2 - IGL711
 * contient le code php pour l'affichage de certain éléments de la BD et le code HTML pour la création d'un ticket
 */

//Variable global pour l'accès à la base de données
global $wpdb;

//Variable qui contient toutes les catégories d'anomalies
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mga_categories_anomalie" );


//Validation d'une reception POST
if( isset($_POST['action']) && $_POST['action'] == "createNewTicket" )
{

    //Ajout de l'anomalie
    $values=array(
        'sujet'=>htmlspecialchars($_POST['create_ticket_subject'],ENT_QUOTES),
        'description_courte'=>htmlspecialchars($_POST['create_ticket_description_courte'],ENT_QUOTES),
        'description'=>htmlspecialchars($_POST['create_ticket_description'],ENT_QUOTES),
        'created_by'=>$_POST['user_id'],
        'guest_name'=>$_POST['guest_name'],
        'guest_email'=>$_POST['guest_email'],
        'type'=>$_POST['type'],
        'version'=>$_POST['create_ticket_version'],
        'status'=>'nouveau',
        'cat_id'=>$_POST['create_ticket_category'],
        'create_time'=>current_time('mysql', 1),
        'update_time'=>current_time('mysql', 1),
        'priority'=>$_POST['create_ticket_priority']
    );
    //Insersion des données contenues dans $values dans la table mga_anomalies
    $wpdb->insert($wpdb->prefix.'mga_anomalies',$values);
}

//La suite consiste à l'utilisation de la variable catégories et ses informations dans le template HTML ci-dessous
?>

<div class="tab-pane" id="create_ticket">
	<div id="create_ticket_container" style="">
        <form id="frmCreateNewTicketGeuest" action="" method="post">
            <span class="label label-info" style="font-size: 13px;">Votre nom</span><code>*</code><br>
            <input type="text" id="create_ticket_guest_name" name="guest_name" maxlength="20" style="width: 95%; margin-top: 10px;" /><br><br>
            <span class="label label-info" style="font-size: 13px;">Votre courriel</span><code>*</code><br>
            <input type="text" id="create_ticket_guest_email" name="guest_email" maxlength="50" style="width: 95%; margin-top: 10px;" /><br><br>
            <span class="label label-info" style="font-size: 13px;">Sujet</span><code>*</code><br>
            <input type="text" id="create_ticket_sujet" name="create_ticket_subject" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
            <span class="label label-info" style="font-size: 13px;">Version</span><code>*</code><br>
            <input type="text" id="create_ticket_version" name="create_ticket_version" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
            <span class="label label-info" style="font-size: 13px;">Description courte</span><code>*</code><br>
            <input type="text" id="create_ticket_description_courte" name="create_ticket_description_courte" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
            <span class="label label-info" style="font-size: 13px;">Description</span><code>*</code><br>
            <textarea id="create_ticket_body" name="create_ticket_description" style="margin-top: 10px; width: 95%; overflow-y:hidden;" onkeyup='this.rows = (this.value.split("\n").length||1);'></textarea><br><br>
            <div id="replyFloatedContainer" style="">
                <div class="replyFloatLeft">
                    <span class="label label-info" style="font-size: 13px;">Categories</span><br>
                    <select id="create_ticket_category" name="create_ticket_category" style="margin-top: 10px;">
                        <?php
                        foreach ($categories as $category){
                            echo '<option value="'.$category->id.'">'.$category->name.'</option>';
                        }
                        ?>
                    </select><br><br>
                </div>
                <div class="replyFloatLeft">
                    <span class="label label-info" style="font-size: 13px;">Prioritees</span><br>
                    <select id="create_ticket_priority" name="create_ticket_priority" style="margin-top: 10px;">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <br>
            <input type="hidden" name="action" value="createNewTicket">
            <input type="hidden" name="user_id" value="0">
            <input type="hidden" name="type" value="guest">
            <input type="submit" class="btn btn-success" value="Soumettre l'anomalie">
            <input type="button" class="btn btn-success" value="Vider le formulaire" onClick="this.form.reset()" />
        </form>
</div>
