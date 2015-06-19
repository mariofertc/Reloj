<?php
class Empleado_model extends CI_Model {

	public function exist($ide){
		$this->db->where(array('id'=>$ide));
		$result = $this->db->get('empleados');
		//var_dump();
		if($result->num_rows()>0)
			return true;
		else
			return false;
	}
	public function save($data,$id=-1){
		$response = null;
	    if(!$this->exist($id))
			$response = $this->db->insert('empleados',$data);
		else
			$response = $this->db->update('empleados',$data,array('id'=>$id));
		//echo $this->db->last_query();
		return $response;
	}
	public function get_all(){
		$result = $this->db->get('empleados');
		return $result->result();
	}
	public function get_info($id){
		$this->db->where(array('id'=>$id));
		$result = $this->db->get('empleados');
		return $result->result();
	}
}