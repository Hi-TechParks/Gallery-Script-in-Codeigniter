<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Photo_model extends MY_Model {
	var $table = "photos";
	var $searchable_fields = array('name', 'description');

	var $related = array(
			'gallery' => array(
					'type' => 'column',
					'other_column' => 'gallery_id', 
					'other_table' => 'galleries',
					'other_primary_key' => 'id'
				),
			'user' => array(
					'type' => 'column',
					'other_column' => 'user_id', 
					'other_table' => 'users',
					'other_primary_key' => 'id'
				)
		);

	public function __construct(){
		parent::__construct();
	}
	
}