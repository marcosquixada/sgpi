<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/xml; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

	$cpf = mysqli_real_escape_string( $conn, $_REQUEST['cpf'] );
	$cnpj = mysqli_real_escape_string( $conn, $_REQUEST['cnpj'] );
	$tipo = mysqli_real_escape_string( $conn, $_REQUEST['tipo'] );
	
	$sql = null;
	$result = null;
	
	if($cpf !== ''){
		$sql = "SELECT nome 
				FROM usuario u
				WHERE u.cpf='".$cpf."'
				  AND u.tipo = '".$tipo."'";
				
		$res = mysqli_query($conn, $sql);
	
		while ( $row = mysqli_fetch_assoc( $res ) ) {
			$result = $row['nome'];
		}
	}else{
		$sql = "SELECT razaoSocial
				FROM usuario u
				WHERE u.cnpj='".$cnpj."'";
				
		$res = mysqli_query($conn, $sql);
	
		while ( $row = mysqli_fetch_assoc( $res ) ) {
			$result = $row['razaoSocial'];
		}
	}			

	echo( json_encode( $result ) );