<?php
$id = $_GET["id"];

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
 
$sql = "update usuario set ativo = '0'
        where id = ".$id;
 
if(mysqli_query($conn, $sql)){
	echo "<script>alert('Atendente inativado com sucesso!');window.location.href='/sgpi/cadastros/atendente';</script>";
}else{
	echo "<script>alert('Erro ao inativar!');</script>";
}
 
?>