<?php
$id = $_GET["id"];

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
 
$sql = "update orcamento set status = 'R'
        where id = ".$id;
 
if(mysqli_query($conn, $sql)){
	$msg = "Orçamento reprovado com sucesso!";
	echo "<script>alert('Orçamento reprovado com sucesso!');window.location.href='/sgpi/cadastros/orcamento';</script>";
}else{
	$msg = "Erro ao reprovar!";
}
 
?>