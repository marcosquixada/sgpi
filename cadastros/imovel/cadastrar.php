<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	$queryCliente = "SELECT id, nome FROM usuario WHERE tipo = 'C' AND ativo = 1";
	$resultCliente = mysqli_query($conn, $queryCliente);
	
	$queryCondominio = "SELECT id, tipo, descricao FROM condominio order by 2";
	$resultCondominio = mysqli_query($conn, $queryCondominio);
	
	if ( !empty($_POST)) {
        // keep track validation errors
		$clienteError = null;
		$condError = null;
		$blError = null;
		$complError = null;
         
        // keep track post values
		$idCondominio = $_POST['idCondominio'];
		$idBloco = $_POST['idBloco'];
		$cep = $_POST['cep'];
		$estado = $_POST['cod_estados'];
		$cidade = $_POST['cod_cidades'];
		$bairro = $_POST['bairro'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		$complemento = $_POST['complemento'];
         
        // validate input
        $valid = true;
        if ($idCliente === '-') {
            $clienteError = 'Por favor informe o cliente.';
            $valid = false;
        }
		if ($idCondominio === '-') {
            $idCondominio = '';
        }
        if ($idBloco === '-') {
            $idBloco = '';
        }
        
        // insert data
        if ($valid) {
			$sql = "INSERT INTO imovel (idCondominio, idBloco, complemento, cep, estado, cidade, bairro, logradouro, numero, data_cadastro) values('".$idCondominio."', '".$idBloco."', '".$complemento."', '".$cep."', '".$estado."', '".$cidade."', '".$bairro."', '".$endereco."', '".$numero."', '".date('Y-m-d H:i:s')."')";
			
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
			
			$id = mysqli_insert_id($conn);
			
			$idCliente = $_POST['idCliente'];
			
			for($i = 0; $i < count($idCliente); $i++) {
			
				$sql = "INSERT INTO imovel_cliente (idImovel, idCliente) values('".$id."', '".$idCliente[$i]."')";
				
				mysqli_query($conn, $sql) or die(mysqli_error($conn));
			
			}
			
			echo "<script>alert('Cadastrado com sucesso!');window.location.href='/sgpi/cadastros/imovel';</script>"; 
        }
    }
?>

<form action="cadastrar.php" method="post">
	<table style="border: 0;">
		<tr class="linha">
			<td colspan="2">
				<fieldset>
					<legend>Cliente</legend>
					<input type="hidden" name="idCliente[]" class="idCliente" />
					<input name="nomeCliente[]" class="ui-widget nomeCliente" type="text">
					<?php if (!empty($clienteError)): ?>
						<span><?php echo $clienteError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<a href="javascript:void(0);" class="clone"><img src="/sgpi/_img/add.png" height="15px" width="15px" /></a>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Condomínio</legend>
				<select name="idCondominio" id="idCondominio">
				<option value="-">Selecione uma opção</option>
				<?php 
					while($row = mysqli_fetch_row($resultCondominio))
						echo "<option value=".$row[0]."-".$row[1].">".strtr(strtoupper($row[2]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
				?>
				</select> 
				<?php if (!empty($condError)): ?>
					<span><?php echo $condError;?></span>
				<?php endif; ?>
				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				  google.load('jquery', '1.3');
				</script>		

				<script type="text/javascript">
				$(function(){
					$('#idCondominio').change(function(){
						if( $(this).val() ) {
							$.getJSON('/sgpi/libs/blocos.ajax.php?search=',{id: $(this).val(), ajax: 'true'}, function(j){
								//alert(j);
								var options = '<option value="-"></option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].id + '">' + j[i].descricao + '</option>';
								}	
								$('.bloco').html(options + "").show();
							});
						} 
					});
				});
				</script>
			</fieldset>
			</td>
			<td class="tdBloco" style="display: none;">
				<fieldset>
					<legend>Bloco</legend>
					<select name="idBloco" class="bloco">
					<option value="-">Selecione uma opção</option>
					<?php 
						while($row = mysqli_fetch_row($resultBloco))
							echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
					?>
					</select> 
					<?php if (!empty($blocoError)): ?>
						<span><?php echo $blocoError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
			<td class="enderecoImovel">
				<fieldset>
				<legend>CEP</legend>
				<input name="cep" type="text" id="cep" OnKeyUp="mascaraCep(this);" value="<?php echo !empty($cep)?$cep:'';?>" maxlength="10">
				<?php if (!empty($cepError)): ?>
					<span><?php echo $cepError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr class="enderecoImovel">
			<td>
				<fieldset>
				<legend>Estado</legend>
				<select name="cod_estados" id="cod_estados">
				<option value=""></option>
					<?php
						$sql = "SELECT sigla
								FROM estados
								ORDER BY sigla";
						$res = mysqli_query($conn, $sql);
						while ( $row = mysqli_fetch_assoc( $res ) ) {
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
					<select name="cod_cidades" id="cod_cidades">
						<option value="">-- Escolha um estado --</option>
					</select>
				</div>

				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				  google.load('jquery', '1.3');
				</script>		

				<script type="text/javascript">
				$(function(){
					$('#cod_estados').change(function(){
						if( $(this).val() ) {
							$('#cod_cidades').hide();
							$('.carregando').show();
							$.getJSON('/sgpi/libs/cidades.ajax.php?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
								var options = '<option value=""></option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].cod_cidades + '">' + j[i].nome + '</option>';
								}	
								$('#cod_cidades').html(options).show();
								$('.carregando').hide();
							});
						} else {
							$('#cod_cidades').html('<option value="">– Escolha um estado –</option>');
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
		<tr class="enderecoImovel">
			<td colspan="2">
				<fieldset>
				<legend>Endereço</legend>
				<input name="endereco" style="width: 100%;" id="rua" type="text" value="<?php echo !empty($endereco)?$endereco:'';?>">
				<?php if (!empty($enderecoError)): ?>
					<span><?php echo $enderecoError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Número</legend>
				<input name="numero" type="text" value="<?php echo !empty($numero)?$numero:'';?>">
				<?php if (!empty($numeroError)): ?>
					<span><?php echo $numeroError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<fieldset>
				<legend>Complemento</legend>
				<div id="complementos">
					<input name="complemento" type="text" class="complemento">
				</div>
				<?php if (!empty($complementoError)): ?>
					<span><?php echo $complementoError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="background-color: white;">
				<button type="submit">Cadastrar</button>
			</td>
		</tr>
	</table>
</form>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>