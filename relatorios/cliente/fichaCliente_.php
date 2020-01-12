<?php 

include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php");
session_start();

	//obter id usuario logado
	$id = $_SESSION['id'];
	 //dados do cliente
	$cliente = mysqli_query($conn, "select u.nome, 
                    u.rg, 
                    u.cpf, 
                    u.estado, 
                    u.cidade, 
                    u.bairro,
                    u.logradouro, 
                    u.numero, 
                    u.cep,
                    o.descricao
			from usuario u, 
                 origem o
			where u.id = ".$id."
			  and u.idOrigem = o.id
            ");

	while($row = mysqli_fetch_array($cliente)){
		//dados do cliente
		echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'>";
		echo "<tr><td colspan=4 align=center>DADOS DO CLIENTE</td></tr>";
		echo "<td>DATA CADASTRO</td>";
		echo "<td>".$row[0]."</td>";
		echo "<td>ID</td>";
		echo "<td>".$id."</td>";
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
		$servico = mysqli_query($conn, "select i.logradouro, 
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
									 and u.id = ".$id);
		echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'>"; 
		echo "<tr><td colspan=5 align=center>DADOS DO SERVIÇO</td></tr>";
		echo "<tr><td>IMÓVEL</td><td>SERVIÇO</td><td>VALOR</td><td>DESCONTO</td><td>TOTAL</td></tr>";
		while($row2 = mysqli_fetch_array($servico)){
			echo "<tr><td>".$row2[0]." - ".$row2[1]." - ".$row2[2]." - ".$row2[3]." - ".$row2[4]." - ".$row2[5]."</td><td>".$row2[6]."</td><td>".$row2[7]."</td><td>".$row2[8]."</td><td>".$row2[7] - $row2[8]."</td></tr>";
		}
		echo "</table>";
		echo "<br><br>";
		
		
		//histórico
		$historico = mysqli_query($conn, "select hp.status, 
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
		while($row3 = mysqli_fetch_array($historico)){
			echo "<tr><td>".$row3[0]."</td><td>".$row3[1]."</td><td>".$row3[2]."</td><td>".$row3[3]."</td><td>".$row3[4]."</td></tr>";
		}
		echo "</table>";
		echo "<br><br>";
		
		
		//dados do cônjuge
		$conjuge = mysqli_query($conn, "select u.nome, 
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
		while($row3 = mysqli_fetch_array($conjuge)){
			echo "<tr><td>".$row3[0]."</td><td>".$row3[1]."</td><td>".$row3[2]."</td><td>".$row3[3]."</td><td>".$row3[4]."</td><td>".$row3[5]."</td><td>".$row3[6]."</td></tr>";
		}
		echo "</table>";
		echo "<br><br>";
		
		
		//documentação entregue
		$documentacao = mysqli_query($conn, "select d.descricao
									   from cliente_documento cd, 
											documento d
									  where cd.idCliente = ".$row[1]."
										and cd.idDocumento = d.id
									 ");
		echo "<table style='width: 500px; border:1px solid #0000FF; border-radius:8px;'><tr><td align=center>DOCUMENTAÇÃO ENTREGUE</td></tr>";
		while($row3 = mysqli_fetch_array($documentacao)){
			echo "<tr><td>".utf8_encode($row3[0])."</td></tr>";
		}
		echo "</table>";
		echo "<br><br>";
	}

 ?>

</body>
</html>
