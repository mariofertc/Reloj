<?php
/**
 * Controlador de Picadas.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Secure_area
 * @subpackage Picadas
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';


/**
 * Controla las picadas de los diferentes empleados.
 */
class Picadas extends Secure_area {

    /**
     *Almacenta el nombre del controlador.
     * @var string 
     */
    public $controller_name;

    /**
     * Inicializa la clase de tipo Picadas.
     */
    public function __construct() {
        $this->controller_name = "picadas";
        parent::__construct($this->controller_name);
    }

    /**
     * Presenta la pantalla principal para la carga de picadas, reporte principal de picadas 
     * e ingreso de permisos al sistema.
     */
    public function index() {
        $empleados = $this->Empleado_model->get_all(0, 100);
        $data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => 'Seleccione un Empleado', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();

        $this->twiggy->set($data);
        $this->twiggy->display('picadas/registros');
    }

    /**
     * Reporte personal de los empleados.
     * 
     * Ha este reporte acceden los empleados que tienen el acceso habilitado por el administrador del sistema.
     */
    public function personal($id) {
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
        $this->twiggy->display('picadas/horas_trabajadas');
    }

    /**
     * Genera el reporte de horas extras por empleado, sección, departamento y empresa.
     */
    public function horas_extras() {
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
        $this->twiggy->display('picadas/horas_extras');
    }

    /**
     * Genera el reporte de horas trabajadas por empleado, sección, departamento y empresa.
     */
    public function horas_trabajadas() {
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
        $this->twiggy->display('picadas/horas_trabajadas');
    }

    /**
     * Genera el reporte de atrasos por empleado, sección, departamento y empresa.
     */
    public function horas_atrasos() {
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
        $this->twiggy->display('picadas/horas_atrasos');
    }

    /**
     * Genera el reporte de Permisos solicitados por los empleados, mismo que se desglosa por empleado, sección, departamento y empresa.
     */
    public function horas_permisos() {
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
        $this->twiggy->display('picadas/horas_permisos');
    }

    /**
     * Permite ingresar los permisos correspondientes al empleado, de acuerdo al día faltado.
     * 
     * @param type $codigo_reloj
     * @param type $dia
     * @param type $mes
     * @param type $ano
     */
    public function permiso($codigo_reloj = null, $dia = null, $mes = null, $ano = null) {
        $fecha = $ano . "-" . $mes . "-" . $dia;

        $picadas = $this->input->get('picadas');
        $picadas = explode(',', trim($picadas, ','));

        //$picadas = $this->Picada_model->get_all(0, 100, array('date(fecha_picada)' => $fecha, 'codigo' => $codigo_reloj));

        $empleado = $this->Empleado_model->get_all(0, 100, array('id_reloj' => $codigo_reloj));
        $empleado = $empleado[0];

        $horario = $this->Horario_model->get_horario_empleado($empleado['id']);
        $data['data'] = $empleado;

        $horario = json_decode($horario[0]['picadas']);

        $timestamp = strtotime($fecha);

        //$day = date('D', $timestamp);
        $day = get_dia_nombre($timestamp);
        $picadas_horario = null;

        foreach ($horario as $dia) {
            if ($dia->nombre == strtolower($day)) {
                $picadas_horario = $dia->picadas;
            }
        }

        $permiso = $this->Permiso_model->get_all();
        $permiso = array_to_htmlcombo($permiso, array('blank_text' => 'Seleccione un Permiso', 'id' => 'id', 'name' => array('nombre')));

        $data['permisos'] = $this->Permiso_picada_model->get_permisos(array('fecha' => $fecha, 'codigo' => $codigo_reloj));
        $data['permiso'] = $permiso;
        $data['fecha'] = $fecha;
        $data['title'] = "Reloj | Empleados";
        $data['titulo'] = "Permisos";
        //$horarios = $this->Horario_model->get_all();
        $data['picadas_horario'] = $picadas_horario;
        $data['picadas'] = $picadas;
        $this->twiggy->set($data);
        $this->twiggy->display('picadas/permiso');
    }

