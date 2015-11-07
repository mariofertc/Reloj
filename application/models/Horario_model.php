<?php

class Horario_model extends CI_Model {

    public function exist($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('horario');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }
    
    public function exist_name($data) {
        $this->db->where(array('nombre' => $data['nombre']));
        $this->db->where(array('id!=' => $data['id']));
        $result = $this->db->get('horario');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save(&$data, $id = -1) {
        $response = null;
        $data['fecha_actualizacion'] = date('Y-m-d H:i:s');
        if (!$this->exist($id)) {
            $data['fecha_creacion'] = date('Y-m-d H:i:s');
            $response = $this->db->insert('horario', $data);
            return $data['id'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('horario', $data, array('id' => $id));
        //echo $this->db->last_query();
        return $response;
    }

    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('horario');
        return $result->result_array();
    }
    
    function get_horario_empleado($id_empleado){
        $this->db->where_not_in('empleados_horario.deleted', 1);
        //$this->db->where($where);
        //$this->db->limit($num, $offset);
        $this->db->order_by('horario.fecha_creacion', 'DESC');
        $this->db->from('horario');
        $this->db->join('empleados_horario','horario.id=empleados_horario.id_horario');
        $this->db->where('empleados_horario.id_empleado',$id_empleado);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('horario');
        return $result->result();
    }

    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('horario');
    }

    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('horario', array('deleted' => 1));
    }

}