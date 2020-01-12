<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php

$idCliente = $_GET['idCliente'];

if(isset($_POST['idCliente'])){
	$idCliente = $_POST['idCliente'];
	$i = 0;
	foreach($_FILES as $arquivo){
		$id = $_POST['id'][$i];
		$idDoc = $_POST['doc'][$i];
		$entregue = "N";
		if (isset($_POST['check'.$idDoc])){
			$entregue = "S";
		}
		
		$file = null;
		$fileName = $arquivo['name'];
		$tmpName  = $arquivo['tmp_name'];
		$fileSize = $arquivo['size'];
		$fileType = $arquivo['type'];
		
		if(!empty($fileName)){
			$file = rand(1000,100000)."-".$fileName;
			$folder=$_SERVER['DOCUMENT_ROOT']."/sgpi/uploads/";
			move_uploaded_file($tmpName,$folder.$file);
		}
		
		if(!empty($fileName)){
			$sql = "UPDATE cliente_documento SET name='".$file."', entregue='".$entregue."', size='".$fileSize."', type='".$fileType."' WHERE id='".$id."'";
		}else{
			$sql = "UPDATE cliente_documento SET entregue='".$entregue."', size='".$fileSize."', type='".$fileType."' WHERE id='".$id."'";
		}
		
		
		mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$i++;
	}
	//die(" - ".$i);
	echo "<script>alert('Registros atualizados com sucesso!');</script>";
}

$result = mysqli_query($conn, "select cd.id, 
							  cd.idCliente, 
							  cd.idDocumento,
							  cd.name,
							  d.descricao,
							  cd.entregue
                       from cliente_documento cd,
					        documento d
					   where cd.idCliente = ".$idCliente."
					     and cd.idDocumento = d.id");

echo "<h1>Check-List Documentos</h1>";
echo "<form action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">";
echo "<input type=\"hidden\" name=\"idCliente\" value=".$idCliente." />"; 
echo "<table border='1'>";
echo "<tr bgcolor=#3954A5>";
echo "<td>ID</td>";
echo "<td>ID CLIENTE</td>";
echo "<td>DOCUMENTO</td>";
echo "<td align=center>ENTREGUE</td>";
echo "<td align=center>PDF</td>";
echo "<td align=center>UPLOAD</td>";
echo "</tr>";
// printing table rows
while($row = mysqli_fetch_row($result))
{
	echo "<tr>";
    echo "<td><input type=hidden name=id[] value=".$row[0]." />".$row[0]."</td>";
	echo "<td>".$row[1]."</td>";
	echo "<td>".$row[4]."</td>";
	echo "<td align=center><input type=hidden name=doc[] value=".$row[2]." />";
		if($row[5] === 'S') 
			echo "<input type=checkbox name=check".$row[2]." checked/></td>";
		else
			echo "<input type=checkbox name=check".$row[2]." /></td>";
	if(!empty($row[3])){
		echo "<td align=center><a href='/sgpi/uploads/$row[3]' target='_blank'><img src='/sgpi/_img/download.png' height='15px' width='15px' alt='Baixar' Title='Baixar' /></a></td>";
	}else
		echo "<td></td>";
	echo "<td><input type=file name=arquivo".$row[2]." /></td>";
	echo "</tr>";			
}

mysqli_free_result($result);
echo "</table>";
echo "<p align=\"center\"><input type=\"submit\" name=\"upload\" \" value=Atualizar /><input type=\"button\" name=\"voltar\" onClick=\"history.back();\" value=Voltar /></p>";
echo "</form>";
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>
