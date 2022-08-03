/**
 * @source ./views/scripts/venta.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion de ventas
 */

/**
 * Variables grobales
 */
let tablaApartados;

/**
 * Init
 */
$(document).ready(function () {
    listarApartados();
    $("#btnVerVentas").hide();
    $("#listadoregistrosApartados").hide();
});

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar las ventas registradas mediante una operación AJAX
 */
const listarApartados = () => {
   /* tablaApartados = $('#tbllistadoApartados').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tablaApartados
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":
                {
                    url: '../ajax/venta.php?op=listarApartados',
                    type: "get",
                    dataType: "json",
                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();*/
}

/**
 * Permite anular un apartado mediante una operación AJAX
 * @param idVenta
 */
const anularApartado = (idVenta) => {
    bootbox.confirm("¿Está seguro de anular el apartado?", function (result) {
        if (result) {
            $.post("../ajax/venta.php?op=anularApartado", {idVenta: idVenta}, function (e) {
                bootbox.alert(e);
                tablaApartados.ajax.reload();
                mostrarSaldoFlujo();
            });
        }
    })
}

const finalizarApartado = (idVenta) => {
    $.post("../ajax/venta.php?op=finalizarApartado", {idVenta: idVenta}, function (e) {
        bootbox.alert(e);
        tablaApartados.ajax.reload();
        mostrarSaldoFlujo();
    });
}

function verApartados() {
    $("#btnVerVentas").show();
    $("#listadoregistrosApartados").show();
    $("#btnVerApartados").hide();
    $("#listadoregistros").hide();
    $("#tittleVA").text('Apartados');
    listarApartados();
}

