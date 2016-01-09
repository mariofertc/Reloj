<?php

/**
 * Permite el CRUD de los Departamentos con la Base de Datos.
 */
class Departamento_model extends CI_Model {

    /**
     * Verifica la existencia del Departamento dado.
     * @param int $iddep
     * @return boolean
     */
    public function exist($iddep) {
        $this->db->where(array('iddep' => $iddep));
        $result = $this->db->get('departamento');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica el departamento con el identificador dado en la base de datos.
     * @param array $data
     * @param int $iddep
     * @return object
     */
    public function save(&$data, $iddep = -1) {
        $response = null;
        if (!$this->exist($iddep)) {
            $response = $this->db->insert('departamento', $data);
            $data['iddep'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('departamento', $data, array('iddep' => $iddep));
        return $response;
    }

    /**
     * Permite consultar los departamentos ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @return object
     */
    public function get_all($num = 0, $offset = 100, $where = null) {
        if (isset($where))
            $this->db->where($where);
        $this->db->where(array('deleted' => 0));
        $this->db->limit($num, $offset);
        $result = $this->db->get('departamento');
        return $result->result();
    }

    /**
     * Obtiene la información del departamento.
     * @param type $iddep
     * @return type
     */
    public function get_info($iddep) {
        $this->db->where(array('iddep' => $iddep));
        $result = $this->db->get('departamento');
        return $result->result();
    }

    /**
     * Verifica si existe un departamento con el nombre dado.
     * @param array $data
     * @return boolean
     */
    public function exist_name($data) {
        $this->db->where(array('departamento' => $data['nombre']));
        $this->db->where(array('iddep!=' => $data['id']));
        $this->db->where(array('deleted!=' => 1));
        $result = $this->db->get('departamento');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

}
