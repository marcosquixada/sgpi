<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<p align="center">
<?php if($_SESSION['tipo'] === 'T') { 

$result = mysqli_query($conn, "select p.id, 
							  u.cpf, 
							  u.nome, 
							  s.descricao, 
							  date_format(p.dtInicio, '%d/%m/%Y'), 
							  concat('R$ ', replace(replace(replace(format(sum(os.valor), 2), '.', '|'), ',', '.'), '|', ',')), 
							  hp.observacao,
							  date_format(hp.dtPrev, '%d/%m/%Y'),
							  date_format(hp.dtConclusao, '%d/%m/%Y')
                       from processo p, 
					        orcamento o, 
							orcamento_servico os,
					        historico_processo hp, 
							usuario u, 
							servico s
					   where p.id = hp.idProcesso 
					     and u.id = o.idCliente
						 and p.idOrcamento = o.id
						 and o.id = os.idOrcamento
						 and s.id = os.idServico
						 and hp.dtConclusao is null
						 and hp.idUsuAlteracao = ".$_SESSION['id']."
						 and hp.dtPrev < date_add(current_date(),INTERVAL 5 DAY)
					   group by p.id, 
								u.cpf, 
								u.nome, 
								s.descricao, 
								p.dtInicio, 
								hp.dtPrev
					   order by hp.dtPrev asc") or die(mysqli_error($conn));
					   
//$result = mysqli_query($conn, $sql);

echo "<h1>Atendimentos Pendentes</h1>";
echo "<table cellspacing=\"0\" id='testTable'>";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>CPF/CNPJ</td>";
echo "<td>NOME</td>";
echo "<td>SERVIÇO</td>";
echo "<td>INÍCIO</td>";
echo "<td>VALOR</td>";
echo "<td>OBS.</td>";
echo "<td>PREVISÃO</td>";
echo "<td>CONCLUSÃO</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr></thead><tbody>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

    echo "<td><a href=/sgpi/cadastros/historico_processo/index.php?idProcesso=".$row[0]."><img src='/sgpi/_img/historico.png' alt='Histórico' title='Histórico' height='15px' width='15px' /></a></td><td><a href=/sgpi/cadastros/processo/ex3.php?idProcesso=".$row[0]." target=\"_blank\"><img src='/sgpi/_img/pdf.png' alt='PDF' title='PDF' height='15px' width='15px' /></a></td></tr>\n";
}
echo "</tbody></table><br><br>";
mysqli_free_result($result);

} elseif($_SESSION['tipo'] === 'C') { 
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/relatorios/cliente/fichaCliente.php");
} elseif($_SESSION['tipo'] === 'J') {
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/relatorios/construtora/fichaConstrutora.php");
} else { 
$result = mysqli_query($conn, "select p.id, 
							  u.cpf, 
							  u.nome, 
							  s.descricao, 
							  date_format(p.dtInicio, '%d/%m/%Y'), 
							  concat('R$ ', replace(replace(replace(format(sum(os.valor), 2), '.', '|'), ',', '.'), '|', ',')), 
							  hp.observacao,
							  date_format(hp.dtPrev, '%d/%m/%Y'),
							  date_format(hp.dtConclusao, '%d/%m/%Y')
                       from processo p, 
					        orcamento o, 
							orcamento_servico os,
					        historico_processo hp, 
							usuario u, 
							servico s
					   where p.id = hp.idProcesso 
					     and u.id = o.idCliente
						 and p.idOrcamento = o.id
						 and o.id = os.idOrcamento
						 and s.id = os.idServico
						 and hp.dtConclusao is null
						 and hp.observacao <> 'PROCESSO INICIADO'
						 and hp.dtPrev <= date_add(current_date(),INTERVAL 5 DAY)
					   group by p.id, 
								u.cpf, 
								u.nome, 
								s.descricao, 
								p.dtInicio, 
								hp.dtPrev
					   order by hp.dtPrev asc") or die(mysqli_error($conn));
					   
//$result = mysqli_query($conn, $sql);

echo "<h1>Atendimentos Pendentes</h1>";
echo "<table cellspacing=\"0\" id='testTable'>";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>CPF/CNPJ</td>";
echo "<td>NOME</td>";
echo "<td>SERVIÇO</td>";
echo "<td>INÍCIO</td>";
echo "<td>VALOR</td>";
echo "<td>OBS.</td>";
echo "<td>PREVISÃO</td>";
echo "<td>CONCLUSÃO</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr></thead><tbody>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

    echo "<td><a href=/sgpi/cadastros/historico_processo/index.php?idProcesso=".$row[0]."><img src='/sgpi/_img/historico.png' alt='Histórico' title='Histórico' height='15px' width='15px' /></a></td><td><a href=/sgpi/cadastros/processo/ex3.php?idProcesso=".$row[0]." target=\"_blank\"><img src='/sgpi/_img/pdf.png' alt='PDF' title='PDF' height='15px' width='15px' /></a></td></tr>\n";
}
echo "</tbody></table><br><br>";
mysqli_free_result($result);
} ?>
</p>
</body>
</html>
