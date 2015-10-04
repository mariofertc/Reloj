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

    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('picadas');
        return $result->result();
    }

    public function get_info($ide) {
        $this->db->where(array('ide' => $ide));
        $result = $this->db->get('empresa');
        return $result->result();
    }
    public function get_desde_hasta($id) {
        $this->db->select('min(fecha_picada) as min, max(fecha_picada) as max');
        $this->db->where(array('codigo' => $id));
        $result = $this->db->get('picadas');
        return $result->result();
    }
    
    public function get_group_by_date() {
        $this->db->select('fecha_creacion, count(*) as total');
        $this->db->group_by('fecha_creacion');
        $result = $this->db->get('picadas');
        return $result->result();
    }
    public function borrar_registro($fecha) {
        //$this->db->select('count(*)');
        $this->db->where(array('fecha_creacion' => $fecha));        
        $result = $this->db->delete('picadas');
        return $result;
    }

}