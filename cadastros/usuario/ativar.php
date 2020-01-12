<?php

// Novamente, nao irei fazer nenhum tipo de checagem para validar os dados
// em busca de SQL Injection ou coisas do genero. Nao se esqueca voce de fazer
// isso.

// Conectar no banco de dados
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

// Dados vindos pela url
$id = $_GET['id'];
$emailMd5 = $_GET['email'];
$uidMd5 = $_GET['uid'];
$dataMd5 = $_GET['key'];

//Buscar os dados no banco
$sql = "select email, uid, data_ts from usuario where id = ".$id;
$rs = mysqli_query($conn, $sql);
if($rs){
	$rs = mysqli_fetch_array( $rs );
} else {
	echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

// Comparar os dados que pegamos no banco, com os dados vindos pela url
$valido = true;

if( $emailMd5 !== md5( $rs['email'] ) ){
	$valido = false;
	echo "email";
}    

if( $uidMd5 !== md5( $rs['uid'] ) ){
    $valido = false;
	echo "uid";
}

if( $dataMd5 !== md5( $rs['data_ts'] ) ){
    $valido = false;
	echo "data";
}

// Os dados estão corretos, hora de ativar o cadastro
if( $valido === true ) {
    $sql = "update usuario set ativo='1' where id = ".$id;
    if(mysqli_query($conn, $sql)){
		header("Location: cadastrar_senha.php?id=".$id);
	} else {
		echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
	}
    //echo "Cadastro ativado com sucesso!";
 
} else {
    echo "Informacoes invalidas";
}
?>