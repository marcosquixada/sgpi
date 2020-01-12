<?php
     
    include($_SERVER['DOCUMENT_ROOT']."/sgpi/conexao/db.php");
 
    if ( !empty($_POST)) {
        // keep track validation errors
        $tipoError = null;
		$nomeError = null;
		$cpfError = null;
		$cnpjError = null;
		$dtNascError = null;
		$sexoError = null;
		$emailError = null;
		$cepError = null;
		$enderecoError = null;
		$numeroError = null;
		$estadoError = null;
		$cidadeError = null;
		$bairroError = null;
		$tel1Error = null;
		$tel2Error = null;
		$razaoSocialError = null;
		$nomeFantasiaError = null;
		$inscricaoEstadualError = null;
		$inscricaoMunicipalError = null;
		
        // keep track post values
        $tipo = $_POST['tipo'];
		$idOrigem = $_POST['idOrigem'];
		$nome = $_POST['nome'];
		$cpf = str_replace('.','',str_replace('-','',$_POST['cpf']));
		$parte_um     = substr($cpf, 0, 3);
		$parte_dois   = substr($cpf, 3, 3);
		$parte_tres   = substr($cpf, 6, 3);
		$parte_quatro = substr($cpf, 9, 2);
		$cpf = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";
		$cnpj = $_POST['cnpj'];
		$dtNasc = date("Y-d-m",strtotime($_POST['dtNasc']));
		$sexo = $_POST['sexo'];
		$email = $_POST['email'];
		$cep = $_POST['cep'];
		$endereco = $_POST['endereco'];
		$numero = $_POST['numero'];
		$estado = $_POST['estado'];
		$cidade = $_POST['cidade'];
		$bairro = $_POST['bairro'];
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$razaoSocial = $_POST['razaoSocial'];
		$nomeFantasia = $_POST['nomeFantasia'];
		$inscricaoEstadual = $_POST['inscricaoEstadual'];
		$inscricaoMunicipal = $_POST['inscricaoMunicipal'];
		$responsavel = $_POST['responsavel'];
		$complemento = $_POST['complemento'];
         
        // validate input
        $valid = true;
        
		if (empty($tipo)) {
			$tipoError = 'Por favor informe o Tipo.';
			$valid = false;
		}
		if ($idOrigem === '-') {
			$origemError = 'Por favor informe a Origem.';
			$valid = false;
		}
		
        $uid = uniqid( rand(), true );
		$data_ts = time();
		$ativo = 0;
		$sql = null;
        // insert data
        if ($valid) {
			if($tipo === 'C'){
				//echo $nome;
				//echo $tipo;
				$sql = "INSERT INTO Usuario (nome, cpf, IdOrigem, DataNascimento, sexo, email, cep, logradouro, estado, cidade, numero, complemento, tipo, data_ts, uid, ativo, telefone1, telefone2) values('".$nome."', '".$cpf."', ".$idOrigem.", '".$dtNasc."', '".$sexo."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', ".$numero.", '".$complemento."', 'C', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."')";
			}else if($tipo === 'J') //Construtora
				$sql = "INSERT INTO Usuario (cnpj, IdOrigem, RazaoSocial, NomeFantasia, InscricaoEstadual, InscricaoMunicipal, email, cep, logradouro, estado, cidade, numero, complemento, responsavel, tipo, data_ts, uid, ativo, telefone1, telefone2) values('".$cnpj."', '".$idOrigem."', '".$razaoSocial."', '".$nomeFantasia."', '".$inscricaoEstadual."', '".$inscricaoMunicipal."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', ".$numero.", '".$complemento."', '".$responsavel."', 'J', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."')";
			else if($tipo === 'T') //Atendente
				$sql = "INSERT INTO Usuario (nome, cpf, IdOrigem, DataNascimento, sexo, email, cep, logradouro, estado, cidade, numero, complemento, tipo, data_ts, uid, ativo, telefone1, telefone2) values('".$nome."', '".$cpf."', ".$idOrigem.", '".$dtNasc."', '".$sexo."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', ".$numero.", '".$complemento."', 'T', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."')";
			else{ //Administrador
				
				$sql = "INSERT INTO Usuario (nome, cpf, IdOrigem, DataNascimento, sexo, email, cep, logradouro, estado, cidade, numero, complemento, tipo, data_ts, uid, ativo, telefone1, telefone2) values('".$nome."', '".$cpf."', ".$idOrigem.", '".$dtNasc."', '".$sexo."', '".$email."', '".$cep."', '".$endereco."', '".$estado."', '".$cidade."', '".$numero."', '".$complemento."', 'A', '".$data_ts."','".$uid."','".$ativo."', '".$tel1."', '".$tel2."')";
			}
			
            if(mysqli_query($conn, $sql)){
				$id = mysqli_insert_id($conn);
			
				// Criar as variaveis para validar o email
				$url = sprintf( 'id=%s&email=%s&uid=%s&key=%s', $id, md5($email), md5($uid), md5($data_ts));

				$mensagem = 'Para confirmar seu cadastro acesse o link:'."\n";
				$mensagem .= sprintf('http://www.mqsystems.com.br/sgpi/cadastros/usuario/ativar.php?%s',$url);

				// enviar o email
				mail( $email, 'Confirmacao de cadastro', $mensagem );
			}else{
				$msg = "Erro ao gravar!";
				echo mysqli_errno($conn) . ": " . mysqli_error($conn) . "\n";
			}

			//echo "Registro inserido com sucesso";
            header("Location: /sgpi/cadastros?table=Usuario");
        }
    }
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/header.php"); ?>
<script>
function ativaCPF(){
	document.getElementById('cnpj').style.display = 'none';
	document.getElementById('razaoSocial').style.display = 'none';
	document.getElementById('nomeFantasia').style.display = 'none';
	document.getElementById('inscricaoEstadual').style.display = 'none';
	document.getElementById('inscricaoMunicipal').style.display = 'none';
	document.getElementById('cpf').style.display = 'block';
	document.getElementById('nome').style.display = 'block';
	document.getElementById('sexo').style.display = 'block';
	document.getElementById('dtNasc').style.display = 'block';
	document.getElementById('responsavel').style.display = 'none';
}

