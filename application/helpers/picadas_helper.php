<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('asignar_picadas_empleados')) {
/**
 * Inicio del algoritmo base del Sistema.
 * @param array $empleados
 */
    function asignar_picadas_empleados($empleados) {
        $cll_picada = array();
        foreach ($empleados as $empleado) {
            $cll_picada[$empleado] = asignar_picadas($horario, $picadas, $desde, $hasta);
        }
    }

}

if (!function_exists('asignar_picadas')) {
/**
 * Algoritmo principal que permite conocer las picadas, permisos, atrasos, horas extras, horas trabajadas 
 * y Resúmenes de los diferentes empleados.
 * @param array $horarios
 * @param array $picadas
 * @param string $desde
 * @param string $hasta
 * @param array $permisos
 * @return array[]
 */
    function asignar_picadas($horarios, $picadas, $desde, $hasta, $permisos) {
        $max_minutos_extras = 60 * 4;
        //Convierte las picadas diarias en un valor entero para poder comaprar.
        $cll_horario_diario = array();
        foreach ($horarios as &$horario) {
            $horario_diario = json_decode($horario['picadas']);
            foreach ($horario_diario as &$dia) {
                foreach ($dia->picadas as $picada) {
                    $hora_horario = explode(":", $picada);
                    $hora_horario = $hora_horario[0];
                    $minuto_horario = explode(":", $picada);
                    $minuto_horario = $minuto_horario[1];
                    $am_pm = explode(" ", $picada);
                    if ($am_pm[1] == "PM")
                        $hora_horario += 12;
                    $minutos_horario = $hora_horario * 60 + $minuto_horario;
                    $dia->rango[] = $minutos_horario;
                    //var_dump($horario);
                }
                //$dia->fecha = $horario['fecha_creacion'];
            }
            $data['horario'] = $horario_diario;
            $data['fecha'] = $horario['fecha_creacion'];
            $cll_horario_diario[] = $data;
        }
        //var_dump($cll_horario_diario);
        $cll_picadas = array();
        $cll_observacion = array();
        $idx_picadas = -1;
        $idx_picada = 0;
        //$dia_anterior = null;
        //$dia_falta = null;

        $tiempo_actual = null;
        $tiempo_anterior = null;
        $tiempo_falta = null;
        foreach ($picadas as $registro) {
            //Selecciona el horario de acuerdo a la picada.
            $horario_diario = get_horario_diario($registro->fecha_picada, $cll_horario_diario);
            //var_dump($horario_diario);
            $tiempo_actual = dia_horario(new DateTime($registro->fecha_picada), $horario_diario);
            //var_dump($tiempo_actual);
            //Ver si es la fecha del ingreso del trabajo, y completa con los días de faltas.
            //Cambio de Día.
            //$dia_anterior = $dia_anterior == null ? $desde : $dia_anterior;
            //
//            $tiempo_anterior->picada = !isset($tiempo_anterior->picada)?$desde:$tiempo_anterior->picada;            
//            $tiempo_anterior->picada_format = date("Y-m-d", strtotime($tiempo_anterior->picada->format('Y-m-d')));
            $tiempo_anterior = !isset($tiempo_anterior->picada) ? dia_horario($desde, $horario_diario) : $tiempo_anterior;
            //$tiempo_anterior->picada_format = date("Y-m-d", strtotime($tiempo_anterior->picada->format('Y-m-d')));
            //$fecha_anterior = date("Y-m-d", strtotime($dia_anterior->format('Y-m-d')));

            if ($tiempo_anterior->picada_format != $tiempo_actual->picada_format) {
                if (isset($cll_picadas[$idx_picadas]))
                    if (count($cll_picadas[$idx_picadas]) < count($tiempo_anterior->rango)) {
                        $tiempo_falta = $tiempo_anterior;
                    } else {
                        $tiempo_falta = siguiente_dia_horario($tiempo_anterior->picada, $horario_diario);
                    }
            }
            //Rellena dias y picadas faltantes.
            if ($tiempo_actual->picada_format > $tiempo_anterior->picada_format || $idx_picadas == -1) {
                //Chequea y rellena los días faltados.
                //while ($dia_falta->format('d/m/y') < $tiempo_picada->format('d/m/y')) {
                //$tiempo_falta->picada = !isset($tiempo_falta->picada) ? dia_horario($desde, $horario_diario,$rango) : $tiempo_falta->picada;
                //$tiempo_falta = !isset($tiempo_falta->picada) ? dia_horario($desde, $horario_diario) : $tiempo_falta;
                $tiempo_falta = !isset($tiempo_falta->picada) ? $tiempo_anterior : $tiempo_falta;


                /* Hojete* */
                //$tiempo_falta->picada_format = $tiempo_anterior->picada_format;
//                $fecha_falta = date("Y-m-d", strtotime($dia_anterior->format('Y-m-d')));
                //while ($fecha_falta < $fecha_picada && count($cll_picadas[$idx_picadas])<count($rango)) {
                while ($tiempo_falta->picada_format < $tiempo_actual->picada_format) {
                    if ($idx_picadas == -1)
                        $idx_picadas++;
                    if (isset($cll_picadas[$idx_picadas]))
//                        if (count($cll_picadas[$idx_picadas]) >= count($tiempo_falta->rango)) {
                        if (count($cll_picadas[$idx_picadas]) >= count($tiempo_falta->rango) || count($cll_picadas[$idx_picadas]) >= count($tiempo_anterior->rango)) {
                            $idx_picadas++;
                            $cll_picadas[$idx_picadas] = array();
                        }
                    $idx_for = isset($cll_picadas[$idx_picadas]) ? count($cll_picadas[$idx_picadas]) : 0;
                    //$rango = get_rango($tiempo_falta->picada,$horario_diario);
//                    for ($idx_falta = $idx_for; $idx_falta < count($tiempo_falta->rango); $idx_falta++) {

                    for ($idx_falta = $idx_for; $idx_falta < count($tiempo_falta->rango); $idx_falta++) {
                        //var_dump($tiempo_anterior);
//                        if(count($tiempo_anterior->rango) < $idx_for-1)
                        $cll_picadas[$idx_picadas][] = get_comodin($tiempo_falta, $idx_falta, $permisos, 1);
                    }
                    // if ($tiempo_falta->picada_format < $tiempo_actual->picada_format) {
                    $tiempo_anterior = $tiempo_falta;
                    $tiempo_falta = siguiente_dia_horario($tiempo_falta->picada, $horario_diario);
                    //}
                    $idx_picadas++;
                }
                $tiempo_falta = siguiente_dia_horario($tiempo_falta->picada, $horario_diario);
                //Indice general de días.
                $idx_picadas = count($cll_picadas);
                //echo $idx_picadas;
                $idx_comodin = $idx_picadas > 0 ? $idx_picadas - 1 : $idx_picadas;
                //Llena Datos del día anterior.
                if (isset($cll_picadas[$idx_comodin]) && $idx_picadas != 0)
                    while (count($tiempo_anterior->rango) > count($cll_picadas[$idx_comodin]))
                        $cll_picadas[$idx_comodin][] = get_comodin($tiempo_anterior, count($cll_picadas[$idx_comodin]), $permisos, 2);
                $idx_picada = 0;
                $tiempo_anterior = $tiempo_actual;
            }

            $picada_acomodada = acomoda_ubicacion($tiempo_actual, isset($cll_picadas[$idx_picadas]) ? count($cll_picadas[$idx_picadas]) : 0);
            //$picada_acomodada = acomoda_ubicacion($tiempo_actual->picada, $rango);
            while ($picada_acomodada->posicion > $idx_picada) {
                $cll_picadas[$idx_picadas][] = get_comodin($tiempo_actual, $idx_picada, $permisos, 3);
                $idx_picada++;
            }
            if ($idx_picada <= $picada_acomodada->posicion)
                $idx_picada++;
            //else 
            //  $idx_picadas ++;

            /* if($idx_picadas == -1)
              $idx_picadas ++; */
            //Borra picadas repetidas
            if (!es_repetida($picada_acomodada, isset($cll_picadas[$idx_picadas]) ? $cll_picadas[$idx_picadas] : array()))
                $cll_picadas[$idx_picadas][] = $picada_acomodada;
            else
                continue;
            if ($idx_picada >= count($tiempo_actual->rango) || $picada_acomodada->posicion >= count($tiempo_actual->rango)) {
                if (count($cll_picadas[$idx_picadas]) > count($tiempo_actual->rango)) {
                    $cll_picadas[$idx_picadas] = quitar_relleno($cll_picadas[$idx_picadas]);
                }
            } else if ($picadas[count($picadas) - 1] == $registro) {
                while (count($tiempo_actual->rango) > count($cll_picadas[$idx_picadas])) {
                    $cll_picadas[$idx_picadas][] = get_comodin($tiempo_actual, $idx_picada, $permisos, 4);
                    $idx_picada++;
                }
            }
            if ($picadas[count($picadas) - 1] == $registro) {
                //Chequea y rellena los días faltados.
                $tiempo_falta = siguiente_dia_horario($tiempo_actual->picada, $horario_diario);
                //while ($hasta > $tiempo_falta->picada) {
                while ($hasta > $tiempo_falta->picada) {
                    $idx_picadas++;
                    for ($idx_falta = 0; $idx_falta < count($tiempo_falta->rango); $idx_falta++) {
                        $cll_picadas[$idx_picadas][] = get_comodin($tiempo_falta, $idx_falta, $permisos, 5);
                    }
                    $tiempo_falta = siguiente_dia_horario($tiempo_falta->picada, $horario_diario);
                }
            }
            $tiempo_anterior = $tiempo_actual;
        }

        //Observaciones
        $tot_minutos_trabajados = 0;
        $tot_minutos_trabajados_reales = 0;
        $tot_minutos_extras = 0;
        $tot_minutos_atrasos = 0;
        $tot_minutos_permisos = 0;
        $tot_minutos_x = 0;
        $tot_nombre_permiso = '';
        for ($idx_arreglo = 0; $idx_arreglo < count($cll_picadas); $idx_arreglo++) {
            $obs = new stdClass();
            $minutos_trabajados = 0;
            $minutos_trabajados_reales = 0;
            $minutos_atrasos = 0;
            $minutos_permisos = 0;
            $nombre_permiso = '';
            $idx_anterior = 0;
            $picada_comodin = null;
            $picada_anterior = null;
            if (!isset($cll_picadas[$idx_arreglo])) {
                var_dump($cll_picadas);
                var_dump($idx_arreglo);
                die();
            }

            $total_picadas_dia = count($cll_picadas[$idx_arreglo]);
            $minutos_faltantes = 0;
            for ($idx = 0; $idx < $total_picadas_dia; $idx++) {
                $picada = $cll_picadas[$idx_arreglo][$idx];
                /* Chequea si pica dentro de los rangos Reales */
                if ($idx == 0) {
                    if ($picada->diferencia_minutos > 0)
                        $minutos_faltantes = abs($picada->diferencia_minutos);
                }else if ($idx == $total_picadas_dia - 1) {
                    if ($picada->diferencia_minutos < 0)
                        $minutos_faltantes = $minutos_faltantes != 0 ? $minutos_faltantes + abs($picada->diferencia_minutos) : abs($picada->diferencia_minutos);
                }
                /* Fin chequeo */
                if ($idx % 2 == 1) {
                    $picada_comodin = $cll_picadas[$idx_arreglo][$idx - 1];
                    if ($picada_anterior != null) {
                        if (!$picada_comodin->fallo)
                            $picada_anterior = $picada_comodin;
                    }
                    else
                        $picada_anterior = $picada_comodin;
//                    if (!$picada->fallo && !$picada_comodin->fallo) {
//                    echo $picada->fallo;
                    //if ($picada->fallo != 's/r' && $picada_comodin->fallo != 's/r') {
                    if ($picada->fallo != 's/r' && ($picada_comodin->fallo != 's/r' || $picada_anterior->fallo != 's/r')) {
//                        $rango_trabajado = $picada->minutos - $picada_anterior->minutos;
//                        $rango_trabajado = $picada->minutos - $picada_comodin->minutos;
                        $rango_trabajado = $picada->minutos - ($picada_comodin->fallo != 's/r'?$picada_comodin->minutos:$picada_anterior->minutos);
//                        $minutos_permisos = $picada->tiempo_permiso?$picada->tiempo_permiso:$picada_comodin->tiempo_permiso?$picada_comodin->tiempo_permiso:$picada_anterior->tiempo_permiso?$picada_anterior->tiempo_permiso:0;
                        $picada_permiso = $picada_comodin->fallo == "permiso"?$picada_comodin:$picada_anterior->fallo =="permiso"?$picada_anterior:null;
                        if($picada_permiso != null){
                            $minutos_permisos = $picada_permiso->minutos;
                            $nombre_permiso = $picada_permiso->nombre_permiso;
                        }
                        else
                            $minutos_permisos = 0;
                        $minutos_trabajados += $rango_trabajado;
                        $minutos_trabajados_reales += $rango_trabajado - $minutos_faltantes;
                        $minutos_faltantes = 0;
                    }
                } else if ($picada->diferencia_minutos < 0) {
                    $minutos_atrasos += $minutos_atrasos - $picada->diferencia_minutos;
                }
            }
            $horas_extras = $minutos_trabajados - ($horario['numero_horas'] * 60);
            $horas_extras = $horas_extras < 0 ? 0 : $horas_extras;

            $obs->horas_trabajadas = adherir_zero(intval($minutos_trabajados / 60, 0));
            $obs->minutos_trabajados = adherir_zero(abs($minutos_trabajados % 60));
            $tot_minutos_trabajados += $minutos_trabajados;

            $obs->horas_trabajadas_reales = adherir_zero(intval($minutos_trabajados_reales / 60, 0));
            $obs->minutos_trabajados_reales = adherir_zero(abs($minutos_trabajados_reales % 60));
            $tot_minutos_trabajados_reales += $minutos_trabajados_reales;

            $obs->horas_extras = adherir_zero(intval($horas_extras / 60, 0));
            $obs->minutos_extras = adherir_zero(intval($horas_extras % 60));
            $tot_minutos_extras += $horas_extras;

            $obs->horas_atrasos = adherir_zero(intval($minutos_atrasos / 60, 0));
            $obs->minutos_atrasos = adherir_zero(abs($minutos_atrasos % 60));
            $tot_minutos_atrasos += $minutos_atrasos;
            
            $obs->horas_permisos = adherir_zero(intval($minutos_permisos / 60, 0));
            $obs->minutos_permisos = adherir_zero(abs($minutos_permisos % 60));
            $tot_minutos_permisos += $minutos_permisos;
            
            $obs->nombre_permiso = $nombre_permiso;
            $tot_nombre_permiso .= $nombre_permiso;

            $horas_x = $horas_extras - $minutos_atrasos;
            //$horas_x = $horas_x < 0 ? 0 : $horas_x;

            $obs->horas_x = adherir_zero(intval($horas_x / 60, 0));
            $obs->minutos_x = adherir_zero(intval($horas_x % 60, 0));
            $tot_minutos_x += $horas_x;

            $cll_observacion[$idx_arreglo] = $obs;
            //$cll_observacion[$idx_arreglo]['minutos_atrasos'] = $minutos_atrasos;
        }
        $resumen = new stdClass();
        $resumen->tot_horas_trabajadas = adherir_zero(intval($tot_minutos_trabajados / 60, 0));
        $resumen->tot_minutos_trabajadas = adherir_zero(abs($tot_minutos_trabajados % 60));

        $resumen->tot_horas_trabajadas_reales = adherir_zero(intval($tot_minutos_trabajados_reales / 60, 0));
        $resumen->tot_minutos_trabajadas_reales = adherir_zero(abs($tot_minutos_trabajados_reales % 60));

        $resumen->tot_horas_extras = adherir_zero(intval($tot_minutos_extras / 60, 0));
        $resumen->tot_minutos_extras = adherir_zero(intval($tot_minutos_extras % 60));

        $resumen->tot_horas_atrasos = adherir_zero(intval($tot_minutos_atrasos / 60, 0));
        $resumen->tot_minutos_atrasos = adherir_zero(abs($tot_minutos_atrasos % 60));
        
        $resumen->tot_horas_permisos = adherir_zero(intval($tot_minutos_permisos / 60, 0));
        $resumen->tot_minutos_permisos = adherir_zero(abs($tot_minutos_permisos % 60));

        $resumen->tot_nombre_permisos = $tot_nombre_permiso;
        
        $resumen->tot_horas_x = adherir_zero(intval($tot_minutos_x / 60, 0));
        $resumen->tot_minutos_x = adherir_zero(abs($tot_minutos_x % 60));
        return array('cll_picadas' => $cll_picadas, 'cll_observacion' => $cll_observacion, 'resumen' => $resumen);
    }

}

