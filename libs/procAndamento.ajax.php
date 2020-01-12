<?php
header( 'Cache-Control: no-cache' );
header( 'Content-type: application/json; charset="utf-8"', true );

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$idOrcamento = mysqli_real_escape_string( $conn, $_REQUEST['id'] );

//verifica se já existe um processo em andamento para o cliente do orçamento que está querendo ser aprovado.
$sql = "select * 
		from processo p, 
			 orcamento o1, 
			 orcamento o2
		where p.idOrcamento = o1.id 
		  and o1.status = 'A'
		  and p.dtConclusao is null 
		  and o2.idCliente = o1.idCliente 
		  and o2.id = ".$idOrcamento;
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);

if($num_rows > 0)
	echo( 1 );
else 
	echo( 0 );