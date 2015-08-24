<?php

class Permiso_model extends CI_Model {

    public function exist($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('permiso');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save(&$data, $id = -1) {
        $response = null;
        $data['fecha_actualizacion'] = date('Y-m-d H:i:s');
        if (!$this->exist($id)) {
            $data['fecha'] = date('Y-m-d H:i:s');
            $response = $this->db->insert('permiso', $data);
            return $data['id'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('permiso', $data, array('id' => $id));
        //echo $this->db->last_query();
        return $response;
    }

    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('permiso');
        return $result->result_array();
    }

    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('permiso');
        return $result->result();
    }

    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('permiso');
    }

    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('permiso', array('deleted' => 1));
    }

}