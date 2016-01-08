<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");

/**
 * Permite manipular la información de la Empresa del Control de Picadas.
 */
class Empresa extends Secure_area {

    public $controller_name;

    /**
     * Inicializa la clase de la Empresa.
     */
    public function __construct() {
        $this->controller_name = "empresa";
        parent::__construct($this->controller_name);
    }
    
    /**
     * Permite presentar la información de la Empresa.
     */
    public function index() {
        $empresa = $this->Empresa_model->get_all();
        $data['empresa'] = count($empresa) == 0 ? null : $empresa[0];
        $data['titulo'] = "Empresa";
        $data['tipo'] = array("publica" => "Pública", "privada" => "Privada");

        $this->twiggy->set($data);
        $this->twiggy->display('empresa/guardare');
    }

    /**
     * Almacena la información de la Empresa.
     * @param int $id En caso que el *id* corresponda a una Empresa, se actualizará los datos de la misma,
     *  caso contrario se ingresa una nueva Empresa.
     */
    public function save($id) {
        //$data1['ide'] = $this->input->post('ide');
        $data['nombree'] = $this->input->post('nombree');
        $data['direccione'] = $this->input->post('direccione');
        $data['telefonoe'] = $this->input->post('telefonoe');
        $data['tipo'] = $this->input->post('tipo');
        $data['ruc'] = $this->input->post('ruc');
        if ($this->Empresa_model->save($data, $id) == false)
            $data1['error'] = " Solo se puede ingresar una Empresa";
//                return json_encode($data1);
        $this->twiggy->set($data);
        $this->twiggy->display('empresa/mensajeempresa');
    }

    /**
     * Lista las Empresas Existentes en la Base de Datos.
     */
    public function listar() {
        $data['datos'] = $this->Empresa_model->get_all();
        $this->twiggy->set($data);
        $this->twiggy->display('empresa/reporteemp');
    }

}
