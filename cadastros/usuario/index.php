<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$table = $_GET["table"];

$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Table: {$table}</h1>";
echo "<p><a href=cadastrar.php>Novo</a></p>";
echo "<p><a href=/sgpi/index.php>Voltar</a></p>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num + 2; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td bgcolor= #3954A5>{$field->name}</td>";
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

    echo "<td><a href=editar.php?id=".$row[0].">Editar</a></td><td><a href=excluir.php?id=".$row[0].">Excluir</a></td></tr>\n";
}
mysql_free_result($result);

?>

</body>
</html>
