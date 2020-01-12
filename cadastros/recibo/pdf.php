<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT date_format(r.data_cadastro, '%d/%m/%Y %h:%i:%s'), 
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
										   ORDER BY r.id");	
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>DATA CADASTRO</td>
				  <td>ID</td>
				  <td>CPF/CNPJ</td>
				  <td>NOME</td>
				  <td>SERVIÇO</td>
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
$mpdf->Output('recibos.pdf', 'I'); 

?>
