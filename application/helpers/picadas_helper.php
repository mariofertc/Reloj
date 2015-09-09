<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('asignar_picadas')) {

    function asignar_picadas($horario, $picadas) {
        //extract($options);
        $rango = array();
        $max_minutos_extras = 60 * 4;
        //append += "<label class='control-label'>Horario AP</label>";
        //var_dump($horario);
        foreach (json_decode($horario['picadas']) as $picada) {
            $hora_horario = explode(":", $picada)[0];
            $minuto_horario = explode(":", $picada)[1];
            if (explode(" ", $picada)[1] == "PM")
                $hora_horario += 12;
            $minutos_horario = $hora_horario * 60 + $minuto_horario;
            $rango[] = $minutos_horario;
        }
        //Chequea si hubo ya una picada anterior.
        $picada_anterior = 0;
        $cambia_dia = "";
        $id_dia = 0;
        $cll_picadas = [];
        $registro_picada = count($rango);
        $idx_picadas = 0;
        $idx_picada = 0;
        $dia_anterior = new DateTime();
        foreach ($picadas as $registro) {
            $tiempo_picada = new DateTime($registro->fecha_picada);
            if ($tiempo_picada->format('d') != $dia_anterior->format('d')) {
                $idx_picadas++;
                if (isset($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas]) ) {
                    //var_dump($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas]);
                    //echo count($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas]);
                    while (count($rango) > count($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas])) {
                        $cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas][] = get_comodin($dia_anterior, $rango, count($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas]));
                        //$idx_picada ++;
                    }
                    //if(count($rango) == count($cll_picadas[$idx_picadas != 0 ? $idx_picadas - 1 : $idx_picadas]))
                }
                $idx_picada = 0;
                $dia_anterior = $tiempo_picada;
            }
            $picada_acomodada = acomoda_ubicacion($tiempo_picada, $rango);
            while ($picada_acomodada->posicion > $idx_picada) {
                $cll_picadas[$idx_picadas][] = get_comodin($tiempo_picada, $rango, $idx_picada);
                $idx_picada ++;
            }
            //adherir_comodin($picada_acomodad,&$idx_picada);
//            adherir_comodin($picada_acomodad,&$idx_picada);
            if ($idx_picada <= $picada_acomodada->posicion)
                $idx_picada ++;
            $cll_picadas[$idx_picadas][] = $picada_acomodada;
            if ($idx_picada >= count($rango) || $picada_acomodada->posicion >= count($rango)) {
                if (count($cll_picadas[$idx_picadas]) > count($rango)) {
                    $cll_picadas[$idx_picadas] = quitar_relleno($cll_picadas[$idx_picadas]);
                }
            }else if($picadas[count($picadas)-1] == $registro){
                while (count($rango) > count($cll_picadas[$idx_picadas])) {
                //echo "last";
                    $cll_picadas[$idx_picadas][] = get_comodin($tiempo_picada, $rango, $idx_picada);
                    $idx_picada ++;
                }                
            }
            //var_dump($picada_acomodada);
        }
        return $cll_picadas;
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

if (!function_exists('adherir_comodin')) {

    function get_comodin($tiempo_picada, $rango, $idx_picada) {
        $obj = new stdClass;
        $obj->picada = "s/r";
        $obj->dia = $tiempo_picada->format("d/m/Y");
        $obj->tiempo = "00:00";
        $obj->posicion = $idx_picada;
        $obj->picada_red_horario = $rango[$idx_picada];
        $obj->picada_red = "s/r";
        $obj->fallo = "s/r";
        return $obj;
    }

}
if (!function_exists('acomoda_ubicacion')) {

    function acomoda_ubicacion($tiempo_picada, $rango) {
        $picada_reducida = (($tiempo_picada->format('H') * 60 + $tiempo_picada->format('i'))) - $rango[0];
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