<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");

$queryCliente = "SELECT id, nome FROM usuario WHERE (tipo = 'C' OR 'J') AND ativo = 1";
$resultCliente = mysql_query($queryCliente);

$queryServico = "SELECT id, descricao FROM servico";
$resultServico = mysql_query($queryServico);
	
?>

<form action="recibo3.php" method="post">
	<table>
		<tr>
			<td>
				<fieldset>
					<legend>Cliente</legend>
					<select name="idCliente">
					<option value="-">Selecione uma opção</option>
					<?php 
						while($row = mysql_fetch_row($resultCliente))
							echo "<option value=".$row[0].">".$row[1]."</option>"; 
					?>
					</select> 
					<?php if (!empty($clienteError)): ?>
						<span><?php echo $clienteError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>Serviço</legend>
					<select name="idServico">
					<option value="-">Selecione uma opção</option>
					<?php 
						while($row = mysql_fetch_row($resultServico))
							echo "<option value=".$row[0].">".$row[1]."</option>"; 
					?>
					</select> 
					<?php if (!empty($servicoError)): ?>
						<span><?php echo $servicoError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>Valor</legend>
					<input name="valor" type="text" placeholder="Valor" onkeypress="return(FormataReais(this,'.',',',event))" value="<?php echo !empty($valor)?$valor:'';?>" id="number">
					<?php if (!empty($valorError)): ?>
						<span><?php echo $valorError;?></span>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td align="center" style="background-color: white;">
				<button type="submit">Gerar</button>
			</td>
		</tr>
	</table>
</form>
	
</body>
</html>