<?php
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	$queryCliente = "SELECT id, nome FROM usuario WHERE tipo = 'C' AND ativo = 1";
	$resultCliente = mysqli_query($conn, $queryCliente);
	
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else {
        $id = $_POST['id'];
    }
	    
    if ( null==$id ) {
        header("Location: /sgpi/cadastros/imovel");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
		$clienteError = null;
		$cepError = null;
		$enderecoError = null;
		$numeroError = null;
		$estadoError = null;
		$cidadeError = null;
		$bairroError = null;
		 
        // keep track post values
		$idCliente = $_POST['idCliente'];
		$cep = $_POST['cep'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		$estado = $_POST['cod_estados'];
		$cidade = $_POST['cod_cidades'];
		$bairro = $_POST['bairro'];
		$complemento = $_POST['complemento'];
		 
        // validate input
        $valid = true;
        if ($idCliente === '-') {
            $clienteError = 'Por favor informe o cliente.';
            $valid = false;
        }
		if (empty($cep)) {
            $cepError = 'Por favor digite seu cep.';
            $valid = false;
        }
        if (empty($endereco)) {
            $enderecoError = 'Por favor digite seu endereço.';
            $valid = false;
        }
		if (empty($numero)) {
            $numeroError = 'Por favor digite seu número.';
            $valid = false;
        }
        
        // update data
        if ($valid) {
			$sql = "UPDATE usuario 
			        set id_proprietario = '".$idCliente."', 
					    cep = '".$cep."',
						logradouro = '".$endereco."',
						numero = '".$numero."',
						estado = '".$estado."',
						cidade = '".$cidade."',
						bairro = '".$bairro."',
						complemento = '".$complemento."'
					WHERE id = ".$id;
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
			echo "<script>alert('Atualizado com sucesso!');window.location.href='/sgpi/cadastros/imovel';</script>";
        }
    } else {
		$query = "SELECT * FROM usuario WHERE id = ".$id;
		$sql = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array( $sql )) {
			$idCliente = $row['id_proprietario'];
			$cep = $row['cep'];
			$endereco = $row['logradouro'];
			$numero = $row['numero'];
			$estado = $row['estado'];
			$cidade = $row['cidade'];
			$bairro = $row['bairro'];
			$complemento = $row['complemento'];
		}
	} 
?>
<form action="editar.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<table style="border: 0;">
		<tr>
			<td colspan="3">
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
				<legend>CEP</legend>
				<input name="cep" type="text" id="cep" value="<?php echo !empty($cep)?$cep:'';?>">
				<?php if (!empty($cepError)): ?>
					<span><?php echo $cepError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Endereço</legend>
				<input name="endereco" type="text" id="rua" value="<?php echo !empty($endereco)?utf8_encode($endereco):'';?>">
				<?php if (!empty($enderecoError)): ?>
					<span><?php echo $enderecoError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Número</legend>
				<input name="numero" type="text" value="<?php echo !empty($numero)?$numero:'';?>">
				<?php if (!empty($numeroError)): ?>
					<span><?php echo $numeroError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Complemento</legend>
				<input name="complemento" type="text" value="<?php echo !empty($complemento)?$complemento:'';?>">
				<?php if (!empty($complementoError)): ?>
					<span><?php echo $complementoError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Estado</legend>
				<select name="cod_estados" id="cod_estados" value="<?php echo !empty($estado)?$estado:'';?>">
				<option value=""></option>
					<?php
						$sql = "SELECT sigla
								FROM estados
								ORDER BY sigla";
						$res = mysqli_query($conn, $sql);
						while ( $row = mysqli_fetch_assoc( $res ) ) {
							if($row['sigla'] === $estado)
								echo "<option value=".$row['sigla']." selected>".$row['sigla']."</option>"; 
							else
								echo '<option value="'.$row['sigla'].'">'.$row['sigla'].'</option>';
							
						}
					?>
				</select>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Cidade</legend>
				<span class="carregando">Aguarde, carregando...</span>
				<div id="cidade">
					<?php if($cidade !== '') { ?>
						<input name="cod_cidades" type="text" value="<?php echo !empty($cidade)?$cidade:'';?>">
					<? } else { ?>
						<select name="cod_cidades" id="cod_cidades">
							<option value="">-- Escolha um estado --</option>
						</select>
					<? } ?>
				</div>

				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				    google.load('jquery', '1.3');
				</script>		

				<script type="text/javascript">
				$(function(){
					$('#cod_estados').change(function(){
						if( $(this).val() ) {
							$('#cidade').hide();
							$('.carregando').show();
							$.getJSON('/sgpi/libs/cidades.ajax.php?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
								var options = '<select name="cod_cidades" id="cod_cidades"><option value=""></option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
								}	
								$('#cidade').html(options + "</select>").show();
								$('.carregando').hide();
							});
						} else {
							$('#cidade').html('<select name="cod_cidades" id="cod_cidades"><option value="">– Escolha um estado –</option></select>');
						}
					});
				});
				</script>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Bairro</legend>
				<input name="bairro" type="text" id="bairro" value="<?php echo !empty($bairro)?$bairro:'';?>">
				<?php if (!empty($bairroError)): ?>
					<span><?php echo $bairroError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="background-color: white;">
				<button type="submit">Atualizar</button>
			</td>
		</tr>
	</table>
</form>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>