<?php
/**
 * Modelo del Empleado
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package CI_Model
 * @subpackage Empleado_model
 * @author Mario Torres
 */
/**
 * Permite el CRUD de los Empleados de la Base de Datos.
 */
class Empleado_model extends CI_Model {

    /**
     * Verifica la existencia del Departamento dado.
     * @param int $ide
     * @return boolean
     */
    public function exist($ide) {
        $this->db->where(array('id' => $ide));
        $result = $this->db->get('empleados');
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica el empleado con el horario, de acuerdo al identificador dado.
     * @param array $data Arreglo con los campos de los horarios.
     * @param int $id
     * @return type
     */
    public function save(&$data, $id = -1) {
        $response = null;
        if (!$this->exist($id)) {
            $this->db->insert('empleados', $data);
            return $data['id'] = $this->db->insert_id();
        }
        else
            return $this->db->update('empleados', $data, array('id' => $id));
    }
    
    /**
     * Permite consultar los empleados ingresados en la base de datos.
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
        $result = $this->db->get('empleados');
        return $result->result_array();
    }

    /**
     * Obtiene la información de los Empleados con sus Horarios.
     * @param int $id
     * @return type
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('empleados');
        return $result->result();
    }

    /**
     * Elimina a los empleados con los identificadores enviados.
     * @param array $ids
     * @return type
     */
    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('empleados', array('deleted' => 1));
    }

    /**
     * Devuelve el numero total de empleados.
     */
    function get_total() {
        return $this->db->count_all('empleados');
    }

    /**
     * Inicia la sesión del usuario, previamente validando su acceso autorizado.
     * @param string $username
     * @param string $password
     * @return boolean
     */
    function login($username, $password) {
        $query = $this->db->get_where('empleados', array('username' => $username, 'password' => sha1($password), 'deleted' => 0), 1);
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $this->session->set_userdata('id', $row->id);
            $this->session->set_userdata('es_personal', true);
            return true;
        }
        return false;
    }
    
    /**
     * Cierra la sesión del usuario.
     */
    function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
    
    /**
     * Verifica si esta logeado el usuario.
     * @return type
     */
    function is_logged_in() {
        return $this->session->userdata('id') != false;
    }
    
    /**
     * Verifica si esta seteado en la sesión el parámetro personal.
     * @return type
     */
    function is_personal_in() {
        return $this->session->userdata('es_personal') != false;
    }
    
    /**
     * Devuelve la información del Empleado que ha iniciado la sesión.
     * @return boolean
     */
    function get_logged_in_employee_info() {
        if ($this->is_logged_in()) {
            return $this->get_info($this->session->userdata('id'));
        }

        return false;
    }

    /**
     * Determina si la persona tiene acceso al módulo indicado.
     * @param int $module_id
     * @param int $person_id
     * @return boolean
     */
    function has_permission($module_id, $person_id) {
        //if no module_id is null, allow access
        if ($module_id == null) {
            return true;
        }

        $query = $this->db->get_where('permissions', array('employee_id' => $person_id, 'module_id' => $module_id), 1);
        return $query->num_rows() == 1;
    }

}