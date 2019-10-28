<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {
	var $table = "users";
    var $searchable_fields = array('username', 'first_name', 'last_name', 'email');

    var $related = array(
            'groups' => array(
                    'type' => 'pivot', 
                    'pivot_table' => 'group_user',
                    'my_column' => 'user_id',
                    'other_column' => 'group_id',
                    'other_table' => 'groups',
                    'other_primary_key' => 'id'
                ),
            'galleries' => array(
                    'type' => 'column-reverse',
                    'my_column' => 'user_id', 
                    'other_table' => 'galleries',
                    'other_primary_key' => 'id'
                ),
            'photos' => array(
                    'type' => 'column-reverse',
                    'my_column' => 'user_id', 
                    'other_table' => 'photos',
                    'other_primary_key' => 'id'
                )
        );

	public function __construct(){
		parent::__construct();
	}

	public function prepare_password($pass){
		return md5($pass);
	}

	public function update_last_seen($id) {
        $this -> update(array("last_login" => date('Y-m-d H:i:s')), $id);
    }

    public function get_shortname($user){
    	if($user->first_name){
    		return $user->first_name;
    	}
    	return $user->username;
    }

    public function check_password($user, $password){
    	if($user->is_unusable == 1){
    		return false;
    	}

    	if($user->password == $this->prepare_password($password)){
    		return true;
    	}

    	return false;
    }
	
}