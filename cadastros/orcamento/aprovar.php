<?php
$id = $_GET["id"];

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$sql = "update orcamento set status = 'A'
		where id = ".$id;

mysqli_query($conn, $sql) or die(mysqli_error($conn));
$sql = "INSERT INTO processo (idOrcamento, dtInicio, status) values (".$id.", '".date('Y-m-d')."', 'I')";
		
mysqli_query($conn, $sql) or die(mysqli_error());
$idProcesso = mysqli_insert_id($conn);
session_start();

$sql2 = "INSERT INTO historico_processo (idProcesso, status, idUsuAlteracao, dtAlteracao, observacao, dtPrev) values ('".$idProcesso."', 'I', '".$_SESSION['id']."', '".date('Y-m-d H:i:s')."', 'PROCESSO INICIADO', ".date('Y-m-d').")";
mysqli_query($conn, $sql2) or die(mysqli_error($conn));

echo "<script>alert('Orçamento aprovado com sucesso!');window.location.href='/sgpi/cadastros/orcamento';</script>";

?>