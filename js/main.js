/**
 * Para coger los parámetros de un string url
 * @param {type} ref
 * @param {type} name
 * @returns {$.urlParam.results|Array|Number}
 */
$.urlParam = function (ref, name) {
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
$('body').on('click', '.modal_btn', function (e) {
    ref = this.href;
    $(function () {
        //title = $.urlParam(ref,'title')===null?"":$.urlParam(ref,'title');
        width = $.urlParam(ref, 'width') === null ? 400 : $.urlParam(ref, 'width');
        height = $.urlParam(ref, 'height') === null ? 400 : $.urlParam(ref, 'height');
        $('<div id="dialog">').dialog({
            modal: true,
            open: function () {
                $(this).load(ref, function () {
                    $(this).dialog("option", "title", $(this).find("legend").first().text());
                    $(this).find("legend").remove();
                });
            },
            height: height,
            width: width,
            maxWidth: 600,
            title: "Cargando...",
            close: function (event, ui) {
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
$("#add_department").on('click',function(){
    var empresa = $("[name='empresa_id']").val();
    $('<div id="dialog">').dialog({
            modal: true,
            open: function () {
                $(this).load("departamento/view/-1/",
                {"empresa":empresa},
                function () {
                    $(this).dialog("option", "title", $(this).find("h1").first().text());
                    $(this).find("h1").remove();
                }
                );
            },
            height: 400,
            width: 500,
            maxWidth: 600,
            title: "Cargando...",
            close: function (event, ui) {
                $(this).dialog("destroy").remove();
            }
        });
 });$("#edit_department").on('click',function(){
    var empresa = $("[name='empresa_id']").val();
    var departamento = $("[name='departamento_id']").val();
    $('<div id="dialog">').dialog({
            modal: true,
            open: function () {
                $(this).load("departamento/view/",
                {
                    "empresa":empresa,
                    "departamento": departamento
                },
                function () {
                    $(this).dialog("option", "title", $(this).find("h1").first().text());
                    $(this).find("h1").remove();
                }
                );
            },
            height: 400,
            width: 500,
            maxWidth: 600,
            title: "Cargando...",
            close: function (event, ui) {
                $(this).dialog("destroy").remove();
            }
        });
 });
 $("[name='departamento_id']").on("change",function(data){
    id_departamento = this.value;
    if(id_departamento == 0)
        return;
    $.ajax({
      type: "POST",
      url: 'seccion/get_by_department/',
      data: {'departamento':id_departamento},
      success: function(response){
        seccion_cbx = $("[name='seccion_id']");
        seccion_cbx.html("");
        $.each(response.seccion, (function(idx, value){
            seccion_cbx.append('<option value="'+idx+'">'+value+'</option>');
        }));
      },
      dataType: "json"
    });
 })
/*-------------------------------------Seccinones-------------------------------*/ 
 $("#add_seccion").on('click',function(){
    var departamento = $("[name='departamento_id']").val();
    var seccion = -1;
    $('<div id="dialog">').dialog({
            modal: true,
            open: function () {
                $(this).load('seccion/view/-1/',{
                    "departamento":departamento,
                    "seccion":seccion,
                },
                function () {
                    $(this).dialog("option", "title", $(this).find("h1").first().text());
                    $(this).find("h1").remove();
                }
                );
            },
            height: 400,
            width: 500,
            maxWidth: 600,
            title: "Cargando...",
            close: function (event, ui) {
                $(this).dialog("destroy").remove();
            }
        });
 });
 $("#edit_seccion").on('click',function(){
    var departamento = $("[name='departamento_id']").val();
    var seccion = $("[name='seccion_id']").val();
    $('<div id="dialog">').dialog({
            modal: true,
            open: function () {
                $(this).load("seccion/view/",{
                    "departamento": departamento,
                    "seccion": seccion
                },
                function () {
                    $(this).dialog("option", "title", $(this).find("h1").first().text());
                    $(this).find("h1").remove();
                }
                );
            },
            height: 400,
            width: 500,
            maxWidth: 600,
            title: "Cargando...",
            close: function (event, ui) {
                $(this).dialog("destroy").remove();
            }
        });
 });
 //Sólo en los formularios tengo que traer de forma global los eventos.
 // $("#form_seccion").on('submit', function( event ) {
  $( "body" ).on( "submit", "#form_seccion", function(event) {
        event.preventDefault();
        var $form = $( this ),
        //term = $form.find( "input[name='s']" ).val(),
        data = $form.serialize(),
        url = $form.attr( "action" );
        //alert(this.href)
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(response){
            if(response.result){
                tb_remove();
                new PNotify({
                    title: 'Sección',
                    text: 'Ingreso correctamente.',
                    nonblock: {
                            nonblock: true
                    },
                    delay: 3000,
                    type:"success"
                });
            }else{
                new PNotify({
                    title: 'Sección',
                    text: 'No se pudo almacenar los datos.',
                    nonblock: {
                            nonblock: true
                    },
                    delay: 3000,
                    type : "error"
                });
            }
          },
          dataType: "json"
        });
    });

/*-------------------------Empleados-----------------------*/
function set_feedback(message, title, type){
    new PNotify({
                    title: title,
                    text: message,
                    nonblock: {
                            nonblock: true
                    },
                    delay: 3000,
                    type : type
                });
}