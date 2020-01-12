<?php 
    if ( !empty($_POST)) {	
		include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
		include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/fpdf/fpdf.php");
		include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/fpdi/fpdi.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/GExtenso/GExtenso.php");
		require_once('mc_table.php');
        // keep track validation errors
        $clienteError = null;
		$servicoError = null;
		$valorError = null;
         
        // keep track post values
        $idCliente = $_POST['idCliente'];
		
		$idImovel = $_POST['idImovel'];
		$idServico = $_POST['idServico'];
		$valor = $_POST['valor'];
		$desconto = $_POST['desconto'];
		
		$condCom = $_POST['condCom'];
		$observacao = $_POST['observacao'];
         
        // validate input
        $valid = true;
        if ($idCliente === '-') {
            $clienteError = 'Por favor informe o cliente';
            $valid = false;
        }
		if ($idImovel === '-') {
            $imovelError = 'Por favor informe o Imóvel';
            $valid = false;
        }
		if ($idServico === '-') {
            $servicoError = 'Por favor informe o serviço';
            $valid = false;
        }
		if (empty($valor)) {
            $valorError = 'Por favor informe o valor';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			session_start();
			$sql = "INSERT INTO orcamento (idCliente, status, condCom, obs, data_cadastro) values (".$idCliente.", 'E', '".$condCom."', '".$observacao."', '".date('Y-m-d H:i:s')."')";
				
			mysqli_query($conn, $sql);
			
			$idOrcamento = mysqli_insert_id($conn);
			
			for($i = 0; $i < count($idServico); $i++) {
				$sql = "INSERT INTO orcamento_servico (idOrcamento, idImovel, idServico, valor, desconto, data_cadastro) values (".$idOrcamento.", '".$idImovel[$i]."', ".$idServico[$i].", '".str_replace(',','.', str_replace('.','', $valor[$i]))."', '".str_replace(',','.', str_replace('.','', $desconto[$i]))."', '".date('Y-m-d H:i:s')."')";
				
				mysqli_query($conn, $sql);
			}
			
			$mesAtual = null;
			
			if(date("F") === 'January')
				$mesAtual = 'Janeiro';
			if(date("F") === 'February')
				$mesAtual = 'Fevereiro';
			if(date("F") === 'March')
				$mesAtual = 'Março';
			if(date("F") === 'April')
				$mesAtual = 'Abril';
			if(date("F") === 'May')
				$mesAtual = 'Maio';
			if(date("F") === 'June')
				$mesAtual = 'Junho';
			if(date("F") === 'July')
				$mesAtual = 'Julho';
			if(date("F") === 'August')
				$mesAtual = 'Agosto';
			if(date("F") === 'September')
				$mesAtual = 'Setembro';
			if(date("F") === 'October')
				$mesAtual = 'Outubro';
			if(date("F") === 'November')
				$mesAtual = 'Novembro';
			if(date("F") === 'December')
				$mesAtual = 'Dezembro';
			
			$idCliente = $_POST['idCliente'];
			$idImovel = $_POST['idImovel'];
			$idServico = $_POST['idServico'];
			$nome = null;
			
			$totalServicos = null;
			
			$queryCliente = "select u.nome
					  from usuario u 
					  where u.id = ".$idCliente;
							 
			$result = mysqli_query($conn, $queryCliente);
			
			while($row = mysqli_fetch_row($result)){
				$nome = $row[0];
			}
			
			$query = "select sum(valor) 
			          from orcamento_servico 
					  where idOrcamento = ".$idOrcamento;
					  
			$result = mysqli_query($conn, $query) or die(mysqli_error());
			while($row = mysqli_fetch_row($result))
				$totalServicos = $row[0];

			$pdf = new PDF_MC_Table();

			$pageCount = $pdf->setSourceFile("timbrado2.pdf");
			$tplIdx = $pdf->importPage(1, '/MediaBox');

			$pdf->addPage();
			$pdf->useTemplate($tplIdx, 10, 10, 180);

			$pdf->SetFont('Arial');
			$pdf->SetTextColor(128, 128, 128);
			$pdf->SetXY(10, 50);
			$pdf->MultiCell(190, 5, utf8_decode("CREDIMÓVEL SERVIÇOS IMOBILIÁRIOS LTDA\nFONE: (85) 3052-2244 / 3052-2247\nCNPJ: 20.263.295/0001-04"), 0);
			
			$pdf->SetFont('Arial');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(10, 70);
			$pdf->MultiCell(190, 10, utf8_decode("Fortaleza-CE, ".date("d")." de ".$mesAtual." de ".date("Y")."."), 0);
			
			$pdf->SetFont('Arial','B');
			$pdf->SetXY(90, 90);
			$pdf->Write(0, utf8_decode('ORÇAMENTO'));
			$pdf->Ln(10);
			
			$pdf->Write(0, utf8_decode("À"));
			$pdf->Ln(5);
			$pdf->Write(0, $nome);
			
			$pdf->SetXY(10, 110);
			$idImovel = array_unique($idImovel);
			for($i = 0; $i < count($idImovel); $i++) {
				$logradouro = null;
				$numero = null;
				$complemento = null;
				$cidade = null;
				$estado = null;
				$bairro = null;
				
				$query = "select CASE i.cep WHEN '' THEN c.cep ELSE i.cep END, 
								  CASE i.estado WHEN '' THEN c.estado ELSE i.estado END, 
								  CASE i.cidade WHEN '' THEN c.cidade ELSE i.cidade END, 
								  CASE i.bairro WHEN '' THEN c.bairro ELSE i.bairro END, 
								  CASE i.logradouro WHEN '' THEN c.logradouro ELSE i.logradouro END, 
								  CASE i.numero WHEN '' THEN c.numero ELSE i.numero END, 
								  i.complemento
                          from imovel i 
						  LEFT JOIN condominio c
							ON i.idCondominio = c.id
						  where i.id = ".$idImovel[$i];
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_row($result)){
					$cep = $row[0];
					$estado = $row[1];
					$cidade = $row[2];
					$bairro = $row[3];
					$logradouro = $row[4];
					$numero = $row[5];
					$complemento = $row[6];
				}
			
				$pdf->SetFont('Arial','B');
				$pdf->Cell(190, 5, utf8_decode("Imóvel: ".$logradouro.", nº ".$numero." - ".$complemento." - ".utf8_encode($bairro)." - ".$cidade." - ".$estado), 0, 1, 'L');
				//$pdf->SetXY(10, $pdf->GetY()+10);
				//$pdf->SetFont('Arial','',14);
				//Table with 20 rows and 4 columns
				$pdf->SetWidths(array(80,30,30,30,30));
				$pdf->SetXY(10, $pdf->GetY()+10);
				$pdf->Row(array(utf8_decode("Serviço"),"Valor","Desc.","Total"));
				$sql = "select s.descricao, 
							   concat('R$ ', replace(replace(replace(format((os.valor), 2), '.', '|'), ',', '.'), '|', ',')), 
							   concat('R$ ', replace(replace(replace(format((os.desconto), 2), '.', '|'), ',', '.'), '|', ',')), 
							   concat('R$ ', replace(replace(replace(format((os.valor - os.desconto), 2), '.', '|'), ',', '.'), '|', ',')) as total
						from servico s, 
							 orcamento_servico os
						where s.id = os.idServico 
						  and os.idImovel = ".$idImovel[$i]."
						  and os.idOrcamento = '".$idOrcamento."'";
					
				$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
				while($row = mysqli_fetch_row($result)){
					$pdf->SetX(10);
					$pdf->Row(array(utf8_decode($row[0]),$row[1],$row[2],$row[3]));
				}

				$totalV = 0;
				$totalD = 0;
				$total = 0;
				$sql = "select os.idOrcamento, 
							   concat('R$ ', replace(replace(replace(format((sum(os.valor)), 2), '.', '|'), ',', '.'), '|', ',')) as totalV, 
							   concat('R$ ', replace(replace(replace(format((sum(os.desconto)), 2), '.', '|'), ',', '.'), '|', ',')) as totalD, 
							   concat('R$ ', replace(replace(replace(format((sum(os.valor - os.desconto)), 2), '.', '|'), ',', '.'), '|', ',')) as total
						from orcamento_servico os
						where os.idOrcamento = '".$idOrcamento."'
						  and os.idImovel = ".$idImovel[$i]."
						group by os.idOrcamento, 
                                 os.idImovel ";
					
				$result = mysqli_query($conn, $sql) or die(mysql_error($conn));
				while($row = mysqli_fetch_row($result)){
					$totalV = $row[1];
					$totalD = $row[2];
					$total  = $row[3];
				}
				$pdf->Row(array('Total',$totalV,$totalD,$total));
			}
			
			$pdf->SetFont('Arial','B');
			$pdf->SetXY(10, 190);
			$pdf->Write(0, utf8_decode("OBSERVAÇÕES:\n"));
			
			$pdf->SetFont('Arial');
			$pdf->SetXY(10, 200);
			$pdf->MultiCell(190, 5, utf8_decode("1. Orçamento válido por 30 (trinta) dias a contar da data de emissão;\n2.  O valor poderá ser pago com 5% de desconto ou em até 3x sem juros nos cartões Master Card, Visa, Elo e American Express;\n3. O valor pago à título de despachante é referente prestação de serviços junto á Cartórios, Prefeituras, SEFAZ, etc.\n4. É de responsabilidade do contratante os pagamentos de taxas cartorárias, impostos, taxas bancárias, dentre outras taxas referentes a transação.\n"), 0);
								
			$pdf->SetFont('Arial','B');
			$pdf->SetXY(100, 250);
			$pdf->MultiCell(200, 5, utf8_decode("ATENCIOSAMENTE\nCREDIMÓVEL SERVIÇOS IMOBILIÁRIOS LTDA"), 0);

			$pdf->SetFont('Arial', 'B');
			$pdf->SetXY(145, 270);
			$pdf->Cell(50, 5, "ACEITO: ", 1);

			$pdf->Output(utf8_decode("Orçamento.pdf"), "D");
        }
    }
?>