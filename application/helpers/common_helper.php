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