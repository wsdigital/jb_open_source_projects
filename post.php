<?php require_once('Connections/conn.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "comment_form")) {
  $insertSQL = sprintf("INSERT INTO comentario (id, idpost, nome, email, comentario, `data`, hora, autorpost) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['idpost'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['message'], "text"),
                       GetSQLValueString($_POST['data'], "text"),
                       GetSQLValueString($_POST['hora'], "text"),
                       GetSQLValueString($_POST['autorpost'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "#comentarios";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_lepost = "-1";
if (isset($_GET['id'])) {
  $colname_lepost = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_lepost = sprintf("SELECT * FROM blog_post WHERE id = %s", GetSQLValueString($colname_lepost, "int"));
$lepost = mysql_query($query_lepost, $conn) or die(mysql_error());
$row_lepost = mysql_fetch_assoc($lepost);
$totalRows_lepost = mysql_num_rows($lepost);

mysql_select_db($database_conn, $conn);
$query_ctblog = "SELECT * FROM blog_menu ORDER BY titulo ASC";
$ctblog = mysql_query($query_ctblog, $conn) or die(mysql_error());
$row_ctblog = mysql_fetch_assoc($ctblog);
$totalRows_ctblog = mysql_num_rows($ctblog);

$maxRows_comentarios = 15;
$pageNum_comentarios = 0;
if (isset($_GET['pageNum_comentarios'])) {
  $pageNum_comentarios = $_GET['pageNum_comentarios'];
}
$startRow_comentarios = $pageNum_comentarios * $maxRows_comentarios;

$colname_comentarios = "-1";
if (isset($_GET['id'])) {
  $colname_comentarios = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_comentarios = sprintf("SELECT * FROM comentario WHERE idpost = %s ORDER BY `data` DESC", GetSQLValueString($colname_comentarios, "text"));
$query_limit_comentarios = sprintf("%s LIMIT %d, %d", $query_comentarios, $startRow_comentarios, $maxRows_comentarios);
$comentarios = mysql_query($query_limit_comentarios, $conn) or die(mysql_error());
$row_comentarios = mysql_fetch_assoc($comentarios);

if (isset($_GET['totalRows_comentarios'])) {
  $totalRows_comentarios = $_GET['totalRows_comentarios'];
} else {
  $all_comentarios = mysql_query($query_comentarios);
  $totalRows_comentarios = mysql_num_rows($all_comentarios);
}
$totalPages_comentarios = ceil($totalRows_comentarios/$maxRows_comentarios)-1;

$queryString_comentarios = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_comentarios") == false && 
        stristr($param, "totalRows_comentarios") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_comentarios = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_comentarios = sprintf("&totalRows_comentarios=%d%s", $totalRows_comentarios, $queryString_comentarios);
?>
<?php date_default_timezone_set ("America/Sao_Paulo") ;?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>JamBull | Open Source Projects</title>
	<meta charset="iso-8859-1"/>
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="tema/classic/css/reset.css" />
	<link rel="stylesheet" href="tema/classic/css/styles.css" />

	<!-- Scripts -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="plugins/tiny_mce/jscripts/tiny_mce.js"></script>
	<script type="text/javascript">
    tinyMCE.init({
            // General options
        language : "pt",
            mode : "textareas",
            theme : "advanced",
            width : "425px",
            
    
            // Theme options
			theme_advanced_buttons1:
			"bold,italic,underline,strikethrough,justifyleft,forecolor,backcolor",
    
            // Theme options
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_buttons4 : "",
            
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            
            theme_advanced_resizing : true,
    
            
            // Replace values for the template plugin
            template_replace_values : {
                username : "Some User",
                staffid : "991234"
            }
        });
    
    </script>
	<!--[if IE 6]>
	<script src="js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script>
	  /* EXAMPLE */
	  DD_belatedPNG.fix('.button');
	  
	  /* string argument can be any CSS selector */
	  /* .png_bg example is unnecessary */
	  /* change it to what suits you! */
	</script>
	<![endif]-->
	
</head>

<body>

	<div id="wrapper" class="container_12 clearfix">

		<!-- Text Logo -->
		<h1 id="logo" class="grid_4">JamBull<span>Open Source Projects</span></h1>
		
		<!-- Navigation Menu -->
		<ul id="navigation" class="grid_8">
			<li><a href="contato">Contato</a></li>
			<li><a href="blog" class="current">Blog</a></li>
			<li><a href="projetos">Projetos</a></li>
			<li><a href="sobre">Sobre</a></li>
			<li><a href="./">Home</a></li>
		</ul>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>
			
		<!-- Caption Line --><!-- Column 1 /Content -->
		<div class="grid_8">
			
			<!-- Blog Post -->
			<div class="post">
				<!-- Post Title -->
			  <h3 class="title"><a><?php echo $row_lepost['titulo']; ?></a></h3>
				<!-- Post Title -->
			  <p class="sub">Por: <strong><?php echo $row_lepost['autor']; ?></strong> em <?php echo $row_lepost['data']; ?> às <?php echo $row_lepost['hora']; ?> &bull; <a href="#comentarios"> <?php echo $totalRows_comentarios ?>  Comentario(s)</a></p>
				<div class="hr dotted clearfix">&nbsp;</div>
				<!-- Post Title -->
				<img class="thumb" src="jb-includes/uploads/images/post/<?php echo $row_lepost['capa']; ?>" alt=""/>
				<!-- Post Content -->
			  <p>
              <?php echo $row_lepost['corpo']; ?>
              </p> 
				<!-- Post Links -->
				<p class="clearfix">
					<a href="blog" class="button float" >&lt;&lt; Voltar ao Blog</a>
				</p>
			</div>
			<div class="hr clearfix">&nbsp;</div>
			
			<!-- Comment's List -->
			<div id="comentarios">
            <h3>Comentários</h3>
			<div class="hr dotted clearfix">&nbsp;</div>
			
			<ol class="commentlist">
		<!--começa os comentarios-->
      			    <?php do { ?>
   			        <li class="comment">
      			        
      			        <div class="comment_content"> 
      			          <div class="clearfix">
      			            <cite class="author_name"><?php echo $row_comentarios['nome']; ?></cite>       
      			            <div class="comment-meta commentmetadata"><?php echo $row_comentarios['data']; ?> <?php echo $row_comentarios['hora']; ?></div> 
   			              </div>
      			          <div class="comment_text"> 
      			            <p><?php echo $row_comentarios['comentario']; ?></p> 
   			              </div>
      			          
   			            </div>
      			        <div class="hr dotted clearfix">&nbsp;</div> 
      			        </li>
      			      <?php } while ($row_comentarios = mysql_fetch_assoc($comentarios)); ?>
                      <div class="entry">
                      <div class="pagination">
                        <table border="0">
                          <tr>
                            <td><?php if ($pageNum_comentarios > 0) { // Show if not first page ?>
                                <a href="<?php printf("%s?pageNum_comentarios=%d%s", $currentPage, 0, $queryString_comentarios); ?>">Primeiro</a>
                                <?php } // Show if not first page ?></td>
                            <td><?php if ($pageNum_comentarios > 0) { // Show if not first page ?>
                                <a href="<?php printf("%s?pageNum_comentarios=%d%s", $currentPage, max(0, $pageNum_comentarios - 1), $queryString_comentarios); ?>">Anterior</a>
                                <?php } // Show if not first page ?></td>
                            <td><?php if ($pageNum_comentarios < $totalPages_comentarios) { // Show if not last page ?>
                                <a href="<?php printf("%s?pageNum_comentarios=%d%s", $currentPage, min($totalPages_comentarios, $pageNum_comentarios + 1), $queryString_comentarios); ?>">Pr&oacute;ximo</a>
                                <?php } // Show if not last page ?></td>
                            <td><?php if ($pageNum_comentarios < $totalPages_comentarios) { // Show if not last page ?>
                                <a href="<?php printf("%s?pageNum_comentarios=%d%s", $currentPage, $totalPages_comentarios, $queryString_comentarios); ?>">&Uacute;ltimo</a>
                                <?php } // Show if not last page ?></td>
                          </tr>
                        </table>
                      </div>
                      </div>
<!--termina os comentarios-->
                
			</ol> 
			</div>
			<div class="hr clearfix">&nbsp;</div>
            
			
			<!-- Comment Form -->
			<form action="<?php echo $editFormAction; ?>" name="comment_form" id="comment_form" method="POST"> 
				<h3>Comentar</h3>
				<div class="hr dotted clearfix">&nbsp;</div>
				<ul>
					<input name="id" type="hidden" id="id">
                    <input name="idpost" type="hidden" id="idpost" value="<?php echo $row_lepost['id']; ?>">
                    <input name="data" type="hidden" id="data" value="<?php echo date('d/m/Y') ?> às">
                    <input name="hora" type="hidden" id="hora" value="<?php echo date('H:i') ?>">
                    <input name="autorpost" type="hidden" id="autorpost" value="<?php echo $row_lepost['username']; ?>">
                  <li class="clearfix">
					<label for="name">Nome</label> 
					  <input id="name" name="name" type="text" class="input" required /> 
					</li> 
					<li class="clearfix"> 
						<label for="email">E-mail</label> 
					  <input id="email" name="email" type="text" class="input" required /> 
					</li>
					<li class="clearfix"> 
						<label for="message">Comentário</label> 
					  <textarea id="message" name="message" class="right" rows="3" cols="30"></textarea> 
					</li> 
					<li class="clearfix">
						<!-- Add Comment Button -->
						<input name="enviar" type="submit" value="Comentar" class="button right" >
                        
					</li> 
				</ul>
				<input type="hidden" name="MM_insert" value="comment_form">
			</form> 
		</div>
	
		<!-- Column 2 / Sidebar -->
		<div class="grid_4">
		
			<h4>Categorias</h4>
			<ul class="sidebar">
			    <?php do { ?>
		        <li><a href="categoria?cat=<?php echo $row_ctblog['id']; ?>"><?php echo $row_ctblog['titulo']; ?></a></li>
			      <?php } while ($row_ctblog = mysql_fetch_assoc($ctblog)); ?>
			</ul>
		
		</div>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>
		
		<!-- Footer -->
		
<p class="grid_12 footer clearfix">
			<span class="float"><b>&copy; Copyright</b> 2012-<?php echo date('Y') ?> <a href="">JamBull</a> </span>
            <span class="float right">Baseado no tema <a href="http://qwibbledesigns.co.uk" target="_blank"><strong>Aurelius</strong></a></span>
			
		</p>

	</div><!--end wrapper-->

</body>
</html>
<?php
mysql_free_result($lepost);

mysql_free_result($ctblog);

mysql_free_result($comentarios);
?>
