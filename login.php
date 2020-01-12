<?php 
header("Content-Type: text/html; charset=ISO-8859-1", true); 
include('conexao/db.php');
session_start();
{
	if(isset($_POST['cpfCnpj'])){
		$cpfCnpj=mysqli_real_escape_string($conn, $_POST['cpfCnpj']);
		$senha=md5(mysqli_real_escape_string($conn, $_POST['senha']));
		$sql = "SELECT id, tipo 
				FROM usuario 
				WHERE (cpf = '".$cpfCnpj."' or cnpj = '".$cpfCnpj."') 
				AND senha = '".$senha."' 
				AND ativo=1";
		$fetch=mysqli_query($conn, $sql) or die("erro ao selecionar");
		$count=mysqli_num_rows($fetch);
		$row=mysqli_fetch_array($fetch);
		$id=$row['id'];
		$tipo=$row['tipo'];
		if($count!="") {
			$_SESSION['cpfCnpj']=$cpfCnpj;
			$_SESSION['password']=$senha;
			$_SESSION['id']=$id;
			$_SESSION['tipo']=$tipo;
			header("Location:index.php");
		} else {
			echo"<script language='javascript' type='text/javascript'>alert('CPF/CNPJ e/ou senha incorretos!');window.location.href='login.php';</script>";
			die();
		} 
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" /> 
<title>CredIm�vel :: Login</title>
<meta name="DESCRIPTION" content=""> 
<meta name="KEYWORDS" content="Casas ,Apartamentos ,Flats ,marcelino ,freitas ,im�veis ,imoveis ,imobili�ria ,imobiliaria ,">

<meta name="MSSmartTagsPreventParsing" content="true">
<meta name="google-site-verification" content="2PLV1H5CLirGcXP09TFvmJZsg3h5m9jM9kI4j6hTsVQ">
<meta name="robots" content="index,follow">

<meta http-equiv="cache-control" content="no-cache">
<link rel="stylesheet" type="text/css" href="a_data/erroLogin.css" media="screen" />
<script type="text/javascript" src="/sgpi/js/sgpi.js"></script>
<style>
body{ background-position:center; background-attachment:fixed; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif}
.div_login{
    width:350px; height:450px; margin:auto; margin-top:-230px; -webkit-border-radius: 20px;-moz-border-radius: 20px;border-radius: 20px; 
	
	
	
    background-color: #E38417; 
}

.logo_login{ width:380px;  position:relative; padding:5px;  height:130px; background-color:#FFF; -webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;  margin:auto ;margin-top:150px; box-shadow: 0px 5px 5px  #000;
-webkit-box-shadow: 0px 5px 5px  #000;
-moz-box-shadow: 0px 5px 5px  #000; }

.text{float:left; margin-left:0px; line-height:50px; font-size:30px; text-align:center;  font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color: #FFF;width:350px; height:60px;margin-top:20px; text-shadow: 2px 2px  #000; }
.text2{ font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; color:#FFF}
.site{clear:both; top:175px; cursor: pointer; text-align:center; font-size:14px; width:350px; position:relative; color:#FFF }
.site:hover{ color: #FFF}

.botao{margin:auto; width:200px; cursor:pointer;  padding:5px 10px 5px 10px; border:none; background-color:#0587B1; color:#FFF; font-weight:bold;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
.botao:hover{ background-color:#FFF; color: #E38417; border:solid 1px #666;}

.input_{border:none ; padding-left:5px; height:25px; line-height:25px; width:200px; margin-top:5px; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}

</style>

<script src="a_data/AC_RunActiveContent.htm" language="javascript"></script><script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17207060-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body>
    
<div id="Bavancada" style="position:absolute;display:none;width:600px;height:auto;background:#FFFFFF;left:60%;margin-left:-400px;text-align:center"></div>

<div class="logo_login">
    <center>
        <img style=" margin-top:0px;" src="/sgpi/_img/LogoManager.png" alt="Our logo" title="Visit Site" height="112" width="145">
    </center>
</div>
<div class="div_login">

<div class="text">�rea do Cliente</div>

<div style="width:200px;  top:170px; position:relative; margin-left:0px; clear:both; height:auto; margin:auto;">
<form class="form-horizontal" method="POST" action="login.php" enctype="UTF-8">
    <input name="botao" id="botao" type="hidden">
    <input name="comando" value="validarUsuario" type="hidden">
    <input name="valida" value="1" type="hidden">
<span class="text2">CPF/CNPJ</span><br>
<input class="input_" name="cpfCnpj" id="inputCPF" placeholder="CPF/CNPJ" type="text" maxlength="18" onkeyup="onlydigs(this);" onfocus="retirarFormatacao(this);" onblur="formatarCampo(this);" width="300"><br>

<span class="text2">Senha</span><br>
<input class="input_" id="senha_usu" placeholder="Senha" name="senha" type="password" width="300"><br><br>
<input class="botao" value="Entrar" type="submit">

</form>

</div>

<a class="recover" style="text-decoration:none; color: #FFF;" href="/sgpi/esqueci_senha.php" onclick=""><span class="site" style="width:200px; margin: auto; clear:both; margin-left:100px; text-align:center;">Esqueci minha senha </span></a>

</div>
</body>
</html>