for (var i = 0; i<response.picadas.cll_picadas.length; i++) {
                                var dia = "";
                                $.each(response.picadas.cll_picadas[i], function (key2, picadas) {
                                    if (cambia_dia != picadas.dia) {
                                        cambia_dia = picadas.dia;
                                        if (append != "")
                                            append += "</div>";
                                        append += '<div class="row"><span class="col-sm-1 label label-default">' + picadas.dia_texto + "</span>";
                                        append += '<span class="col-sm-1 label label-primary">' + cambia_dia + "</span>";
                                    }
                                    append += '<span class="col-sm-1 label ' + (picadas.fallo == "s/r"?"label-danger":"label-info") + '">' + picadas.tiempo + " " + "</span>";
                                });
                                    observacion = response.picadas.cll_observacion[i];
                                    append += '<span class="col-sm-1 label label-default text-right">' + observacion.horas_trabajadas + ":" + observacion.minutos_trabajados + "</span>";
                                    append += '<span class="col-sm-1 label label-default text-right">' + observacion.horas_trabajadas_reales + ":" + observacion.minutos_trabajados_reales + "</span>";
                                    append += '<span class="col-sm-1 label label-default text-right">' + observacion.horas_atrasos + ":" + observacion.minutos_atrasos + "</label>";
                                    //append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_extras + ":" + observacion.minutos_extras + "</div>";
                                    //append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_x + ":" + observacion.minutos_x + "</div>";
                                append += "</div>";
                            };