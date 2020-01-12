<?php 

include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); 

$queryCliente = "SELECT id, nome FROM usuario WHERE tipo = 'C' AND ativo = 1";
$resultCliente = mysqli_query($conn, $queryCliente);

$queryCondominio = "SELECT id, descricao FROM condominio order by 2";
$resultCondominio = mysqli_query($conn, $queryCondominio);

$idCondominio = null;
if ( !empty($_GET['idCondominio'])) {
    $idCondominio = $_REQUEST['idCondominio'];
} else {
    $idCondominio = $_POST['idCondominio'];
}

?>

<form action="index.php" method="post" style="width: 30%;">

<table>
<tr>
	<td colspan="2">
		<fieldset>
			<legend>Cliente</legend>
			<input type="hidden" name="idCliente" class="idCliente" />
			<input name="nomeCliente" class="ui-widget" type="text" id="nomeCliente">
			<?php if (!empty($clienteError)): ?>
				<span><?php echo $clienteError;?></span>
			<?php endif; ?>
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="3">
		<fieldset>
		<legend>Condomínio</legend>
		<select name="idCondominio" id="idCondominio">
		<option value="-">Selecione uma opção</option>
		<?php 
			while($row = mysqli_fetch_row($resultCondominio)){
				if($row[0] == $idCondominio)
					echo "<option value=".$row[0]." selected>".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
				else
					echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
			}
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
</tr>
<tr>
	<td colspan="3" class="tdBloco" style="display: none;">
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
</tr>
<tr>
	<td>
		<fieldset>
		<legend>CEP</legend>
		<input name="cep" type="text" id="cep" onkeyup="mascaraCep(this);" maxlength="10" value="<?php echo !empty($cep)?$cep:'';?>">
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Endereço</legend>
		<input name="endereco" type="text" id="rua" value="<?php echo !empty($endereco)?$endereco:'';?>">
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
		<legend>Data Cadastro</legend>
		<input name="dataInicio" type="text" id="dataInicio" OnKeyUp="mascaraData(this);" maxlength="10" />
		a
		<input name="dataFim" type="text" id="dataFim" OnKeyUp="mascaraData(this);" maxlength="10" />
		</fieldset>	
	</td>
</tr>
<tr>
	<td colspan="3" align="center" style="background-color: white;">
		<input type="submit" value="Buscar" />
		<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
	</td>
</tr>
</table>

</form>

<?php
$result = mysqli_query($conn, "SELECT date_format(i.data_cadastro, '%d/%m/%Y'), 
						  i.id, 
						  u.nome, 
						  c.descricao as condominio,
						  b.descricao as bloco,
						  CASE i.cep WHEN '' THEN c.cep ELSE i.cep END, 
						  CASE i.estado WHEN '' THEN c.estado ELSE i.estado END, 
						  CASE i.cidade WHEN '' THEN c.cidade ELSE i.cidade END, 
						  CASE i.bairro WHEN '' THEN c.bairro ELSE i.bairro END, 
						  CASE i.logradouro WHEN '' THEN c.logradouro ELSE i.logradouro END, 
						  CASE i.numero WHEN '' THEN c.numero ELSE i.numero END, 
						  i.complemento
				   FROM imovel i
                   LEFT JOIN condominio c
						ON i.idCondominio = c.id
				   LEFT JOIN bloco b
				        ON i.idBloco = b.id
				   INNER JOIN imovel_cliente ic 
				        ON ic.idImovel = i.id
				   INNER JOIN usuario u
				        ON ic.idCliente = u.id
				   WHERE (('".$_POST['idCliente']."' = '-' or '".$_POST['idCliente']."' = '') or 
						 ('".$_POST['idCliente']."' <> '-' AND ic.idCliente = '".$_POST['idCliente']."')) 
					 AND (('".$_POST['idCondominio']."' = '-') or 
						 ('".$_POST['idCondominio']."' <> '-' AND i.idCondominio = '".$idCondominio."')) 
					 AND (('".$_POST['idBloco']."' = '-' or '".$_POST['idBloco']."' = '') or 
						 ('".$_POST['idBloco']."' <> '-' AND i.idBloco = '".$_POST['idBloco']."')) 
					 AND (('".$_POST['cep']."' = '') or 
						 ('".$_POST['cep']."' <> '' AND i.cep = '".$_POST['cep']."')) 
					 AND (('".$_POST['estado']."' = '') or 
						 ('".$_POST['estado']."' <> '' AND i.estado = '".$_POST['estado']."')) 
					 AND (('".$_POST['cidade']."' = '') or 
						 ('".$_POST['cidade']."' <> '' AND i.cidade = '".$_POST['cidade']."')) 
					 AND (('".$_POST['bairro']."' = '') or 
						 ('".$_POST['bairro']."' <> '' AND i.bairro = '".$_POST['bairro']."')) 
					 AND (('".$_POST['dataInicio']."' = '') or
		                  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and i.data_cadastro between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
					 ") or die(mysql_error());
if (!empty($_POST))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Imóveis</h1>";
echo "<table border='1' id='testTable'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>DATA CADASTRO</td>";
echo "<td>ID</td>";
echo "<td>CLIENTE</td>";
echo "<td>CONDOMÍNIO</td>";
echo "<td>BLOCO</td>";
echo "<td>CEP</td>";
echo "<td>ESTADO</td>";
echo "<td>CIDADE</td>";
echo "<td>BAIRRO</td>";
echo "<td>LOGRADOURO</td>";
echo "<td>NUMERO</td>";
echo "<td>COMPLEMENTO</td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "</tr>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

    echo "<td class='link'><a href=editar.php?id=".$row[1]."&sexo=".$row[6]."><img src='/sgpi/_img/edit.png' alt='Editar' title='Editar' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/imovel_cliente/index.php?idImovel=".$row[1]."><img src='/sgpi/_img/cliente.png' alt='Clientes' title='Clientes' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[1]."&modelo=imovel&table=imovel><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
mysqli_free_result($result);
?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>