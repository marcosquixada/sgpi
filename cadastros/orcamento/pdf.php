<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT date_format(o.data_cadastro, '%d/%m/%Y %h:%m:%s'),
									   o.id,
									   IFNULL(u.cpf, u.cnpj), 
									   IFNULL(u.nome, u.razaoSocial),
									   concat('R$ ', replace(replace(replace(format((select sum(os.valor)
																					from orcamento_servico os 
																					where os.idOrcamento = o.id), 2), '.', '|'), ',', '.'), '|', ',')) AS valorF,
									   case o.status WHEN 'E' THEN 'em aprovação' WHEN 'A' THEN 'aprovado' ELSE 'reprovado' END AS status,
									   o.condCom, 
									   o.obs
							   FROM orcamento o,
									usuario u
							   WHERE o.idCliente = u.id 
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
								 AND (('".$_POST['dataInicio']."' = '') or
									  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and o.data_cadastro between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
							   ORDER BY o.id");	
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>DT. CADASTRO</td>
				  <td>ID</td>
				  <td>CPF/CNPJ</td>
				  <td>NOME/RAZÃO SOCIAL</td>
				  <td>VALOR</td>
				  <td>STATUS</td>
				  <td>COND. COM.</td>
				  <td>OBS.</td>
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
$mpdf->Output('orcamentos.pdf', 'I'); 

?>
