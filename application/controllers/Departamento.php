<?php
/**
 * Controlador de Departamento.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Secure_area
 * @subpackage Departamento
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

/**
 * Permite administrar los Departamentos de la Empresa.
 */
class Departamento extends Secure_area {
/**
     *Almacenta el nombre del controlador.
     * @var string 
     */
    public $controller_name;

    /**
     * Inicializa la clase de tipo Departamento.
     */
    public function __construct() {
        $this->controller_name = "departamento";
        parent::__construct($this->controller_name);
    }

    /**
     * Presenta al usuario la página de administración de los departamentos.
     */
    public function index() {
        $empresa = $this->Empresa_model->get_all();
        $data['empresa'] = count($empresa) == 0 ? null : $empresa[0];

        $departamento = $this->Departamento_model->get_all(0, 100, array('ideem' => $data['empresa']->ide));
        if (count($departamento) != 0) {
            $dep_array = array();
            $dep_array[] = "Seleccione un Departamento";
            foreach ($departamento as $dep) {
                $dep_array[$dep->iddep] = $dep->departamento;
            }
            $data['departamento'] = $dep_array;
        }

        $this->twiggy->set($data);
        $this->twiggy->display('departamento/asignar');
        //$this->parser->parse('departamento/ingreso',array('titulo'=>'Departamento'));
    }

    /**
     * Almacena o actualiza los departamentos en la base de datos.
     * 
     * @param int $iddep  Id del *departamento*. Si el *id* del departamento existe en la base de datos,
     * actualiza la información del departamento, de no existir, inserta un nuevo departamento.
     * 
     * @return json El resultado de la operación.
     */
    public function save($iddep = null) {
        $data['ideem'] = $this->input->post('ideem');
        $data['iddep'] = $iddep == null ? $this->input->post('iddep') : $iddep;
        $data['departamento'] = $this->input->post('departamento');
        $result = $this->Departamento_model->save($data, $data['iddep']);
        if ($result == true) {
            $operation = $iddep == null ? 'insert' : 'update';
            echo json_encode(array("result" => true, 'operation' => $operation, 'id' => $data['iddep'], 'nombre' => $data['departamento']));
            //echo "{reult:true}";
        } else {
            echo json_encode(array("result" => false));
        }
        //$this->parser->parse('departamento/mensajedepar', $data2);
    }

    /**
     * Elimina un departamento de acuerdo al $id del **departamento** enviado en el post.
     */
    public function deleted() {
        $id = $this->input->post('departamento');
        $data['deleted'] = 1;
        $result = $this->Departamento_model->save($data, $id);
        echo json_encode(array("result" => true, "id" => $id));
    }

    /**
     * Lista todos los departamentos existentes en el sistema.
     */
    public function listar() {
        $data2['datos'] = $this->Departamento_model->get_all();
        $this->parser->parse('departamento/verdep', $data2);
    }

    /**
     * Presenta el formulario del departamento.
     * 
     * @param int $id Presenta el formulario para ingresar o actualizar un departamento.
     */
    public function view($id = -1) {
        if ($id < 0) {
            $post_id = $this->input->post('departamento');
            $id = $post_id > -1 ? $post_id : -1;
        }
        $id_empresa = $this->input->post("empresa");
        $departamento = $this->Departamento_model->get_all(0, 100, array('iddep' => $id));
        $data['departamento'] = count($departamento) == 0 ? null : $departamento[0];
        $data['id_empresa'] = $id_empresa;
        $this->twiggy->set($data);
        $this->twiggy->display('departamento/ingreso');
    }

    /**
     * Comprueba si el nombre del departamento ya existe en la base de datos. **Id** del departamento enviado en POST.
     */
    function exist_name() {
        $data['id'] = $this->input->post('id');
        $data['nombre'] = $this->input->post('departamento');
        if ($this->Departamento_model->exist_name($data))
            echo "false";
        else
            echo "true";
    }

}
