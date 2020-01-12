<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<form action="index.php" method="post">
<div>
<fieldset>
<legend>Filtro</legend>
<div>
	<table style="border: 0;">
	<tr>
		<td>
			<fieldset>
			<legend>CPF</legend>
			<input name="cpf" maxlength="14" class="cpf" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
			</fieldset>
		</td>
		<td>
			<fieldset>
			<span class="carregando">Aguarde, carregando...</span>
			<legend>Nome</legend>
			<input name="nome" type="text" id="nome" value="<?php echo !empty($nome)?$nome:'';?>">
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
		<td colspan="3" align="center" style="background-color: white;">
			<input type="submit" name="submissao" value="Buscar" />
			<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
		</td>
	</tr>
	</table>
</div>
</fieldset>
</div>

<?php

$result = mysqli_query($conn, "SELECT u.id, 
							  u.nome, 
							  u.cpf, 
							  date_format(u.dataNascimento, '%d/%m/%Y'),
							  case u.sexo WHEN 'M' THEN 'MASCULINO' ELSE 'FEMININO' END AS sexo,
							  u.email,
							  u.telefone1,
							  u.telefone2,
							  case u.ativo WHEN 0 THEN 'NÃO' ELSE 'SIM' END AS ativo
					   FROM usuario u
					   WHERE u.tipo = 'A'
					   AND ((upper(u.nome) like UPPER('%".$_POST['nome']."%')) or 
							 ('%".$_POST['nome']."%' = '%%'))
					   AND (('".$_POST['cpf']."' = '') or 
						   ('".$_POST['cpf']."' <> '' AND u.cpf = '".$_POST['cpf']."')) 
					   AND (('".$_POST['ativo']."' = '-') or 
						   ('".$_POST['ativo']."' <> '-' AND u.ativo = '".$_POST['ativo']."')) 
						   ");				   

if (!empty($_POST))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

$fields_num = mysqli_num_fields($result);

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Administradores</h1>";
echo "<table border='1' id='testTable'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>NOME</td>";
echo "<td>CPF</td>";
echo "<td>DT NASC</td>";
echo "<td>SEXO</td>";
echo "<td>EMAIL</td>";
echo "<td>TEL RES</td>";
echo "<td>CEL</td>";
echo "<td>ATIVO</td>";
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
	echo "<td class='link'><a href=editar.php?id=".$row[0]."><img src='/sgpi/_img/edit.png' height='15px' width='15px' alt='Editar' title='Editar' /></a></td></tr>\n";
}
mysqli_free_result($result);

?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>
