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

mysql_select_db($database_conn, $conn);
$query_ctblog = "SELECT * FROM blog_menu ORDER BY titulo ASC";
$ctblog = mysql_query($query_ctblog, $conn) or die(mysql_error());
$row_ctblog = mysql_fetch_assoc($ctblog);
$totalRows_ctblog = mysql_num_rows($ctblog);

$maxRows_ultpostiicio = 3;
$pageNum_ultpostiicio = 0;
if (isset($_GET['pageNum_ultpostiicio'])) {
  $pageNum_ultpostiicio = $_GET['pageNum_ultpostiicio'];
}
$startRow_ultpostiicio = $pageNum_ultpostiicio * $maxRows_ultpostiicio;

mysql_select_db($database_conn, $conn);
$query_ultpostiicio = "SELECT * FROM blog_post ORDER BY id DESC";
$query_limit_ultpostiicio = sprintf("%s LIMIT %d, %d", $query_ultpostiicio, $startRow_ultpostiicio, $maxRows_ultpostiicio);
$ultpostiicio = mysql_query($query_limit_ultpostiicio, $conn) or die(mysql_error());
$row_ultpostiicio = mysql_fetch_assoc($ultpostiicio);

if (isset($_GET['totalRows_ultpostiicio'])) {
  $totalRows_ultpostiicio = $_GET['totalRows_ultpostiicio'];
} else {
  $all_ultpostiicio = mysql_query($query_ultpostiicio);
  $totalRows_ultpostiicio = mysql_num_rows($all_ultpostiicio);
}
$totalPages_ultpostiicio = ceil($totalRows_ultpostiicio/$maxRows_ultpostiicio)-1;
?>
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
			
		<!-- Caption Line -->
	
		
		<!-- Column 1 /Content -->
		<div class="grid_8">
			
			<!-- Blog Post -->
		    <?php do { ?>
	        <div class="post">
		        <!-- Post Title -->
		        <h3 class="title"><a><?php echo $row_ultpostiicio['titulo']; ?></a></h3>
		        <!-- Post Data -->
		        <p class="sub">Por: <strong><?php echo $row_ultpostiicio['autor']; ?></strong> &nbsp;&nbsp; em&nbsp;&nbsp; <?php echo $row_ultpostiicio['data']; ?> às <?php echo $row_ultpostiicio['hora']; ?> </p>
		        <div class="hr dotted clearfix">&nbsp;</div>
		        <!-- Post Image -->
		        <img src="jb-includes/uploads/images/post/<?php echo $row_ultpostiicio['capa']; ?>" class="thumb">
		        <!-- Post Content -->
		        <p><?php echo $row_ultpostiicio['min_desc']; ?></p>
		        <!-- Read More Button -->
		        <p class="clearfix"><a href="post?id=<?php echo $row_ultpostiicio['id']; ?>" class="button right">Continue lendo...</a></p>
		        </div>
		      <?php } while ($row_ultpostiicio = mysql_fetch_assoc($ultpostiicio)); ?>
			  <div class="hr clearfix">&nbsp;</div>
            
            
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
mysql_free_result($ctblog);

mysql_free_result($ultpostiicio);
?>
