<?php
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	$id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else {
        $id = $_POST['id'];
    }
	
	$queryOrigem = "SELECT id, descricao FROM origem";
	$resultOrigem = mysqli_query($conn, $queryOrigem);
     
    if ( null==$id ) {
        header("Location: /sgpi/cadastros/clientepj");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
		$cnpjError = null;
		$emailError = null;
		$cepError = null;
		$enderecoError = null;
		$numeroError = null;
		$estadoError = null;
		$cidadeError = null;
		$bairroError = null;
		$tel1Error = null;
		$tel2Error = null;
		$razaoSocialError = null;
		$nomeFantasiaError = null;
		$inscricaoEstadualError = null;
		$inscricaoMunicipalError = null;
         
        // keep track post values
		$idOrigem = $_POST['idOrigem'];
		$cnpj = $_POST['cnpj'];
		$email = $_POST['email'];
		$cep = $_POST['cep'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		$estado = $_POST['cod_estados'];
		$cidade = $_POST['cod_cidades'];
		$bairro = $_POST['bairro'];
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$razaoSocial = $_POST['razaoSocial'];
		$nomeFantasia = $_POST['nomeFantasia'];
		$inscricaoEstadual = $_POST['inscricaoEstadual'];
		$inscricaoMunicipal = $_POST['inscricaoMunicipal'];
		$responsavel = $_POST['responsavel'];
		$complemento = $_POST['complemento'];
         
        // validate input
        $valid = true;
        if ($idOrigem === '-') {
            $origemError = 'Por favor informe a origem.';
            $valid = false;
        }
        if (empty($cnpj)) {
            $cnpjError = 'Por favor digite seu cnpj.';
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
		if (empty($responsavel)) {
            $responsavelError = 'Por favor digite o responsável.';
            $valid = false;
        }
        
        // update data
        if ($valid) {
			$sql = "UPDATE usuario 
			        set idOrigem = '".$idOrigem."',
						cnpj = '".$cnpj."',
						email = '".$email."',
						cep = '".$cep."',
						logradouro = '".$endereco."',
						numero = '".$numero."',
						estado = '".$estado."',
						cidade = '".$cidade."',
						bairro = '".$bairro."',
						telefone1 = '".$tel1."',
						telefone2 = '".$tel2."',
						razaoSocial = '".$razaoSocial."',
						nomeFantasia = '".$nomeFantasia."',
						inscricaoEstadual = '".$inscricaoEstadual."',
						inscricaoMunicipal = '".$inscricaoMunicipal."',
						responsavel = '".$responsavel."',
						complemento = '".$complemento."'
					WHERE id = ".$id;
			mysqli_query($conn, $sql) or die(mysqli_error());
			echo "<script>alert('Atualizado com sucesso!');window.location.href='/sgpi/cadastros/clientepj';</script>";
        }
    } else {
		$query = "SELECT * FROM usuario WHERE id = ".$id;
		$sql = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array( $sql )) {
			$idOrigem = $row['idOrigem'];
			$cnpj = $row['cnpj'];
			$email = $row['email'];
			$cep = $row['cep'];
			$endereco = $row['logradouro'];
			$numero = $row['numero'];
			$estado = $row['estado'];
			$cidade = $row['cidade'];
			$bairro = $row['bairro'];
			$tel1 = $row['telefone1'];
			$tel2 = $row['telefone2'];
			$razaoSocial = $row['razaoSocial'];
			$nomeFantasia = $row['nomeFantasia'];
			$inscricaoEstadual = $row['inscricaoEstadual'];
			$inscricaoMunicipal = $row['inscricaoMunicipal'];
			$complemento = $row['complemento'];
			$responsavel = $row['responsavel'];
		}
	} 
?>
<form class="form-horizontal" action="editar.php" method="post">
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
				<legend>CNPJ</legend>
				<input name="cnpj" OnKeyUp="mascaraCnpj(this);" onblur="if(!TestaCNPJ(this.value.replace('.', '').replace('.', '').replace('-','').replace('/',''))){alert('CNPJ Inválido!');this.value='';this.focus();}" id="cnpj" maxlength="19" type="text" value="<?php echo !empty($cnpj)?$cnpj:'';?>">
				<?php if (!empty($cnpjErrorError)): ?>
					<span><?php echo $cnpjError;?></span>
				<?php endif; ?>
				</fieldset>
			</td>
			<tr>
				<td colspan="3">
					<fieldset>
					<legend>Razão Social</legend>
					<input name="razaoSocial" type="text" style="width: 100%;" value="<?php echo !empty($razaoSocial)?$razaoSocial:'';?>">
					<?php if (!empty($razaoSocialError)): ?>
						<span><?php echo $razaoSocialError;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<fieldset>
					<legend>Nome Fantasia</legend>
					<input name="nomeFantasia" type="text" style="width: 100%;" value="<?php echo !empty($nomeFantasia)?$nomeFantasia:'';?>">
					<?php if (!empty($nomeFantasiaError)): ?>
						<span><?php echo $nomeFantasiaError;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
					<fieldset>
					<legend>Inscrição Estadual</legend>
					<input name="inscricaoEstadual" type="text" value="<?php echo !empty($inscricaoEstadual)?$inscricaoEstadual:'';?>">
					<?php if (!empty($inscricaoEstadualError)): ?>
					<span><?php echo $inscricaoEstadualError;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
				<td>
					<fieldset>
					<legend>Inscrição Municipal</legend>
					<input name="inscricaoMunicipal" type="text" value="<?php echo !empty($inscricaoMunicipal)?$inscricaoMunicipal:'';?>">
					<?php if (!empty($inscricaoMunicipalError)): ?>
					<span><?php echo $inscricaoMunicipalError;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
				<td>
					<fieldset>
					<legend>Responsável</legend>
					<input name="responsavel" type="text" value="<?php echo !empty($responsavel)?$responsavel:'';?>">
					<?php if (!empty($responsavelError)): ?>
					<span><?php echo $responsavelError;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<fieldset>
					<legend>Email</legend>
					<input name="email" type="text" style="width: 100%;" value="<?php echo !empty($email)?$email:'';?>">
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
					<input name="cep" id="cep" type="text" value="<?php echo !empty($cep)?$cep:'';?>">
					<?php if (!empty($cepError)): ?>
						<span><?php echo $cepError;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
				<td colspan="2">
					<fieldset>
					<legend>Endereço</legend>
					<input name="endereco" style="width: 100%;" type="text" value="<?php echo !empty($endereco)?$endereco:'';?>">
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
				<td colspan="2">
					<fieldset>
					<legend>Complemento</legend>
					<input name="complemento" type="text" style="width: 100%;" value="<?php echo !empty($complemento)?$complemento:'';?>">
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
					<select name="cod_estados" id="cod_estados">
					<option value=""></option>
						<?php
							$sql = "SELECT cod_estados, sigla
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
							<select name="cod_cidades" id="cod_cidades" value="<?php echo !empty($cidade)?$cidade:'';?>">
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
					<input name="bairro" type="text" value="<?php echo !empty($bairro)?$bairro:'';?>">
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
					<input name="tel1" type="text" value="<?php echo !empty($tel1)?$tel1:'';?>" id="phone1">
					<?php if (!empty($tel1Error)): ?>
						<span><?php echo $tel1Error;?></span>
					<?php endif; ?>
					</fieldset>
				</td>
				<td>
					<fieldset>
					<legend>Celular</legend>
					<input name="tel2" type="text" value="<?php echo !empty($tel2)?$tel2:'';?>" id="phone2">
					<?php if (!empty($tel2Error)): ?>
						<span><?php echo $tel2Error;?></span>
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
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>    