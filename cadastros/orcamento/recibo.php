<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
require_once($_SERVER['DOCUMENT_ROOT']."/sgpi/libs/GExtenso/GExtenso.php");
$id    = $_GET['id'];
$query = "SELECT u.nome,
				 u.cpf,
                 u.logradouro, 
				 u.numero,
				 o.valor, 
                 s.descricao, 
				 u.cidade,
				 u.estado, 
				 u.bairro
		  FROM usuario u,
               orcamento o, 
               servico s
          WHERE o.id = '".$id."'
		    AND o.idCliente = u.id
			AND o.idServico = s.id
		  ";
$firma=utf8_decode('CREDIMOVEL CONSULTORIA IMOBILIÁRIA');
$cnpj_cgc_firma='09.594.296/0001-90';
$logomarca='/sgpi/_img/logo.png';

$nome_devedor='';
$cpf_cnpj_devedor='';
$endereco='';
$numero='';
$bairro='';
$valor_devedor='';
$valor_extenso_devedor='';
$referente='';
$final=utf8_decode('estando quitado o débito referido a esta data');
$dia_pagamento=date("d");
$mes_pagamento=date("m");
$ano_pagamento=date("Y");
$cidade='';
$estado='';

$result = mysql_query($query);
if($result === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
while($row = mysql_fetch_row($result))
{
	$nome_devedor=$row[0];
	$cpf_cnpj_devedor=$row[1];
	$endereco=$row[2];
	$numero=$row[3];
	$valor_devedor=$row[4];
	$valor_extenso_devedor=GExtenso::moeda($row[4]*100);
	$referente=$row[5];
	$cidade=$row[6];
	$estado=$row[7];
	$bairro=$row[8];
}

?>
<link rel="stylesheet" type="text/css" href="style.css">
<title><?php echo $firma; ?></title>
<p><a href="javascript:print()" title="Imprimir recibo para <?php echo $firma; ?> assinar">Imprimir..</a></p>
<h1 align="center"><img src="<?php echo $logomarca; ?>" width="100"></h1>
<h1 align="center"><font style="font-family:Verdana, Arial, Helvetica, sans-serif">RECIBO</font></h1>
<p align="center"><font style="font-family:Verdana, Arial, Helvetica, sans-serif"><?php echo $firma; ?><br />CNPJ: <?php echo $cnpj_cgc_firma; ?></font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="right"><font style="font-family:Verdana, Arial, Helvetica, sans-serif">RECIBO: R$ <?php echo $valor_devedor; ?></font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="font-family:Verdana, Arial, Helvetica, sans-serif">Recebemos de (a) <strong><?php echo $nome_devedor; ?></strong> portador (a) do CPF/CNPJ: <strong><?php echo $cpf_cnpj_devedor; ?></strong>, a import&acirc;ncia de R$ <strong><?php echo $valor_devedor; ?></strong> (<?php echo $valor_extenso_devedor; ?>) referente &agrave; <strong><?php echo $referente; ?></strong>, <?php echo $final; ?>.</font></p>
<p align="right"><font style="font-family:Verdana, Arial, Helvetica, sans-serif"><?php echo $endereco; ?>, <?php echo $numero; ?></font></p>
<p>&nbsp;</p>
<p align="center"><font style="font-family:Verdana, Arial, Helvetica, sans-serif"><?php echo $cidade; ?> - <?php echo $estado; ?>, <?php echo $dia_pagamento; ?>/<?php echo $mes_pagamento; ?>/<?php echo $ano_pagamento; ?>.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center">________________________________________<br /><font style="font-family:Verdana, Arial, Helvetica, sans-serif"><?php echo $firma; ?></font></p>