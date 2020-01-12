<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$queryCliente = "SELECT id, nome FROM usuario WHERE (tipo = 'C' OR 'J') AND ativo = 1";
	$resultCliente = mysqli_query($conn, $queryCliente);
	
	$queryServico = "SELECT id, descricao FROM servico";
	$resultServico = mysqli_query($conn, $queryServico);
		
    if ( !empty($_POST)) {
        // keep track validation errors
        $clienteError = null;
		$servicoError = null;
		$valorError = null;
         
        // keep track post values
        $idCliente = $_POST['idCliente'];
		$idServico = $_POST['idServico'];
		$valor = $_POST['valor'];
         
        // validate input
        $valid = true;
        if ($idCliente === '-') {
            $clienteError = 'Por favor informe o cliente';
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
			$sql = "INSERT INTO recibo (idCliente, idServico, valor, data_cadastro) values (".$idCliente.", ".$idServico.", '".str_replace(',','.', str_replace('.','', $valor))."', '".date('Y-m-d H:i:s')."')";
			
            mysqli_query($conn, $sql) or die(mysqli_error($conn));
			
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
			//$valor = $_POST['valor'];
			$valor_extenso_devedor=GExtenso::moeda(str_replace(",", ".", str_replace(".", "", $valor))*100);
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
							 
			$result = mysqli_query($conn, $queryCliente);
			
			while($row = mysqli_fetch_row($result)){
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
			
			$result = mysqli_query($conn, $queryServico);
			
			while($row = mysqli_fetch_row($result)){
				$servico = $row[0];
			}

			$pdf = new FPDI();
	
			$pageCount = $pdf->setSourceFile("timbrado3.pdf");
			$tplIdx = $pdf->importPage(1, '/MediaBox');

			$pdf->addPage();
			$pdf->useTemplate($tplIdx, 10, 10, 180);

			// now write some text above the imported page
			$pdf->SetFont('Arial','BU');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(100, 25);
			$pdf->Write(0, 'RECIBO');

			//setlocale(LC_MONETARY, 'pt_BR');
			$pdf->SetFont('Arial','B');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(170, 40);
			$pdf->Write(0, 'R$ '.$valor);

			$pdf->SetFont('Arial');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(10, 50);
			
			$pdf->MultiCell(190, 5, iconv('UTF-8', 'windows-1252', "    Recebi(emos) de ".strtoupper($nome).", CPF n° ".$cpf." a quantia de R$: ".$valor." (".strtoupper($valor_extenso_devedor)."), referente aos serviços de ".$servico." do imóvel situado na ".$logradouro.", ".$numero." - ".$complemento." - bairro: ".$bairro." - ".$cidade." - ".$estado."."), 0);

			$pdf->SetFont('Arial');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(10, 75);
			$pdf->MultiCell(190, 10, utf8_decode('    Pelo que firmo(amos) o mesmo em duas vias de igual teor para efeitos legais.'), 0);

			$pdf->SetFont('Arial', 'B');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(130, 90);
			$pdf->MultiCell(190, 10, utf8_decode("Fortaleza-CE, ".date("d")." de ".$mesAtual." de ".date("Y")."."), 0);

			$pdf->SetFont('Arial', 'B');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(125, 110);
			$pdf->MultiCell(190, 5, utf8_decode("_________________________________\nCredimóvel Serviços Imobiliários LTDA\nCNPJ: 20.263.295/0001-04"), 0);
			
			//2A REPLICA
			$pdf->SetFont('Arial','BU');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(100, 150);
			$pdf->Write(0, 'RECIBO');

			$pdf->SetFont('Arial','B');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(170, 165);
			$pdf->Write(0, 'R$ '.$valor);

			$pdf->SetFont('Arial');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(10, 175);
			
			$pdf->MultiCell(190, 5, iconv('UTF-8', 'windows-1252', "    Recebi(emos) de ".strtoupper($nome).", CPF n° ".$cpf." a quantia de R$: ".$valor." (".strtoupper($valor_extenso_devedor)."), referente aos serviços de ".$servico." do imóvel situado na ".$logradouro.", ".$numero."; ".$complemento." - bairro: ".$bairro." - ".$cidade." - ".$estado."."), 0);

			$pdf->SetFont('Arial');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(10, 200);
			$pdf->MultiCell(190, 10, utf8_decode('    Pelo que firmo(amos) o mesmo em duas vias de igual teor para efeitos legais.'), 0);

			$pdf->SetFont('Arial', 'B');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(130, 215);
			$pdf->MultiCell(190, 10, utf8_decode("Fortaleza-CE, ".date("d")." de ".$mesAtual." de ".date("Y")."."), 0);

			$pdf->SetFont('Arial', 'B');
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetXY(125, 235);
			$pdf->MultiCell(190, 5, utf8_decode("_________________________________\nCredimóvel Serviços Imobiliários LTDA\nCNPJ: 20.263.295/0001-04"), 0);

			$pdf->Output("recibo.pdf", "D");
        }
    }
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<form action="cadastrar.php" method="post">
	<table>
		<tr>
			<td>
				<fieldset>
					<legend>Cliente</legend>
					<select name="idCliente">
					<option value="-">Selecione uma opção</option>
					<?php 
						while($row = mysqli_fetch_row($resultCliente))
							echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
					?>
					</select> 
					<?php if (!empty($clienteError)): ?>
						<span><?php echo $clienteError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>Serviço</legend>
					<select name="idServico">
					<option value="-">Selecione uma opção</option>
					<?php 
						while($row = mysqli_fetch_row($resultServico))
							echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
					?>
					</select> 
					<?php if (!empty($servicoError)): ?>
						<span><?php echo $servicoError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>Valor</legend>
					<input name="valor" type="text" placeholder="Valor" onkeypress="return(FormataReais(this,'.',',',event))" value="<?php echo !empty($valor)?$valor:'';?>" id="number">
					<?php if (!empty($valorError)): ?>
						<span><?php echo $valorError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="background-color: white;">
				<button type="submit">Cadastrar</button>
			</td>
		</tr>
	</table>
</form>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>