<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct(){
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
    }


	public function index()
	{
		$this->form_validation->set_rules('username_email', lang('auth_username_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', lang('auth_password'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $username_email = $this->input->post('username_email');
            $password = $this->input->post('password');

            if(filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
		        $user = $this->user_model->find('email', $username_email);
		    }else {
		        $user = $this->user_model->find('username', $username_email);
		    }

		    if($user){
		    	if($this->user_model->check_password($user, $password)){
		    		$this->user_model->update_last_seen($user->id);
		    		$this->session->set_userdata(array('logged_in_user_ID' => $user->id));

		    		if($user->is_staff == 1){
		    			redirect('admin/dashboard');
		    		}

		    		redirect('home');
		    	}
		    }
            

            $this->error_flash(lang('auth_incorrect_details_msg'));
        }

        $this->render_page(lang('app_staff_login'), "auth", 'auth/login', 'templates/auth_header', 'templates/auth_footer');
	}

	public function logout()
	{
		$this->session->unset_userdata('logged_in_user_ID');

        redirect('auth');
	}

	public function registration()
	{
		$data = array();

		$this->form_validation->set_rules('username', lang('auth_username'), 'trim|required|xss_clean|is_unique[users.username]');
		$this->form_validation->set_rules('email', lang('auth_email'), 'trim|required|xss_clean|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', lang('auth_password'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password_confirm', lang('auth_password_confirmation'), 'trim|required|xss_clean|matches[password]');
		
		if ($this->form_validation->run() == TRUE){
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->user_model->prepare_password($this->input->post('password'));

            $id = $this->user_model->create(array(
            		'username' => $username,
            		'email' => $email,
            		'password' => $password,
            		'date_joined' => date('Y-m-d H:i:s')
            	));

            $token = $this->token_model->generate($id);

			$email_template = $this->email_model->get_template('email-verification', array('[URL]' => site_url('auth/verify_email/' . $token['token'] . '/' . $token['uuid'] . '/')));

			if($this->send_email($user->email, $email_template['subject'], $email_template['message'])){
				$this->success_flash(lang('auth_check_your_email_msg'));

				redirect('auth/registration');
			}else{
				$this->error_flash(lang('auth_error_sending_msg'));
			}           

            $this->error_flash(lang('auth_incorrect_details_msg'));
        }
		
		$this->render_page(lang('auth_registration'), "auth", 'auth/registration', 'templates/auth_header', 'templates/auth_footer', $data);
	}

	public function verify_email($token, $uuid)
	{
		$token = $this->token_model->find(array('token' => $token, 'uuid' => $uuid));

		if(!$token){
        	 $this->error_flash(lang('auth_incorrect_activation_link_msg'));
        }else{
        	$user = $this->user_model->find($token->user_id);
        	if($user){
        		$this->user_model->update(array('is_active' => 1), $user->id);
        		$this->token_model->delete($token->id);

        		$this->success_flash(lang('auth_account_activated_msg') . ' <a href="' . site_url('auth') . '">' . lang('auth_login') .'</a>');
        	}else{
				$this->error_flash(lang('auth_invalid_token'));
        	}
        }

		$this->render_page(lang('auth_email_verification'), "auth", 'auth/verify_email', 'templates/auth_header', 'templates/auth_footer');
	}

	public function change_password()
	{
		$this->form_validation->set_rules('username_email', lang('auth_username_email'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $username_email = $this->input->post('username_email');

            if(filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
		        $user = $this->user_model->find('email', $username_email);
		    }else {
		        $user = $this->user_model->find('username', $username_email);
		    }

		    if($user){
		    	if(!$user->is_active){
					$this->error_flash(lang('auth_inactive_account_msg'));
				}else{
					$this->token_model->delete(array('user_id' => $user->id));

					$token = $this->token_model->generate($user->id);

					$email_template = $this->email_model->get_template('password-reset', array('[URL]' => site_url('auth/reset_password/' . $token['token'] . '/' . $token['uuid'] . '/')));

					if($this->send_email($user->email, $email_template['subject'], $email_template['message'])){
						$this->success_flash(lang('auth_check_your_email_msg'));
						redirect('auth/change_password');
					}else{
						$this->error_flash(lang('auth_error_sending_msg'));
					}
				}
		    }else{
		    	$this->error_flash(lang('auth_incorrect_details_msg'));
		    }
        }

        $this->render_page(lang('auth_change_password'), "auth", 'auth/change_password', 'templates/auth_header', 'templates/auth_footer');
	}

	public function reset_password($token = null, $uuid = null)
	{
		$data = array();

		$data['token_uuid_url_part'] = '';
		if($token and $uuid){
			$data['token_uuid_url_part'] = '/' . $token . '/' . $uuid . '/';
		}

		$this->form_validation->set_rules('password', lang('auth_password'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('password_confirm', lang('auth_password_confirmation'), 'trim|required|xss_clean|matches[password]');

		if ($this->form_validation->run() == TRUE){
            $password = $this->user_model->prepare_password($this->input->post('password'));

			$token = $this->token_model->find(array('token' => $token, 'uuid' => $uuid));

			if(!$token){
	        	 $this->error_flash(lang('auth_incorrect_reset_link_msg'));
	        }else{
	        	$user = $this->user_model->find($token->user_id);
		        if($user){
		        	$this->user_model->update(array('password' => $password), $user->id);
		        	$this->token_model->delete($token->id);

		        	$this->success_flash(lang('auth_password_changed_msg') .' <a href="' . site_url('auth') . '">' . lang('auth_login') .'</a>');
		        }else{
					$this->error_flash(lang('auth_invalid_token'));
		        }
	        }
	    }

        $this->render_page(lang('auth_reset_password'), "auth", 'auth/reset_password', 'templates/auth_header', 'templates/auth_footer', $data);
	}
}