    /**
     * Almacena el permiso del empleado en la base de datos.
     * 
     * @param type $id_reloj
     */
    public function save_permiso($id_reloj = null) {
        $fecha = $this->input->post('fecha');
        $idx = 1;
        $cll_permiso = array();
        do {
            $picada = $this->input->post('picada_' . $idx);
            $picada_new = $this->input->post('picada_new_' . $idx);
            $tipo_permiso = $this->input->post('horario_' . $idx);
            if (!empty($picada_new) && !empty($tipo_permiso)) {
                $cll_permiso[$idx]['picada'] = date('Y-m-d H:i:s', strtotime($fecha . ' ' . $picada));
                $cll_permiso[$idx]['nueva_picada'] = date('Y-m-d H:i:s', strtotime($fecha . ' ' . $picada_new));
                $cll_permiso[$idx]['tipo_permiso'] = $tipo_permiso;
                $cll_permiso[$idx]['codigo'] = $id_reloj;
                $cll_permiso[$idx]['posicion'] = $idx - 1;
            }
            $idx++;
        } while ($idx < 11);

        try {
            if ($this->Permiso_picada_model->save($cll_permiso, array('fecha' => $fecha, 'codigo' => $id_reloj))) {
                echo json_encode(array('success' => true, 'message' => $this->lang->line($this->controller_name . '_permiso_successful_add') .
                    $fecha, 'id' => 1));
            } else {
                echo json_encode(array('success' => false, 'message' => $this->lang->line($this->controller_name . '_permiso_error_add_update') .
                    $fecha, 'id' => -1));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e .
                $fecha, 'id' => $id_reloj));
            $this->db->trans_off();
        }
    }
    
    var $datos_upload;

    /**
     * Almacena los datos de 3 tipos de relojes biométricos. Los datos son guardados en la base de datos.
     * Estos datos son procesados y se garantiza que no haya duplicidad de los mismos.
     * @param type $id Identificador del reloj.
     * @return type
     */
    function load_data_reloj($id = -1) {
        $this->session->set_userdata('upload_status', 'pending');
        $this->session->set_userdata('upload_progress', 0);

        $upLoad = do_upload('reloj', '*');
        if ($upLoad['error'] != "0") {
            echo json_encode(array('success' => false, 'message' => $upLoad['error'], 'id' => $id));
            return;
        }
        $path_file = $upLoad['upload_data']['full_path'];
        $success = true;
        $config = array();
        $tipo = view_type($path_file);
        if ($tipo == NULL) {
            echo json_encode(array('success' => false, 'message' => 'Formato no soportado por el Sistema!', 'id' => null));
            return;
        }
        switch ($tipo) {
            case "dat":
                $config['separador'] = chr(9);
                $config['data'] = array(
                    'codigo' => array('indice' => array(0)),
                    'fecha_picada' => array('indice' => array(1), 'format' => 'Y-m-d H:i:s '));
                break;
            case "txt":
                $config['salta_encabezado'] = true;
                $config['data'] = array(
                    'codigo' => array('indice' => array(0)),
                    'fecha_picada' => array('indice' => array(1, 2), 'format' => 'd m Y H i '));
                break;
            case "log":
                $config['data'] = array(
                    'codigo' => array('indice' => array(3)),
                    'fecha_picada' => array('indice' => array(5, 6, 7, 8, 9), 'format' => 'G i n j y '));
                break;
        }
        $datos = read_data($path_file, array('fecha_creacion' => date('Y-m-d H:i:s')), $config);
        $this->datos_upload = $datos;
        //echo json_encode(array('total_data'=>count($datos),'estado'=>'pending'));
        $idx_subido = 0;
        $tot_datos = count($datos);
        foreach ($datos as $key => $value) {
            if ($this->Picada_model->exist($value)) {
                unset($datos[$key]);
            }
            $idx_subido++;
            if ($idx_subido % 100 == 0) {
                $this->session->set_userdata('upload_status', 'pending');
                $this->session->set_userdata('upload_progress', 0);
                $this->session->set_userdata('upload_total', count($datos));
            }
        }
        if (count($datos) > 0)
            $success = $this->Picada_model->save_batch($datos);

        $this->session->set_userdata('upload_status', 'done');
        echo json_encode(array('success' => $success, 'message' => 'Datos subidos satisfactoriamente! ' . count($datos) . " de " . $tot_datos, 'estado' => 'done'));
    }

    /**
     * Obtiene el estado de la carga de archivos de los relojes biométricos.
     */
    public function status_upload() {
        $id = $this->input->post('id');
        $status = "pending";
        if ((int) $this->session->upload_progress >= 100 || $this->session->upload_status == "done")
            $status = "done";

        echo json_encode(array("estado" => $status, 'progress' => $this->session->upload_progress));
    }

    /**
     * Función principal del Sistema, que permite realizar el análisis de los datos recolectados 
     * y compararlos con los horarios y permisos asignados a los empleados.
     * 
     * @return string Json con los datos de los empleados y sus respectivas picadas. 
     */
    public function consulta_picadas() {
        $id_empleado = $this->input->post('id_empleado');
        $id_seccion = $this->input->post('id_seccion');
        $id_departamento = $this->input->post('id_departamento');
        $id_empresa = $this->input->post('id_empresa');
        $fecha_desde = $this->input->post('from');
        $fecha_hasta = $this->input->post('to');
        $tipo = $this->input->post('tipo');
        if ($id_empresa != 0) {
            $empresa = $this->Empresa_model->get_info($id_empresa);
            $cll_departamento = $this->Departamento_model->get_all(0, 300, array('ideem' => $id_empresa));
            $cll_picadas = array();
            foreach ($cll_departamento as $departamento) {
                $cll_seccion = $this->Seccion_model->get_all(0, 300, array('iddep' => $departamento->iddep));
                foreach ($cll_seccion as $seccion) {
                    $empleados = $this->Empleado_model->get_all(0, 300, array('id_seccion' => $seccion->idsec));
                    //Add Seccion
                    $cll_picadas_temp = $this->coje_picadas($empleados, $fecha_desde, $fecha_hasta, $tipo);
                    foreach ($cll_picadas_temp as &$picada) {
                        $picada[] = $seccion->seccion;
                        $picada[] = $departamento->departamento;
                    }
                    $cll_picadas = array_merge($cll_picadas, $cll_picadas_temp);
                }
            }
            echo json_encode(array('response' => true, "message" => "empresa", "departamentos_picadas" => $cll_picadas, 'empresa' => $empresa[0], 'desde' => $fecha_desde, 'hasta' => $fecha_hasta));
            return;
        }
        if ($id_departamento != 0) {
            $departamento = $this->Departamento_model->get_info($id_departamento);
            $cll_seccion = $this->Seccion_model->get_all(0, 300, array('iddep' => $departamento[0]->iddep));
            $cll_picadas = array();
            $cll_picadas_temp = array();
            foreach ($cll_seccion as $seccion) {
                $empleados = $this->Empleado_model->get_all(0, 300, array('id_seccion' => $seccion->idsec));
                //Add Seccion
                $cll_picadas_temp = $this->coje_picadas($empleados, $fecha_desde, $fecha_hasta, $tipo);
                foreach ($cll_picadas_temp as &$picada) {
                    $picada[] = $seccion->seccion;
                }
                $cll_picadas = array_merge($cll_picadas, $cll_picadas_temp);
            }
            echo json_encode(array('response' => true, "message" => "departamento", "secciones_picadas" => $cll_picadas, 'departamento' => $departamento[0], 'desde' => $fecha_desde, 'hasta' => $fecha_hasta));
            return;
        }

        if ($id_seccion != 0) {
            $seccion = $this->Seccion_model->get_info($id_seccion);
            $empleados = $this->Empleado_model->get_all(0, 300, array('id_seccion' => $id_seccion));
            $cll_empleados_picadas = $this->coje_picadas($empleados, $fecha_desde, $fecha_hasta, $tipo);
            echo json_encode(array('response' => true, "message" => "seccion", "empleados_picadas" => $cll_empleados_picadas, 'seccion' => $seccion[0], 'desde' => $fecha_desde, 'hasta' => $fecha_hasta));
            return;
        }

        $info = $this->Empleado_model->get_info($id_empleado);
        $empleado = $info[0];
        $codigo_reloj = $empleado->id_reloj;
        if ($codigo_reloj) {
//            $horario = $this->Horario_model->get_all(100, 0, array('id' => $empleado->id_horario));
            $horarios = $this->Horario_model->get_horario_empleado($id_empleado);
            $desde = date('Y-m-d', strtotime($fecha_desde));
            $hasta = date('Y-m-d', strtotime($fecha_hasta));

            $picadas = $this->Picada_model->get_all(1000, 0, array('codigo' => $codigo_reloj, 'fecha_picada >=' => $desde, 'fecha_picada<=' => $hasta), 'fecha_picada ASC');
            $permisos = $this->Permiso_picada_model->get_all(1000, 0, array('codigo' => $codigo_reloj, 'picada >=' => $desde, 'picada<=' => $hasta), 'picada ASC');
//            $resp = asignar_picadas($horario[0], $picadas, new DateTime($desde), new DateTime($hasta));
            $resp = asignar_picadas($horarios, $picadas, new DateTime($desde), new DateTime($hasta), $permisos);
//            echo json_encode(array('response' => true, "message" => "Empleado sin código de reloj asignado", "picadas" => $resp, "horario" => $horario[0], "empleado" => $empleado));
            echo json_encode(array('response' => true, "message" => "Empleado sin código de reloj asignado", "picadas" => $resp, "horario" => end($horarios), "empleado" => $empleado));
        } else {
            echo json_encode(array('response' => false, "message" => "Empleado sin código de reloj asignado"));
        }
    }

    /**
     * Recolecta las picadas que fueron realizadas por los empleados.
     * 
     * Desagregación del algorítmo de consulta_picadas.
     * 
     * @param type $empleados
     * @param type $fecha_desde
     * @param type $fecha_hasta
     * @param type $acumulado_tipo
     * @return array Picadas de los empleados.
     */
    public function coje_picadas($empleados, $fecha_desde, $fecha_hasta, $acumulado_tipo = "extras") {
        $cll_empleados = array();
        foreach ($empleados as $empleado) {
            $codigo_reloj = $empleado['id_reloj'];
            if ($codigo_reloj) {
                $horarios = $this->Horario_model->get_horario_empleado($empleado['id']);
//                $horario = $this->Horario_model->get_all(100, 0, array('id' => $empleado['id_horario']));
                $desde = date('Y-m-d', strtotime($fecha_desde));
                $hasta = date('Y-m-d', strtotime($fecha_hasta));
                $picadas = $this->Picada_model->get_all(1000, 0, array('codigo' => $codigo_reloj, 'fecha_picada >=' => $desde
                    , 'fecha_picada<=' => $hasta), 'fecha_picada ASC');
            }
            $permisos = $this->Permiso_picada_model->get_all(1000, 0, array('codigo' => $codigo_reloj, 'picada >=' => $desde, 'picada<=' => $hasta), 'picada ASC');
            $resp = asignar_picadas($horarios, $picadas, new DateTime($desde), new DateTime($hasta), $permisos);
            $res_horas = 'tot_horas_' . $acumulado_tipo;
            $res_minutos = 'tot_minutos_' . $acumulado_tipo;
            $cll_empleados[] = array($empleado['nombre'], $empleado['apellido'], $empleado['id_reloj'], $resp['resumen']->$res_horas . ":" . $resp['resumen']->$res_minutos);
        }
        return $cll_empleados;
    }

    /**
     * Obtiene los permisos que se han asignado a los empleados.
     * 
     * Es complemento de la función de *consulta_picadas*.
     * @param type $empleados
     * @param type $fecha_desde
     * @param type $fecha_hasta
     * @param type $acumulado_tipo
     * @return type
     */
    public function coje_permisos($empleados, $fecha_desde, $fecha_hasta, $acumulado_tipo = "extras") {
        $cll_empleados = array();
        foreach ($empleados as $empleado) {
            $codigo_reloj = $empleado['id_reloj'];
            if ($codigo_reloj) {
                $horarios = $this->Horario_model->get_horario_empleado($empleado['id']);

//                $horario = $this->Horario_model->get_all(100, 0, array('id' => $empleado['id_horario']));
                $desde = date('Y-m-d', strtotime($fecha_desde));
                $hasta = date('Y-m-d', strtotime($fecha_hasta));
                $picadas = $this->Picada_model->get_all(1000, 0, array('codigo' => $codigo_reloj, 'fecha_picada >=' => $desde
                    , 'fecha_picada<=' => $hasta), 'fecha_picada ASC');
            }
            $resp = asignar_picadas($horarios, $picadas, new DateTime($desde), new DateTime($hasta));
            $res_horas = 'tot_horas_' . $acumulado_tipo;
            $res_minutos = 'tot_minutos_' . $acumulado_tipo;
            $cll_empleados[] = array($empleado['nombre'], $empleado['apellido'], $empleado['id_reloj'], $resp['resumen']->$res_horas . ":" . $resp['resumen']->$res_minutos);
        }
        return $cll_empleados;
    }

    /**
     * Obtiene el total de horas extras que el empleado ha logrado realizar.
     */
    public function consulta_horas_extras() {
        $id_empresa = $this->input->post('id_empresa');
        $id_departamento = $this->input->post('id_departamento');
        $id_seccion = $this->input->post('id_seccion');
        $id_empleado = $this->input->post('id_empleado');
        $fecha_desde = $this->input->post('from');
        $fecha_hasta = $this->input->post('to');
        $info = $this->Empleado_model->get_info($id_empleado);
        $empleado = $info[0];
        $codigo_reloj = $empleado->id_reloj;
        if ($codigo_reloj) {
            $horario = $this->Horario_model->get_all(100, 0, array('id' => $empleado->id_horario));
            $picadas = $this->Picada_model->get_all(1000, 0, array('codigo' => $codigo_reloj, 'fecha_picada >=' => date('Y-m-d', strtotime($fecha_desde))
                , 'fecha_picada<=' => date('Y-m-d', strtotime($fecha_hasta))), 'fecha_picada ASC');
            $resp = asignar_picadas($horario[0], $picadas);
            echo json_encode(array('response' => true, "message" => "Empleado sin código de reloj asignado", "picadas" => $resp, "horario" => $horario[0]));
        } else {
            echo json_encode(array('response' => false, "message" => "Empleado sin código de reloj asignado"));
        }
    }

    /**
     * Obtiene los rangos de fechas que corresponden a la fecha de la primera picada y a la fecha de la última picada.
     */
    function get_desde_hasta() {
        $id = $this->input->post('id');
        $info = $this->Empleado_model->get_info($id);
        $result = $this->Picada_model->get_desde_hasta($info[0]->id_reloj);
        if ($result)
            echo json_encode(array('response' => 'success', 'desde_hasta' => $result[0]));
        else
            echo json_encode(array('response' => 'false', 'message' => 'Empleado sin registros'));
    }

    /**
     * Retorna las picadas registradas en el sistema.
     */
    function registradas() {
        $registros = $this->Picada_model->get_group_by_date();
        $data['registros'] = $registros;
        $this->twiggy->set($data);
        $this->twiggy->display('picadas/registradas');
    }

    /**
     * Borra los registros de los relojes biométricos almacenados en el sistema.
     */
    function borrar_registros() {
        $fecha = $this->input->post('fecha');
        $data = $this->Picada_model->borrar_registro($fecha);
        echo json_encode(array('response' => 'success', 'data' => $data));
    }

    /**
     * Ancho del dialogo del formulario.
     * @return int Dimensión del ancho del Formulario.
     */
    public function get_form_width() {
        return 700;
    }

    /**
     * Alto del formulario.
     * @return int Dimensión del alto del Formulario.
     */
    public function get_form_height() {
        return 500;
    }

}
