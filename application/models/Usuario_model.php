<?php
/**
 * Modelo del Usuario
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package CI_Model
 * @subpackage Usuario_model
 * @author Mario Torres
 */
/**
 * Permite el CRUD de los Usuarios con la Base de Datos.
 */
class Usuario_model extends CI_Model {

    /**
     * Verifica la existencia del Usuario dado.
     * @param int $ide
     * @return boolean
     */
    public function exist($ide) {
        $this->db->where(array('id' => $ide));
        $result = $this->db->get('usuarios');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Almacena o modifica el usuario con el identificador dado en la base de datos.
     * @param array $data
     * @param int $id
     * @param array $permission_data Lista de permisos.
     * @return object
     */
    public function save(&$data, $id = -1, $permission_data) {

        $success = false;
        $this->db->trans_start();
        if (!$this->exist($id)) {
            $this->db->insert('usuarios', $data);
            $success = $data['id'] = $this->db->insert_id();
        }
        else
            $success = $this->db->update('usuarios', $data, array('id' => $id));

        if ($success) {
            $success = $this->db->delete('permissions', array('employee_id' => $id));
            //Insert the new permissions
            if ($success) {
                if ($permission_data <> null) {
                    foreach ($permission_data as $allowed_module) {
                        $success = $this->db->insert('permissions', array(
                            'module_id' => $allowed_module,
                            'employee_id' => isset($data['id']) ? $data['id'] : $id));
                    }
                }
            }
        }
        $this->db->trans_complete();
        return $success;
    }

    /**
     * Permite consultar los usuarios ingresados en la base de datos.
     * @param int $num Inicio de los registros.
     * @param int $offset Cantidad de registros.
     * @param array $where Condición de la consulta.
     * @param array $order Ordenamiento de la consulta.
     * @param array $select Campos de la consulta.
     * @return object[]
     */
    public function get_all($num = 0, $offset = 0, $where = null, $order = null, $select = null) {
        $this->db->where_not_in('deleted', 1);
        if (!empty($where))
            $this->db->where($where);
        if (!empty($select))
            $this->db->select($select);
        $this->db->limit($num, $offset);
        $this->db->order_by($order);
        $result = $this->db->get('usuarios');
        return $result->result_array();
    }

    /**
     * Obtiene la información del usuario.
     * @param int $id
     * @return type
     */
    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('usuarios');
        return $result->result();
    }

    /**
     * Elimina los usuarios con los identificadores dados. Borrado lógico.
     * @param int $ids
     * @return type
     */
    public function delete_list($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->update('usuarios', array('deleted' => 1));
    }

    /**
     * Devuelve el numero total de items
     */
    function get_total() {
        //$this->mongo_db->where(array('deleted' => array('$exists' => false)));
        return $this->db->count_all('usuarios');
    }

    /**
     * Inicia la sesión del usuario, previamente validando su acceso autorizado.
     * @param string $username
     * @param string $password
     * @return boolean
     */
    function login($username, $password) {
        $query = $this->db->get_where('usuarios', array('username' => $username, 'password' => sha1($password), 'deleted' => 0), 1);
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $usuario =  new stdClass();
            $usuario->nombre = $row->username;
            $this->session->set_userdata('user_info', $usuario);
            $this->session->set_userdata('person_id', $row->id);
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
        return $this->session->userdata('person_id') != false;
    }

    /**
     * Devuelve la información del Empleado que ha iniciado la sesión.
     * @return boolean
     */
    function get_logged_in_employee_info() {
        if ($this->is_logged_in()) {
            return $this->get_info($this->session->userdata('person_id'));
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