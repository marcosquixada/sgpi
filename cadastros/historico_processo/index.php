<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$idProcesso = $_GET['idProcesso'];

$query = "select status
          from processo p
		  where p.id = ".$idProcesso;
$result = mysqli_query($conn, $query);
$status = null;
while($row = mysqli_fetch_row($result))
	$status = $row[0];

$result = mysqli_query($conn, "SELECT p.id,
                              p.idProcesso,
							  date_format(p.dtAlteracao, '%d/%m/%Y %H:%i:%s'),
							  u.apelido,
							  case p.status WHEN 'I' THEN 'INICIADO' WHEN 'A' THEN 'EM ATENDIMENTO' ELSE 'FINALIZADO' END AS status,
							  p.observacao,
							  date_format(p.dtPrev, '%d/%m/%Y'),
							  date_format(p.dtConclusao, '%d/%m/%Y')
                       FROM historico_processo p,
					        usuario u
					   WHERE p.idProcesso = ".$idProcesso."
					     AND u.id = p.idUsuAlteracao
						 order by p.id, p.dtAlteracao");
if (!$result) {
    echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
}

echo "<h1>Histórico Processo: {$idProcesso}</h1>";

if($status !== 'F')
	echo "<p align=center><button type=\"button\" onclick=\"window.location.href='cadastrar.php?idProcesso=".$idProcesso."'\">Novo</button><button type=\"button\" onclick=\"history.back();\">Voltar</button></p>";
else
	echo "<p align=center><button type=\"button\" class='voltar'>Voltar</button></p>";

echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>PROCESSO</td>";
echo "<td>ÚLTIMA ALTERAÇÃO</td>";
echo "<td>USU. ULT. ALT.</td>";
echo "<td>STATUS</td>";
echo "<td>OBSERVAÇÃO</td>";
echo "<td>DATA RETORNO</td>";
echo "<td>CONCLUSÃO</td>";
echo "<td></td>";
echo "</tr>";

while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    echo "<td>".$row[0]."</td>";
	echo "<td>".$row[1]."</td>";
	echo "<td>".$row[2]."</td>";
	echo "<td>".strtr(strtoupper($row[3]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";
	echo "<td>".strtoupper($row[4])."</td>";
	echo "<td>".strtr(strtoupper($row[5]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";
	echo "<td>".$row[6]."</td>";
	echo "<td id=dt".$row[0].">".$row[7]."</td>";
	if(empty($row[7]))
		echo "<td><a href=# class='finalizarAtendimento' id=".$row[0]."><img src='/sgpi/_img/finalizar.png' height='15px' width='15px' alt='Finalizar' title='Finalizar' /></a></td></tr>";
	else
		echo "<td></td></tr>";
}
echo "</table><br><br>";
mysqli_free_result($result);

?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>