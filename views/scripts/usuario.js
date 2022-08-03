/**
 * @source ./views/scripts/usuario.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion de usuarios
 */

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
    //Mostramos los permisos
    $.post("../ajax/usuario.php?op=permisos&id=", function (r) {
        $("#permisos").html(r);
    });
});

/*********************************FUNCIONES PARA EL MANEJO DEL DOM *********************************/
/**
 * Permite inicializar los elementos del form
 */
function limpiar() {
    $("#nombre").val("");
    $("#apellido").val("");
    $("#usuario").val("");
    $("#contrasena").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#idUsuario").val("");
    $("#cargo").val("Administrador");
    $("#cargo").selectpicker('refresh');
}

/**
 * Permite mostrar el form para agregar un nuevo cliente
 * @param flag
 */
function mostrarForm(flag) {
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
    }
}

/**
 * Permite ocultar el form
 */
function cancelarform() {
    limpiar();
    mostrarForm(false);
}

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar los usuarios registrados mediante una operación AJAX
 */
const listar = () => {
    tabla = $('#tbllistado').dataTable(
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
                    url: '../ajax/usuario.php?op=listar',
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
 * Permite guardar o editar un usuario mediante una operación AJAX
 * @param e
 */
const guardaryeditar = (e) => {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
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
            tabla.ajax.reload();
        }
    });
    limpiar();
};

/**
 * Permite mostrar un usuarios para editarlo mediante una operacion AJAX
 * @param idUsuario
 */
const mostrar = (idUsuario) => {
    $.post("../ajax/usuario.php?op=mostrar", {idUsuario: idUsuario}, function (data, status) {
        data = JSON.parse(data);
        mostrarForm(true);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#cargo").val(data.cargo);
        $("#cargo").selectpicker('refresh');
        $("#usuario").val(data.usuario);
        $("#idUsuario").val(data.idUsuario);

    });
    $.post("../ajax/usuario.php?op=permisos&id=" + idUsuario, function (r) {
        $("#permisos").html(r);
    });
}

function decryptData(encrypted, iv, key) {
    let decrypted = CryptoJS.AES.decrypt(encrypted, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });
    return decrypted.toString(CryptoJS.enc.Utf8)
}


/**
 * Permite desactivar un usuario para que no pueda ingresar al sistema
 * @param idUsuario
 */
const desactivar = (idUsuario) => {
    bootbox.confirm({
        size: "small",
        message: "¿Está seguro de desactivar el usuario?",
        callback: function (result) {
            if (result) {
                $.post("../ajax/usuario.php?op=desactivar", {idUsuario: idUsuario}, function (e) {
                    bootbox.alert({
                        message: e,
                        size: 'small',
                    })
                    tabla.ajax.reload();
                });
            }/* result is a boolean; true = OK, false = Cancel*/
        }
    });
};

/**
 * Permite activar un usuario para que pueda ingresar al sistema
 * @param idUsuario
 */
const activar = (idUsuario) => {
    bootbox.confirm({
        size: "small",
        message: "¿Está seguro de activar el Usuario?",
        callback: function (result) {
            if (result) {
                $.post("../ajax/usuario.php?op=activar", {idUsuario: idUsuario}, function (e) {
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