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

$currentPage = $_SERVER["PHP_SELF"];

$colname_userlogado = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_userlogado = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_userlogado = sprintf("SELECT * FROM usuario WHERE username = %s", GetSQLValueString($colname_userlogado, "text"));
$userlogado = mysql_query($query_userlogado, $conn) or die(mysql_error());
$row_userlogado = mysql_fetch_assoc($userlogado);
$totalRows_userlogado = mysql_num_rows($userlogado);

$maxRows_listapost = 15;
$pageNum_listapost = 0;
if (isset($_GET['pageNum_listapost'])) {
  $pageNum_listapost = $_GET['pageNum_listapost'];
}
$startRow_listapost = $pageNum_listapost * $maxRows_listapost;

$colname_listapost = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_listapost = $_SESSION['MM_Username'];
}
mysql_select_db($database_conn, $conn);
$query_listapost = sprintf("SELECT * FROM blog_post WHERE username = %s ORDER BY id DESC", GetSQLValueString($colname_listapost, "text"));
$query_limit_listapost = sprintf("%s LIMIT %d, %d", $query_listapost, $startRow_listapost, $maxRows_listapost);
$listapost = mysql_query($query_limit_listapost, $conn) or die(mysql_error());
$row_listapost = mysql_fetch_assoc($listapost);

if (isset($_GET['totalRows_listapost'])) {
  $totalRows_listapost = $_GET['totalRows_listapost'];
} else {
  $all_listapost = mysql_query($query_listapost);
  $totalRows_listapost = mysql_num_rows($all_listapost);
}
$totalPages_listapost = ceil($totalRows_listapost/$maxRows_listapost)-1;

$queryString_listapost = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_listapost") == false && 
        stristr($param, "totalRows_listapost") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_listapost = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_listapost = sprintf("&totalRows_listapost=%d%s", $totalRows_listapost, $queryString_listapost);
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />
<title>Dashboard | Postagens</title>
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
<!--shadow-box-->
<script type="text/javascript" src="../plugins/shadowbox/shadowbox.js"></script>
<link href="../plugins/shadowbox/shadowbox.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript">  
Shadowbox.init({  
language: 'pt',  
player: ['img', 'html', 'swf', 'php'] 
});  
</script> 

<!--shadow-box-->

</head>
<body onLoad="initTime();">
<div class="wrap">
  <div id="header">
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
                    <li class="b1"><a class="icon add_page" href="Novo.Post"
                    rel="shadowbox;width=900;">Novo Post</a></li>
                    
                    <li class="b1"><a class="icon add_page" href="Novo.Projeto"
                    rel="shadowbox;width=900;">Novo Projeto</a></li>
                    
                    <li class="b1"><a class="icon add_page" href="Nova.Categoria.do.Blog" 
                    rel="shadowbox;width=900;height=200;">Nova Categoria do Blog</a></li>
                    
                    <li class="b1"><a class="icon add_page" href="Nova.Categoria.de.Projeto" 
                    rel="shadowbox;width=900;height=200;">Nova Categoria de Projeto</a></li>
                    
                  <li class="b1"><a class="icon add_page" href="Add.Imagem.ao.Projeto" 
                  rel="shadowbox;width=900;height=330;">Add Imagem ao Projeto</a></li>
                    
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
			<div class="full_w">
				<div class="h_title">Postagens</div>
			  <div class="entry">
				</div>
				
			    <table width="750">
				    <thead>
				      <tr>
				        <th width="424" scope="col">Título</th>
				        <th width="212" scope="col">Data / Hora</th>
                         <th width="212" scope="col"> Capa</th>
				        <th width="98" style="width: 65px;" scope="col">Ações</th>
				        </tr>
				      </thead>
				    
				    <tbody>
				      <?php do { ?>
			          <tr>
			            <td><?php echo $row_listapost['titulo']; ?></td>
			            <td align="center"><?php echo $row_listapost['data']; ?>  &agrave;s <?php echo $row_listapost['hora']; ?></td>
                        <td align="center"><a href="alt.capa.post?id=<?php echo $row_listapost['id']; ?>" rel="shadowbox;width=376;height=35;">Alterar Capa</a></td>
			            <td align="center">
			              <a href="Editar.post?id=<?php echo $row_listapost['id']; ?>" class="table-icon edit" title="" rel="shadowbox;width=900;"></a>
			              <a href="exc-post?id=<?php echo $row_listapost['id']; ?>" class="table-icon delete" title="Excluir"></a>
			              </td>
			            </tr>
				        <?php } while ($row_listapost = mysql_fetch_assoc($listapost)); ?>
                    </tbody>
			      </table>
				  
				<div class="entry">
					<div class="pagination">
                      <table border="0">
                        <tr>
                          <td><?php if ($pageNum_listapost > 0) { // Show if not first page ?>
                              <a href="<?php printf("%s?pageNum_listapost=%d%s", $currentPage, 0, $queryString_listapost); ?>">Primeiro</a>
                              <?php } // Show if not first page ?></td>
                          <td><?php if ($pageNum_listapost > 0) { // Show if not first page ?>
                              <a href="<?php printf("%s?pageNum_listapost=%d%s", $currentPage, max(0, $pageNum_listapost - 1), $queryString_listapost); ?>">Anterior</a>
                              <?php } // Show if not first page ?></td>
                          <td><?php if ($pageNum_listapost < $totalPages_listapost) { // Show if not last page ?>
                              <a href="<?php printf("%s?pageNum_listapost=%d%s", $currentPage, min($totalPages_listapost, $pageNum_listapost + 1), $queryString_listapost); ?>">Pr&oacute;ximo</a>
                              <?php } // Show if not last page ?></td>
                          <td><?php if ($pageNum_listapost < $totalPages_listapost) { // Show if not last page ?>
                              <a href="<?php printf("%s?pageNum_listapost=%d%s", $currentPage, $totalPages_listapost, $queryString_listapost); ?>">&Uacute;ltimo</a>
                              <?php } // Show if not last page ?></td>
                        </tr>
                      </table>
                    </div>
					<div class="sep"></div>		
					<a class="button add" href="Novo.Post">Nova Postagem</a> 
			  </div>
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

mysql_free_result($listapost);
?>
