/**
 * Para coger los parámetros de un string url
 * @param {type} ref
 * @param {type} name
 * @returns {$.urlParam.results|Array|Number}
 */
$.urlParam = function(ref, name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(ref);
    if (results == null) {
        return null;
    }
    else {
        return results[1] || 0;
    }
}
//$('.thickbox').on('click', function (e) {
/**
 * Dialogo JqueryUI
 * @param {type} param1
 * @param {type} param2
 * @param {type} param3
 */
$('body').on('click', '.modal_btn', function(e) {
    ref = this.href;
    $(function() {
        //title = $.urlParam(ref,'title')===null?"":$.urlParam(ref,'title');
        width = $.urlParam(ref, 'width') === null ? 400 : $.urlParam(ref, 'width');
        height = $.urlParam(ref, 'height') === null ? 400 : $.urlParam(ref, 'height');
        $('<div id="dialog">').dialog({
            modal: true,
            open: function() {
                $(this).load(ref, function() {
                    $(this).dialog("option", "title", $(this).find("legend").first().text());
                    $(this).find("legend").remove();
                });
            },
            height: height,
            width: width,
            maxWidth: 600,
            title: "Cargando...",
            close: function(event, ui) {
                $(this).dialog("destroy").remove();
            }
        });
    });
    return false;
});

function tb_remove() {
    $("#dialog").dialog('close');
    return false;
}

