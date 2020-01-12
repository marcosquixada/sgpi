<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT p.id,
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
																					   and o.status = 'A'), 2), '.', '|'), ',', '.'), '|', ','))
							   FROM processo p, 
									usuario u, 
									orcamento o
							   WHERE o.idCliente = u.id 
								 AND u.ativo = '1' 
								 AND o.id = p.idOrcamento
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
									  ('".$_POST['idServico']."' <> '-' AND exists (select 1 
																					from orcamento_servico os 
																					where os.idOrcamento = o.id 
																					  and os.idServico = '".$_POST['idServico']."'))) 
							   ORDER BY p.id");	
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>ID</td>
				  <td>ORCAMENTO</td>
				  <td>CPF/CNPJ</td>
				  <td>NOME</td>
				  <td>INÍCIO</td>
				  <td>FIM</td>
				  <td>VALOR</td>
				</tr>
			  </thead>
			  <tbody>";
$count = 1;
while($row = mysqli_fetch_row($result)){

	if($count % 2 == 0){
		$html .= "<tr bgcolor='WhiteSmoke'>";
		foreach($row as $cell)
			$html .= "<td>$cell</td>";
	}else{
		$html .= "<tr>";
		foreach($row as $cell)
			$html .= "<td>$cell</td>";
	}
	$html .= "</tr>";
	$count = $count + 1;
}

$html .= "</tbody></table>";
include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/mpdf60/mpdf.php");
$mpdf = new mPDF();
//$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);
$mpdf->Output('processos.pdf', 'I'); 

?>
