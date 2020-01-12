<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");

$idCondominio = $_GET['idCondominio'];
$condominio = null;
$result = mysqli_query($conn, "select nomeFantasia from usuario u where id = ".$idCondominio);
while($row = mysqli_fetch_row($result))
	$condominio = $row[0];
$result = mysqli_query($conn, "SELECT cc.id, 
                              u.cpf, 
							  u.nome,
							  u.email,
							  cc.apartamento, 
							  cc.bloco
                       FROM condominio_cliente cc,
					        usuario u
					   WHERE cc.idCondominio = ".$idCondominio."
					     AND cc.idCliente = u.id");
if (!$result) {
    echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

$fields_num = mysqli_num_fields($result);

echo "<h1>Condominio: ".$condominio."</h1>";
echo "<p align=center><button type=\"button\" onclick=\"window.location.href='cadastrar.php?idCondominio=".$idCondominio."'\">Novo</button><button type=\"button\" onclick=\"history.back();\">Voltar</button></p>";
echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>CPF</td>";
echo "<td>NOME</td>";
echo "<td>EMAIL</td>";
echo "<td>APARTAMENTO</td>";
echo "<td>BLOCO</td>";
echo "</tr>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
}
mysqli_free_result($result);

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>