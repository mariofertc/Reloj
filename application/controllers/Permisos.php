<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

class Permisos extends Secure_area {

    public $controller_name;

    public function __construct() {
        $this->controller_name = "permisos";
        parent::__construct($this->controller_name);
    }

    public function index() {
        $data['admin_table'] = get_permiso_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('permisos/todos');
    }

    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
//        $data['dias'] = $this->input->post('dias');
        $data['tipo_permiso'] = $this->input->post('tipo_permiso');
        try {
            if ($this->Permiso_model->save($data, $id)) {
                if ($id == null) {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line($this->controller_name . '_successful_add') .
                        $data['nombre'], 'id' => $data['id']));
                } else {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line($this->controller_name . '_successful_update') .
                        $data['nombre'], 'id' => $id));
                }
            } else {
                echo json_encode(array('success' => false, 'message' => $this->lang->line($this->controller_name . '_error_add_update') .
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
        if ($this->Permiso_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line($this->controller_name . '_successful_deleted') . ' ' .
                count($to_delete) . ' ' . $this->lang->line($this->controller_name . '_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line($this->controller_name . '_cannot_be_deleted')));
        }
    }

    function mis_datos() {
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = 150;
        $aColumns = array('id', 'nombre', 'tipo_permiso', 'fecha');
        //Eventos Tabla
        $cllAccion = array(
            '0' => array(
                'function' => "view",
                'common_language' => "common_edit",
                'language' => "_update",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()));
        echo getData('Permiso_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    public function view($id = null) {
        $data['title'] = "Reloj | Permisos";
        $data['titulo'] = "Permisos";
        $data['controller_name'] = $this->controller_name;
        if ($id) {
            $info = $this->Permiso_model->get_info($id);
            $data['data'] = $info[0];
        }
        $this->twiggy->set($data);
        $this->twiggy->display($this->controller_name . '/insert');
    }

    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Permiso_model->get_info($id);
        echo get_permiso_data_row($info[0], $this);
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
