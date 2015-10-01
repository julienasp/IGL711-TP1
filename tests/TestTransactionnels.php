<?php
class TestTransactionnels extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testMultipleNewAnomalie(){
        $jeuxEssaie = [
            [ /* Simple test */
                'sujet'=>'sujet1',
                'description_courte'=>'desc1',
                'description'=>'desc2',
                'created_by'=>'0',
                'guest_name'=>'eric',
                'guest_email'=>'eric@eric.com',
                'type'=>'guest',
                'version'=>'4.13',
                'status'=>'nouveau',
                'cat_id'=>'2',
                'create_time'=>current_time('mysql', 1),
                'update_time'=>current_time('mysql', 1),
                'priority'=>'2'
            ],
            [ /* High values */
                'sujet'=>'long subject long subject long subject long subject ',
                'description_courte'=>'long description long description long description long description long description ',
                'description'=>'long description long description long description long description long description
                                long description long description long description long description long description
                                long description long description long description long description long description
                                long description long description long description long description long description
                                long description long description long description long description long description
                                long description long description long description long description long description ',
                'created_by'=>'212312',
                'guest_name'=>'long name long name long name long name long name long name long name ',
                'guest_email'=>'lonnnnnnnnnnnnnnnnnnnnnnnnnnnng@emaillllllllllllllllllllllllllllll.com',
                'type'=>'guest',
                'version'=>'10.1.1213.55656.1221.3',
                'status'=>'assigné',
                'cat_id'=>'3',
                'create_time'=>current_time('mysql', 1),
                'update_time'=>current_time('mysql', 1),
                'priority'=>'3'
            ],
            [ /* Minimum values */
                'sujet'=>'',
                'description_courte'=>'',
                'description'=>'',
                'created_by'=>'0',
                'guest_name'=>'',
                'guest_email'=>'',
                'type'=>'guest',
                'version'=>'1.1',
                'status'=>'nouveau',
                'cat_id'=>'1',
                'create_time'=>current_time('mysql', 1),
                'update_time'=>current_time('mysql', 1),
                'priority'=>'1'
            ]
        ];
        $ids = [];
        foreach($jeuxEssaie as $jeu)
            $ids[] = $this->testNewAnomalie($jeu);
        return $ids;
    }

    /**
     * Test l'ajout d'une anomalie avec les valeurs indiquées
     * Utilisé pour simplifier testMultipleNewAnomalie
     * \param $values Valeurs du tuple à ajouter
     * \return int New ID
     */
    private function testNewAnomalie($values){
        global $wpdb;
        //Insersion des données contenues dans $values dans la table mga_anomalies
        $wpdb->insert($wpdb->prefix.'mga_anomalies',$values);

        //Get ID
        $sql="select LAST_INSERT_ID() as id";
        $row = $wpdb->get_row( $sql );
        $id = $row->id;

        // Verification
        $sql="select * FROM {$wpdb->prefix}mga_anomalies WHERE id= $id";
        $ticket = $wpdb->get_row( $sql );

        foreach($values as $key => $value)
            $this->assertEquals($value, $ticket->$key);

        return $id;
    }

    /**
     * @test
     * @depends testNewAnomalie
     */
    public function testMultipleEditAnomalie($ids){
        $jeuxEssaie = [
            [ /* Simple test */
                'status'=>'rejeté',
                'cat_id'=>'2',
                'update_time'=>current_time('mysql', 1),
                'priority'=>'2'
            ],
            [ /* High values */
                'status'=>'assigné',
                'cat_id'=>'3',
                'update_time'=>current_time('mysql', 1),
                'priority'=>'3'
            ],
            [ /* Minimum values */
                'status'=>'rejeté',
                'cat_id'=>'1',
                'update_time'=>current_time('mysql', 1),
                'priority'=>'1'
            ]
        ];
        foreach($jeuxEssaie as $jeu)
            $this->testEditAnomalie($jeu, $ids[0]);
        return $ids;
    }

    /**
     * Test la modification d'une anomalie avec les valeurs indiquées
     * Utilisé pour simplifier testMultipleEditAnomalie
     * \param $values array Valeurs du tuple à modifier
     * \param $id int ID du tuple à modifier
     */
    private function testEditAnomalie($values, $id){
        global $wpdb;

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

        foreach($values as $key => $value)
            $this->assertEquals($value, $ticket->$key);
    }

    /**
     * @test
     * @depends testNewAnomalie
     */
    public function testMultipleAddCommentaire($ids){
        $jeuxEssaie = [
            [ /* Simple test */
                'anomalie_id'=>$ids[0],
                'body'=>"body1",
                'guest_name' => 'Responsable',
                'create_time'=>current_time('mysql', 1),
                'created_by'=>'2'
            ],
            [ /* High values */
                'anomalie_id'=>$ids[1],
                'body'=>"long body long body long body long body long body long body long body long body
                         long body long body long body long body long body long body long body long body",
                'guest_name' => 'long Responsable long Responsable long Responsable long Responsable 
                                 long Responsable long Responsable long Responsable long Responsable ',
                'create_time'=>current_time('mysql', 1),
                'created_by'=>'1243346'
            ],
            [ /* Minimum values */
                'anomalie_id'=>$ids[2],
                'body'=>"",
                'guest_name' => '',
                'create_time'=>current_time('mysql', 1),
                'created_by'=>'0'
            ]
        ];
        foreach($jeuxEssaie as $jeu)
            $this->testAddCommentaire($jeu);
        return $ids;
    }

    /**
     * Test l'ajout d'un commentaire avec les valeurs indiquées
     * Utilisé pour simplifier testMultipleAddCommentaire
     * \param $values array Valeurs du tuple à modifier
     */
    private function testAddCommentaire($values){
        global $wpdb;
        
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

        foreach($values as $key => $value)
            $this->assertEquals($value, $commentaire->$key);
    }
}
