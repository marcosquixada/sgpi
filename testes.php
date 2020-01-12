<!DOCTYPE html>
<html lang="pt-br"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- As 3 meta tags acima *devem* vir em primeiro lugar dentro do `head`; qualquer outro conteúdo deve vir *após* essas tags -->
<title>Página para Testes</title>
</head>
<body>
<?php 
$connection = ssh2_connect('http://192.241.241.25', 22);
ssh2_auth_password($connection, 'root', 'Mhcq.1210');

$sftp = ssh2_sftp($connection);

$stream = fopen("ssh2.sftp://$sftp/media/arquivos/valores/2016/", 'r');
var_dump($stream);
?>
</body>
</html>