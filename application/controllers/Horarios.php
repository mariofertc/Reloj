<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Horarios extends CI_Controller {
	protected $controller_name;
	public function __construct(){
		$this->controller_name = "horarios";
		parent::__construct();
	}

	public function index(){
		$data['admin_table']=get_horario_admin_table();
		$data['form_width'] = $this->get_form_width();
		$data['form_height'] = $this->get_form_height();
		$this->twiggy->set('controller_name', $this->controller_name);
		$this->twiggy->set($data, null);
		$this->twiggy->display('horarios/todos');
	}
	public function save($id=null){
		$id = $id==null?$this->input->post('id'):$id;
		$data['nombre'] = $this->input->post('nombre');
		$data['numero_horas'] = $this->input->post('numero_horas');
		$data['picadas'] = $this->input->post('picadas');
		$data['dias'] = $this->input->post('dias');
		$data['horas_extras'] = $this->input->post('horas_extras');

		try {
			if ($this->Empleado_model->save($data, $id)) {
				if ($id == null) {
					echo json_encode(array('success' => true, 'message' =>  $this->lang->line('empleados_successful_add') .
							$data['nombre'], 'id' => $data['id']));
				} else {
					echo json_encode(array('success' => true, 'message' =>  $this->lang->line('empleados_successful_update').
							$data['nombre'], 'id' => $id));
				}
			} else {
				echo json_encode(array('success' => false, 'message' => $this->lang->line('empleados_error_add_update') .
						$data['nombre'], 'id' => -1));
			}
		} catch (Exception $e) {
				echo json_encode(array('success' => false, 'message' => $e .
							$data['nombre'], 'id' => $id)); 
			$this->db->trans_off();
		}
	}

	public function delete($id=null){
		$to_delete = $this->input->post('ids');
		if ($this->Empleado_model->delete_list($to_delete)) {
			echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_deleted') . ' ' .
					count($to_delete) . ' ' . $this->lang->line('empleados_one_or_multiple')));
		} else {
			echo json_encode(array('success' => false, 'message' => $this->lang->line('empleados_cannot_be_deleted')));
		}
	}

	function mis_datos() {
		$data['controller_name'] = strtolower($this->uri->segment(1));
		$data['form_width'] = $this->get_form_width();
		$data['form_height'] = 150;
		$aColumns = array('id', 'nombre', 'numero_horas', 'picadas', 'días', 'horas_extras');
		//Eventos Tabla
		$cllAccion = array(
				'0' => array(
						'function' => "view",
						'common_language' => "common_edit",
						'language' => "_update",
						'width' => $this->get_form_width(),
						'height' => $this->get_form_height()));
		echo getData('Horario_model', $aColumns, $cllAccion,false,null,'mysql');
	}

	public function view($id = null){
		$data['title'] = "Reloj | Horarios";
		$data['titulo'] = "Horarios";
		if($id)
			$data['data'] = $this->Horario_model->get_info($id)[0];
		$this->twiggy->set($data);
		$this->twiggy->display('horarios/insert');
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