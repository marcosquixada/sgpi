<?php 
include('conexao/db.php');
session_start();
{
	if(isset($_POST['cpfCnpj'])){
		$cpfCnpj=mysql_real_escape_string($_POST['cpfCnpj']);
		$senha=md5(mysql_real_escape_string($_POST['senha']));
		$fetch=mysql_query("SELECT id, tipo 
		                    FROM usuario 
							WHERE (cpf = '".$cpfCnpj."' or cnpj = '".$cpfCnpj."') 
							  AND senha = '".$senha."' 
							  AND ativo=1") or die("erro ao selecionar");
		$count=mysql_num_rows($fetch);
		$row=mysql_fetch_array($fetch);
		$id=$row['id'];
		$tipo=$row['tipo'];
		if($count!="") {
			//echo "teste";
			session_register("sessionusername");
			$_SESSION['cpfCnpj']=$cpfCnpj;
			$_SESSION['password']=$senha;
			$_SESSION['id']=$id;
			$_SESSION['tipo']=$tipo;
			header("Location:index.php");
		} else {
			echo"<script language='javascript' type='text/javascript'>alert('CPF e/ou senha incorretos!');window.location.href='login.php';</script>";
			die();
		} 
	}
    
}
?>

<html>
<head>
<title> Login de Usuário </title>
<meta charset="UTF-8">
<style type="text/css">
#maior {
	width: 60%;
	margin: 10% auto;
}
.formLayout {
	background-color: orange;
	border: solid 1px #a1a1a1;
	width: 400px;
	border-radius: 10px;
}

.formLayout h1 {
	color: white;
}

.formlayout input[type=button], input[type=submit] {
	background-color: #0071C5;
    border: none;
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    width: 125px;
    height: 45px;
}

#form {
    float: right;
    margin: 0 auto;
}
</style>
<script src="https://code.jquery.com/jquery-git2.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
   $("#date").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
   $("#phone1").mask("(99) 9999-9999");
   $("#phone2").mask("(99) 999 999 999");
   $("#cpf").mask("999.999.999-99");
   $("#cnpj").mask("99.999.999/9999-99");
   $("#number").keyup(function(){
        $(this).val($(this).val().replace(/[^0-9\.,]/g,''));
   });
});

function formatarCampo(campoTexto) {
    if (campoTexto.value.length <= 11) {
        campoTexto.value = mascaraCpf(campoTexto.value);
    } else {
        campoTexto.value = mascaraCnpj(campoTexto.value);
    }
}

function retirarFormatacao(campoTexto) {
    campoTexto.value = campoTexto.value.replace(/(\.|\/|\-)/g,"");
}

function mascaraCpf(valor) {
    return valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
}

function mascaraCnpj(valor) {
    return valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
}
</script>
</head>
<body>
<form method="POST" action="login.php" enctype="UTF-8">
<div id="maior">
	<img src="_img/logo.jpg" />
	<div align="center" class="formLayout" id="form">
		<h1 align="center">ÁREA DO CLIENTE</h1>
		<p><input type="text" name="cpfCnpj" id="cpfCnpj" placeholder="CPF/CNPJ" onfocus="javascript: retirarFormatacao(this);" onblur="javascript: formatarCampo(this);" maxlength="14"></p>
		<p><input type="password" name="senha" placeholder="Senha" ></p>
		<p>
		<input type="submit" value="Entrar" name="entrar">
		<input type="button" value="Esqueci a senha" name="esqueci">
		</p>
	</div>
</div>
</form>
</body>
</html>