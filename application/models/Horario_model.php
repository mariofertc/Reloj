<?php
class Horario_model extends CI_Model {

	public function exist($codigo){
		$this->db->where(array('codigo'=>$codigo));
		$result = $this->db->get('horario');
		//var_dump();
		if($result->num_rows()>0)
			return true;
		else
			return false;
	}
	public function save($data4,$codigo=-1){
		$response = null;
	    if(!$this->exist($codigo))
			$response = $this->db->insert('horario',$data4);
		else
			$response = $this->db->update('horario',$data4,array('codigo'=>$codigo));
		//echo $this->db->last_query();
		return $response;
	}
	public function get_all(){
		$result = $this->db->get('horario');
		return $result->result();
	}
	public function get_info($codigo){
		$this->db->where(array('codigo'=>$codigo));
		$result = $this->db->get('horario');
		return $result->result();
	}
}