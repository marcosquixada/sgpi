<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
 
    if ( !empty($_POST)) {
        // keep track validation error
		$nomeError = null;
		
        // keep track post values
		$descricaoCond = $_POST['nome'];
		$responsavel = $_POST['responsavel'];
		$tipo = $_POST['tipo'];
		$qtdBl = $_POST['qtdBl'];
		$qtdCasas = $_POST['qtdCasas'];
		$cep = $_POST['cep'];
		$estado = $_POST['cod_estados'];
		$cidade = $_POST['cod_cidades'];
		$bairro = $_POST['bairro'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		
		$andares = $_POST['andares'];
		$descricao = $_POST['descricao'];
		$unids = $_POST['unids'];
		$possuiTerreo = $_POST['possuiTerreo'];
		
        // validate input
        $valid = true;
        
		if ($descricao === '') {
			$nomeError = 'Por favor informe o Nome.';
			$valid = false;
		}
		
        //$uid = uniqid( rand(), true );
		//$data_ts = time();
		//$ativo = 1;
		$sql = null;
        // insert data
        if ($valid) {
			$sql = "INSERT INTO condominio (descricao, responsavel, tipo, qtde_blocos, qtde_casas, cep, estado, cidade, bairro, logradouro, numero) values('".$descricaoCond."', '".$responsavel."', '".$tipo."', '".$qtdBl."', '".$qtdCasas."', '".$cep."', '".$estado."', '".$cidade."', '".$bairro."', '".$endereco."', '".$numero."')";
			
			$res = mysqli_query($conn, $sql);
			if ($res) {
				$id = mysqli_insert_id($conn);
				
				//inserção dos blocos
				for($i = 0; $i < $qtdBl; $i++) {
					$sql = "INSERT INTO bloco (idCondominio, andares, descricao, possuiTerreo, unids_por_andar, total_unids, data_cadastro) values (".$id.", '".$andares[$i]."', '".$descricao[$i]."', '".$possuiTerreo[$i]."', '".$unids[$i]."', '".$andares[$i]*$unids[$i]."', '".date('Y-m-d')."')";
					
					mysqli_query($conn, $sql);
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
				
				mysqli_query($conn, $sql3) or die(mysqli_error($conn));
			
				// Criar as variaveis para validar o email
				//$url = sprintf( 'id=%s&email=%s&uid=%s&key=%s', $id, md5($email), md5($uid), md5($data_ts));

				//$mensagem = 'Para confirmar seu cadastro acesse o link:'."\n";
				//$mensagem .= sprintf('http://www.credimovelsi.com.br/sgpi/cadastros/usuario/ativar.php?%s',$url);

				// enviar o email
				//mail( $email, 'Confirmacao de cadastro', $mensagem );
				echo "<script>alert('Registro cadastrado com sucesso!');window.location.href='/sgpi/cadastros/condominio';</script>";
			}
        }
    }
?>

<form action="cadastrar.php" method="post" enctype="multipart/form-data">
<table style="border: 0;" id="tabela">
<tr>
	<td colspan="2">
		<fieldset>
		<legend>Nome</legend>
		<input name="nome" type="text" style="width: 100%;" value="<?php echo !empty($nome)?$nome:'';?>">
		<?php if (!empty($nomeError)): ?>
			<span><?php echo $nomeError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td>
		<fieldset>
		<legend>Tipo</legend>
		<select name="tipo" id="tipoCondominio">
		<option value="-">Selecione uma opção</option>
		<option value="V">Vertical</option>
		<option value="H">Horizontal</option>
		</select>
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Responsável</legend>
		<input name="responsavel" type="text" value="<?php echo !empty($responsavel)?$responsavel:'';?>">
		<?php if (!empty($responsavelError)): ?>
		<span><?php echo $responsavelError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td>
		<fieldset>
		<legend>CEP</legend>
		<input name="cep" type="text" id="cep" OnKeyUp="mascaraCep(this);" value="<?php echo !empty($cep)?$cep:'';?>" maxlength="10">
		<?php if (!empty($cepError)): ?>
			<span><?php echo $cepError;?></span>
		<?php endif; ?>
		</fieldset>
	</td>
	<td>
		<fieldset id="qtdeBlocos" style="display: none;">
		<legend>Qtde. Blocos</legend>
		<input name="qtdBl" type="text" id="qtdBl" class="preencheBlocos" value="<?php echo !empty($qtdBl)?$qtdBl:'';?>" maxlength="10">
		</fieldset>
		<fieldset id="qtdeCasas" style="display: none;">
		<legend>Qtde. Casas</legend>
		<input name="qtdCasas" type="text" id="qtdCasas" class="" maxlength="10">
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
<tr id="linha">
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
</table>

<?php 
			$result = mysqli_query($conn, "select d.id, 
										  d.descricao
								   from documento d
								   where d.tipo = 'M'");
			echo "<table><tr></tr>";
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