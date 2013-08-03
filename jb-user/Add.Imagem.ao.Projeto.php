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

$colname_userlogado = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_userlogado = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_userlogado = sprintf("SELECT * FROM usuario WHERE username = %s", GetSQLValueString($colname_userlogado, "text"));
$userlogado = mysql_query($query_userlogado, $conn) or die(mysql_error());
$row_userlogado = mysql_fetch_assoc($userlogado);
$totalRows_userlogado = mysql_num_rows($userlogado);

$colname_menu = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_menu = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_menu = sprintf("SELECT * FROM projeto WHERE username = %s ORDER BY titulo ASC", GetSQLValueString($colname_menu, "text"));
$menu = mysql_query($query_menu, $conn) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);
?>
<!--fun��o-de-upload-->
<?php
// Se o usu�rio clicou no bot�o cadastrar efetua as a��es
if ($_POST['cadastrar']) {
	
	// Recupera os dados dos campos
	$autor = $_POST['autor'];
	$menu = $_POST['menu'];
	$titulo = $_POST['titulo'];
	$foto = $_FILES["foto"];
	
	// Se a foto estiver sido selecionada
	if (!empty($foto["name"])) {
		
		// Largura m�xima em pixels
		$largura = 3000;
		// Altura m�xima em pixels
		$altura = 3000;
		// Tamanho m�ximo do arquivo em bytes
		$tamanho = 100000;

    	// Verifica se o arquivo � uma imagem
    	if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
     	   $error[1] = "Isso n�o � uma imagem.";
   	 	} 
	
		// Pega as dimens�es da imagem
		$dimensoes = getimagesize($foto["tmp_name"]);
	
		// Verifica se a largura da imagem � maior que a largura permitida
		if($dimensoes[0] > $largura) {
			$error[2] = "A largura da imagem n�o deve ultrapassar ".$largura." pixels";
		}

		// Verifica se a altura da imagem � maior que a altura permitida
		if($dimensoes[1] > $altura) {
			$error[3] = "Altura da imagem n�o deve ultrapassar ".$altura." pixels";
		}
		
		// Verifica se o tamanho da imagem � maior que o tamanho permitido
		if($arquivo["size"] > $tamanho) {
   		 	$error[4] = "A imagem deve ter no m�ximo ".$tamanho." bytes";
		}

		// Se n�o houver nenhum erro
		if (count($error) == 0) {
		
			// Pega extens�o da imagem
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

        	// Gera um nome �nico para a imagem
        	$nome_imagem = md5(uniqid(time())) . "." . $ext[1];

        	// Caminho de onde ficar� a imagem
        	$caminho_imagem = "../jb-includes/uploads/images/projetos/" . $nome_imagem;

			// Faz o upload da imagem para seu respectivo caminho
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
			
			// Insere os dados no banco
			$sql = mysql_query("INSERT INTO projeto_imagem VALUES ('', '".$menu."', '".$nome_imagem."', '".$titulo."', '".$autor."')");
		
			// Se os dados forem inseridos com sucesso
			if ($sql){
				header("Location: Add.Imagem.ao.Projeto");
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

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>Dashboard | Add Imagem ao Projeto</title>
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
    <div class="h_title">Add Imagem ao Projeto</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="cadastro">
      <div class="element">
        <input name="autor" type="hidden" id="autor" value="<?php echo $row_userlogado['username']; ?>" />
        <label for="projeto">Capa</label>
        <input id="foto" name="foto" type="file" class="text " />
      </div>
      <div class="element">
        <label>Titulo da Imagem</label>
        <input name="titulo" type="text" id="titulo" required>
      </div>
      <div class="element">
        <label for="menu">Meus Projetos</label>
        <select name="menu" id="menu" required>
          <option value="">Selecione um Projeto</option>
          <?php do { ?>
          <option value="<?php echo $row_menu['id']?>"><?php echo $row_menu['titulo']?></option>
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
      <div class="entry">
        <input name="cadastrar" type="submit" id="cadastrar" value="Cadastrar">
        <br>
      </div>
    </form>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($userlogado);

mysql_free_result($menu);
?>
