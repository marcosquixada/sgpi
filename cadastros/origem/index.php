<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<div>
<fieldset>
<legend>Filtro</legend>
<div>
	<form action="index.php" method="post">
	<table style="border: 0;">
	<tr>
		<td colspan="3">
			<fieldset style="width: 450px;">
			<legend>Descrição</legend>
			<input name="descricao" type="text">
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
</div>
</fieldset>
</div>

<?php

$result = mysql_query("SELECT * 
                       FROM origem o 
					   WHERE ((upper(o.descricao) like UPPER('%".$_POST['descricao']."%')) or 
					        ('%".$_POST['descricao']."%' = '%%'))");
if (!empty($_POST))
{
	if (mysql_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

$fields_num = mysql_num_fields($result);

echo "<h1>Origem</h1>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num + 1; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td bgcolor=#3954A5>{$field->name}</td>";
}
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>$cell</td>";

    echo "<td><a href=editar.php?id=".$row[0].">Editar</a></td></tr>\n";
}
mysql_free_result($result);

?>

</body>
</html>
