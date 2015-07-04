<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data['departamento'] = $this->Departamento_model->get_all();
		$this->twiggy->set($data);
		$this->twiggy->display('departamento/asignar');
		//$this->parser->parse('departamento/ingreso',array('titulo'=>'Departamento'));
	}
	public function save($ideem=null){
		$data2['ideem'] = $ideem==null?$this->input->post('ideem'):$ideem;
		$data2['iddep'] = $this->input->post('iddep');
		$data2['departamento'] = $this->input->post('departamento');
		$this->Departamento_model->save($data2, $data2['ideem']);
		$this->parser->parse('departamento/mensajedepar', $data2);
	}
	public function listar(){
		$data2['datos'] = $this->Departamento_model->get_all();
		$this->parser->parse('departamento/verdep', $data2);
	}	
	public function test(){
		$this->parser->parse('departamento/mensajedepar',array());
	}
	public function view($id = -1){
		$data['departamento'] = $this->Departamento_model->get_all(array('iddep'=>$id));
		$this->twiggy->set->($data);
	}
}