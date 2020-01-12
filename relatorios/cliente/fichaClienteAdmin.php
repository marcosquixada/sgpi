<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");

	$queryOrigem = "SELECT id, descricao FROM origem";
	$resultOrigem = mysql_query($queryOrigem);
	
?>	
<form action="fichaClienteAdmin.php" method="post" style="width: 30%;" onsubmit="if(document.forms[0].cpf.value == ''){alert('Preencha o CPF!');return false;}else{return true;}">

<table>
<tr>
	<td>
		<fieldset>
		<legend>CPF</legend>
		<input name="cpf" OnKeyUp="mascaraCpf(this);" onblur="if(!TestaCPF(this.value.replace('.', '').replace('.', '').replace('-',''))){alert('CPF Inválido!');this.value='';}" maxlength="14" id="cpf" type="text" value="<?php echo !empty($cpf)?$cpf:'';?>">
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
	<td>
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
		<legend>Origem</legend>
		<select name="idOrigem">
			<option value="-">Selecione uma opção</option>
			<?php 
			while($row = mysql_fetch_row($resultOrigem))
				echo "<option value=".$row[0].">".$row[1]."</option>"; 
			?>
		</select> 
		</fieldset>
	</td>
	<td>
		<fieldset>
		<legend>Ativo</legend>
		<select name="ativo">
			<option value="-">Selecione uma opção</option>
			<option value="1">Sim</option>
			<option value="0">Não</option>
		</select> 
		</fieldset>
	</td>
	<td></td>
</tr>
<tr>
	<td>
		<fieldset>
		<legend>Data Cadastro</legend>
		<input name="dataInicio" type="text" id="dataInicio" OnKeyUp="mascaraData(this);" maxlength="10" />
		a
		<input name="dataFim" type="text" id="dataFim" OnKeyUp="mascaraData(this);" maxlength="10" />
		</fieldset>	
	</td>
</tr>
<tr>
	<td colspan="2" align="center" style="background-color: white;">
		<input type="submit" value="Buscar" />
		<input type="button" onclick="window.location.href='cadastrar.php'" value="Novo">
	</td>
</tr>
</table>

</form>
<?php 

