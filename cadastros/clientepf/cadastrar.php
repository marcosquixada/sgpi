<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	$queryOrigem = "SELECT id, descricao FROM origem";
	$resultOrigem = mysqli_query($conn, $queryOrigem);
	
	$queryEstCivil = "SELECT id, descricao FROM estado_civil";
	$resultEstCivil = mysqli_query($conn, $queryEstCivil);
	
	$queryTipoRenda = "SELECT id, descricao FROM tipo_renda";
	$resultTipoRenda = mysqli_query($conn, $queryTipoRenda);
	
	const MYSQL_DUPLICATE_KEY_ENTRY = 1022;
	const MYSQL_UNIQUE_KEY_ENTRY = 1062;
		
    if ( !empty($_POST)) {
        // keep track validation errors
		$error = null;
         
        // keep track post values
		$idOrigem = $_POST['idOrigem'];
		$descOrigem = $_POST['descOrigem'];
		$nomeCliente = $_POST['nomeCliente'];
		$apelido = $_POST['apelido'];
		$cpf = $_POST['cpf'];
		$rg = $_POST['rg'];
		$dtNasc = date("Y-m-d", strtotime(str_replace('/','-', $_POST['dtNasc'])));
		$sexo = $_POST['sexo'];
		$email = $_POST['email'];
		$cep = $_POST['cep'];
		$endereco = strtoupper($_POST['endereco']);
		$numero = $_POST['numero'];
		$estado = $_POST['cod_estados'];
		$cidade = strtoupper($_POST['cod_cidades']);
		$bairro = strtoupper($_POST['bairro']);
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$complemento = strtoupper($_POST['complemento']);
		$renda = $_POST['renda'];
		$outraRenda = $_POST['outraRenda'];

        // validate input
        $valid = true;
        if ($idOrigem === '-') {
            $error = 'Por favor informe a origem.';
            $valid = false;
        }
		if ($nomeCliente === '') {
            $error = 'Por favor digite o nome do cliente.';
            $valid = false;
        }
        if ($email === '') {
            $error = 'Por favor digite seu email.';
            $valid = false;
        }
        if ($cep === '') {
            $error = 'Por favor digite seu cep.';
            $valid = false;
        }
        if ($endereco === '') {
            $error = 'Por favor digite seu endereço.';
            $valid = false;
        }
        
		$uid = uniqid( rand(), true );
		$data_ts = time();
		$ativo = 1;
		$sql = null;
        // insert data
        if ($valid) {
			$sql = "INSERT INTO usuario (nome, apelido, cpf, rg, idOrigem, dataNascimento, sexo, email, cep, logradouro, estado, cidade, numero, 
			complemento, tipo, data_ts, uid, ativo, telefone1, telefone2, data_cadastro, renda, outra_renda, data_contrato) 
			values('".strtoupper($nomeCliente)."', '".strtoupper($apelido)."', '".$cpf."', '".$rg."', ".$idOrigem.", '".$dtNasc."', '".$sexo."', 
			'".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', '".$numero."', '".$complemento."', 'C', '".$data_ts."', 
			'".$uid."','".$ativo."', '".$tel1."', '".$tel2."', '".date('Y-m-d H:i:s')."', '".$renda."', '".$outraRenda."', null)";
			
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
            }else{
				$id = mysqli_insert_id($conn);
				
				//inserção de conjuge, se for o caso
				if($_POST['estadoCivil'] === '2' or $_POST['estadoCivil'] === '5'){
					$cpfConj = $_POST['cpfConj'];
					$rgConj = $_POST['rgConj'];
					$nomeConj = strtoupper($_POST['nomeConj']);
					$apelidoConj = strtoupper($_POST['apelidoConj']);
					$dtNascConj = $_POST['dtNascConj'];
					$emailConj = $_POST['emailConj'];
					$cepConj = $_POST['cepConj'];
					$enderecoConj = strtoupper($_POST['enderecoConj']);
					$numeroConj = $_POST['numeroConj'];
					$complementoConj = strtoupper($_POST['complementoConj']);
					$sexoConj = $_POST['sexoConj'];
					$estadoConj = $_POST['estadoConj'];
					$cidadeConj = strtoupper($_POST['cidadeConj']);
					$bairroConj = strtoupper($_POST['bairroConj']);
					$tel1Conj = $_POST['tel1Conj'];
					$tel2Conj = $_POST['tel2Conj'];
					$rendaConj = $_POST['rendaConj'];
					$tipoRendaConj = $_POST['tipoRendaConj'];
					$outraRendaConj = $_POST['outraRendaConj'];
					
					$sql = "INSERT INTO usuario (idConjuge, cpf, rg, nome, apelido, dataNascimento, email, cep, logradouro, numero, complemento, sexo, estado, cidade, bairro, telefone1, telefone2, renda, tipo_renda, outra_renda, data_cadastro) values('".$id."', '".$cpfConj."', '".$rgConj."', '".$nomeConj."', '".$apelidoConj."', '".$dtNascConj."', '".$emailConj."', '".$cepConj."', '".$enderecoConj."', '".$numeroConj."', '".$complementoConj."', '".$sexoConj."', '".$estadoConj."', '".$cidadeConj."', '".$bairroConj."', '".$tel1Conj."', '".$tel2Conj."', '".$rendaConj."', '".$tipoRendaConj."', '".$outraRendaConj."', '".date('Y-m-d H:i:s')."')";
					mysqli_query($conn, $sql) or die(mysqli_error($conn));
					$idConj = mysqli_insert_id($conn);
					$sql = "update usuario set idConjuge = ".$idConj." where id = ".$id;
					mysqli_query($conn, $sql) or die(mysql_error($conn));
				}
				
				//inserção de participante, se for o caso
				if($_POST['comporRenda'] === 's'){
					$cpfPart = $_POST['cpfPart'];
					$rgPart = $_POST['rgPart'];
					$nomePart = strtoupper($_POST['nomePart']);
					$apelidoPart = strtoupper($_POST['apelidoPart']);
					$dtNascPart = $_POST['dtNascPart'];
					$emailPart = $_POST['emailPart'];
					$cepPart = $_POST['cepPart'];
					$enderecoPart = strtoupper($_POST['enderecoPart']);
					$numeroPart = $_POST['numeroPart'];
					$complementoPart = strtoupper($_POST['complementoPart']);
					$sexoPart = $_POST['sexoPart'];
					$estadoPart = $_POST['estadoPart'];
					$cidadePart = strtoupper($_POST['cidadePart']);
					$bairroPart = strtoupper($_POST['bairroPart']);
					$tel1Part = $_POST['tel1Part'];
					$tel2Part = $_POST['tel2Part'];
					$rendaPart = $_POST['rendaPart'];
					$tipoRendaPart = $_POST['tipoRendaPart'];
					$outraRendaPart = $_POST['outraRendaPart'];
					
					$sql = "INSERT INTO usuario (idParticipante, cpf, rg, nome, apelido, dataNascimento, email, cep, logradouro, numero, complemento, sexo, estado, cidade, bairro, telefone1, telefone2, renda, tipo_renda, outra_renda, data_cadastro) values('".$id."', '".$cpfPart."', '".$rgPart."', '".$nomePart."', '".$apelidoPart."', '".$dtNascPart."', '".$emailPart."', '".$cepPart."', '".$enderecoPart."', '".$numeroPart."', '".$complementoPart."', '".$sexoPart."', '".$estadoPart."', '".$cidadePart."', '".$bairroPart."', '".$tel1Part."', '".$tel2Part."', '".$rendaPart."', '".$tipoRendaPart."', '".$outraRendaPart."', '".date('Y-m-d H:i:s')."')";
					mysqli_query($conn, $sql) or die(mysqli_error($conn));
					$idPart = mysqli_insert_id($conn);
					$sql = "update usuario set idParticipante = ".$idPart." where id = ".$id;
					mysqli_query($conn, $sql) or die(mysqli_error($conn));
				}
				
				$i = 0;
				foreach($_FILES as $arquivo){
					$idDoc = $_POST['doc'][$i];
					$entregue = "N";
					if (isset($_POST['check'.$idDoc]))
						$entregue = "S";
					
					$file = null;
					$fileName = $arquivo['name'];
					$tmpName  = $arquivo['tmp_name'];
					$fileSize = $arquivo['size'];
					$fileType = $arquivo['type'];
					
					if(!empty($fileName)){
						$file = rand(1000,100000)."-".$fileName;
						$folder=$_SERVER['DOCUMENT_ROOT']."/sgpi/uploads/";
						move_uploaded_file($tmpName,$folder.$file);
					}
					
					$sql3 = "INSERT INTO cliente_documento (idCliente, idDocumento, entregue, name, size, type) values (".$id.", ".$idDoc.", '".$entregue."', '".$file."', '".$fileSize."', '".$fileType."')";
					//die($sql3);
					mysqli_query($conn, $sql3) or die(mysqli_error($conn)." - ".$sql3);
					$i++;
				}
			
				// Criar as variaveis para validar o email
				$url = sprintf( 'id=%s&email=%s&uid=%s&key=%s', $id, md5($email), md5($uid), md5($data_ts));

				$mensagem = 'Para confirmar seu cadastro acesse o link:'."\n";
				$mensagem .= sprintf('http://www.credimovelsi.com.br/sgpi/cadastros/usuario/ativar.php?%s',$url);

				// enviar o email
				/*$headers = array("From:  andresilvestre.credimovel@gmail.com",
					"Reply-To: replyto@example.com",
					"Content-Type: text/html; charset=UTF-8",
					"X-Mailer: PHP/" . PHP_VERSION
				);
				$headers = implode("\r\n", $headers);
				$mail = mail($email, utf8_decode('CONFIRMAÇÃO DE CADASTRO CREDIMOVEL'), $mensagem, $headers);*/
				$mail = mail($email, mb_encode_mimeheader("Confirmação de Cadastro Credimovel","UTF-8"), $mensagem );
				if($mail)
					echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/clientepf';</script>";
				else
					echo "<script>alert('Registro cadastrado com sucesso, porém falha ao enviar email!');window.location.href='/sgpi/cadastros/clientepf';</script>";
			}
        } else {
			die($error);
		}
    }
