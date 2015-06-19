<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_menu'))
{
function get_menu(){
	return json_decode('
        [{"nombre":"Empresa","url":"/reloj/index.php/empleados","link":"/menu_inteligente"},
        {"nombre":"Ingreso Empleados","url":"/reloj/index.php/empleados","link":"/menu_inteligente"},
        {"nombre":"Reporte Empleados Ingresados","url":"/reloj/index.php/empleados/listar","link":"/menu_inteligente"},
        {"nombre":"Buscar","url":"/reloj/index.php/empleados/buscar_vista","link":"/menu_inteligente"},
        {"nombre":"Html y css","url":"html_css.php","link":"/menu_inteligente/html_css.php"},
        {"nombre":"Accesos con mysql","url":"acceso_mysql.php","link":"/menu_inteligente/acceso_mysql.php"}]');
}
}