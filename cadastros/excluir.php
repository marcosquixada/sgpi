<?php
$id = $_GET['id'];
$modelo = $_GET['modelo'];
$table = $_GET['table'];
	
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

session_start();
$idUsuario = $_SESSION['id'];
$perfil = null;

$result = mysqli_query($conn, "select tipo from usuario where id = ".$idUsuario);
while($row = mysqli_fetch_row($result))
	$perfil = $row[0];

if($perfil === 'A'){
	$sql = "update ".$table." set dt_exclusao = '".date('Y-m-d H:i:s')."' where id = ".$id;
	mysqli_query($conn, $sql) or die(mysqli_error($conn));
	echo "<script>alert('Deletado com sucesso!');</script>";
	
	echo "<script>window.location.href='/sgpi/cadastros/$modelo';</script>";  
} else {
	echo "<script>alert('Você não tem permissão para executar esta ação!');</script>";
}
 
?>