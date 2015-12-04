<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Secure_area.php';

class Reportes extends Secure_area {

    public $controller_name;

    public function __construct() {
        $this->controller_name = "reportes";
        parent::__construct($this->controller_name);
    }
    
    public function personal($id) {
        $empresas = $this->Empresa_model->get_all(0, 100);
        $departamentos = $this->Departamento_model->get_all(0, 100);
        $secciones = $this->Seccion_model->get_all(0, 100);
        $empleados = $this->Empleado_model->get_info($id);
        $data['empleado'] = $empleados[0];
        $data['empleados'] = array_to_htmlcombo($empleados, array('blank_text' => '', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));

        $this->twiggy->set($data);
        $this->twiggy->display('reportes/horas_trabajadas');
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

    function get_desde_hasta() {
        $id = $this->input->post('id');
        $info = $this->Empleado_model->get_info($id);
        $result = $this->Picada_model->get_desde_hasta($info[0]->id_reloj);
        if ($result)
            echo json_encode(array('response' => 'success', 'desde_hasta' => $result[0]));
        else
            echo json_encode(array('response' => 'false', 'message' => 'Empleado sin registros'));
    }

}
