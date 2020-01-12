<?php

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

//id do orçamento que está querendo ser aprovado.
$idOrcamento = mysqli_real_escape_string( $conn, $_REQUEST['idOrcamento'] );

//id do orçamento do processo já existente.
$idOrcTransf = '';
$sql = "select p.idOrcamento 
		from processo p, 
			 orcamento o1, 
			 orcamento o2
		where p.idOrcamento = o1.id 
		  and o1.status = 'A'
		  and p.dtConclusao is null 
		  and o2.idCliente = o1.idCliente 
		  and o2.id = ".$idOrcamento;
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
while($row = mysqli_fetch_row($result))
	$idOrcTransf = $row[0];

//obter imóveis do orcamento que está querendo ser aprovado.
$sqlImoveis = " select distinct os.idImovel
				from orcamento_servico os
				where os.idOrcamento = ".$idOrcamento;
$resultImoveis = mysqli_query($conn, $sqlImoveis) or die(mysqli_error($conn));
$arrayImoveis = [];
while($row = mysqli_fetch_row($resultImoveis))
	$arrayImoveis[] = $row[0];

//print_r($arrayImoveis);

for($i = 0; $i < count($arrayImoveis); $i++) {
	//Para cada imóvel, obter id dos serviços.
	$sqlServs = "select os.idServico
				 from orcamento_servico os
				 where os.idOrcamento = ".$idOrcamento."
				   and os.idImovel = ".$arrayImoveis[$i];
	$resultServs = mysqli_query($conn, $sqlServs) or die(mysqli_error($conn));
	$arrayServs = [];
	while($row = mysqli_fetch_row( $resultServs ))
		$arrayServs[] = $row[0];
	
	for($j = 0; $j < count($arrayServs); $j++) {
		$sql = "update orcamento_servico set idOrcamento = ".$idOrcTransf." where idOrcamento = ".$idOrcamento;
		
		mysqli_query($conn, $sql);
			
		$sql = "update orcamento set status = 'R'
				where id = ".$idOrcamento;

		mysqli_query($conn, $sql) or die(mysqli_error($conn));
	}
}
echo "<script>alert('Serviços vinculados com sucesso!');window.location.href='/sgpi/cadastros/orcamento';</script>";