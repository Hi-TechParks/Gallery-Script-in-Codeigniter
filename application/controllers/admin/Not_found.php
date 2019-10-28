<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Not_found extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$this->render_page(lang('admin_not_found'), "dash", 'admin/not_found', 'templates/admin_header', 'templates/admin_footer');
	}
}