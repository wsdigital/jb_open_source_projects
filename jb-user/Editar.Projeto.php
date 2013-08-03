<?php require_once('../Connections/conn.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "./";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "./";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<!--função-de-upload-->
<?php
// Se o usuário clicou no botão cadastrar efetua as ações
if ($_POST['cadastrar']) {
	
	// Recupera os dados dos campos
	$foto = $_FILES["foto"];
	
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "cadastro")) {
  $updateSQL = sprintf("UPDATE projeto SET autor=%s, username=%s, categoria=%s, titulo=%s, descricao=%s, demo=%s, download=%s WHERE id=%s",
                       GetSQLValueString($_POST['autor'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['menu'], "text"),
                       GetSQLValueString($_POST['titulo'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['demo'], "text"),
                       GetSQLValueString($_POST['projeto'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

$colname_projetoedt = "-1";
if (isset($_GET['id'])) {
  $colname_projetoedt = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_projetoedt = sprintf("SELECT * FROM projeto WHERE id = %s", GetSQLValueString($colname_projetoedt, "int"));
$projetoedt = mysql_query($query_projetoedt, $conn) or die(mysql_error());
$row_projetoedt = mysql_fetch_assoc($projetoedt);
$totalRows_projetoedt = mysql_num_rows($projetoedt);

$colname_userlogado = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_userlogado = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_userlogado = sprintf("SELECT * FROM usuario WHERE username = %s", GetSQLValueString($colname_userlogado, "text"));
$userlogado = mysql_query($query_userlogado, $conn) or die(mysql_error());
$row_userlogado = mysql_fetch_assoc($userlogado);
$totalRows_userlogado = mysql_num_rows($userlogado);

mysql_select_db($database_conn, $conn);
$query_menu = "SELECT * FROM projetos_menu ORDER BY titulo ASC";
$menu = mysql_query($query_menu, $conn) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>Dashboard | Novo Projeto</title>
<link rel="stylesheet" type="text/css" href="../tema/administrator/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../tema/administrator/navi.css" media="screen" />
<script type="text/javascript" src="../tema/administrator/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".box .h_title").not(this).next("ul").hide("normal");
	$(".box .h_title").not(this).next("#home").show("normal");
	$(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
});
</script>
<script type="text/javascript" src="../plugins/tiny_mce/jscripts/tiny_mce.js"></script>
<script type="text/javascript" src="../plugins/tiny_mce/jscripts/plugins/tinybrowser/tb_tinymce.js.php"></script>

<script type="text/javascript" src="../plugins/tiny_mce/editor.js"></script>

</head>
<body onLoad="initTime();">
<div id="main">
  <div class="clear"></div>
  <div class="full_w">
    <div class="h_title">Editar Projeto -> <strong><?php echo $row_projetoedt['titulo']; ?></strong></div>
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="cadastro">
      <div class="element">
        <input name="id" type="hidden" id="id" value="<?php echo $row_projetoedt['id']; ?>"/>
        <input name="autor" type="hidden" id="autor" value="<?php echo $row_userlogado['nome']; ?>"/>
        <input name="username" type="hidden" id="username" value="<?php echo $row_userlogado['username']; ?>"/>
        <label for="projeto">Titulo</label>
        <input name="titulo" class="text " id="titulo" value="<?php echo $row_projetoedt['titulo']; ?>" />
      </div>
      <div class="element">
        <label for="projeto">Download</label>
        <input name="projeto" type="text" class="text " id="projeto" placeholder="URL de Download" value="<?php echo $row_projetoedt['download']; ?>" />
      </div>
      <div class="element">
        <label for="projeto">Demonstra&ccedil;&atilde;o</label>
        <input name="demo" class="text " id="demo" placeholder="URL de Demonstra&ccedil;&atilde;o" value="<?php echo $row_projetoedt['demo']; ?>"/>
      </div>
      <div class="element">
        <label for="menu">Categoria</label>
        <select name="menu" id="menu">
          <option value="" <?php if (!(strcmp("", $row_projetoedt['categoria']))) {echo "selected=\"selected\"";} ?>>Selecione uma Categoria</option>
          <?php
do {  
?>
          <option value="<?php echo $row_menu['id']?>"<?php if (!(strcmp($row_menu['id'], $row_projetoedt['categoria']))) {echo "selected=\"selected\"";} ?>><?php echo $row_menu['titulo']?></option>
          <?php
} while ($row_menu = mysql_fetch_assoc($menu));
  $rows = mysql_num_rows($menu);
  if($rows > 0) {
      mysql_data_seek($menu, 0);
	  $row_menu = mysql_fetch_assoc($menu);
  }
?>
        </select>
      </div>
      <div class="element">
        <label for="mindesc">Descri&ccedil;&atilde;o</label>
        <textarea name="content" class="textarea" rows="20"><?php echo $row_projetoedt['descricao']; ?></textarea>
      </div>
      <div class="entry">
        <input name="cadastrar" type="submit" id="cadastrar" value="Cadastrar">
        <br>
      </div>
      <input type="hidden" name="MM_update" value="cadastro">
    </form>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($projetoedt);

mysql_free_result($userlogado);

mysql_free_result($menu);
?>
