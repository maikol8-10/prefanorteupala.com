<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['ventas'] == 1) {
        include "fpdf/fpdf.php";
        require_once "../modelo/Venta.php";

        $venta = new Venta();


        $pdf = new FPDF($orientation = 'P', $unit = 'mm', array(45, 350));
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 7);    //Letra Arial, negrita (Bold), tam. 20
        $textypos = 5;
        $pdf->setY(2);
        $pdf->setX(5);
        $pdf->Cell(5, $textypos, "Steph Boutique Americana", "", "", "L");

        $pdf->SetFont('Arial', 'B', 5);    //Letra Arial, negrita (Bold), tam. 20
        $textypos += 6;
        $pdf->setY(2);
        $pdf->setX(3);
        $pdf->Cell(5, $textypos, "Upala, Frente a Auto Respuesto Chambero", "", "", "L");

        $pdf->SetFont('Arial', 'B', 5);    //Letra Arial, negrita (Bold), tam. 20
        $textypos += 6;
        $pdf->setY(2);
        $pdf->setX(12);
        $pdf->Cell(5, $textypos, "Telefono: +506 71571294", "", "", "L");

        $pdf->SetFont('Arial', '', 5);    //Letra Arial, negrita (Bold), tam. 20
        $textypos += 6;
        $pdf->setX(2);
        $pdf->Cell(5, $textypos, '-------------------------------------------------------------------');
        $textypos += 6;
        $pdf->setX(2);
        $pdf->Cell(5, $textypos, 'CANT.  ARTICULO       PRECIO               TOTAL');

        $total = 0;
        $off = $textypos + 6;
        $producto = array(
            "q" => 1,
            "name" => "Computadora Lenovo i5",
            "price" => 100
        );
        $productos = array($producto, $producto, $producto, $producto, $producto);
        foreach ($productos as $pro) {
            $pdf->setX(2);
            $pdf->Cell(5, $off, $pro["q"]);
            $pdf->setX(6);
            $pdf->Cell(35, $off, strtoupper(substr($pro["name"], 0, 12)));
            $pdf->setX(20);
            $pdf->Cell(11, $off, "$" . number_format($pro["price"], 2, ".", ","), 0, 0, "R");
            $pdf->setX(32);
            $pdf->Cell(11, $off, "$ " . number_format($pro["q"] * $pro["price"], 2, ".", ","), 0, 0, "R");

            $total += $pro["q"] * $pro["price"];
            $off += 6;
        }
        $textypos = $off + 6;

        $pdf->setX(2);
        $pdf->Cell(5, $textypos, "TOTAL: ");
        $pdf->setX(38);
        $pdf->Cell(5, $textypos, "$ " . number_format($total, 2, ".", ","), 0, 0, "R");

        $pdf->setX(2);
        $pdf->Cell(5, $textypos + 6, 'GRACIAS POR TU COMPRA ');

        $pdf->output();
    }
}

