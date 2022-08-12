/**
 * @source ./views/scripts/venta.js
 * @author MyTek Technology
 * @since febrero, 2020
 * @description Este Script se encarga del funcionamiento para la administracion de ventas
 */

/**
 * Variables grobales
 */
let tabla, flagEfectivo, flagApartado = false, saldo = 0, vuelto = 0, totalFinal = 0.0, cont = 0, detalles = 0,
    flagVentaEfectivo;

let productos = [];
/**
 * Init
 */
$(document).ready(function () {
    $('.selectpicker').selectpicker();
    mostrarform(false);
    listar();
    $.post("../ajax/venta.php?op=selectClienteTodos", function (r) {
        $("#idCliente").html(r);
        $('.selectpicker').selectpicker('refresh');
    });
    $("#btnGuardar").hide();
});

/*********************************FUNCIONES PARA EL MANEJO DEL DOM *********************************/

/**
 * Permite inicializar los elementos del form
 */
function limpiar() {
    // $("#serie_comprobante").val("");
    $("#numeroComprobante").val("");
    //$("#impuesto").val("0");
    $("#totalVenta").val("");
    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fechaHora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipoComprobante").val("Ticket");
    $("#tipoPago").val("SinpeMovil");
    $("#tipoPago").selectpicker('refresh');
    $("#tipoComprobante").selectpicker('refresh');
    $("#idCliente").selectpicker('refresh');
    $("#idVenta").val("");

}

/**
 * Permite mostrar el form para agregar un nuevo cliente
 * @param flag
 */
