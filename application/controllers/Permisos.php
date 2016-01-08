<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

/**
 * Permite administra el tipo de Permisos que los Empleados pueden tener.
 */
class Permisos extends Secure_area {

    public $controller_name;

    /**
     * Inicializa la clase de tipo Permiso.
     */
    public function __construct() {
        $this->controller_name = "permisos";
        parent::__construct($this->controller_name);
    }

    /**
     * Presenta los permisos existentes en el sistema.
     */
    public function index() {
        $data['admin_table'] = get_permiso_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('permisos/todos');
    }

    /**
     * Almacena el permiso con los parámetros pasados.
     * @param type $id En caso que el *id* corresponda a un permiso existente, se actualiza el permiso, 
     * caso contrario se inserta un nuevo permiso en la base de datos.
     */
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

    /**
     * Elimina el permiso con el *id* correspondiente del sistema.
     * @param type $id
     */
    public function delete($id = null) {
        $to_delete = $this->input->post('ids');
        if ($this->Permiso_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line($this->controller_name . '_successful_deleted') . ' ' .
                count($to_delete) . ' ' . $this->lang->line($this->controller_name . '_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line($this->controller_name . '_cannot_be_deleted')));
        }
    }

    /**
     * Obtiene los permisos existentes en la base de datos. Responde vía llamadas dinámicas ajax, 
     * solicitadas por el datatable.
     * 
     * @return string Json con los datos de los permisos.
     */
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

    /**
     * Presenta el formulario para el ingreso de permisos.
     * @param type $id Permite actualizar o ingresar un nuevo permiso.
     */
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

    /**
     * Retorna los datos del permiso con el *id* correspondiente, para presentar la información 
     * en el DataTable.
     * @param int $id
     */
    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Permiso_model->get_info($id);
        echo get_permiso_data_row($info[0], $this);
    }

     /**
     * Ancho del dialogo del formulario del permiso.
     * @return int Dimensión del ancho del Formulario.
     */
    public function get_form_width() {
        return 400;
    }

    /**
     * Alto del formulario del permiso.
     * @return int Dimensión del alto del Formulario.
     */
    public function get_form_height() {
        return 500;
    }

}
