<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador que permite manipular las Secciones de cada departamento del sistema.
 */
class Seccion extends CI_Controller {

    /**
     * Inicializa la clase de tipo Sección.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Presenta la interfaz de administración de las secciones.
     */
    public function index() {
        $this->parser->parse('seccion/ingresosec', array('titulo' => 'Seccion'));
    }

    /**
     * Almacena los datos de la sección.
     * 
     * @param int $id_seccion Si el *id* de la sección ya existe actualiza la sección, de lo contrario inserta una nueva sección.
     */
    public function save($id_seccion = null) {
        $data['iddep'] = $this->input->post('iddep');
        $data['idsec'] = $id_seccion == null ? $this->input->post('idsec') : $id_seccion;
        $data['seccion'] = $this->input->post('seccion');
        $result = $this->Seccion_model->save($data, $data['idsec']);
        if ($result == true) {
            $operation = $id_seccion == null ? 'insert' : 'update';
            echo json_encode(array("result" => true, 'operation' => $operation, 'id' => $data['idsec'], 'nombre' => $data['seccion']));
        } else {
            echo json_encode(array("result" => false));
        }
    }

    /**
     * Elimina una sección dada.
     */
    public function deleted() {
        $id = $this->input->post('seccion');
        $data['deleted'] = 1;
        $result = $this->Seccion_model->save($data, $id);
        echo json_encode(array("result" => true, "id" => $id));
    }

    /**
     * Lista las secciones existentes en el sistema.
     */
    public function listar() {
        $data3['datos'] = $this->Seccion_model->get_all();
        $this->parser->parse('seccion/versec', $data3);
    }

    /**
     * Obtiene las secciones por departamento.
     * 
     * @param int $id_departamento Identificador del departamento
     * 
     * @return string Json con las secciones del departamento.
     */
    public function get_by_department($id_departamento = -1) {
        $id_departamento = $this->input->post('departamento');
        $result = $this->Seccion_model->get_all(0, 100, array('iddep' => $id_departamento));
        $result_array[] = "Seleccione una Sección";
        foreach ($result as $r) {
            $result_array[$r->idsec] = $r->seccion;
        }
        $data['seccion'] = $result_array;
        echo json_encode($data);
    }

    /**
     * Formulario que permite el ingreso y modificación de una sección.
     * 
     * @param type $id En caso que el identificador coincida con una sección presenta el formulario 
     * con los datos de la sección, caso contrario el formulario permite ingresar una sección nueva.
     */
    public function view($id = -1) {
        if ($id < 0) {
            $post_id = $this->input->post('seccion');
            $id = $post_id > -1 ? $post_id : -1;
        }
        $id_departamento = $this->input->post("departamento");
        $seccion = $this->Seccion_model->get_all(0, 100, array('idsec' => $id));
        $data['id_departamento'] = $id_departamento;
        $data['seccion'] = count($seccion) == 0 ? null : $seccion[0];
        $this->twiggy->set($data);
        $this->twiggy->display('seccion/ingreso');
    }

}
