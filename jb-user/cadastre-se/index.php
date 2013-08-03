<?php require_once('../../Connections/conn.php'); ?>
<!--função-de-upload-->
<?php
// Se o usuário clicou no botão cadastrar efetua as ações
if ($_POST['cadastrar']) {
	
	// Recupera os dados dos campos
	$nome = $_POST['nome'];
	$email = $_POST['email'];
	$foto = $_FILES["foto"];
	$username = $_POST['username'];
	$senha = base64_encode($_POST['senha']);
	
	// Se a foto estiver sido selecionada
	if (!empty($foto["name"])) {
		
		// Largura máxima em pixels
		$largura = 3000;
		// Altura máxima em pixels
		$altura = 3000;
		// Tamanho máximo do arquivo em bytes
		$tamanho = 100000;

    	// Verifica se o arquivo é uma imagem
    	if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
     	   $error[1] = "Isso não é uma imagem.";
   	 	} 
	
		// Pega as dimensões da imagem
		$dimensoes = getimagesize($foto["tmp_name"]);
	
		// Verifica se a largura da imagem é maior que a largura permitida
		if($dimensoes[0] > $largura) {
			$error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
		}

		// Verifica se a altura da imagem é maior que a altura permitida
		if($dimensoes[1] > $altura) {
			$error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
		}
		
		// Verifica se o tamanho da imagem é maior que o tamanho permitido
		if($arquivo["size"] > $tamanho) {
   		 	$error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
		}

		// Se não houver nenhum erro
		if (count($error) == 0) {
		
			// Pega extensão da imagem
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

        	// Gera um nome único para a imagem
        	$nome_imagem = md5(uniqid(time())) . "." . $ext[1];

        	// Caminho de onde ficará a imagem
        	$caminho_imagem = "../../jb-includes/uploads/images/user/" . $nome_imagem;

			// Faz o upload da imagem para seu respectivo caminho
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
		
			// Insere os dados no banco
			$sql = mysql_query("INSERT INTO usuario VALUES ('', '".$nome."', '".$username."', '".$nome_imagem."', '".$email."', '".$senha."', 'user')");
		
			// Se os dados forem inseridos com sucesso
			if ($sql){
				header("Location: ../");
			}
		}
	
		// Se houver mensagens de erro, exibe-as
		if (count($error) != 0) {
			foreach ($error as $erro) {
				echo $erro . "<br />";
			}
		}
	}
}

?>
<!--função-de-upload-->
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>JamBull | Cadastre-se</title>
<link rel="stylesheet" type="text/css" href="../../tema/administrator/login.css">

</head>

<body>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="cadastro" class="box cadastro">
	<fieldset class="boxBody">
    
	  <label>Nome</label>
	  <input name="nome" type="text" required id="nome" placeholder="Nome Completo" tabindex="1">
      <label>Usu&aacute;rio</label>
	  <input name="username" type="text" required id="username" placeholder="Usuário" tabindex="2">
      <label>Foto</label>
	  <input name="foto" type="file" required id="foto" placeholder="Foto" tabindex="3">
      <label>E-mail</label>
	  <input name="email" type="text" required id="email" placeholder="Usuário" tabindex="4">
	  <label>Senha</label>
	  <input name="senha" type="password" required id="senha" placeholder="Senha" tabindex="5">
	</fieldset>
  <footer>
    <!--<label><input type="checkbox" tabindex="3">Lembre-me</label>-->
	  <input name="cadastrar" type="submit" class="btnLogin" id="cadastrar" tabindex="4" value="Cadastrar">
    </footer>
</form>
<footer id="main">&copy; 2012 - <?php echo date('Y'); ?> <a href="#">JamBull.com.br</a></footer>
</body>
</html>