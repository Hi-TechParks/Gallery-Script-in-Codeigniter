<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$data = array();

		$data['search_term'] = $this->input->get('search_term');

		$objects = $this->group_model;

		if($this->input->get('search_term')){
			$objects = $this->group_model->search($data['search_term']);
		}

		if($this->input->get('sort_value')){
			$this->group_model->order_by($this->input->get('sort_value'), $this->input->get('sort_direction'));
		}

		$data['objects'] = $this->group_model->paginate(intval($this->per_page), intval($this->input->get('page_num')))->fetch();

		$query_string = query_string(array(
					'sort_value' => $this->input->get('sort_value'),
					'sort_direction' => $this->input->get('sort_direction'),
					'search_term' => $this->input->get('search_term')
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('admin/groups' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);

		$this->render_page(lang('admin_groups'), "users", 'admin/groups', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function delete($id){
        $this->has_permission_admin_redirect('group-can-delete');
        
        $this->group_model->delete($id);
        $this->group_model->delete_all_related($id);
        $this->success_flash(lang('admin_object_deleted_msg'));
        redirect('admin/groups');
    }

	public function create(){
		$this->has_permission_admin_redirect('group-can-add');

		$data = array();

		$this->form_validation->set_rules('name', lang('admin_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'name' => $this->input->post('name')
            	);

            if($id = $this->group_model->create($values)){
            	
            	foreach ($this->input->post('permissions') as $value) {
            		$this->group_model->save_related('permissions', $id, intval(trim($value)));
            	}

            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

        $data['permissions'] = $this->permission_model->fetch();

		$this->render_page(lang('admin_add_group'), "users", 'admin/groups_add', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function update($id){
		$this->has_permission_admin_redirect('group-can-change');

		$data = array();

		$this->form_validation->set_rules('name', lang('admin_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'name' => $this->input->post('name')
            	);

            if($this->group_model->update($values, $id)){
            	$this->group_model->delete_related('permissions', $id);
            	foreach ($this->input->post('permissions') as $value) {
            		$this->group_model->save_related('permissions', $id, intval(trim($value)));
            	}

            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

		$data['object'] = $this->group_model->find($id);
		$data['permissions'] = $this->permission_model->fetch();

		$this->render_page(lang('admin_change_group'), "users", 'admin/groups_update', 'templates/admin_header', 'templates/admin_footer', $data);
	}
}