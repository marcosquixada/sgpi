<?php
if(isset($_GET['id']))
{
	// if id is set then get the file with the id from database

	include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

	$id    = $_GET['id'];
	$query = "SELECT name, type, size, content " .
			 "FROM upload WHERE id = '$id'";

	$result = mysql_query($query) or die('Error, query failed');
	list($name, $type, $size, $content) = mysql_fetch_array($result);

	header("Content-length: $size");
	header("Content-type: $type");
	header("Content-Disposition: attachment; filename=$name");
	echo $content;

	exit;
}

?>

<html>
<head>
<title>Download File From MySQL</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");

$query = "SELECT id, name FROM upload";
$result = mysql_query($query) or die('Error, query failed');
if(mysql_num_rows($result) == 0) {
	echo "Database is empty <br>";
} else {
	while(list($id, $name) = mysql_fetch_array($result)) {
?>
<a href="download.php?id=<?php echo $id; ?>"><?php echo $name; ?></a> <br>
<?php
}
}
?>
</body>
</html>
