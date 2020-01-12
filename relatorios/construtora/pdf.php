<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$result = mysqli_query($conn, "SELECT cond.razaoSocial, 
									   pf.nome, 
									   cc.bloco, 
									   cc.apartamento, 
									   s.descricao, 
									   pf.telefone1, 
									   pf.telefone2, 
									   concat('R$ ', replace(replace(replace(format(os.valor, 2), '.', '|'), ',', '.'), '|', ',')), 
									   date_format(pf.data_contrato, '%d/%m/%Y'), 
									   hp.observacao, 
									   date_format(hp.dtPrev, '%d/%m/%Y')
								FROM usuario pf, 
									 usuario c, 
									 usuario cond, 
									 processo p, 
									 historico_processo hp, 
									 orcamento o, 
									 orcamento_servico s, 
									 servico s,
									 condominio_cliente cc, 
									 construtora_condominio cc2
								WHERE pf.id = cc.idCliente 
								  AND c.id  = cc2.idConstrutora
								  AND cc2.idCondominio = cc.idCondominio
								  AND p.id = hp.idProcesso
								  AND p.idOrcamento = o.id
								  AND o.id = os.idOrcamento
								  AND s.id = os.idServico
								  AND pf.id = o.idCliente
								  AND cond.id = cc.idCondominio
								  AND cond.id = cc2.idCondominio
								  AND o.status = 'A'
								  AND pf.ativo = '1' 
								  AND s.id in (1,5,7)
								  AND ((upper(pf.nome) like UPPER('%".$_POST['nome']."%')) or 
									  ('%".$_POST['nome']."%' = '%%'))
								  AND ((upper(cond.razaoSocial) like UPPER('%".$_POST['razaoSocial']."%')) or 
									  ('%".$_POST['razaoSocial']."%' = '%%'))
								  AND (('".$_POST['cnpj']."' = '') or 
									  ('".$_POST['cnpj']."' <> '' AND cond.cnpj = '".$_POST['cnpj']."')) 
								  AND (('".$_POST['cpf']."' = '') or 
									  ('".$_POST['cpf']."' <> '' AND pf.cpf = '".$_POST['cpf']."')) 
								  AND (('".$_POST['status']."' = '-') or 
									  ('".$_POST['status']."' <> '-' AND p.status = '".$_POST['status']."')) 
								  AND (('".$_POST['dataInicio']."' = '') or
									  ('".date("Y-d-m",strtotime($_POST['dataInicio']))."' <> '' and p.dtConclusao between '".date("Y-d-m",strtotime($_POST['dataInicio']))."' and DATE_ADD('".date("Y-d-m",strtotime($_POST['dataFim']))."', INTERVAL 1 DAY)))
							   ORDER BY p.id");	
						   
$html = "<table width=100% style='border: 1px solid black; table-layout: fixed; margin: 0 auto;'>
			  <thead>
				<tr style='background: SteelBlue;'>
				  <td>CONDOMINIO</td>
				  <td>CLIENTE</td>
				  <td>BLOCO</td>
				  <td>AP</td>
				  <td>SERVIÇO</td>
				  <td>TEL. RES.</td>
				  <td>CEL.</td>
				  <td>VALOR</td>
				  <td>DATA CONTRATO</td>
				  <td>OBSERVAÇÃO</td>
				  <td>PREVISÃO</td>
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
$mpdf->Output('fichaConstrutora.pdf', 'I'); 

?>
