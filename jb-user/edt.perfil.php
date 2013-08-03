<?php require_once('../Connections/conn.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "cadpost")) {
  $updateSQL = sprintf("UPDATE usuario SET nome=%s, username=%s, email=%s, senha=%s WHERE id=%s",
                       GetSQLValueString($_POST['nome'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString(base64_encode($_POST['senha']), "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

$colname_atlperfil = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_atlperfil = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_atlperfil = sprintf("SELECT id, nome, username, email, senha FROM usuario WHERE username = %s", GetSQLValueString($colname_atlperfil, "text"));
$atlperfil = mysql_query($query_atlperfil, $conn) or die(mysql_error());
$row_atlperfil = mysql_fetch_assoc($atlperfil);
$totalRows_atlperfil = mysql_num_rows($atlperfil);
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>Dashboard | Nova Categoria do Blog</title>
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

<body>
<div id="main">
  <div class="clear"></div>
  <div class="full_w">
    <div class="h_title">Editar Perfil</div>
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="cadpost" id="cadpost">
      <div class="element">
        <input name="id" type="hidden" id="id" value="<?php echo $row_atlperfil['id']; ?>"/>
        <input name="username" type="hidden" id="username" value="<?php echo $row_atlperfil['username']; ?>"/>
        <label for="nome">Nome Completo</label>
        <input name="nome" class="text " id="nome" value="<?php echo $row_atlperfil['nome']; ?>"  />
      </div>
      <div class="element">
        <label for="nome">E-Mail</label>
        <input name="email" class="text " id="email" value="<?php echo $row_atlperfil['email']; ?>"  />
      </div>
      <div class="element">
        <label for="nome">Senha</label>
        <input name="senha" type="text" class="text " id="senha" value="<?php echo base64_decode($row_atlperfil['senha']); ?>"  />
      </div>
      <div class="entry">
        <button type="submit" class="add">Salvar</button>
        <br>
      </div>
      <input type="hidden" name="MM_update" value="cadpost">
    </form>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($atlperfil);
?>
