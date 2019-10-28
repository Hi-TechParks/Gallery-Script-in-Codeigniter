<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$data = array();

		$data['search_term'] = $this->input->get('search_term');

		$objects = $this->user_model;

		if($this->input->get('search_term')){
			$objects = $this->user_model->search($data['search_term']);
		}

		if($this->input->get('sort_value')){
			$this->user_model->order_by($this->input->get('sort_value'), $this->input->get('sort_direction'));
		}

		if($this->input->get('sort_bool_value')){
			$this->user_model->where($this->input->get('sort_bool_value'), intval($this->input->get('sort_bool_direction')));
		}

		$data['objects'] = $this->user_model->paginate(intval($this->per_page), intval($this->input->get('page_num')))->fetch();

		$query_string = query_string(array(
					'sort_value' => $this->input->get('sort_value'),
					'sort_direction' => $this->input->get('sort_direction'),
					'search_term' => $this->input->get('search_term'),
					'sort_bool_value' => $this->input->get('sort_bool_value'),
					'sort_bool_direction' => $this->input->get('sort_bool_direction'),
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('admin/users' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);

		$this->render_page(lang('admin_users'), "users", 'admin/users', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function delete($id){
        $this->has_permission_admin_redirect('user-can-delete');

        if($id == $this->user->id){
			redirect('admin/users/change_details');
		}

        $this->user_model->delete($id);
        $this->user_model->delete_all_related($id);
        $this->success_flash(lang('admin_object_deleted_msg'));
        redirect('admin/users');
    }

	public function create(){
		$this->has_permission_admin_redirect('user-can-add');

		$data = array();

		$this->form_validation->set_rules('username', lang('admin_username'), 'trim|required|xss_clean|is_unique[users.username]');
		$this->form_validation->set_rules('first_name', lang('admin_first_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', lang('admin_last_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', lang('admin_email'), 'trim|required|xss_clean|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', lang('admin_password'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password_confirm', lang('admin_password_confirmation'), 'trim|required|xss_clean|matches[password]');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'username' => $this->input->post('username'),
            		'email' => $this->input->post('email'),
            		'first_name' => $this->input->post('first_name'),
            		'last_name' => $this->input->post('last_name'),
            		'password' => $this->user_model->prepare_password($this->input->post('password')),
            		'date_joined' => date('Y-m-d H:i:s')
            	);

            if($this->input->post('is_active')){
            	$values['is_active'] = 1;
            }else{
            	$values['is_active'] = 0;
            }

            if($this->input->post('is_staff')){
            	$values['is_staff'] = 1;
            }else{
            	$values['is_staff'] = 0;
            }

            if(intval($this->user->is_superuser) == 1){
            	if($this->input->post('is_superuser')){
	            	$values['is_superuser'] = 1;
	            }else{
					$values['is_superuser'] = 0;
	            }
            }

            $other_errors = false;

            if(isset($_FILES["profile_photo"]['name'])){
                $upload = upload_helper("profile_photo", false, "uploads/profile_photo/");

                if($upload["status"] == 0){
                    $this->error_flash($upload["error"]);
                    $other_errors = true;
                }else{
                    $values["profile_photo"] = $upload["names"][0];
                }
            }            

            if(!$other_errors and $id = $this->user_model->create($values)){
            	
            	foreach ($this->input->post('groups') as $value) {
            		$this->user_model->save_related('groups', $id, intval(trim($value)));
            	}

            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

        $data['groups'] = $this->group_model->fetch();

		$this->render_page(lang('admin_add_user'), "users", 'admin/users_add', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function update($id){
		$this->has_permission_admin_redirect('user-can-change');

		if($id == $this->user->id){
			redirect('admin/users/change_details');
		}

		$data = array();

		$this->form_validation->set_rules('username', lang('admin_username'), 'trim|required|xss_clean|callback_update_unique[users.username.' . $id . ']');
		$this->form_validation->set_rules('first_name', lang('admin_first_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', lang('admin_last_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', lang('admin_email'), 'trim|required|xss_clean|valid_email|callback_update_unique[users.email.' . $id . ']');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'username' => $this->input->post('username'),
            		'email' => $this->input->post('email'),
            		'first_name' => $this->input->post('first_name'),
            		'last_name' => $this->input->post('last_name'),
            	);

             if($this->input->post('is_active')){
            	$values['is_active'] = 1;
            }else{
            	$values['is_active'] = 0;
            }

            if($this->input->post('is_staff')){
            	$values['is_staff'] = 1;
            }else{
            	$values['is_staff'] = 0;
            }

            if(intval($this->user->is_superuser) == 1){
            	if($this->input->post('is_superuser')){
	            	$values['is_superuser'] = 1;
	            }else{
					$values['is_superuser'] = 0;
	            }
            }

            $other_errors = false;

            if(isset($_FILES["profile_photo"]['name'])){
                $upload = upload_helper("profile_photo", false, "uploads/profile_photo/");

                if($upload["status"] == 0){
                    $this->error_flash($upload["error"]);
                    $other_errors = true;
                }else{
                    $values["profile_photo"] = $upload["names"][0];
                    $info = $this->user_model->find($id);
                    if ($info->profile_photo) {
                        $exists = is_file("uploads/profile_photo/" . $info->profile_photoe);
                        if ($exists) {
                            unlink("uploads/profile_photo/" . $info->profile_photo);
                        }
                    }
                }
            }

            if(!$other_errors and $this->user_model->update($values, $id)){
            	$this->user_model->delete_related('groups', $id);
            	foreach ($this->input->post('groups') as $value) {
            		$this->user_model->save_related('groups', $id, intval(trim($value)));
            	}

            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

		$data['object'] = $this->user_model->find($id);
		$data['groups'] = $this->group_model->fetch();

		$this->render_page(lang('admin_change_user'), "users", 'admin/users_update', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function change_details(){
		$data = array();

		$id = $this->user->id;

		$this->form_validation->set_rules('username', lang('admin_username'), 'trim|required|xss_clean|callback_update_unique[users.username.' . $id . ']');
		$this->form_validation->set_rules('first_name', lang('admin_first_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', lang('admin_last_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', lang('admin_email'), 'trim|required|xss_clean|valid_email|callback_update_unique[users.email.' . $id . ']');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'username' => $this->input->post('username'),
            		'email' => $this->input->post('email'),
            		'first_name' => $this->input->post('first_name'),
            		'last_name' => $this->input->post('last_name'),
            	);

            $other_errors = false;

            if(isset($_FILES["profile_photo"]['name'])){
                $upload = upload_helper("profile_photo", false, "uploads/profile_photo/");

                if($upload["status"] == 0){
                    $this->error_flash($upload["error"]);
                    $other_errors = true;
                }else{
                    $values["profile_photo"] = $upload["names"][0];
                    $info = $this->user_model->find($id);
                    if ($info->profile_photo) {
                        $exists = is_file("uploads/profile_photo/" . $info->profile_photoe);
                        if ($exists) {
                            unlink("uploads/profile_photo/" . $info->profile_photo);
                        }
                    }
                }
            }

            if(!$other_errors and $this->user_model->update($values, $id)){
            	$this->user = $this->user_model->find($id);
            	$this->success_flash(lang('admin_my_details_saved_msg'));
            }else{
            	if(!$other_errors){
                    $this->error_flash(lang('admin_error_saving_msg'));
                }
            }
        }

		$data['object'] = $this->user_model->find($id);

		$this->render_page(lang('admin_change_my_details'), "users", 'admin/users_change_details', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function change_password(){
		$data = array();

		$id = $this->user->id;

		$this->form_validation->set_rules('current_password', lang('admin_current_password'), 'trim|required|xss_clean|callback_check_current_password[' . $this->user->password . ']');
		$this->form_validation->set_rules('password', lang('admin_password'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password_confirm', lang('admin_password_confirmation'), 'trim|required|xss_clean|matches[password]');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'password' => $this->user_model->prepare_password($this->input->post('password')),
            	);

            if($this->user_model->update($values, $id)){
            	$this->user = $this->user_model->find($id);
            	$this->success_flash(lang('admin_my_details_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

		$data['object'] = $this->user_model->find($id);

		$this->render_page(lang('admin_change_my_password'), "users", 'admin/users_change_password', 'templates/admin_header', 'templates/admin_footer', $data);
	}
}