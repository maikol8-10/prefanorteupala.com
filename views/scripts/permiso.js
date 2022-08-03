/**
 * @source ./views/scripts/permiso.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la visualización de los permisos
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
});

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar los permisos existentes mediante una operación AJAX
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
                    url: '../ajax/permiso.php?op=listar',
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
};