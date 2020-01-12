<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
	
	$id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else {
        $id = $_POST['id'];
    }
	
	if ( null==$id ) {
        header("Location: /sgpi/cadastros/condominio");
    }
 
    if ( !empty($_POST)) {
        // keep track validation error
		$nomeError = null;
		
        // keep track post values
		$descricaoCond = $_POST['nome'];
		$responsavel = $_POST['responsavel'];
		$tipo = $_POST['tipo'];
		$qtdBl = $_POST['qtdBl'];
		$qtdCasas = $_POST['qtdCasas'];
		$tipo = $_POST['tipo'];
		$cep = $_POST['cep'];
		$estado = $_POST['cod_estados'];
		$cidade = $_POST['cod_cidades'];
		$bairro = $_POST['bairro'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		
		$andares = $_POST['andares'];
		$descricao = $_POST['descricao'];
		$unids = $_POST['unids'];
         
        // validate input
        $valid = true;
        
		if ($descricaoCond === '') {
			$nomeError = 'Por favor informe o Nome.';
			$valid = false;
		}
		if ($tipo === '-') {
			$tipoError = 'Por favor informe o Tipo.';
			$valid = false;
		}
		$sql = null;
        // insert data
        if ($valid) {
			$sql = "UPDATE condominio 
			        set descricao = '".$descricaoCond."',
					    responsavel = '".$responsavel."',
						tipo = '".$tipo."',
						qtde_blocos = '".$qtdBl."',
						qtde_casas = '".$qtdCasas."',
						tipo = '".$tipo."',
						cep = '".$cep."',
						estado = '".$estado."',
						cidade = '".$cidade."',
						bairro = '".$bairro."',
						logradouro = '".$endereco."',
						numero = '".$numero."'
					WHERE id = ".$id;
			mysqli_query($conn, $sql) or die(mysqli_error($conn));
			echo "<script>alert('Atualizado com sucesso!');window.location.href='/sgpi/cadastros/condominio';</script>";
        }
    } else {
		$query = "SELECT * FROM condominio WHERE id = ".$id;
		$sql = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array( $sql )) {
			$descricaoCond = $row['descricao'];
			$responsavel = $row['responsavel'];
			$tipo = $row['tipo'];
			$qtdBl = $row['qtde_blocos'];
			$qtdCasas = $row['qtde_casas'];
			$tipo = $row['tipo'];
			$cep = $row['cep'];
			$estado = $row['estado'];
			$cidade = $row['cidade'];
			$bairro = $row['bairro'];
			$endereco = $row['logradouro'];
			$numero = $row['numero'];
		}
	} 
?>

<form action="editar.php" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<table style="border: 0;">
<tr>
	<td colspan="2">
		<fieldset>
		<legend>Nome</legend>
		<input name="nome" type="text" style="width: 100%;" value="<?php echo !empty($descricaoCond)?$descricaoCond:'';?>">
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
		<?php if($tipo === 'V'){ ?>
			<option value="V" selected>Vertical</option>
			<option value="H">Horizontal</option>
		<? } else if($tipo === 'H'){ ?>
			<option value="V">Vertical</option>
			<option value="H" selected>Horizontal</option>
		<? } else { ?>
			<option value="V">Vertical</option>
			<option value="H">Horizontal</option>
		<? } ?>
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
		<?php if($tipo == 'V') { $styleVert = "display: block;"; $styleHoriz = "display: none;"; } else if($tipo == 'H') { $styleVert = "display: none;"; $styleHoriz = "display: block;"; } ?>
		<fieldset id="qtdeBlocos" style="<?php echo $styleVert; ?>">
		<legend>Qtde. Blocos</legend>
		<input name="qtdBl" type="text" id="qtdBl" class="preencheBlocos" value="<?php echo !empty($qtdBl)?$qtdBl:'';?>" maxlength="10">
		</fieldset>
		<fieldset id="qtdeCasas" style="<?php echo $styleHoriz; ?>">
		<legend>Qtde. Casas</legend>
		<input name="qtdCasas" type="text" id="qtdCasas" class="" value="<?php echo !empty($qtdCasas)?$qtdCasas:'';?>" maxlength="10">
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
		<?php if($cidade !== '') { ?>
			<input name="cod_cidades" type="text" value="<?php echo !empty($cidade)?$cidade:'';?>">
		<? } else { ?>
			<select name="cod_cidades" id="cod_cidades">
				<option value="">-- Escolha um estado --</option>
			</select>
		<? } ?>

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
<tr>
	<td colspan="3" align="center" style="background-color: white;">
		<button type="submit">Atualizar</button>
	</td>
</tr>
</table>
</form>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>