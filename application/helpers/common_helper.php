<?php
/**
 * Helper con funciones comunes.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Helper Common
 * @author Mario Torres
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('array_to_htmlcombo')) {
/**
 * Permite convertir los datos de un array en una lista, lista para asociar a un html combo.
 * @param array $result
 * @param array $options
 * @param boolean $encode_json
 * @return array Listo para consumir en un combobox o listbox.
 */
    function array_to_htmlcombo($result, $options, $encode_json = false) {
        extract($options);
        $result_array[] = $blank_text == null ? "Seleccione un Item" : $blank_text;
        foreach ($result as $r) {
            $texto = array();
            foreach ($name as $n) {
                //if(gettext($r))
                if (gettype($r) == 'object')
                    $texto[] = $r->$n;
                else
                    $texto[] = $r[$n];
            }
            if (gettype($r) == 'object')
                $result_array[$r->$id] = implode(" ", $texto);
            else
                $result_array[$r[$id]] = implode(" ", $texto);
        }
        return $result_array;
    }

}
if (!function_exists('line')) {
/**
 * Función que extiente la función de lenguaje de CodeIgniter.
 * @param string $cadena
 * @return string Texto correspondiente al lenguaje solicitado.
 */
    function line($cadena) {
        $CI = & get_instance();
        return $CI->lang->line($cadena);
    }

}
if (!function_exists('dias_semana')) {

    /**
     * Días de la semana, para utilizarlas en comboboxes.
     * @return array
     */
    function dias_semana() {
        return array("lunes" => "Lunes", "martes" => "Martes", "miercoles" => "Miercoles", "jueves" => "Jueves", "viernes" => "Viernes", "sabado" => "Sabado", "domingo" => "Domingo");
    }

}
if (!function_exists('dias_semana_full')) {

    /**
     * Días de la semana numéricas.
     * @return array
     */
    function dias_semana_full() {
        return array(0, 1, 2, 3, 4, 5, 6, 7);
    }

}
if (!function_exists('get_dia_nombre')) {

    /**
     * Se obtiene el texto del día equivalente en número.
     * @param int $fecha
     * @return string
     */
    function get_dia_nombre($fecha) {
        switch (date('w', $fecha)) {
            case 0: return "Domingo";
                break;
            case 1: return "Lunes";
                break;
            case 2: return "Martes";
                break;
            case 3: return "Miercoles";
                break;
            case 4: return "Jueves";
                break;
            case 5: return "Viernes";
                break;
            case 6: return "Sabado";
                break;
        }
    }

}