<?php
	
	include_once "conexao.php";

	//CONVERTER A DATA DO PADRAO BRASILEIRO PARA O FORMATO DO BANCO DE DADOS
	$start_date = str_replace('/', '-', $_POST["inicio"]);
	$converted_start_date = date("Y-m-d H:i:s", strtotime($start_date));
	$end_date = str_replace('/', '-', $_POST['fim']);
	$converted_end_date = date("Y-m-d H:i:s", strtotime($end_date));

	// $inicio = date('Y/m/d H:i:s',strtotime($_POST["inicio"]));
	// $fim = date('Y/m/d H:i:s',strtotime($_POST["fim"]));
	$id = $_POST["id"];

	$queryEdita = "UPDATE tb06_eventos SET tb06_inicio = '$converted_start_date', tb06_fim = '$converted_end_date' WHERE tb06_idEvento ='$id'";
	$resultadoEdita = mysqli_query($conexao, $queryEdita);    

?>
