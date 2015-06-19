<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->parser->parse('empresa/guardare',array('titulo'=>'Empresa'));
	}
	public function save($id){
		$data1['ide'] = $this->input->post('ide');
		$data1['nombree'] = $this->input->post('nombree');
		$data1['direccione'] = $this->input->post('direccione');
		$data1['telefonoe'] = $this->input->post('telefonoe');
		$data1['tipo'] = $this->input->post('tipo');
		$data1['ruc'] = $this->input->post('ruc');
		if($this->Empresa_model->save($data1,$id)==false)
			$data1['error'] = " Solo se puede ingresar una Empresa";
		$this->parser->parse('empresa/mensajeempresa', $data1);
	}
	public function listar(){
		$data1['datos'] = $this->Empresa_model->get_all();
		$this->parser->parse('empresa/reporteemp', $data1);
	}
	
}