<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/xml; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$id = mysqli_real_escape_string( $conn, $_REQUEST['id'] );

	$sql = "UPDATE historico_processo hp set hp.dtConclusao = '".date('Y-m-d H:i:s')."', hp.status = 'F' WHERE hp.id = ".$id;
	if(mysqli_query($conn, $sql)) 
		echo( json_encode( "OK" ) );
	else
		echo( json_encode( "ERRO" ) );