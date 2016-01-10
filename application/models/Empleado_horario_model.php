<?php
/**
 * Modelo del Empleado
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package CI_Model
 * @subpackage Empleado_horario_model
 * @author Mario Torrses
 */
/**
 * Permite el CRUD de los Empleados con sus horarios asignados de la Base de Datos.
 */
class Empleado_horario_model extends CI_Model {

    /**
     * Verifica la existencia del Departamento dado.
     * 
     * @param int $ide
     * @return boolean
     */
    public function exist($ide) {
        $this->db->where(array('id_empleado' => $ide));
        $result = $this->db->get('empleados_horario');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica el empleado con el horario, de acuerdo al identificador dado.
     * @param type $id
     * @param type $data
     * @return boolean
     */
    public function save($id, &$data) {
        $response = null;
        if (!$this->exist($id)) {
            $this->db->insert_batch('empleados_horario', $data);
            return true;
        }
        else{
            $this->delete($id);
            return $this->db->insert_batch('empleados_horario', $data);
        }
    }

    /**
     * Permite consultar la relación de empleados con horarios ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @param array $order Ordenamiento de la consulta.
     * @param array $select Campos de la consulta.
     * @return object
     */
    public function get_all($num = 0, $offset = 0, $where = null, $order = null,$select=null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        if (!empty($select))
            $this->db->select($select);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('empleados_horario');
        return $result->result_array();
    }

    /**
     * Obtiene la información de los Empleados con sus Horarios.
     * @param int $id
     * @return object
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('empleados_horario');
        return $result->result();
    }
    /**
     * Devuelve el numero total de empleados y horarios.
     */
    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('empleados_horario');
    }
    
    /**
     * Elimina los datos de los empleados y horarios, con borrado lógico.
     * @param type $id
     * @return type
     */
    function delete($id) {
        return $this->db->update('empleados_horario',array('deleted'=>1),array('id_empleado'=>$id));
    }

}