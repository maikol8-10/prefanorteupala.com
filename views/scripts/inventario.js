/**
 * Variables grobales
 */
let tabla;

/**
 * Init
 */
$(document).ready(function () {
    $('.selectpicker').selectpicker();
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $.post("../ajax/producto.php?op=selectProdutoWithCategoria", function (r) {
        $("#idProducto").html(r);
        $('.selectpicker').selectpicker('refresh');
    });
});

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
                    url: '../ajax/inventario.php?op=listar',
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

const guardaryeditar = (e) => {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/inventario.php?op=guardaryeditar",
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

const noAplicarStock = (idInventario) => {
    bootbox.confirm("¿Está seguro de anular el inventario?", function (result) {
        if (result) {
            $.post("../ajax/inventario.php?op=anular", {idInventario: idInventario}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

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

function limpiar() {
    $("#idProducto").val("");
    $("#codigo").val("");
    $("#descripcion").val("");
    $("#precio").val("");
    $("#idProducto").selectpicker('refresh');
    $("#print").hide();

    //Obtenemos la fecha actual
    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha').val(today);

}