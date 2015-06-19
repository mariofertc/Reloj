<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Horario extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->parser->parse('horario/ingresohor',array('titulo'=>'horario'));
	}
	public function save($codigo=null){
		$data4['codigo'] = $codigo==null?$this->input->post('codigo'):$codigo;
		$data4['picada'] = $this->input->post('picada');
		$data4['tipo'] = $this->input->post('tipo');
		$data4['hentrada'] = $this->input->post('hentrada');
		$data4['hsalida'] = $this->input->post('hsalida');
		$data4['hent'] = $this->input->post('hent');
		$data4['hsal'] = $this->input->post('hsal');
		$data4['halm'] = $this->input->post('halm');
		$data4['hentalm'] = $this->input->post('hentalm');
		$this->Horario_model->save($data4, $data4['codigo']);
		$this->parser->parse('horario/mensajehor', $data4);
	}
	
	public function listar(){
		$data4['datos'] = $this->Horario_model->get_all();
		$this->parser->parse('horario/verhor', $data4);
	}
	public function test(){
		$this->parser->parse('horario/mensajehor',array());
	}
}