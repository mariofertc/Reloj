<?php
/**
 * Controlador de Usuarios.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Secure_area
 * @subpackage Usuarios
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");

/**
 * Clase que permite administrar los usuarios del Sistema.
 */
class Usuarios extends Secure_area {
/**
     *Almacenta el nombre del controlador.
     * @var string 
     */
    public $controller_name;

    /**
     * Inicializa la clase de Usuarios.
     */
    public function __construct() {
        $this->controller_name = "usuarios";
        parent::__construct($this->controller_name);
    }

    /**
     * Visualiza los usuarios del Sistema.
     */
    public function index() {
        $data['admin_table'] = get_usuario_admin_table();
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = $this->get_form_height();
        $this->twiggy->set('controller_name', $this->controller_name);
        $this->twiggy->set($data, null);
        $this->twiggy->display('usuarios/todos');
    }

    /**
     * Guarda o modifica los usuarios del Sistema.
     * @param int $id El identificador permite distinguir si se inserta un nuevo usuario o si se actualiza 
     * un usuario existente.
     */
    public function save($id = null) {
        $id = $id == null ? $this->input->post('id') : $id;
        $data['username'] = $this->input->post('username');
        $permission_data = $this->input->post("permissions") != false ? $this->input->post("permissions") : array();

        if ($this->input->post('password'))
            $data['password'] = sha1($this->input->post('password'));
        try {
            if ($this->Usuario_model->save($data, $id, $permission_data)) {
                if ($id == null) {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('usuarios_successful_add') .
                        $data['username'], 'id' => $data['id']));
                } else {
                    echo json_encode(array('success' => true, 'message' => $this->lang->line('usuarios_successful_update') .
                        $data['username'], 'id' => $id));
                }
            } else {
                echo json_encode(array('success' => false, 'message' => $this->lang->line('usuarios_error_add_update') .
                    $data['username'], 'id' => -1));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e .
                $data['username'], 'id' => $id));
            $this->db->trans_off();
        }
    }

    /**
     * Elimina un usuario de la base de datos.
     * @param int $id Identificador del usuario.
     */
    public function delete($id = null) {
        $to_delete = $this->input->post('ids');
        if ($this->Usuario_model->delete_list($to_delete)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('usuarios_successful_deleted') . ' ' .
                count($to_delete) . ' ' . $this->lang->line('usuarios_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('usuarios_cannot_be_deleted')));
        }
    }

    /**
     * Función que envía dinámicamente al DataTable los datos de los usuarios.
     * 
     * @return string Json con la información de los usuarios.
     */
    function mis_datos() {
        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['form_height'] = 150;
        $aColumns = array('id', 'username', 'fecha_creacion');
        //Eventos Tabla
        $cllAccion = array(
            '1' => array(
                'function' => "view",
                'common_language' => "common_edit",
                'language' => "_update",
                'width' => $this->get_form_width(),
                'height' => $this->get_form_height())
        );
        header("Access-Control-Allow-Origin: *");
        echo getData('Usuario_model', $aColumns, $cllAccion, false, null, 'mysql');
    }

    /**
     * Formulario para el ingreso o modificación de usuarios.
     * @param int $id Si existe el identificador entonces el formulario permite visualizar los datos
     * almacenados del usuario, caso contrario se puede ingresar un nuevo usuario.
     */
    public function view($id = null) {
        $data['title'] = "Reloj | Usuarios";
        $data['titulo'] = "Usuarios";

        if ($id) {
            $info = $this->Usuario_model->get_info($id);
            $info = $info[0];
            $data['data'] = $info;
        }
        $result = $this->Module_model->get_all_modules()->result();
        //$data['all_modules'] = array_to_htmlcombo($result, array('blank_text' => 'Seleccione un Cargo', 'id' => 'id', 'name' => array('nombre')));
        foreach ($result as &$module) {
            $module->permiso = $this->Usuario_model->has_permission($module->module_id, $id);
        }
        $data['all_modules'] = $result;
        $this->twiggy->set($data);
        $this->twiggy->display('usuarios/insert');
    }

    /**
     * Busca al usuario.
     * 
     * @deprecated since version 1.0.0
     */
    public function buscar_vista() {
        $data['id'] = $this->input->post('q');
        $data['datos'] = $this->Usuario_model->get_info($data['id']);
        $emp = $data['datos'];
        if (count($emp) > 0) {
            $edad = $data['datos'][0]->edad;
            $id = $data['datos'][0]->id;
            $resultado = $edad + $id * $edad;
            $data['resultado'] = $resultado;
            $data['datos'][0]->se_casa = $data['datos'][0]->edad * $data['datos'][0]->edad;
        }
        $this->twiggy->set($data);
        $this->twiggy->display('usuarios/buscar');
    }

    /**
     * Reporte de Usuarios que estan almacenados en el sistema.
     */
    function reporte() {
        $empresas = $this->Empresa_model->get_all(0, 100);
        $departamentos = $this->Departamento_model->get_all(0, 100);
        $secciones = $this->Seccion_model->get_all(0, 100);
        $usuarios = $this->Usuario_model->get_all(0, 100);
        $data['empresas'] = array_to_htmlcombo($empresas, array('blank_text' => 'Seleccione una Empresa', 'id' => 'ide', 'name' => array('nombree')));
        $data['departamentos'] = array_to_htmlcombo($departamentos, array('blank_text' => 'Seleccione un Departamento', 'id' => 'iddep', 'name' => array('departamento')));
        $data['secciones'] = array_to_htmlcombo($secciones, array('blank_text' => 'Seleccione una Seccion', 'id' => 'idsec', 'name' => array('seccion')));
        $data['usuarios'] = array_to_htmlcombo($usuarios, array('blank_text' => 'Seleccione un Usuario', 'id' => 'id', 'name' => array('nombre', 'apellido')));
        $data['controller_name'] = strtolower($this->uri->segment(1));

        $this->twiggy->set($data);
        $this->twiggy->display('reportes/usuarios');
    }

    /**
     * Reporte de los usuarios agrupados por usuarios, empresa, departamentos y secciones.
     * @return type
     */
    function consulta_usuarios() {
        $id_usuario = $this->input->post('id_usuario');
        $id_seccion = $this->input->post('id_seccion');
        $id_departamento = $this->input->post('id_departamento');
        $id_empresa = $this->input->post('id_empresa');
        if ($id_empresa != 0) {
            $empresa = $this->Empresa_model->get_info($id_empresa);
            $cll_departamento = $this->Departamento_model->get_all(0, 300, array('ideem' => $id_empresa));
            $cll_usuarios = array();
            foreach ($cll_departamento as $departamento) {
                $cll_seccion = $this->Seccion_model->get_all(0, 300, array('iddep' => $departamento->iddep));
                foreach ($cll_seccion as $seccion) {
                    $usuarios = $this->Usuario_model->get_all(0, 300, array('id_seccion' => $seccion->idsec), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
                    $usuarios_temp = array();
                    foreach ($usuarios as $usuario) {
                        $usuarios_temp = array_values($usuario);
                        $usuarios_temp[] = $seccion->seccion;
                        $usuarios_temp[] = $departamento->departamento;
                        $cll_usuarios[] = $usuarios_temp;
                    }
                }
            }
            echo json_encode(array('response' => true, "message" => "empresa", "usuarios_by_empresa" => $cll_usuarios, 'empresa' => $empresa[0]));
            return;
        }
        if ($id_departamento != 0) {
            $departamento = $this->Departamento_model->get_info($id_departamento);
            $cll_seccion = $this->Seccion_model->get_all(0, 300, array('iddep' => $departamento[0]->iddep));
            $cll_usuarios = array();
            foreach ($cll_seccion as $seccion) {
                $usuarios = $this->Usuario_model->get_all(0, 300, array('id_seccion' => $seccion->idsec), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
                $usuarios_temp = array();
                foreach ($usuarios as $usuario) {
                    $usuarios_temp = array_values($usuario);
                    $usuarios_temp[] = $seccion->seccion;
                    $cll_usuarios[] = $usuarios_temp;
                }
            }
            echo json_encode(array('response' => true, "message" => "departamento", "usuarios_by_departamento" => $cll_usuarios, 'departamento' => $departamento[0]));
            return;
        }

        if ($id_seccion != 0) {
            $seccion = $this->Seccion_model->get_info($id_seccion);
            $usuarios = $this->Usuario_model->get_all(0, 300, array('id_seccion' => $id_seccion), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
            $cll_usuarios = array();
            $usuarios_temp = array();
            foreach ($usuarios as $usuario) {
                $usuarios_temp = array_values($usuario);
                //$usuarios_temp[] = $seccion->seccion;
                $cll_usuarios[] = $usuarios_temp;
            }
            //$cll_usuarios_picadas = $this->coje_picadas($usuarios, $fecha_desde, $fecha_hasta, $tipo);
            echo json_encode(array('response' => true, "message" => "seccion", "usuarios_by_seccion" => $cll_usuarios, 'seccion' => $seccion[0]));
            return;
        }

        $usuarios = $this->Usuario_model->get_all(0, 300, array('id' => $id_usuario), null, array('cedula', 'nombre', 'apellido', 'fecha_ingreso', 'direccion'));
        $usuarios_temp = array();
        foreach ($usuarios as $usuario) {
            $usuarios_temp[] = array_values((array) $usuario);
            //$usuario = array_values($info[0]);
        }
        //$horario = $this->Horario_model->get_all(100, 0, array('id' => $usuario->id_horario));
        echo json_encode(array('response' => true, "message" => "usuario", "usuario" => $usuarios_temp));
    }

    /**
     * Retorna la fila del DataTable, con información del usuario que se ha editado o insertado en la base de datos.
     * @param int $id Identificador del usuario.
     */
    public function get_row($id = null) {
        $id = $this->input->post('row_id');
        $info = $this->Usuario_model->get_info($id);
        echo get_usuario_data_row($info[0], $this);
    }

    /**
     * Ancho del dialogo del formulario.
     * @return int Dimensión del ancho del Usuario.
     */
    public function get_form_width() {
        return 400;
    }

    /**
     * Alto del formulario.
     * @return int Dimensión del alto del Usuario.
     */
    public function get_form_height() {
        return 500;
    }

}