/**
 * Define el horario diario de la picada.
 * @param datetime $picada
 * @param array $cll_horario
 * @return datetime
 */
function get_horario_diario($picada, $cll_horario) {
    $horario_diario = array();
    foreach ($cll_horario as $horario) {
        //if($horario['fecha']>=$picada)
        if ($picada >= $horario['fecha'])
            $horario_diario = $horario['horario'];
    }
    //end($cll_horario);
    if (count($horario_diario) == 0)
        $horario_diario = $cll_horario[count($cll_horario) - 1]['horario'];
//        $horario = end($cll_horario)['horario'];
    return $horario_diario;
}

/**
 * Obtiene el rango de horas en las que cae la picada.
 * @param datetime $dia
 * @param array $horario
 * @return int
 */
function get_rango($dia, $horario) {
    $i = 7;
    do {
        foreach ($horario as $d) {
            if (strcasecmp(substr(dia_semana($dia), -4), substr($d->nombre, -4)) == 0) {
                return $d->rango;
            }
        }
        $dia->modify('+1 day');
    } while ($i-- > 0);
}

if (!function_exists('es_repetida')) {
/**
 * Verifica si la picada es repetida.
 * @param object $picada_acomodada
 * @param array $cll_picadas
 * @return boolean
 */
    function es_repetida($picada_acomodada, $cll_picadas) {
        $max_timempo_picada = 4;
        foreach ($cll_picadas as $picada) {
            $diferencia = $picada_acomodada->picada_red - $picada->picada_red;
            if (abs($diferencia) < $max_timempo_picada && !$picada->fallo && !$picada_acomodada->fallo)
                return true;
        }
        return false;
    }

}
if (!function_exists('siguiente_dia_horario')) {
/**
 * Obtiene el siguiente día de la picada enviada.
 * @param datetime $dia_falta
 * @param array $horario
 * @return \stdClass
 */
    function siguiente_dia_horario($dia_falta, $horario) {
        //$dias_horario = json_decode($dias, TRUE);
        $i = 7;
        do {
            $dia_falta->setTime(00, 00);
            $dia = $dia_falta->modify('+1 day');
            foreach ($horario as $d) {
                if (strcasecmp(substr(dia_semana($dia), -4), substr($d->nombre, -4)) == 0) {
                    $obj = new stdClass();
                    $obj->rango = $d->rango;
                    $obj->picada = $dia;
                    $obj->picada_format = date("Y-m-d", strtotime($dia->format('Y-m-d')));
                    return $obj;
                    //$rango = $d->rango;
                    // return $dia;
                }
            }
        } while ($i-- > 0);
    }

}
if (!function_exists('dia_horario')) {

    /**
     * Devuelve la fecha del día con el horario.
     * @param type $dia_falta
     * @param type $horario
     * @param type $rango
     * @return type
     */
    function dia_horario($dia, $horario) {
        $i = 7;
        $rango_aux = null;
        do {
            foreach ($horario as $d) {
                if (strcasecmp(substr(dia_semana($dia), -4), substr($d->nombre, -4)) == 0) {
                    $obj = new stdClass();
                    $obj->rango = $d->rango;
                    $obj->picada = $dia;
                    $obj->picada_format = date("Y-m-d", strtotime($dia->format('Y-m-d')));
                    return $obj;
                }
                $rango_aux = $d->rango;
            }
            $obj = new stdClass();
            $obj->rango = $rango_aux;
            $obj->picada = $dia;
            $obj->picada_format = date("Y-m-d", strtotime($dia->format('Y-m-d')));
            return $obj;
            //$dia->modify('+1 day');
        } while ($i-- > 0);
    }

}

