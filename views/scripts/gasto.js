/**
 * @source ./views/scripts/cliente.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion gastos
 */

/**
 * Variables grobales
 */
let tabla;
let flagSaldo = true;
let saldo = 0;

/**
 * Init
 */
$(document).ready(function () {
    validarSaldo();
    mostrarForm(false);
    listar();
});

/*********************************FUNCIONES PARA EL MANEJO DEL DOM *********************************/
/**
 * Permite inicializar los elementos del form
 */
function limpiar() {
    $("#idGasto").val("");
    $("#monto").val("");
    $("#descripcion").val("");
    //Obtenemos la fecha actual
    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fechaHora').val(today);
}

/**
 * Permite mostrar el form para agregar un nuevo cliente
 * @param flag
 */
function mostrarForm(flag) {
    if (flagSaldo) {
        limpiar();
        if (flag) {
            $("#monto").val(0);
            $("#listadoregistros").hide();
            $("#formularioregistro").show();
            $("#btnGuardar").prop("disabled", false);
            $("#btnAgregar").hide();
        } else {
            $("#listadoregistros").show();
            $("#formularioregistro").hide();
            $("#btnAgregar").show();
        }
    } else {
        bootbox.alert({
            message: "No tienes un flujo de caja abierto para registrar un gasto",
            size: 'medium',
        })
    }
}

/**
 * Permite ocultar el form
 */
function cancelarForm() {
    limpiar();
    mostrarForm(false);
}

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar los clientes registrados mediante una operación AJAX
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
                    url: '../ajax/gasto.php?op=listar',
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
$('#btnGuardar').on("click", function () {
    let monto = parseInt($("#monto").val());
    let efectivo = parseInt(saldo);
    if (monto <= efectivo) {
        $("#btnGuardar").prop("disabled", true);
        var formData = new FormData($("#formulario")[0]);
        $.ajax({
            url: "../ajax/gasto.php?op=guardaryeditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (datos) {
                bootbox.alert({
                    message: datos,
                    size: 'small',
                })
                mostrarForm(false);
                limpiar();
                mostrarSaldoFlujo();
                tabla.ajax.reload();
            }
        });
    } else {
        bootbox.alert({
            message: "No tienes suficiente efectivo para registrar el gasto",
            size: 'medium',
        })

    }
});
/**
 * Permite mostrar el gasto para editarlo
 * @param idCliente
 */
const mostrar = (idGasto) => {
    $.post("../ajax/gasto.php?op=mostrar", {idGasto: idGasto}, function (data, status) {
        data = JSON.parse(data);
        mostrarForm(true);
        $("#idGasto").val(data.idGasto);
        $("#monto").val(data.monto);
        $("#fechaHora").val(data.fechaHora);
        $("#descripcion").val(data.descripcion);
    })
};

const eliminar = async (idGasto) => {
    bootbox.confirm({
        size: "small",
        message: "¿Está seguro de eliminar este gasto?",
        callback: function (result) {
            if (result) {
                $.post("../ajax/gasto.php?op=eliminar", {idGasto: idGasto}, function (e) {
                    bootbox.alert({
                        message: e,
                        size: 'small',
                    })
                    tabla.ajax.reload();
                });

            }/* result is a boolean; true = OK, false = Cancel*/
            mostrarSaldoFlujo();
        }
    });
};

const validarSaldo = () => {
    let array = [], json = [], flujoSaldo;
    $.post("../ajax/venta.php?op=mostrarSaldoFlujo", function (r) {
        json = r;
        array = JSON.parse(json);
        if (array.length === 0 || array.data[1] === "cerrada") {
            flagSaldo = false;
        } else {
            flagSaldo = true;
        }
    });
}
