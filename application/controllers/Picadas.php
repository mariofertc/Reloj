<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

class Picadas extends Secure_area {

    public $controller_name;

    public function __construct() {
        $this->controller_name = "picadas";
        parent::__construct($this->controller_name);
    }

    public function index() {
        $empleados = $this->Empleado_model->get_all(0, 100);
        $data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => 'Seleccione un Empleado', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();

        $this->twiggy->set($data);
        $this->twiggy->display('picadas/registros');
    }
    
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

    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['nombre'] = $this->input->post('nombre');
        $data['apellido'] = $this->input->post('apellido');
        $data['edad'] = $this->input->post('edad');
        $data['cedula'] = $this->input->post('cedula');
        $data['estadocivil'] = $this->input->post('estadocivil');
        $data['direccion'] = $this->input->post('direccion');
        $data['fecha_ingreso'] = date('Y-m-d H:i:s', strtotime($this->input->post('fecha_ingreso')));
        //$data['fecha_ingreso'] = date('Y-m-d H:i:s', now());
        $data['id_horario'] = $this->input->post('id_horario');
        $data['id_reloj'] = $this->input->post('id_reloj');
        try {
            if ($this->Empleado_model->save($data, $id)) {
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

    public function delete($id = null) {
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
        $aColumns = array('id', 'nombre', 'apellido', 'edad', 'cedula', 'direccion', 'fecha_ingreso');
        //Eventos Tabla
        $cllAccion = array(
            '1' => array(
                'function' => "view",
                'common_language' => "common_edit",
                'language' => "_update",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height()));
        echo getData('Empleado_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    public function view($id = null) {
        $data['title'] = "Reloj | Empleados";
        $data['titulo'] = "Empleados";
        $horarios = $this->Horario_model->get_all();
        $cll_horario = array();
        foreach ($horarios as $horario) {
            $cll_horario[$horario['id']] = $horario['nombre'];
        }
        $data['horarios'] = $cll_horario;
        if ($id) {
            $info = $this->Empleado_model->get_info($id);
            $data['data'] = $info[0];
        }
        $this->twiggy->set($data);
        $this->twiggy->display('empleados/insert');
    }

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

    var $datos_upload;

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

    public function status_upload() {
        $id = $this->input->post('id');
        $status = "pending";
        if ((int) $this->session->upload_progress >= 100 || $this->session->upload_status == "done")
            $status = "done";

        echo json_encode(array("estado" => $status, 'progress' => $this->session->upload_progress));
    }

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
            $resp = asignar_picadas($horarios, $picadas, new DateTime($desde), new DateTime($hasta));
            $res_horas = 'tot_horas_' . $acumulado_tipo;
            $res_minutos = 'tot_minutos_' . $acumulado_tipo;
            $cll_empleados[] = array($empleado['nombre'], $empleado['apellido'], $empleado['id_reloj'], $resp['resumen']->$res_horas . ":" . $resp['resumen']->$res_minutos);
        }
        return $cll_empleados;
    }

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

    function get_desde_hasta() {
        $id = $this->input->post('id');
        $info = $this->Empleado_model->get_info($id);
        $result = $this->Picada_model->get_desde_hasta($info[0]->id_reloj);
        if ($result)
            echo json_encode(array('response' => 'success', 'desde_hasta' => $result[0]));
        else
            echo json_encode(array('response' => 'false', 'message' => 'Empleado sin registros'));
    }

    function registradas() {
        $registros = $this->Picada_model->get_group_by_date();
        $data['registros'] = $registros;
        $this->twiggy->set($data);
        $this->twiggy->display('picadas/registradas');
    }

    function borrar_registros() {
        $fecha = $this->input->post('fecha');
        $data = $this->Picada_model->borrar_registro($fecha);
        echo json_encode(array('response' => 'success', 'data' => $data));
    }

    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Empleado_model->get_info($id);
        echo get_empleado_data_row($info[0], $this);
    }

    public function get_form_width() {
        return 700;
    }

    public function get_form_height() {
        return 500;
    }

}
