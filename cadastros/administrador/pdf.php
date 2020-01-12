<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT u.id, 
							  u.nome, 
							  u.cpf, 
							  date_format(u.dataNascimento, '%d/%m/%Y'),
							  case u.sexo WHEN 'M' THEN 'MASCULINO' ELSE 'FEMININO' END AS sexo,
							  u.email,
							  u.cep,
							  u.logradouro,
							  u.estado,
							  u.cidade,
							  u.bairro,
							  u.numero,
							  u.complemento,
							  u.telefone1,
							  u.telefone2,
							  case u.ativo WHEN 0 THEN 'NÃO' ELSE 'SIM' END AS ativo
					   FROM usuario u
					   WHERE u.tipo = 'A'
					   AND ((upper(u.nome) like UPPER('%".$_POST['nome']."%')) or 
							 ('%".$_POST['nome']."%' = '%%'))
					   AND (('".$_POST['cpf']."' = '') or 
						   ('".$_POST['cpf']."' <> '' AND u.cpf = '".$_POST['cpf']."')) 
					   AND (('".$_POST['ativo']."' = '-') or 
						   ('".$_POST['ativo']."' <> '-' AND u.ativo = '".$_POST['ativo']."')) 
						   ");	
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>ID</td>
				  <td>NOME</td>
				  <td>CPF</td>
				  <td>DT NASC</td>
				  <td>SEXO</td>
				  <td>EMAIL</td>
				  <td>CEP</td>
				  <td>LOGRADOURO</td>
				  <td>ESTADO</td>
				  <td>CIDADE</td>
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
$mpdf = new mPDF('c', 'A4-L');
//$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);
$mpdf->Output('administradores.pdf', 'I'); 

?>
