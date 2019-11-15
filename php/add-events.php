<?php

	require "conexao.php";

	$idLogin = $_SESSION['idUsuario'];

	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	
	//CONVERTER A DATA DO PADRAO BRASILEIRO PARA O FORMATO DO BANCO DE DADOS
	$start_date = str_replace('/', '-', $dados['add-start']);
	$converted_start_date = date("Y-m-d H:i:s", strtotime($start_date));
	$end_date = str_replace('/', '-', $dados['add-end']);
	$converted_end_date = date("Y-m-d H:i:s", strtotime($end_date));

	$paciente = mysqli_real_escape_string($conexao, trim($dados['add-patient']));
	$titulo = mysqli_real_escape_string($conexao, trim($dados['add-title']));
	$descricao = mysqli_real_escape_string($conexao, trim($dados['add-description']));
	$cor = mysqli_real_escape_string($conexao, trim($dados['color']));

	$queryInsereCadastroEstoque = "INSERT INTO tb06_eventos(tb06_nome, tb06_paciente, tb06_descricao,		tb06_cor, tb06_inicio, tb06_fim, tb06_idUsuario) VALUES ('$titulo', '$paciente', 					'$descricao', '$cor', '$converted_start_date', '$converted_end_date', '$idLogin')";
	$resultadoInsereCadastroEstoque = mysqli_query($conexao, $queryInsereCadastroEstoque);	

	header("Location: ../schedule.php");

	mysqli_close($conexao);		
?>