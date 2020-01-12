<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/json', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$cod_estados = $_REQUEST['cod_estados'];
	
	$cidades = array();

	$sql = "SELECT c.cod_cidades, c.nome
			FROM estados e, cidades c
			WHERE e.sigla = '".$cod_estados."'
			  AND c.estados_cod_estados = e.cod_estados
			ORDER BY c.nome";
	$res = mysqli_query($conn, $sql);
	while ( $row = mysqli_fetch_assoc( $res ) ) {
		$cidades[] = array(
			'cod_cidades'	=> $row['cod_cidades'],
			'nome'			=> $row['nome'],
		);
	}

	echo( json_encode( $cidades ) );