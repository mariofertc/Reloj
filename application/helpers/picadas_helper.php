<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('asignar_picadas')) {

    function asignar_picadas($horario, $picadas) {
        //extract($options);
        $rango = array();
        $max_minutos_extras = 60 * 4;
        //append += "<label class='control-label'>Horario AP</label>";
//        var_dump($horario);
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
        $dia_anterior = new DateTime();
        foreach ($picadas as $registro) {
            $tiempo_picada = new DateTime($registro->fecha_picada);
            //Ver si es la fecha del ingreso del trabajo, y completa con los días de faltas.
            //if($tiempo_picada >)
            if ($tiempo_picada->format('d') != $dia_anterior->format('d')) {
                $idx_picadas++;
                if (isset($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas])) {
                    while (count($rango) > count($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas])) {
                        $cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas][] = get_comodin($dia_anterior, $rango, count($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas]));
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
            $cll_picadas[$idx_picadas][] = $picada_acomodada;
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
        }
            $tot_minutos_trabajados = 0;
            $tot_minutos_extras = 0;
            $tot_minutos_atrasos = 0;
        for ($idx_arreglo = 0; $idx_arreglo < count($cll_picadas); $idx_arreglo++) {
            //$cll_response[$idx_arreglo]['cll_picadas'] = $cll_picadas[$idx_arreglo];
            $obs = new stdClass();
            $minutos_trabajados = 0;
            $minutos_atrasos = 0;
            $idx_anterior = 0;
            $picada_comodin = null;
            $picada_anterior = null;
            for ($idx = 0; $idx < count($cll_picadas[$idx_arreglo]); $idx++) {
                $picada = $cll_picadas[$idx_arreglo][$idx];
                if ($idx % 2 == 1) {
                    $picada_comodin = $cll_picadas[$idx_arreglo][$idx - 1];
                    if ($picada_anterior != null) {
                        if (!$picada_comodin->fallo)
                            $picada_anterior = $picada_comodin;
                    } else
                        $picada_anterior = $picada_comodin;

                    //$picada_anterior = $cll_picadas[$idx_arreglo][$idx_anterior];
                    if (!$picada->fallo) {
                        $minutos_trabajados += $picada->minutos - $picada_anterior->minutos;
                    }
                    //$obs->minutos_trabajados += $picada->picada_red + $picada_anterior->picada_red;
                    //$res = $picada->minutos - $picada_anterior->minutos;
                } else if ($picada->diferencia_minutos < 0) {
                    $minutos_atrasos = $minutos_atrasos - $picada->diferencia_minutos;
                }
            }
            $horas_extras = $minutos_trabajados - ($horario['numero_horas'] * 60);
            $horas_extras = $horas_extras < 0 ? 0 : $horas_extras;

            $obs->horas_trabajadas = adherir_zero(intval($minutos_trabajados / 60, 0));
            $obs->minutos_trabajados = adherir_zero(abs($minutos_trabajados % 60));
            $tot_minutos_trabajados += $minutos_trabajados;

            $obs->horas_extras = adherir_zero(intval($horas_extras / 60, 0));
            $obs->minutos_extras = adherir_zero(intval($horas_extras % 60));
            $tot_minutos_extras += $horas_extras;

            $obs->horas_atrasos = adherir_zero(intval($minutos_atrasos / 60, 0));
            $obs->minutos_atrasos = adherir_zero(abs($minutos_atrasos % 60));
            $tot_minutos_atrasos += $minutos_atrasos;
            $cll_observacion[$idx_arreglo] = $obs;
            //$cll_observacion[$idx_arreglo]['minutos_atrasos'] = $minutos_atrasos;
        }
        $resumen = new stdClass();
        $resumen->tot_horas_trabajadas = adherir_zero(intval($tot_minutos_trabajados / 60, 0));
        $resumen->tot_minutos_trabajados = adherir_zero(abs($tot_minutos_trabajados % 60));

        $resumen->tot_horas_extras = adherir_zero(intval($tot_minutos_extras / 60, 0));
        $resumen->tot_minutos_extras = adherir_zero(intval($tot_minutos_extras % 60));

        $resumen->tot_horas_atrasos = adherir_zero(intval($tot_minutos_atrasos / 60, 0));
        $resumen->tot_minutos_atrasos = adherir_zero(abs($tot_minutos_atrasos % 60));
//        return $cll_picadas;
        return array('cll_picadas' => $cll_picadas, 'cll_observacion' => $cll_observacion,'resumen'=>$resumen);
        //var_dump($cll_picadas);
        //Primer caso, todo bien.
        /* if (cll_picadas.length == rango.length) {
          append = "<div class='form-group form-inline'>";
          append += "<label>" + tiempo[0] +"</label>";
          total_minutos = 0;
          $.each(cll_picadas, function(idx, pica) {
          hora_picada = parseInt(pica.split(":")[0]);
          minuto_picada = parseInt(pica.split(":")[1]);
          if(idx%2 == 1){
          total_minutos += (hora_picada * 60 + minuto_picada) - minutos_picada;
          }
          minutos_picada = hora_picada * 60 + minuto_picada;
          diff = minutos_picada - rango[idx];

          if(idx%2 == 1){
          if(diff > 0){
          //Atrasos
          append += '<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputWarning2Status" class="sr-only">(warning)</span>';
          }
          if(diff < 0){
          //Horas Extras
          append +=  '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>';
          }
          }else{
          diff = diff * -1;
          if(diff > 0){
          //Atrasos
          append += '<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputWarning2Status" class="sr-only">(warning)</span>';
          }
          if(diff < 0){
          //Horas Extras
          append +=  '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>';
          }
          }
          append += "<input value='" + pica + " " + diff + "' class='form-control' readonly=true/>";
          });
          append += "<label>Horas Trabajadas:" + Math.floor(total_minutos/60) + ":" + (total_minutos%60)+"</label>";
          horas_obs = (total_minutos-480)/60
          if(horas_obs > 0)
          append += "<label> - HE:" + Math.floor((total_minutos-480)/60) + ":" + ((total_minutos-480)%60)+"</label>";
          else
          append += "<label> - HA:" + Math.floor((total_minutos-480)/60) + ":" + ((total_minutos-480)%60)+"</label>";
          // append += "<label>Horas Trabajadas:" + (total_minutos/60).toFixed(2) + "</label>";
          append += "</div>";
          $("#result").append(append);
          cll_picadas = []; */
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
            //echo $i;
            $picada_reducida_horario = $rango[$i] - $rango[0];
            $result[$i] = array(abs($picada_reducida - $picada_reducida_horario), $i);
        }
        sort($result);
        $best_idx = $result[0][1];
        $obj = new stdClass;
        $obj->picada = $tiempo_picada;
        $obj->dia = $tiempo_picada->format("d/m/Y");
        $obj->tiempo = $tiempo_picada->format("H:i");
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