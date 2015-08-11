<?php

class Empleado_model extends CI_Model {

    public function exist($ide) {
        $this->db->where(array('id' => $ide));
        $result = $this->db->get('empleados');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save(&$data, $id = -1) {
        $response = null;
        if (!$this->exist($id)) {
            $this->db->insert('empleados', $data);
            return $data['id'] = $this->db->insert_id();
        }
        else
            return $this->db->update('empleados', $data, array('id' => $id));
        //echo $this->db->last_query();
    }

    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('empleados');
        return $result->result_array();
    }

    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('empleados');
        return $result->result();
    }

    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('empleados', array('deleted' => 1));
    }

    /**
     * Devuelve el numero total de items
     */
    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('empleados');
    }

}