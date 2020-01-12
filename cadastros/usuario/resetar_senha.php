<?php 

    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
	$id = $_GET['id'];
	//echo $id;
	$sql = "select email from usuario where id = ".$id;
	$result = mysqli_query($conn, $sql);
	$emailMd5 = null;
	while($row = mysqli_fetch_row($result))
	{
		$emailMd5 = $row[0];
	} 
	
	$dataMd5 = date("Y-m-d");
	
    $url = sprintf( 'id=%s&email=%s&key=%s', $id, md5($emailMd5), md5($data_ts));

	$mensagem = 'Para cadastrar uma nova senha acesse o link:'."\n";
	$mensagem .= sprintf('http://www.credimovelsi.com.br/sgpi/cadastros/usuario/redefinir_senha.php?%s',$url);

	// enviar o email
	mail( $emailMd5, mb_encode_mimeheader("Redefinição de Senha CredImóvel","UTF-8"), $mensagem );
	echo "Link enviado com sucesso!";
	
?>