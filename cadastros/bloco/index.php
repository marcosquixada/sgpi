<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$idCondominio = $_GET['id'];
$nome = $_GET['nome'];

$result = mysqli_query($conn, "SELECT b.id,
                                      c.descricao, 
									  b.andares, 
									  b.descricao, 
									  b.unids_por_andar, 
									  b.total_unids,
									  date_format(b.data_cadastro, '%d/%m/%Y')
                       FROM bloco b, 
					        condominio c
					   WHERE b.idCondominio = c.id 
					     AND b.idCondominio = ".$idCondominio."
					     order by b.id ");
if (!$result) {
    echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

echo "<h1>Condomínio: {$idCondominio}</h1>";

echo "<p align=center><button type=\"button\" onclick=\"history.back();\">Voltar</button></p>";

echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>CONDOMÍNIO</td>";
echo "<td>ANDARES</td>";
echo "<td>DESCRIÇÃO.</td>";
echo "<td>UNIDS. P/ ANDAR</td>";
echo "<td>TOTAL UNIDS.</td>";
echo "<td>DATA CADASTRO</td>";
echo "</tr>";

while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    foreach($row as $cell)
        echo "<td>".$cell."</td>";
}
mysqli_free_result($result);

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>