<?php
session_start();
$_SESSION['tipo'] = null;
$_SESSION = array();
//session_destroy();
if(session_destroy())
{
	header("Location: index.php");
}else{
	echo "impossible to destroy session.";
}
?>