<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/xml; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$tiposRenda = array();

	$sql = "SELECT id, descricao FROM tipo_renda";
	$res = mysqli_query($conn, $sql);
	while ( $row = mysqli_fetch_assoc( $res ) ) {
		$tiposRenda[] = array(
			'id' => $row['id'],
			'descricao' => utf8_encode($row['descricao']),
		);
	}

	echo( json_encode( $tiposRenda ) );