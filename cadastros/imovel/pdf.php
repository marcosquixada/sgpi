<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT date_format(u.data_cadastro, '%d/%m/%Y %H:%i:%s'), 
						  u.id, 
						  p.nome,
						  u.cep, 
						  u.estado, 
						  u.cidade, 
						  u.logradouro,
						  u.numero, 
						  u.complemento
				   FROM usuario u, 
				        usuario p
				   WHERE u.tipo = 'I'
				     AND u.id_proprietario = p.id
					 AND (('".$_POST['idCliente']."' = '-') or 
						 ('".$_POST['idCliente']."' <> '-' AND u.id_proprietario = '".$_POST['idCliente']."')) 
					 AND (('".$_POST['cep']."' = '') or 
						 ('".$_POST['cep']."' <> '' AND u.cep = '".$_POST['cep']."')) 
					 AND (('".$_POST['estado']."' = '') or 
						 ('".$_POST['estado']."' <> '' AND u.estado = '".$_POST['estado']."')) 
					 AND (('".$_POST['cidade']."' = '') or 
						 ('".$_POST['cidade']."' <> '' AND u.cidade = '".$_POST['cidade']."')) 
					 AND (('".$_POST['bairro']."' = '') or 
						 ('".$_POST['bairro']."' <> '' AND u.bairro = '".$_POST['bairro']."')) 
					 AND (('".$_POST['dataInicio']."' = '') or
		                  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and u.data_cadastro between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
					 ") or die(mysql_error());
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>DATA CADASTRO</td>
				  <td>ID</td>
				  <td>CLIENTE</td>
				  <td>CEP</td>
				  <td>ESTADO</td>
				  <td>CIDADE</td>
				  <td>LOGRADOURO</td>
				  <td>NUMERO</td>
				  <td>COMPLEMENTO</td>
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
$mpdf->Output('imoveis.pdf', 'I'); 

?>
