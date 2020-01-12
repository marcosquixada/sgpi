<?php 

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
session_start();

$id = $_SESSION['id'];

$idProcesso = -1;
$query = "select p.id 
          from processo p, 
		       orcamento o
		  where p.idOrcamento = o.id 
		    and o.idCliente = ".$id;
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_row($result))
	$idProcesso = $row[0];

$operador = null;
$query = "select u.apelido
          from usuario u, 
		       processo p,	   
			   historico_processo hp 
		  where u.id = hp.idUsuAlteracao 
            and hp.idProcesso = p.id 
            and p.id = ".$idProcesso." 
			and hp.dtAlteracao = (select min(hp2.dtAlteracao) 
			                      from historico_processo hp2 
								  where hp2.idProcesso = p.id)";
$resultOperador = mysqli_query($conn, $query) or die(mysqli_error($conn));
$rowOperador = mysqli_fetch_row($resultOperador);

$idProprietario = -1;
$query = "select o.idCliente
          from orcamento o, 
		       processo p 
		  where p.idOrcamento = o.id 
		    and p.id = ".$idProcesso;
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_row($result))
	$idProprietario = $row[0];

$queryImovel = "select u.logradouro,
                       u.numero, 
					   u.complemento,
					   u.bairro,
					   u.cep,
					   u.estado,
					   u.cidade,
					   u.id
			  from usuario u 
			  where u.id_proprietario = ".$idProprietario;
$resultImovel = mysqli_query($conn, $queryImovel);
$rowImovel = mysqli_fetch_row($resultImovel);

$queryCliente = "select u.nome, 
                       e.descricao,
					   u.rg,
					   u.cpf,
					   u.bairro,
					   u.cep,
					   u.estado,
					   u.cidade,
                       u.logradouro,
                       u.numero, 
					   u.complemento,
					   u.telefone1, 
					   u.telefone2, 
					   u.email
			  from usuario u
			  LEFT JOIN estado_civil e
			  ON u.estado_civil = e.id
			  where u.id = ".$idProprietario;
$resultCliente = mysqli_query($conn, $queryCliente) or die(mysqli_error($conn));
$rowCliente = mysqli_fetch_row($resultCliente);

$queryConj = "select u.nome, 
                       e.descricao,
					   u.rg,
					   u.cpf,
					   u.bairro,
					   u.cep,
					   u.estado,
					   u.cidade,
                       u.logradouro,
                       u.numero, 
					   u.complemento
			  from usuario u
			  LEFT JOIN estado_civil e
			  ON u.estado_civil = e.id
			  where u.idConjuge = ".$idProprietario;
$resultConj = mysqli_query($conn, $queryConj) or die(mysqli_error($conn));

$queryPart = "select u.nome, 
                       e.descricao,
					   u.rg,
					   u.cpf,
					   u.bairro,
					   u.cep,
					   u.estado,
					   u.cidade,
                       u.logradouro,
                       u.numero, 
					   u.complemento
			  from usuario u
			  LEFT JOIN estado_civil e
			  ON u.estado_civil = e.id
			  where u.idParticipante = ".$idProprietario;
$resultPart = mysqli_query($conn, $queryPart) or die(mysqli_error($conn));

