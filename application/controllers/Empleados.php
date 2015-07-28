<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empleados extends CI_Controller {
	protected $controller_name;
	public function __construct(){
		$this->controller_name = "empleados";
		parent::__construct();
	}

	public function index(){
		/*$data['datos'] = $this->Empleado_model->get_all();
		$this->twiggy->set($data);
		$this->twiggy->display('empleados/todos');*/

		$data['admin_table']=get_empleado_admin_table();		
		$this->twiggy->set('controller_name', $this->controller_name);
		$this->twiggy->set($data, null);
		$this->twiggy->display('empleados/todos');
	}
	public function save($id=null){
		$id = $id==null?$this->input->post('id'):$id;
		$data['nombre'] = $this->input->post('nombre');
		$data['apellido'] = $this->input->post('apellido');
		$data['edad'] = $this->input->post('edad');
		$data['cedula'] = $this->input->post('cedula');
		$data['estadocivil'] = $this->input->post('estadocivil');
		$data['direccion'] = $this->input->post('direccion');
		$data['fecha'] =  date('Y-m-d H:i:s', strtotime($this->input->post('fecha')));
		$data['fecha_ingreso'] = date('Y-m-d H:i:s',now());
		// $data['id_horario'] = $this->input->post('id_horario');
		$data['id_reloj'] = $this->input->post('id_reloj');
		$this->Empleado_model->save($data, $id);
		$this->twiggy->display('empleados/todos');
	}

	function mis_datos() {
		$data['controller_name'] = strtolower($this->uri->segment(1));
		$data['form_width'] = $this->get_form_width();
		$data['form_height'] = 150;
		$aColumns = array('id', 'nombre', 'apellido', 'edad', 'cedula', 'direcion', 'fecha_ingreso');
		//Eventos Tabla
		$cllAccion = array(
				'1' => array(
						'function' => "view",
						'common_language' => "common_edit",
						'language' => "_update",
						'width' => $this->get_form_width(),
						'height' => $this->get_form_height()));
		echo getData('Empleado_model', $aColumns, $cllAccion,false,null,'mysql');
	}


	public function view(){
		$data['title'] = "Reloj | Empleados";
		$data['titulo'] = "Empleados";
		$this->twiggy->set($data);
		$this->twiggy->display('empleados/insert');
	}
	
	public function buscar_vista(){
		$data['id'] = $this->input->post('q');
		$data['datos'] = $this->Empleado_model->get_info($data['id']);
		//foreach($data as $empleado){
			//
		//	var_dump($empleado);
		//}
		$emp = $data['datos'];
		if(count($emp)>0){
			$edad = $data['datos'][0]->edad;
			$id = $data['datos'][0]->id;
			$resultado = $edad + $id * $edad;
			$data['resultado'] = $resultado;
			$data['datos'][0]->se_casa = $data['datos'][0]->edad * $data['datos'][0]->edad;
		}
		$this->twiggy->set($data);
		$this->twiggy->display('empleados/buscar');
	}

	public function importar_registro(){
		//var_dump($this->Registro_model->leer_datos('uploads/registro.txt'));
		$row = 0;
		if (($handle = fopen('uploads/registro.txt', "r")) !== FALSE) {
    		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        $num = count($data);
		        echo "<p> $num fields in line $row: <br /></p>\n";
		        $row++;
		        for ($c=0; $c < $num; $c++) {
	            	echo $data[$c] . "<br />\n";
	        	}
       		}
    		fclose($handle);
		}
	}
	
	public function get_form_width(){
		return 400;
	}

	public function get_form_height(){
		return 500;
	}
}