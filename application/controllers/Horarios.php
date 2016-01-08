<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

/**
 * Permite definir los Horarios que las empresas manejen.
 */
class Horarios extends Secure_area {

    public $controller_name;

    /**
     * Inicializa la clase Horarios.
     */
    public function __construct() {
        $this->controller_name = "horarios";
        parent::__construct($this->controller_name);
    }

    /**
     * Presenta la interfaz que permite manipular los horarios.
     */
    public function index() {
        $data['admin_table'] = get_horario_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('horarios/todos');
    }

    /**
     * Ingresa o Edita el horario seleccionado.
     * 
     * @param int $id Identificador del horario.
     */
    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $data['numero_horas'] = $this->input->post('numero_horas');
        $data['dias'] = json_encode($this->input->post('dias'));
//        $data['horas_extras'] = json_encode($this->input->post('horas_extras'));
        $data['minuto_gracia'] = $this->input->post('minuto_gracia');
        $idx_dia = 0;
        $cll_dia = array();
        do {
            $dia = $this->input->post('dia_especial_' . $idx_dia);
            if ($dia) {
                $cll_dia[$idx_dia]['nombre'] = $dia;
                $picada = $this->input->post('picada_especial_' . $idx_dia);
                if ($picada) {
                    //$cll_dia[$idx_dia][] = array();
                    $cll_dia[$idx_dia]['picadas'] = $picada;
                }
            }
            $idx_dia++;
        } while ($dia || $idx_dia < 11);

        $data['picadas'] = json_encode($cll_dia);
        //$data['es_rotativo'] = $this->input->post('es_rotativo');

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

    /**
     * Verifica si el nombre de horario ya está asignado.
     */
    function exist_name() {
        $data['id'] = $this->input->post('id');
        $data['nombre'] = $this->input->post('nombre');
        if ($this->Horario_model->exist_name($data))
            echo "false";
        else
            echo "true";
    }

    /**
     * Eliminado lógico del horario seleccionado.
     * 
     * @param int $id 
     */
    public function delete($id = null) {
        $to_delete = $this->input->post('ids');
        if ($this->Horario_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('horarios_successful_deleted') . ' ' . count($to_delete) . ' ' . $this->lang->line('horarios_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('horarios_cannot_be_deleted')));
        }
    }

    /**
     * Da los datos de los horarios al datatable.
     * 
     * La función es llamada dinámicamente por el datatable vía ajax.
     * 
     * @return string Json con los datos de los horarios en el formato requerido.
     */
    function mis_datos() {
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = 150;
        $aColumns = array('id', 'nombre', 'numero_horas', 'picadas', 'minuto_gracia');
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

    /**
     * Presenta el formulario para el ingreso de los horarios.
     * @param int $id El *id* del horario si conrresponde a un existente presenta el formulario con los 
     * respectivos datos, caso contrario, el formulario permite ingresar un nuevo horario.
     */
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
            $data['data']->picadas = $this->to_array(json_decode($data['data']->picadas));
        }
        $this->twiggy->set($data);
        $this->twiggy->display('horarios/insert');
    }

    /**
     * Convierte los datos de tipo object a Array.
     * @param type $datos
     * @return array
     */
    public function to_array($datos) {
        $cll_data = array();
        foreach ($datos as $dato)
            $cll_data[] = (array) $dato;
        return $cll_data;
    }

    /**
     * Retorna los datos del horario con el *id* correspondiente, para presentar la información 
     * en el DataTable.
     * @param int $id
     */
    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Horario_model->get_info($id);
        echo get_horario_data_row($info[0], $this);
    }

    /**
     * Visualiza los datos del archivo registro.txt.
     * @deprecated since version 1.0.0
     */
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

    /**
     * Ancho del dialogo del formulario del horario.
     * @return int Dimensión del ancho del Formulario.
     */
    public function get_form_width() {
        return 400;
    }

    /**
     * Alto del formulario del horario.
     * @return int Dimensión del alto del Formulario.
     */
    public function get_form_height() {
        return 500;
    }

}
