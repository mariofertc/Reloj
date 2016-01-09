<?php

/**
 * Permite el CRUD de las Picadas y los Permisos con la Base de Datos.
 */
class Permiso_picada_model extends CI_Model {

    /**
     * Verifica la existencia de la picada dada en la tabla intermedia.
     * @param type $picada
     * @return boolean
     */
    public function exist($picada) {
        $this->db->where(array('codigo' => $picada['codigo']));
        $this->db->where(array('picada' => $picada['fecha']));
        $result = $this->db->get('permiso_picadas');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Obtiene los permisos de acuerdo a la picada con fecha dadas.
     * @param array $picada
     * @return object[]
     */
    public function get_permisos($picada) {
        //$this->db->select("DATE_FORMAT(nueva_picada,'%r') as nueva_picada,picada,tipo_permiso,codigo,posicion,DATE_FORMAT(picada,'%r') as vieja_picada, permiso.nombre");
        $this->db->select("nueva_picada,picada,permiso_picadas.tipo_permiso,codigo,posicion,DATE_FORMAT(picada,'%r') as vieja_picada, permiso.nombre");
        $this->db->from('permiso_picadas');
        $this->db->where(array('codigo' => $picada['codigo']));
        $this->db->where(array('picada' => $picada['fecha']));
        $this->db->where(array('permiso_picadas.deleted' => 0));
        $this->db->join('permiso', 'permiso.id=permiso_picadas.tipo_permiso');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Almacena o modifica las picadas con los permisos dados.
     * @param array $data
     * @param array $picada
     * @return object
     */
    public function save(&$data, $picada) {
        $response = true;
        if ($this->exist($picada)) {
            $this->db->where(array('codigo' => $picada['codigo']));
            $this->db->where(array('picada' => $picada['fecha']));
            $this->db->update('permiso_picadas', array('deleted' => 1));
        }
        if (count($data) > 0)
            $response = $this->db->insert_batch('permiso_picadas', $data);
        return $response;
    }

    /**
     * Permite consultar las picadas y los permiso ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @param array $order Tipo de ordenación de la consulta.
     * @return object[]
     */
    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        //$this->db->select("DATE_FORMAT(nueva_picada,'%r') as nueva_picada,picada,tipo_permiso,codigo,posicion,DATE_FORMAT(picada,'%r') as vieja_picada, permiso.nombre");
        $this->db->select("permiso_picadas.*,permiso.*");
        $this->db->from('permiso_picadas');
        $this->db->where_not_in('permiso_picadas.deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $this->db->join('permiso', 'permiso.id=permiso_picadas.tipo_permiso');
        $result = $this->db->get();
        return $result->result_array();
    }

    /**
     * Obtiene la información de la picada y permiso.
     * @param type $id
     * @return type
     * @deprecated since version 1.0.0
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('permiso');
        return $result->result();
    }

    /**
     * Obtiene el total de permisos ingresados a la base de datos.
     * @return int
     * @deprecated since version 1.0.0
     */
    function get_total() {
        return $this->db->count_all('permiso');
    }

    /**
     * Elimina los permisos que corresponden a los identificadores dados.
     * @param array $ids
     * @return object
     * @deprecated since version 1.0.0
     */
    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('permiso', array('deleted' => 1));
    }

}