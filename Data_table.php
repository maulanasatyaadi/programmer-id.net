<?php
class Data_table extends CI_Model
{
	public $table = NULL;
	public $column = array();
	
	public $static_param = NULL;
	public $search = NULL;

	function __construct($config = NULL)
	{
		parent::__construct();
		if(!is_null($config)){
			$this->load->database($config);
		} else {
			$this->load->database();
		}
	}

	public function render($render_type = 'json')
	{
		for ($i=0; $i < count($this->column); $i++) { 
			$this->db->select($this->column[$i].' AS `'.$i.'`');
		}
		$this->db->from($this->table);
		if(strlen($this->input->get('search')['value']) > 0){
			$this->search = $this->input->get('search')['value'];
		}
		if(!is_null($this->static_param)){
			if(is_null($this->search)){
				$this->db->where($this->static_param);
			} else {
				$this->db->group_start();
				$this->db->where($this->static_param);
				$this->db->group_start();
				for ($i=0; $i < count($this->column); $i++) { 
					if($i == 0){
						$this->db->like($this->column[$i], $this->search, 'both');
					}else{
						$this->db->or_like($this->column[$i], $this->search, 'both');
					}
				}
				$this->db->group_end();
				$this->db->group_end();
			}
		} else {
			if(!is_null($this->search)){
				for ($i=0; $i < count($this->column); $i++) { 
					if($i == 0){
						$this->db->like($this->column[$i], $this->search, 'both');
					}else{
						$this->db->or_like($this->column[$i], $this->search, 'both');
					}
				}
			}
		}
		$this->db->order_by(
			$this->input->get('order')[0]['column'] + 1,
			$this->input->get('order')[0]['dir']
			);
		$this->db->limit(
			$this->input->get('length'),
			$this->input->get('start')
			);
		$data = $this->db->get()->result_array();
		$result['data'] = $data;
		$result['draw'] = $this->input->get('draw');
		$this->db->from($this->table);
		if(!is_null($this->static_param)){
			if(is_null($this->search)){
				$this->db->where($this->static_param);
			} else {
				$this->db->group_start();
				$this->db->where($this->static_param);
				$this->db->group_start();
				for ($i=0; $i < count($this->column); $i++) { 
					if($i == 0){
						$this->db->like($this->column);
					}else{
						$this->db->or_like($this->column);
					}
				}
				$this->db->group_end();
				$this->db->group_end();
			}
		} else {
			if(!is_null($this->search)){
				for ($i=0; $i < count($this->column); $i++) { 
					if($i == 0){
						$this->db->like($this->column);
					}else{
						$this->db->or_like($this->column);
					}
				}
			}
		}
		$result['recordsFiltered'] = $this->db->get()->num_rows();
		$result['recordsTotal'] = $this->db->get($this->table)->num_rows();
		return $render_type == 'json' ? json_encode($result) : $result;
	}
}