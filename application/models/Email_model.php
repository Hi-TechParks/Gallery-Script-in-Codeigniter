<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_model extends MY_Model {
	var $table = "email_templates";

	var $searchable_fields = array('name', 'slug', 'subject', 'body');

	public function __construct(){
		parent::__construct();
	}

	public function get_template($slug, $data = array()){
		 $return = array('subject' => '', 'message' => '');

		 $template = $this->find(array('slug' => $slug));

		 if($template){
			$return['subject'] = $template->subject;
			$return['message'] = $template->body;
			foreach ($data as $k => $v) {
	        	$return['subject'] = str_replace($k, $v, $return['subject']);
	        }
	        foreach ($data as $k => $v) {
	        	$return['message'] = str_replace($k, $v, $return['message']);
	        }
		 }

		 return $return;
	}
	
}