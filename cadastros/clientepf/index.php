<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); 

$queryOrigem = "SELECT id, descricao FROM origem";
$resultOrigem = mysqli_query($conn, $queryOrigem);

?>

<form action="index.php" method="GET" style="width: 30%;">

<table>
<tr>
	<td>
		<fieldset>
		<legend>CPF</legend>
		<input name="cpf" onblur="formatarCampo(this);if(!TestaCPF(this.value.replace('.', '').replace('.', '').replace('-',''))){alert('CPF Inválido!');this.value='';}" maxlength="14" id="cpf" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
		<script src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load('jquery', '1.3');
		</script>		
		<script type="text/javascript">
		$(function(){
			$('#cpf').blur(function(){
				if( $(this).val() ) {
					$('#nome').hide();
					$('.carregando').show();
					$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), ajax: 'true'}, function(j){
						$('#nome').val(j);
						$('#nome').show();
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
		<span class="carregando">Aguarde, carregando...</span>
		<legend>Nome</legend>
		<input name="nome" type="text" id="nome" value="<?php echo !empty($nome)?$nome:'';?>">
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Origem</legend>
		<select name="idOrigem">
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
	<td></td>
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
	<td colspan="2" align="center" style="background-color: white;">
		<input type="submit" value="Buscar" />
		<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
	</td>
</tr>
</table>

</form>

<?php
session_start();
$id = $_SESSION['id'];
$perfil = null;

$result = mysqli_query($conn, "select tipo from usuario where id = ".$id);
while($row = mysqli_fetch_row($result))
	$perfil = $row[0];

$result = mysqli_query($conn, "SELECT date_format(u.data_cadastro, '%d/%m/%Y %H:%i:%s'), 
						  u.id, 
						  u.apelido, 
						  u.nome,
						  u.cpf, 
						  date_format(u.dataNascimento, '%d/%m/%Y'),
						  u.sexo,
						  u.telefone1,
						  u.telefone2,
						  o.descricao					  
				   FROM usuario u, 
						origem o
				   WHERE u.tipo = 'C'
					 AND u.idOrigem = o.id
					 AND u.ativo = '1'
					 AND u.dt_exclusao is null
					 AND ((upper(u.nome) like UPPER('%".$_GET['nome']."%')) or 
						 ('%".$_GET['nome']."%' = '%%'))
					 AND (('".$_GET['cpf']."' = '') or 
						 ('".$_GET['cpf']."' <> '' AND u.cpf = '".$_GET['cpf']."')) 
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
					 AND (('".$_GET['dataInicio']."' = '') or
		                  ('".date("Y-d-m",strtotime($_GET['dataInicio']))."' <> '' and u.data_cadastro between '".date("Y-d-m",strtotime($_GET['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_GET['dataFim']))."', INTERVAL 1 DAY)))
					 ORDER BY 2");
if (!empty($_GET))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Clientes PF</h1>";
echo "<table border='1' id='testTable'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>DATA CADASTRO</td>";
echo "<td>ID</td>";
echo "<td>APELIDO</td>";
echo "<td>NOME</td>";
echo "<td>CPF</td>";
echo "<td>DT NASC</td>";
echo "<td>SEXO</td>";
echo "<td>TEL RES</td>";
echo "<td>CEL</td>";
echo "<td>ORIGEM</td>";
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

    echo "<td class='link'><a href=editar.php?id=".$row[1]."&sexo=".$row[6]."><img src='/sgpi/_img/edit.png' alt='Editar' title='Editar' height='15px' width='15px' /></a></td>";
	//echo "<td><a href=/sgpi/cadastros/clientepf/ex.php?idCliente=".$row[1]." target='_blank'><img src='/sgpi/_img/pdf.png' alt='PDF' title='PDF' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/clientepf/inativar.php?id=".$row[1]."><img src='/sgpi/_img/inactivate.png' alt='INATIVAR' title='INATIVAR' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href='javascript:;' id='$row[1]' class='resetaSenha'><img src='/sgpi/_img/reset.png' alt='RESETAR SENHA' title='RESETAR SENHA' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/cliente_documento/index.php?idCliente=".$row[1]."><img src='/sgpi/_img/doc.png' alt='DOCUMENTAÇÃO' title='DOCUMENTAÇÃO' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[1]."&modelo=clientepf&table=usuario><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
mysqli_free_result($result);
//echo "</table>";
?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>