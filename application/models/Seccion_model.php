<?php

/**
 * Permite el CRUD de las Secciones con la Base de Datos.
 */
class Seccion_model extends CI_Model {

    /**
     * Verifica la existencia de la sección dada.
     * @param int $idsec
     * @return boolean
     */
    public function exist($idsec) {
        $this->db->where(array('idsec' => $idsec));
        $result = $this->db->get('seccion');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica la sección con el identificador dado en la base de datos.
     * @param array $data
     * @param int $idsec
     * @return type
     */
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

    /**
     * Permite consultar los usuarios ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @return object[]
     */
    public function get_all($num = 0, $offset = 100, $where = null) {
        if ($where != null)
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $result = $this->db->get('seccion');
        return $result->result();
    }

    /**
     * Obtiene la información de la sección.
     * @param int $idsec
     * @return object
     */
    public function get_info($idsec) {
        $this->db->where(array('idsec' => $idsec));
        $result = $this->db->get('seccion');
        return $result->result();
    }

}