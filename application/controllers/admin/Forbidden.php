<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forbidden extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$this->render_page(lang('admin_forbidden'), "dash", 'admin/forbidden', 'templates/admin_header', 'templates/admin_footer');
	}
}