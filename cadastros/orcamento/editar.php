<?php
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
		
		$queryCliente = "SELECT id, nome FROM usuario WHERE (tipo = 'C' OR 'J') AND ativo = 1";
		$resultCliente = mysqli_query($conn, $queryCliente);
    }
	
	$desconto = null;
	$condCom = null;
	$observacao = null;
	
    if ( !empty($_POST)) {
        // keep track validation errors
        $clienteError = null;
         
        // keep track post values
        $idCliente = $_POST['idCliente'];
		$condCom = $_POST['condCom'];
		$observacao = $_POST['observacao'];
         
        // validate input
        $valid = true;
        if ($idCliente === '-') {
            $clienteError = 'Por favor informe o cliente';
            $valid = false;
        }
         
        // update data
        if ($valid) {
			$sql = "update orcamento set idCliente = '".$idCliente."', '".$condCom."', '".$observacao."' where id = ".$id;
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
			echo "<script>alert('Registro atualizado com sucesso!');window.location.href='/sgpi/cadastros/orcamento';</script>";
        }
    } else {
		$query = "SELECT o.idCliente, 
                         o.condCom, 
                         o.obs						 
		          FROM orcamento o
                  WHERE o.id = ".$id;
		$sql = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array( $sql )) {
			$idCliente = $row['idCliente'];
			$condCom = $row['condCom'];
			$observacao = $row['obs'];
		}
	} 
?>

<div class="container">
	<fieldset>
		<legend>Atualização de Orçamento</legend>

		<form action="cadOrc.php" method="post">
			<table style="border: 0;" id="tabela">
				<tr>
					<td>
						<fieldset>
							<legend>Cliente</legend>
							<select name="idCliente">
							<option value="-">Selecione uma opção</option>
							<?php 
								while($row = mysqli_fetch_row($resultCliente)){
									if($row[0] === $idCliente)
										echo "<option value=".$row[0]." selected>".$row[1]."</option>"; 
									else
										echo "<option value=".$row[0].">".$row[1]."</option>"; 
								}
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
							<legend>Condição Comercial</legend>
							<textarea name="condCom" placeholder="Condição Comercial"><?php echo !empty($condCom)?$condCom:'';?></textarea>
							<?php if (!empty($condComError)): ?>
								<span><?php echo $condComError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend>Observação</legend>
							<textarea name="observacao" placeholder="Observação"><?php echo !empty($observacao)?$observacao:'';?></textarea>
							<?php if (!empty($valorError)): ?>
								<span><?php echo $valorError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" style="background-color: white;" colspan="4">
						<input type="submit" value="Cadastrar">
						<input type="button" value="Voltar" onClick="history.back();" />
					</td>
				</tr>
			</table>
		</form>
	</fieldset>         
</div> <!-- /container -->
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>