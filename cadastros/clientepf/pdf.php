<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT date_format(u.data_cadastro, '%d/%m/%Y %H:%i:%s'), 
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
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>DATA CADASTRO</td>
				  <td>ID</td>
				  <td>APELIDO</td>
				  <td>NOME</td>
				  <td>CPF</td>
				  <td>DT NASC</td>
				  <td>SEXO</td>
				  <td>EMAIL</td>
				  <td>TEL RES</td>
				  <td>CEL</td>
				  <td>ORIGEM</td>
				  <td>ATIVO</td>
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
$mpdf->Output('Clientes PF.pdf', 'I'); 

?>
