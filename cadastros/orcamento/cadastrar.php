<?php 
	include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); 
	
	$queryCliente = "SELECT id, nome FROM usuario WHERE (tipo = 'C' OR 'J') AND ativo = 1";
	$resultCliente = mysqli_query($conn, $queryCliente);
	
	$queryServico = "SELECT id, descricao FROM servico";
	$resultServico = mysqli_query($conn, $queryServico);
?>

<div class="container">
	<fieldset>
		<legend>Cadastro de Orçamento</legend>

		<form action="cadOrc.php" method="post">
			<table style="border: 0;" id="tabela">
				<tr>
					<td>
						<fieldset>
							<legend>Cliente</legend>
							<input type="hidden" name="idCliente" class="idCliente" />
							<input name="nomeCliente" class="ui-widget nomeCliente" type="text" id="nomeCliente">
							<?php if (!empty($clienteError)): ?>
								<span><?php echo $clienteError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr class="linha">
					<td>
						<fieldset>
							<legend>Imóvel</legend>
							<select name="idImovel[]" class="imovel">
							<option value="-">Selecione uma opção</option>
							</select> 
							<?php if (!empty($imovelError)): ?>
								<span><?php echo $imovelError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend>Serviço</legend>
							<select name="idServico[]">
							<option value="-">Selecione uma opção</option>
							<?php 
								while($row = mysqli_fetch_row($resultServico))
									echo "<option value=".$row[0].">".strtr(strtoupper($row[1]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</option>"; 
							?>
							</select> 
							<?php if (!empty($servicoError)): ?>
								<span><?php echo $servicoError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend>Valor</legend>
							<input name="valor[]" type="text" placeholder="Valor" class="formatareais" value="<?php echo !empty($valor)?$valor:'';?>">
							<?php if (!empty($valorError)): ?>
								<span><?php echo $valorError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend>Desconto</legend>
							<input name="desconto[]" type="text" placeholder="Desconto" class="formatareais" />
						</fieldset>
					</td>
					<td>
						<a href="javascript:void(0);" class="clone"><img src="/sgpi/_img/add.png" height="15px" width="15px" /></a>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<fieldset>
							<legend>Condição Comercial</legend>
							<textarea name="condCom" placeholder="Condição Comercial" style="width: 100%;"></textarea>
							<?php if (!empty($condComError)): ?>
								<span><?php echo $condComError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
					<td colspan="2">
						<fieldset>
							<legend>Observação</legend>
							<textarea name="observacao" placeholder="Observação" style="width: 100%;"></textarea>
							<?php if (!empty($valorError)): ?>
								<span><?php echo $valorError;?></span>
							<?php endif; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" style="background-color: white;" colspan="4">
						<input type="submit" value="Cadastrar">
						<input type="button" value="Voltar" onClick="history.back();" />
					</td>
				</tr>
			</table>
		</form>
	</fieldset>         
</div> <!-- /container -->
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>