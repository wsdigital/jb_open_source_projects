<?php require_once('../Connections/conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_capaprojeto = "-1";
if (isset($_GET['id'])) {
  $colname_capaprojeto = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_capaprojeto = sprintf("SELECT id, capa FROM projeto WHERE id = %s", GetSQLValueString($colname_capaprojeto, "int"));
$capaprojeto = mysql_query($query_capaprojeto, $conn) or die(mysql_error());
$row_capaprojeto = mysql_fetch_assoc($capaprojeto);
$totalRows_capaprojeto = mysql_num_rows($capaprojeto);

// Se o usuário clicou no botão cadastrar efetua as ações
if ($_POST['atualizar']) {
	
	// Recupera os dados dos campos
	$id       = $_POST['id'];
	$foto     = $_FILES["foto"];
	
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
        	$caminho_imagem = "../jb-includes/uploads/images/projetos/" . $nome_imagem;

			// Faz o upload da imagem para seu respectivo caminho
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
			
			// Insere os dados no banco
			$sql = mysql_query("UPDATE projeto SET capa='".$nome_imagem."' WHERE id='".$id."'");
		
			// Se os dados forem inseridos com sucesso
			if ($sql){
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" id="cadastrar">
<input name="id" type="hidden" id="id" value="<?php echo $row_capaprojeto['id']; ?>" />
<input name="foto" type="file" id="foto" />
<input type="submit" name="atualizar" id="atualizar" value="Atualizar"/>
</form>

</body>
</html>
<?php
mysql_free_result($capaprojeto);
?>
