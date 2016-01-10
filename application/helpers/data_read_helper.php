<?php
/**
 * Helper con funciones de lectura de archivos.
 * El código de la Aplicación esta bajo la licencia GPL.
 * @package Helper Data Read
 * @author Mario Torres
 */
/**
 * Facilita la lectura de archivos de texto.
 * @param string $path_file
 * @param array $datos_adicionales
 * @param array $config
 * @return array
 */
function read_data($path_file, $datos_adicionales, $config) {
    $h = fopen($path_file, "r");
    $cll_dato = array();
    $cll_result = array();
    $prev_dato = "";
    while (!feof($h)) {
        if ($prev_dato == "" && isset($config['salta_encabezado'])) {
            $prev_dato = fgetcsv($h, 500, isset($config['separador']) ? $config['separador'] : ",");
            continue;
        }
        $prev_dato = fgetcsv($h, 500, isset($config['separador']) ? $config['separador'] : ",");
        for ($i = 0; $i < count($prev_dato); $i++) {
            //Remove blank columns
            if (empty($prev_dato[$i]))
                continue;
            foreach ($config['data'] as $key => $cfg_data) {
                if (in_array($i, $cfg_data['indice'])) {
                    $datum = "";
                    foreach ($cfg_data['indice'] as $idx) {
                        $datum .= trim($prev_dato[$idx]) . " ";
                        $i++;
                    }
                    if (isset($cfg_data['format'])) {
                        //Complete minutes it is because php doesnt have without zero minutes. "12 2 12 10 12 ". Should be "12 02 12 10 12 ";
                        $datum = minute_integrity($datum);
                        $datum = DateTime::createFromFormat($cfg_data['format'], $datum)->format('Y-m-d H:i:s');
                    }
                    $datum = trim($datum);
                    $cll_dato[$key] = $datum;
                }
            }
        }
        $cll_result[] = array_merge($cll_dato, $datos_adicionales);
    }//Fin del While
    fclose($h);
    return $cll_result;
}

/**
 * Integrar minutos
 * @param string $datum
 * @return string
 */
function minute_integrity($datum) {
    if (strlen($datum) < 15) {
        $resp = explode(" ", $datum);
        if (strlen($resp[1]) == 1) {
            $datum = "";
            $resp[1] = '0' . $resp[1];
            foreach ($resp as $r)
                $datum = $datum . $r . " ";
        }
    }
    return $datum;
}

/**
 * Permite determinar a qué tipo de reloj biométrico se esta accediendo.
 * @param string $path_file
 * @return string|null
 */
function view_type($path_file) {
    $h = fopen($path_file, "r");
    if ($h) {
        while (($line = fgets($h)) !== false) {
            //echo $line;
            //echo "-".strpos('\t',$line );
            //echo $line;
            if (strpos($line, chr(9)))
                return "dat";
            if (strpos($line, ',') == 2)
                return "log";
            if (strpos($line, '.') == 2)
                return "txt";
            return null;
            break;
        }
        fclose($h);
    }
}
