<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empleados extends CI_Controller {

    public $controller_name;

    public function __construct() {
        $this->controller_name = "empleados";
        parent::__construct();
    }

    public function index() {
        /* $data['datos'] = $this->Empleado_model->get_all();
          $this->twiggy->set($data);
          $this->twiggy->display('empleados/todos'); */

        $data['admin_table'] = get_empleado_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('empleados/todos');
    }

    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $data['apellido'] = $this->input->post('apellido');
        $data['edad'] = $this->input->post('edad');
        $data['cedula'] = $this->input->post('cedula');
        $data['estadocivil'] = $this->input->post('estadocivil');
        $data['direccion'] = $this->input->post('direccion');
        $data['fecha_ingreso'] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_ingreso')));
        $data['id_seccion'] = $this->input->post('id_seccion');
        $data['id_horario'] = $this->input->post('id_horario');
        $data['id_cargo'] = $this->input->post('id_cargo');
        $data['id_reloj'] = $this->input->post('id_reloj');
        try {
            if ($this->Empleado_model->save($data, $id)) {
                if ($id == null) {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_add') .
                        $data['nombre'], 'id' => $data['id']));
                } else {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_update') .
                        $data['nombre'], 'id' => $id));
                }
            } else {
                echo json_encode(array('success' => false, 'message' => $this->lang->line('empleados_error_add_update') .
                    $data['nombre'], 'id' => -1));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e .
                $data['nombre'], 'id' => $id));
            $this->db->trans_off();
        }
    }

    public function delete($id = null) {
        $to_delete = $this->input->post('ids');
        if ($this->Empleado_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_deleted') . ' ' .
                count($to_delete) . ' ' . $this->lang->line('empleados_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('empleados_cannot_be_deleted')));
        }
    }

    function mis_datos() {
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = 150;
        $aColumns = array('id', 'nombre', 'apellido', 'edad', 'cedula', 'direccion', 'fecha_ingreso');
        //Eventos Tabla
        $cllAccion = array(
            '1' => array(
                'function' => "view",
                'common_language' => "common_edit",
                'language' => "_update",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()),
            '0' => array(
                'function' => "vacaciones",
                'common_language' => "empleados_vacations",
                'language' => "_more_vacations",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()));
        header("Access-Control-Allow-Origin: *");
        echo getData('Empleado_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    public function view($id = null) {
        $data['title'] = "Reloj | Empleados";
        $data['titulo'] = "Empleados";
        $horarios = $this->Horario_model->get_all();
        $cll_horario = array();
        foreach($horarios as $horario){
            $cll_horario[$horario['id']] = $horario['nombre'];
        }
        $cargo = $this->Cargo_model->get_all();
        $data['cargos'] = array_to_htmlcombo($cargo, array('blank_text' => 'Seleccione un Cargo', 'id' => 'id', 'name' => array('nombre')));
        
        $departamentos = $this->Departamento_model->get_all(0, 100);
        $cll_seccion = array();
        foreach($departamentos as $departamento){
            $secciones = $this->Seccion_model->get_all(0,100, array('iddep'=>$departamento->iddep));
            $cll_seccion_temp = array();
            foreach ($secciones as $seccion) {
//                $cll_seccion[$departamento->departamento] = $seccion->seccion;
                $cll_seccion_temp[$seccion->idsec] = $seccion->seccion;
            }
                $cll_seccion[$departamento->departamento] = $cll_seccion_temp;
//                $cll_seccion[$departamento->departamento] = array($seccion->idsec,$seccion->seccion);
        }
        //$data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => 'Seleccione un Empleado', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        
        $data['secciones'] = $cll_seccion;
        $data['horarios'] = $cll_horario;
        if ($id){
            $info = $this->Empleado_model->get_info($id);
            $data['data'] = $info[0];
        }
        $this->twiggy->set($data);
        $this->twiggy->display('empleados/insert');
    }

    public function buscar_vista() {
        $data['id'] = $this->input->post('q');
        $data['datos'] = $this->Empleado_model->get_info($data['id']);
        //foreach($data as $empleado){
        //
		//	var_dump($empleado);
        //}
        $emp = $data['datos'];
        if (count($emp) > 0) {
            $edad = $data['datos'][0]->edad;
            $id = $data['datos'][0]->id;
            $resultado = $edad + $id * $edad;
            $data['resultado'] = $resultado;
            $data['datos'][0]->se_casa = $data['datos'][0]->edad * $data['datos'][0]->edad;
        }
        $this->twiggy->set($data);
        $this->twiggy->display('empleados/buscar');
    }

    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Empleado_model->get_info($id);
        echo get_empleado_data_row($info[0],$this);
    }

    public function get_form_width() {
        return 400;
    }

    public function get_form_height() {
        return 500;
    }

}