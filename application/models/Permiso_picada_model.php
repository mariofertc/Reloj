<?php

class Permiso_picada_model extends CI_Model {

    public function exist($picada) {
        $this->db->where(array('codigo' => $picada['codigo']));
        $this->db->where(array('picada' => $picada['fecha']));
        $result = $this->db->get('permiso_picadas');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    public function get_permisos($picada) {
        //$this->db->select("DATE_FORMAT(nueva_picada,'%r') as nueva_picada,picada,tipo_permiso,codigo,posicion,DATE_FORMAT(picada,'%r') as vieja_picada, permiso.nombre");
        $this->db->select("nueva_picada,picada,tipo_permiso,codigo,posicion,DATE_FORMAT(picada,'%r') as vieja_picada, permiso.nombre");
        $this->db->from('permiso_picadas');
        $this->db->where(array('codigo' => $picada['codigo']));
        $this->db->where(array('picada' => $picada['fecha']));
        $this->db->where(array('permiso_picadas.deleted' => 0));
        $this->db->join('permiso','permiso.id=permiso_picadas.tipo_permiso');
        $query = $this->db->get();
//        $query = $this->db->get('permiso_picadas');
        return $query->result_array();
    }

    public function save(&$data, $picada) {
        $response = true;
        if ($this->exist($picada)) {
            $this->db->where(array('codigo' => $picada['codigo']));
            $this->db->where(array('picada' => $picada['fecha']));
            $this->db->update('permiso_picadas', array('deleted' => 1));
        }
        if (count($data) > 0)
            $response = $this->db->insert_batch('permiso_picadas', $data);
        //echo $this->db->last_query();
        return $response;
    }

    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        //$this->db->select("DATE_FORMAT(nueva_picada,'%r') as nueva_picada,picada,tipo_permiso,codigo,posicion,DATE_FORMAT(picada,'%r') as vieja_picada, permiso.nombre");
        $this->db->select("permiso_picadas.*,permiso.*");
        $this->db->from('permiso_picadas');
        $this->db->where_not_in('permiso_picadas.deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
         $this->db->join('permiso','permiso.id=permiso_picadas.tipo_permiso');
        $result = $this->db->get();
//        $result = $this->db->get('permiso_picadas');
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