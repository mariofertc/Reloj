<?php
class Seccion_model extends CI_Model {

	public function exist($idsec){
		$this->db->where(array('idsec'=>$idsec));
		$result = $this->db->get('seccion');
		//var_dump();
		if($result->num_rows()>0)
			return true;
		else
			return false;
	}
	public function save($data3,$idsec=-1){
		$response = null;
	    if(!$this->exist($idsec))
			$response = $this->db->insert('seccion',$data3);
		else
			$response = $this->db->update('seccion',$data3,array('idsec'=>$idsec));
		//echo $this->db->last_query();
		return $response;
	}
	
	public function get_all($where=null){
		if($where != null)
			$this->db->where($where);
		$result = $this->db->get('seccion');
		return $result->result();
	}
	public function get_info($idsec){
		$this->db->where(array('idsec'=>$idsec));
		$result = $this->db->get('seccion');
		return $result->result();
	}
}