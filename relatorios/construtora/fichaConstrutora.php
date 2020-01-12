<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php"); 

$idConstrutora = $_SESSION['id'];
?>

<form action="index.php" method="post">
<table>
<tr>
	<td>
		<fieldset>
		<legend>CPF Cliente</legend>
		<input name="cpf" OnKeyUp="mascaraCpf(this);" id="cpf" maxlength="14" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Nome</legend>
		<input name="nome" type="text" style="width: 100%" value="<?php echo !empty($nome)?$nome:'';?>">
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="2">
		<fieldset>
		<legend>Condomínio</legend>
		<input name="descricao" type="text" style="width: 100%" value="<?php echo !empty($descricao)?$descricao:'';?>">
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Intervalo</legend>
		<input name="dataInicio" type="text" id="dataInicio" OnKeyUp="mascaraData(this);" onblur="testa(this, this.value);" maxlength="10" />
		a
		<input name="dataFim" type="text" id="dataFim" OnKeyUp="mascaraData(this);" onblur="testa(this, this.value);" maxlength="10" />
		</fieldset>	
	</td>
	<td>
		<fieldset>
		<legend>Status Processo</legend>
		<select name="status">
		<option value="-">Selecione uma opção</option>
		<option value="A">ANDAMENTO</option>
		<option value="F">FINALIZADO</option>
		</select> 
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="3" align="center" style="background-color: white;">
		<input type="submit" value="Buscar" />
	</td>
</tr>
</table>
</form>

<?php
$sql = "SELECT pf.nome,
			   cond.descricao, 
               b.descricao, 
			   i.complemento, 
			   p.id,
			   s.descricao, 
			   pf.telefone1, 
			   pf.telefone2, 
			   date_format(pf.data_contrato, '%d/%m/%Y'), 
			   hp.observacao, 
			   date_format(hp.dtPrev, '%d/%m/%Y')
        FROM usuario pf, 
		     usuario c, 
			 condominio cond, 
			 bloco b,
			 processo p, 
			 historico_processo hp, 
			 orcamento o, 
			 orcamento_servico os, 
			 servico s,
			 construtora_condominio cc2, 
			 imovel i,
			 imovel_cliente ic
		WHERE pf.id = ic.idCliente 
		  AND ic.idImovel = i.id
		  AND c.id  = cc2.idConstrutora
		  AND cc2.idCondominio = i.idCondominio
		  AND cond.id = i.idCondominio
		  AND cond.id = cc2.idCondominio
		  AND p.id = hp.idProcesso
		  AND hp.id = (select max(hp2.id) 
		               from historico_processo hp2 
					   where hp2.idProcesso = hp.idProcesso)
		  AND p.idOrcamento = o.id
		  AND o.id = os.idOrcamento
		  AND s.id = os.idServico
		  AND pf.id = o.idCliente
		  AND o.status = 'A'
		  AND s.id in (1,5,7)
		  AND i.idBloco = b.id
		  AND c.id = $idConstrutora
		  AND ((upper(pf.nome) like UPPER('%".$_POST['nome']."%')) or 
			  ('%".$_POST['nome']."%' = '%%'))
		  AND ((upper(cond.descricao) like UPPER('%".$_POST['descricao']."%')) or 
			  ('%".$_POST['descricao']."%' = '%%'))
	      AND (('".$_POST['cpf']."' = '') or 
			  ('".$_POST['cpf']."' <> '' AND pf.cpf = '".$_POST['cpf']."')) 
	      AND (('".$_POST['status']."' = '-') or 
			  ('".$_POST['status']."' <> '-' AND p.status = '".$_POST['status']."')) 
		  AND (('".$_POST['dataInicio']."' = '') or
			  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and p.dtConclusao between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
	   ORDER BY hp.dtPrev DESC";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$num_rows = mysqli_num_rows($result);

if (!empty($_POST))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

$fields_num = mysqli_num_fields($result);

echo "<h1>Relatório Construtora</h1>";
echo "<table cellspacing=\"0\" id='testTable'>";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>CLIENTE</td>";
echo "<td>CONDOMINIO</td>";
echo "<td>BLOCO</td>";
echo "<td>AP</td>";
echo "<td>PROCESSO</td>";
echo "<td>SERVIÇO</td>";
echo "<td>TEL. RES.</td>";
echo "<td>CEL.</td>";
echo "<td>DATA CONTRATO</td>";
echo "<td>OBSERVAÇÃO</td>";
echo "<td>PREVISÃO</td>";
echo "<td class='link'></td>";
echo "</tr></thead><tbody>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
	echo "<td class='link'><a href=/sgpi/cadastros/processo/ex3.php?idProcesso=".$row[4]." target=\"_blank\"><img src='/sgpi/_img/pdf.png' alt='PDF' title='PDF' height='15px' width='15px' /></a></td>";
    echo "</tr>";
    
}
echo "</tbody></table>";
mysqli_free_result($result);



$sql = "SELECT hp.observacao, 
			   count(*),
			   floor(count(*)/$num_rows*100)
        FROM usuario pf, 
		     usuario c, 
			 condominio cond, 
			 bloco b,
			 processo p, 
			 historico_processo hp, 
			 orcamento o, 
			 orcamento_servico os, 
			 servico s,
			 construtora_condominio cc2, 
			 imovel i,
			 imovel_cliente ic
		WHERE pf.id = ic.idCliente 
		  AND i.id = ic.idImovel
		  AND c.id  = cc2.idConstrutora
		  AND cc2.idCondominio = i.idCondominio
		  AND cond.id = i.idCondominio
		  AND cond.id = cc2.idCondominio
		  AND p.id = hp.idProcesso
		  AND hp.id = (select max(hp2.id) 
		               from historico_processo hp2 
					   where hp2.idProcesso = hp.idProcesso)
		  AND p.idOrcamento = o.id
		  AND o.id = os.idOrcamento
		  AND s.id = os.idServico
		  AND pf.id = o.idCliente
		  AND o.status = 'A'
		  AND s.id in (1,5,7)
		  AND i.idBloco = b.id
		  AND c.id = $idConstrutora
		  AND ((upper(pf.nome) like UPPER('%".$_POST['nome']."%')) or 
			  ('%".$_POST['nome']."%' = '%%'))
		  AND ((upper(cond.descricao) like UPPER('%".$_POST['descricao']."%')) or 
			  ('%".$_POST['descricao']."%' = '%%'))
	      AND (('".$_POST['cpf']."' = '') or 
			  ('".$_POST['cpf']."' <> '' AND pf.cpf = '".$_POST['cpf']."')) 
	      AND (('".$_POST['status']."' = '-') or 
			  ('".$_POST['status']."' <> '-' AND p.status = '".$_POST['status']."')) 
		  AND (('".$_POST['dataInicio']."' = '') or
			  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and p.dtConclusao between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
	   GROUP BY hp.observacao";
	   
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

echo "<br><br><table cellspacing=\"0\">";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>STATUS</td>";
echo "<td>QTDE. CLIENTES</td>";
echo "<td>%</td>";
echo "</tr></thead><tbody>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    echo "</tr>";
    
}
echo "</tbody></table>";
mysqli_free_result($result);

?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>
