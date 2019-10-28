<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends MY_Model {
	var $table = "groups";
	var $searchable_fields = array('name');

	var $related = array(
			'permissions' => array(
					'type' => 'pivot', 
					'pivot_table' => 'group_permission',
					'my_column' => 'group_id',
					'other_column' => 'permission_id',
					'other_table' => 'permissions',
					'other_primary_key' => 'id'
				),
			'users' => array(
					'type' => 'pivot', 
					'pivot_table' => 'group_user',
					'my_column' => 'group_id',
					'other_column' => 'user_id',
					'other_table' => 'users',
					'other_primary_key' => 'id'
				)
		);

	public function __construct(){
		parent::__construct();
	}
	
}