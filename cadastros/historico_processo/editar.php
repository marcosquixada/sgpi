<?php
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
	$idProcesso = $_GET['idProcesso'];
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $statusError = null;
		$dtPrevError = null;
		$observacaoError = null;
         
        // keep track post values
        $status = $_POST['status'];
		$observacao = $_POST['observacao'];
		$dtPrev = date("Y-m-d", strtotime(str_replace('/','-', $_POST['dtPrev'])));
         
        // validate input
        $valid = true;
        if ($status === '-') {
            $statusError = 'Por favor informe o status';
            $valid = false;
        }
		if ($dtPrev === '') {
            $dtPrevError = 'Por favor informe a data de previsão.';
            $valid = false;
        }
		if ($observacao === '') {
            $statusError = 'Por favor informe a observação.';
            $valid = false;
        }
         
        // update data
        if ($valid) {
			$sql = "INSERT INTO historico_processo (idProcesso, status, observacao, idUsuAlteracao, dtAlteracao) values ('".$idProcesso."', '".$status."', '".$observacao."', '".$_SESSION['id']."', '".date('Y-m-d H:i:s')."')";
			mysqli_query($conn, $sql) or die(mysqli_error());
            echo "<script>alert('Registro atualizado com sucesso!');window.location.href='/sgpi/cadastros/historico_processo?idProcesso=".$_POST['idProcesso']."';</script>";
        }
    } else {
		$query = "SELECT * FROM historico_processo WHERE id = ".$id;
		$sql = mysql_query($query);
		while($row = mysql_fetch_array( $sql )) {
			$desc = $row[1];
		}
	} 
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<div class="container">
	<fieldset>
		<legend>Atualização de Histórico -> Processo: <?php echo $idProcesso; ?></legend>

		<form action="cadastrar.php" method="post">
			<table style="border: 0;">
				<tr>
					<td>
						<fieldset>
							<legend>Status</legend>
							<input type="hidden" name="idProcesso" value="<?php echo $idProcesso; ?>" />
							<select name="status">
							     <option value="-">Selecione uma opção</option>
							     <option value="A">Em Atendimento</option>
								 <option value="F">Finalizado</option>								 
							 </select> 
                            <?php if (!empty($statusError)): ?>
                                <span><?php echo $statusError;?></span>
                            <?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset>
							<legend>Data Retorno</legend>
							<input name="dtPrev" type="text" OnKeyUp="mascaraData(this);" onblur="testa(this, this.value);" value="<?php echo !empty($dtPrev)?$dtPrev:'';?>" maxlength="10" id="date">
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
						<button type="submit">Atualizar</button>
						<button type="button" onclick="history.back();">Voltar</button>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>    
</div> <!-- /container -->
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>    