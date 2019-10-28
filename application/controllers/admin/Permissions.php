<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$data = array();

		$data['search_term'] = $this->input->get('search_term');

		$objects = $this->permission_model;

		if($this->input->get('search_term')){
			$objects = $this->permission_model->search($data['search_term']);
		}

		if($this->input->get('sort_value')){
			$this->permission_model->order_by($this->input->get('sort_value'), $this->input->get('sort_direction'));
		}

		$data['objects'] = $this->permission_model->paginate(intval($this->per_page), intval($this->input->get('page_num')))->fetch();

		$query_string = query_string(array(
					'sort_value' => $this->input->get('sort_value'),
					'sort_direction' => $this->input->get('sort_direction'),
					'search_term' => $this->input->get('search_term')
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('admin/permissions' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);

		$this->render_page(lang('admin_permissions'), "users", 'admin/permissions', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function update($id){
		$this->has_permission_admin_redirect('permission-can-change');

		$data = array();

		$this->form_validation->set_rules('name', lang('admin_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'name' => $this->input->post('name')
            	);

            if($this->permission_model->update($values, $id)){
            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

		$data['object'] = $this->permission_model->find($id);

		$this->render_page(lang('admin_change_permission'), "users", 'admin/permissions_update', 'templates/admin_header', 'templates/admin_footer', $data);
	}
}