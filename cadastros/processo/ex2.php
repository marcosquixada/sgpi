<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

ob_start();

$html = ob_get_clean();

$html = utf8_encode($html);

$html .= '<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#aabcfe;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#669;background-color:#e8edff;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#039;background-color:#b9c9fe;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-mb3i{background-color:#D2E4FC;text-align:right;vertical-align:top}
.tg .tg-lqy6{text-align:right;vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
.tg .tg-yw4l{vertical-align:top}
</style>
<table class="tg">
  <tr>
    <th class="tg-baqh" colspan="6">Results</th>
  </tr>
  <tr>
    <td class="tg-6k2t">No</td>
    <td class="tg-6k2t">Competition</td>
    <td class="tg-6k2t">John</td>
    <td class="tg-6k2t">Adam</td>
    <td class="tg-6k2t">Robert</td>
    <td class="tg-6k2t">Paul</td>
  </tr>
  <tr>
    <td class="tg-yw4l">1</td>
    <td class="tg-yw4l">Swimming</td>
    <td class="tg-lqy6">1:30</td>
    <td class="tg-lqy6">2:05</td>
    <td class="tg-lqy6">1:15</td>
    <td class="tg-lqy6">1:41</td>
  </tr>
  <tr>
    <td class="tg-6k2t">2</td>
    <td class="tg-6k2t">Running</td>
    <td class="tg-mb3i">15:30</td>
    <td class="tg-mb3i">14:10</td>
    <td class="tg-mb3i">15:45</td>
    <td class="tg-mb3i">16:00</td>
  </tr>
  <tr>
    <td class="tg-yw4l">3</td>
    <td class="tg-yw4l">Shooting</td>
    <td class="tg-lqy6">70%</td>
    <td class="tg-lqy6">55%</td>
    <td class="tg-lqy6">90%</td>
    <td class="tg-lqy6">88%</td>
  </tr>
</table>';

$html2 = ob_get_clean();

$html2 = utf8_encode($html2);

$html2 = '<style type="text/css">
.title {font-family:Calibri;font-size:14px;font-weight:bold;}
.tg .tg-lqy6{background-color:#C0C0C0;text-align:center;font-family:Calibri;font-size:8px;font-weight:bold;border: 1px solid black;}
.tg .tg-6k2t{font-family:Calibri;font-size:6px;}
.tg .tg-yw4l{font-family:Calibri;font-size:10px;}
</style>
<p align=center class="title">FICHA DE ACOMPANHAMENTO DE PROCESSO<p>
<table width=100% class="tg">
	<tr>
		<td class="tg-lqy6">
			DADOS DO IMÓVEL
		</td>
	</tr>';
$idProcesso = $_GET['idProcesso'];
$idProprietario = '';
$query = "select o.idCliente
          from orcamento o, 
		       processo p 
		  where p.idOrcamento = o.id 
		    and p.id = ".$idProcesso;
$result = mysql_query($query);
while($row = mysql_fetch_row($result))
	$idProprietario = $row[0];
$query = "select * 
          from usuario u 
		  where u.id_proprietario = ".$idProprietario;
$result = mysql_query($query);
while($row = mysql_fetch_row($result)){
	$html2 .= '<tr><td class="tg-6k2t">ENDEREÇO</td><td>'.$row['logradouro'].'</td><td class="tg-6k2t">Nº</td><td>'.$row['numero'].'</td><td class="tg-6k2t">COMPLEMENTO</td><td>'.$row['complemento'].'</td></tr>';
	$html2 .= '<tr><td class="tg-6k2t">BAIRRO</td><td>'.$row['bairro'].'</td><td class="tg-6k2t">CEP</td><td>'.$row['cep'].'</td><td class="tg-6k2t">CIDADE-UF</td><td>'.$row['cidade'].' - '.$row['estado'].'</td></tr>';
	$html2 .= '<tr><td class="tg-6k2t">OPERADOR</td><td></td><td class="tg-6k2t">ID CLIENTE</td><td class="tg-yw4l">'.$idProprietario.'</td><td class="tg-6k2t">TEL. CORRETOR</td><td></td></tr>';	
}	
$html2 .= '<tr>
		<td class="tg-lqy6">
			DADOS DO CLIENTE
		</td>
	</tr>';
$query = "select * 
          from usuario u 
		  where id = ".$idProprietario;
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_row($result)){
	$html2 .= '<tr><td class="tg-6k2t">ENDEREÇO</td><td>'.$row['logradouro'].'</td><td class="tg-6k2t">Nº</td><td>'.$row['numero'].'</td><td class="tg-6k2t">COMPLEMENTO</td><td>'.$row['complemento'].'</td></tr>';
	$html2 .= '<tr><td class="tg-6k2t">BAIRRO</td><td>'.$row['bairro'].'</td><td class="tg-6k2t">CEP</td><td>'.$row['cep'].'</td><td class="tg-6k2t">CIDADE-UF</td><td>'.$row['cidade'].' - '.$row['estado'].'</td></tr>';
}
$html2 .= '<tr>
		<td class="tg-lqy6">
			DOCUMENTOS ENTREGUES
		</td>
	</tr>';	
$html2 .= '<tr><td></td><td class="tg-6k2t">01. FICHA DE CAPTAÇÃO</td><td></td><td>11. CND SEFAZ - PROPRIETÁRIO</td><td rowspan=10></td></tr>';
$html2 .= '<tr><td></td><td class="tg-6k2t">02. MATRÍCULA REG. IMÓVEL</td><td></td><td>12. CERTIDÃO CASAMENTO</td><td></td></tr>';
$html2 .= '<tr><td></td><td>03. ESCRITURA IMÓVEL</td><td></td><td>13. CERTIDÃO DE ÓBITO CONJUGE.</td><td></td></tr>';
$html2 .= '<tr><td></td><td>04. IPTU</td><td></td><td>14. CERT. CASAMENTO C/AVERB.  DIVÓRCIO</td><td></td></tr>';
$html2 .= '<tr><td></td><td>05. CONTRATO COMPRA E VENDA</td><td></td><td>15. RG CONJUGE/2º PROPRIETÁRIO</td><td></td></tr>';
$html2 .= '<tr><td></td><td>06. EXTRATO FINANCIAMENTO</td><td></td><td>16. CPF CONJUGE/2º PROPRIETÁRIO</td><td></td></tr>';
$html2 .= '<tr><td></td><td>07. CERTIDÃO NASCIMENTO PROPR.</td><td></td><td>17. CND RECEITA CONJUGE/2º PROPRIETÁRIO</td><td></td></tr>';
$html2 .= '<tr><td></td><td>08. RG E CPF PROPRIETÁRIO</td><td></td><td>18. CND SEFAZ CONJUGE/2º PROPRIETÁRIO</td><td></td></tr>';
$html2 .= '<tr><td></td><td>09. COMPROVANTE RESIDÊNCIA</td><td></td><td>19. </td><td></td></tr>';
$html2 .= '<tr><td></td><td>10. CND RECEITA - PROPRIETÁRIO</td><td></td><td>20. </td><td></td></tr>';

$html2 .= '<tr>
		<td class="tg-lqy6">
			DATA/HORA
		</td>
		<td class="tg-lqy6">
			OPERADOR
		</td>
		<td class="tg-lqy6">
			HISTÓRICO
		</td>
		<td class="tg-lqy6">
			PREVISÃO RETORNO
		</td>
	</tr>';	
$query = "select hp.dtAlteracao, 
                 u.nome, 
				 hp.observacao, 
				 hp.dtPrev
          from historico_processo hp,
               processo p, 
               usuario u			   
		  where hp.idProcesso = p.id 
		    and hp.idUsuAlteracao = u.id
		    and p.id = ".$idProcesso;
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_row($result)){
	$html2 .= '<tr>
		<td class="tg-lqy6">
			'.$row[0].'
		</td>
		<td class="tg-lqy6">
			'.$row[1].'
		</td>
		<td class="tg-lqy6">
			'.$row[2].'
		</td>
		<td class="tg-lqy6">
			'.$row[3].'
		</td>
	</tr>';
}
$html2 .= '</table>';

include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->WriteHTML($html2);
$mpdf->Output('Ficha_cliente.pdf','I');

exit();
?>