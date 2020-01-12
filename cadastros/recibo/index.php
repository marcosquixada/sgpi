<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php 

$queryOrigem = "SELECT id, descricao FROM origem";
$resultOrigem = mysqli_query($conn, $queryOrigem);

$queryServico = "SELECT id, descricao FROM servico";
$resultServico = mysqli_query($conn, $queryServico); 

?>

<form action="index.php" method="post">
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
		<select name="idOrigem" id="idOrigem1">
			<option value="-">Selecione uma opção</option>
			<?php 
			while($row = mysqli_fetch_row($resultOrigem)){
				if($row[0] === $idOrigem)
					echo "<option value=".$row[0]." selected>".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
				else
					echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
			}
			?>
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
					echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
			?>
		</select> 
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="3" align="center" style="background-color: white;">
		<input type="submit" value="Buscar" />
		<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
	</td>
</tr>
</table>
</form>

<?php
$sql = "SELECT date_format(r.data_cadastro, '%d/%m/%Y %h:%i:%s'), 
               r.id,
               IFNULL(u.cpf, u.cnpj), 
			   IFNULL(u.nome, u.razaoSocial),
			   s.descricao, 
			   concat('R$ ', replace(replace(replace(format(r.valor, 2), '.', '|'), ',', '.'), '|', ','))
	   FROM recibo r, 
			usuario u,
			servico s
	   WHERE r.idCliente = u.id 
		 AND r.idServico = s.id
		 AND ((upper(u.nome) like UPPER('%".$_POST['nome']."%')) or 
			  ('%".$_POST['nome']."%' = '%%'))
		 AND ((upper(u.nomeFantasia) like UPPER('%".$_POST['nomeFantasia']."%')) or 
			  ('%".$_POST['nomeFantasia']."%' = '%%'))
	     AND ((upper(u.razaoSocial) like UPPER('%".$_POST['razaoSocial']."%')) or 
			  ('%".$_POST['razaoSocial']."%' = '%%'))
	     AND (('".$_POST['cnpj']."' = '') or 
			  ('".$_POST['cnpj']."' <> '' AND u.cnpj = '".$_POST['cnpj']."')) 
		 AND (('".$_POST['cpf']."' = '') or 
			  ('".$_POST['cpf']."' <> '' AND u.cpf = '".$_POST['cpf']."')) 
	     AND (('".$_POST['idOrigem']."' = '-') or 
			  ('".$_POST['idOrigem']."' <> '-' AND u.idOrigem = '".$_POST['idOrigem']."')) 
		 AND (('".$_POST['idServico']."' = '-') or 
			  ('".$_POST['idServico']."' <> '-' AND r.idServico = '".$_POST['idServico']."')) 
	   ORDER BY r.id";
$result = mysqli_query($conn, $sql);
if (!empty($_POST))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

$fields_num = mysqli_num_fields($result);

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Recibos</h1>";
echo "<table cellspacing=\"0\" id='testTable'>";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>DATA CADASTRO</td>";
echo "<td>ID</td>";
echo "<td>CPF/CNPJ</td>";
echo "<td>NOME</td>";
echo "<td>SERVIÇO</td>";
echo "<td>VALOR</td>";
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
        echo "<td>".strtr(strtoupper($cell),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";

    echo "<td><a href=/sgpi/cadastros/recibo/recibo3.php?id=".$row[1]." target=\"_blank\"><img src='/sgpi/_img/pdf.png' alt='PDF' title='PDF' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[1]."&modelo=recibo&table=recibo><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
echo "</tbody>";
mysqli_free_result($result);

?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>