/*-------------------------------Departamentos-----------------------------*/
$("#add_department").on('click', function() {
    var empresa = $("[name='empresa_id']").val();
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load("departamento/view/-1/",
                    {"empresa": empresa},
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});
$("#edit_department").on('click', function() {
    var empresa = $("[name='empresa_id']").val();
    var departamento = $("[name='departamento_id']").val();
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load("departamento/view/",
                    {
                        "empresa": empresa,
                        "departamento": departamento
                    },
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});
$("#remove_department").on('click', function() {
    var empresa = $("[name='empresa_id']").val();
    var departamento = $("[name='departamento_id']").val();
    var r = confirm("Seguro desea borrar el departamento");
    if (r == true) {
        $.ajax({
            type: "POST",
            url: 'departamento/deleted/',
            data: {'departamento': departamento},
            success: function(response) {
                if (response.result == true)
                    $("[name='departamento_id'] > option[value=" + response.id + ']').remove();
            },
            dataType: "json"
        });
    }
});
$("[name='departamento_id']").on("change", function(data) {
    id_departamento = this.value;
    if (id_departamento == 0)
        return;
    $.ajax({
        type: "POST",
        url: 'seccion/get_by_department/',
        data: {'departamento': id_departamento},
        success: function(response) {
            seccion_cbx = $("[name='seccion_id']");
            seccion_cbx.html("");
            $.each(response.seccion, (function(idx, value) {
                seccion_cbx.append('<option value="' + idx + '">' + value + '</option>');
            }));
        },
        dataType: "json"
    });
})

function post_departamento(response) {
    if (response.result) {
        tb_remove();
        if (response.operation == 'update') {
            $("[name='departamento_id'] > option").each(function() {
                if (this.value == response.id)
                    this.text = response.nombre;
            });
        }
        else if (response.operation == 'insert') {
            $("[name='departamento_id']").append(
                    $('<option></option>').val(response.id).html(response.nombre)
                    );
        }
        new PNotify({
            title: 'Departamento',
            text: 'Ingreso correctamente.',
            nonblock: {
                nonblock: true
            },
            delay: 3000,
            type: "success"
        });
        $("#departamento_id > option").each(function() {
            alert($(this).attr('id'));
        });
    } else {
        new PNotify({
            title: 'Departamento',
            text: 'No se pudo almacenar los datos.',
            nonblock: {
                nonblock: true
            },
            delay: 3000,
            type: "error"
        })
    }
}

/*$("body").on("submit", "#form_departamento", function (event) {
 event.preventDefault();
 var $form = $(this),
 data = $form.serialize(),
 url = $form.attr("action");
 $.ajax({
 type: "POST",
 url: url,
 data: data,
 success: function (response) {
 post_departamento(response);
 return false;
 },
 dataType: "json"
 });
 });*/
/*-------------------------------------Seccinones-------------------------------*/
$("#add_seccion").on('click', function() {
    var departamento = $("[name='departamento_id']").val();
    var seccion = -1;
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load('seccion/view/-1/', {
                "departamento": departamento,
                "seccion": seccion,
            },
                    function() {
                        $(this).dialog("option", "title", $(this).find("h1").first().text());
                        $(this).find("h1").remove();
                    }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});
$("#edit_seccion").on('click', function() {
    var departamento = $("[name='departamento_id']").val();
    var seccion = $("[name='seccion_id']").val();
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load("seccion/view/", {
                "departamento": departamento,
                "seccion": seccion
            },
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});
$("#remove_seccion").on('click', function() {
    var seccion = $("[name='seccion_id']").val();
//    var nombre = $("[name='seccion_id']").text();
    var nombre = $("[name='seccion_id']").find('option:selected').text();
    var r = confirm("Seguro desea borrar la sección " + nombre);
    if (r == true) {
        $.ajax({
            type: "POST",
            url: 'seccion/deleted/',
            data: {'seccion': seccion},
            success: function(response) {
                if (response.result == true)
                    $("[name='seccion_id'] > option[value=" + response.id + ']').remove();
            },
            dataType: "json"
        });
    }
});
function post_seccion(response) {
    if (response.result) {
        tb_remove();

        if (response.operation == 'update') {
            $("[name='seccion_id'] > option").each(function() {
                if (this.value == response.id)
                    this.text = response.nombre;
            });
        }
        else if (response.operation == 'insert') {
            $("[name='seccion_id']").append(
                    $('<option></option>').val(response.id).html(response.nombre)
                    );
        }


        new PNotify({
            title: 'Sección',
            text: 'Ingreso correctamente.',
            nonblock: {
                nonblock: true
            },
            delay: 3000,
            type: "success"
        });
    } else {
        new PNotify({
            title: 'Sección',
            text: 'No se pudo almacenar los datos.',
            nonblock: {
                nonblock: true
            },
            delay: 3000,
            type: "error"
        });
    }
}
//Sólo en los formularios tengo que traer de forma global los eventos.
// $("#form_seccion").on('submit', function( event ) {
$("body").on("submit", "#form_seccion", function(event) {
    event.preventDefault();
    var $form = $(this),
            //term = $form.find( "input[name='s']" ).val(),
            data = $form.serialize(),
            url = $form.attr("action");
    //alert(this.href)
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(response) {
            post_seccion(response);
            return false;
        },
        dataType: "json"
    });
});
/*-------------------------------------Cargos-------------------------------*/
$("#add_cargo").on('click', function() {
    var cargo = -1;
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load('cargos/view/-1/', {
                "cargo": cargo
            },
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});
$("#edit_cargo").on('click', function() {
    var cargo = $("[name='cargo_id']").val();
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load("cargos/view/", {
                "cargo": cargo
            },
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});
//Sólo en los formularios tengo que traer de forma global los eventos.
// $("#form_seccion").on('submit', function( event ) {
$("body").on("submit", "#form_cargo", function(event) {
    event.preventDefault();
    var $form = $(this),
            data = $form.serialize(),
            url = $form.attr("action");
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(response) {
            post_cargos(response);
        },
        dataType: "json"
    });
});
function post_cargos(response) {
    if (response.result) {
        tb_remove();
        if (response.operation == 'update') {
            $("[name='cargo_id'] option[value='" + response.id + "']").text(response.nombre);
        }
        else if (response.operation == 'insert') {
            $("[name='cargo_id']").append($('<option></option>').val(response.id).html(response.nombre));
            //$("[name='cargo_id']").append($('<option value="'+ response.id + '"></option>').html(response.nombre));
        }

        new PNotify({
            title: 'Cargo',
            text: 'Ingreso correctamente.',
            nonblock: {
                nonblock: true
            },
            delay: 3000,
            type: "success"
        });
    } else {
        new PNotify({
            title: 'Cargo',
            text: 'No se pudo almacenar los datos.',
            nonblock: {
                nonblock: true
            },
            delay: 3000,
            type: "error"
        });
    }

}

/*-------------------------Empleados-----------------------*/
function set_feedback(message, title, type) {
    new PNotify({
        title: title,
        text: message,
        nonblock: {
            nonblock: true
        },
        delay: 3000,
        type: type
    });
}

/*Picadas*/
$("#btn_picadas_registradas").on('click', function() {
    //var empresa = $("[name='empresa_id']").val();
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load("picadas/registradas/",
                    {"empresa": null},
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});

/*-----------------Funciones Tiempo---------------*/
/**
 * Transforma la hora del datetimepicker en minutos.
 * @param {type} hora
 * @returns {undefined}
 */
function get_minutes(hora) {
    hora_horario = parseInt(hora.split(":")[0]);
    minuto_horario = parseInt(hora.split(":")[1]);
    if (hora.split(" ")[1] == "PM")
        hora_horario += parseInt(12);
    return hora_horario * 60 + minuto_horario;
}

$("#btn_fix").on('click', function() {
    alert("2");
    //var empresa = $("[name='empresa_id']").val();
    $('<div id="dialog">').dialog({
        modal: true,
        open: function() {
            $(this).load("picadas/registradas/",
                    {"empresa": null},
            function() {
                $(this).dialog("option", "title", $(this).find("h1").first().text());
                $(this).find("h1").remove();
            }
            );
        },
        height: 400,
        width: 500,
        maxWidth: 600,
        title: "Cargando...",
        close: function(event, ui) {
            $(this).dialog("destroy").remove();
        }
    });
});

/*-------------------------------Permisos-----------------------------*/
$('body').on('click', '.add_permisos', function(e) {
    if ($(this).text() == '+') {
        $(this).parent().find('.chosen-select').show(100);
        $(this).parent().find('.input-group').show(1000);
        $(this).text('-');
    } else {
        $(this).parent().find('.chosen-select').hide(100);
        $(this).parent().find('.input-group').hide(1000);
        $(this).text('+');

    }

});