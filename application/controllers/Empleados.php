<?php
/**
 * Controlador de Empleados.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Secure_area
 * @subpackage Empleados
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

/**
 * Permite administrar los empleados del sistema.
 */
class Empleados extends Secure_area {

    /**
     *Almacenta el nombre del controlador.
     * @var string 
     */
    public $controller_name;

    /**
     * Inicializa la clase de Empleados.
     */
    public function __construct() {
        $this->controller_name = "empleados";
        parent::__construct($this->controller_name);
    }

    /**
     * Visualiza todos los empleados existentes en el Sistema.
     */
    public function index() {
        $data['admin_table'] = get_empleado_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('empleados/todos');
    }

    /**
     * Almacena los datos de los empleados en la Base de Datos.
     * 
     * Permite además ingresar los datos, con los cuales el empleado podrá consultar sus picadas realizadas.
     * 
     * @param int $id Si el *id* del empleado existe en la base de datos, actualiza la información del empleado, 
     * caso contrario, ingresa un nuevo empleado a la base de datos.
     */
    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $data['apellido'] = $this->input->post('apellido');
        $data['edad'] = $this->input->post('edad');
        $data['cedula'] = $this->input->post('cedula');
        $data['estadocivil'] = $this->input->post('estadocivil');
        $data['direccion'] = $this->input->post('direccion');
        $data['fecha_ingreso'] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_ingreso')));
        $data['id_seccion'] = $this->input->post('id_seccion');
        $data['id_cargo'] = $this->input->post('id_cargo');
        $data['id_reloj'] = $this->input->post('id_reloj');

