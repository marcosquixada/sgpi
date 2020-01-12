<?php
	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/json; charset="utf-8"', true );

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

	$id_bloco = mysqli_real_escape_string( $conn, $_REQUEST['id'] );

	$complementosIn = array();
	$complementosOut = array();

	$sql = "SELECT b.andares, b.unids_por_andar, b.possuiTerreo
				   FROM bloco b
			WHERE b.id = ".$id_bloco;
	$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	
	while ( $row = mysqli_fetch_row( $res ) ) {
		for($i = 1; $i <= $row[0]; $i++){
			for($j = 1; $j <= $row[1]; $j++){
				if($row[2] === 'N')
					$complementosIn[] = ($i * (100) + $j);
				else
					$complementosIn[] = str_pad((($i - 1) * 100) + $j, 3, '0', STR_PAD_LEFT);
			}
		}
	}
	
	$sql = "SELECT complemento from imovel i where i.idBloco = ".$id_bloco;
	$res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	while ( $row = mysqli_fetch_row( $res ) ) {
		$complementosOut[] = $row[0];
	}
	
	$complementosIn = array_slice(array_diff($complementosIn, $complementosOut), 0);
	
	echo( json_encode( $complementosIn ) );