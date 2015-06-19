<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empleados extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data['title'] = "Reloj | Empleados";
		$data['titulo'] = "Empleados";
		$this->twiggy->set($data);
		$this->twiggy->display('empleados/insert');
	}
	public function save($id=null){
		$data['id'] = $id==null?$this->input->post('id'):$id;
		$data['nombre'] = $this->input->post('nombre');
		$data['Apellido'] = $this->input->post('Apellido');
		$data['edad'] = $this->input->post('edad');
		$data['cedula'] = $this->input->post('cedula');
		$data['estadocivil'] = $this->input->post('estadocivil');
		$data['direccion'] = $this->input->post('direccion');
		$data['fecha'] =  date('Y-m-d H:i:s', strtotime($this->input->post('fecha')));
		$data['fecha_ingreso'] = date('Y-m-d H:i:s',now());
		$this->Empleado_model->save($data, $data['id']);
		$this->parser->parse('empleados/mensaje', $data);
	}
	public function listar(){
		$data['datos'] = $this->Empleado_model->get_all();
		$this->twiggy->set($data);
		$this->twiggy->display('empleados/todos');
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
	
	public function test(){
		$this->parser->parse('empleados/mensaje',array());
	}
}