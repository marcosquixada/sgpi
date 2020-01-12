<?php 
$conn=mysqli_connect('localhost','mqsys082_root','mudar#123','mqsys082_sgpi2');
mysqli_set_charset($conn, 'utf-8');
mysqli_query($conn, "SET NAMES 'utf8'");
mysqli_query($conn, 'SET character_set_connection=utf8');
mysqli_query($conn, 'SET character_set_client=utf8');
mysqli_query($conn, 'SET character_set_results=utf8');
//$db=mysql_select_db('mqsys082_sgpi2',$conn);
?>