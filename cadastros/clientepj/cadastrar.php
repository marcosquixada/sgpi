<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	const MYSQL_DUPLICATE_KEY_ENTRY = 1022;
	const MYSQL_UNIQUE_KEY_ENTRY = 1062;
	
	$queryOrigem = "SELECT id, descricao FROM origem";
	$resultOrigem = mysqli_query($conn, $queryOrigem);
 
    if ( !empty($_POST)) {
        // keep track validation error
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
			$origemError = 'Por favor informe a Origem.';
			$valid = false;
		}
		if ($nomeFantasia === '') {
            $nomeFantasiaError = 'Por favor digite o nome de fantasia.';
            $valid = false;
        }
		if ($razaoSocial === '') {
            $razaoSocialError = 'Por favor digite a razão social.';
            $valid = false;
        }
        if ($cnpj === '') {
            $cnpjError = 'Por favor digite o cnpj.';
            $valid = false;
        }
        if ($email === '') {
            $emailError = 'Por favor digite o email.';
            $valid = false;
        }
        if ($cep === '') {
            $cepError = 'Por favor digite o cep.';
            $valid = false;
        }
        if ($endereco === '') {
            $enderecoError = 'Por favor digite o endereço.';
            $valid = false;
        }
		
        $uid = uniqid( rand(), true );
		$data_ts = time();
		$ativo = 1;
		$sql = null;
        // insert data
        if ($valid) {
			$sql = "INSERT INTO usuario (cnpj, idOrigem, razaoSocial, nomeFantasia, inscricaoEstadual, inscricaoMunicipal, email, cep, logradouro, estado, cidade, bairro, numero, complemento, responsavel, tipo, data_ts, uid, ativo, telefone1, telefone2) values('".$cnpj."', '".$idOrigem."', '".$razaoSocial."', '".$nomeFantasia."', '".$inscricaoEstadual."', '".$inscricaoMunicipal."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', '".$bairro."', ".$numero.", '".$complemento."', '".$responsavel."', 'U', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."')";
			
			$res = mysqli_query($conn, $sql);
			if (!$res) {
				$errno = mysqli_errno($conn);
				$error = mysqli_error($conn);
				switch ($errno) {
					case MYSQL_UNIQUE_KEY_ENTRY:
						echo "<script>alert('CNPJ JÁ CADASTRADO NO SISTEMA!');</script>";
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
				mail( $email, utf8_decode('CONFIRMAÇÃO DE CADASTRO CREDIMOVEL'), $mensagem );
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/clientepj';</script>";
			}
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
	<td colspan="2">
		<fieldset>
			<legend>CNPJ</legend>
			<input name="cnpj" class="cnpj" style="width: 100%;" maxlength="18" id="cnpj" type="text" value="<?php echo !empty($cnpj)?$cnpj:'';?>">
			<?php if (!empty($cnpjError)): ?>
				<span><?php echo $cnpjError;?></span>
			<?php endif; ?>
			<script src="http://www.google.com/jsapi"></script>
			<script type="text/javascript">
				google.load('jquery', '1.3');
			</script>		
			<script type="text/javascript">
			$(function(){
				$('#cnpj').blur(function(){
					if( $(this).val() ) {
						//alert($(this).val().replace(/\//g, '').replace(/\./g, '').replace(/\-/g, ''));
						$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cnpj: $(this).val(), ajax: 'true'}, function(j){
							if(j !== null){
								alert('CNPJ já cadastrado no sistema!');
								$('#cnpj').val("");
							}
							//jAlert('Cliente já cadastrado no sistema!', 'Alert Dialog');
						});
					} 
				});
			});
			</script>
		</fieldset>
	</td>
</tr>
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
		<input name="email" type="text" id="email" class="email" style="width: 100%;" value="<?php echo !empty($email)?$email:'';?>">
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
		<input name="endereco" style="width: 100%;" id="rua" type="text" value="<?php echo !empty($endereco)?$endereco:'';?>">
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
<?php 
			$result = mysqli_query($conn, "select d.id, 
										  d.descricao
								   from documento d
								   where d.tipo = 'U'");
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