$resultServ = mysqli_query($conn, "SELECT s.descricao,
							  concat('R$ ', replace(replace(replace(format(os.valor, 2), '.', '|'), ',', '.'), '|', ',')) AS valor,
							  concat('R$ ', replace(replace(replace(format(os.desconto, 2), '.', '|'), ',', '.'), '|', ',')) AS desconto,
							  date_format(os.data_cadastro, '%d/%m/%Y')
                       FROM processo p, 
					        orcamento_servico os, 
                            servico s 
					   WHERE p.id = ".$idProcesso." 
					     AND os.idOrcamento = p.idOrcamento
					     AND s.id = os.idServico
						 order by os.id ");

$queryDocs = "select d.descricao, cd.entregue
			  from usuario u, 
                   cliente_documento cd, 
                   documento d				   
			  where cd.idCliente = u.id 
			    and cd.idDocumento = d.id 
				and d.tipo = 'C'
				and cd.entregue = 'S'
				and u.id = ".$idProprietario;
$resultDocs = mysqli_query($conn, $queryDocs) or die(mysqli_error($conn));

$queryHist = "select date_format(hp.dtAlteracao, '%d/%m/%Y %H:%m:%s'), 
                 u.apelido, 
				 hp.observacao, 
				 date_format(hp.dtPrev, '%d/%m/%Y'),
				 date_format(hp.dtConclusao, '%d/%m/%Y')
          from historico_processo hp,
               processo p, 
               usuario u			   
		  where hp.idProcesso = p.id 
		    and hp.idUsuAlteracao = u.id
		    and p.id = ".$idProcesso;
$resultHist = mysqli_query($conn, $queryHist) or die(mysqli_error($conn));

$html = "<div class='row'>
  <p align=center style='font-family:Calibri;font-size:14px;font-weight:bold;'><b>FICHA DE ACOMPANHAMENTO DO PROCESSO</b></p>
  <table width=100% style='border: 1px solid black;'>
	<tbody>
      <tr>
		<td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>DADOS DO IMÓVEL</b></td>
	  </tr>
	</tbody>
  </table>
  <div>
    <table width=100% style='border: 1px solid black;'>
      <tbody>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>ENDEREÇO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtr(strtoupper($rowImovel[0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>
		  <td style='font-family:Calibri;font-size:12px;'>Nº:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowImovel[1]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>COMPLEMENTO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowImovel[2])."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>BAIRRO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowImovel[3])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>CEP:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowImovel[4]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>CIDADE-UF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowImovel[6])."-".strtoupper($rowImovel[5])."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>OPERADOR:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowOperador[0]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>ID CLIENTE:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowImovel[7]."</td>
        </tr>
      </tbody>
    </table>
  </div>
  <table width=100% style='border: 1px solid black;'>
	<tbody>
      <tr>
		<td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>DADOS DO CLIENTE</b></td>
	  </tr>
	</tbody>
  </table>
  <div>
    <table width=100% style='border: 1px solid black;'>
      <tbody>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>NOME:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowCliente[0])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>EST. CIVIL:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowCliente[1])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>RG:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowCliente[2]."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>CPF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowCliente[3]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>BAIRRO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowCliente[4])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>CEP:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowCliente[5]."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>CIDADE-UF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowCliente[7])."-".strtoupper($rowCliente[6])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>ENDEREÇO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtr(strtoupper($rowCliente[8]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß").", ".$rowCliente[9]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>COMPLEMENTO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowCliente[10]."</td>
        </tr>
		<tr>
          <td style='font-family:Calibri;font-size:6px;'>TEL. RES.:</td>
          <td style='font-family:Calibri;font-size:10px;font-weight: bold;'>".$rowCliente[11]."</td>
		  <td style='font-family:Calibri;font-size:6px;'>CEL.:</td>
          <td style='font-family:Calibri;font-size:10px;font-weight: bold;'>".$rowCliente[12]."</td>
		  <td style='font-family:Calibri;font-size:6px;'>EMAIL:</td>
          <td style='font-family:Calibri;font-size:10px;font-weight: bold;'>".$rowCliente[13]."</td>
        </tr>
      </tbody>
    </table>
  </div>";
  
while($rowConj = mysqli_fetch_row($resultConj)){  
  $html .= "<table width=100% style='border: 1px solid black;'>
	<tbody>
      <tr>
		<td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:8px;'><b>DADOS DO CÔNJUGE</b></td>
	  </tr>
	</tbody>
  </table>
  <div>
    <table width=100% style='border: 1px solid black;'>
      <tbody>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>NOME:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowConj[0])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>EST. CIVIL:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowConj[1])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>RG:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowConj[2]."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>CPF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowConj[3]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>BAIRRO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowConj[4])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>CEP:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowConj[5]."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>CIDADE-UF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowConj[7])."-".strtoupper($rowConj[6])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>ENDEREÇO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowConj[8]).", ".$rowConj[9]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>COMPLEMENTO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowConj[10]."</td>
        </tr>
      </tbody>
    </table>
  </div>";
}

while($rowPart = mysqli_fetch_row($resultPart)){  
  $html .= "<table width=100% style='border: 1px solid black;'>
	<tbody>
      <tr>
		<td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:8px;'><b>DADOS DO PARTICIPANTE</b></td>
	  </tr>
	</tbody>
  </table>
  <div>
    <table width=100% style='border: 1px solid black;'>
      <tbody>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>NOME:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowPart[0])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>EST. CIVIL:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowPart[1])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>RG:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowPart[2]."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>CPF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowPart[3]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>BAIRRO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowPart[4])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>CEP:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowPart[5]."</td>
        </tr>
        <tr>
          <td style='font-family:Calibri;font-size:12px;'>CIDADE-UF:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowPart[7])."-".strtoupper($rowPart[6])."</td>
		  <td style='font-family:Calibri;font-size:12px;'>ENDEREÇO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".strtoupper($rowPart[8]).", ".$rowPart[9]."</td>
		  <td style='font-family:Calibri;font-size:12px;'>COMPLEMENTO:</td>
          <td style='font-family:Calibri;font-size:15px;font-weight: bold;'>".$rowPart[10]."</td>
        </tr>
      </tbody>
    </table>
  </div>";
}

  $html .= "
  <div>
    <table width=100% style='border: 1px solid black;'>
	  <thead>
        <tr>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>SERVIÇO</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>VALOR</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>DESCONTO</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>DATA CADASTRO</b></td>
	    </tr>
	  </thead>
      <tbody>";
