/**
 * @source ./views/scripts/login.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento el inicio de sesión de los usuarios
 */

$("#formularioLogin").on('submit', function (e) {
    e.preventDefault();
    try {
        let usuarioAccess = $("#usuarioAccess").val(), contrasenaAccess = $("#contrasenaAccess").val();
        $.post("../ajax/usuario.php?op=verificar", {
                "usuarioAccess": usuarioAccess,
                "contrasenaAccess": contrasenaAccess
            },
            function (data) {
                if (data != "null") {
                    $(location).attr("href", "./escritorio.php");
                } else {
                    bootbox.alert({
                        className: 'rubberBand animated',
                        message: 'Usuario y/o Password incorrectos',
                        size: 'small',
                    })
                }
            });
    } catch (e) {
        console.log(e)
    }
});