if (!function_exists('adherir_zero')) {
/**
 * Rellena el cero de los minutos.
 * @param string $hora_minuto
 * @return string
 */
    function adherir_zero($hora_minuto) {
        if (strlen($hora_minuto) < 2)
            return '0' . $hora_minuto;
        return $hora_minuto;
    }

}
if (!function_exists('coje_permiso')) {
/**
 * Barre los permisos que se encuentren asignados a las picadas.
 * @param type $picada
 * @param type $permisos
 * @param type $idx
 * @return null
 */
    function coje_permiso($picada, $permisos, $idx) {
        foreach ($permisos as $permiso) {
//            echo $permiso['picada'] . "-" . $picada->picada->format("Y-m-d H:i:s") . "+";
            if ($permiso['picada'] == $picada->picada->format("Y-m-d H:i:s"))
                if ($permiso['posicion'] == $idx)
                    
//                    return array(substr($permiso['nueva_picada'],0,-6),$permiso['nombre']);
                    return array($permiso['nueva_picada'],$permiso['nombre']);
//                    return array($permiso['nueva_picada'],$permiso['nombre']);
//                    return $permiso['nueva_picada'];
        }
        return null;
    }

}
if (!function_exists('adherir_comodin')) {

    /**
     * Artificio para llenar las picadas vacías.
     * @param string $tiempo_picada
     * @param int $idx_picada
     * @param array $permisos
     * @param string $log
     * @return \stdClass
     */
    function get_comodin($tiempo_picada, $idx_picada, $permisos, $log) {
        $tiene_permiso = coje_permiso($tiempo_picada, $permisos, $idx_picada);
        $permiso = null;
        $picada_minutos = 0;
        $diferencia_minutos = 0;
        $fallo = "s/r";
        $obj = new stdClass;
        if ($tiene_permiso != null) {
            $permiso = substr($tiene_permiso[0], 11);
            $permiso = substr($permiso, 0, -3);
            $obj->nombre_permiso = $tiene_permiso[1];
            //$permiso_hora = substr($permiso, 8);
            $horas = substr($permiso, 0, 2);
            $minutos = substr($permiso, 2, 2);
            $rango = $tiempo_picada->rango;
            $picada_minutos = (($horas * 60 + $minutos));
            $picada_reducida = $picada_minutos - $rango[0];
            $result = array();
            for ($i = 0; $i < count($rango); $i++) {
                $picada_reducida_horario = $rango[$i] - $rango[0];
                $result[$i] = array(abs($picada_reducida - $picada_reducida_horario), $i);
            }
            sort($result);
            $best_idx = $result[0][1];
            $diferencia_minutos = $rango[$best_idx] - $picada_minutos;
            $fallo = 'permiso';
            //echo $permiso.'-';
            //echo $permiso_hora.'+';
        }
        $obj->picada = "s/r";
        $obj->dia = $tiempo_picada->picada->format("d/m/Y");
        $obj->tiempo = $permiso == null ? "00:00" : $permiso;
        $obj->tiempo_permiso = $permiso;
        $obj->posicion = $idx_picada;
        $obj->dia_texto = dia_semana($tiempo_picada->picada);
        $obj->picada_red_horario = $tiempo_picada->rango[$idx_picada];
        $obj->picada_red = "s/r";
        $obj->minutos = $picada_minutos;
        $obj->diferencia_minutos = $diferencia_minutos;
//        $obj->fallo = "s/r";
        $obj->fallo = $fallo;
        $obj->rango = $tiempo_picada->rango;
        $obj->idx_picada = $idx_picada;
        $obj->log = $log;
        return $obj;
    }

}
if (!function_exists('acomoda_ubicacion')) {
/**
 * Ubica la picada en la mejor ubicación de las picadas.
 * @param datetime $tiempo_picada
 * @param int $idx_picada
 * @return \stdClass
 */
    function acomoda_ubicacion($tiempo_picada, $idx_picada) {
        $rango = $tiempo_picada->rango;
        $picada_minutos = (($tiempo_picada->picada->format('H') * 60 + $tiempo_picada->picada->format('i')));
        $picada_reducida = $picada_minutos - $rango[0];
        $result = array();
        for ($i = 0; $i < count($rango); $i++) {
            $picada_reducida_horario = $rango[$i] - $rango[0];
            $result[$i] = array(abs($picada_reducida - $picada_reducida_horario), $i);
        }
        sort($result);

        //Para obtener el día en español.
        $best_idx = $result[0][1];
        $obj = new stdClass;
        $obj->picada = $tiempo_picada->picada;
        $obj->dia = $tiempo_picada->picada->format("d/m/Y");
        $obj->tiempo = $tiempo_picada->picada->format("H:i");
        $obj->dia_texto = dia_semana($tiempo_picada->picada);
        $obj->fallo = null;
        $obj->posicion = $best_idx;
        $obj->picada_red_horario = $picada_reducida_horario;
        $obj->picada_red = $picada_reducida;
        $obj->minutos = $picada_minutos;
        $obj->diferencia_minutos = $rango[$best_idx] - $picada_minutos;

//        var_dump($tiempo_picada);
        $obj->rango = $rango;
        $obj->idx_picada = $idx_picada;
        $obj->log = 0;

        return $obj;
    }

}


