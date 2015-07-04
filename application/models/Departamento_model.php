<?php
class Departamento_model extends CI_Model {

	public function exist($ideem){
		$this->db->where(array('ideem'=>$ideem));
		$result = $this->db->get('departamento');
		//var_dump();
		if($result->num_rows()>0)
			return true;
		else
			return false;
	}
	public function save($data2,$ideem=-1){
		$response = null;
	    if(!$this->exist($ideem))
			$response = $this->db->insert('departamento',$data2);
		else
			$response = $this->db->update('departamento',$data2,array('ideem'=>$ideem));
		//echo $this->db->last_query();
		return $response;
	}
	public function get_all($where = null){
		if(isset($where))
			$this->db->where($where);
		$result = $this->db->get('departamento');
		return $result->result();
	}
	public function get_info($ideem){
		$this->db->where(array('ideem'=>$ideem));
		$result = $this->db->get('departamento');
		return $result->result();
	}
}