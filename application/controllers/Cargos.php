<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

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

    public function save($id = null) {
        $data['id'] = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $result = $this->Cargo_model->save($data, $data['id']);
        if ($result == true) {
            echo json_encode(array("result" => true));
        } else {
            echo json_encode(array("result" => false));
        }
    }

    public function listar() {
        $data2['datos'] = $this->Departamento_model->get_all();
        $this->parser->parse('departamento/verdep', $data2);
    }

    public function test() {
        $this->parser->parse('departamento/mensajedepar', array());
    }

    public function view($id = -1) {
        if ($id < 0) {
            $post_id = $this->input->post('cargo');
            $id = $post_id > -1 ? $post_id : -1;
        }
        $info = $this->Cargo_model->get_all(0, 100, array('id'=>$id));
        $data['info'] = count($info) == 0 ? null : $info[0];
        $this->twiggy->set($data);
        $this->twiggy->display('cargos/ingreso');
    }

}