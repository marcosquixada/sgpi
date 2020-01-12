<?php 
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
    if ( !empty($_POST)) {
        // keep track validation errors
        $senhaError = null;
		$confSenhaError = null;
         
        // keep track post values
        $senha = $_POST['senha'];
		$confSenha = $_POST['confSenha'];
		$id = $_POST['id'];

        // validate input
        $valid = true;
        if (empty($senha)) {
            $senhaError = 'Por favor digite a senha';
            $valid = false;
        }
		if (empty($confSenha)) {
            $confSenhaError = 'Por favor digite a confirmação da senha';
            $valid = false;
        }
		if ($senha !== $confSenha){
			$senhaError = 'Senha não confere com a confirmação.';
            $valid = false;
		}
		if (strlen($senha) < 6){
			$senhaError = 'A senha deve ter no mínimo 6 caracteres.';
            $valid = false;
		}
         
        // insert data
        if ($valid) {
			$sql = "UPDATE usuario set senha='".md5($senha)."' where id = ".$id;
			if(mysqli_query($conn, $sql)){
				echo "<script>alert('Senha cadastrada com sucesso!');window.location.href='/sgpi/login.php';</script>";
			} else {
				echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n".$sql;
			}
			
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>CredImóvel :: Login</title>
<meta name="DESCRIPTION" content=""> 
<meta name="KEYWORDS" content="Casas ,Apartamentos ,Flats ,marcelino ,freitas ,imóveis ,imoveis ,imobiliária ,imobiliaria ,">

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
        <img style=" margin-top:0px;" src="/sgpi/_img/logo.png" alt="Our logo" title="Visit Site" height="112" width="138">
    </center>
</div>
<div class="div_login">

	<div class="text">Cadastro de Senha</div>

	<div style="width:200px;  top:170px; position:relative; margin-left:0px; clear:both; height:auto; margin:auto;">
		<form class="form-horizontal" method="POST" action="cadastrar_senha.php" enctype="UTF-8">
			<input type="hidden" name="id" value="<? echo $_GET['id']; ?>" />
			<span class="text2">Senha</span><br><?php echo !empty($senhaError)?$senhaError:'';?>
			<input class="input_" name="senha" placeholder="Senha" type="password" width="300"><br>
			
			<span class="text2">Confirmação</span><br><?php echo !empty($confSenhaError)?$confSenhaError:'';?>
			<input class="input_" name="confSenha" placeholder="Confirmação" type="password" width="300"><br><br>
			<input class="botao" value="Enviar" type="submit">
		</form>
	</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>