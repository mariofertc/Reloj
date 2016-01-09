<?php

/**
 * Permite el CRUD de los módulos con la Base de Datos.
 */
class Module_model extends CI_Model {

    /**
     * Obtiene el nombre del módulo con el identificador dado.
     * @param int $module_id
     * @return string
     */
    function get_module_name($module_id) {
        $query = $this->db->get_where('modules', array('module_id' => $module_id), 1);

        if ($query->num_rows() == 1) {
            $row = $query->row();
            return $this->lang->line($row->name_lang_key);
        }

        return $this->lang->line('error_unknown');
    }

    /**
     * Obtiene la descripción del módulo con el identificador dado.
     * @param int $module_id
     * @return string
     */
    function get_module_desc($module_id) {
        $query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
        if ($query->num_rows() == 1) {
            $row = $query->row();
            return $this->lang->line($row->desc_lang_key);
        }

        return $this->lang->line('error_unknown');
    }

    /**
     * Obtiene todos los módulos reistrados.
     * @return object[]
     */
    function get_all_modules() {
        $this->db->from('modules');
        $this->db->order_by("sort", "asc");
        return $this->db->get();
    }

    /**
     * Devuelve el listado de módulos a los que la persona tiene acceso.
     * @param int $person_id
     * @return object[]
     */
    function get_allowed_modules($person_id) {
        $this->db->from('modules');
        $this->db->join('permissions', 'permissions.module_id=modules.module_id');
        $this->db->where("permissions.employee_id", $person_id);
        $this->db->order_by("sort", "asc");
        return $this->db->get();
    }

}