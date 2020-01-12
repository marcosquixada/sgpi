<?php
     
	$idCondominio = $_GET['idCondominio'];
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	$condominio = null;
	$result = mysqli_query($conn, "select nomeFantasia from usuario where id = ".$idCondominio);
	while($row = mysqli_fetch_row($result))
		$condominio = $row[0];
	$queryCliente = "SELECT id, nome FROM usuario WHERE tipo = 'C' and ativo = 1";
	$resultCliente = mysqli_query($conn, $queryCliente);
		
    if ( !empty($_POST)) {
        // keep track validation errors
        $clienteError = null;
         
        // keep track post values
        $idCliente = $_POST['idCliente'];
		$apartamento = $_POST['apartamento'];
		$bloco = $_POST['bloco'];
        
        // validate input
        $valid = true;
        if ($idCliente === '-') {
            $clienteError = 'Por favor informe o cliente';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			$sql = "INSERT INTO condominio_cliente (idCondominio, idCliente, apartamento, bloco) values (".$idCondominio.", ".$idCliente.", '".$apartamento."', '".$bloco."')" or die("erro ao inserir");
			
            if(mysqli_query($conn, $sql)){
				$msg = "Gravado com sucesso!";
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/condominio_cliente?idCondominio=".$idCondominio."';</script>";
			}else{
				$msg = "Erro ao gravar!";
				echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
			}
        }
    }
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<div class="container">
	<fieldset>
		<legend>Cadastro de Cliente-<?php echo $condominio; ?></legend>
 
		<form class="form-horizontal" action="cadastrar.php?idCondominio=<?php echo $idCondominio; ?>" method="post">
			<table style="border: 0;">
				<tr>
					<td>
						<fieldset>
							<legend>Cliente</legend>
							<select name="idCliente">
								<option value="-">Selecione uma opção</option>
								<?php 
									while($row = mysqli_fetch_row($resultCliente))
										echo "<option value=".$row[0].">".utf8_encode($row[1])."</option>"; 
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
							<legend>Apartamento</legend>
							<input name="apartamento" type="text" maxlength="10">
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset>
							<legend>Bloco</legend>
							<input name="bloco" type="text" maxlength="10">
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" style="background-color: white;">
						<button type="submit">Cadastrar</button>
						<button type="button" onclick="history.back();">Voltar</button>
					</td>
				</tr>
			</table>
		</form>			 
</div> <!-- /container -->
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>