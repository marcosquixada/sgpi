<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$idOrcamento = $_GET['id'];
$cliente = $_GET['cliente'];
$status = strtoupper($_GET['status']);

$result = mysqli_query($conn, "SELECT os.id,
                              CONCAT(i.estado, ' - ', i.cidade, ' - ', i.logradouro, ', ', i.numero, ' - ', i.complemento, ' - ', i.cep), 
                              s.descricao,
							  concat('R$ ', replace(replace(replace(format(os.valor, 2), '.', '|'), ',', '.'), '|', ',')) AS valor,
							  concat('R$ ', replace(replace(replace(format(os.desconto, 2), '.', '|'), ',', '.'), '|', ',')) AS desconto,
							  date_format(os.data_cadastro, '%d/%m/%Y')
                       FROM orcamento_servico os, 
					        orcamento o, 
					        usuario u,
							imovel i,
                            servico s 
					   WHERE o.id = os.idOrcamento 
					     AND os.idOrcamento = ".$idOrcamento."
						 AND i.idCliente = o.idCliente
                         AND u.id = i.idCliente
					     AND s.id = os.idServico
						 order by os.id ");
if (!$result) {
    echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

echo "<h1>Orçamento: {$idOrcamento} - {$cliente}</h1>";

if($status === 'E')
	echo "<p align=center><button type=\"button\" onclick=\"window.location.href='cadastrar.php?idOrcamento=".$idOrcamento."'\">Novo</button><button type=\"button\" onclick=\"history.back();\">Voltar</button></p>";
else
	echo "<p align=center><button type=\"button\" onclick=\"history.back();\">Voltar</button></p>";

echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>IMOVEL</td>";
echo "<td>SERVIÇO</td>";
echo "<td>VALOR</td>";
echo "<td>DESCONTO</td>";
echo "<td>DATA CADASTRO</td>";
echo "</tr>";

while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
}
mysqli_free_result($result);

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>