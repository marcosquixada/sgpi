<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT u.id, 
							  u.cnpj, 
                              u.razaoSocial,
							  u.nomeFantasia,
                              u.responsavel,
							  u.email,
							  u.cep,
							  u.logradouro,
							  u.bairro,
							  u.numero,
							  u.complemento,
							  u.telefone1,
							  u.telefone2,
							  case u.ativo WHEN 0 THEN 'NÃO' ELSE 'SIM' END AS ativo
                       FROM usuario u
                       WHERE u.tipo = 'M'
					   AND ((upper(u.nomeFantasia) like UPPER('%".$_POST['nomeFantasia']."%')) or 
					        ('%".$_POST['nomeFantasia']."%' = '%%'))
					   AND ((upper(u.razaoSocial) like UPPER('%".$_POST['razaoSocial']."%')) or 
					        ('%".$_POST['razaoSocial']."%' = '%%'))
					   AND (('".$_POST['cnpj']."' = '') or 
					        ('".$_POST['cnpj']."' <> '' AND u.cnpj = '".$_POST['cnpj']."')) 
					   AND (('".$_POST['estado']."' = '') or 
					        ('".$_POST['estado']."' <> '' AND u.estado = '".$_POST['estado']."')) 
					   AND (('".$_POST['cidade']."' = '') or 
					        ('".$_POST['cidade']."' <> '' AND u.cidade = '".$_POST['cidade']."')) 
					   AND (('".$_POST['bairro']."' = '') or 
					        ('".$_POST['bairro']."' <> '' AND u.bairro = '".$_POST['bairro']."')) 
					   AND (('".$_POST['ativo']."' = '-') or 
					        ('".$_POST['ativo']."' <> '-' AND u.ativo = '".$_POST['ativo']."')) 
					   ");
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>ID</td>
				  <td>CNPJ</td>
				  <td>R. SOC.</td>
				  <td>N. FANT.</td>
				  <td>RESP</td>
				  <td>EMAIL</td>
				  <td>CEP</td>
				  <td>LOGRADOURO</td>
				  <td>BAIRRO</td>
				  <td>NUM</td>
				  <td>COMPL</td>
				  <td>TEL RES</td>
				  <td>CEL</td>
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
$mpdf->Output('condominios.pdf', 'I'); 

?>
