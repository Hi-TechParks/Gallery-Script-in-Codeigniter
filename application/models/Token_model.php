<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_model extends MY_Model {
	var $table = "tokens";

	public function __construct(){
		parent::__construct();
	}

	public function generate($user_id){
		$u = uniqid('uuid_');
		$t = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

		$this->insert(array('token' => $t, 'uuid' => $u, 'user_id' => $user_id));

		return array('token' => $t, 'uuid' => $u);
	}
	
}