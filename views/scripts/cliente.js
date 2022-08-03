/**
 * @source ./views/scripts/cliente.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion clientes
 */

/**
 * Variables grobales
 */
let tabla;


/**
 * Init
 */
$(document).ready(function () {
    let urlParam = getParameterByName('op');
    if (urlParam == "registrar") { //Si es la configuracion de logos
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        mostrarform(false);
    }
    listar();
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
});

/*********************************FUNCIONES PARA EL MANEJO DEL DOM *********************************/
/**
 * Permite inicializar los elementos del form
 */
function limpiar() {
    $("#idCliente").val("");
    $("#identificacion").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#telefono").val("");
    $("#direccion").val("");

}

/**
 * Permite mostrar el form para agregar un nuevo cliente
 * @param flag
 */
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        listar();
    }
}

/**
 * Permite ocultar el form
 */
function cancelarform() {
    limpiar();
    mostrarform(false);
}

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar los clientes registrados mediante una operación AJAX
 */
const listar = () => {
    tabla = $('#tbllistado').dataTable(
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
                    url: '../ajax/cliente.php?op=listar',
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
 * Permite guardar o editar un cliente mediante una operación AJAX
 * @param e
 */
const guardaryeditar = (e) => {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/cliente.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert({
                message: datos,
                size: 'small',
            })
            mostrarform(false);
            tabla.ajax.reload();
        }
    });
    limpiar();
}

/**
 * Permite mostrar el cliente para editarlo
 * @param idCliente
 */
const mostrar = (idCliente) => {
    $.post("../ajax/cliente.php?op=mostrar", {idCliente: idCliente}, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        $("#identificacion").val(data.identificacion);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#telefono").val(data.telefono);
        $("#direccion").val(data.direccion);
        $("#idCliente").val(data.idCliente);
    })
};

/**
 * Permite obtener parametros por url
 * @param name
 * @return {string|string}
 */
function getParameterByName(op) {
    op = op.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    let regex = new RegExp("[\\?&]" + op + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};
