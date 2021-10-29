<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_settings extends CI_Model
{
	private $table = 'settings';
	private $id = 'id';

	function __construct()
	{
		parent::__construct();
	}
	function get(){
		$arr = array();
		for($i = 0; $i < func_num_args(); $i++)
		{
			$arr = array_merge($arr, func_get_arg($i));
		}
		$this->db->select('*');
		$this->db->from($this->table);
		if(array_key_exists("where", $arr))
		{
			$this->db->where($arr['where']);
		}
		if(array_key_exists("not_in", $arr))
		{
			$this->db->where_not_in($arr['not_in'][0],$arr['not_in'][1]);
		}
		if(array_key_exists("in", $arr))
		{
			$this->db->where_in($arr['in'][0],$arr['in'][1]);
		}
		if(array_key_exists("limit", $arr))
		{
			$this->db->limit($arr['limit'][0],$arr['limit'][1]);
		}
		if(array_key_exists("like", $arr))
		{
			$this->db->like($arr['like']);
		}
		if(array_key_exists("order", $arr))
		{
			$this->db->order_by($arr['order']);
		}
		$query = $this->db->get();
		if(array_key_exists("result", $arr))
		{
			if($arr['result'] == 'array'){
				return $query->result_array();
			}
			if($arr['result'] == 'row'){
				return $query->row();
			}
		}
	}
	function get_num(){
		$arr = array();
		for($i = 0; $i < func_num_args(); $i++)
		{
			$arr = array_merge($arr, func_get_arg($i));
		}
		$this->db->select('*');
		$this->db->from($this->table);
		if(array_key_exists("where", $arr))
		{
			$this->db->where($arr['where']);
		}
		if(array_key_exists("not_in", $arr))
		{
			$this->db->where_not_in($arr['not_in'][0],$arr['not_in'][1]);
		}
		if(array_key_exists("in", $arr))
		{
			$this->db->where_in($arr['in'][0],$arr['in'][1]);
		}
		if(array_key_exists("limit", $arr))
		{
			$this->db->limit($arr['limit'][0],$arr['limit'][1]);
		}
		if(array_key_exists("like", $arr))
		{
			$this->db->like($arr['like']);
		}
		if(array_key_exists("order", $arr))
		{
			$this->db->order_by($arr['order']);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}
	function ins($arr){
		$this->db->insert($this->table, $arr);
	}
	function up($id, $arr){
		$this->db->where($this->id, $id);
		$this->db->update($this->table, $arr);
	}
	function del($id){
		$this->db->where($this->id, $id);
		$this->db->delete($this->table);
	}
	function max(){
		$this->db->select_max($this->id);
		$query = $this->db->get($this->table);
		return $query->row();
	}
	function empty_table(){
		$this->db->empty_table($this->table);
	}
}
