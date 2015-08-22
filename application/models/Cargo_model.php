<?php

class Cargo_model extends CI_Model {

    public function exist($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('cargo');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save($data, $id = -1) {
        $response = null;
        if (!$this->exist($id))
            $response = $this->db->insert('cargo', $data);
        else
            $response = $this->db->update('cargo', $data, array('id' => $id));
        return $response;
    }

    public function get_all($num = 0, $offset = 100, $where = null) {
        if (isset($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $result = $this->db->get('cargo');
        return $result->result();
    }

    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('departamento');
        return $result->result();
    }

}