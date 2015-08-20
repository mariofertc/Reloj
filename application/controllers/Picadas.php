<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Picadas extends CI_Controller {

    public $controller_name;

    public function __construct() {
        $this->controller_name = "empleados";
        parent::__construct();
    }

    public function index() {
        $empleados = $this->Empleado_model->get_all(0, 100);
        $data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => 'Seleccione un Empleado', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));

        $this->twiggy->set($data);
        $this->twiggy->display('picadas/registros');
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
        //$data['fecha_ingreso'] = date('Y-m-d H:i:s', now());
        $data['id_horario'] = $this->input->post('id_horario');
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
                'height' => $this->get_form_height()));
        echo getData('Empleado_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    public function view($id = null) {
        $data['title'] = "Reloj | Empleados";
        $data['titulo'] = "Empleados";
        $horarios = $this->Horario_model->get_all();
        $cll_horario = array();
        foreach ($horarios as $horario) {
            $cll_horario[$horario['id']] = $horario['nombre'];
        }
        $data['horarios'] = $cll_horario;
        if ($id)
            $data['data'] = $this->Empleado_model->get_info($id)[0];
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

    function load_data_reloj($id = -1) {
        $subir = $this->input->post('userfile');
        $upLoad = array();
        if (is_bool($subir)) {
            $upLoad = do_upload('reloj', '*');
            if ($upLoad['error'] != "0") {
                echo json_encode(array('success' => false, 'message' => $upLoad['error'], 'id' => $id));
                return;
            }
        }
        $path_file = $upLoad['upload_data']['full_path'];
        $formato = $this->input->post('formato');
        $success = true;
        $config = array();
        switch ($formato) {
            case "estacion":
                $config['separador'] = ',';
                $config['data']=array('fecha' => array('indice' => array(1, 2), 'format' => 'd m Y H i'),
                                    'codigo' => array('indice' => array(1)));
                $datos = read_data($path_file, array('fecha_subida'=>now()), $config);
                $success = $this->picada_model->save($datos);
                break;
        }
        echo json_encode(array('success' => $success, 'message' => 'Datos subidos satisfactoriamente!', 'id' => $id));
    }

    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        echo get_empleado_data_row($this->Empleado_model->get_info($id)[0], $this);
    }

    public function get_form_width() {
        return 400;
    }

    public function get_form_height() {
        return 500;
    }

}