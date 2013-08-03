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

mysql_select_db($database_conn, $conn);
$query_ctblog = "SELECT * FROM blog_menu ORDER BY titulo ASC";
$ctblog = mysql_query($query_ctblog, $conn) or die(mysql_error());
$row_ctblog = mysql_fetch_assoc($ctblog);
$totalRows_ctblog = mysql_num_rows($ctblog);

$maxRows_lepost = 3;
$pageNum_lepost = 0;
if (isset($_GET['pageNum_lepost'])) {
  $pageNum_lepost = $_GET['pageNum_lepost'];
}
$startRow_lepost = $pageNum_lepost * $maxRows_lepost;

$colname_lepost = "-1";
if (isset($_GET['cat'])) {
  $colname_lepost = $_GET['cat'];
}
mysql_select_db($database_conn, $conn);
$query_lepost = sprintf("SELECT * FROM blog_post WHERE menu = %s ORDER BY id DESC", GetSQLValueString($colname_lepost, "text"));
$query_limit_lepost = sprintf("%s LIMIT %d, %d", $query_lepost, $startRow_lepost, $maxRows_lepost);
$lepost = mysql_query($query_limit_lepost, $conn) or die(mysql_error());
$row_lepost = mysql_fetch_assoc($lepost);

if (isset($_GET['totalRows_lepost'])) {
  $totalRows_lepost = $_GET['totalRows_lepost'];
} else {
  $all_lepost = mysql_query($query_lepost);
  $totalRows_lepost = mysql_num_rows($all_lepost);
}
$totalPages_lepost = ceil($totalRows_lepost/$maxRows_lepost)-1;

$queryString_lepost = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_lepost") == false && 
        stristr($param, "totalRows_lepost") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_lepost = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_lepost = sprintf("&totalRows_lepost=%d%s", $totalRows_lepost, $queryString_lepost);
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
		        <h3 class="title"><a><?php echo $row_lepost['titulo']; ?></a></h3>
		        <!-- Post Data -->
		        <p class="sub">Por: <strong><?php echo $row_lepost['autor']; ?></strong> em <?php echo $row_lepost['data']; ?> às  <?php echo $row_lepost['hora']; ?></p>
		        <div class="hr dotted clearfix">&nbsp;</div>
		        <!-- Post Image -->
		        <img src="jb-includes/uploads/images/post/<?php echo $row_lepost['capa']; ?>" width="610" height="150" class="thumb">
		        <!-- Post Content -->
		        <p> <?php echo $row_lepost['min_desc']; ?></p>
		        <!-- Read More Button -->
		        <p class="clearfix"><a href="post?id=<?php echo $row_lepost['id']; ?>" class="button right">Continue lendo...</a></p>
	          </div>
		      <?php } while ($row_lepost = mysql_fetch_assoc($lepost)); ?>
			  <div class="hr clearfix">&nbsp;</div>
			 

			<!-- Blog Post --><!-- Blog Post --><!-- Blog Navigation -->
			<div class="entry">
            <div class="pagination">
              <table border="0">
                <tr>
                  <td><?php if ($pageNum_lepost > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_lepost=%d%s", $currentPage, 0, $queryString_lepost); ?>">Primeiro</a>
                      <?php } // Show if not first page ?></td>
                  <td><?php if ($pageNum_lepost > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_lepost=%d%s", $currentPage, max(0, $pageNum_lepost - 1), $queryString_lepost); ?>">Anterior</a>
                      <?php } // Show if not first page ?></td>
                  <td><?php if ($pageNum_lepost < $totalPages_lepost) { // Show if not last page ?>
                      <a href="<?php printf("%s?pageNum_lepost=%d%s", $currentPage, min($totalPages_lepost, $pageNum_lepost + 1), $queryString_lepost); ?>">Pr&oacute;ximo</a>
                      <?php } // Show if not last page ?></td>
                  <td><?php if ($pageNum_lepost < $totalPages_lepost) { // Show if not last page ?>
                      <a href="<?php printf("%s?pageNum_lepost=%d%s", $currentPage, $totalPages_lepost, $queryString_lepost); ?>">&Uacute;ltimo</a>
                      <?php } // Show if not last page ?></td>
                </tr>
              </table>
            </div>
            </div>
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

mysql_free_result($lepost);
?>
