<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php 

$queryServico = "SELECT id, descricao FROM servico";
$resultServico = mysqli_query($conn, $queryServico); 

?>

<form action="index.php" method="GET">
<table>
<tr>
	<td>
		<fieldset>
		<legend>CPF</legend>
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
	<td>
		<fieldset>
		<legend>CNPJ</legend>
		<input name="cnpj" OnKeyUp="mascaraCnpj(this);" id="cnpj" maxlength="18" type="text" value="<?php echo !empty($cnpj)?$cnpj:'';?>">
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Razão Social</legend>
		<input name="razaoSocial" type="text" style="width: 100%" value="<?php echo !empty($razaoSocial)?$razaoSocial:'';?>">
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Origem</legend>
		<select name="idOrigem">
			<option value="-">Selecione uma opção</option>
			<option value="1">WhatsApp</option>
			<option value="2">Facebook</option>
			<option value="3">Instagram</option>
			<option value="4">Mídia Impressa</option>
			<option value="5">Indicação</option>
		</select> 
		</fieldset>
	</td>
	<td>
		<fieldset>
		<legend>Serviço</legend>
		<select name="idServico">
		<option value="-">Selecione uma opção</option>
			<?php 
				while($row = mysqli_fetch_row($resultServico))
					echo "<option value=".$row[0].">".utf8_encode($row[1])."</option>"; 
			?>
		</select> 
		</fieldset>
	</td>
	<td>
		<fieldset>
		<legend>Status</legend>
		<select name="status">
		<option value="I">INICIADO</option>
		<option value="-">Selecione uma opção</option>
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
$sql = "SELECT p.id,
			   p.idOrcamento,
               IFNULL(u.cpf, u.cnpj), 
			   IFNULL(u.nome, u.razaoSocial),
			   date_format(p.dtInicio, '%d/%m/%Y'),
			   date_format(p.dtConclusao, '%d/%m/%Y'),
			   concat('R$ ', replace(replace(replace(format((select sum(os.valor)
			                                                  from orcamento_servico os,
															       orcamento o
															 where os.idOrcamento = o.id
															   and o.id = p.idOrcamento 
															   and o.status = 'A'), 2), '.', '|'), ',', '.'), '|', ',')),
			   hp.observacao
	   FROM processo p, 
			usuario u, 
			orcamento o,
			historico_processo hp
	   WHERE o.idCliente = u.id 
		 AND u.ativo = '1' 
		 AND o.id = p.idOrcamento
		 AND hp.idProcesso = p.id
		 AND hp.dtAlteracao = (select max(hp2.dtAlteracao) 
		                       from historico_processo hp2 
							   where hp2.idProcesso = p.id)
		 AND p.dt_exclusao is null
		 AND ((upper(u.nome) like UPPER('%".$_GET['nome']."%')) or 
			  ('%".$_GET['nome']."%' = '%%'))
		 AND ((upper(u.nomeFantasia) like UPPER('%".$_GET['nomeFantasia']."%')) or 
			  ('%".$_GET['nomeFantasia']."%' = '%%'))
	     AND ((upper(u.razaoSocial) like UPPER('%".$_GET['razaoSocial']."%')) or 
			  ('%".$_GET['razaoSocial']."%' = '%%'))
	     AND (('".$_GET['cnpj']."' = '') or 
			  ('".$_GET['cnpj']."' <> '' AND u.cnpj = '".$_GET['cnpj']."')) 
		 AND (('".$_GET['cpf']."' = '') or 
			  ('".$_GET['cpf']."' <> '' AND u.cpf = '".$_GET['cpf']."')) 
	     AND (('".$_GET['idOrigem']."' = '-') or 
			  ('".$_GET['idOrigem']."' <> '-' AND u.idOrigem = '".$_GET['idOrigem']."')) 
		 AND (('".$_GET['status']."' = '-') or 
			  ('".$_GET['status']."' <> '-' AND p.status = '".$_GET['status']."')) 
		 AND (('".$_GET['idServico']."' = '-') or 
			  ('".$_GET['idServico']."' <> '-' AND exists (select 1 
			                                                from orcamento_servico os 
															where os.idOrcamento = o.id 
															  and os.idServico = '".$_GET['idServico']."'))) 
	   ORDER BY p.id";
$result = mysqli_query($conn, $sql) or die(mysql_error());
if (!empty($_GET))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

$fields_num = mysqli_num_fields($result);

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Processos</h1>";
echo "<table cellspacing=\"0\" id='testTable'>";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>ORCAMENTO</td>";
echo "<td>CPF/CNPJ</td>";
echo "<td>NOME</td>";
echo "<td>INÍCIO</td>";
echo "<td>FIM</td>";
echo "<td>VALOR</td>";
echo "<td>ÚLT. STATUS</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
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

    echo "<td class='link'><a href=/sgpi/cadastros/historico_processo/index.php?idProcesso=".$row[0]."><img src='/sgpi/_img/historico.png' alt='Histórico' title='Histórico' height='15px' width='15px' /></a></td>
	<td class='link'><a href=/sgpi/cadastros/processo/ex3.php?idProcesso=".$row[0]." target=\"_blank\"><img src='/sgpi/_img/pdf.png' alt='PDF' title='PDF' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/orcamento_servico/index.php?id=".$row[1]."&cliente=".str_replace(' ', '&nbsp;', $row[3])."><img src='/sgpi/_img/servico.png' height='15px' width='15px' alt='Serviços' title='Serviços' /></a></td>";
	echo "<td class='link'>";
	if(empty($row[5]))
		echo "<a href=/sgpi/cadastros/processo/finalizar.php?id=".$row[0]."><img src='/sgpi/_img/finalizar.png' height='15px' width='15px' alt='Finalizar' title='Finalizar' /></a>";
	echo "</td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[1]."&modelo=processo&table=processo><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
echo "</tbody>";
mysqli_free_result($result);

?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>