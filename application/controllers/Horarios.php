<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Horarios extends CI_Controller {

    public $controller_name;

    public function __construct() {
        $this->controller_name = "horarios";
        parent::__construct();
    }

    public function index() {
        $data['admin_table'] = get_horario_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('horarios/todos');
    }

    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $data['numero_horas'] = $this->input->post('numero_horas');
        $data['picadas'] = json_encode($this->input->post('picada'));
        $data['dias'] = json_encode($this->input->post('dias'));
        $data['horas_extras'] = json_encode($this->input->post('horas_extras'));
        $data['minuto_gracia'] = $this->input->post('minuto_gracia');
        $data['es_rotativo'] = $this->input->post('es_rotativo');

        try {
            if ($this->Horario_model->save($data, $id)) {
                if ($id == null) {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('horarios_successful_add') .
                        $data['nombre'], 'id' => $data['id']));
                } else {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('horarios_successful_update') .
                        $data['nombre'], 'id' => $id));
                }
            } else {
                echo json_encode(array('success' => false, 'message' => $this->lang->line('horarios_error_add_update') .
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
        if ($this->Horario_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('horarios_successful_deleted') . ' ' .
                count($to_delete) . ' ' . $this->lang->line('horarios_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('horarios_cannot_be_deleted')));
        }
    }

    function mis_datos() {
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = 150;
        $aColumns = array('id', 'nombre', 'numero_horas', 'picadas', 'dias', 'horas_extras');
        //Eventos Tabla
        $cllAccion = array(
            '0' => array(
                'function' => "view",
                'common_language' => "common_edit",
                'language' => "_update",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()));
        echo getData('Horario_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    public function view($id = null) {
        $data['title'] = "Reloj | Horarios";
        $data['titulo'] = "Horarios";
        $data['controller_name'] = $this->controller_name;
        $data['dias_semana'] = dias_semana();
        $data['dias_semana_full'] = dias_semana_full();
        if ($id) {
            $info = $this->Horario_model->get_info($id);
            $data['data'] = $info[0];
            $data['data']->dias = json_decode($data['data']->dias);
            $data['data']->horas_extras = json_decode($data['data']->horas_extras);
            $data['data']->picadas = json_decode($data['data']->picadas);
        }
        $this->twiggy->set($data);
        $this->twiggy->display('horarios/insert');
    }

    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Horario_model->get_info($id);
        echo get_horario_data_row($info[0],$this);
    }
    public function importar_registro() {
        //var_dump($this->Registro_model->leer_datos('uploads/registro.txt'));
        $row = 0;
        if (($handle = fopen('uploads/registro.txt', "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }

    public function get_form_width() {
        return 400;
    }

    public function get_form_height() {
        return 500;
    }

}