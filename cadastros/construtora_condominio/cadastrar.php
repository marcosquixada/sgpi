<?php
     
	$idConstrutora = $_GET['idConstrutora'];
	
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	$construtora = null;
	$result = mysqli_query($conn, "select nomeFantasia from usuario where id = ".$idConstrutora) or die(mysqli_error($conn));
	while($row = mysqli_fetch_row($result))
		$construtora = $row[0];
	$queryCondominio = "SELECT id, descricao FROM condominio";
	$resultCondominio = mysqli_query($conn, $queryCondominio);
		
    if ( !empty($_POST)) {
        // keep track validation errors
        $condominioError = null;
         
        // keep track post values
        $idCondominio = $_POST['idCondominio'];
        
        // validate input
        $valid = true;
        if ($idCondominio === '-') {
            $condominioError = 'Por favor informe o condomínio.';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			$sql = "INSERT INTO construtora_condominio (idConstrutora, idCondominio) values (".$idConstrutora.", ".$idCondominio.")" or die(mysqli_error($conn));
			
            if(mysqli_query($conn, $sql)){
				$msg = "Gravado com sucesso!";
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/construtora_condominio?idConstrutora=".$idConstrutora."';</script>";
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
		<legend>Cadastro de Condomínios - Construtora: <?php echo $construtora; ?></legend>
 
		<form class="form-horizontal" action="cadastrar.php?idConstrutora=<?php echo $idConstrutora; ?>" method="post">
			<table style="border: 0;">
				<tr>
					<td>
						<fieldset>
							<legend>Condomínio</legend>
							<select name="idCondominio">
								<option value="-">Selecione uma opção</option>
								<?php 
									while($row = mysqli_fetch_row($resultCondominio))
										echo "<option value=".$row[0].">".$row[1]."</option>"; 
								?>
							</select> 
							<?php if (!empty($condominioError)): ?>
								<span><?php echo $condominioError;?></span>
							<?php endif; ?>
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