<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
session_start(); ?>

<?php 

$queryOrigem = "SELECT id, descricao FROM origem";
$resultOrigem = mysqli_query($conn, $queryOrigem);
	
$queryServico = "SELECT id, descricao FROM servico";
$resultServico = mysqli_query($conn, $queryServico); 

?>


<form action="index.php" method="GET">
<table>
<tr>
	<td>
		<fieldset>
		<legend>CPF</legend>
		<input name="cpf" onblur="formatarCampo(this);if(!TestaCPF(this.value.replace('.', '').replace('.', '').replace('-',''))){alert('CPF Inválido!');this.value='';}" maxlength="14" id="cpf" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
		<script src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load('jquery', '1.3');
		</script>		
		<script type="text/javascript">
		$(function(){
			$('#cpf').blur(function(){
				if( $(this).val() ) {
					$('#nome').hide();
					$('.carregando').show();
					$.getJSON('/sgpi/libs/cpfCnpj.ajax.php?search=',{cpf: $(this).val(), ajax: 'true'}, function(j){
						$('#nome').val(j);
						$('#nome').show();
						$('.carregando').hide();
					});
				} else {
					$('#cod_cidades').html('<option value="">– Escolha um estado –</option>');
				}
			});
		});
		</script>
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<span class="carregando">Aguarde, carregando...</span>
		<legend>Nome</legend>
		<input name="nome" type="text" id="nome" value="<?php echo !empty($nome)?$nome:'';?>">
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>CNPJ</legend>
		<input name="cnpj" id="cnpj" type="text" value="<?php echo !empty($cnpj)?$cnpj:'';?>">
		</fieldset>
	</td>
	<td colspan="2">
		<fieldset>
		<legend>Razão Social</legend>
		<input name="razaoSocial" type="text" style="width: 100%;" value="<?php echo !empty($razaoSocial)?$razaoSocial:'';?>">
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Origem</legend>
		<select name="idOrigem" id="idOrigem">
			<option value="-">Selecione uma opção</option>
			<?php 
			while($row = mysqli_fetch_row($resultOrigem))
				echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
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
	<td>
		<fieldset>
		<legend>Status</legend>
		<select name="idServico">
		<option value="E">EM APROVAÇÃO</option>
		<option value="-">Selecione uma opção</option>
		<option value="A">APROVADO</option>
		<option value="R">REPROVADO</option>
		</select> 
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Data Cadastro</legend>
		<input name="dataInicio" type="text" id="dataInicio" OnKeyUp="mascaraData(this);" onblur="testa(this, this.value);" maxlength="10" />
		a
		<input name="dataFim" type="text" id="dataFim" OnKeyUp="mascaraData(this);" onblur="testa(this, this.value);" maxlength="10" />
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
session_start();
$id = $_SESSION['id'];
$perfil = null;

$result = mysqli_query($conn, "select tipo from usuario where id = ".$id);
while($row = mysqli_fetch_row($result))
	$perfil = $row[0];

$sql = "SELECT date_format(o.data_cadastro, '%d/%m/%Y %h:%m:%s'),
			   o.id,
			   IFNULL(u.cpf, u.cnpj), 
			   IFNULL(u.nome, u.razaoSocial),
			   concat('R$ ', replace(replace(replace(format((select sum(os.valor)
															from orcamento_servico os 
															where os.idOrcamento = o.id), 2), '.', '|'), ',', '.'), '|', ',')) AS valorF,
			   o.status,
			   o.condCom, 
			   o.obs
	   FROM orcamento o,
			usuario u
	   WHERE o.idCliente = u.id 
	     AND o.dt_exclusao is null
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
		 AND (('".$_GET['idServico']."' = '-') or 
			  ('".$_GET['idServico']."' <> '-' AND o.status = '".$_GET['idServico']."')) 
		 AND (('".$_GET['dataInicio']."' = '') or
			  ('".date("Y-d-m",strtotime($_GET['dataInicio']))."' <> '' and o.data_cadastro between '".date("Y-d-m",strtotime($_GET['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_GET['dataFim']))."', INTERVAL 1 DAY)))
	   ORDER BY o.id";
