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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "cadastro")) {
  $updateSQL = sprintf("UPDATE blog_post SET menu=%s, titulo=%s, corpo=%s, min_desc=%s WHERE id=%s",
                       GetSQLValueString($_POST['menu'], "text"),
                       GetSQLValueString($_POST['titulo'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
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

mysql_select_db($database_conn, $conn);
$query_menu = "SELECT * FROM blog_menu ORDER BY titulo ASC";
$menu = mysql_query($query_menu, $conn) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);

$colname_postedt = "-1";
if (isset($_GET['id'])) {
  $colname_postedt = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_postedt = sprintf("SELECT * FROM blog_post WHERE id = %s", GetSQLValueString($colname_postedt, "int"));
$postedt = mysql_query($query_postedt, $conn) or die(mysql_error());
$row_postedt = mysql_fetch_assoc($postedt);
$totalRows_postedt = mysql_num_rows($postedt);
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>Dashboard | Novo Post</title>
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
  <div class="full_w">
    <div class="h_title">Add Novo Post</div>
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="cadastro">
      <div class="element">
        <input name="id" type="hidden" id="id" value="<?php echo $row_postedt['id']; ?>"/>
        <label for="foto">Titulo</label>
        <input name="titulo" class="text " id="titulo" value="<?php echo $row_postedt['titulo']; ?>" />
      </div>
      <div class="element">
        <label for="menu">Categoria</label>
        <select name="menu" id="menu">
          <option value="" <?php if (!(strcmp("", $row_postedt['menu']))) {echo "selected=\"selected\"";} ?>>Selecione uma Categoria</option>
          <?php
do {  
?>
          <option value="<?php echo $row_menu['id']?>"<?php if (!(strcmp($row_menu['id'], $row_postedt['menu']))) {echo "selected=\"selected\"";} ?>><?php echo $row_menu['titulo']?></option>
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
        <label for="mindesc">Conteudo</label>
        <textarea name="content" class="textarea" rows="20"><?php echo $row_postedt['corpo']; ?></textarea>
      </div>
      <div class="entry">
        <input name="cadastrar" type="submit" id="cadastrar" value="Atualizar">
        <br>
      </div>
      <input type="hidden" name="MM_update" value="cadastro">
    </form>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($userlogado);

mysql_free_result($menu);

mysql_free_result($postedt);
?>
