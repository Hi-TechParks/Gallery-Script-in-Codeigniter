<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends MY_Controller {

	public function __construct(){
        parent::__construct();
    }


	public function index()
	{
		$data = array();

		$objects = $this->gallery_model;

		if($this->input->get('search_term')){
			$objects = $this->gallery_model->search($data['search_term']);
		}

		if($this->input->get('sort_value')){
			$this->gallery_model->order_by($this->input->get('sort_value'), $this->input->get('sort_direction'));
		}else{
			$this->gallery_model->order_by('date_created', 'DESC');
		}

		$data['objects'] = $this->gallery_model->paginate(intval($this->per_page), intval($this->input->get('page_num')))->fetch();

		$query_string = query_string(array(
					'sort_value' => $this->input->get('sort_value'),
					'sort_direction' => $this->input->get('sort_direction'),
					'search_term' => $this->input->get('search_term')
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('gallery' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);


        $this->render_page(lang('app_galleries'), "", 'frontend/galleries', 'templates/frontend_header', 'templates/frontend_footer', $data);
	}

	public function featured_photo($id){
		$gallery =  $this->gallery_model->find($id);

    	if($gallery->cover_photo_id){
    		$cover_photo = $this->photo_model->find($gallery->cover_photo_id);
    		if($cover_photo){
	    		$exists = is_file("uploads/photos/" . $cover_photo->location);
		        if ($exists) {
		            return base_url("uploads/photos/" . $cover_photo->location);
		        }
	    	}
    	}

		$objects = $this->gallery_model->find_related_limit('photos', $id, array('limit' => 1,'offset' => 0), array('date_created', 'DESC'));

		if(count($objects["results"]) > 0){
			return base_url('uploads/photos/' . $objects["results"][0]->location);
		}
	}

	public function view($id)
	{
		$data = array();

		

		$data['gallery'] = $this->gallery_model->find($id);

		$data['gallery_id'] = $data['gallery']->id;

		$data['objects'] = $this->gallery_model->find_related_limit('photos', $id, array('limit' => intval($this->per_page),'offset' => intval($this->input->get('page_num'))), array('date_created', 'DESC'));

		$query_string = query_string(array(
					'sort_value' => "",
					'sort_direction' => "",
					'search_term' => ""
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('gallery/view/' . $id . '/' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

		$this->pagination->initialize($config_pagination);

        $this->render_page($data['gallery']->name, "", 'frontend/gallery', 'templates/frontend_header', 'templates/frontend_footer', $data);
	}

	/*public function view_full($gallery_id, $photo_id)
	{
		$data = array();

		

		$data['gallery'] = $this->gallery_model->find($gallery_id);

		$data['gallery_id'] = $gallery_id;

		// $data['photo'] = $this->photo_model->find($photo_id);

		$data['objects'] = $this->gallery_model->find_related_limit('photos', $gallery_id, array('limit' => intval($this->per_page),'offset' => intval($this->input->get('page_num'))), array('date_created', 'DESC'));

		$query_string = query_string(array(
					'sort_value' => "",
					'sort_direction' => "",
					'search_term' => ""
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('gallery/view_full/' . $gallery_id . '/' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => $this->per_page,
			));

        $this->render_page($data['gallery']->name, "", 'frontend/gallery_full', 'templates/frontend_header', 'templates/frontend_footer', $data);
	}*/




	public function view_full($id, $photo_id)
	{
		$data = array();

		$this->load->library('pagination');

		$data['gallery'] = $this->gallery_model->find($id);

		$data['gallery_id'] = $data['gallery']->id;

		$page_name = $this->input->get('page_num');

		$data['objects'] = $this->gallery_model->find_related_limit('photos', $id, array('limit' => intval(1),'offset' => intval($page_name)), array('id', 'DESC'));

		/*$config['base_url'] = site_url('gallery/view_full/' . $id . '/' . $photo_id);
		$config['total_rows'] = $data['objects']['total_rows'];
		$config['per_page'] = 1;
		$config['first_tag_open'] = '<div style="color:red;">';
		$config['first_tag_close'] = '<div>';
		$config['prev_link'] = 'Prev';
		$config['next_link'] = 'Next';
		$config['display_pages'] = FALSE;*/

		$config = pagination_config(array(
			'base_url' => site_url('gallery/view_full/' . $id . '/' . $photo_id),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => 1,
			'prev_link' => lang('app_back'),
			'next_link' => lang('app_forward'),
			'display_pages' => FALSE,
			'prev_tag_open' => '<div class="btn btn-info">',
			'prev_tag_close' => '</div>',
			'next_tag_open' => '<div class="btn btn-info">',
			'next_tag_close' => '</div>',
			'first_link' => FALSE,
			'last_link' => FALSE,
			));

		$this->pagination->initialize($config);



		/*$data['gallery'] = $this->gallery_model->find($id);

		$data['gallery_id'] = $data['gallery']->id;

		$data['objects'] = $this->gallery_model->find_related_limit('photos', $id, array('limit' => intval(1),'offset' => intval($this->input->get('page_num'))), array('id', 'DESC'));

		$query_string = query_string(array(
					'sort_value' => 2,
					'sort_direction' => "",
					'search_term' => ""
				));

		$config_pagination = pagination_config(array(
			'base_url' => site_url('gallery/view_full/' . $id . '/' . $photo_id . '/' . $query_string),
			'total_rows' => $data['objects']['total_rows'],
			'per_page' => 1,
			));

		$this->pagination->initialize($config_pagination);*/

        $this->render_page($data['gallery']->name, "", 'frontend/gallery_full', 'templates/frontend_header', 'templates/frontend_footer', $data);
	}

}
