<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seccion extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->parser->parse('seccion/ingresosec',array('titulo'=>'Seccion'));
	}
	public function save($iddep1=null){
		$data3['iddep1'] = $iddep1==null?$this->input->post('iddep1'):$iddep1;
		$data3['idsec'] = $this->input->post('idsec');
		$data3['seccion'] = $this->input->post('seccion');
		$this->Seccion_model->save($data3, $data3['iddep1']);
		$this->parser->parse('seccion/mensajesec', $data3);
	}
	public function listar(){
		$data3['datos'] = $this->Seccion_model->get_all();
		$this->parser->parse('seccion/versec', $data3);
	}	
	public function test(){
		$this->parser->parse('seccion/mensajesec',array());
	}
}