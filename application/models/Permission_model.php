<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends MY_Model {
	var $table = "permissions";
	var $searchable_fields = array('name', 'codename');

	public function __construct(){
		parent::__construct();
	}
	
}