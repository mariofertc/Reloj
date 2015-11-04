<?php

class Empleado_horario_model extends CI_Model {

    public function exist($ide) {
        $this->db->where(array('id_empleado' => $ide));
        $result = $this->db->get('empleados_horario');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save(&$data) {
        $response = null;
        if (!$this->exist($data['id_empleado'])) {
            $this->db->insert('empleados_horario', $data);
            return true;
        }
        else{
            $this->delete($data['id_empleado']);
            return $this->db->insert('empleados_horario', $data);
        }
        //echo $this->db->last_query();
    }

    public function get_all($num = 0, $offset = 0, $where = null, $order = null,$select=null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        if (!empty($select))
            $this->db->select($select);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('empleados_horario');
        return $result->result_array();
    }

    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('empleados_horario');
        return $result->result();
    }
    /**
     * Devuelve el numero total de items
     */
    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('empleados_horario');
    }
    
    function delete($id) {
        return $this->db->update('empleados_horario',array('deleted'=>1),array('id_empleado'=>$id));
    }

}