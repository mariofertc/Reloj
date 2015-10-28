for (var i = 0; i<response.picadas.cll_picadas.length; i++) {
                                var dia = "";
                                $.each(response.picadas.cll_picadas[i], function (key2, picadas) {
                                    if (cambia_dia != picadas.dia) {
                                        cambia_dia = picadas.dia;
                                        if (append != "")
                                            append += "</div>";
                                        append += '<div class="row color-swatches"><div class="col-sm-1 color-swatch brand-default">' + picadas.dia_texto + "</div>";
                                        append += '<div class="col-sm-1 color-swatch brand-primary">' + cambia_dia + "</div>";
                                    }
                                    append += '<div class="col-sm-1 color-swatch ' + (picadas.fallo == "s/r"?"brand-danger":"brand-info") + '">' + picadas.tiempo + " " + "</div>";
                                });
                                    observacion = response.picadas.cll_observacion[i];
                                    append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_trabajadas + ":" + observacion.minutos_trabajados + "</div>";
                                    append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_trabajadas_reales + ":" + observacion.minutos_trabajados_reales + "</div>";
                                    append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_atrasos + ":" + observacion.minutos_atrasos + "</div>";
                                    //append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_extras + ":" + observacion.minutos_extras + "</div>";
                                    //append += '<div class="col-sm-1 color-swatch brand-default text-right">' + observacion.horas_x + ":" + observacion.minutos_x + "</div>";
                                append += "</div>";
                            };                            