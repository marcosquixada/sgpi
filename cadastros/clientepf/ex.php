<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/fpdf/fpdf.php");
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
$idCliente = $_GET['idCliente'];
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->Image($_SERVER['DOCUMENT_ROOT'].'/sgpi/_img/logo credimovel2.png', 10, 10, 'PNG');
$pdf->Cell(18, 10, '', 0);

$pdf->SetFont('Arial', '', 9);

$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(70, 8, '', 0);
$pdf->Cell(50, 8, 'CLIENTE', 1, 0, 'C');
$pdf->Ln(15);
//CONSULTA
$cliente = mysqli_query($conn, "select u.nome, 
                    u.rg, 
                    u.cpf, 
                    u.estado, 
                    u.cidade, 
                    u.bairro,
                    u.logradouro, 
                    u.numero, 
                    u.cep,
                    o.descricao
			from usuario u, 
                 origem o
			where u.id = ".$idCliente."
			  and u.idOrigem = o.id
            ");

while($row = mysqli_fetch_array($cliente)){
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'NOME', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, $row['nome'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'RG', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['rg'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CPF', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['cpf'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'ESTADO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, $row['estado'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'CIDADE', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['cidade'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'BAIRRO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['bairro'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'LOGRADOURO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, utf8_decode($row['logradouro']), 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'NUMERO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['numero'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CEP', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 8, $row['cep'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'ORIGEM', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, utf8_decode($row['descricao']), 0);
}
/*******************************************************/
$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);

$pdf->Cell(50, 8, utf8_decode('SERVIÇO'), 1, 0, 'C');
$pdf->Ln(15);
//CONSULTA
$servico = mysqli_query($conn, "select s.descricao
             from servico s,
                  processo p, 
				  usuario u, 
				  orcamento o, 
				  orcamento_servico os
			 where o.idCliente = ".$idCliente."
			   and os.idServico = s.id
			   and os.idOrcamento = o.id
			   and p.idOrcamento = o.id
			   and u.id = o.idCliente
			 order by p.id ");

while($row = mysqli_fetch_array($servico)){
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, '', 0);
	$pdf->Cell(70, 8, $row['descricao'], 0, 1);
}
/*******************************************************/
$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);

$pdf->Cell(50, 8, utf8_decode('HISTÓRICO'), 1, 0, 'C');
$pdf->Ln(15);
//CONSULTA
$historico = mysqli_query($conn, "select hp.status, 
                               u.nome, 
                               hp.dtAlteracao, 
                               hp.observacao, 
                               hp.dtPrev							   
             from historico_processo hp,
			      processo p,
			      usuario u,
				  orcamento o
			 where o.idCliente = ".$idCliente."
			   and o.id = p.idOrcamento
			   and p.id = hp.idProcesso
			   and hp.idUsuAlteracao = u.id
			 order by hp.id ");

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 8, 'STATUS', 1);
$pdf->Cell(70, 8, 'ATENDENTE', 1);
$pdf->Cell(30, 8, 'DATA', 1);
$pdf->Cell(25, 8, 'OBS', 1);
$pdf->Cell(30, 8, utf8_decode('PREVISÃO'), 1, 1);	
while($row = mysqli_fetch_array($historico)){
	$pdf->SetFont('Arial', '', 6);
	$pdf->Cell(25, 8, $row['status'], 0);
	$pdf->Cell(70, 8, utf8_decode($row['nome']), 0);
	$pdf->Cell(30, 8, $row['dtAlteracao'], 0);
	$pdf->MultiCell(25, 1, utf8_decode($row['observacao']), 0);
	$pdf->Cell(30, 8, $row['dtPrev'], 0, 1);
}
$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);
/*******************************************************/
$pdf->Cell(50, 8, utf8_decode('CÔNJUGE'), 1, 0, 'C');
$pdf->Ln(15);
$conjuge = mysqli_query($conn, "select u.nome, 
                    u.rg, 
                    u.cpf, 
                    u.dataNascimento, 
					u.sexo, 
					u.email,
					u.telefone2
			from usuario u
			where u.idConjuge = ".$idCliente."
            ") or die(mysqli_error($conn));

while($row = mysqli_fetch_array($conjuge)){
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'NOME', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, $row['nome'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'RG', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['rg'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CPF', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['cpf'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'DT. NASC.', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, $row['dataNascimento'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'SEXO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['sexo'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'EMAIL', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 8, $row['email'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CELULAR', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['telefone2'], 0);
}
$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);

/*******************************************************/
$pdf->Cell(50, 8, utf8_decode('PARTICIPANTE'), 1, 0, 'C');
$pdf->Ln(15);
$conjuge = mysqli_query($conn, "select u.nome, 
                    u.rg, 
                    u.cpf, 
                    u.dataNascimento, 
					u.sexo, 
					u.email,
					u.telefone2
			from usuario u
			where u.idParticipante = ".$idCliente."
            ");

while($row = mysqli_fetch_array($conjuge)){
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'NOME', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, $row['nome'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'RG', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['rg'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CPF', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['cpf'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'DT. NASC.', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(70, 8, $row['dataNascimento'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(15, 8, 'SEXO', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['sexo'], 0);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'EMAIL', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(30, 8, $row['email'], 0, 1);
	
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(25, 8, 'CELULAR', 1);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(25, 8, $row['telefone2'], 0);
}
$pdf->SetFont('Arial', 'B', 11);
$pdf->Ln(15);
$pdf->Cell(70, 8, '', 0);

$pdf->Output();
?>