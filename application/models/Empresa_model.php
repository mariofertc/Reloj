<?php
class Empresa_model extends CI_Model {

	public function exist($ide){
		$this->db->where(array('ide'=>$ide));
		$result = $this->db->get('empresa');
		//var_dump();
		if($result->num_rows()>0)
			return true;
		else
			return false;
	}
	public function save($data1,$ide=-1){
		$response = null;
	    if(!$this->exist($ide)){
	    	if($this->total()<1)
				$response = $this->db->insert('empresa',$data1);
			else
				return false;
		}
		else
			$response = $this->db->update('empresa',$data1,array('ide'=>$ide));
		//echo $this->db->last_query();
		return $response;
	}
	public function total(){
		$result = $this->db->get('empresa');
		$result = $result->result_array();

		return count($result);
	}
	public function get_all(){
		$result = $this->db->get('empresa');
		return $result->result();
	}
	public function get_info($ide){
		$this->db->where(array('ide'=>$ide));
		$result = $this->db->get('empresa');
		return $result->result();
	}
}