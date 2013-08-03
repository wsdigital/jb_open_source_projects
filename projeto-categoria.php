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

$colname_catprojeto = "-1";
if (isset($_GET['cp'])) {
  $colname_catprojeto = $_GET['cp'];
}
mysql_select_db($database_conn, $conn);
$query_catprojeto = sprintf("SELECT * FROM projetos_menu WHERE id = %s", GetSQLValueString($colname_catprojeto, "int"));
$catprojeto = mysql_query($query_catprojeto, $conn) or die(mysql_error());
$row_catprojeto = mysql_fetch_assoc($catprojeto);
$maxRows_catprojeto = 15;
$pageNum_catprojeto = 0;
if (isset($_GET['pageNum_catprojeto'])) {
  $pageNum_catprojeto = $_GET['pageNum_catprojeto'];
}
$startRow_catprojeto = $pageNum_catprojeto * $maxRows_catprojeto;

$totalRows_catprojeto = "-1";
if (isset($_GET['cp'])) {
  $totalRows_catprojeto = $_GET['cp'];
}
$colname_catprojeto = "-1";
if (isset($_GET['cp'])) {
  $colname_catprojeto = $_GET['cp'];
}
mysql_select_db($database_conn, $conn);
$query_catprojeto = sprintf("SELECT * FROM projeto WHERE categoria = %s", GetSQLValueString($colname_catprojeto, "text"));
$query_limit_catprojeto = sprintf("%s LIMIT %d, %d", $query_catprojeto, $startRow_catprojeto, $maxRows_catprojeto);
$catprojeto = mysql_query($query_limit_catprojeto, $conn) or die(mysql_error());
$row_catprojeto = mysql_fetch_assoc($catprojeto);

if (isset($_GET['totalRows_catprojeto'])) {
  $totalRows_catprojeto = $_GET['totalRows_catprojeto'];
} else {
  $all_catprojeto = mysql_query($query_catprojeto);
  $totalRows_catprojeto = mysql_num_rows($all_catprojeto);
}
$totalPages_catprojeto = ceil($totalRows_catprojeto/$maxRows_catprojeto)-1;

$colname_menupertence = "-1";
if (isset($_GET['cp'])) {
  $colname_menupertence = $_GET['cp'];
}
mysql_select_db($database_conn, $conn);
$query_menupertence = sprintf("SELECT * FROM projetos_menu WHERE id = %s", GetSQLValueString($colname_menupertence, "int"));
$menupertence = mysql_query($query_menupertence, $conn) or die(mysql_error());
$row_menupertence = mysql_fetch_assoc($menupertence);
$totalRows_menupertence = mysql_num_rows($menupertence);

$queryString_catprojeto = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_catprojeto") == false && 
        stristr($param, "totalRows_catprojeto") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_catprojeto = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_catprojeto = sprintf("&totalRows_catprojeto=%d%s", $totalRows_catprojeto, $queryString_catprojeto);
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
			<li><a href="blog">Blog</a></li>
			<li><a href="projetos" class="current">Projetos</a></li>
			<li><a href="sobre">Sobre</a></li>
			<li><a href="./">Home</a></li>
		</ul>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>
			
		<!-- Catch Line and Link -->
			<h2 class="grid_12 caption clearfix">Todos nossos <span>Projetos</span> s&atilde;o Open Source. Voc&ecirc; pode baixar Gratuitamente!</h2>
		
		<div class="pr grid_12 clearfix">&nbsp;</div>

<!-- Categoria inicio -->
      <div class="catagory_1 clearfix">
			<!-- Row 1 -->
			<div class="grid_3 textright" >
				<span>Você está em:</span><br><br>
			  <h4 class="title"><?php echo $row_menupertence['titulo']; ?></h4>
				<div class="hr clearfix dotted">&nbsp;</div>
			</div>
			<div class="grid_9">
				
                
                <?php do { ?>
                <a class="projetos_item float alpha" href="projeto?p=<?php echo $row_catprojeto['id']; ?>">
                    <span><?php echo $row_catprojeto['titulo']; ?></span>
                    <img class="" src="jb-includes/uploads/images/projetos/<?php echo $row_catprojeto['capa']; ?>" width="223" height="112">
                </a>
                  <?php } while ($row_catprojeto = mysql_fetch_assoc($catprojeto)); ?>
                  
				
          </div>
		</div>
      <!-- categoria final-->

<!-- Categoria inicio -->
<div class="entry">
<div class="pagination">
<table border="0">
  <tr>
    <td><?php if ($pageNum_catprojeto > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_catprojeto=%d%s", $currentPage, 0, $queryString_catprojeto); ?>" class="pagination">Primeiro</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_catprojeto > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_catprojeto=%d%s", $currentPage, max(0, $pageNum_catprojeto - 1), $queryString_catprojeto); ?>">Anterior</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_catprojeto < $totalPages_catprojeto) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_catprojeto=%d%s", $currentPage, min($totalPages_catprojeto, $pageNum_catprojeto + 1), $queryString_catprojeto); ?>">Pr&oacute;ximo</a>
      <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_catprojeto < $totalPages_catprojeto) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_catprojeto=%d%s", $currentPage, $totalPages_catprojeto, $queryString_catprojeto); ?>">&Uacute;ltimo</a>
      <?php } // Show if not last page ?></td>
  </tr>
</table>
</div>
</div>

<div class="pr clearfix grid_12">&nbsp;</div>
        
<!-- categoria final-->
        
	  <!-- Footer -->
		
<p class="grid_12 footer clearfix">
			<span class="float"><b>&copy; Copyright</b> 2012-<?php echo date('Y') ?> <a href="">JamBull</a> </span>
            <span class="float right">Baseado no tema <a href="http://qwibbledesigns.co.uk" target="_blank"><strong>Aurelius</strong></a></span>
			
		</p>

		
	</div><!--end wrapper-->

</body>
</html>
<?php
mysql_free_result($catprojeto);

mysql_free_result($menupertence);
?>
