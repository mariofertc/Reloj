<?php
/**
 * Controlador de Reportes.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Secure_area
 * @subpackage Reportes
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

/**
 * Controlador que maneja los reportes del Sistema.
 */
class Reportes extends Secure_area {

    /**
     *Almacenta el nombre del controlador.
     * @var string 
     */
    public $controller_name;

    /**
     * Inicializa la clase reportes.
     */
    public function __construct() {
        $this->controller_name = "reportes";
        parent::__construct($this->controller_name);
    }

    /**
     * Genera el Reporte Personal al que tiene acceso cada empleado.
     * 
     * @param int $id del Empleado.
     */
    public function personal($id) {
        $empresas = $this->Empresa_model->get_all(0, 100);
        $departamentos = $this->Departamento_model->get_all(0, 100);
        $secciones = $this->Seccion_model->get_all(0, 100);
        $empleados = $this->Empleado_model->get_info($id);
        $data['empleado'] = $empleados[0];
        $data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => '', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));

        $this->twiggy->set($data);
        $this->twiggy->display('reportes/horas_trabajadas');
    }

}
