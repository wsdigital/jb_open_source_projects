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
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>Dashboard | Categorias</title>
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
<div class="wrap">
  <div id="header">
    <meta charset="iso-8859-1" />
    <div id="header">
	  <div id="top">
	    <div class="left">
	      <p>Bem Vindo, <strong><?php echo $row_userlogado['nome']; ?></strong> [ <a href="<?php echo $logoutAction ?>">sair</a> ]</p>
        </div>
	    <div class="right">
				<div class="align-right">
					<p><strong><script type="text/javascript" src="../plugins/data.js"></script> de <?php echo date("Y") ?> | Hora Certa<script type="text/javascript" src="../plugins/hora.js"></script> <span id="timer">Horas</span></strong></p>
				</div>
			</div>
		</div>
	</div>
    <div id="nav"></div>
  </div>
  
  <div id="content">
    <div id="sidebar">
      <div class="box">
        <div class="h_title">&#8250; Painel Principal</div>
        <ul>
          <li class="b1"><a class="icon view_page" href="Home">Home</a></li>
          <li class="b1"><a class="icon add_page" href="Novo.Post">Novo Post</a></li>
          <li class="b1"><a class="icon add_page" href="Novo.Projeto">Novo Projeto</a></li>
          <li class="b1"><a class="icon add_page" href="Nova.Categoria.do.Blog">Nova Categoria do Blog</a></li>
          <li class="b1"><a class="icon add_page" href="Nova.Categoria.de.Projeto">Nova Categoria de Projeto</a></li>
          <li class="b1"><a class="icon add_page" href="Add.Imagem.ao.Projeto">Add Imagem ao Projeto</a></li>
          
        </ul>
      </div>
      <div class="box">
        <div class="h_title">&#8250; Gerenciar Conteudo</div>
        <ul id="home">
          <li class="b1"><a class="icon config" href="Projetos">Projetos</a></li>
          <li class="b1"><a class="icon config" href="Postagens">Postagens</a></li>
          <li class="b1"><a class="icon config" href="Comentarios">Comentários</a></li>
          <li class="b1"><a class="icon config" href="Categoria.do.Blog">Categoria do Blog</a></li>
          <li class="b1"><a class="icon config" href="Categoria.de.Projetos">Categoria de Projetos</a></li>
          <li class="b1"><a class="icon config" href="Imagem.dos.Projetos">Imagem dos Projetos</a></li>
        </ul>
      </div>
    </div>
    <div id="main">
	  <div class="clear"></div>
		  <div class="full_w">
		    <div class="h_title">Add Novo Post</div>
				<form action="" method="POST" enctype="multipart/form-data" name="cadpost" id="cadpost">
					<div class="element">
                    
                    <input name="link" type="hidden" id="link" value="post"/>
                    <input name="data" type="hidden" id="data" value="<?php echo date('d/M/Y') ?>"/>
                    <input name="hora" type="hidden" id="hora" value="<?php echo date('g:i A') ?>"/>
                    <input name="autor" type="hidden" id="autor" value=""/>
                    <input name="username" type="hidden" id="username" value=""/>
                    
						<label for="titulo">Titulo</label>
						<input id="titulo" name="titulo" class="text " />
					</div>
                    <div class="element">
						<label for="titulo">Capa</label>
						<input id="titulo" name="titulo" type="file" class="text " />
					</div>
					<div class="element">
						<label for="menu">Categoria</label>
						<select name="menu" id="menu">
						  <option value="categoria1">Categoria 1</option>
						  <option value="categoria2">Categoria 2</option>
                      </select>
					</div>
					
					
					<div class="element">
						<label for="mindesc">Conteudo</label>
						<textarea name="content" class="textarea" rows="20"></textarea>
					</div>
					<div class="entry">
					<button type="submit" class="add">Salvar</button> 
						<br>
				  </div>
				</form>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div id="footer">

		<div class="left">

			

		</div>

		<div class="right">

			<p>Design e Desenvolvimento: <a href="http://jambull.com.br">Jambull</a></p>

		</div>

	</div>
	
    </div>

</body>
</html>
<?php
mysql_free_result($userlogado);
?>
