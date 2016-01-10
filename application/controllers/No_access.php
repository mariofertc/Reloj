<?php

/**
 * Controlador de No acceso.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package CI_Controller
 * @subpackage Empleados
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Indica a los usuarios no autorizados, que el módulo al que quieren acceder está restringido.
 */
class No_access extends CI_Controller {

    /**
     * Visualiza pantalla de acceso restringido.
     * @param type $module_id **ID** del módulo que no tiene acceso.
     */
    function index($module_id = '') {
        $data['module_name'] = $this->Module_model->get_module_name($module_id);
        $this->twiggy->set($data, null);
        $this->twiggy->display('no_access');
    }

}