$result = mysqli_query($conn, $sql) or die(mysql_error());
if (!empty($_GET))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}
$fields_num = mysqli_num_fields($result);

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Orçamentos</h1>";
echo "<table cellspacing=\"0\" id='testTable'>";
echo "<thead><tr bgcolor=#3954A5>";
echo "<td>DT. CADASTRO</td>";
echo "<td>ID</td>";
echo "<td>CPF/CNPJ</td>";
echo "<td>NOME/RAZÃO SOCIAL</td>";
echo "<td>VALOR</td>";
echo "<td>STATUS</td>";
echo "<td>COND. COM.</td>";
echo "<td>OBS.</td>";
echo "<td></td>";
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
	echo "<td>".$row[0]."</td>";
    echo "<td>".$row[1]."</td>";
	echo "<td>".$row[2]."</td>";
	echo "<td>".strtr(strtoupper($row[3]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";
	echo "<td>".$row[4]."</td>";
	if($row[5] === 'R')
		echo "<td><font color=\"red\">REPROVADO</font></td>";
	elseif($row[5] === 'A')
		echo "<td><font color=\"blue\">APROVADO</font></td>";
	else
		echo "<td>EM APROVAÇÃO</td>";
	echo "<td>".strtr(strtoupper($row[6]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";	
	echo "<td>".strtr(strtoupper($row[7]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";	
	if($row[5] === 'E' && $_SESSION['tipo'] === 'A')
		echo "<td><a href='javascript:void(0);'><img onClick='aprovacaoOrcamento({$row[1]});' src='/sgpi/_img/aprovado.png' height='15px' width='15px' alt='Aprovar' title='Aprovar' /></a></td><td><a href=/sgpi/cadastros/orcamento/reprovar.php?id=".$row[1]."><img src='/sgpi/_img/reprovado.png' height='15px' width='15px' alt='Reprovar' title='Reprovar' /></a></td>";
	else 
		echo "<td></td><td></td>";
	if($row[5] === 'E')
		echo "<td><a href=/sgpi/cadastros/orcamento/editar.php?id=".$row[1]."><img src='/sgpi/_img/edit.png' height='15px' width='15px' alt='Editar' title='Editar' /></a></td>";
	else
		echo "<td></td>";
	echo "<td><a href=/sgpi/cadastros/orcamento/download2.php?id=".$row[1]."><img src='/sgpi/_img/pdf.png' height='15px' width='15px' target='_blank' alt='PDF' title='PDF' /></a></td>";
	echo "<td><a href=/sgpi/cadastros/orcamento_servico/index.php?id=".$row[1]."&cliente=".str_replace(' ', '&nbsp;', $row[3])."&status=".$row[5]."><img src='/sgpi/_img/servico.png' height='15px' width='15px' alt='Serviços' title='Serviços' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[1]."&modelo=orcamento&table=orcamento><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
echo "</tbody>";
mysqli_free_result($result);

?>
<tr><td colspan="14"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<script>
function aprovacaoOrcamento(idOrcamento){
	var aprovar = '/sgpi/cadastros/orcamento/aprovar.php?id=' + idOrcamento;
	$.getJSON('/sgpi/libs/procAndamento.ajax.php?search=',{id: idOrcamento, ajax: 'true'}, function(i){
		if(i === 1){ //já existe processo em andamento
			if(confirm('Já existe um processo em andamento para este cliente! Deseja vincular os serviços?')){
				window.location.href = '/sgpi/libs/vinculaProcesso.ajax.php?idOrcamento=' + idOrcamento;
			}
		} else { //não existe
			window.location.href = aprovar;
		}
	});
}
</script>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>