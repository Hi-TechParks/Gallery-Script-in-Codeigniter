<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('query_string')){
	function query_string($params){
		if(count($params) == 0){
			return "";
		}

		$str = array();
		foreach ($params as $key => $value) {
			if($value){
				$str[] = urlencode($key) . "=" . urlencode($value);
			}
		}

		return "?" . implode("&", $str);
	}
}


if(!function_exists('show_if')){
	function show_if($type, $when, $saved_type = null){
		if(in_array($type, $when)){
			return '';
		}else{
			if(in_array($saved_type, $when)){
				return '';
			}else{
				return 'style="display: none"';
			}
		}
	}
}

if(!function_exists('get_sorter')){
	function get_sorter($label, $value, $url, $sort_value, $sort_direction){
		if($sort_value != $value){
			return '<a href="' . $url . query_string(array('sort_value' => $value, 'sort_direction' => 'asc')) . '">' . $label . '</a>';
		}else{
			if($sort_direction == 'asc'){
				$icon = '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
				$new_direction = 'desc';
			}else{
				$icon = '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
				$new_direction = 'asc';
			}

			return '<a href="' . $url . query_string(array('sort_value' => $value, 'sort_direction' => $new_direction)) . '">' . $label . '</a>' . ' ' . $icon;
		}
	}
}

if(!function_exists('get_bool_sorter')){
	function get_bool_sorter($label, $value, $url, $sort_value, $sort_direction){
		if($sort_value != $value){
			return '<a href="' . $url . query_string(array('sort_bool_value' => $value, 'sort_bool_direction' => '1')) . '">' . $label . '</a>';
		}else{
			if($sort_direction == '1'){
				$icon = '<span class="label label-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>';
				$new_direction = '0';
			}else{
				$icon = '<span class="label label-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></span>';
				$new_direction = '1';
			}

			return '<a href="' . $url . query_string(array('sort_bool_value' => $value, 'sort_bool_direction' => $new_direction)) . '">' . $label . '</a>' . ' ' . $icon;
		}
	}
}

if(!function_exists('pagination_config')){
	function pagination_config($params){
		
		$config = array(
			'query_string_segment' => 'page_num', 
			'num_links' => 5, 
			'page_query_string' => TRUE, 
			'full_tag_open' => '<ul class="pagination pagination-sm no-margin">', 
			'full_tag_close' => '</ul>', 
			'num_tag_open' => '<li>', 
			'num_tag_close' => '</li>', 
			'cur_tag_open' => '<li class="active"><a href="">', 
			'cur_tag_close' => '</a></li>', 
			'next_tag_open' => '<li>', 
			'next_tag_close' => '</li>', 
			'prev_tag_open' => '<li>', 
			'prev_tag_close' => '</li>', 
			'last_tag_open' => '<li>', 
			'last_tag_close' => '</li>', 
			'first_tag_open' => '<li>', 
			'first_tag_close' => '</li>', 
			'prev_link' => '&laquo;', 
			'next_link' => '&raquo;', 
			'last_link' => 'Last', 
			'first_link' => 'First'
			);

		return array_merge($config, $params);
	}
}


if(!function_exists('boolean_to_icon')){
	function boolean_to_icon($value = 0){
		if(intval($value) == 1){
			return '<span class="label label-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>';
		}

		return '<span class="label label-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></span>';
	}
}

if(!function_exists('is_checked')){
	function is_checked($value){
		if($value){
			if(is_numeric($value)){
				if($value == 1){
					return 'checked';
				}else{
					return '';
				}
			}else{
				return 'checked';
			}
		}
		return "";
	}
}

if (!function_exists('trim_text')) {
    function trim_text($input, $length, $add_hellips = true) {
        $input = strip_tags($input);
        if (strlen($input) <= $length) {
            return $input;
        }
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        if($add_hellips){
            $trimmed_text .= '&hellip;';
        }

        return $trimmed_text;
    }
}

if (!function_exists('featured_photo')) {
    function featured_photo($id) {
    	$CI = get_instance();

    	$gallery = $CI->gallery_model->find($id);

    	if($gallery->cover_photo_id){
    		$cover_photo = $CI->photo_model->find($gallery->cover_photo_id);
    		if($cover_photo){
	    		$exists = is_file("uploads/photos/" . $cover_photo->location);
		        if ($exists) {
		            return base_url("uploads/photos/" . $cover_photo->location);
		        }
	    	}
    	}

    	$objects = $CI->gallery_model->find_related_limit('photos', $id, array('limit' => 1,'offset' => 0), array('date_created', 'DESC'));

		if(count($objects["results"]) > 0){
			return base_url('uploads/photos/' . $objects["results"][0]->location);
		}

		return base_url('uploads/photos/placeholder.png');
    }
}

