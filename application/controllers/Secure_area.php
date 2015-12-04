<?php

class Secure_area extends CI_Controller {
    /*
      Controllers that are considered secure extend Secure_area, optionally a $module_id can
      be set to also check if a user can access a particular module in the system.
     */

    function __construct($module_id = null) {
        parent::__construct();
        //$CI =& get_instance();
        //$this->load->model('Usuario');
        $logged_in_employee_info = $this->Usuario_model->get_logged_in_employee_info();
        $logged_in_employee_info = (isset($logged_in_employee_info[0]) ? $logged_in_employee_info[0] : array());
        //var_dump($logged_in_employee_info);
        if (!$this->Usuario_model->is_logged_in()) {
            if ($this->Empleado_model->is_logged_in() && $module_id == "reportes") {
                return;
                //redirect(site_url('picadas/personal') . "/" . $logged_in_info->id);
            }
//		echo $this->Usuario->is_logged_in();
            redirect(site_url('login'));
        }

        if (!$this->Usuario_model->has_permission($module_id, $logged_in_employee_info->id)) {
            redirect('no_access/' . $module_id);
        }

        //load up global data
        //var_dump($logged_in_employee_info);
        $data['allowed_modules'] = $this->Module_model->get_allowed_modules($logged_in_employee_info->id);
        $data['user_info'] = $logged_in_employee_info;
//		$this->load->vars($data);
//                $this->twiggy->set($data,null, true);
        $this->twiggy->set($data);
    }

}
