<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$empresa = $this->Empresa_model->get_all();
		$data['empresa'] = count($empresa)==0?null:$empresa[0];
		
		$departamento = $this->Departamento_model->get_all(array('ideem'=>$data['empresa']->ide));
		if(count($departamento)!=0){
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
	public function save($iddep=null){
		$data2['ideem'] = $this->input->post('ideem');
		$data2['iddep'] = $iddep==null?$this->input->post('iddep'):$iddep;
		$data2['departamento'] = $this->input->post('departamento');
		$result = $this->Departamento_model->save($data2, $data2['iddep']);
		if($result == true){
			echo json_encode(array("result"=>true));
			//echo "{reult:true}";
		}else{
			echo json_encode(array("result"=>false));
		}
		//$this->parser->parse('departamento/mensajedepar', $data2);
	}
	public function listar(){
		$data2['datos'] = $this->Departamento_model->get_all();
		$this->parser->parse('departamento/verdep', $data2);
	}	
	public function test(){
		$this->parser->parse('departamento/mensajedepar',array());
	}
	public function view($id = -1){
		if($id < 0){
			$post_id = $this->input->post('departamento');
			$id = $post_id>-1?$post_id:-1;
		}
		$id_empresa = $this->input->post("empresa");
		$departamento = $this->Departamento_model->get_all(array('iddep'=>$id));
		$data['departamento'] = count($departamento)==0?null:$departamento[0];
		$data['id_empresa'] = $id_empresa;
		$this->twiggy->set($data);
		$this->twiggy->display('departamento/ingreso');
	}
}