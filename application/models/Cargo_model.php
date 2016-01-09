<?php
/**
 * Permite el CRUD de los Cargos con la Base de Datos.
 */
class Cargo_model extends CI_Model {

    /**
     * Verifica la existencia del Cargo dado.
     * @param type $id
     * @return boolean
     */
    public function exist($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('cargo');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }
    
    /**
     * Almacena o modifica el cargo con el identificador dado en la base de datos.
     * @param array $data
     * @param int $id
     * @return object
     */
    public function save(&$data, $id = -1) {
        $response = null;
        if (!$this->exist($id)){
            $response = $this->db->insert('cargo', $data);
            $data['id'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('cargo', $data, array('id' => $id));
        return $response;
    }

    /**
     * Permite consultar los cargos ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param int $where Condición de la consulta.
     * @return object
     */
    public function get_all($num = 0, $offset = 100, $where = null) {
        if (isset($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $result = $this->db->get('cargo');
        return $result->result();
    }

    /**
     * Obtiene la información del departamento.
     * @param type $id
     * @return type
     * @deprecated since version 1.0.0
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('departamento');
        return $result->result();
    }

}