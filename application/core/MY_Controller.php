<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    var $per_page = 50;
    
	public function __construct(){
        parent::__construct();

        $this->load->database();

        $this->load->helper(array('form','url','security', 'function', 'language', 'cookie'));
        $this->load->library(array('session', 'form_validation', 'email', 'pagination', 'upload'));
        $this->config->load('email', TRUE);
        $this->config->load('site', TRUE);

        $this->lang->load(array('form_validation', 'authentication', 'admin_dashboard', 'app'));

        $this->load->model('user_model');
        $this->load->model('permission_model');
        $this->load->model('group_model');
        $this->load->model('token_model');
        $this->load->model('email_model');

        $this->load->model('gallery_model');
        $this->load->model('photo_model');

    }

    public function render_page($title, $menu = "", $page, $header_template, $footer_template, $data = array()){
        $data['page_title'] = $title . ' | ' . $this->config->item('site_name', 'site');
        $data['active_menu'] = $menu;
        $this->load->view($header_template, $data);
        $this->load->view($page, $data);
        $this->load->view($footer_template, $data);
    }


	public function send_email($to, $subject, $message, $reply_to = null){
        $config['protocol'] = $this->config->item('protocol', 'email');
        $config['smtp_host'] = $this->config->item('smtp_host', 'email');
        $config['smtp_port'] = $this->config->item('smtp_port', 'email');
        $config['smtp_user'] = $this->config->item('smtp_user', 'email');
        $config['smtp_pass'] = $this->config->item('smtp_pass', 'email');
        $config['mailtype'] = $this->config->item('mailtype', 'email');
        $config['charset'] = $this->config->item('charset', 'email');
        $config['wordwrap'] = $this->config->item('wordwrap', 'email');
        $config['newline'] = $this->config->item('newline', 'email');   

        $this->email->initialize($config);                        

        $from_email = $this->config->item('default_from_email', 'email');

        $this->email->from($from_email[1], $from_email[0]);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if($reply_to){
            $this->email->reply_to($reply_to[0], $reply_to[1]);
        }
        
        if ($this->email->send()){
            return true;
        }else{
            return false;
        }
        

	}

    public function error_flash($msg){
        $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">' . $msg . '</div>');
    }

    public function success_flash($msg){
        $this->session->set_flashdata('msg','<div class="alert alert-success text-center">' . $msg . '</div>');
    }

    public function update_unique($value, $params)  {
        $this->form_validation->set_message('update_unique', lang('form_validation_update_unique'));

        list($table, $field, $current_id) = explode(".", $params);

        $query = $this->db->select()->from($table)->where($field, $value)->limit(1)->get();

        if ($query->row() && $query->row()->id != $current_id)
        {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_current_password($value, $params)  {
        $this->form_validation->set_message('check_current_password', lang('form_validation_current_password'));

        if ($this->user_model->prepare_password($value) != $params)
        {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function alpha_space_only($str){
        if (!preg_match("/^[a-zA-Z ]+$/",$str)){
            $this->form_validation->set_message('alpha_space_only', lang('form_validation_alpha_space_only'));
            return FALSE;
        }else{
            return TRUE;
        }
    }
}



class ADMIN_Controller extends MY_Controller {
    var $user = null;
    var $permissions = array();

    public function __construct(){
        parent::__construct();
        $this->only_staff();
        $this->get_permissions();
    }

    protected function only_staff(){
        if($user_id = $this->session->userdata('logged_in_user_ID')){
            $user = $this->user_model->find($user_id);

            if(!$user or $user->is_active == 0 or $user->is_staff == 0){
                redirect('home');
            }else{
                $this->user = $user;
            }
        }else{
            redirect('auth');
        }
    }

    protected function get_permissions(){
        if($this->user){
            $groups = $this->user_model->find_related('groups', $this->user->id);
            foreach ($groups as $group) {
                $permissions = $this->group_model->find_related('permissions', $group->id);
                foreach ($permissions as $permission) {
                    $this->permissions[] = $permission->codename;
                }
            }
        }
        
    }

    protected function has_permission($codename){
        if($this->user->is_superuser){
            return true;
        }

        if(in_array($codename, $this->permissions)){
            return true;
        }
        return false;
    }

    protected function has_permission_admin_redirect($codename){
        if($this->user->is_superuser){
            return true;
        }

        if(in_array($codename, $this->permissions)){
            return true;
        }
        return redirect('admin/forbidden');
    }

    public function render_page($title, $menu = "", $page, $header_template, $footer_template, $data = array()){
        $data['page_title'] = $title . ' | ' . lang('admin_admin') . ' | ' . $this->config->item('site_name', 'site');
        $data['active_menu'] = $menu;

        $data['auth_username'] = $this->user_model->get_shortname($this->user);
        $data['auth_user_photo'] = base_url('uploads/profile_photo/' . $this->user->profile_photo);

        $this->load->view($header_template, $data);
        $this->load->view($page, $data);
        $this->load->view($footer_template, $data);
    }
}