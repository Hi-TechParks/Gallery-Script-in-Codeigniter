<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends MY_Model {
	var $table = "galleries";
	var $searchable_fields = array('name','description');

	var $related = array(
			'photos' => array(
					'type' => 'column-reverse',
					'my_column' => 'gallery_id', 
					'other_table' => 'photos',
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