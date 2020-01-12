<?php
if(isset($_GET['id']))
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

$idCliente = null;
$totalServicos = null;

$query = "select idCliente from orcamento where id = ".$_GET['id'];
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_row($result))
	$idCliente = $row[0];

$query = "select sum(valor) from orcamento_servico where idOrcamento = ".$_GET['id'];
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_row($result))
	$totalServicos = $row[0];

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
				 
$result = mysql_query($queryCliente) or die(mysql_error());

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

$pdf = new FPDI();

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
$pdf->SetXY(100, 90);
$pdf->Write(0, utf8_decode('ORÇAMENTO'));

$pdf->SetFont('Arial','B');
$pdf->SetXY(10, 110);
$pdf->MultiCell(190, 5, utf8_decode("À\n".$nome."\nImóvel: ".$logradouro.", nº ".$numero." - ".$complemento." - ".$bairro." - ".$cidade." - ".$estado), 0);

$pdf->SetXY(10, 130);
$pdf->Cell(80, 10, utf8_decode("SERVIÇO"), 0);
$pdf->Cell(20, 10, utf8_decode("QUANT."), 0);
$pdf->Cell(20, 10, utf8_decode("VALOR"), 0);
$pdf->Cell(20, 10, utf8_decode("DESC."), 0);
$pdf->Cell(20, 10, utf8_decode("TOTAL"), 0, 1);

$sql = "select s.descricao, 
			   os.valor, 
			   os.desconto, 
			   os.valor - os.desconto as total
		from servico s, 
			 orcamento_servico os
		where s.id = os.idServico 
		  and os.idOrcamento = '".$_GET['id']."'";
	
$result = mysql_query($sql) or die(mysql_error());

while($row = mysql_fetch_row($result)){
	$pdf->Cell(80, 10, utf8_decode($row[0]), 0);
	$pdf->Cell(20, 10, "1", 0);
	$pdf->Cell(20, 10, $row[1], 0);
	$pdf->Cell(20, 10, $row[2], 0);
	$pdf->Cell(20, 10, $row[3], 0, 1);
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

$pdf->Output("Orçamento.pdf", "D");
}

?>