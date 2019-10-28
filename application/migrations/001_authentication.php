<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Authentication extends CI_Migration {
    public function up(){
        if(!$this->db->table_exists('users')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'username' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '150',
                    ),
                    'first_name' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '30',
                        'null' => TRUE,
                    ),
                    'last_name' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '30',
                        'null' => TRUE,
                    ),
                    'email' => array(
                        'type' => 'TEXT',
                        'null' => TRUE,
                    ),
                    'password' => array(
                        'type' => 'TEXT',
                    ),
                    'is_staff' => array(
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    ),
                    'is_active' => array(
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    ),
                    'is_superuser' => array(
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    ),
                    'is_unusable' => array(
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    ),
                    'last_login' => array(
                        'type' => 'TIMESTAMP',
                        'null' => true,
                    ),
                    'date_joined' => array(
                        'type' => 'TIMESTAMP',
                        'null' => true,
                    ),
                    'profile_photo' => array(
                        'type' => 'TEXT',
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('users');

            $users_data = array(
                array(
                    'username' => 'admin',
                    'email' => 'admin@email.com',
                    'password' => md5('pass'),
                    'is_staff' => 1,
                    'is_active' => 1,
                    'is_superuser' => 1,
                    'date_joined' => date('Y-m-d H:i:s')
                )
            );

            $this->db->insert_batch('users', $users_data);
        }

        if(!$this->db->table_exists('groups')){
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
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('groups');
        }

        if(!$this->db->table_exists('permissions')){
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
                    'codename' => array(
                        'type' => 'TEXT',
                    ),
                    'is_uneditable' => array(
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('permissions');

            $permissions_data = array(
                array(
                    'name' => 'Users | Can add user',
                    'codename' => 'user-can-add',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Users | Can change user',
                    'codename' => 'user-can-change',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Users | Can delete user',
                    'codename' => 'user-can-delete',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Groups | Can add group',
                    'codename' => 'group-can-add',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Groups | Can change group',
                    'codename' => 'group-can-change',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Groups | Can delete group',
                    'codename' => 'group-can-delete',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Permissions | Can add permission',
                    'codename' => 'permission-can-add',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Permissions | Can change permission',
                    'codename' => 'permission-can-change',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Permissions | Can delete permission',
                    'codename' => 'permission-can-delete',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Email templates | Can add email template',
                    'codename' => 'email-template-can-add',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Email templates | Can change email template',
                    'codename' => 'email-template-can-change',
                    'is_uneditable' => 1
                ),array(
                    'name' => 'Email templates | Can delete email template',
                    'codename' => 'email-template-can-delete',
                    'is_uneditable' => 1
                )
            );

            $this->db->insert_batch('permissions', $permissions_data);
        }

        if(!$this->db->table_exists('group_permission')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'group_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    ),
                    'permission_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('group_permission');
        }

        if(!$this->db->table_exists('group_user')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'group_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    ),
                    'user_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('group_user');
        }

        if(!$this->db->table_exists('tokens')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'user_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => false,
                    ),
                    'token' => array(
                        'type' => 'TEXT',
                    ),
                    'uuid' => array(
                        'type' => 'TEXT',
                    )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('tokens');
        }

        if(!$this->db->table_exists('email_templates')){
            $this->dbforge->add_field(
                array(
                    'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => true,
                    'null' => false
                ),
                'name' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),
                'slug' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),
                'subject' => array(
                    'type' => 'TEXT',
                    'null' => true,
                ),
                'body' => array(
                    'type' => 'TEXT',
                    'null' => true,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('email_templates');

            $email_data = array(
                array(
                    'name' => 'Password Reset',
                    'slug' => 'password-reset',
                    'subject' => 'Password Reset',
                    'body' => '<p>Hi,</p><p>Please click on the link below to reset your password</p><p>[URL]</p><p>Thanks,</p><p>Admin</p>'
                ),
                array(
                    'name' => 'Email Verification',
                    'slug' => 'email-verification',
                    'subject' => 'Verify Email',
                    'body' => '<p>Hi,</p><p>Thanks for signing up. Please click on the link below to verify your email.</p><p>[URL]</p><p>Best regards,</p><p>Admin</p>'
                )
            );

            $this->db->insert_batch('email_templates', $email_data);
        }

    }

    public function down(){
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('groups');
        $this->dbforge->drop_table('permissions');
        $this->dbforge->drop_table('group_permission');
        $this->dbforge->drop_table('group_user');
        $this->dbforge->drop_table('tokens');

        $this->dbforge->drop_table('email_templates');
    }
}