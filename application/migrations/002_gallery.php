<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Gallery extends CI_Migration {
    public function up(){
        if(!$this->db->table_exists('galleries')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'name' => array(
                        'type' => 'TEXT',
                    ),
                    'description' => array(
                        'type' => 'TEXT',
                        'null' => true,
                    ),
                    'views' => array(
                        'type' => 'BIGINT',
                        'constraint' => 20,
                        'default' => 0,
                        'null' => false,
                    ),
                    'user_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    ),
                    'date_created' => array(
                        'type' => 'TIMESTAMP',
                        'null' => true,
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('galleries');
        }

        if(!$this->db->table_exists('photos')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'name' => array(
                        'type' => 'TEXT',
                    ),
                    'location' => array(
                        'type' => 'TEXT',
                    ),
                    'description' => array(
                        'type' => 'TEXT',
                        'null' => true,
                    ),
                    'views' => array(
                        'type' => 'BIGINT',
                        'constraint' => 20,
                        'default' => 0,
                        'null' => false,
                    ),
                    'user_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    ),
                    'status' => array(
                        'type' => 'TEXT',
                        'null' => true,
                    ),
                    'gallery_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    ),
                    'date_created' => array(
                        'type' => 'TIMESTAMP',
                        'null' => true,
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('photos');
        }

        //if($this->db->table_exists('permissions')){
            $permissions_data = array(
                array(
                    'name' => 'Galleries | Can add gallery',
                    'codename' => 'gallery-can-add',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Galleries | Can change gallery',
                    'codename' => 'gallery-can-change',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Galleries | Can delete gallery',
                    'codename' => 'gallery-can-delete',
                    'is_uneditable' => 1
                )
            );

            $this->db->insert_batch('permissions', $permissions_data);
        //}

    }

    public function down(){
        $this->dbforge->drop_table('galleries');
        $this->dbforge->drop_table('photos');
    }
}