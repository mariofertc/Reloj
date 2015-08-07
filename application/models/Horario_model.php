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
	public function get_all($num = 0, $offset = 0, $where = null, $order = null){
		$this->db->where_not_in('deleted', 1);
		if(!empty($where))
			$this->db->where($where);
		$this->db->limit($num, $offset);
		$this->db->order_by($order);
		$result = $this->db->get('horario');
		return $result->result_array();
	}
	public function get_info($codigo){
		$this->db->where(array('codigo'=>$codigo));
		$result = $this->db->get('horario');
		return $result->result();
	}
        function get_total(){
		//$this->mongo_db->where(array('deleted' => array('$exists' => false)));
   		return $this->db->count_all('horario');
        }
}