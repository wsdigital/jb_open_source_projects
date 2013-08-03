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

$colname_leprojeto = "-1";
if (isset($_GET['p'])) {
  $colname_leprojeto = $_GET['p'];
}
mysql_select_db($database_conn, $conn);
$query_leprojeto = sprintf("SELECT * FROM projeto WHERE id = %s", GetSQLValueString($colname_leprojeto, "int"));
$leprojeto = mysql_query($query_leprojeto, $conn) or die(mysql_error());
$row_leprojeto = mysql_fetch_assoc($leprojeto);
$totalRows_leprojeto = "-1";
if (isset($_GET['p'])) {
  $totalRows_leprojeto = $_GET['p'];
}
$colname_leprojeto = "-1";
if (isset($_GET['p'])) {
  $colname_leprojeto = $_GET['p'] ;
}
mysql_select_db($database_conn, $conn);
$query_leprojeto = sprintf("SELECT * FROM projeto WHERE id = %s", GetSQLValueString($colname_leprojeto, "int"));
$leprojeto = mysql_query($query_leprojeto, $conn) or die(mysql_error());
$row_leprojeto = mysql_fetch_assoc($leprojeto);
$totalRows_leprojeto = mysql_num_rows($leprojeto);

$colname_imgprojeto = "-1";
if (isset($_GET['p'])) {
  $colname_imgprojeto = $_GET['p'];
}
mysql_select_db($database_conn, $conn);
$query_imgprojeto = sprintf("SELECT * FROM projeto_imagem WHERE id_projeto = %s", GetSQLValueString($colname_imgprojeto, "text"));
$imgprojeto = mysql_query($query_imgprojeto, $conn) or die(mysql_error());
$row_imgprojeto = mysql_fetch_assoc($imgprojeto);
$totalRows_imgprojeto = mysql_num_rows($imgprojeto);
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
	<script type="text/javascript" src="js/jquery.roundabout-1.0.min.js"></script> 
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="js/jquery.roundabout-shapes-1.1.js"></script>
	<script type="text/javascript">			
		$(document).ready(function() { //Start up our Project Preview Carosuel
			$('ul#folio_scroller').roundabout({
				easing: 'easeOutInCirc',
				shape: 'waterWheel',
				duration: 600
			});
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
			<li><a href="blog">Blog</a></li>
			<li><a href="projetos" class="current">Projetos</a></li>
			<li><a href="sobre">Sobre</a></li>
			<li><a href="./">Home</a></li>
		</ul>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>
			
		<!-- Catch Line and Link -->
		<h2 class="grid_12 caption clearfix">Todos nossos <span>Projetos</span> s&atilde;o Open Source. Voc&ecirc; pode baixar Gratuitamente!</h2>
		<div class="hr grid_12 clearfix">&nbsp;</div>
		
		<!-- Column 1 / Project Information -->
		<div class="grid_4">
        
<h4 class="title"><?php echo $row_leprojeto['titulo']; ?></h4>

		<div class="hr dotted clearfix">&nbsp;</div>
			<p>
            <?php echo $row_leprojeto['descricao']; ?>
             </p>	
			<p class="clearfix">
				<a href="<?php echo $row_leprojeto['demo']; ?>" class="button float">Demonstação</a>
				<a href="<?php echo $row_leprojeto['download']; ?>" class="button float right">Download</a>
			</p>
		</div>
		
		<!-- Column 2 / Image Carosuel -->
		<div id="folio_scroller_container" class="grid_8 cleafix">
			<ul id="folio_scroller"> 
					
				    <?php do { ?>
			        <li><span><?php echo $row_imgprojeto['titulo_img']; ?></span>
				        <img src="jb-includes/uploads/images/projetos/<?php echo $row_imgprojeto['url']; ?>" width="600" height="300"></li>
				      <?php } while ($row_imgprojeto = mysql_fetch_assoc($imgprojeto)); ?>
					   
					
			</ul> 
		</div>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>
		
		<!-- Client pickup line --><!-- Footer -->
		<p class="grid_12 footer clearfix">
			<span class="float"><b>&copy; Copyright</b> 2012-<?php echo date('Y') ?> <a href="">JamBull</a> </span>
            <span class="float right">Baseado no tema <a href="http://qwibbledesigns.co.uk" target="_blank"><strong>Aurelius</strong></a></span>
			
		</p>
		
	</div><!--end wrapper-->

</body>
</html>
<?php
mysql_free_result($leprojeto);

mysql_free_result($imgprojeto);
?>
