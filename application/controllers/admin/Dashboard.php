<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$data = array();

		$this->render_page(lang('admin_dashboard'), "dash", 'admin/dashboard', 'templates/admin_header', 'templates/admin_footer', $data);
	}
}