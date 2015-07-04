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
$('body').on('click', '.modal', function (e) {
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