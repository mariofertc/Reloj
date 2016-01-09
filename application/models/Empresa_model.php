<?php

/**
 * Permite el CRUD de la Empresa con la Base de Datos.
 */
class Empresa_model extends CI_Model {

    /**
     * Verifica la existencia de la Empresa dado.
     * @param type $ide
     * @return boolean
     */
    public function exist($ide) {
        $this->db->where(array('ide' => $ide));
        $result = $this->db->get('empresa');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica la empresa con el identificador dado en la base de datos.
     * @param array $data1
     * @param int $ide
     * @return boolean
     */
    public function save($data1, $ide = -1) {
        $response = null;
        if (!$this->exist($ide)) {
            if ($this->total() < 1)
                $response = $this->db->insert('empresa', $data1);
            else
                return false;
        }
        else
            $response = $this->db->update('empresa', $data1, array('ide' => $ide));
        //echo $this->db->last_query();
        return $response;
    }

    /**
     * Obtiene el total de empresas ingresadas.
     * @return int
     */
    public function total() {
        $result = $this->db->get('empresa');
        $result = $result->result_array();

        return count($result);
    }

    /** 
     * Obtiene la información de todas las empresas.
     * @return object
     */
    public function get_all() {
        $result = $this->db->get('empresa');
        return $result->result();
    }

    /**
     * Obtiene la información de la empresa.
     * @param int $ide
     * @return object
     */
    public function get_info($ide) {
        $this->db->where(array('ide' => $ide));
        $result = $this->db->get('empresa');
        return $result->result();
    }

}