function ativaCNPJ(){
	document.getElementById('cnpj').style.display = 'block';
	document.getElementById('razaoSocial').style.display = 'block';
	document.getElementById('nomeFantasia').style.display = 'block';
	document.getElementById('inscricaoEstadual').style.display = 'block';
	document.getElementById('inscricaoMunicipal').style.display = 'block';
	document.getElementById('cpf').style.display = 'none';
	document.getElementById('nome').style.display = 'none';
	document.getElementById('sexo').style.display = 'none';
	document.getElementById('dtNasc').style.display = 'none';
	document.getElementById('responsavel').style.display = 'block';
}
</script>
    <div class="container">
     
                <div>
                    <div>
                        <h3 class="register-title">Cadastro de Usuário</h3>
                    </div>
             
                    <form class="register" action="cadastrar.php" method="post">
					  <div>
                        <label>Tipo</label>
                        <div>
								<input name="tipo" type="radio" value="C" checked onclick="ativaCPF();" />CPF
								<input name="tipo" type="radio" value="J" onclick="ativaCNPJ();" />CNPJ
							<?php if($_SESSION['tipo'] === 'A') { ?>
								<input name="tipo" type="radio" value="T" />Atendente
								<input name="tipo" type="radio" value="A" />Administrador
							<?php } ?>
                            <?php if (!empty($tipoError)): ?>
                                <span><?php echo $tipoError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
					  <div>
                        <label>Origem</label>
                        <div>
                             <select name="idOrigem">
							  <option value="-">Selecione uma opção</option>
							  <option value="1">WhatsApp</option>
							  <option value="2">Facebook</option>
							  <option value="3">Instagram</option>
							  <option value="4">Mídia Impressa</option>
							  <option value="5">Indicação</option>
							</select> 
                            <?php if (!empty($origemError)): ?>
                                <span><?php echo $origemError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
					  
					  <div id="pf">
						  <div id="nome">
							<label>Nome</label>
							<div>
								<input name="nome" type="text" placeholder="Nome" value="<?php echo !empty($nome)?$nome:'';?>">
								<?php if (!empty($nomeError)): ?>
									<span><?php echo $nomeError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div id="cpf">
							<label>CPF</label>
							<div>
								<input name="cpf" type="text" placeholder="CPF" value="<?php echo !empty($cpf)?$cpf:'';?>">
								<?php if (!empty($cpfError)): ?>
									<span><?php echo $cpfError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div id="dtNasc">
							<label>Data de Nascimento</label>
							<div>
								<input name="dtNasc" type="text" placeholder="Data de Nascimento" value="<?php echo !empty($dtNasc)?$dtNasc:'';?>" id="date">
								<?php if (!empty($dtNascError)): ?>
									<span><?php echo $dtNascError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div id="sexo">
							<label>Sexo</label>
							<div>
								<input name="sexo" type="radio" value="M" />Masculino
								<input name="sexo" type="radio" value="F" />Feminino
								<?php if (!empty($sexoError)): ?>
									<span><?php echo $sexoError;?></span>
								<?php endif; ?>
							</div>
						  </div>
					  </div>
					  
					  <div id="pj">
						  <div style="display: none;" id="cnpj">
							<label>CNPJ</label>
							<div>
								<input name="cnpj" type="text" placeholder="CNPJ" value="<?php echo !empty($cnpj)?$cnpj:'';?>">
								<?php if (!empty($cnpjError)): ?>
									<span><?php echo $cnpjError;?></span>
								<?php endif; ?>
							</div>
							<div <?php echo !empty($razaoSocialError)?'error':'';?>" style="display: none;" id="razaoSocial">
							<label>Razão Social</label>
							<div>
								<input name="razaoSocial" type="text" placeholder="Razão Social" value="<?php echo !empty($razaoSocial)?$razaoSocial:'';?>">
								<?php if (!empty($razaoSocialError)): ?>
									<span><?php echo $razaoSocialError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div style="display: none;" id="nomeFantasia">
							<label>Nome Fantasia</label>
							<div>
								<input name="nomeFantasia" type="text" placeholder="Nome Fantasia" value="<?php echo !empty($nomeFantasia)?$nomeFantasia:'';?>">
								<?php if (!empty($nomeFantasiaError)): ?>
									<span><?php echo $nomeFantasiaError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div style="display: none;" id="inscricaoEstadual">
							<label>Inscrição Estadual</label>
							<div>
								<input name="inscricaoEstadual" type="text" placeholder="Inscrição Estadual" value="<?php echo !empty($inscricaoEstadual)?$inscricaoEstadual:'';?>">
								<?php if (!empty($inscricaoEstadualError)): ?>
									<span><?php echo $inscricaoEstadualError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div style="display: none;" id="inscricaoMunicipal">
							<label>Inscrição Municipal</label>
							<div>
								<input name="inscricaoMunicipal" type="text" placeholder="Inscrição Municipal" value="<?php echo !empty($inscricaoMunicipal)?$inscricaoMunicipal:'';?>">
								<?php if (!empty($inscricaoMunicipalError)): ?>
									<span><?php echo $inscricaoMunicipalError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div style="display: none;" id="responsavel">
							<label>Responsável</label>
							<div>
								<input name="responsavel" type="text" placeholder="Responsável" value="<?php echo !empty($responsavel)?$responsavel:'';?>">
								<?php if (!empty($responsavelError)): ?>
									<span><?php echo $responsavelError;?></span>
								<?php endif; ?>
							</div>
						  </div>
                      </div>
					  
					  <div id="areacomum">
						  <div>
							<label>Email</label>
							<div>
								<input name="email" type="text" placeholder="Email" value="<?php echo !empty($email)?$email:'';?>">
								<?php if (!empty($emailError)): ?>
									<span><?php echo $emailError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>CEP</label>
							<div>
								<input name="cep" type="text" placeholder="CEP" value="<?php echo !empty($cep)?$cep:'';?>">
								<?php if (!empty($cepError)): ?>
									<span><?php echo $cepError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Endereço</label>
							<div>
								<input name="endereco" type="text" placeholder="Endereço" value="<?php echo !empty($endereco)?$endereco:'';?>">
								<?php if (!empty($enderecoError)): ?>
									<span><?php echo $enderecoError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Número</label>
							<div>
								<input name="numero" type="text" placeholder="Número" value="<?php echo !empty($numero)?$numero:'';?>">
								<?php if (!empty($numeroError)): ?>
									<span><?php echo $numeroError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Complemento</label>
							<div>
								<input name="complemento" type="text" placeholder="Complemento" value="<?php echo !empty($complemento)?$complemento:'';?>">
								<?php if (!empty($complementoError)): ?>
									<span><?php echo $complementoError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Estado</label>
							<div>
								<input name="estado" type="text" placeholder="Estado" value="<?php echo !empty($estado)?$estado:'';?>">
								<?php if (!empty($estadoError)): ?>
									<span><?php echo $estadoError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Cidade</label>
							<div>
								<input name="cidade" type="text" placeholder="Cidade" value="<?php echo !empty($cidade)?$cidade:'';?>">
								<?php if (!empty($cidadeError)): ?>
									<span><?php echo $cidadeError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Bairro</label>
							<div>
								<input name="bairro" type="text" placeholder="Bairro" value="<?php echo !empty($bairro)?$bairro:'';?>">
								<?php if (!empty($bairroError)): ?>
									<span><?php echo $bairroError;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Telefone 1</label>
							<div>
								<input name="tel1" type="text" placeholder="Telefone 1" value="<?php echo !empty($tel1)?$tel1:'';?>" id="phone1">
								<?php if (!empty($tel1Error)): ?>
									<span><?php echo $tel1Error;?></span>
								<?php endif; ?>
							</div>
						  </div>
						  <div>
							<label>Telefone 2</label>
							<div>
								<input name="tel2" type="text" placeholder="Telefone 2" value="<?php echo !empty($tel2)?$tel2:'';?>" id="phone2">
								<?php if (!empty($tel2Error)): ?>
									<span><?php echo $tel2Error;?></span>
								<?php endif; ?>
							</div>
						  </div>
					  </div>
					  
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Cadastrar</button>
                          <a class="btn" href="index.php">Voltar</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
<?php include($_SERVER['DOCUMENT_ROOT']."/sgpi/footer.php"); ?>