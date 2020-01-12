<?php
$id = $_GET["id"];

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
 
$sql = "update processo set status = 'F', dtConclusao = '".date('Y-m-d H:i:s')."'
        where id = ".$id;
 
if(mysqli_query($conn, $sql)){
	echo "<script>alert('Processo finalizado com sucesso!');window.location.href='/sgpi/cadastros/processo';</script>";
}else{
	echo "<script>alert('Erro ao finalizar!');</script>";
}
 
?>