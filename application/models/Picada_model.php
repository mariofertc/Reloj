<?php

/**
 * Permite el CRUD de las Picadas con la Base de Datos.
 */
class Picada_model extends CI_Model {

    /**
     * Verifica la existencia de la picada dada.
     * @param int $data
     * @return boolean
     */
    public function exist($data) {
        $this->db->where(array('codigo' => $data['codigo']));
        $this->db->where(array('fecha_picada' => $data['fecha_picada']));
        $result = $this->db->get('picadas');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica la picada con el identificador dado en la base de datos.
     * @param array $data
     * @param int $ide
     * @return boolean
     */
    public function save($data, $ide = -1) {
        $response = null;
        if (!$this->exist($data))
            return $this->db->insert('picadas', $data);
        else
            return true;
    }
    
    /**
     * Almacena el batch de picadas, optimizando el ingreso de las picadas en la base de datos.
     * @param array $data
     * @param int $ide
     * @return type
     */
    public function save_batch($data, $ide = -1) {
        $response = null;
        return $this->db->insert_batch('picadas', $data);
    }

    /**
     * Permite consultar las picadas ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @param array $order Tipo de ordenación de la consulta.
     * @return object[]
     */
    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('picadas');
        return $result->result();
    }

    /**
     * Obtiene la información de la picada.
     * @param int $ide
     * @return type
     * @deprecated since version 1.0.0
     */
    public function get_info($ide) {
        $this->db->where(array('ide' => $ide));
        $result = $this->db->get('empresa');
        return $result->result();
    }
    
    /**
     * Obtiene las picadas que están en el rango dado.
     * @param int $id
     * @return object[]
     */
    public function get_desde_hasta($id) {
        $this->db->select('min(fecha_picada) as min, max(fecha_picada) as max');
        $this->db->where(array('codigo' => $id));
        $result = $this->db->get('picadas');
        return $result->result();
    }
    
    /**
     * Obtiene las picadas agrupadas por las fechas.
     * @return object[]
     */
    public function get_group_by_date() {
        $this->db->select('fecha_creacion, count(*) as total');
        $this->db->group_by('fecha_creacion');
        $result = $this->db->get('picadas');
        return $result->result();
    }
    
    /**
     * Elimina las picadas de la fecha dad.
     * @param datetime $fecha
     * @return object
     */
    public function borrar_registro($fecha) {
        //$this->db->select('count(*)');
        $this->db->where(array('fecha_creacion' => $fecha));        
        $result = $this->db->delete('picadas');
        return $result;
    }

}