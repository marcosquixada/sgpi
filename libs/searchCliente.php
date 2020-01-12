<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/json; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$nome = mysqli_real_escape_string( $conn, $_REQUEST['nome'] );
	
	$nomes = array();

	$sql = "SELECT id, nome FROM usuario where upper(nome) like '%".strtoupper($nome)."%'";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_assoc($result)) {
		$nomes[] = array(
			'id'	=> $row['id'],
			'nome' => $row['nome']
		);
	}

	echo json_encode($nomes);
?>