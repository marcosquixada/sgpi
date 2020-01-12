<?php 

include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); 

$queryOrigem = "SELECT id, descricao FROM origem";
$resultOrigem = mysqli_query($conn, $queryOrigem);

?>

<div>
<fieldset>
<legend>Filtro</legend>
<div>
	<form action="index.php" method="GET">
	<table style="border: 0;">
	<tr>
		<td>
			<fieldset>
			<legend>CNPJ</legend>
			<input name="cnpj" onblur="formatarCampo(this);if(!valida_cnpj(this.value)){alert('CNPJ Inválido!');this.value='';this.focus();}" maxlength="18" id="cnpj" type="text" value="<?php echo !empty($cnpj)?$cnpj:'';?>">
			<script src="http://www.google.com/jsapi"></script>
			<script type="text/javascript">
			  google.load('jquery', '1.3');
			</script>		
			<script type="text/javascript">
			$(function(){
				$('#cnpj').blur(function(){
					if( $(this).val() ) {
						//$('#nome').hide();
						//$('.carregando').show();
						$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cnpj: $(this).val(), ajax: 'true'}, function(j){
							//$('#nomeFantasia').val(j);
							$('#razaoSocial').val(j);
							//$('.carregando').hide();
						});
					} 
				});
			});
			</script>
			</fieldset>
		</td>
		<td colspan="2">
			<fieldset>
			<legend>Nome Fantasia</legend>
			<input name="nomeFantasia" id="nomeFantasia" type="text" style="width: 100%;" value="<?php echo !empty($nomeFantasia)?$nomeFantasia:'';?>">
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<fieldset>
			<legend>Razão Social</legend>
			<input name="razaoSocial" id="razaoSocial" type="text" value="<?php echo !empty($razaoSocial)?$razaoSocial:'';?>">
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
				<legend>Origem</legend>
				<select name="idOrigem" id="idOrigem1">
					<option value="-">Selecione uma opção</option>
					<?php 
					while($row = mysqli_fetch_row($resultOrigem))
						echo "<option value=".$row[0].">".$row[1]."</option>"; 
					?>
				</select> 
			</fieldset>
		</td>
		<td>
			<fieldset>
			<legend>Ativo</legend>
			<select name="ativo">
				<option value="-">Selecione uma opção</option>
				<option value="1">Sim</option>
				<option value="0">Não</option>
			</select> 
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Buscar" />
			<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
		</td>
	</tr>
	</table>
	</form>
</div>
</fieldset>
</div>

<?php

$result = mysqli_query($conn, "SELECT u.id, u.cnpj, 
                              u.razaoSocial,
							  u.responsavel,
							  u.email,
							  u.logradouro,
							  u.telefone1,
							  o.descricao, 
							  case u.ativo WHEN 0 THEN 'NÃO' ELSE 'SIM' END AS ativo
                       FROM usuario u, 
                            origem o
                       WHERE u.tipo = 'J'
					   AND u.idOrigem = o.id
					   AND u.dt_exclusao is null
					   AND ((upper(u.nomeFantasia) like UPPER('%".$_GET['nomeFantasia']."%')) or 
					        ('%".$_GET['nomeFantasia']."%' = '%%'))
					   AND ((upper(u.razaoSocial) like UPPER('%".$_GET['razaoSocial']."%')) or 
					        ('%".$_GET['razaoSocial']."%' = '%%'))
					   AND (('".$_GET['cnpj']."' = '') or 
					        ('".$_GET['cnpj']."' <> '' AND u.cnpj = '".$_GET['cnpj']."')) 
					   AND (('".$_GET['estado']."' = '') or 
					        ('".$_GET['estado']."' <> '' AND u.estado = '".$_GET['estado']."')) 
					   AND (('".$_GET['cidade']."' = '') or 
					        ('".$_GET['cidade']."' <> '' AND u.cidade = '".$_GET['cidade']."')) 
					   AND (('".$_GET['bairro']."' = '') or 
					        ('".$_GET['bairro']."' <> '' AND u.bairro = '".$_GET['bairro']."')) 
					   AND (('".$_GET['idOrigem']."' = '-') or 
					        ('".$_GET['idOrigem']."' <> '-' AND u.idOrigem = '".$_GET['idOrigem']."')) 
					   AND (('".$_GET['ativo']."' = '-') or 
					        ('".$_GET['ativo']."' <> '-' AND u.ativo = '".$_GET['ativo']."')) 
					   ");
if (!empty($_GET))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Construtoras</h1>";
echo "<table border='1' id='testTable'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>CNPJ</td>";
echo "<td>R. SOC.</td>";
echo "<td>RESP</td>";
echo "<td>EMAIL</td>";
echo "<td>LOGRADOURO</td>";
echo "<td>TEL COM</td>";
echo "<td>ORIGEM</td>";
echo "<td>ATIVO</td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
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

    echo "<td class='link'><a href=/sgpi/cadastros/construtora_condominio/index.php?idConstrutora=".$row[0]."><img src='/sgpi/_img/condominio.png' alt='Condomínios' title='Condomínios' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=editar.php?id=".$row[0]."><img src='/sgpi/_img/edit.png' alt='Editar' title='Editar' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href='javascript:;' id=".$row[0]." class='resetaSenha'><img src='/sgpi/_img/reset.png' alt='RESETAR SENHA' title='RESETAR SENHA' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/construtora/inativar.php?id=".$row[0]."><img src='/sgpi/_img/inactivate.png' alt='INATIVAR' title='INATIVAR' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/cliente_documento/index.php?idCliente=".$row[0]."><img src='/sgpi/_img/doc.png' alt='Documentação' title='Documentação' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[0]."&modelo=construtora&table=usuario><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
mysqli_free_result($result);

?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/jszip.min.js"></script>
<script type="text/javascript" src="/sgpi/js/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.16/jspdf.plugin.autotable.js"></script>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>