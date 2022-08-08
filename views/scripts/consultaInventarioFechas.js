let tabla;

/**
 * Init
 */
$(document).ready(function () {
    setToday();
    listarInventariosFechas();
});


function listarInventariosFechas() {
    let fecha_inicio = $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val();
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
                    url: '../ajax/consultas.php?op=inventariosPorFechas',
                    data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
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
    mostrarTotalVentasPorFechas();
};

function mostrarTotalVentasPorFechas() {
    let fecha_inicio = $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val();
    $.ajax({
        url: '../ajax/consultas.php?op=totalInventarioPorFechas',
        type: 'POST',
        dataType: 'html',
        data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
    })
        .done(function (respuesta) {
            $("#total").text("₡" + respuesta);
        })
        .fail(function () {
            console.log("error");
        })
}

function setToday() {
    //Obtenemos la fecha actual
    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#fecha_inicio").val(today);
    $("#fecha_fin").val(today);
}