$count = 1;
while($row = mysqli_fetch_row($resultServ)){
	if($count % 2 == 0){
		$html .= "<tr bgcolor='#EEF8FC'>
          <td style='font-family:Calibri;font-size:13px;'>".$row[0]."</td>
          <td style='font-family:Calibri;font-size:13px;'>".strtoupper($row[1])."</td>
		  <td style='font-family:Calibri;font-size:13px;'>".strtoupper($row[2])."</td>
          <td style='font-family:Calibri;font-size:13px;'>".$row[3]."</td>
        </tr>";
	}else{
		$html .= "<tr>
          <td style='font-family:Calibri;font-size:13px;'>".$row[0]."</td>
          <td style='font-family:Calibri;font-size:13px;'>".strtoupper($row[1])."</td>
		  <td style='font-family:Calibri;font-size:13px;'>".strtoupper($row[2])."</td>
          <td style='font-family:Calibri;font-size:13px;'>".$row[3]."</td>
        </tr>";
	}
	$count = $count + 1;
}

$html .= "</tbody>
    </table>
  </div>
  <table width=100% style='border: 1px solid black;'>
	<tbody>
      <tr>
		<td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>DOCUMENTOS ENTREGUES</b></td>
	  </tr>
	</tbody>
  </table>
  <div>
    <table width=100% style='border: 1px solid black;'>
      <tbody>";
	  $numRows = mysqli_num_rows($resultDocs);
	  $array_docs = [];
	  while($row = mysqli_fetch_array($resultDocs))
		  $array_docs[]=$row;
	  
	  if($numRows < 20){
		  $diff = 20 - $numRows;
		  for($i = 1; $i <= $diff; $i++)
			  $array_docs[] = array("", "");
	  }
	  
	  $primeiraVez = true;
	  $num = 0;
	  for($i = 1; $i <= 10; $i++){
        $html .= "<tr>
          <td style='font-family:Calibri;font-size:12px;'>".strtr(strtoupper($array_docs[$num][0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>
		  <td style='font-family:Calibri;font-size:12px;'>".strtr(strtoupper($array_docs[$num+9][0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>";
        $html .= "</tr>";
		$num++;
	  }
        
      $html .= "</tbody>
    </table>
  </div>
  <div>
    <table width=100% style='border: 1px solid black;'>
	  <thead>
        <tr>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>DATA/HORA</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>OPERADOR</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>HISTÓRICO DO PROCESSO</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>PREVISÃO RETORNO</b></td>
		  <td align=center style='background-color:#C0C0C0;font-family:Calibri;font-size:13px;'><b>CONCLUSÃO</b></td>
	    </tr>
	  </thead>
      <tbody>";
$count = 1;
while($row = mysqli_fetch_row($resultHist)){
	if($count % 2 == 0){
		$html .= "<tr bgcolor='#EEF8FC'>
          <td style='font-family:Calibri;font-size:13px;'>".$row[0]."</td>
          <td style='font-family:Calibri;font-size:13px;'>".strtoupper($row[1])."</td>
		  <td style='font-family:Calibri;font-size:13px;'>".strtr(strtoupper($row[2]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>
          <td align=center style='font-family:Calibri;font-size:13px;color: red;'>".$row[3]."</td>
		  <td align=center style='font-family:Calibri;font-size:13px;color: red;'>".$row[4]."</td>
        </tr>";
	}else{
		$html .= "<tr>
          <td style='font-family:Calibri;font-size:13px;'>".$row[0]."</td>
          <td style='font-family:Calibri;font-size:13px;'>".strtoupper($row[1])."</td>
		  <td style='font-family:Calibri;font-size:13px;'>".strtr(strtoupper($row[2]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß")."</td>
          <td align=center style='font-family:Calibri;font-size:13px;color: red;'>".$row[3]."</td>
		  <td align=center style='font-family:Calibri;font-size:13px;color: red;'>".$row[4]."</td>
        </tr>";
	}
	$count = $count + 1;
}

$html .= "</tbody>
    </table>
	<p align=center><input type=button onclick='window.open(\"/sgpi/cadastros/processo/ex3.php?idProcesso=".$idProcesso."\",\"\",\"\")' value=PDF />
	<br>
  </div>
";

//include($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/mpdf60/mpdf.php");
//$mpdf = new mPDF();
//$mpdf->WriteHTML($stylesheet, 1);
//$mpdf->WriteHTML($html);
//$mpdf->Output('ficha_cliente.pdf', 'I');
echo $html;

?>