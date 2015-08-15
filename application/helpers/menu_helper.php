<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_menu'))
{
function get_menu(){
	return json_decode('
        [{"nombre":"Empresa","url":"/reloj/index.php/empresa","icon":"fa fa-building"},
        {"nombre":"Departamentos","url":"/reloj/index.php/departamento","icon":"fa fa-university"},
        {"nombre":"Empleados","url":"/reloj/index.php/empleados","icon":"fa fa-users"},
        {"nombre":"Horarios","url":"/reloj/index.php/horarios","icon":"fa fa-calendar"},
        {"nombre":"Permisos","url":"/reloj/index.php/horarios","icon":"fa fa-fire"},
        {"nombre":"Registro Picadas","url":"/reloj/index.php/picadas","icon":"fa fa-bell","link":"/menu_inteligente"},
        {"nombre":"Buscar","url":"/reloj/index.php/empleados/buscar_vista","link":"/menu_inteligente"},
        {"nombre":"Html y css","url":"html_css.php","link":"/menu_inteligente/html_css.php"},
        {"nombre":"Accesos con mysql","url":"acceso_mysql.php","link":"/menu_inteligente/acceso_mysql.php"}]');
}
}