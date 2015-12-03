<?php

class Usuario_model extends CI_Model {

    public function exist($ide) {
        $this->db->where(array('id' => $ide));
        $result = $this->db->get('usuarios');
        //var_dump();
        if ($result->num_rows() > 0)
            return true;
        else
            return false;
    }

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
                            'employee_id' => isset($data['id'])?$data['id']:$id));
                    }
                }
            }
        }
        $this->db->trans_complete();
        return $success;
    }

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

    public function get_info($id) {
        $this->db->where(array('id' => $id));
        $result = $this->db->get('usuarios');
        return $result->result();
    }

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

    /*
      Attempts to login employee and set session. Returns boolean based on outcome.
     */

    function login($username, $password) {
        $query = $this->db->get_where('usuarios', array('username' => $username, 'password' => md5($password), 'deleted' => 0), 1);
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $this->session->set_userdata('person_id', $row->person_id);
            //echo $row->person_id;
            return true;
        }
        return false;
    }

    /*
      Logs out a user by destorying all session data and redirect to login
     */

    function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

    /*
      Determins if a employee is logged in
     */

    function is_logged_in() {
        //echo $this->session->userdata('person_id');
        return $this->session->userdata('person_id') != false;
    }

    /*
      Gets information about the currently logged in employee.
     */

    function get_logged_in_employee_info() {
        if ($this->is_logged_in()) {
            return $this->get_info($this->session->userdata('person_id'));
        }

        return false;
    }

    /*
      Determins whether the employee specified employee has access the specific module.
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