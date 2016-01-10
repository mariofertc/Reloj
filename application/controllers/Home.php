<?php
/**
 * Controlador de Home.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Secure_area
 * @subpackage Home
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");

/**
 * Controlador inicial del Sistema de Control de Picadas.
 */
class Home extends Secure_area {

    /**
     * Inicializa la clase Inicio.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Presenta la pantalla del Inicio del Sistema.
     */
    public function index() {
        $data['controller_name'] = "home";
        $data['title'] = "Menú Reloj";

        $this->twiggy->set($data);
        $this->twiggy->display('front_end/home');
    }

    /**
     * Finaliza la sesión de usuario.
     */
    function logout() {
        $this->Usuario_model->logout();
    }

}
