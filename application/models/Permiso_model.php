<?php

/**
 * Permite el CRUD de los permisos con la Base de Datos.
 */
class Permiso_model extends CI_Model {

    /**
     * Verifica la existencia del permiso dado.
     * @param int $id
     * @return boolean
     */
    public function exist($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('permiso');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     *  Almacena o modifica los permisos.
     * @param array $data
     * @param int $id
     * @return object
     */
    public function save(&$data, $id = -1) {
        $response = null;
        $data['fecha_actualizacion'] = date('Y-m-d H:i:s');
        if (!$this->exist($id)) {
            $data['fecha'] = date('Y-m-d H:i:s');
            $response = $this->db->insert('permiso', $data);
            return $data['id'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('permiso', $data, array('id' => $id));
        return $response;
    }

    /**
     * Permite consultar los permisos ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @param array $order Tipo de ordenación de la consulta.
     * @return object[]
     */
    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('permiso');
        return $result->result_array();
    }

    /**
     * Obtiene la información de la picada y permiso.
     * @param type $id
     * @return type
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('permiso');
        return $result->result();
    }

    /**
     * Obtiene el total de permisos ingresados a la base de datos.
     * @return int
     */
    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('permiso');
    }

    /**
     * Elimina los permisos que corresponden a los identificadores dados.
     * @param array $ids
     * @return object
     */
    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('permiso', array('deleted' => 1));
    }

}