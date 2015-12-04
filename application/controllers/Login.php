<?php

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
//        $this->load->spark('twiggy/0.8.5');
    }

    function index() {
        if ($this->Usuario_model->is_logged_in()) {
            //echo "yap";
            redirect('home');
        } else {
//$this->form_validation->set_rules('username', 'lang:login_username', 'callback_login_check');
            $this->form_validation->set_rules('username', 'lang:login_username', 'callback_login_check');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'login_login';
                $this->twiggy->set($data);
                $this->twiggy->display('login');
            } else {
                if ($this->Empleado_model->is_logged_in() && !$this->Usuario_model->is_logged_in()) {
                    $logged_in_info = $this->Empleado_model->get_logged_in_employee_info();
                    $logged_in_info = isset($logged_in_info[0]) ? $logged_in_info[0] : null;
                    redirect(site_url('reportes/personal'). "/" . $logged_in_info->id);
                }
                //echo "yap";
                redirect('home');
            }
        }
    }

    function login_check($username) {
        $password = $this->input->post("password");

        if (!$this->Usuario_model->login($username, $password)) {
            if ($this->Empleado_model->login($username, $password)) {
                return true;
            }
            $this->form_validation->set_message('login_check', $this->lang->line('login_invalid_username_and_password'));
            return false;
        }
        return true;
    }

}
