<?php
/**
 * Modelo del Horario
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package CI_Model
 * @subpackage Horario_model
 * @author Mario Torres
 */
/**
 * Permite el CRUD del Horario con la Base de Datos.
 */
class Horario_model extends CI_Model {

    /**
     * Verifica la existencia del Horario dado.
     * @param int $id
     * @return boolean
     */
    public function exist($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('horario');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }
    
    /**
     * Verifica si existe un horario con el nombre dado.
     * @param array $data
     * @return boolean
     */
    public function exist_name($data) {
        $this->db->where(array('nombre' => $data['nombre']));
        $this->db->where(array('id!=' => $data['id']));
        $result = $this->db->get('horario');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica el horario con el identificador dado en la base de datos.
     * @param array $data
     * @param int $id
     * @return object
     */
    public function save(&$data, $id = -1) {
        $response = null;
        $data['fecha_actualizacion'] = date('Y-m-d H:i:s');
        if (!$this->exist($id)) {
            $data['fecha_creacion'] = date('Y-m-d H:i:s');
            $response = $this->db->insert('horario', $data);
            return $data['id'] = $this->db->insert_id();
        }
        else
            $response = $this->db->update('horario', $data, array('id' => $id));
        //echo $this->db->last_query();
        return $response;
    }

    /**
     * Permite consultar los horarios ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @param type $order Ordenamiento de la consulta.
     * @return object
     */
    public function get_all($num = 0, $offset = 0, $where = null, $order = null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('horario');
        return $result->result_array();
    }
    
    /**
     * Obtiene todos los horarios que un empleado tiene asignados.
     * @param int $id_empleado
     * @return object
     */
    function get_horario_empleado($id_empleado){
        $this->db->where_not_in('empleados_horario.deleted', 1);
        $this->db->order_by('horario.fecha_creacion', 'DESC');
        $this->db->from('horario');
        $this->db->join('empleados_horario','horario.id=empleados_horario.id_horario');
        $this->db->where('empleados_horario.id_empleado',$id_empleado);
        $result = $this->db->get();
        return $result->result_array();
    }

    /**
     * Obtiene la información del horario.
     * @param int $id
     * @return object
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('horario');
        return $result->result();
    }

    /**
     * Total de horarios ingresados en la base de datos.
     * @return int
     */
    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('horario');
    }

    /**
     * Elimina los horarios con los identificadores dados. Borrado lógico.
     * @param array $ids
     * @return type
     */
    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('horario', array('deleted' => 1));
    }

}