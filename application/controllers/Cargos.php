<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';
/**
 * Permite administrar los cargos de los empleados.
 */
class Cargos extends Secure_area {

    public $controller_name;

    /**
     * Inicio del Controlador.
     */
    public function __construct() {
        $this->controller_name = "cargos";
        parent::__construct($this->controller_name);
    }

    /**
     * Página principal de los Cargos.
     */
    public function index() {
        $cargo = $this->Cargo_model->get_all(0, 100);
        $data = array();
        if (count($cargo) != 0) {
            $cargo_array = array();
            foreach ($cargo as $carg) {
                $cargo_array[$carg->id] = $carg->nombre;
            }
            $data['cargo'] = $cargo_array;
        }
        $this->twiggy->set($data);
        $this->twiggy->display('cargos/asignar');
    }

    /**
     * Permite insertar y actualizar los cargos.
     * 
     * @param int $id Id del *cargo*. Si es un cargo existente lo actualiza, si no existe el id, 
     * inserta un nuevo cargo.
     */
    public function save($id = null) {
        $data['id'] = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $result = $this->Cargo_model->save($data, $data['id']);
        if ($result == true) {
            $operation = $id == null ? 'insert' : 'update';
            echo json_encode(array("result" => true, 'operation' => $operation, 'id' => $data['id'], 'nombre' => $data['nombre']));
        } else {
            echo json_encode(array("result" => false));
        }
    }

    /**
     * Lista los Departamentos almacenados.
     */
    public function listar() {
        $data2['datos'] = $this->Departamento_model->get_all();
        $this->parser->parse('departamento/verdep', $data2);
    }

    /**
     * Permite editar e insertar nuevos cargos en la base de datos.
     * 
     * El formulario es presentado al usuario en una ventana de tipo modal.
     * 
     * @param int $id
     */
    public function view($id = -1) {
        if ($id < 0) {
            $post_id = $this->input->post('cargo');
            $id = $post_id > -1 ? $post_id : -1;
        }
        $info = $this->Cargo_model->get_all(0, 100, array('id' => $id));
        $data['info'] = count($info) == 0 ? null : $info[0];
        $this->twiggy->set($data);
        $this->twiggy->display('cargos/ingreso');
    }

}
