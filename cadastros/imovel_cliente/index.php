<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$idImovel = $_GET['idImovel'];

$query = "select ic.id, 
                 ic.idImovel, 
				 cliente.nome
          from imovel_cliente ic, 
		       usuario cliente 
		  where ic.idCliente = cliente.id
            and ic.idImovel = ".$idImovel;
$result = mysqli_query($conn, $query);

if (!$result) {
    echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

echo "<h1>Imóvel: {$idImovel}</h1>";

echo "<p align=center><button type=\"button\" onclick=\"var referrer = document.referrer; window.location.replace(referrer);\">Voltar</button></p>";

echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>IMOVEL</td>";
echo "<td>CLIENTE</td>";
echo "</tr>";

while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    echo "<td>".$row[0]."</td>";
	echo "<td>".$row[1]."</td>";
	echo "<td>".strtr(strtoupper($row[2]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td></tr>";
}
echo "</table><br><br>";
mysqli_free_result($result);

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>