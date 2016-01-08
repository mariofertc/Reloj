<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase Base de la seguridad del Sistema.
 * 
 * Los controladores que se desee agregar seguridad deben extender esta clase.
 */
class Secure_area extends CI_Controller {
    
    /**
     * Inicializador de la clase Secure_area.
     * 
     * Chequea si el identificador del módulo es visible para el usuario.
     * 
     * @param int $module_id Identificador del módulo.
     */
    function __construct($module_id = null) {
        parent::__construct();
        $logged_in_employee_info = $this->Usuario_model->get_logged_in_employee_info();
        $logged_in_employee_info = (isset($logged_in_employee_info[0]) ? $logged_in_employee_info[0] : array());
        if (!$this->Usuario_model->is_logged_in()) {
            if ($this->Empleado_model->is_logged_in() && $module_id == "reportes") {
                return;
            }
            redirect(site_url('login'));
        }
        if (!$this->Usuario_model->has_permission($module_id, $logged_in_employee_info->id)) {
            redirect('no_access/' . $module_id);
        }

        //load up global data
        $data['allowed_modules'] = $this->Module_model->get_allowed_modules($logged_in_employee_info->id);
        $data['user_info'] = $logged_in_employee_info;
        $this->twiggy->set($data);
    }

}
