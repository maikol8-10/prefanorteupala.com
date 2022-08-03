<?php

require_once "../modelo/Categoria.php";

$categoria = new Categoria();

$idCategoria = isset($_POST["idCategoria"]) ? limpiarCadena($_POST["idCategoria"]) : "";
$categoria = isset($_POST["categoria"]) ? limpiarCadena($_POST["categoria"]) : "";

switch ($_GET["op"]) {

}
