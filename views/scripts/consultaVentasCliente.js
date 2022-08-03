let tabla;

/**
 * Init
 */
$(document).ready(function () {
    listarVentasCliente();
    //Cargamos los items al select cliente
    $.post("../ajax/venta.php?op=selectClienteTodos", function (r) {
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');
    });
});


//Función Listar
function listarVentasCliente() {
    let idcliente = $("#idcliente").val();
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
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
                    url: '../ajax/consultas.php?op=ventasPorCliente',
                    data: {idcliente: idcliente},
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