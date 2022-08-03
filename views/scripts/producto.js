/**
 * @source ./views/scripts/producto.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion de prendas
 */

/**
 * Variables grobales
 */
let tabla;

/**
 * Init
 */
$(document).ready(function () {
    obtenerProductos();
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $.post("../ajax/producto.php?op=selectCategoriaTodos", function (r) {
        $("#idCategoria").html(r);
        $('.selectpicker').selectpicker('refresh');
    });
});

/*********************************FUNCIONES PARA EL MANEJO DEL DOM *********************************/
/**
 * Permite inicializar los elementos del form
 */
function limpiar() {
    $("#idProducto").val("");
    $("#codigo").val("");
    $("#descripcion").val("");
    $("#precio").val("");
    $("#print").hide();

}

/**
 * Permite mostrar el form para agregar un nuevo cliente
 * @param flag
 */
function mostrarForm(flag) {
    limpiar();
    if (flag) {
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
};

/**
 * Permite ocultar el form
 */
function cancelarForm() {
    limpiar();
    mostrarForm(false);
};

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar las prendas registradas mediante una operación AJAX
 */
const listar = () => {
    tabla = $('#tablaListado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del dataTables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [
                'excelHtml5',
                'pdf'
            ],
            "ajax":
                {
                    url: '../ajax/producto.php?op=listar',
                    type: "get",
                    dataType: "json",
                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
};

/**
 * Permite guardar o editar una prenda mediante una operación AJAX
 * @param e
 */
const guardaryeditar = (e) => {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/producto.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert({
                message: datos,
                //size: 'large',
            })
            mostrarForm(false);
            tabla.ajax.reload();
        }

    });
    limpiar();
};

/**
 * Permite mostrar la prenda para editarla
 * @param idProducto
 */
const mostrar = (idProducto) => {
    console.log(idProducto);
    $.post("../ajax/producto.php?op=mostrar", {idProducto: idProducto}, function (data, status) {
        data = JSON.parse(data);
        mostrarForm(true);
        $("#idProducto").val(data.idProducto);
        $("#codigo").val(data.codigo);
        $("#descripcion").val(data.descripcion);
        $("#precio").val(data.precio);
        generarbarcode();
    })
};

/**
 * Permite cambiar el estado de una prenda a desactivada
 * @param idProducto
 */
const desactivar = (idProducto) => {
    bootbox.confirm({
        size: "small",
        message: "¿Está seguro de desactivar la prenda?",
        callback: function (result) {
            if (result) {
                $.post("../ajax/producto.php?op=desactivar", {idProducto: idProducto}, function (e) {
                    bootbox.alert({
                        message: e,
                        size: 'small',
                    })
                    tabla.ajax.reload();
                });
            }/* result is a boolean; true = OK, false = Cancel*/
        }
    })
}

/**
 * Permite cambiar el estado de una prenda a activada
 * @param idProducto
 */
const activar = (idProducto) => {
    bootbox.confirm({
        size: "small",
        message: "¿Está seguro de activar la prenda?",
        callback: function (result) {
            if (result) {
                $.post("../ajax/producto.php?op=activar", {idProducto: idProducto}, function (e) {
                    bootbox.alert({
                        message: e,
                        size: 'small',
                    })
                    tabla.ajax.reload();
                });
            }/* result is a boolean; true = OK, false = Cancel*/
        }
    })
};

/**
 * Permite generar un codigo de barras para las prendas
 */
const generarbarcode = () => {
    let codigo = $("#codigo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}

/**
 * Permite imprimir el codigo de barras
 */
function imprimir() {
    $("#print").printArea();
}
