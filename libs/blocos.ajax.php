<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/json; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

	$idCondominio = mysqli_real_escape_string( $conn, $_REQUEST['id'] );
	$idCondominio = explode('-', $idCondominio)[0];

	$blocos = array();

	$sql = "SELECT id, 
	               descricao
			FROM bloco 
			WHERE idCondominio = ".$idCondominio;
	$res = mysqli_query($conn, $sql);
	while ( $row = mysqli_fetch_assoc( $res ) ) {
		$blocos[] = array(
			'id'	=> $row['id'],
			'descricao' => utf8_encode($row['descricao']),
		);
	}

	echo( json_encode( $blocos ) );