        if ($this->input->post('username')) {
            $data['username'] = $this->input->post('username');
        }
        if ($this->input->post('password')) {
            $data['password'] = sha1($this->input->post('password'));
        }
        try {
            if ($this->Empleado_model->save($data, $id)) {

                $idx_data = 0;
                $cll_horario = array();
                $id_empleado = (isset($data['id']) ? $data['id'] : $id);
                do {
                    $horario = $this->input->post('horario_' . $idx_data);
                    if ($horario) {
                        $cll_horario[$idx_data]['id_horario'] = $horario;
                        $fecha = $this->input->post('fecha_horario_' . $idx_data);
                        if ($fecha) {
                            $cll_horario[$idx_data]['fecha_creacion'] = date('Y-m-d H:i:s', strtotime($fecha));
                            $cll_horario[$idx_data]['id_empleado'] = $id_empleado;
                        }
                    }
                    $idx_data++;
                } while ($horario || $idx_data < 11);
                //foreach ($cll_horario as $horario) {
                $this->Empleado_horario_model->Save($id_empleado, $cll_horario);
                //}
                if ($id == null) {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_add') .
                        $data['nombre'], 'id' => $data['id']));
                } else {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_update') .
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

    /**
     * Elimina el empleado con el **id** correspondiente.
     * 
     * @param int $id Realiza un borrado lógico del empleado en la base de datos.
     */
    public function delete($id = null) {
        $to_delete = $this->input->post('ids');
        if ($this->Empleado_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('empleados_successful_deleted') . ' ' .
                count($to_delete) . ' ' . $this->lang->line('empleados_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('empleados_cannot_be_deleted')));
        }
    }

    /**
     * Obtiene los empleados dinámicamente.
     * 
     * Utilizada para devolver datos dinámicamente al *datatable*, via peticiones ajax.
     * 
     * @return json Con el formato compatible para el datatable.
     */
    function mis_datos() {
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = 150;
        $aColumns = array('id', 'nombre', 'apellido', 'edad', 'cedula', 'direccion', 'fecha_ingreso');
        //Eventos Tabla
        $cllAccion = array(
            '1' => array(
                'function' => "view",
                'common_language' => "common_edit",
                'language' => "_update",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()),
            '0' => array(
                'function' => "vacaciones",
                'common_language' => "empleados_vacations",
                'language' => "_more_vacations",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()));
        header("Access-Control-Allow-Origin: *");
        echo getData('Empleado_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    /**
     * Presenta un formulario para el ingreso de información del empleado.
     * 
     * @param int $id Si el *id* del empleado existe, presenta los datos del empleado almacenado, caso 
     * contrario permite ingresar un nuevo empleado.
     */
    public function view($id = null) {
        $data['title'] = "Reloj | Empleados";
        $data['titulo'] = "Empleados";
        $horarios = $this->Horario_model->get_all();
        $cll_horario = array();
        foreach ($horarios as $horario) {
            $cll_horario[$horario['id']] = $horario['nombre'];
        }
        $cargo = $this->Cargo_model->get_all();
        $data['cargos'] = array_to_htmlcombo($cargo, array('blank_text' => 'Seleccione un Cargo', 'id' => 'id', 'name' => array('nombre')));
        $departamentos = $this->Departamento_model->get_all(0, 100);
        $cll_seccion = array();
        foreach ($departamentos as $departamento) {
            $secciones = $this->Seccion_model->get_all(0, 100, array('iddep' => $departamento->iddep));
            $cll_seccion_temp = array();
            foreach ($secciones as $seccion) {
                $cll_seccion_temp[$seccion->idsec] = $seccion->seccion;
            }
            $cll_seccion[$departamento->departamento] = $cll_seccion_temp;
        }
        $data['secciones'] = $cll_seccion;
        $data['horarios'] = $cll_horario;
        if ($id) {
            $info = $this->Empleado_model->get_info($id);
            $info = $info[0];
            $info->fecha_ingreso = date('m/d/Y h:i A', strtotime($info->fecha_ingreso));
            $data['data'] = $info;

            //$data['data']['fecha_ingreso'] = date('Y-m-d H:i:s', strtotime($data['data']['fecha_ingreso']));

            $horario = $this->Empleado_horario_model->get_all(0, 100, array('id_empleado' => $id));
            foreach ($horario as &$value) {
                $value['fecha_creacion'] = date('m/d/Y h:i A', strtotime($value['fecha_creacion']));
            }
            $data['horario'] = $horario;
        }
        $result = $this->Module_model->get_all_modules()->result();
        foreach ($result as &$module) {
            $module->permiso = $this->Empleado_model->has_permission($module->module_id, $id);
        }
        $data['all_modules'] = $result;
        $this->twiggy->set($data);
        $this->twiggy->display('empleados/insert');
    }

    /**
     * Busca a los empleados almacenados en el sistema.
     */
    public function buscar_vista() {
        $data['id'] = $this->input->post('q');
        $data['datos'] = $this->Empleado_model->get_info($data['id']);
        $emp = $data['datos'];
        if (count($emp) > 0) {
            $edad = $data['datos'][0]->edad;
            $id = $data['datos'][0]->id;
            $resultado = $edad + $id * $edad;
            $data['resultado'] = $resultado;
            $data['datos'][0]->se_casa = $data['datos'][0]->edad * $data['datos'][0]->edad;
        }
        $this->twiggy->set($data);
        $this->twiggy->display('empleados/buscar');
    }

    /**
     * Reporte de los empleados almacenados en el Sistema.
     */
    function reporte() {
        $empresas = $this->Empresa_model->get_all(0, 100);
        $departamentos = $this->Departamento_model->get_all(0, 100);
        $secciones = $this->Seccion_model->get_all(0, 100);
        $empleados = $this->Empleado_model->get_all(0, 100);
        $data['empresas'] = array_to_htmlcombo($empresas, array('blank_text' => 'Seleccione una Empresa', 'id' => 'ide', 'name' => array('nombree')));
        $data['departamentos'] = array_to_htmlcombo($departamentos, array('blank_text' => 'Seleccione un Departamento', 'id' => 'iddep', 'name' => array('departamento')));
        $data['secciones'] = array_to_htmlcombo($secciones, array('blank_text' => 'Seleccione una Seccion', 'id' => 'idsec', 'name' => array('seccion')));
        $data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => 'Seleccione un Empleado', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));

        $this->twiggy->set($data);
        $this->twiggy->display('reportes/empleados');
    }

    /**
     * Retorna los datos de todos los empleados.
     * 
     * Agrupados por:
     * * Empleado
     * * Sección
     * * Departamento
     * * Empresa
     * 
     * Permite al datatable realizar las consultas dinámicamente de los empleados.
     * 
     * @return string De tipo json con el formato aceptado por el *datatable*.
     */
    function consulta_empleados() {
        $id_empleado = $this->input->post('id_empleado');
        $id_seccion = $this->input->post('id_seccion');
        $id_departamento = $this->input->post('id_departamento');
        $id_empresa = $this->input->post('id_empresa');
        if ($id_empresa != 0) {
            $empresa = $this->Empresa_model->get_info($id_empresa);
            $cll_departamento = $this->Departamento_model->get_all(0, 300, array('ideem' => $id_empresa));
            $cll_empleados = array();
            foreach ($cll_departamento as $departamento) {
                $cll_seccion = $this->Seccion_model->get_all(0, 300, array('iddep' => $departamento->iddep));
                foreach ($cll_seccion as $seccion) {
                    $empleados = $this->Empleado_model->get_all(0, 300, array('id_seccion' => $seccion->idsec), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
                    $empleados_temp = array();
                    foreach ($empleados as $empleado) {
                        $empleados_temp = array_values($empleado);
                        $empleados_temp[] = $seccion->seccion;
                        $empleados_temp[] = $departamento->departamento;
                        $cll_empleados[] = $empleados_temp;
                    }
                }
            }
            echo json_encode(array('response' => true, "message" => "empresa", "empleados_by_empresa" => $cll_empleados, 'empresa' => $empresa[0]));
            return;
        }
        if ($id_departamento != 0) {
            $departamento = $this->Departamento_model->get_info($id_departamento);
            $cll_seccion = $this->Seccion_model->get_all(0, 300, array('iddep' => $departamento[0]->iddep));
            $cll_empleados = array();
            foreach ($cll_seccion as $seccion) {
                $empleados = $this->Empleado_model->get_all(0, 300, array('id_seccion' => $seccion->idsec), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
                $empleados_temp = array();
                foreach ($empleados as $empleado) {
                    $empleados_temp = array_values($empleado);
                    $empleados_temp[] = $seccion->seccion;
                    $cll_empleados[] = $empleados_temp;
                }
            }
            echo json_encode(array('response' => true, "message" => "departamento", "empleados_by_departamento" => $cll_empleados, 'departamento' => $departamento[0]));
            return;
        }

        if ($id_seccion != 0) {
            $seccion = $this->Seccion_model->get_info($id_seccion);
            $empleados = $this->Empleado_model->get_all(0, 300, array('id_seccion' => $id_seccion), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
            $cll_empleados = array();
            $empleados_temp = array();
            foreach ($empleados as $empleado) {
                $empleados_temp = array_values($empleado);
                //$empleados_temp[] = $seccion->seccion;
                $cll_empleados[] = $empleados_temp;
            }
            //$cll_empleados_picadas = $this->coje_picadas($empleados, $fecha_desde, $fecha_hasta, $tipo);
            echo json_encode(array('response' => true, "message" => "seccion", "empleados_by_seccion" => $cll_empleados, 'seccion' => $seccion[0]));
            return;
        }

        $empleados = $this->Empleado_model->get_all(0, 300, array('id' => $id_empleado), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
        $empleados_temp = array();
        foreach ($empleados as $empleado) {
            $empleados_temp[] = array_values((array) $empleado);
            //$empleado = array_values($info[0]);
        }
        //$horario = $this->Horario_model->get_all(100, 0, array('id' => $empleado->id_horario));
        echo json_encode(array('response' => true, "message" => "empleado", "empleado" => $empleados_temp));
    }

    /**
     * Información de la fila correspondiente al datatable que va a ser editado o insertado.
     * 
     * @param int $id Del empleado correspondiente.
     */
    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Empleado_model->get_info($id);
        echo get_empleado_data_row($info[0], $this);
    }

    /**
     * Ancho del dialogo del formulario del empleado.
     * @return int Dimensión del ancho del Formulario.
     */
    public function get_form_width() {
        return 400;
    }

    /**
     * Alto del formulario del empleado.
     * @return int Dimensión del alto del Formulario.
     */
    public function get_form_height() {
        return 500;
    }

}
