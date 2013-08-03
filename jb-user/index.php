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

mysql_select_db($database_conn, $conn);
$query_logauser = "SELECT * FROM usuario";
$logauser = mysql_query($query_logauser, $conn) or die(mysql_error());
$row_logauser = mysql_fetch_assoc($logauser);
$totalRows_logauser = mysql_num_rows($logauser);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=base64_encode($_POST['senha']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "Home";
  $MM_redirectLoginFailed = "./";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn, $conn);
  
  $LoginRS__query=sprintf("SELECT username, senha FROM usuario WHERE username=%s AND senha=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>JamBull | Login</title>
<link rel="stylesheet" type="text/css" href="../tema/administrator/login.css">

</head>

<body>
<form action="<?php echo $loginFormAction; ?>" method="POST" name="cadastro" class="box login">
	<fieldset class="boxBody">
    
	  
      <label>Usu&aacute;rio</label>
	  <input name="username" type="text" required id="username" placeholder="Usuário" tabindex="2">
	  <label>Senha</label>
	  <input name="senha" type="password" required id="senha" placeholder="Senha" tabindex="5">
	</fieldset>
  <footer>
    <!--<label><input type="checkbox" tabindex="3">Lembre-me</label>-->
	  <input name="cadastrar" type="submit" class="btnLogin" id="cadastrar" tabindex="4" value="Entrar">
      <a href="cadastre-se">Não sou Cadastrado Ainda</a>
	</footer>
</form>
<footer id="main">&copy; 2012 - <?php echo date('Y'); ?> <a href="#">JamBull.com.br</a></footer>
</body>
</html>
<?php
mysql_free_result($logauser);
?>