if (!empty($_POST))	{
		$result = mysql_query("SELECT date_format(u.data_cadastro, '%d/%m/%Y %H:%i:%s'), 
							  u.id, 
							  u.apelido, 
							  u.nome,
							  u.cpf, 
							  date_format(u.dataNascimento, '%d/%m/%Y'),
							  u.sexo,
							  u.email,
							  u.telefone1,
							  u.telefone2,
							  o.descricao, 
							  case u.ativo WHEN 0 THEN 'NÃO' ELSE 'SIM' END AS ativo						  
					   FROM usuario u, 
							origem o
					   WHERE u.tipo = 'C'
						 AND u.idOrigem = o.id
						 AND ((upper(u.nome) like UPPER('%".$_POST['nome']."%')) or 
							 ('%".$_POST['nome']."%' = '%%'))
						 AND (('".$_POST['cpf']."' = '') or 
							 ('".$_POST['cpf']."' <> '' AND u.cpf = '".$_POST['cpf']."')) 
						 AND (('".$_POST['estado']."' = '') or 
							 ('".$_POST['estado']."' <> '' AND u.estado = '".$_POST['estado']."')) 
						 AND (('".$_POST['cidade']."' = '') or 
							 ('".$_POST['cidade']."' <> '' AND u.cidade = '".$_POST['cidade']."')) 
						 AND (('".$_POST['bairro']."' = '') or 
							 ('".$_POST['bairro']."' <> '' AND u.bairro = '".$_POST['bairro']."')) 
						 AND (('".$_POST['idOrigem']."' = '-') or 
							 ('".$_POST['idOrigem']."' <> '-' AND u.idOrigem = '".$_POST['idOrigem']."')) 
						 AND (('".$_POST['ativo']."' = '-') or 
							 ('".$_POST['ativo']."' <> '-' AND u.ativo = '".$_POST['ativo']."')) 
						 AND (('".$_POST['dataInicio']."' = '') or
							  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and u.data_cadastro between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
						 ");

		while($row = mysql_fetch_row($result)) {
			//dados do cliente
			echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'>";
			echo "<tr><td colspan=4 align=center>DADOS DO CLIENTE</td></tr>";
			echo "<td>DATA CADASTRO</td>";
			echo "<td>".$row[0]."</td>";
			echo "<td>ID</td>";
			echo "<td>".$row[1]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>APELIDO</td>";
			echo "<td>".$row[2]."</td>";
			echo "<td>NOME</td>";
			echo "<td>".$row[3]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>CPF</td>";
			echo "<td>".$row[4]."</td>";
			echo "<td>DT NASC</td>";
			echo "<td>".$row[5]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>SEXO</td>";
			echo "<td>".$row[6]."</td>";
			echo "<td>EMAIL</td>";
			echo "<td>".$row[7]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>TEL RES</td>";
			echo "<td>".$row[8]."</td>";
			echo "<td>CEL</td>";
			echo "<td>".$row[9]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>ORIGEM</td>";
			echo "<td>".$row[10]."</td>";
			echo "<td>ATIVO</td>";
			echo "<td>".$row[11]."</td>";
			echo "</tr>";
			echo "</table>";
			echo "<br><br>";
			
			
			//serviços
			$servico = mysql_query("select i.logradouro, 
											  i.numero, 
											  i.complemento, 
											  i.bairro, 
											  i.estado, 
											  i.cidade,
											  s.descricao, 
											  os.valor, 
											  os.desconto
									   from usuario u,
											usuario i, 
											servico s, 
											processo p, 
											orcamento o, 
											orcamento_servico os
									   where o.idCliente = u.id 
										 and p.idOrcamento = o.id
										 and os.idOrcamento = o.id 
										 and os.idServico = s.id
										 and i.id_proprietario = u.id
										 and u.id = ".$row[1]);
			echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'>"; 
			echo "<tr><td colspan=5 align=center>DADOS DO SERVIÇO</td></tr>";
			echo "<tr><td>IMÓVEL</td><td>SERVIÇO</td><td>VALOR</td><td>DESCONTO</td><td>TOTAL</td></tr>";
			while($row2 = mysql_fetch_array($servico)){
				echo "<tr><td>".$row2[0]." - ".$row2[1]." - ".$row2[2]." - ".$row2[3]." - ".$row2[4]." - ".$row2[5]."</td><td>".$row2[6]."</td><td>".$row2[7]."</td><td>".$row2[8]."</td><td>".$row2[7] - $row2[8]."</td></tr>";
			}
			echo "</table>";
			echo "<br><br>";
			
			
			//histórico
			$historico = mysql_query("select hp.status, 
											   u.nome, 
											   hp.dtAlteracao, 
											   hp.observacao, 
											   hp.dtPrev							   
							 from historico_processo hp,
								  processo p,
								  usuario u,
								  orcamento o
							 where o.idCliente = ".$row[1]."
							   and o.id = p.idOrcamento
							   and p.id = hp.idProcesso
							   and hp.idUsuAlteracao = u.id
							 order by hp.id ");
			echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'><tr><td colspan=5 align=center>HISTÓRICO</td></tr>";
			echo "<tr><td>STATUS</td><td>ATENDENTE</td><td>DT. ALT.</td><td>OBS.</td><td>PREVISÃO</td></tr>";
			while($row3 = mysql_fetch_array($historico)){
				echo "<tr><td>".$row3[0]."</td><td>".$row3[1]."</td><td>".$row3[2]."</td><td>".$row3[3]."</td><td>".$row3[4]."</td></tr>";
			}
			echo "</table>";
			echo "<br><br>";
			
			
			//dados do cônjuge
			$conjuge = mysql_query("select u.nome, 
									u.rg, 
									u.cpf, 
									u.dataNascimento, 
									u.sexo, 
									u.email,
									u.telefone2
							from usuario u
							where u.idConjuge = ".$row[1]);
			echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'><tr><td colspan=7 align=center>CÔNJUGE/PARTICIPANTE</td></tr>";
			echo "<tr><td>NOME</td><td>RG</td><td>CPF</td><td>DT.NASC.</td><td>SEXO</td><td>EMAIL</td><td>CELULAR</td></tr>";
			while($row3 = mysql_fetch_array($conjuge)){
				echo "<tr><td>".$row3[0]."</td><td>".$row3[1]."</td><td>".$row3[2]."</td><td>".$row3[3]."</td><td>".$row3[4]."</td><td>".$row3[5]."</td><td>".$row3[6]."</td></tr>";
			}
			echo "</table>";
			echo "<br><br>";
			
			
			//documentação entregue
			$documentacao = mysql_query("select d.descricao
										   from cliente_documento cd, 
												documento d
										  where cd.idCliente = ".$row[1]."
											and cd.idDocumento = d.id
										 ");
			echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'><tr><td align=center>DOCUMENTAÇÃO ENTREGUE</td></tr>";
			while($row3 = mysql_fetch_array($documentacao)){
				echo "<tr><td>".utf8_encode($row3[0])."</td></tr>";
			}
			echo "</table>";
			echo "<br><br>";
		}
	}
	//filtro
?>
</body>
</html>
