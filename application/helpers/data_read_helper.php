<?php

function read_data($path_file, $datos_adicionales, $config) {
    $h = fopen($path_file, "r");
    $cll_dato = array();
    $cll_variable = array();
    $cll_result = array();
    while (!feof($h)) {
        $prev_dato = fgetcsv($h, 500, isset($config['separador']) ? $config['separador'] : ",");
        //Salta encabezado
        if (count($cll_variable) == 0) {
            $cll_variable = $prev_dato;
            continue;
        }
        $variables = $datos_adicionales;
        $fecha = "";
        for ($i = 0; $i < count($prev_dato); $i++) {
            //Remove blank columns
            if (empty($prev_dato[$i]))
                continue;
            foreach ($config['data'] as $key => $cfg_data) {

                if (in_array($i, $cfg_data['indice'])) {
                    $datum = "";
                    foreach ($cfg_data['indice'] as $idx) {
//                      $fecha_cll = strtotime(str_replace(" ", "-", $prev_dato[$i]));
                        $datum .= $prev_dato[$idx] . " ";
                        $i++;
                    }
                    if (isset($cfg_data['format']))
                        $datum = DateTime::createFromFormat($cfg_data['format'], $datum)->format('Y-m-d H:i:s');
                    //var_dump($datum);
                    //$datum = date($cfg_data['format'], $datum);
                    $cll_dato[$key] = $datum;
                    //$cll_dato[] = $variables;
                    //break;
                }
            }
            
            //$indice = nombre_variable($cll_variable[$i]);
            //$variables[$indice] = (float) str_replace(",", ".", $prev_dato[$i]);
        }
        $cll_result[] = array_merge($cll_dato,$datos_adicionales);
        //Eliminar datos en blanco
    }//Fin del While
//    var_dump($cll_result);
    fclose($h);
    return $cll_result;
}