if (!function_exists('upload_helper')) {
    function upload_helper($field, $multiple = false, $path = "") {
        $CI = get_instance();
        $CI -> load -> library('upload');

        $return = array();
        $return["status"] = 0;
        $return["names"] = array();
        $return["error"] = "";
        if ($multiple) {
            $files = $_FILES;
            $cpt = count($_FILES[$field]['name']);
            for ($i = 0; $i < $cpt; $i++) {

                $_FILES[$field]['name'] = $files[$field]['name'][$i];
                $_FILES[$field]['type'] = $files[$field]['type'][$i];
                $_FILES[$field]['tmp_name'] = $files[$field]['tmp_name'][$i];
                $_FILES[$field]['error'] = $files[$field]['error'][$i];
                $_FILES[$field]['size'] = $files[$field]['size'][$i];

                $CI -> upload -> initialize(set_upload_options(random_file_name($_FILES[$field]['name']), $path));
                if ($CI -> upload -> do_upload($field)) {
                    $upload_data = $CI -> upload -> data();
                    $return["status"] = 1;
                    $return["names"][] = $upload_data["file_name"];
                } else {
                    $return["status"] = 0;
                    $return["error"] .=  $CI -> upload -> display_errors() . " ";
                }

            }
        } else {
            $CI -> upload -> initialize(set_upload_options(random_file_name($_FILES[$field]['name']), $path));
            if ($CI -> upload -> do_upload($field)) {
                $upload_data = $CI -> upload -> data();
                $return["status"] = 1;
                $return["names"][] = $upload_data["file_name"];
            } else {
                $return["status"] = 0;
                $return["error"] = $CI -> upload -> display_errors();
            }
        }

        return $return;
    }

}

if (!function_exists('set_upload_options')) {
    function random_file_name($name) {
        return "FILE-" . date("Ymd") . "-" . generateRandomNumber(30) . "." . mi_get_extension($name);
    }

}

if (!function_exists('set_upload_options')) {
    function set_upload_options($new_name = "", $path) {
        $config = array();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|png';
        if ($new_name) {
            $config['file_name'] = $new_name;
        }
        return $config;
    }

}

if (!function_exists('mi_get_extension')) {
    function mi_get_extension($filename) {
        $x = explode('.', $filename);
        return end($x);
    }

}

if (!function_exists('generateRandomNumber')) {
    function generateRandomNumber($len) {
        $al = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $date = date("Hs");
        $password = "$date";
        for ($index = 1; $index <= $len; $index++) {
            $randomNumber = rand(1, strlen($al));
            $password .= substr($al, $randomNumber - 1, 1);
        }
        return $password;
    }

}

if(!function_exists('select_multiple_option')){
	function select_multiple_option($post, $this_value, $saved = array()){
		if(is_array($post)){
			if(in_array($this_value, $post)){
				return 'selected="selected"';
			}
		}

		if($saved){
			if(is_array($saved)){
				foreach ($saved as $value) {
					if($value->id == $this_value){
						return 'selected="selected"';
					}
				}
			}else{
				if($saved == $this_value){
					return 'selected="selected"';
				}
			}
			
		}

		return "";
	}
}

if (!function_exists('delete_directory')) {
	function delete_directory($dirname) {
	    if (is_dir($dirname)){
	    	$dir_handle = opendir($dirname);
	    }
	           
	    if(!$dir_handle){
	    	return false;
	    }
	          
	    while($file = readdir($dir_handle)) {
	        if($file != "." && $file != "..") {
	            if(!is_dir($dirname."/".$file)){
	                     unlink($dirname."/".$file);
	            }else{
	            	delete_directory($dirname.'/'.$file);
	            }          
	        }
	     }
	     closedir($dir_handle);
	     rmdir($dirname);
	     return true;
	}
}

if (!function_exists('date_encoder')) {
    function date_encoder($unix_time = null) {
        if($unix_time == null){
            $unix_time = date("Y-m-d H:i:s");
        }
        return date("d/m/Y", strtotime($unix_time));
    }
}

if (!function_exists('date_decoder')) {
    function date_decoder($date = null) {
        if($date == null){
            $date = date("d/m/Y");
        }
        $date = explode('/', urldecode($date));
        return date("Y-m-d H:i:s", mktime(0, 0, 0, $date[1], $date[0], $date[2]));
    }
}