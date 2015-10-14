<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('asignar_picadas_empleados')) {

    function asignar_picadas_empleados($empleados) {
        $cll_picada = array();
        foreach ($empleados as $empleado) {
            $cll_picada[$empleado] = asignar_picadas($horario, $picadas, $desde, $hasta);
        }
    }

}


if (!function_exists('asignar_picadas')) {

    function asignar_picadas($horario, $picadas, $desde, $hasta) {
        $rango = array();
        $max_minutos_extras = 60 * 4;
        foreach (json_decode($horario['picadas']) as $picada) {
            $hora_horario = explode(":", $picada);
            $hora_horario = $hora_horario[0];
            $minuto_horario = explode(":", $picada);
            $minuto_horario = $minuto_horario[1];
            $am_pm = explode(" ", $picada);
            if ($am_pm[1] == "PM")
                $hora_horario += 12;
            $minutos_horario = $hora_horario * 60 + $minuto_horario;
            $rango[] = $minutos_horario;
        }
        //Chequea si hubo ya una picada anterior.
        $cll_picadas = array();
        $cll_observacion = array();
        $idx_picadas = -1;
        $idx_picada = 0;
        $dia_anterior = null;
        $dia_falta = null;
        foreach ($picadas as $registro) {
            $tiempo_picada = new DateTime($registro->fecha_picada);
            //Ver si es la fecha del ingreso del trabajo, y completa con los días de faltas.
            //Cambio de Día.
            $dia_anterior = $dia_anterior == null ? $desde : $dia_anterior;
            $fecha_picada = date("Y-m-d", strtotime($tiempo_picada->format('Y-m-d')));
            $fecha_anterior = date("Y-m-d", strtotime($dia_anterior->format('Y-m-d')));

            if ($fecha_anterior != $fecha_picada) {
                if (isset($cll_picadas[$idx_picadas]))
                    if (count($cll_picadas[$idx_picadas]) < count($rango)) {
                        $dia_falta = $dia_anterior;
                    } else {
                        $dia_falta = siguiente_dia_horario($dia_anterior, $horario['dias']);
                    }
            }
            //Rellena dias y picadas faltantes.
            if ($fecha_picada > $fecha_anterior || $idx_picadas == -1) {
                //Chequea y rellena los días faltados.
                //while ($dia_falta->format('d/m/y') < $tiempo_picada->format('d/m/y')) {
                $dia_falta = is_null($dia_falta) ? dia_horario($desde, $horario['dias']) : $dia_falta;
                $fecha_falta = date("Y-m-d", strtotime($dia_anterior->format('Y-m-d')));
                //while ($fecha_falta < $fecha_picada && count($cll_picadas[$idx_picadas])<count($rango)) {
                while ($fecha_falta < $fecha_picada) {
                    if ($idx_picadas == -1)
                        $idx_picadas ++;
                    if (isset($cll_picadas[$idx_picadas]))
                        if (count($cll_picadas[$idx_picadas]) >= count($rango)) {
                            $idx_picadas ++;
                            $cll_picadas[$idx_picadas] = array();
                        }
                    $idx_for = isset($cll_picadas[$idx_picadas]) ? count($cll_picadas[$idx_picadas]) : 0;
                    for ($idx_falta = $idx_for; $idx_falta < count($rango); $idx_falta ++) {
                        $cll_picadas[$idx_picadas][] = get_comodin($dia_falta, $rango, $idx_falta);
                    }
                    if ($fecha_falta < $fecha_picada) {
                        $dia_falta = siguiente_dia_horario($dia_falta, $horario['dias']);
                        $dia_anterior = $dia_falta;
                        $fecha_falta = date("Y-m-d", strtotime($dia_falta->format('Y-m-d')));
                    }
                    $idx_picadas ++;
                }
                $dia_falta = siguiente_dia_horario($dia_falta, $horario['dias']);
                //Indice general de días.
                $idx_picadas = count($cll_picadas);
                $idx_comodin = $idx_picadas > 0 ? $idx_picadas - 1 : $idx_picadas;
                if (isset($cll_picadas[$idx_comodin]) && $idx_picadas != 0) {
                    //Llena Datos del día anterior.
                    while (count($rango) > count($cll_picadas[$idx_comodin])) {
                        $cll_picadas[$idx_comodin][] = get_comodin($dia_anterior, $rango, count($cll_picadas[$idx_comodin]));
                    }
                }
                $idx_picada = 0;
                $dia_anterior = $tiempo_picada;
            }

            $picada_acomodada = acomoda_ubicacion($tiempo_picada, $rango);
            while ($picada_acomodada->posicion > $idx_picada) {
                $cll_picadas[$idx_picadas][] = get_comodin($tiempo_picada, $rango, $idx_picada);
                $idx_picada ++;
            }
            if ($idx_picada <= $picada_acomodada->posicion)
                $idx_picada ++;
            /* if($idx_picadas == -1)
              $idx_picadas ++; */
            //Borra picadas repetidas
            if (!es_repetida($picada_acomodada, isset($cll_picadas[$idx_picadas]) ? $cll_picadas[$idx_picadas] : array()))
                $cll_picadas[$idx_picadas][] = $picada_acomodada;
            else
                continue;
            if ($idx_picada >= count($rango) || $picada_acomodada->posicion >= count($rango)) {
                if (count($cll_picadas[$idx_picadas]) > count($rango)) {
                    $cll_picadas[$idx_picadas] = quitar_relleno($cll_picadas[$idx_picadas]);
                }
            } else if ($picadas[count($picadas) - 1] == $registro) {
                while (count($rango) > count($cll_picadas[$idx_picadas])) {
                    $cll_picadas[$idx_picadas][] = get_comodin($tiempo_picada, $rango, $idx_picada);
                    $idx_picada ++;
                }
            }
            if ($picadas[count($picadas) - 1] == $registro) {
                //Chequea y rellena los días faltados.
                $dia_falta = siguiente_dia_horario($tiempo_picada, $horario['dias']);
                while ($hasta > new DateTime($dia_falta->format('m/d/Y'))) {
                    $idx_picadas ++;
                    for ($idx_falta = 0; $idx_falta < count($rango); $idx_falta ++) {
                        $cll_picadas[$idx_picadas][] = get_comodin($dia_falta, $rango, $idx_falta);
                    }
                    $dia_falta = siguiente_dia_horario($dia_falta, $horario['dias']);
                }
            }
            $dia_anterior = $tiempo_picada;
        }

        //Observaciones
        $tot_minutos_trabajados = 0;
        $tot_minutos_trabajados_reales = 0;
        $tot_minutos_extras = 0;
        $tot_minutos_atrasos = 0;
        $tot_minutos_x = 0;
        for ($idx_arreglo = 0; $idx_arreglo < count($cll_picadas); $idx_arreglo++) {
            $obs = new stdClass();
            $minutos_trabajados = 0;
            $minutos_trabajados_reales = 0;
            $minutos_atrasos = 0;
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
                    } else
                        $picada_anterior = $picada_comodin;
                    if (!$picada->fallo && !$picada_comodin->fallo) {
                        $rango_trabajado = $picada->minutos - $picada_comodin->minutos;
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

        $resumen->tot_horas_x = adherir_zero(intval($tot_minutos_x / 60, 0));
        $resumen->tot_minutos_x = adherir_zero(abs($tot_minutos_x % 60));
        return array('cll_picadas' => $cll_picadas, 'cll_observacion' => $cll_observacion, 'resumen' => $resumen);
    }

}

if (!function_exists('es_repetida')) {

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

    function siguiente_dia_horario($dia_falta, $dias) {
        $dias_horario = json_decode($dias, TRUE);
        $i = 7;
        do {
            $dia = $dia_falta->modify('+1 day');
            foreach ($dias_horario as $d) {
                if (strcasecmp(substr(dia_semana($dia), -4), substr($d, -4)) == 0) {
                    return $dia;
                }
            }
        } while ($i-- > 0);
    }

}
if (!function_exists('dia_horario')) {

    function dia_horario($dia_falta, $dias) {
        $dias_horario = json_decode($dias, TRUE);
        $i = 7;
        do {

            foreach ($dias_horario as $d) {
                if (strcasecmp(substr(dia_semana($dia_falta), -4), substr($d, -4)) == 0) {
                    return $dia_falta;
                }
            }
            $dia_falta->modify('+1 day');
        } while ($i-- > 0);
    }

}

if (!function_exists('adherir_zero')) {

    function adherir_zero($hora_minuto) {
        if (strlen($hora_minuto) < 2)
            return '0' . $hora_minuto;
        return $hora_minuto;
    }

}
if (!function_exists('adherir_comodin')) {

    function get_comodin($tiempo_picada, $rango, $idx_picada) {
        $obj = new stdClass;
        $obj->picada = "s/r";
        $obj->dia = $tiempo_picada->format("d/m/Y");
        $obj->tiempo = "00:00";
        $obj->posicion = $idx_picada;
        $obj->dia_texto = dia_semana($tiempo_picada);
        $obj->picada_red_horario = $rango[$idx_picada];
        $obj->picada_red = "s/r";
        $obj->minutos = 0;
        $obj->diferencia_minutos = 0;
        $obj->fallo = "s/r";
        return $obj;
    }

}
if (!function_exists('acomoda_ubicacion')) {

    function acomoda_ubicacion($tiempo_picada, $rango) {
        $picada_minutos = (($tiempo_picada->format('H') * 60 + $tiempo_picada->format('i')));
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
        $obj->picada = $tiempo_picada;
        $obj->dia = $tiempo_picada->format("d/m/Y");
        $obj->tiempo = $tiempo_picada->format("H:i");
        $obj->dia_texto = dia_semana($tiempo_picada);
        $obj->fallo = null;
        $obj->posicion = $best_idx;
        $obj->picada_red_horario = $picada_reducida_horario;
        $obj->picada_red = $picada_reducida;
        $obj->minutos = $picada_minutos;
        $obj->diferencia_minutos = $rango[$best_idx] - $picada_minutos;
        //$cll_picadas[] = $obj;
        return $obj;
    }

}


if (!function_exists('dia_semana')) {

    function dia_semana($tiempo_picada) {
        $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
        return $dias[$tiempo_picada->format("w")];
    }

}
if (!function_exists('quitar_relleno')) {

    function quitar_relleno($datos) {
        $cll_temp = array();
        foreach ($datos as $dato)
            if ($dato->fallo != true)
                $cll_temp[] = $dato;
        return $cll_temp;
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
if (!function_exists('dias_semana_full')) {

    function dias_semana_full() {
        return array(0, 1, 2, 3, 4, 5, 6, 7);
    }

}