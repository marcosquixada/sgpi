<?php
     
	$idProcesso = $_GET['idProcesso'];
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	
    if ( !empty($_POST)) {
        // keep track validation errors
        $dtPrevError = null;
		$observacaoError = null;
         
        // keep track post values
        $observacao = $_POST['observacao'];
		$dtPrev = $_POST['dtPrev'];
        
        // validate input
        $valid = true;
        if ($dtPrev === '') {
            $dtPrevError = 'Por favor informe a data de previsão.';
            $valid = false;
        }else
			$dtPrev = date("Y-m-d", strtotime(str_replace('/','-', $_POST['dtPrev'])));
		if ($observacao === '') {
            $statusError = 'Por favor informe a observação.';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			session_start();
			$sql = "INSERT INTO historico_processo (idProcesso, status, idUsuAlteracao, dtAlteracao, dtPrev, observacao) values (".$_POST['idProcesso'].", 'A', ".$_SESSION['id'].", '".date('Y-m-d H:i:s')."', '".$dtPrev."', '".$observacao."')";
			
            if(mysqli_query($conn, $sql)){
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/historico_processo?idProcesso=".$_POST['idProcesso']."';</script>";
			}else{
				echo "Erro ao gravar o histórico! Contate o administrador do sistema!";
				echo mysql_errno() . ": " . mysql_error() . "\n";
			}			
        }
    }
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<div class="container">
	<fieldset>
		<legend>Cadastro de Histórico -> Processo: <?php echo $idProcesso; ?></legend>

		<form action="cadastrar.php" method="post" id="target">
			<input type="hidden" name="idProcesso" value="<?php echo $idProcesso; ?>" />
			<table style="border: 0;">
				<tr>
					<td>
						<fieldset>
							<legend>Data Retorno</legend>
							<input name="dtPrev" type="text" class="data datavelha"
							  value="<?php echo !empty($dtPrev)?$dtPrev:'';?>" maxlength="10" id="dtPrev">
							<?php if (!empty($dtPrevError)): ?>
								<span><?php echo $dtPrevError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset>
							<legend>Observação</legend>
							<textarea name="observacao" placeholder="Observação"></textarea>
							<?php if (!empty($observacaoError)): ?>
								<span><?php echo $observacaoError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" style="background-color: white;">
						<button type="submit" id="btn-submit">Cadastrar</button>
						<button type="button" onclick="history.back();">Voltar</button>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>    
</div> <!-- /container -->
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>