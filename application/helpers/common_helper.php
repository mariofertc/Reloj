<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('array_to_htmlcombo')){
	function array_to_htmlcombo($result,$blanck_text=null,$encode_json=false){
		$result_array[] = $blanck_text == null?"Seleccione un Item":blanck_text;
			foreach ($result as $r) {
				$dep_array[$r->idsec] = $r->seccion;
			}
		return json_decode();
	}
}
if ( ! function_exists('line')){
	function line($cadena){
		$CI = & get_instance();
		return $CI->lang->line($cadena);
	}
}
if ( ! function_exists('dias_semana')){
    function dias_semana(){
        return array("lunes"=>"Lunes","martes"=>"Martes","miercoles"=>"Miercoles","jueves"=>"Jueves","viernes"=>"Viernes","sabado"=>"Sabado","domingo"=>"Domingo");
    }
}