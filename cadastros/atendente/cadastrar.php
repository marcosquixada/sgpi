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
		$apelidoError = null;
         
        // keep track post values
		$nome = $_POST['nome'];
		$cpf = $_POST['cpf'];
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
		$dataAdmissao = date("Y-m-d", strtotime(str_replace('/','-', $_POST['dataAdmissao'])));
		$funcao = $_POST['funcao'];
		$apelido = $_POST['apelido'];
         
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
		if (empty($apelido)) {
            $apelidoError = 'Por favor digite o apelido.';
            $valid = false;
        }
        
		$uid = uniqid( rand(), true );
		$data_ts = time();
		$ativo = 1;
		$sql = null;
        // insert data
		//$db->beginTransaction();
        if ($valid) {
			$sql = "INSERT INTO usuario (nome, cpf, dataNascimento, sexo, email, cep, logradouro, estado, cidade, numero, complemento, tipo, data_ts, uid, ativo, telefone1, telefone2, data_admissao, funcao, apelido) values ('".$nome."', '".$cpf."', '".$dtNasc."', '".$sexo."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', ".$numero.", '".$complemento."', 'T', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."', '".$dataAdmissao."', '".$funcao."', '".$apelido."')";
			
			$res = mysqli_query($conn,$sql) or die(mysqli_error($conn));
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
				mail( $email, 'CONFIRMAÇÃO DE CADASTRO CREDIMOVEL', $mensagem );
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/atendente';</script>";
			}
        }
		//$db->commit();
    }
?>

<form action="cadastrar.php" method="post" enctype="multipart/form-data">
<table style="border: 0;">
<tr>
	<td>
		<fieldset>
			<legend>CPF</legend>
			<input name="cpf" class="cpf" id="cpf" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
			<script src="http://www.google.com/jsapi"></script>
			<script type="text/javascript">
				google.load('jquery', '1.3');
			</script>		
			<script type="text/javascript">
			$(function(){
				$('#cpf').blur(function(){
					if( $(this).val() ) {
						$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), tipo: 'T', ajax: 'true'}, function(j){
							if(j !== null){
								alert('CPF já cadastrado no sistema!');
								$('#cpf').val("");
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
			<input name="dtNasc" type="text" value="<?php echo !empty($dtNasc)?$dtNasc:'';?>" class="data" maxlength="10">
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
			<input name="cep" type="text" id="cep" class="cep" value="<?php echo !empty($cep)?$cep:'';?>" maxlength="10">
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
		<fieldset style="text-align: center; margin: 0 auto;">
			<legend>Sexo</legend>
			<input name="sexo" type="radio" value="M" <?php echo ($sexo=='M')?'checked':'' ?> />Masculino
			<input name="sexo" type="radio" value="F" <?php echo ($sexo=='F')?'checked':'' ?>/>Feminino
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
	<td>
		<fieldset>
			<legend>Data de Admissão</legend>
				<input name="dataAdmissao" type="text" value="<?php echo !empty($dataAdmissao)?$dataAdmissao:'';?>" class="data" maxlength="10">
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="2">
		<fieldset>
			<legend>Função</legend>
				<input name="funcao" type="text" value="<?php echo !empty($funcao)?$funcao:'';?>" maxlength="20">
		</fieldset>
	</td>
	<td>
		<fieldset>
			<legend>Apelido</legend>
				<input name="apelido" type="text" value="<?php echo !empty($apelido)?$apelido:'';?>" maxlength="20">
				<?php if (!empty($apelidoError)): ?>
					<span><?php echo $apelidoError;?></span>
				<?php endif; ?>
		</fieldset>
	</td>
</tr>
<?php 
			$result = mysqli_query($conn, "select d.id, 
										  d.descricao
								   from documento d
								   where d.tipo = 'T'");
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