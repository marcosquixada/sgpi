<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
	$queryCliente = "SELECT id, nome FROM usuario WHERE (tipo = 'C' OR 'J') AND ativo = 1";
	$resultCliente = mysql_query($queryCliente);
	
	$queryCondominio = "SELECT id, razaoSocial FROM usuario WHERE (tipo = 'M') AND ativo = 1";
	$resultCondominio = mysql_query($queryCondominio);
	
	$queryServico = "SELECT id, descricao FROM servico";
	$resultServico = mysql_query($queryServico);
		
    if ( !empty($_POST)) {
        // keep track validation errors
        $clienteError = null;
		$servicoError = null;
		$dtInicioError = null;
		$valorError = null;
         
        // keep track post values
        $idCliente = $_POST['idCliente'];
		$idServico = $_POST['idServico'];
		$dtIni = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dtInicio'])));
		$valor = $_POST['valor'];
		$apartamento = $_POST['apartamento'];
		$bloco = $_POST['bloco'];
         
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
		if (empty($dtIni)) {
            $dtIniError = 'Por favor informe a data de início do Processo';
            $valid = false;
        }
		if (empty($valor)) {
            $valorError = 'Por favor informe o valor';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			$sql = "INSERT INTO processo (idCliente, idServico, dtInicio, valor, apartamento, bloco) values (".$idCliente.", ".$idServico.", '".$dtIni."', '".$valor."', '".$apartamento."', '".$bloco."')";
			
            if(mysql_query($sql)){
				$idProcesso = mysql_insert_id();
				session_start();
				
				$sql2 = "INSERT INTO historico_processo (idProcesso, status, idUsuAlteracao, dtAlteracao, observacao) values (".$idProcesso.", 'I', ".$_SESSION['id'].", '".date('Y-m-d H:i:s')."', 'PROCESSO INICIADO')";
				mysql_query($sql2) or die(mysql_error());
			}else{
				$msg = "Erro ao gravar!".$sql;
				echo mysql_errno() . ": " . mysql_error() . "\n";
			}
			echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/processo';</script>";
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
						while($row = mysql_fetch_row($resultCliente))
							echo "<option value=".$row[0].">".$row[1]."</option>"; 
					?>
					</select> 
					<?php if (!empty($clienteError)): ?>
						<span><?php echo $clienteError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<fieldset>
					<legend>Condomínio</legend>
					<select name="idCondominio">
					<option value="-">Selecione uma opção</option>
					<?php 
						while($row = mysql_fetch_row($resultCondominio))
							echo "<option value=".$row[0].">".$row[1]."</option>"; 
					?>
					</select> 
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
						while($row = mysql_fetch_row($resultServico))
							echo "<option value=".$row[0].">".$row[1]."</option>"; 
					?>
					</select> 
					<?php if (!empty($servicoError)): ?>
						<span><?php echo $servicoError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<fieldset>
					<legend>Data de Início</legend>
					<input name="dtInicio" type="text" placeholder="Data de Início" onblur="testa(this, this.value);" value="<?php echo !empty($dtIni)?$dtIni:'';?>" id="date">
					<?php if (!empty($dtInicioError)): ?>
						<span><?php echo $dtInicioError;?></span>
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