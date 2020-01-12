<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	const MYSQL_DUPLICATE_KEY_ENTRY = 1022;
	const MYSQL_UNIQUE_KEY_ENTRY = 1062;
		
    if ( !empty($_POST)) {
        // keep track validation errors
		$nomeError = null;
		$cpfError = null;
		$dtNascError = null;
		$sexoError = null;
		$emailError = null;
		$cepError = null;
		$enderecoError = null;
		$numeroError = null;
		$estadoError = null;
		$cidadeError = null;
		$bairroError = null;
		$tel1Error = null;
		$tel2Error = null;
         
        // keep track post values
		$nome = $_POST['nome'];
		$cpf = $_POST['cpf'];
		$dtNasc = date("Y-d-m",strtotime($_POST['dtNasc']));
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
         
        // validate input
        $valid = true;
		if (empty($nome)) {
            $nomeError = 'Por favor digite seu nome.';
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
        
		$uid = uniqid( rand(), true );
		$data_ts = time();
		$ativo = 0;
		$sql = null;
        // insert data
        if ($valid) {
			$sql = "INSERT INTO usuario (nome, cpf, idOrigem, dataNascimento, sexo, email, cep, logradouro, estado, cidade, numero, complemento, tipo, data_ts, uid, ativo, telefone1, telefone2) values('".$nome."', '".$cpf."', 0, '".$dtNasc."', '".$sexo."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', ".$numero.", '".$complemento."', 'A', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."')";
			
			$res = mysqli_query($conn, $sql);
			if (!$res) {
				$errno = mysqli_errno($conn);
				$error = mysqli_error($conn);
				switch ($errno) {
					case MYSQL_UNIQUE_KEY_ENTRY:
						$date = new DateTime($dtNasc);
						$dtNasc = $date->format('d/m/Y');
						echo "<script>alert('CPF JÁ CADASTRADO NO SISTEMA!');</script>";
						break;
					default:
						echo $errno." - ".$error;
						break;
				}
            } else {
				$id = mysqli_insert_id($conn);
			
				// Criar as variaveis para validar o email
				$url = sprintf( 'id=%s&email=%s&uid=%s&key=%s', $id, md5($email), md5($uid), md5($data_ts));

				$mensagem = 'Para confirmar seu cadastro acesse o link:'."\n";
				$mensagem .= sprintf('http://www.credimovelsi.com.br/sgpi/cadastros/usuario/ativar.php?%s',$url);

				// enviar o email
				mail( $email, 'Confirmação de cadastro', $mensagem );
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/administrador';</script>";
			}
        }
    }
?>

<form action="cadastrar.php" method="post">
<table style="border: 0;">
<tr>
	<td>
		<fieldset>
		<legend>CPF</legend>
		<input name="cpf" maxlength="14" class="cpf" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
		<?php if (!empty($cpfError)): ?>
			<span><?php echo $cpfError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Nome</legend>
		<input name="nome" type="text" style="width: 100%;" value="<?php echo !empty($nome)?$nome:'';?>">
		<?php if (!empty($nomeError)): ?>
			<span><?php echo $nomeError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Data de Nascimento</legend>
		<input name="dtNasc" type="text" value="<?php echo !empty($dtNasc)?$dtNasc:'';?>" maxlength="10" class="data">
		<?php if (!empty($dtNascError)): ?>
			<span><?php echo $dtNascError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Email</legend>
		<input name="email" type="text" style="width: 100%;" class="email" value="<?php echo !empty($email)?$email:'';?>">
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
		<input name="cep" type="text" id="cep" maxlength="10" class="cep"  value="<?php echo !empty($cep)?$cep:'';?>">
		<?php if (!empty($cepError)): ?>
			<span><?php echo $cepError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Endereço</legend>
		<input name="endereco" type="text" id="rua" style="width: 100%;" value="<?php echo !empty($endereco)?$endereco:'';?>">
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
		<select name="cod_estados" class="cod_estados" id="cod_estados">
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
				<input type="text" value="<?php echo !empty($cidade)?$cidade:'';?>" name="cod_cidades">
			<? } else { ?>
				<select name="cod_cidades" id="cod_cidades">
					<option value="">-- Escolha um estado --</option>
				</select>
			<? } ?>
		</div>
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
		<?php if (!empty($tel1Error)): ?>
			<span><?php echo $tel1Error;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td>
		<fieldset>
		<legend>Celular</legend>
		<input name="tel2" type="text" value="<?php echo !empty($tel2)?$tel2:'';?>" class="phone2" maxlength="16">
		<?php if (!empty($tel2Error)): ?>
			<span><?php echo $tel2Error;?></span>
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