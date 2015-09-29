<?php
class TestTransactionnels extends PHPUnit_Framework_TestCase
{
    public function testNewAnomalie(){
        global $wpdb;
        $values=array(
            'sujet'=>'sujet1',
            'description_courte'=>'desc1',
            'description'=>'desc2',
            'created_by'=>'0',
            'guest_name'=>'eric',
            'guest_email'=>'eric@eric.com',
            'type'=>'guest',
            'version'=>'1.1',
            'status'=>'nouveau',
            'cat_id'=>'1',
            'create_time'=>current_time('mysql', 1),
            'update_time'=>current_time('mysql', 1),
            'priority'=>'1',
        );
        //Insersion des données contenues dans $values dans la table mga_anomalies
        $wpdb->insert($wpdb->prefix.'mga_anomalies',$values);

        //Get ID
        $sql="select LAST_INSERT_ID() as id";
        $row = $wpdb->get_row( $sql );
        $id = $row->id;

        // Verification
        $sql="select *
		FROM {$wpdb->prefix}mga_anomalies WHERE id= $id";

        /**
         * \var $ticket
         * \brief  La variable ticket contient tous les informations relative au tuple
         */
        $ticket = $wpdb->get_row( $sql );

        $this->assertEquals('sujet1', $ticket->sujet);
        $this->assertEquals('desc1', $ticket->description_courte);
        $this->assertEquals('desc2', $ticket->description);
        $this->assertEquals('0', $ticket->created_by);
        $this->assertEquals('eric', $ticket->guest_name);
        $this->assertEquals('eric@eric.com', $ticket->guest_email);
        $this->assertEquals('guest', $ticket->type);
        $this->assertEquals('1.1', $ticket->version);
        $this->assertEquals('nouveau', $ticket->status);
        $this->assertEquals('1', $ticket->cat_id);
        $this->assertEquals('1', $ticket->priority);

        return $id;
    }

    /**
     * @depends testNewAnomalie
     */
    public function testEditAnomalie($id){
        global $wpdb;
        $values=array(
            'status'=>'rejeté',
            'cat_id'=>'2',
            'update_time'=>current_time('mysql', 1),
            'priority'=>'2'
        );

        //On effectue une MàJ dans la base de données pour la table mga_anomalies
        $wpdb->update($wpdb->prefix.'mga_anomalies',$values,array('id' => $id));

        // Verification
        $sql="select *
		FROM {$wpdb->prefix}mga_anomalies WHERE id= $id";


        /**
         * \var $ticket
         * \brief  La variable ticket contient tous les informations relative au tuple
         */
        $ticket = $wpdb->get_row( $sql );

        $this->assertEquals('rejeté', $ticket->status);
        $this->assertEquals('2', $ticket->cat_id);
        $this->assertEquals('2', $ticket->priority);

        return $id;
    }

    /**
     * @depends testNewAnomalie
     */
    public function testAddCommentaire($id){
        global $wpdb;
        //Ajout d'un commentaire
        //Variable values contient un array assosiatif de forme attribut => valeur
        $values=array(
            'anomalie_id'=>$id,
            'body'=>"body1",
            'guest_name' => 'Responsable',
            'create_time'=>current_time('mysql', 1),
            'created_by'=>'0'
        );

        //On effectue une insersion dans la base de données pour la table mga_anomalie_commentaires
        $wpdb->insert($wpdb->prefix.'mga_anomalie_commentaires',$values);

        // Verification
        $sql="select *
		FROM {$wpdb->prefix}mga_anomalie_commentaires WHERE id=LAST_INSERT_ID";


        /**
         * \var $ticket
         * \brief  La variable commentaire contient tous les informations relative au tuple
         */
        $commentaire = $wpdb->get_row( $sql );

        $this->assertEquals($id, $commentaire->anomalie_id);
        $this->assertEquals('body1', $commentaire->body);
        $this->assertEquals('Responsable', $commentaire->guest_name);
        $this->assertEquals('0', $commentaire->created_by);

        return $id;
    }
}
