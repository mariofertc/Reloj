<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_menu'))
{
function get_menu(){
	return json_decode('
        [{"nombre":"Empresa","url":"'.base_url('empresa').'","icon":"fa fa-building"},
        {"nombre":"Departamentos","url":"'.base_url('departamento').'","icon":"fa fa-university"},
        {"nombre":"Cargos","url":"'.base_url('cargos').'","icon":"fa fa-cab"},
        {"nombre":"Horarios","url":"'.base_url('horarios').'","icon":"fa fa-calendar"},
        {"nombre":"Empleados","url":"'.base_url('empleados').'","icon":"fa fa-users"},
        {"nombre":"Permisos","url":"'.base_url('permisos').'","icon":"fa fa-fire"},
        {"nombre":"Registro Picadas","url":"'.base_url('picadas').'","icon":"fa fa-bell","link":"/menu_inteligente"},
        {"nombre":"Html y css","url":"html_css.php","link":"/menu_inteligente/html_css.php"},
        {"nombre":"Accesos con mysql","url":"acceso_mysql.php","link":"/menu_inteligente/acceso_mysql.php"}]');
}
}