?>

<form action="cadastrar.php" method="post" enctype="multipart/form-data">
	<table style="border: 0;">
		<tr>
			<td>
				<fieldset>
				<legend>Origem</legend>
				<select name="idOrigem" id="idOrigem1">
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
				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				    google.load('jquery', '1.3');
				</script>		
				<script type="text/javascript">
				$(function(){
					$('#cpf').blur(function(){
						if( $(this).val() ) {
							$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), tipo: 'C', ajax: 'true'}, function(j){
								if(j !== null){
									alert('CPF já cadastrado no sistema!');
									$('#cpf').val("");
									$('#nome').val("");
								} 
								//jAlert('Cliente já cadastrado no sistema!', 'Alert Dialog');
							});
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
				<input name="nomeCliente" type="text" style="width: 100%;" value="<?php echo !empty($nome)?$nome:'';?>">
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
				<input name="dtNasc" type="text" class="data" maxlength="10" value="<?php echo !empty($dtNasc)?$dtNasc:'';?>">
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
				<input name="cep" type="text" id="cep" class="cep" maxlength="10" value="<?php echo !empty($cep)?$cep:'';?>">
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
				<select name="cod_estados" id="cod_estados">
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
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Tel. Residencial</legend>
				<input name="tel1" type="text" value="<?php echo !empty($tel1)?$tel1:'';?>" class="phone1" maxlength="14">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Celular</legend>
				<input name="tel2" type="text" value="<?php echo !empty($tel2)?$tel2:'';?>" class="phone2" maxlength="16">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Estado Civil</legend>
				<select name="estadoCivil" id="estadoCivil">
					<option value="-">Selecione uma opção</option>
					<?php 
					while($row = mysqli_fetch_row($resultEstCivil)){
						echo "<option value=".$row[0].">".$row[1]."</option>"; 
					}
					?>
				</select>
				<script type="text/javascript">
				$(function(){
					$('#estadoCivil').change(function(){
						if( $(this).val() === '2' || $(this).val() === '5' ) {
							$('.conjuge').show();
							$.getJSON('/sgpi/libs/tipoRenda.ajax.php',{ajax: 'true'}, function(j){
								var options = '<select name="tipoRendaConj" id="tipoRendaConj"><option value="">Selecione uma opção</option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].id + '">' + j[i].descricao + '</option>';
								}	
								$('#rendaConj').html(options + "</select>").show();
							});
						} else {
							$('.conjuge').hide();
						}
					});
				});
				</script>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Renda</legend>
				<input name="renda" type="text" value="<?php echo !empty($renda)?$renda:'';?>" placeholder="Renda" class="formatareais">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Tipo Renda</legend>
				<select name="tipoRenda" id="tipoRenda">
					<option value="-">Selecione uma opção</option>
					<?php 
					while($row = mysqli_fetch_row($resultTipoRenda)){
						echo "<option value=".$row[0].">".$row[1]."</option>"; 
					}
					?>
				</select> 
				<script type="text/javascript">
				$(function(){
					$('#tipoRenda').change(function(){
						if( $(this).val() === '21' ) {
							$('#outraRenda').show();
						} else {
							$('#outraRenda').hide();
						}
					});
				});
				</script>
				</fieldset>
			</td>
			<td id="outraRenda" style="display: none;">
				<fieldset>
				<legend>Qual?</legend>
				<input type="text" name="outraRenda" />
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
				<legend>Compor Renda?</legend>
				<select name="comporRenda" id="comporRenda">
					<option value="n">Não</option>
					<option value="s">Sim</option>
				</select> 
				<script type="text/javascript">
				$(function(){
					$('#comporRenda').change(function(){
						if( $(this).val() === 's' ) {
							$('.participante').show();
							$.getJSON('/sgpi/libs/tipoRenda.ajax.php',{ajax: 'true'}, function(j){
								var options = '<select name="tipoRendaPart" id="tipoRendaPart"><option value="">Selecione uma opção</option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].id + '">' + j[i].descricao + '</option>';
								}	
								$('#rendaPart').html(options + "</select>").show();
							});
						} else {
							$('.participante').hide();
						}
					});
				});
				</script>
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td colspan="3" align="center">
				<ins><b>Dados do Cônjuge</b></ins>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>CPF</legend>
				<input name="cpfConj" class="cpf" id="cpfConj" maxlength="14" type="text">
				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				    google.load('jquery', '1.3');
				</script>		
				<script type="text/javascript">
				$(function(){
					$('#cpfConj').blur(function(){
						if( $(this).val() ) {
							$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), ajax: 'true'}, function(j){
								if(j !== null){
									alert('CPF já cadastrado no sistema!');
									$('#cpfConj').val("");
								} 
								//jAlert('Cliente já cadastrado no sistema!', 'Alert Dialog');
							});
						} 
					});
				});
				</script>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>RG</legend>
				<input name="rgConj" type="text">
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td colspan="2">
				<fieldset>
				<legend>Nome</legend>
				<input name="nomeConj" type="text" id="nomeConj" style="width: 100%;">
				</fieldset>
			</td>
			<td colspan="1">
				<fieldset>
				<legend>Apelido</legend>
				<input name="apelidoConj" type="text">
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>Data de Nascimento</legend>
				<input name="dtNascConj" type="text" maxlength="10" class="data">
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Email</legend>
				<input name="emailConj" type="text" class="email" style="width: 100%;">
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>CEP</legend>
				<input name="cepConj" type="text" id="cepConj" class="cep" maxlength="10">
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Endereço</legend>
				<input name="enderecoConj" type="text" id="ruaConj">
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>Número</legend>
				<input name="numeroConj" type="text">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Complemento</legend>
				<input name="complementoConj" type="text">
				</fieldset>
			</td>
			<td>
				<fieldset style="width: 150px;">
				<legend>Sexo</legend>
				<input name="sexoConj" type="radio" value="M" />Masculino
				<input name="sexoConj" type="radio" value="F" />Feminino
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>Estado</legend>
				<select name="cod_estadosConj" id="cod_estadosConj">
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
				<span class="carregandoConj">Aguarde, carregando...</span>
				<div id="cidadeConj">
					<?php if($cidade !== '') { ?>
						<input type="text" name="cod_cidadesConj">
					<? } else { ?>
						<select name="cod_cidadesConj" id="cod_cidadesConj">
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
					$('#cod_estadosConj').change(function(){
						if( $(this).val() ) {
							$('#cidadeConj').hide();
							$('.carregandoConj').show();
							$.getJSON('/sgpi/libs/cidades.ajax.php?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
								var options = '<select name="cod_cidadesConj" id="cod_cidadesConj"><option value=""></option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
								}	
								$('#cidadeConj').html(options + "</select>").show();
								$('.carregandoConj').hide();
							});
						} else {
							$('#cidadeConj').html('<select name="cod_cidadesConj" id="cod_cidadesConj"><option value="">– Escolha um estado –</option></select>');
						}
					});
				});
				</script>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Bairro</legend>
				<input name="bairroConj" type="text" id="bairroConj">
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>Tel. Residencial</legend>
				<input name="tel1Conj" type="text" value="<?php echo !empty($tel1)?$tel1:'';?>" class="phone1" maxlength="14">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Celular</legend>
				<input name="tel2Conj" type="text" value="<?php echo !empty($tel2)?$tel2:'';?>" class="phone2" maxlength="16">
				</fieldset>
			</td>
		</tr>
		<tr class="conjuge" style="display: none;">
			<td>
				<fieldset>
				<legend>Renda</legend>
				<input name="rendaConj" type="text" placeholder="Renda" class="formatareais">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Tipo Renda</legend>
				<div id="rendaConj">
					<select name="tipoRendaConj" id="tipoRendaConj">
						<option value="-">Selecione uma opção</option>
						<?php 
						while($row = mysqli_fetch_row($resultTipoRenda)){
							echo "<option value=".$row[0].">".utf8_encode($row[1])."</option>"; 
						}
						?>
					</select> 
				</div>
				<script type="text/javascript">
				$(function(){
					$('#tipoRendaConj').change(function(){
						if( $(this).val() === '21' ) {
							$('#outraRendaConj').show();
						} else {
							$('#outraRendaConj').hide();
						}
					});
				});
				</script>
				</fieldset>
			</td>
			<td id="outraRendaConj" style="display: none;">
				<fieldset>
				<legend>Qual?</legend>
				<input type="text" name="outraRendaConj" />
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td colspan="3" align="center">
				<ins><b>Dados do Participante</b></ins>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>CPF</legend>
				<input name="cpfPart" class="cpf" id="cpfPart" maxlength="14" type="text">
				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				    google.load('jquery', '1.3');
				</script>		
				<script type="text/javascript">
				$(function(){
					$('#cpfPart').blur(function(){
						if( $(this).val() ) {
							$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), ajax: 'true'}, function(j){
								if(j !== null){
									alert('CPF já cadastrado no sistema!');
									$('#cpfPart').val("");
								} 
								//jAlert('Cliente já cadastrado no sistema!', 'Alert Dialog');
							});
						} 
					});
				});
				</script>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>RG</legend>
				<input name="rgPart" type="text">
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td colspan="2">
				<fieldset>
				<legend>Nome</legend>
				<input name="nomePart" type="text" id="nomePart" style="width: 100%;">
				</fieldset>
			</td>
			<td colspan="1">
				<fieldset>
				<legend>Apelido</legend>
				<input name="apelidoPart" type="text">
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>Data de Nascimento</legend>
				<input name="dtNascPart" type="text" class="data" maxlength="10" id="datePart">
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Email</legend>
				<input name="emailPart" type="text" class="email" style="width: 100%;">
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>CEP</legend>
				<input name="cepPart" type="text" id="cepPart" class="cep" maxlength="10">
				</fieldset>
			</td>
			<td colspan="2">
				<fieldset>
				<legend>Endereço</legend>
				<input name="enderecoPart" type="text" id="ruaPart">
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>Número</legend>
				<input name="numeroPart" type="text">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Complemento</legend>
				<input name="complementoPart" type="text">
				</fieldset>
			</td>
			<td>
				<fieldset style="width: 150px;">
				<legend>Sexo</legend>
				<input name="sexoPart" type="radio" value="M" />Masculino
				<input name="sexoPart" type="radio" value="F" />Feminino
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>Estado</legend>
				<select name="cod_estadosPart" id="cod_estadosPart">
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
				<span class="carregandoPart">Aguarde, carregando...</span>
				<div id="cidadePart">
					<?php if($cidade !== '') { ?>
						<input type="text" name="cod_cidadesPart">
					<? } else { ?>
						<select name="cod_cidadesPart" id="cod_cidadesPart">
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
					$('#cod_estadosPart').change(function(){
						if( $(this).val() ) {
							$('#cidadePart').hide();
							$('.carregandoPart').show();
							$.getJSON('/sgpi/libs/cidades.ajax.php?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
								var options = '<select name="cod_cidadesPart" id="cod_cidadesPart"><option value=""></option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
								}	
								$('#cidadePart').html(options + "</select>").show();
								$('.carregandoPart').hide();
							});
						} else {
							$('#cidadePart').html('<select name="cod_cidadesPart" id="cod_cidadesPart"><option value="">– Escolha um estado –</option></select>');
						}
					});
				});
				</script>
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Bairro</legend>
				<input name="bairroPart" type="text" id="bairroPart">
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>Tel. Residencial</legend>
				<input name="tel1Part" type="text" value="<?php echo !empty($tel1)?$tel1:'';?>" class="phone1" maxlength="14">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Celular</legend>
				<input name="tel2Part" type="text" value="<?php echo !empty($tel2)?$tel2:'';?>" class="phone2" maxlength="16">
				</fieldset>
			</td>
		</tr>
		<tr class="participante" style="display: none;">
			<td>
				<fieldset>
				<legend>Renda</legend>
				<input name="rendaPart" type="text" placeholder="Renda" class="formatareais">
				</fieldset>
			</td>
			<td>
				<fieldset>
				<legend>Tipo Renda</legend>
				<div id="rendaPart">
					<select name="tipoRendaPart" id="tipoRendaPart">
						<option value="-">Selecione uma opção</option>
						<?php 
						while($row = mysqli_fetch_row($resultTipoRenda)){
							echo "<option value=".$row[0].">".utf8_encode($row[1])."</option>"; 
						}
						?>
					</select> 
				</div>
				<script type="text/javascript">
				$(function(){
					$('#tipoRendaPart').change(function(){
						if( $(this).val() === '21' ) {
							$('#outraRendaPart').show();
						} else {
							$('#outraRendaPart').hide();
						}
					});
				});
				</script>
				</fieldset>
			</td>
			<td id="outraRendaPart" style="display: none;">
				<fieldset>
				<legend>Qual?</legend>
				<input type="text" name="outraRendaPart" />
				</fieldset>
			</td>
		</tr>
		<?php 
			$result = mysqli_query($conn, "select d.id, 
										  d.descricao
								   from documento d
								   where d.tipo = 'C'");
			echo "<tr><td colspan=3 align=center><ins><b>Check-List Documentos</b></ins></td></tr>";
			echo "<tr bgcolor=#3954A5>";
			echo "<td align=center>DOCUMENTO</td>";
			echo "<td align=center>ENTREGUE</td>";
			echo "<td align=center>UPLOAD</td>";
			echo "</tr>";
			// printing table rows
			while($row = mysqli_fetch_row($result))
			{
				echo "<tr>";
				echo "<td>".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";
				echo "<td align=center><input type=hidden name=doc[] value=".$row[0]." /><input type=checkbox name=check".$row[0]." /></td>";
				echo "<td><input type=file name=arquivo".$row[0]." /></td>";
				echo "</tr>";			
			}

			mysqli_free_result($result);
		?>
		<tr>
			<td colspan="3" align="center" style="background-color: white;">
				<button type="submit">Cadastrar</button>
			</td>
		</tr>
	</table>
</form>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>