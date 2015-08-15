<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('array_to_htmlcombo')) {

    function array_to_htmlcombo($result, $options, $encode_json = false) {
        extract($options);
        $result_array[] = $blank_text == null ? "Seleccione un Item" : $blank_text;
        foreach ($result as $r) {
            $texto = array();
            foreach($name as $n)
                $texto[]= $r[$n];
            $result_array[$r[$id]] = implode(" ",$texto);
        }
        return $result_array;
    }

}
if (!function_exists('line')) {

    function line($cadena) {
        $CI = & get_instance();
        return $CI->lang->line($cadena);
    }

}
if (!function_exists('dias_semana')) {

    function dias_semana() {
        return array("lunes" => "Lunes", "martes" => "Martes", "miercoles" => "Miercoles", "jueves" => "Jueves", "viernes" => "Viernes", "sabado" => "Sabado", "domingo" => "Domingo");
    }

}