<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seccion extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->parser->parse('seccion/ingresosec',array('titulo'=>'Seccion'));
	}
	public function save($id_seccion=null){
		$data3['iddep'] = $this->input->post('iddep');
		$data3['idsec'] = $id_seccion==null?$this->input->post('idsec'):$id_seccion;
		$data3['seccion'] = $this->input->post('seccion');
		$result = $this->Seccion_model->save($data3, $data3['idsec']);
		if($result == true){
			echo json_encode(array("result"=>true));
		}else{
			echo json_encode(array("result"=>false));
		}
	}
	public function listar(){
		$data3['datos'] = $this->Seccion_model->get_all();
		$this->parser->parse('seccion/versec', $data3);
	}	
	public function get_by_department($id_departamento = -1){
		$id_departamento = $this->input->post('departamento');
		 $result = $this->Seccion_model->get_all(0,100,array('iddep'=>$id_departamento));
		 $result_array[] = "Seleccione una Sección";
		foreach ($result as $r) {
			$result_array[$r->idsec] = $r->seccion;
		}
		$data['seccion'] = $result_array;
		echo json_encode($data);
	}
	public function view($id = -1){
		if($id < 0){
			$post_id = $this->input->post('seccion');
			$id = $post_id>-1?$post_id:-1;
		}
		$id_departamento = $this->input->post("departamento");
		$seccion = $this->Seccion_model->get_all(0,100,array('idsec'=>$id));
		$data['id_departamento'] = $id_departamento;
		$data['seccion'] = count($seccion)==0?null:$seccion[0];
		$this->twiggy->set($data);
		$this->twiggy->display('seccion/ingreso');
	}
}