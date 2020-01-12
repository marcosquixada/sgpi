<?php
     
	$idOrcamento = $_GET['idOrcamento'];
	
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$queryServico = "SELECT id, descricao FROM servico";
	$resultServico = mysqli_query($conn, $queryServico);
	
	$queryImovel = "SELECT i.id, 
						   i.logradouro, 
						   i.numero, 
						   i.complemento, 
						   i.estado, 
						   i.cidade, 
						   i.bairro 
					FROM usuario i,
                         usuario u,					
					     orcamento o
					WHERE i.id_proprietario = u.id
                      AND o.idCliente = u.id					
					  AND i.tipo = 'I'
					  AND o.id = '".$idOrcamento."'";
	$resultImovel = mysqli_query($conn, $queryImovel);
	
    if ( !empty($_POST)) {
		$idOrcamento = $_POST['idOrcamento'];
        // keep track validation errors
        $servicoError = null;
		$valorError = null;
		$descontoError = null;
         
        // keep track post values
        $idImovel = $_POST['idImovel'];
		$idServico = $_POST['idServico'];
		$valor = $_POST['valor'];
		$desconto = $_POST['desconto'];
        
        // validate input
        $valid = true;
		if ($idImovel === '-') {
            $servicoError = 'Por favor informe o imovel.';
            $valid = false;
        }
		if ($idServico === '-') {
            $servicoError = 'Por favor informe o serviço.';
            $valid = false;
        }
		if ($valor === '') {
            $valorError = 'Por favor informe o valor do serviço.';
            $valid = false;
        }
		if ($desconto === '') {
            $descontoError = 'Por favor informe o desconto.';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			for($i = 0; $i < count($idServico); $i++) {
				$sql = "INSERT INTO orcamento_servico (idOrcamento, idImovel, idServico, valor, desconto, data_cadastro) values (".$idOrcamento.", '".$idImovel[$i]."', ".$idServico[$i].", '".$valor[$i]."', '".$desconto[$i]."', '".date('Y-m-d H:i:s')."')";
				
				mysqli_query($conn, $sql) or die($sql.mysqli_error($conn));
			}
			
            echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/orcamento_servico/index.php?id=".$idOrcamento."';</script>";				
        }
    }
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<fieldset>
	<legend>Cadastro de Serviço -> Orçamento: <?php echo $idOrcamento; ?></legend>

	<form action="cadastrar.php" method="post">
		<input type="hidden" name="idOrcamento" value="<?php echo $idOrcamento; ?>" />
		<table style="border: 0;" id="tabela">
			<tr id="linha">
				<td>
					<fieldset>
						<legend>Imóvel</legend>
						<select name="idImovel[]" class="imovel">
						<option value="-">Selecione uma opção</option>
						<?php 
							while($row = mysqli_fetch_row($resultImovel))
								echo "<option value=".$row[0].">".$row[1]." - ".$row[2]." - ".$row[3]." - ".$row[4]." - ".$row[5]." - ".$row[6]." - ".$row[7]."</option>"; 
						?>
						</select> 
						<?php if (!empty($imovelError)): ?>
							<span><?php echo $imovelError;?></span>
						<?php endif; ?>
					</fieldset>
				</td>
				<td>
					<fieldset>
						<legend>Serviço</legend>
						<select name="idServico[]">
						<option value="-">Selecione uma opção</option>
						<?php 
							while($row = mysqli_fetch_row($resultServico))
								echo "<option value=".$row[0].">".utf8_encode($row[1])."</option>"; 
						?>
						</select> 
						<?php if (!empty($servicoError)): ?>
							<span><?php echo $servicoError;?></span>
						<?php endif; ?>
					</fieldset>
				</td>
				<td>
					<fieldset>
						<legend>Valor</legend>
						<input name="valor[]" type="text" placeholder="Valor" onkeypress="return(FormataReais(this,'.',',',event))" value="<?php echo !empty($valor)?$valor:'';?>" id="number">
						<?php if (!empty($valorError)): ?>
							<span><?php echo $valorError;?></span>
						<?php endif; ?>
					</fieldset>
				</td>
				<td>
					<fieldset>
						<legend>Desconto</legend>
						<input name="desconto[]" type="text" placeholder="Desconto" onkeypress="return(FormataReais(this,'.',',',event))" value="<?php echo !empty($desconto)?$desconto:'';?>" maxlength="2">
					</fieldset>
				</td>
				<td>
					<img src="/sgpi/_img/add.png" height="15px" width="15px" onclick="cloneRow('linha','tabela');" />
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center" style="background-color: white;">
					<button type="submit">Cadastrar</button>
					<button type="button" onclick="history.back();">Voltar</button>
				</td>
			</tr>
		</table>
	</form>
</fieldset>    
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>