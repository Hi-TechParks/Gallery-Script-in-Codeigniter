<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Gallery_update extends CI_Migration {
    public function up(){
        $fields = array(
                'cover_photo_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => true,
                    )
        );
        $this->dbforge->add_column('galleries', $fields);
    }

    public function down(){
        
    }
}