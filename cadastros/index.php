<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$table = $_GET["table"];
$tipo = $_GET["tipo"];
if(!isset($table))
	$table = Origem;

$query = null;

if(!isset($_GET['tipo']))
	$query = "SELECT * FROM {$table}";
else
	$query = "SELECT * FROM {$table} WHERE tipo = '{$_GET['tipo']}'";

$result = mysql_query($query);
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Table: {$table}</h1>";
echo "<input type=button onclick=\"window.location.href='/sgpi/cadastros/".$table."/cadastrar.php'\" value=Novo />";

echo "<table><tr>";
// printing table headers
if($table === 'Processo'){
	for($i=0; $i<$fields_num + 3; $i++)
	{
		$field = mysql_fetch_field($result);
		echo "<td bgcolor= #3954A5>{$field->name}</td>";
	}
	echo "</tr>\n";
} else {
	for($i=0; $i<$fields_num + 2; $i++)
	{
		$field = mysql_fetch_field($result);
		echo "<td bgcolor= #3954A5>{$field->name}</td>";
	}
	echo "</tr>\n";
}
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>$cell</td>";

	if($table === 'Processo')
		echo "<td><a href=/sgpi/cadastros/HistoricoProcesso/index.php?id=".$row[0].">Histórico</a></td><td><a href=/sgpi/cadastros/".$table."/editar.php?id=".$row[0].">Editar</a></td><td><a href=excluir.php?id=".$row[0]."&table=".$table.">Excluir</a></td></tr>\n";
	elseif($table === 'Usuario' and $tipo === 'J')
		echo "<td><a href=/sgpi/cadastros/Cliente_Construtora/index.php?idConstrutora=".$row[0].">Clientes</a></td><td><a href=/sgpi/cadastros/".$table."/editar.php?id=".$row[0].">Editar</a></td><td><a href=excluir.php?id=".$row[0]."&table=".$table.">Excluir</a></td></tr>\n";
	else 
		echo "<td><a href=/sgpi/cadastros/".$table."/editar.php?id=".$row[0].">Editar</a></td><td><a href=excluir.php?id=".$row[0]."&table=".$table.">Excluir</a></td></tr>\n";
}
mysql_free_result($result);

?>

</body>
</html>
