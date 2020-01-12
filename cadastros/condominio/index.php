<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<div>
<fieldset>
<legend>Filtro</legend>
<div>
	<form action="index.php" method="GET">
	<table style="border: 0;">
	<tr>
		<td colspan="2">
			<fieldset>
			<legend>Nome</legend>
			<input name="nome" type="text" style="width: 100%;" value="<?php echo !empty($nome)?$nome:'';?>">
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset>
				<legend>Estado</legend>
				<select name="cod_estados" id="cod_estados">
					<option value=""></option>
					<?php
						$sql = "SELECT sigla
								FROM estados
								ORDER BY sigla";
						$res = mysqli_query($conn, $sql);
						while ( $row = mysqli_fetch_assoc( $res ) ) {
							if($row['sigla'] === $estado)
								echo "<option value=".$row['sigla']." selected>".$row['sigla']."</option>"; 
							else
								echo '<option value="'.$row['sigla'].'">'.$row['sigla'].'</option>';
						}
					?>
				</select>
			</fieldset>
		</td>
		<td>
			<fieldset>
				<legend>Cidade</legend>
				<span class="carregando">Aguarde, carregando...</span>
				<div id="cidade">
					<?php if($cidade !== '') { ?>
						<input type="text" value="<?php echo !empty($cidade)?$cidade:'';?>" name="cod_cidades">
					<? } else { ?>
						<select name="cod_cidades" id="cod_cidades">
							<option value="">-- Escolha um estado --</option>
						</select>
					<? } ?>
				</div>

				<script src="http://www.google.com/jsapi"></script>
				<script type="text/javascript">
				  google.load('jquery', '1.3');
				</script>		

				<script type="text/javascript">
				$(function(){
					$('#cod_estados').change(function(){
						if( $(this).val() ) {
							$('#cidade').hide();
							$('.carregando').show();
							$.getJSON('/sgpi/libs/cidades.ajax.php?search=',{cod_estados: $(this).val(), ajax: 'true'}, function(j){
								var options = '<select name="cod_cidades" id="cod_cidades"><option value=""></option>';	
								for (var i = 0; i < j.length; i++) {
									options += '<option value="' + j[i].nome + '">' + j[i].nome + '</option>';
								}	
								$('#cidade').html(options + "</select>").show();
								$('.carregando').hide();
							});
						} else {
							$('#cidade').html('<select name="cod_cidades" id="cod_cidades"><option value="">– Escolha um estado –</option></select>');
						}
					});
				});
				</script>
			</fieldset>
		</td>
		<td>
			<fieldset>
			<legend>Bairro</legend>
			<input name="bairro" type="text" value="<?php echo !empty($bairro)?$bairro:'';?>">
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" value="Buscar" />
			<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
		</td>
	</tr>
	</table>
	</form>
</div>
</fieldset>
</div>

<?php

$result = mysqli_query($conn, "SELECT c.id, 
                                      c.descricao, 
									  c.tipo, 
									  c.responsavel, 
									  c.qtde_blocos, 
									  c.qtde_casas, 
									  c.cep, 
									  c.estado, 
									  c.cidade, 
									  c.bairro, 
									  c.logradouro, 
									  c.numero
                       FROM condominio c
                       WHERE ((upper(c.descricao) like UPPER('%".$_GET['nome']."%')) or 
					        ('%".$_GET['nome']."%' = '%%'))
					   AND c.dt_exclusao is null
					   AND (('".$_GET['estado']."' = '') or 
					        ('".$_GET['estado']."' <> '' AND c.estado = '".$_GET['estado']."')) 
					   AND (('".$_GET['cidade']."' = '') or 
					        ('".$_GET['cidade']."' <> '' AND c.cidade = '".$_GET['cidade']."')) 
					   AND (('".$_GET['bairro']."' = '') or 
					        ('".$_GET['bairro']."' <> '' AND c.bairro = '".$_GET['bairro']."'))");
if (!empty($_GET))
{
	if (mysqli_num_rows($result) === 0) {
		echo "<script>alert('REGISTRO(S) NÃO ENCONTRADO(S)!');</script>";
	}
}

echo "<h1 align=center style='font-family:Calibri;font-weight:bold;'>Condominio</h1>";
echo "<table border='1' id='testTable'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>NOME</td>";
echo "<td>TIPO</td>";
echo "<td>RESP.</td>";
echo "<td>QTD. BL.</td>";
echo "<td>QTD. CASAS</td>";
echo "<td>CEP</td>";
echo "<td>ESTADO</td>";
echo "<td>CIDADE</td>";
echo "<td>BAIRRO</td>";
echo "<td>LOGRADOURO</td>";
echo "<td>NUM.</td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "<td class='link'></td>";
echo "</tr>";
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    foreach($row as $cell)
        echo strtr(strtoupper("<td>$cell</td>"),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

    echo "<td class='link'><a href=/sgpi/cadastros/imovel/index.php?idCondominio=".$row[0]."><img src='/sgpi/_img/cliente.png' alt='Clientes' title='Clientes' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=editar.php?id=".$row[0]."><img src='/sgpi/_img/edit.png' alt='Editar' title='Editar' height='15px' width='15px' /></a></td>";
	if($row[2] === 'V'){
		echo "<td class='link'><a href=/sgpi/cadastros/bloco/index.php?id=".$row[0]."><img src='/sgpi/_img/condominio.png' alt='Blocos' title='Blocos' height='15px' width='15px' /></a></td>";	
	} else {
		echo "<td class='link'></td>";	
	}	
	echo "<td class='link'><a href=/sgpi/cadastros/cliente_documento/index.php?idCliente=".$row[0]."><img src='/sgpi/_img/doc.png' alt='Documentação' title='Documentação' height='15px' width='15px' /></a></td>";
	echo "<td class='link'><a href=/sgpi/cadastros/excluir.php?id=".$row[0]."&modelo=condominio&table=condominio><img src='/sgpi/_img/excluir.png' alt='Excluir' title='Excluir' height='15px' width='15px' /></a></td></tr>\n";
}
mysqli_free_result($result);

?>
<tr><td colspan="16"><a href="#" id="btnExport"><button>XLS</button></a><input type="button" id="exportButton" value="PDF" />
</td></tr>
</table>
<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/jszip.min.js"></script>
<script type="text/javascript" src="/sgpi/js/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.16/jspdf.plugin.autotable.js"></script>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>