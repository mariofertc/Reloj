<?php
class Departamento_model extends CI_Model {

	public function exist($iddep){
		$this->db->where(array('iddep'=>$iddep));
		$result = $this->db->get('departamento');
		//var_dump();
		if($result->num_rows()>0)
			return true;
		else
			return false;
	}
	public function save($data2,$iddep=-1){
		$response = null;
	    if(!$this->exist($iddep))
			$response = $this->db->insert('departamento',$data2);
		else
			$response = $this->db->update('departamento',$data2,array('iddep'=>$iddep));
		//echo $this->db->last_query();
		return $response;
	}
	public function get_all($where = null){
		if(isset($where))
			$this->db->where($where);
		$result = $this->db->get('departamento');
		return $result->result();
	}
	public function get_info($iddep){
		$this->db->where(array('iddep'=>$iddep));
		$result = $this->db->get('departamento');
		return $result->result();
	}
}