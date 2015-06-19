<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        $data['controller_name'] = "home";
        $data['title'] = "Menú";
        $data['menu'] =  json_decode('
        [{"nombre":"Empresa","url":"/reloj/index.php/empleados","link":"/menu_inteligente"},
        {"nombre":"Ingreso Empleados","url":"/reloj/index.php/empleados","link":"/menu_inteligente"},
        {"nombre":"Reporte Empleados Ingresados","url":"/reloj/index.php/empleados/listar","link":"/menu_inteligente"},
        {"nombre":"Buscar","url":"/reloj/index.php/empleados/buscar_vista","link":"/menu_inteligente"},
        {"nombre":"Html y css","url":"html_css.php","link":"/menu_inteligente/html_css.php"},
        {"nombre":"Accesos con mysql","url":"acceso_mysql.php","link":"/menu_inteligente/acceso_mysql.php"}]');

        $this->twiggy->set($data);
        //$this->twiggy->template('front_end/home')->display();
        $this->twiggy->display('front_end/home');

        //$this->parser->parse('_layout/header', $data, true);
        //$this->parser->parse('home', $data, true);
    }
}