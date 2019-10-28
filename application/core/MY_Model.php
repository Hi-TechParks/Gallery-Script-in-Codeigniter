<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
	var $table = "";
	var $searchable_fields = array();

	var $created_on = false;
	var $updated_on = false;

	var $soft_delete = false;

	var $related = array();

	var $select = "*";
	var $search = "";
	var $join = array();
	var $where = array();
	var $where_in = array();
	var $order_by = array();
	var $paginate = array();

	var $where_gt = array();
	var $where_gte = array();
	var $where_lt = array();
	var $where_lte = array();
	var $where_isnull = array();
	var $where_notnull = array();
	var $where_not = array();
	var $where_startswith = array();
	var $where_in_or = array();
	var $from_trash = false;
	
	function __construct() {
		parent::__construct();
	}

	function find_or_404($field = null, $value = null){
		$item = $this->find($field = null, $value = null);
		if(!$item){
			redirect('admin/not_found');
		}
		return $item;
	}
	
	function find($field = null, $value = null) {
		$where = array();

		if(is_numeric($field)){
			$where = array('id' => $field);
		}elseif($field and $value){
			$where = array($field => $value);
		}elseif(is_array($field)){
			$where = $field;
		}else{
			return false;
		}

		$this -> db -> where($where);
		$query = $this->db->get($this->table); 
		if ($query -> num_rows() > 0) {
			$results = $query -> result();

			if($this->soft_delete){
				if($results[0]->deleted_at){
					return false;
				}
			}
			return $results[0];
		} else {
			return false;
		}	
	}
	
	function create($data) {
		if($this->created_on){
			$data['created_on'] = date('Y-m-d H:i:s');
		}

		$this -> db -> insert($this->table, $data);
		return $this->db->insert_id();
	}

	function update($data, $id) {
		if($this->updated_on){
			$data['updated_on'] = date('Y-m-d H:i:s');
		}

		$this -> db -> update($this->table, $data, array('id' => $id));
		return true;
	}

	function trash($field = null, $value = null) {
		$where = array();

		if(is_numeric($field)){
			$where = array('id' => $field);
		}elseif($field and $value){
			$where = array($field => $value);
		}elseif(is_array($field)){
			$where = $field;
		}else{
			return false;
		}

		if($this->soft_delete){
			$this -> db -> update($this->table, array('deleted_at' => date('Y-m-d H:i:s')), $where);
		}else{
			$this -> db -> delete($this->table, $where);
		}	
	}

	function delete($field = null, $value = null) {
		$where = array();

		if(is_numeric($field)){
			$where = array('id' => $field);
		}elseif($field and $value){
			$where = array($field => $value);
		}elseif(is_array($field)){
			$where = $field;
		}else{
			return false;
		}

		$this -> db -> delete($this->table, $where);	
	}

	function save_related($name, $my_id, $other_id, $other_data = array()){
		if($this->related[$name]){
			if($this->related[$name]['type'] == 'column'){
				$this->update(array($this->related[$name]['other_column'] => $other_id), $my_id);
			}elseif ($this->related[$name]['type'] == 'pivot') {
				$data = array(
					$this->related[$name]['my_column'] => $my_id,
					$this->related[$name]['other_column'] => $other_id
				);

				if ($this->db->where($data)->count_all_results($this->related[$name]['pivot_table']) == 0) {
					$data = array_merge($data, $other_data);
					$this->db->insert($this->related[$name]['pivot_table'], $data);
				}
			}
		}
	}

	function delete_related($name, $my_id, $other_id = null){
		if($this->related[$name]){
			if($this->related[$name]['type'] == 'column'){
				$this->update(array($this->related[$name]['other_column'] => null), $my_id);
			}elseif ($this->related[$name]['type'] == 'pivot') {
				$data = array(
					$this->related[$name]['my_column'] => $my_id
				);

				if($other_id){
					$data[$this->related[$name]['other_column']] = $other_id;
				}

				$this->db->delete($this->related[$name]['pivot_table'], $data);
			}elseif($this->related[$name]['type'] == 'column-reverse'){
					$data = array(
						$this->related[$name]['my_column'] => $my_id
					);
					if($other_id == null){
						$this->db->delete($this->related[$name]['other_table'], $data);
					}else{
						$this -> db -> update($this->table, array($this->related[$name]['my_column'] => $other_id), array($this->related[$name]['my_column'] => $my_id));
					}
				}
		}
	}

	function delete_all_related($my_id, $new_id = null){
		if($this->related){
			foreach ($this->related as $name => $value) {
				if ($this->related[$name]['type'] == 'pivot') {
					$data = array(
						$this->related[$name]['my_column'] => $my_id
					);
					$this->db->delete($this->related[$name]['pivot_table'], $data);
				}elseif($this->related[$name]['type'] == 'column-reverse'){
					$data = array(
						$this->related[$name]['my_column'] => $my_id
					);
					if($new_id == null){
						$this->db->delete($this->related[$name]['other_table'], $data);
					}else{
						$this -> db -> update($this->table, array($this->related[$name]['my_column'] => $new_id), array($this->related[$name]['my_column'] => $my_id));
					}
				}
			}
		}
	}

	function find_related($name, $my_id){
		if($this->related[$name]){
			if($this->related[$name]['type'] == 'column'){
				$me = $this->find($my_id);
				if($me){
					$other_column = $this->related[$name]['other_column'];
					$other_pk = $this->related[$name]['other_primary_key'];
					$other_table = $this->related[$name]['other_table'];
					$this -> db -> where(array($other_pk => $me->$other_column));
					$query = $this->db->get($other_table); 
					if ($query -> num_rows() > 0) {
						$results = $query -> result();
						return $results[0];
					} else {
						return false;
					}
				}
				return false;
			}elseif ($this->related[$name]['type'] == 'pivot') {
				
				$other_table = $this->related[$name]['other_table'];
				$pivot_table = $this->related[$name]['pivot_table'];
				$other_pk = $this->related[$name]['other_primary_key'];
				$my_column = $this->related[$name]['my_column'];
				$other_column = $this->related[$name]['other_column'];

				$query =  $this->db->query("SELECT {$other_table}.* FROM {$other_table} LEFT JOIN {$pivot_table} ON {$other_table}.{$other_pk} = {$pivot_table}.{$other_column} WHERE {$pivot_table}.{$my_column} = {$my_id}");

				return $query->result();
			}elseif($this->related[$name]['type'] == 'column-reverse'){
				$me = $this->find($my_id);
				if($me){
					$my_column = $this->related[$name]['my_column'];
					$other_table = $this->related[$name]['other_table'];
					$this -> db -> where(array($my_column => $me->id));
					$query = $this->db->get($other_table); 
					return $query -> result();
				}
				return false;
			}
		}

		return false;
	}

	function find_related_limit($name, $my_id, $paginate = array(), $order_by = array()){
		if($this->related[$name]){
			if($this->related[$name]['type'] == 'column'){
				$me = $this->find($my_id);
				if($me){
					$other_column = $this->related[$name]['other_column'];
					$other_pk = $this->related[$name]['other_primary_key'];
					$other_table = $this->related[$name]['other_table'];
					$this -> db -> where(array($other_pk => $me->$other_column));
					$query = $this->db->get($other_table); 
					if ($query -> num_rows() > 0) {
						$results = $query -> result();
						return $results[0];
					} else {
						return false;
					}
				}
				return false;
			}elseif ($this->related[$name]['type'] == 'pivot') {
				$return = array();
				$other_table = $this->related[$name]['other_table'];
				$pivot_table = $this->related[$name]['pivot_table'];
				$other_pk = $this->related[$name]['other_primary_key'];
				$my_column = $this->related[$name]['my_column'];
				$other_column = $this->related[$name]['other_column'];

				$query =  $this->db->query("SELECT COUNT({$other_table}.id) as total FROM {$other_table} LEFT JOIN {$pivot_table} ON {$other_table}.{$other_pk} = {$pivot_table}.{$other_column} WHERE {$pivot_table}.{$my_column} = {$my_id}");

				$return['total_rows'] = $query -> result()[0]->total;

				$page = "";
				if($paginate){
					$page = " LIMIT " . $paginate['offset'] . ", " . $paginate['limit'];
				}

				if($order_by){
					$ordered = " ORDER BY " . $order_by[0] . " " . $order_by[1];
				}

				$query =  $this->db->query("SELECT {$other_table}.* FROM {$other_table} LEFT JOIN {$pivot_table} ON {$other_table}.{$other_pk} = {$pivot_table}.{$other_column} WHERE {$pivot_table}.{$my_column} = {$my_id}{$page}{$ordered}");

				$return['results'] = $query -> result();

				return $return;
			}elseif($this->related[$name]['type'] == 'column-reverse'){
				$me = $this->find($my_id);
				if($me){
					$return = array();
					$my_column = $this->related[$name]['my_column'];
					$other_table = $this->related[$name]['other_table'];
					$this -> db -> where(array($my_column => $me->id));

					$return['total_rows'] = $this->db->count_all_results($other_table);

					$this -> db -> where(array($my_column => $me->id));

					if ($paginate) {
						$this->db->limit($paginate['limit'], $paginate['offset']);
					}

					if ($order_by) {
						$this->db->order_by($order_by[0] . " " . $order_by[1]); 
					}

					$query = $this->db->get($other_table); 
					$return['results'] = $query -> result();

					return $return;
				}
				return false;
			}
		}

		return false;
	}

	function select($columns){
		$this->select = $columns;
		return $this;
	}

	function search($term){
		$this->search = $term;
		return $this;
	}

	function join($table, $on_expression, $type = 'left'){
		$this->join = array('table' => $table, 'on_expression' => $on_expression, 'type' => $type);
		return $this;
	}

	function where($key, $value){
		$this->where[$key] = $value;
		return $this;
	}

	function where_gt($key, $value){
		$this->where_gt[$key] = $value;
		return $this;
	}

	function where_gte($key, $value){
		$this->where_gte[$key] = $value;
		return $this;
	}

	function where_lt($key, $value){
		$this->where_lt[$key] = $value;
		return $this;
	}

	function where_lte($key, $value){
		$this->where_lte[$key] = $value;
		return $this;
	}

	function where_not($key, $value){
		$this->where_not[$key] = $value;
		return $this;
	}

	function where_isnull($value){
		$this->where_isnull[] = $value;
		return $this;
	}

	function where_notnull($value){
		$this->where_notnull[] = $value;
		return $this;
	}

	function where_in($key, $value){
		$this->where_in[$key] = $value;
		return $this;
	}

	function where_startswith($key, $value){
		$this->where_startswith[$key] = $value;
		return $this;
	}

	function where_in_or($key, $value){
		$this->where_in_or[] = array($key, $value);
		return $this;
	}

	function from_trash(){
		$this->from_trash = true;
		return $this;
	}

	function order_by($column, $direction){
		$this->order_by = array('column' => $column, 'direction' => $direction);
		return $this;
	}

	function paginate($limit, $offset){
		$this->paginate = array('limit' => intval($limit), 'offset' => intval($offset));
		return $this;
	}

	function _get_where_parts(){
		$where_parts = array();

		foreach ($this->where as $key => $value) {
			$where_parts[] = $key . " = '" . $value . "'";
		}

		foreach ($this->where_gt as $key => $value) {
			$where_parts[] = $key . " > '" . $value . "'";
		}

		foreach ($this->where_gte as $key => $value) {
			$where_parts[] = $key . " >= '" . $value . "'";
		}

		foreach ($this->where_lt as $key => $value) {
			$where_parts[] = $key . " < '" . $value . "'";
		}

		foreach ($this->where_lte as $key => $value) {
			$where_parts[] = $key . " <= '" . $value . "'";
		}

		foreach ($this->where_startswith as $key => $value) {
			$where_parts[] = $key . " LIKE '" . $value . "%'";
		}

		foreach ($this->where_isnull as $key => $value) {
			$where_parts[] = $value . " IS NULL";
		}

		foreach ($this->where_notnull as $key => $value) {
			$where_parts[] = $value . " IS NOT NULL";
		}

		foreach ($this->where_not as $key => $value) {
			$where_parts[] = $key . " != '" . $value . "'";
		}

		if($this->soft_delete and $this->from_trash){
			$where_parts[] = "deleted_at IS NOT NULL";
		}

		if ($this->search) {
			$search_where_parts = array();
			foreach ($this->searchable_fields as $value) {
				$search_where_parts[] = $value . " like '%" . $this->search . "%'";
			}
			$where_parts[] = "(" . implode(" OR ", $search_where_parts) . ")";
		}

		if($this->where_in){
			foreach ($this->where_in as $key => $value) {
				$where_parts[] = $key . " IN ('" . implode("', '", $value) . "')";
			}
		}

		if($this->where_in_or){
			foreach ($this->where_in_or as $value) {
				$where_parts[] = "( " . $value[0][0] . " IN ('" . implode("', '", $value[0][1]) . "') OR " . $value[1][0] . " = '" . $value[1][1] . "' )";
			}
		}

		$this->search = "";
		$this->where = array();
		$this->where_gt = array();
		$this->where_gte = array();
		$this->where_lt = array();
		$this->where_lte = array();
		$this->where_isnull = array();
		$this->where_not = array();
		$this->where_in = array();

		return $where_parts;
	}

	function fetch(){
		$return = array();
		$where_parts = $this->_get_where_parts();

		if (count($where_parts) > 0) {
			$this->db->where(implode(" AND ", $where_parts));
		}

		if($this->join){
			$this->db->join($this->join['table'], $this->join['on_expression'], $this->join['type']);
		}

		$return['total_rows'] = $this->db->count_all_results($this->table);

		if (count($where_parts) > 0) {
			$this->db->where(implode(" AND ", $where_parts));
		}

		if($this->join){
			$this->db->join($this->join['table'], $this->join['on_expression'], $this->join['type']);
		}

		if ($this->order_by) {
			$this->db->order_by($this->order_by['column'] . " " . $this->order_by['direction']); 
		}

		if ($this->paginate) {
			$this->db->limit($this->paginate['limit'], $this->paginate['offset']);
		}
		
		if($this->select != "*"){
			$this->db->select($select, false);
		}
		
		$query = $this->db->get($this->table);
		
		$return['results'] = $query -> result();

		$this->select = "*";
		$this->join = array();
		$this->order_by = array();
		$this->paginate = array();

		return $return;
	}

	function count(){
		$count = 0;
		
		$where_parts = $this->_get_where_parts();

		if (count($where_parts) > 0) {
			$this->db->where(implode(" AND ", $where_parts));
		}

		if($this->join){
			$this->db->join($this->join['table'], $this->join['on_expression'], $this->join['type']);
		}

		$count = $this->db->count_all_results($this->table);

		$this->select = "*";
		$this->join = array();
		$this->order_by = array();
		$this->paginate = array();

		return $count;
	}

}