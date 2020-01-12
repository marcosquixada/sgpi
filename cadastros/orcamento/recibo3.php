<?php
if (!empty($_POST))
{
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/fpdf/fpdf.php");
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/fpdi/fpdi.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/GExtenso/GExtenso.php");
	
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
	$idServico = $_POST['idServico'];
	$valor = $_POST['valor'];
	$valor_extenso_devedor=GExtenso::moeda(str_replace(".", "", $valor)*100);
	$nome = null;
	$cpf = null;
	$logradouro = null;
	$numero = null;
	$complemento = null;
	$bairro = null;
	$cidade = null;
	$estado = null;
	$bairro = null;
	
	$servico = null;
	
	$queryCliente = "select u.nome, 
	                 u.cpf, 
					 u.logradouro, 
					 u.numero, 
					 u.complemento, 
					 u.bairro, 
					 u.cidade, 
					 u.estado,
					 u.bairro
			  from usuario u 
              where u.id = ".$idCliente;
					 
	$result = mysql_query($queryCliente);
	
	while($row = mysql_fetch_row($result)){
		$nome = $row[0];
		$cpf = $row[1];
		$logradouro = $row[2];
		$numero = $row[3];
		$complemento = $row[4];
		$bairro = $row[5];
		$cidade = $row[6];
		$estado = $row[7];
		$bairro = $row[8];
	}
	
	$queryServico = "select descricao from servico s where s.id = ".$idServico;
	
	$result = mysql_query($queryServico);
	
	while($row = mysql_fetch_row($result)){
		$servico = $row[0];
	}

	$pdf = new FPDI();

	$pageCount = $pdf->setSourceFile("timbrado.pdf");
	$tplIdx = $pdf->importPage(1, '/MediaBox');

	$pdf->addPage();
	$pdf->useTemplate($tplIdx, 10, 10, 180);

	// now write some text above the imported page
	$pdf->SetFont('Arial','BU');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(100, 60);
	$pdf->Write(0, 'RECIBO');

	$pdf->SetFont('Arial','B');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(170, 90);
	$pdf->Write(0, 'R$ '.$valor.'');

	$pdf->SetFont('Arial');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(10, 110);
	
	$pdf->MultiCell(190, 10, iconv('UTF-8', 'windows-1252', "    Recebi(emos) de ".strtoupper($nome).", CPF n° ".$cpf." a quantia de R$: ".$valor." (".strtoupper($valor_extenso_devedor)."), referente aos serviços de ".$servico." do imóvel situado na ".$logradouro.", ".$numero."; ".$complemento." - bairro: ".$bairro." - ".$cidade." - ".$estado."."), 0);

	$pdf->SetFont('Arial');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(10, 155);
	$pdf->MultiCell(190, 10, utf8_decode('    Pelo que firmo(amos) o mesmo em duas vias de igual teor para efeitos legais.'), 0);

	$pdf->SetFont('Arial', 'B');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(140, 170);
	$pdf->MultiCell(190, 10, utf8_decode("Fortaleza-CE, ".date("d")." de ".$mesAtual." de ".date("Y")."."), 0);

	$pdf->SetFont('Arial', 'B');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(125, 190);
	$pdf->MultiCell(190, 5, utf8_decode("_________________________________\nCredimóvel Serviços Imobiliários LTDA\nCNPJ: 20.263.295/0001-04"), 0);

	$pdf->Output("recibo.pdf", "D");
}
?>