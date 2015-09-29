<?php

class Seccion_model extends CI_Model {

    public function exist($idsec) {
        $this->db->where(array('idsec' => $idsec));
        $result = $this->db->get('seccion');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save(&$data, $idsec = -1) {
        $response = null;
        if (!$this->exist($idsec)){
            $response = $this->db->insert('seccion', $data);
            $data['idsec'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('seccion', $data, array('idsec' => $idsec));
        //echo $this->db->last_query();
        return $response;
    }

    public function get_all($num = 0, $offset = 100, $where = null) {
        if ($where != null)
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $result = $this->db->get('seccion');
        return $result->result();
    }

    public function get_info($idsec) {
        $this->db->where(array('idsec' => $idsec));
        $result = $this->db->get('seccion');
        return $result->result();
    }

}