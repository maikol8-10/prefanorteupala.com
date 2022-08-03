$(document).ready(function () {
    mostrarSaldoFlujo();
    let setting = getParameterByName('view');
    if (setting == "apartados") { //Si es la configuracion de logos
        verApartados();
    } else if (setting == "ventas") {
        verVentas();
    }
});

/**
 * Permite mostrar el flujo del saldo disponible por el usuario logueado
 */
const mostrarSaldoFlujo = () => {
    $('#contentSaldo').empty();
    let array = [], json = [], flujoSaldo;
    $.post("../ajax/venta.php?op=mostrarSaldoFlujo", function (r) {
        try {
            json = r;
            if (json != "") {
                array = JSON.parse(json);
            }
            if (array.length === 0 || array.data[1] === "cerrada") {
                $("#contentSaldo").removeClass("box-tools");
                $("#contentAdd").hide();
                flujoSaldo = "<h1 class=\"box-title\" style=\"font-size: 22px; font-weight: bold; color: #dd4b39;\">Sin flujo de caja abierta</h1>";
            } else {
                //for (let item = 0; item <= array.data.length; item++) {
                //if (item == 0 )
                saldo = array.data[0];
                if (array.data[0] > 0 && array.data[1] === "abierta" || array.data[0] <= 0 && array.data[1] === "abierta") {
                    $("#contentAdd").show();
                    $("#contentSaldo").addClass("box-tools");
                    flujoSaldo = "<h1 class=\"box-title\" style=\"font-size: 22px; font-weight: bold;\">Flujo de caja: </h1>"
                    flujoSaldo += "<h1 class=\"box-title\" style=\"font-size: 22px; font-weight: bold; color:#00a65a;\">" + "₡" + array.data[0] + "</h1>";
                } else {
                    $("#contentSaldo").removeClass("box-tools");
                    $("#contentAdd").hide();
                    flujoSaldo = "<h1 class=\"box-title\" style=\"font-size: 22px; font-weight: bold; color: #dd4b39;\">Sin flujo de caja abierta</h1>";
                }
            }
            $("#contentSaldo").append(flujoSaldo);
        } catch (e) {
            console.log(e);
        }
    });
}

/**
 * Permite obtener parametros por url
 * @param name
 * @return {string|string}
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    let regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};
