<?php
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else {
        $id = $_POST['id'];
    }
	
	$queryEstCivil = "SELECT id, descricao FROM estado_civil";
	$resultEstCivil = mysqli_query($conn, $queryEstCivil);
	
	$queryOrigem = "SELECT id, descricao FROM origem";
	$resultOrigem = mysqli_query($conn, $queryOrigem);
     
    if ( null==$id ) {
        header("Location: /sgpi/cadastros/clientepf");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
		$origemError = null;
		$nomeError = null;
		$cpfError = null;
		$emailError = null;
		$cepError = null;
		$enderecoError = null;
		$numeroError = null;
		$estadoError = null;
		$cidadeError = null;
		$bairroError = null;
		 
        // keep track post values
		$idOrigem = $_POST['idOrigem'];
		$nome = $_POST['nome'];
		$apelido = $_POST['apelido'];
		$cpf = $_POST['cpf'];
		$rg = $_POST['rg'];
		$dtNasc = date("Y-m-d", strtotime(str_replace('/','-', $_POST['dtNasc'])));
		$sexo = $_POST['sexo'];
		$email = $_POST['email'];
		$cep = $_POST['cep'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		$estado = $_POST['cod_estados'];
		$cidade = $_POST['cod_cidades'];
		$bairro = $_POST['bairro'];
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$complemento = $_POST['complemento'];
		$dataContrato = $_POST['dataContrato'];
		$estadoCivil = $_POST['estadoCivil'];
         
        // validate input
        $valid = true;
        if (empty($nome)) {
            $nomeError = 'Por favor digite seu nome.';
            $valid = false;
        }
		if (empty($idOrigem)) {
            $origemError = 'Por favor informe a origem.';
            $valid = false;
        }
        if (empty($cpf)) {
            $cpfError = 'Por favor digite seu cpf.';
            $valid = false;
        }
        if (empty($email)) {
            $emailError = 'Por favor digite seu email.';
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
			        set idOrigem = '".$idOrigem."',
					    nome = '".$nome."',
						apelido = '".$apelido."',
						cpf = '".$cpf."',
						rg = '".$rg."',
						dataNascimento = '".date("Y-m-d", strtotime(str_replace('/','-', $dtNasc)))."',
						sexo = '".$sexo."',
						email = '".$email."',
						cep = '".$cep."',
						logradouro = '".$endereco."',
						numero = '".$numero."',
						estado = '".$estado."',
						cidade = '".$cidade."',
						bairro = '".$bairro."',
						telefone1 = '".$tel1."',
						telefone2 = '".$tel2."',
						complemento = '".$complemento."',
						estado_civil = '".$estadoCivil."',
						data_contrato = '".date("Y-m-d", strtotime(str_replace('/','-', $dataContrato)))."'
					WHERE id = ".$id;
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
			echo "<script>alert('Atualizado com sucesso!');window.location.href='/sgpi/cadastros/clientepf';</script>";
        }
    } else {
		$query = "SELECT * FROM usuario WHERE id = ".$id;
		$sql = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array( $sql )) {
			$idOrigem = $row['idOrigem'];
			$nome = $row['nome'];
			$apelido = $row['apelido'];
			$cpf = $row['cpf'];
			$rg = $row['RG'];
			$dtNasc = date("d/m/Y", strtotime($row['dataNascimento']));
			$sexo = $row['sexo'];
			$email = $row['email'];
			$cep = $row['cep'];
			$endereco = $row['logradouro'];
			$numero = $row['numero'];
			$estado = $row['estado'];
			$cidade = $row['cidade'];
			$bairro = $row['bairro'];
			$tel1 = $row['telefone1'];
			$tel2 = $row['telefone2'];
			$complemento = $row['complemento'];
			$estadoCivil = $row['estado_civil'];
			$dataContrato = '';
			if(!empty($row['data_contrato']))
				$dataContrato = date("d/m/Y", strtotime($row['data_contrato']));
		}
	} 
