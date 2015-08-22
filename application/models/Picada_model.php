<?php

class Picada_model extends CI_Model {

    public function exist($data) {
        //var_dump($data);
        $this->db->where(array('codigo' => $data['codigo']));
        $this->db->where(array('fecha_picada' => $data['fecha_picada']));
        $result = $this->db->get('picadas');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function save($data, $ide = -1) {
        $response = null;
        if (!$this->exist($data))
            return $this->db->insert('picadas', $data);
        else
            return true;
    }

    public function get_all() {
        $result = $this->db->get('empresa');
        return $result->result();
    }

    public function get_info($ide) {
        $this->db->where(array('ide' => $ide));
        $result = $this->db->get('empresa');
        return $result->result();
    }

}