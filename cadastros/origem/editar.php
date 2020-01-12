<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>

<?php
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: /sgpi/cadastros/origem");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $descError = null;
         
        // keep track post values
        $desc = $_POST['descricao'];
         
        // validate input
        $valid = true;
        if (empty($desc)) {
            $descError = 'Por favor digite a descrição';
            $valid = false;
        }
         
        // update data
        if ($valid) {
			$sql = "UPDATE origem set descricao = '".$desc."' WHERE id = ".$id;
			if(mysql_query($sql)){
				$msg = "Gravado com sucesso!";
			}else{
				$msg = "Erro ao gravar!";
				//echo mysql_errno() . ": " . mysql_error() . "\n";
			}         
			echo "<script>window.location.href='/sgpi/cadastros/origem';</script>";
        }
    } else {
		$query = "SELECT * FROM origem WHERE id = ".$id;
		$sql = mysql_query($query);
		while($row = mysql_fetch_array( $sql )) {
			$desc = $row[1];
		}
	} 
?>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Atualizar Origem</h3>
                    </div>
             
                    <form class="form-horizontal" action="editar.php?id=<?php echo $id?>" method="post">
                      <div class="control-group <?php echo !empty($descError)?'error':'';?>">
                        <label class="control-label">Descrição</label>
                        <div class="controls">
                            <input name="descricao" type="text" placeholder="Descrição" value="<?php echo !empty($desc)?$desc:'';?>">
                            <?php if (!empty($descError)): ?>
                                <span class="help-inline"><?php echo $descError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Atualizar</button>
                          <a class="btn" href="index.php">Voltar</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
        