?>
<form action="editar.php" method="post">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<table style="border: 0;">
		<tr>
			<td>
				<fieldset>
				<legend>Origem</legend>
				<select name="idOrigem" id="idOrigem1" value="<?php echo $idOrigem; ?>">
					<option value="-">Selecione uma opção</option>
					<?php 
					while($row = mysqli_fetch_row($resultOrigem)){
						if($row[0] === $idOrigem)
							echo "<option value=".$row[0]." selected>".$row[1]."</option>"; 
						else
							echo "<option value=".$row[0].">".$row[1]."</option>"; 
					}
					?>
				</select> 
				<?php if (!empty($origemError)): ?>
					<span><?php echo $origemError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>CPF</legend>
				<input name="cpf" class="cpf" id="cpf" maxlength="14" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
				<script type="text/javascript">
				$(function(){
					$('#cpf').blur(function(){
						if( $(this).val() ) {
							$('.carregando').show();
							$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), ajax: 'true'}, function(j){
								var result = j;	
								
								$('#nome').html(result).show();
								$('.carregando').hide();
							});
						} else {
							$('#cod_cidades').html('<option value="">– Escolha um estado –</option>');
						}
					});
				});
				</script>
				<?php if (!empty($cpfError)): ?>
					<span><?php echo $cpfError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>RG</legend>
				<input name="rg" type="text" value="<?php echo !empty($rg)?$rg:'';?>">
				<?php if (!empty($rgError)): ?>
					<span><?php echo $rgError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<fieldset>
				<legend>Nome</legend>
				<input name="nome" type="text" id="nome" style="width: 100%;" value="<?php echo !empty($nome)?$nome:'';?>">
				<?php if (!empty($nomeError)): ?>
					<span><?php echo $nomeError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td colspan="1">
				<fieldset>
				<legend>Apelido</legend>
				<input name="apelido" type="text" value="<?php echo !empty($apelido)?$apelido:'';?>">
				<?php if (!empty($apelidoError)): ?>
					<span><?php echo $apelidoError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Data de Nascimento</legend>
				<input name="dtNasc" type="text" class="data" value="<?php echo !empty($dtNasc)?$dtNasc:'';?>" maxlength="10">
				<?php if (!empty($dtNascError)): ?>
					<span><?php echo $dtNascError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Email</legend>
				<input name="email" type="text" class="email" style="width: 100%;" value="<?php echo !empty($email)?$email:'';?>">
				<?php if (!empty($emailError)): ?>
					<span><?php echo $emailError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>CEP</legend>
				<input name="cep" type="text" id="cep" value="<?php echo !empty($cep)?$cep:'';?>" class="cep" maxlength="10">
				<?php if (!empty($cepError)): ?>
					<span><?php echo $cepError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Endereço</legend>
				<input name="endereco" type="text" id="rua" value="<?php echo !empty($endereco)?$endereco:'';?>">
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
			<td>
				<fieldset style="width: 150px;">
				<legend>Sexo</legend>
				<input name="sexo" type="radio" value="M" <?php echo ($sexo=='M')?'checked':'' ?> />Masculino
				<input name="sexo" type="radio" value="F" <?php echo ($sexo=='F')?'checked':'' ?> />Feminino
				<?php if (!empty($sexoError)): ?>
					<span><?php echo $sexoError;?></span>
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
			<td>
				<fieldset>
				<legend>Tel. Residencial</legend>
				<input name="tel1" type="text" value="<?php echo !empty($tel1)?$tel1:'';?>" class="phone1" maxlength="14">
			</td>
			<td>
				<fieldset>
				<legend>Celular</legend>
				<input name="tel2" type="text" value="<?php echo !empty($tel2)?$tel2:'';?>" class="phone2" maxlength="16">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Data Contrato</legend>
				<input name="dataContrato" type="text" value="<?php echo !empty($dataContrato)?$dataContrato:'';?>" class="data" maxlength="10">
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Estado Civil</legend>
				<select name="estadoCivil" id="estadoCivil">
					<option value="-">Selecione uma opção</option>
					<?php 
					while($row = mysqli_fetch_row($resultEstCivil)){
						if($row[0] === $estadoCivil)
							echo "<option value=".$row[0]." selected>".utf8_encode($row[1])."</option>"; 
						else
							echo "<option value=".$row[0].">".utf8_encode($row[1])."</option>"; 
					}
					?>
				</select>
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