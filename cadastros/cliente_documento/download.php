<?php
if(isset($_GET['id']))
{
// if id is set then get the file with the id from database

include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$id    = $_GET['id'];
$query = "SELECT name, type, size, content FROM cliente_documento WHERE id = '$id'";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
list($name, $type, $size, $content) = mysqli_fetch_array($result);

//header("Content-length: $size");
header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: attachment; filename=$name");
echo $content;

exit;
}

?>