async function mostrarform(flag) {
    limpiar();
    if (flag) {
        document.getElementById('ingreso').focus();
        document.getElementById('ingreso').select();
        await getNumeroComprobante();
        await resetForm();
        productos = [];
    } else {
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
};

function resetForm() {
    $("#btnagregar").hide();
    $("#listadoregistros").hide();
    $("#btnCalcularTotales").hide();
    listarProductos();
    $("#goClientes").show();
    $("#pagoCliente").val(0);
    $("#btnGuardar").hide();
    $("#btnCancelar").show();
    $("#btnAgregarArt").show();
    $("#vuelto").text('₡0');
    $("#subTotalV").text('₡0');
    $("#montoIva").text('₡0');
    $("#tipoPago").val("SinpeMovil");
    $("#tipoPago").selectpicker('refresh');
    flagEfectivo = false;
    $("#tr-pago").hide();
    $("#tr-vuelto").hide();
    $("#formularioregistros").show();
    detalles = 0;
    $("#tr-subtotal").attr("hidden", false);
    $("#tr-iva").attr("hidden", false);
}

/**
 * Permite ocultar el form
 */
function cancelarform() {
    verVentas();
    limpiar();
    mostrarform(false);
}

/*********************************FUNCIONALIDAD*********************************/

/**
 * Permite listar las ventas registradas mediante una operación AJAX
 */
const listar = () => {
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
                    url: '../ajax/venta.php?op=listar',
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

/**
 * Permite listar las productos mediante una operación AJAX
 * @param
 */
const listarProductos = () => {
    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [],
            "ajax":
                {
                    url: '../ajax/venta.php?op=listarProductoVenta',
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
 * Permite guardar o editar una venta mediante una operación AJAX
 * @param e
 */
$('#btnGuardar').on("click", async function () {
    await modificarSubTotales();
    if (flagVentaEfectivo) {
        let formData = new FormData($("#formulario")[0]);
        await $.ajax({
            url: "../ajax/venta.php?op=guardaryeditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            //error: function(req, err){ console.log('my message' + err); }
            success: function (datos) {
                console.log(datos)
                Swal.fire({
                    icon: 'success',
                    title: datos,
                    showConfirmButton: false,
                    timer: 1500
                }).then(async () => {
                    await mostrarform(false);
                    verVentas();
                    await listar();
                    await redirectUltimaVenta();
                })
            }
        });
        mostrarSaldoFlujo();
        limpiar();
    }
});
/**
 * Permite mostrar una venta mediante una operacion AJAX
 * @param idVenta
 */
const mostrar = async (idVenta) => {
    await $.post("../ajax/venta.php?op=mostrar", {idVenta: idVenta}, function (data, status) {
        data = JSON.parse(data);
        $("#idCliente").val(data.idCliente);
        $("#idCliente").selectpicker('refresh');
        $("#tipoPago").val(data.tipoPago);
        $("#tipoComprobante").val(data.tipoComprobante);
        $("#tipoComprobante").selectpicker('refresh');
        $("#numeroComprobante").val(data.numeroComprobante);
        $("#fechaHora").val(data.fecha);
        $("#idVenta").val(data.idVenta);
        $("#tipoPago").val(data.tipoPago);
        $("#tipoPago").selectpicker('refresh');
        $("#btnGuardar").hide(), $("#goClientes").hide(), $("#btnCancelar").show(), $("#btnAgregarArt").hide();
        $("#btnagregar").hide(), $("#listadoregistros").hide();
        $("#formularioregistros").show(), $("#btnCalcularTotales").hide();
    });
    //Permite mostrar el detalle de la venta
    await $.post("../ajax/venta.php?op=listarDetalle&id=" + idVenta, function (r) {
        $("#detalles").html(r);
    });
}

/**
 * Permite anular una venta mediante una operación AJAX
 * @param idVenta
 */
const anular = (idVenta) => {
    bootbox.confirm("¿Está seguro de anular la venta?", function (result) {
        if (result) {
            $.post("../ajax/venta.php?op=anular", {idVenta: idVenta}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
                mostrarSaldoFlujo();
            });
        }
    })
}
const addProducto = (id, descripcion, cantidad, precio, stock) => {
    productos.push({
        id: id,
        descripcion: descripcion,
        cantidad: cantidad,
        stock: stock,
        precio: precio
    })
};

function inArray(arreglo, elemento) {
    return arreglo.indexOf(elemento) !== -1;
}

/**
 * Funcion que permite agregar un detalle a una venta
 * @param idProducto
 * @param prenda
 */
const agregarDetalle = (idProducto, descripcion, precio, stock) => {
    if (parseInt(stock) <= parseInt($("#cantidad-" + idProducto).val())) {
        Swal.fire({
            icon: 'error',
            title: 'Sin Stock disponible para el producto',
            showConfirmButton: false,
            timer: 1500
        })
    } else {
        if (productos.some(e => e.id === idProducto)) {
            let objIndex = productos.findIndex((obj => obj.id === idProducto));
            productos[objIndex].cantidad = productos[objIndex].cantidad + 1;
            //productos[objIndex].precio = parseInt(productos[objIndex].precio) + parseInt(precio);
            renderDetalles();
            console.log(true);
        } else {
            addProducto(idProducto, descripcion, 1, precio, stock);
            renderDetalles();
            console.log(false);
        }
    }
    //renderDetalles(idProducto);
    /*let cantidad = 1, descuento = 0, precioVenta = 0, subtotal = 0;
    if (idProducto != "") {
        let fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-trash"></i> Eliminar</button></td>' +
            '<td><input type="hidden" name="idProducto[]" min="1" max="999999" value="' + idProducto + '">' + prenda + '</td>' +
            '<td><input type="number" class="form-control" min="0" max="999999" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
            '<td><input type="number" class="form-control" min="0" max="999999"  name="precioVenta[]" id="precioVenta[]" value="' + precioVenta + '"></td>' +
            '<td><input type="number" class="form-control" min="0" max="999999" name="descuento[]" value="' + descuento + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + '₡' + subtotal + '</span></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        $("#btnCalcularTotales").show();
        modificarSubTotales();
    } else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }*/
}

function renderDetalles() {
    let cantidad = 1, descuento = 0, precioVenta = 0, subtotal = 0, iva = 13;
    let fila = '';
    let headTable = '<thead style="background-color:#ff3f06; color: #ffffff">\n' +
        '                                    <th>Opciones</th>\n' +
        '                                    <th>Producto</th>\n' +
        '                                    <th>Cantidad</th>\n' +
        '                                    <th>Precio Unitario</th>\n' +
        '                                    <th>IVA %</th>\n' +
        '                                    <th>Descuento %</th>\n' +
        '                                    <th>Subtotal</th>\n' +
        '                                </thead>'
    $('#detalles thead').remove();
    $('#detalles').append(headTable);
    $('#detalles tbody').empty();
    for (let prenda = 0; prenda < productos.length; prenda++) {
        fila += '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ', ' + prenda + ')"><i class="fa fa-trash"></i> Eliminar</button></td>' +
            '<td><input type="hidden" name="idProducto[]" min="1" max="999999" value="' + productos[prenda].id + '">' + productos[prenda].descripcion + '</td>' +
            '<td><input id="cantidad-' + productos[prenda].id + '" type="number" class="form-control" min="0" max="999999" onchange="validarStock(' + productos[prenda].id + ')" name="cantidad[]" id="cantidad[]" value="' + productos[prenda].cantidad + '"></td>' +
            '<td><input type="number" class="form-control" min="0" max="999999" onchange="modificarSubTotales()"  name="precioVenta[]" id="precioVenta[]" value="' + productos[prenda].precio + '"></td>' +
            '<td><input type="number" class="form-control" min="0" max="999999" onchange="modificarSubTotales()"   name="iva[]" id="iva[]" value="' + iva + '"></td>' +
            '<td><input type="number" class="form-control" min="0" max="999999" onchange="modificarSubTotales()" name="descuento[]" value="' + descuento + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + '₡' + subtotal + '</span></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
    }
    $('#detalles tbody').append(fila);
    $("#btnCalcularTotales").show();
    modificarSubTotales();
}

function validarStock(idProducto) {
    let producto = productos.filter(({id}) => id === idProducto);
    if (parseInt(producto[0].stock) <= parseInt($("#cantidad-" + idProducto).val())) {
        Swal.fire({
            icon: 'error',
            title: 'Sin Stock disponible para el producto',
            showConfirmButton: false,
            timer: 1500
        })
        $("#btnGuardar").hide()
    } else {
        $("#btnGuardar").show()
    }
    modificarSubTotales();
}

/**
 * Permite modificar los totales de las ventas
 */
const modificarSubTotales = () => {
    let cant = document.getElementsByName("cantidad[]");
    let prec = document.getElementsByName("precioVenta[]");
    let iva = document.getElementsByName("iva[]");
    let desc = document.getElementsByName("descuento[]");
    let sub = document.getElementsByName("subtotal");
    let descuentoTotal, ivaTotal, subtotalFinal = 0, ivaTotalFinal = 0;

    for (let i = 0; i < cant.length; i++) {
        let inpC = cant[i];
        let inpP = prec[i];
        let inpD = desc[i];
        let inpIva = iva[i];
        let inpS = sub[i];
        descuentoTotal = ((inpC.value * inpP.value) * (inpD.value / 100));
        ivaTotal = ((inpC.value * inpP.value) * (inpIva.value / 100))
        //inpIva.value = ((inpC.value * inpP.value) * 0.13);

        //Monto sin IVA
        subtotalFinal += (inpC.value * inpP.value) - descuentoTotal;

        //Monto con IVA
        inpS.value = (inpC.value * inpP.value) - descuentoTotal + ivaTotal;

        //IVA TOTAL
        ivaTotalFinal += ivaTotal;
        $("#montoIva").html("₡ " + decimalSeparator(ivaTotalFinal));
        $("#totalIvaFinal").val(myRound(ivaTotalFinal, 2));

        $("#subTotalV").html("₡ " + decimalSeparator(subtotalFinal));
        $("#subtotalVenta").val(myRound(subtotalFinal, 2));

        if (inpS.value !== undefined) {
            document.getElementsByName("subtotal")[i].innerHTML = '₡' + myRound(inpS.value, 2);
        }
        /*if (subtotalFinal !== undefined) {
            document.getElementsByName("iva")[i].innerHTML = inpIva.value;
        }*/
    }
    calcularTotales();
}

/**
 * Permite realizar los canculos finales de las ventas
 */
const calcularTotales = () => {
    let sub = document.getElementsByName("subtotal");
    let totalTransporte = $("#totalTransporte").val();
    let subtotalVenta = 0.0;
    for (let i = 0; i < sub.length; i++) {
        subtotalVenta += document.getElementsByName("subtotal")[i].value;
    }
    if (flagApartado) {
        $("#total").html("₡ " + decimalSeparator(total / 2));
        $("#totalVenta").val(total / 2);
        vuelto = $("#pagoCliente").val() - total / 2;

    } else {
        vuelto = $("#pagoCliente").val() - subtotalVenta;
        $("#total").html("₡ " + decimalSeparator(subtotalVenta + myRound(totalTransporte, 2)));
        $("#totalVenta").val(subtotalVenta + myRound(totalTransporte, 2));
    }
    if (flagEfectivo) {
        $("#vuelto").html("₡ " + decimalSeparator(vuelto));
        $("#vueltoCliente").val(vuelto);
    } else {
        $("#vueltoCliente").val(0);
        $("#pagoCliente").val(0);
    }
    totalFinal = subtotalVenta;
    evaluar();
    validarVentaEfectivo();
}

$("#totalTransporte").keypress(function () {
    calcularTotales();
});

/**
 * Permite envaluar si existen lineas en el detalle
 */
const evaluar = () => {
    if (detalles > 0) {
        $("#btnGuardar").show();
    } else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

/**
 * Permite eliminar una linea agregada en el detalle
 * @param indice
 */
const eliminarDetalle = (indice, prenda) => {
    $("#fila" + indice).remove();
    productos.splice(prenda, 1);
    console.log(productos)
    calcularTotales();
    detalles = detalles - 1;
    evaluar()
}


/**
 * Permite validar cuando se selecciona el tipo de pago "Efectivo" si el usuario posee saldo
 */
$('#tipoPago').on('change', function () {
    if (this.value == "Efectivo") {
        if (saldo <= 1000) {
            $("#tipoPago").val("SinpeMovil");
            bootbox.alert({
                message: "No tienes suficiente efectivo",
                size: 'small',
            })
        } else {
            flagEfectivo = true;
            $("#tr-pago").show();
            $("#tr-vuelto").show();
        }
    } else {
        flagEfectivo = false;
        $("#tr-pago").hide();
        $("#tr-vuelto").hide();
    }
});

const getNumeroComprobante = async () => {
    await $.post("../ajax/venta.php?op=numeroComprobante", function (r) {
        let n = r;
        ++n;
        $("#numeroComprobante").val(n);
    });
}

const redirectUltimaVenta = async () => {
    await $.post("../ajax/venta.php?op=ultimaVenta", function (r) {
        window.open("../reportes/exTicket.php?id=" + r, "Ticket", "_blank",)
    });
};

function validarVentaEfectivo() {
    let pagoCliente = $("#pagoCliente").val();
    if ($('#tipoPago').val() === 'Efectivo') {
        if (saldo < vuelto) {
            flagVentaEfectivo = false;
            bootbox.alert({
                message: "No tienes saldo para dar vuelto al cliente",
                size: 'medium',
            })
        } else if (parseInt(pagoCliente) < totalFinal) {
            flagVentaEfectivo = false;
            bootbox.alert({
                message: "El cliente está debiendo ₡" + vuelto,
                size: 'medium',
            })
        } else {
            flagVentaEfectivo = true;
        }
    } else {
        flagVentaEfectivo = true;
    }
}


$.fn.delayPasteKeyUp = function (fn, ms) {
    var timer = 0;
    $(this).on("propertychange input", function () {
        clearTimeout(timer);
        timer = setTimeout(fn, ms);
    });
};

$(document).ready(function () {
    $("#ingreso").delayPasteKeyUp(async function () {
        let codigo = $("#ingreso").val();
        await buscarProducto(codigo);
        $("#respuesta").append("Producto registrado: " + codigo + "<br>");
        $("#ingreso").val("");
    }, 200);
});

async function buscarProducto(codigo) {

    /*await $.post("../ajax/venta.php?op=buscarProducto", {codigo: codigo}, function (data, status) {
       data = JSON.parse(data);
        console.log(data)
    });*/
    $.ajax({
        url: '../ajax/venta.php?op=buscarProducto',
        method: 'post',
        data: {codigo: codigo,},
    }).done(function (data, status) {
        /* Depuramos los datos recibidos */
        /* Si no se devolvió ningún registro (false) debería hacerse algo */
        if (data === false || data === "null") {
            /* hacer algo */
            Swal.fire({
                icon: 'error',
                title: 'No se encontró el código de barras',
                showConfirmButton: false,
                timer: 1500
            })
            //return;
        } else {
            data = JSON.parse(data);
            //productos.push(data.idProducto)
            agregarDetalle(parseInt(data.idProducto), data.nombre, data.precio)
        }
        /* Agregamos una fila con los datos obtenidos */
        /* $('#registros').append($('<tr>')
             .append($('<td>').append(datos.DN))
             .append($('<td>').append(datos.model_name))
         );*/
    }).fail(function () {
        alert("Error");
    }).always(function () {
        /* Seleccionamos el texto para que se pueda sobreescribir por la siguiente lectura */
        $("input[name='codigo']").select();
    });
};

function verVentas() {
    $("#btnVerVentas").hide();
    $("#btnVerApartados").show();
    $("#listadoregistros").show();
    $("#tittleVA").text('Ventas');
}