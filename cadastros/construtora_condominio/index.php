<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php
$idConstrutora = $_GET['idConstrutora'];
$construtora = null;
$result = mysqli_query($conn, "select razaoSocial from usuario where id = ".$idConstrutora);
while($row = mysqli_fetch_row($result))
	$construtora = $row[0];
$result = mysqli_query($conn, "SELECT cc.id, 
                              c.descricao,
							  c.responsavel, 
							  c.qtde_blocos,
							  c.cep,
							  c.estado, 
							  c.cidade, 
							  c.bairro, 
							  c.logradouro, 
							  c.numero
                       FROM construtora_condominio cc,
					        condominio c
					   WHERE cc.idConstrutora = ".$idConstrutora."
					     AND cc.idCondominio = c.id");
if (!$result) {
    echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

$fields_num = mysqli_num_fields($result);

echo "<h1>Lista de Condomínios - Construtora: ".$construtora."</h1>";
echo "<p align=center><button type=\"button\" onclick=\"window.location.href='cadastrar.php?idConstrutora=".$idConstrutora."'\">Novo</button></p>";
echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>DESCRIÇÃO</td>";
echo "<td>RESPONSÁVEL</td>";
echo "<td>QTD. BLOCOS</td>";
echo "<td>CEP</td>";
echo "<td>ESTADO</td>";
echo "<td>CIDADE</td>";
echo "<td>BAIRRO</td>";
echo "<td>ENDEREÇO</td>";
echo "<td>NUMERO</td>";
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