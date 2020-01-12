<?php
$id = $_GET["id"];

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
session_start();
 		
$sql2 = "INSERT INTO historico_processo (idProcesso, status, idUsuAlteracao, dtAlteracao) values ('".$idProcesso."', 'F', '".$_SESSION['id']."', '".date('Y-m-d H:i:s')."')";
mysql_query($sql2) or die(mysql_error());
echo "<script>alert('".utf8_decode('Orçamento')." aprovado com sucesso!');window.location.href='/sgpi/cadastros/orcamento';</script>";
 
?>