/**
 * @source ./views/scripts/caja.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion del flujo de caja
 */

/**
 * Variables grobales
 */
let tabla;

/**
 * Init
 */
$(document).ready(function () {
    listar();
    $('.selectpicker').selectpicker();
    mostrarForm(false);
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
    $.post("../ajax/caja.php?op=selectUsuario", function (r) {
        $("#idUsuario").html(r);
        $('.selectpicker').selectpicker('refresh');
    });
});

/*********************************FUNCIONES PARA EL MANEJO DEL DOM *********************************/

/**
 * Permite mostrar el form para agregar un nuevo flujo de caja
 * @param flag
 */
function mostrarForm(flag) {
    limpiar();
    if (flag) {
        $("#saldo").val(0);
        $("#listadoregistros").hide();
        $("#formularioregistro").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistro").hide();
        $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").show();
    }
}

/**
 * Permite ocultar el form
 */
function cancelarForm() {
    limpiar();
    mostrarForm(false);
}

/**
 * Permite inicializar los elementos del form
 */
function limpiar() {
    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fechaHora').val(today);
    $("#idFlujo").val("");
    $("#saldo").val("");
}


/*********************************FUNCIONALIDAD*********************************/
/**
 * Permite guardar o editar un flujo de caja mediante una operación AJAX
 * @param e
 */
const guardaryeditar = (e) => {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    let formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/caja.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert({
                message: datos,
                size: 'medium',
            })
            mostrarForm(false);
            tabla.ajax.reload();
        }
    });
    limpiar();
}

/**
 * Permite listar el flujo de caja del usuario que inición sesion mediante una operación AJAX
 */
const listar = () => {
    tabla = $('#tablaListado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del dataTables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":
                {
                    url: '../ajax/caja.php?op=listar',
                    type: "get",
                    dataType: "json",
                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}

/**
 * Permite cambiar de estado del flujo de caja a "cerrada" mediante una operacion AJAX
 * @param idFlujo
 */
const cerrar = (idFlujo) => {
    bootbox.confirm("¿Está seguro de cerrar el flujo de caja?", function (result) {
        if (result) {
            $.post("../ajax/caja.php?op=cerrar", {idFlujo: idFlujo}, function (e) {
                bootbox.alert({
                    message: e,
                    size: 'small',
                })
                tabla.ajax.reload();
            });
            mostrarSaldoFlujo();
        }
    })
}

const abrir = (idFlujo) => {
    bootbox.confirm("¿Está seguro de abrir el flujo de caja?", function (result) {
        if (result) {
            $.post("../ajax/caja.php?op=abrir", {idFlujo: idFlujo}, function (e) {
                bootbox.alert({
                    message: e,
                    size: 'small',
                })
                tabla.ajax.reload();
            });
            mostrarSaldoFlujo();
        }
    })
}

/**
 * Permite mostrar el flujo de caja para editarlo
 * @param idFlujo
 */
const mostrar = (idFlujo) => {
    $.post("../ajax/caja.php?op=mostrar", {idFlujo: idFlujo}, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        mostrarForm(true);
        $("#idUsuario").val(data.idUsuario);
        $("#idFlujo").val(data.idFlujo);
        $("#fechaHora").val(data.fechaHora);
        $("#saldo").val(data.saldo);
    });
}