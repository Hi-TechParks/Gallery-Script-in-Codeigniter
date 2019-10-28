<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galleries extends ADMIN_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$data = array();

		$data['search_term'] = $this->input->get('search_term');

		$objects = $this->gallery_model;

		if($this->input->get('search_term')){
			$objects = $this->gallery_model->search($data['search_term']);
		}

		$this->gallery_model->where('user_id', $this->user->id);

		if($this->input->get('sort_value')){
			$this->gallery_model->order_by($this->input->get('sort_value'), $this->input->get('sort_direction'));
		}

		$data['objects'] = $this->gallery_model->paginate(intval($this->per_page), intval($this->input->get('page_num')))->fetch();

		$query_string = query_string(array(
					'sort_value' => $this->input->get('sort_value'),
					'sort_direction' => $this->input->get('sort_direction'),
					'search_term' => $this->input->get('search_term')
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('admin/galleries' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);

		$this->render_page(lang('app_galleries'), "galleries", 'admin/app/galleries', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function photos($id){
		$data = array();

		if(isset($_FILES["photos"]['name']) OR isset($_FILES["photos"]['name'][0]))	{
            $upload = upload_helper("photos", true, "uploads/photos/gallery_" . $id);

            if($upload["status"] == 0){
                $this->error_flash($upload["error"]);
            }else{
                foreach ($upload["names"] as $photo_name) {
                	$values = array(
	            		'name' => $photo_name,
	            		'location' => "gallery_" . $id . "/" . $photo_name,
	            		'user_id' => $this->user->id,
	            		'gallery_id' => $id,
	            		'date_created' => date('Y-m-d H:i:s')
	            	);

            		$this->photo_model->create($values);
                }

                $this->success_flash(lang('app_photos_uploaded'));
            }
        }

		$data['objects'] = $this->gallery_model->find_related_limit('photos', $id, array('limit' => intval($this->per_page),'offset' => intval($this->input->get('page_num'))));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('admin/galleries/photos/' . $id . '/'),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);

		$data['gallery'] = $this->gallery_model->find($id);

		if($data['gallery']->user_id != $this->user->id){
			redirect('admin/galleries');
		}

		$data['gallery_id'] = $data['gallery']->id;


		$this->render_page(lang('app_photos_in_gallery'), "galleries", 'admin/app/galleries_photos', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function delete($id){

        $gallery = $this->gallery_model->find($id);

		if($gallery->user_id != $this->user->id){
			redirect('admin/galleries');
		}
        
        $this->gallery_model->delete($id);
        $this->gallery_model->delete_all_related($id);

        delete_directory("uploads/photos/gallery_" . $id);

        $this->success_flash(lang('admin_object_deleted_msg'));
        redirect('admin/galleries');
    }

    
    public function bulk_delete_photos($id){
    	$gallery = $this->gallery_model->find($id);

		if($gallery->user_id != $this->user->id){
			redirect('admin/galleries');
		}

    	$ids = $this->input->post('ids');

       
    	
    	foreach ($ids as $i) {
             
            $photo = $this->photo_model->find($i);
            $exists = is_file("uploads/photos/" . $photo->location);

            if ($exists) {
                unlink("uploads/photos/" . $photo->location);
            }
            $this->photo_model->delete($i);
        }

    	$this->success_flash(lang('app_photos_deleted_msg'));
        redirect('admin/galleries/photos/' . $id);
    }

    

	public function create(){

		$data = array();

		$this->form_validation->set_rules('name', lang('app_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', lang('app_description'), 'trim|xss_clean');
        $this->form_validation->set_rules('date_created', lang('admin_date_created'), 'trim|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'name' => $this->input->post('name'),
            		'description' => $this->input->post('description'),
            		'user_id' => $this->user->id,
            		'date_created' => date_decoder($this->input->post('date_created'))
            	);

            if($id = $this->gallery_model->create($values)){
            	mkdir("uploads/photos/gallery_" . $id, 0755);

                $this->success_flash(lang('admin_object_saved_msg'));
            	redirect('admin/galleries/photos/' . $id);
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

        $data['permissions'] = $this->permission_model->fetch();

		$this->render_page(lang('app_add_gallery'), "galleries", 'admin/app/galleries_add', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function update($id){

		$data = array();

		$this->form_validation->set_rules('name', lang('app_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', lang('app_description'), 'trim|xss_clean');
        $this->form_validation->set_rules('date_created', lang('admin_date_created'), 'trim|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'name' => $this->input->post('name'),
            		'description' => $this->input->post('description'),
                    'date_created' => date_decoder($this->input->post('date_created'))
            	);

            if($this->gallery_model->update($values, $id)){
            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

		$data['object'] = $this->gallery_model->find($id);



		if($data['object']->user_id != $this->user->id){
			redirect('admin/galleries');
		}

		$this->render_page(lang('app_change_gallery'), "galleries", 'admin/app/galleries_update', 'templates/admin_header', 'templates/admin_footer', $data);
	}

    private function update_cover_photo($photo_id){
        $photo = $this->photo_model->find($photo_id);
        $this->gallery_model->update(array('cover_photo_id' => $photo_id), $photo->gallery_id);
    }

	public function photo_update($id){

		$data = array();

		$this->form_validation->set_rules('name', lang('app_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', lang('app_description'), 'trim|xss_clean');

        if ($this->form_validation->run() == TRUE){
            $values = array(
            		'name' => $this->input->post('name'),
            		'description' => $this->input->post('description')
            	);

            if($this->input->post('is_cover_photo')){
                $this->update_cover_photo($id);
            }

            if($this->photo_model->update($values, $id)){
            	$this->success_flash(lang('admin_object_saved_msg'));
            }else{
            	$this->error_flash(lang('admin_error_saving_msg'));
            }
        }

		$data['object'] = $this->photo_model->find($id);

        $gallery = $this->gallery_model->find($data['object']->gallery_id);
        $data['is_cover_photo'] = 0;
        if($gallery->cover_photo_id and $gallery->cover_photo_id == $id){
            $data['is_cover_photo'] = 1;
        }

		if($data['object']->user_id != $this->user->id){
			redirect('admin/galleries');
		}

		$this->render_page(lang('app_change_photo'), "galleries", 'admin/app/galleries_update_photo', 'templates/admin_header', 'templates/admin_footer', $data);
	}

	public function delete_photo($id){
		$photo = $this->photo_model->find($id);
		$gallery = $this->gallery_model->find($photo->gallery_id);

		if($gallery->user_id != $this->user->id){
			redirect('admin/galleries');
		}

		$exists = is_file("uploads/photos/" . $photo->location);
        if ($exists) {
            unlink("uploads/photos/" . $photo->location);
        }

		$this->photo_model->delete($id);

        redirect('admin/galleries/photos/' . $gallery->id);
    }

    public function photos_upload($gallery_id){
        if (empty($_FILES) || $_FILES["file"]["error"]) {
          die('{"OK": 0}');
        }
         
        $fileName = $this->random_file_name($_FILES['file']['name']);
        move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/photos/gallery_" . $gallery_id . "/" . $fileName);

        $values = array(
                        'name' => $fileName,
                        'location' => 'gallery_' . $gallery_id  . '/' . $fileName,
                        'user_id' => $this->user->id,
                        'gallery_id' => $gallery_id,
                        'date_created' => date('Y-m-d H:i:s')
                    );

        $id = $this->photo_model->create($values);
         
        die('{"OK": 1}');
    }

    public function upload_photos($gallery_id){
    	$output = array();

    	if(isset($_FILES['files']['name'])) {
			$total_files = count($_FILES['files']['name']);
		  	$files = $_FILES;

		  	for ($i = 0; $i < $total_files; $i++) {
                $_FILES['files']['name'] = $files['files']['name'][$i];
                $_FILES['files']['type'] = $files['files']['type'][$i];
                $_FILES['files']['tmp_name'] = $files['files']['tmp_name'][$i];
                $_FILES['files']['error'] = $files['files']['error'][$i];
                $_FILES['files']['size'] = $files['files']['size'][$i];

                $config = array();
        		$config['upload_path'] = "uploads/photos/gallery_" . $gallery_id;
        		$config['allowed_types'] = 'jpg|png';
            	$config['file_name'] = $this->random_file_name($_FILES['files']['name']);

                $o = array(
			  		"name" => $_FILES['files']['name'],
				    "size" => $_FILES['files']['size'],
				    "url" => "",
				    "thumbnailUrl" => "",
				    "deleteUrl" => "",
				    "deleteType" => ""
				);

                $this->upload->initialize($config);
                if ($this->upload->do_upload('files')) {
                    $upload_data = $this ->upload->data();

                    $o["url"] = base_url('uploads/photos/gallery_' . $gallery_id  . '/' . $upload_data["file_name"]);
                    $o["thumbnailUrl"] = base_url('assets/timthumb.php') . "src=" . base_url('uploads/photos/gallery_' . $gallery_id  . '/' . $upload_data["file_name"]) . "&w=40&h=40";
                    $o["deleteType"] = "GET";

                    $values = array(
	            		'name' => $o["name"],
	            		'location' => 'gallery_' . $gallery_id  . '/' . $upload_data["file_name"],
	            		'user_id' => $this->user->id,
	            		'gallery_id' => $gallery_id,
	            		'date_created' => date('Y-m-d H:i:s')
	            	);

            		$id = $this->photo_model->create($values);

            		$o["deleteUrl"] = site_url('admin/galleries/ajax_delete_photo/' . $id);

                } else {
                    $o["error"] = $this->upload->display_errors();
                }
            }
		}

		header("Content-type: text/plain;");
		echo json_encode(array('files' => $output));
		exit();
    }

    private function random_file_name($name) {
        return "FILE-" . date("Ymd") . "-" . $this->get_random_number(30) . "." . $this->get_extension($name);
    }

    private function get_extension($filename) {
        $x = explode('.', $filename);
        return end($x);
    }

    private function get_random_number($len) {
        $al = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $date = date("Hs");
        $password = "$date";
        for ($index = 1; $index <= $len; $index++) {
            $randomNumber = rand(1, strlen($al));
            $password .= substr($al, $randomNumber - 1, 1);
        }
        return $password;
    }

    public function ajax_delete_photo($id){
    	$photo = $this->photo_model->find($id);
		$gallery = $this->gallery_model->find($photo->gallery_id);

		if($gallery->user_id == $this->user->id){
			$exists = is_file("uploads/photos/" . $photo->location);
	        if ($exists) {
	            unlink("uploads/photos/" . $photo->location);
	        }

			$this->photo_model->delete($id);
		}
    }
}