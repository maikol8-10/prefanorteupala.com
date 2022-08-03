/**
 * Funcion que permite trnasformar un numero decimal o int al formato de moneda nacional
 * @param a
 * @returns {string}
 */
function decimalSeparator(a) {
    let auxA = myRound(a)
    //return Number.parseFloat(auxA).toLocaleString("en-US", {minimumFractionDigits: 2});
    //return Number.parseFloat(auxA).toLocaleString("en-US", {minimumFractionDigits: 2});
    return Number.parseFloat(auxA).toFixed(2);
}

/**
 * Funcion que permite obtener un número decimal tal y como es sin redondearlo
 * @param num Numero decimal
 * @param dec Cantidad de decimales a mostrar
 * @returns {number}
 */
function myRound(num, dec) {
    let exp = Math.pow(10, dec || 2); // 2 decimales por defecto
    return parseInt(num * exp, 10) / exp;
}