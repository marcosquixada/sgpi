<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
session_start();

$id = $_SESSION['id'];	
	if(empty($id))
		header("Location: /sgpi/login.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/sgpi/css/style.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">

</head>
<body>
<ul>
  <li class="dropdown" style="padding-top: 15px">
    <a href='/sgpi/logout.php'><img src="/sgpi/_img/logout.png" width="25px" height="25px" alt='Sair' title='Sair' /></a>
  </li>
  <li class="dropdown" style="padding-top: 15px">
    <a href="#" class="dropbtn"><img src="/sgpi/_img/relatorios.png" width="25px" height="25px" alt='Relatórios' title='Relatórios' /></a>
    <div class="dropdown-content">
		<?php if($_SESSION['tipo'] === 'T' or $_SESSION['tipo'] === 'A') { ?>
			<a href="/sgpi/cadastros/processo">Processo</a>
		    <a href="/sgpi/cadastros/orcamento">Orçamento</a>
		    <a href="/sgpi/cadastros/recibo">Recibo</a>
		<? } ?>
    </div>
  </li>
  <?php if($_SESSION['tipo'] === 'A') { ?>
  <li class="dropdown" style="padding-top: 15px">
    <a href="#" class="dropbtn"><img src="/sgpi/_img/cadastros.png" width="25px" height="25px" alt='Cadastros' title='Cadastros' /></a>
    <div class="dropdown-content">
		<a href="/sgpi/cadastros/administrador">Administrador</a>
		<a href="/sgpi/cadastros/atendente">Atendente</a>
		<a href="/sgpi/cadastros/clientepf">Cliente PF</a>
		<a href="/sgpi/cadastros/clientepj">Cliente PJ</a>
		<a href="/sgpi/cadastros/condominio">Condomínio</a>		
		<a href="/sgpi/cadastros/construtora">Construtora</a>
		<a href="/sgpi/cadastros/imovel">Imóvel</a>
    </div>
  </li>
  <?php } elseif($_SESSION['tipo'] === 'T') {  ?>
  <li class="dropdown" style="padding-top: 15px">
    <a href="#" class="dropbtn"><img src="/sgpi/_img/cadastros.png" width="25px" height="25px" alt='Cadastro' title='Cadastro' /></a>
    <div class="dropdown-content">
		<a href="/sgpi/cadastros/atendente">Atendente</a>
		<a href="/sgpi/cadastros/clientepf">Cliente PF</a>
		<a href="/sgpi/cadastros/clientepj">Cliente PJ</a>
		<a href="/sgpi/cadastros/condominio">Condomínio</a>		
		<a href="/sgpi/cadastros/construtora">Construtora</a>
		<a href="/sgpi/cadastros/imovel">Imóvel</a>
    </div>
  </li>
  <? } ?>
  <li class="dropdown" style="padding-top: 15px">
      <a href="/sgpi/index.php" class="dropbtn"><img src="/sgpi/_img/home.png" width="25px" height="25px" alt='Home' title='Home' /></a>
  </li>
  <li class="dropdown" style="float: left; margin-left: 15px; margin-top: 15px;">
      <img src="/sgpi/_img/logo.png" alt='Home' title='Home' height="82" width="108" />
  </li>

</ul>
<hr style="color:#000000" /> 