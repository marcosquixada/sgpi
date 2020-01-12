<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
 
    if ( !empty($_POST)) {
        // keep track validation errors
        $descError = null;
         
        // keep track post values
        $desc = $_POST['descricao'];
         
        // validate input
        $valid = true;
        if (empty($desc)) {
            $descError = 'Por favor digite a descrição';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
			$sql = "INSERT INTO origem (descricao) values('".$desc."')" or die("erro ao inserir");
            if(mysql_query($sql)){
				$msg = "Gravado com sucesso!";
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/origem';</script>";
			}else{
				$msg = "Erro ao gravar!";
				//echo mysql_errno() . ": " . mysql_error() . "\n";
			}
        }
    }
?>

<div class="container">
	<fieldset>
		<legend>Cadastro de Origem</legend>

		<form action="cadastrar.php" method="post">
			<table style="border: 0;">
				<tr>
					<td>
						<fieldset>
							<legend>Descrição</legend>
							<input name="descricao" type="text" placeholder="Descrição" value="<?php echo !empty($descricao)?$descricao:'';?>">
							<?php if (!empty($descError)): ?>
								<span class="help-inline"><?php echo $descError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" style="background-color: white;">
						<button type="submit">Cadastrar</button>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>
</div> <!-- /container -->
</body>
</html>