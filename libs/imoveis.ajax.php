<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/json; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

	$id_proprietario = mysqli_real_escape_string( $conn, $_REQUEST['id'] );

	$imoveis = array();

	$sql = "SELECT date_format(i.data_cadastro, '%d/%m/%Y') as data, 
						  i.id as id, 
						  c.descricao as condominio,
						  b.descricao as bloco,
						  CASE i.cep WHEN '' THEN c.cep ELSE i.cep END, 
						  CASE i.estado WHEN '' THEN c.estado ELSE i.estado END, 
						  CASE i.cidade WHEN '' THEN c.cidade ELSE i.cidade END, 
						  CASE i.bairro WHEN '' THEN c.bairro ELSE i.bairro END, 
						  CASE i.logradouro WHEN '' THEN c.logradouro ELSE i.logradouro END, 
						  CASE i.numero WHEN '' THEN c.numero ELSE i.numero END, 
						  i.complemento as complemento
				   FROM imovel i
				   INNER JOIN imovel_cliente ic 
				        ON ic.idImovel = i.id
				   LEFT JOIN condominio c
						ON i.idCondominio = c.id
				   LEFT JOIN bloco b
				        ON i.idBloco = b.id
			WHERE ic.idCliente = ".$id_proprietario;
	$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	while ( $row = mysqli_fetch_row( $res ) ) {
		$imoveis[] = array(
			'data_cadastro'	=> $row[0],
			'id'	=> $row[1],
			'condominio'	=> $row[2],
			'bloco'	=> $row[3],
			'cep'	=> $row[4],
			'estado' => $row[5],
			'cidade' => $row[6],
			'bairro' => $row[7],
			'logradouro' => $row[8],
			'numero' => $row[9],
			'complemento' => $row[10]
		);
	}

	echo( json_encode( $imoveis ) );