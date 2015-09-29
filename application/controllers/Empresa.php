<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$empresa = $this->Empresa_model->get_all();
		$data['empresa'] = count($empresa)==0?null:$empresa[0];
		$data['titulo'] = "Empresa";
		$data['tipo'] = array("publica"=>"Pública", "privada"=>"Privada");

		$this->twiggy->set($data);
		$this->twiggy->display('empresa/guardare');
	}
	public function save($id){
		//$data1['ide'] = $this->input->post('ide');
		$data['nombree'] = $this->input->post('nombree');
		$data['direccione'] = $this->input->post('direccione');
		$data['telefonoe'] = $this->input->post('telefonoe');
		$data['tipo'] = $this->input->post('tipo');
		$data['ruc'] = $this->input->post('ruc');
		if($this->Empresa_model->save($data,$id)==false)
			$data1['error'] = " Solo se puede ingresar una Empresa";
//                return json_encode($data1);
		$this->twiggy->set($data);
		$this->twiggy->display('empresa/mensajeempresa');
	}
	public function listar(){
		$data['datos'] = $this->Empresa_model->get_all();
		$this->twiggy->set($data);
		$this->twiggy->display('empresa/reporteemp');
	}
	
}