if (!function_exists('dia_semana')) {
/**
 * Devuelve los textos de los días de la semana, del tiempo de picada correspondiente.
 * @param datetime $tiempo_picada
 * @return string
 */
    function dia_semana($tiempo_picada) {
        $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
        return $dias[$tiempo_picada->format("w")];
    }

}
if (!function_exists('quitar_relleno')) {
/**
 * Elimina las picadas comodines que están demás.
 * @param array $datos
 * @return array
 */
    function quitar_relleno($datos) {
        $cll_temp = array();
        foreach ($datos as $dato)
            if ($dato->fallo != true)
                $cll_temp[] = $dato;
        return $cll_temp;
    }

}
if (!function_exists('line')) {
/**
 * Devuelve el texto corresondiente al lenguaje solicitado.
 * @param string $cadena
 * @return string
 */
    function line($cadena) {
        $CI = & get_instance();
        return $CI->lang->line($cadena);
    }

}
if (!function_exists('dias_semana')) {
/**
 * Obtiene los días de las semanas para los combobox.
 * @return type
 */
    function dias_semana() {
        return array("lunes" => "Lunes", "martes" => "Martes", "miercoles" => "Miercoles", "jueves" => "Jueves", "viernes" => "Viernes", "sabado" => "Sabado", "domingo" => "Domingo");
    }

}
if (!function_exists('dias_semana_full')) {
/**
 * Devuelve los días de la semana.
 * @return type
 */
    function dias_semana_full() {
        return array(0, 1, 2, 3, 4, 5, 6, 7);
    }

}