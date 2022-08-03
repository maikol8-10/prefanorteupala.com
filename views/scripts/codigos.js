/**
 * @source ./views/scripts/producto.js
 * @author MyTek Technology
 * @since noviembre, 2021
 * @description Este Script se encarga del funcionamiento para listar y poder imprimir los códigos de barras de las prendas
 */

/**
 * Variables grobales
 */
let codigos = [];
/**
 * Init
 */
$(document).ready(async function () {

});

const generarbarcodes = (prenda, codigo) => {
    JsBarcode("#barcode-" + prenda, codigo);
    $("#print-" + prenda).show();
}


function renderCodigos(data) {
    let prendas = reformattedArray(data.data);
    $("#listaCodigos").empty();
    for (let pren = 0; pren < prendas.length; pren++) {
        let html = '<div id="print-' + pren + '" class="content-barcode">\n' +
            '<svg id="barcode-' + pren + '" class="barcode"></svg>\n' +
            '<div class="info-prenda">' +
            '<h4 id="name-' + pren + '">' + prendas[pren][1] + '</h4>\n' +
            '<h5 id="name-' + pren + '">₡' + prendas[pren][2] + '</h5>\n' +
            '</div>' +
            '</div>'
        $("#listaCodigos").append(html);
        generarbarcodes(pren, prendas[pren][3]);
    }
}

function reformattedArray(data) {
    let reformattedArray = data.map(function (arr) {
        let obj = arr.reduce(function (acc, cur, i) {
            acc[i] = cur;
            return acc;
        }, {});
        return obj;
    });
    return reformattedArray;
}

async function obtenerProductos() {
    $.get("../ajax/producto.php?op=listarCodigos", {}, function (data, status) {
        data = JSON.parse(data);
        renderCodigos(data);
    });
};

function printAll() {
    $("#listaCodigos").printArea();
}