<?php

class Departamento_model extends CI_Model {

    public function exist($iddep) {
        $this->db->where(array('iddep' => $iddep));
        $result = $this->db->get('departamento');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save(&$data, $iddep = -1) {
        $response = null;
        if (!$this->exist($iddep)){
            $response = $this->db->insert('departamento', $data);
            $data['iddep'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('departamento', $data, array('iddep' => $iddep));
        return $response;
    }

    public function get_all($num = 0, $offset = 100, $where = null) {
        if (isset($where))
            $this->db->where($where);
        $this->db->where(array('deleted'=>0));
        $this->db->limit($num, $offset);
        $result = $this->db->get('departamento');
        return $result->result();
    }

    public function get_info($iddep) {
        $this->db->where(array('iddep' => $iddep));
        $result = $this->db->get('departamento');
        return $result->result();
    }

}
