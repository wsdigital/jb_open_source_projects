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

$maxRows_ultprojet = 4;
$pageNum_ultprojet = 0;
if (isset($_GET['pageNum_ultprojet'])) {
  $pageNum_ultprojet = $_GET['pageNum_ultprojet'];
}
$startRow_ultprojet = $pageNum_ultprojet * $maxRows_ultprojet;

mysql_select_db($database_conn, $conn);
$query_ultprojet = "SELECT * FROM projeto ORDER BY id DESC";
$query_limit_ultprojet = sprintf("%s LIMIT %d, %d", $query_ultprojet, $startRow_ultprojet, $maxRows_ultprojet);
$ultprojet = mysql_query($query_limit_ultprojet, $conn) or die(mysql_error());
$row_ultprojet = mysql_fetch_assoc($ultprojet);

if (isset($_GET['totalRows_ultprojet'])) {
  $totalRows_ultprojet = $_GET['totalRows_ultprojet'];
} else {
  $all_ultprojet = mysql_query($query_ultprojet);
  $totalRows_ultprojet = mysql_num_rows($all_ultprojet);
}
$totalPages_ultprojet = ceil($totalRows_ultprojet/$maxRows_ultprojet)-1;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>JamBull | Open Source Projects</title>
	<meta charset="iso-8859-1"/>
	<meta name="description" content="">
	<meta name="keywords" content="">
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="tema/classic/css/reset.css" />
	<link rel="stylesheet" href="tema/classic/css/styles.css" />
	
	<!-- Scripts -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.roundabout-1.0.min.js"></script> 
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	<script type="text/javascript">		
		$(document).ready(function() { //Start up our Featured Project Carosuel
			$('#featured ul').roundabout({
				easing: 'easeOutInCirc',
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
			<li><a href="projetos">Projetos</a></li>
				
			<li><a href="sobre">Sobre</a></li>
			<li><a href="./" class="current">Home</a></li>
		</ul>
		
		<div class="hr grid_12">&nbsp;</div>
		<div class="clear"></div>
		
		<!-- Featured Image Slider -->
		<div id="featured" class="clearfix grid_12">
			<ul> 
			    <?php do { ?>
		        <li>
			        <a href="projeto?p=<?php echo $row_ultprojet['id']; ?>">
			          <span><?php echo $row_ultprojet['titulo']; ?></span>
			          <img src="jb-includes/uploads/images/projetos/<?php echo $row_ultprojet['capa']; ?>" width="600" height="300">
		            </a>
		        </li>
			      <?php } while ($row_ultprojet = mysql_fetch_assoc($ultprojet)); ?>
				  
          </ul> 
		</div>
		<div class="hr grid_12 clearfix">&nbsp;</div>
			
		<!-- Caption Line -->
		<h2 class="grid_12 caption clearfix">Bem Vindo! Você está em <span>JamBull.com.br</span>, aqui você encontra diversos projetos <br>
	    <u>Open Source</u> inclusive templates e sites dinâmicos prontos, Al&eacute;m de poder publicar seus pr&oacute;prios projetos no site.</h2>
		
		<div class="hr grid_12 clearfix quicknavhr">&nbsp;</div>
		<div id="quicknav" class="grid_12">
			<a class="quicknavgrid_3 quicknav alpha" href="jb-user/Home">
					<h4 id="titulospan">Cadastre seu Projeto</h4>
					<br>
			<p style="text-align:center;"><img alt="" src="images/Art_Artdesigner.lv.png" /></p>
				
			</a>
			<a class="quicknavgrid_3 quicknav" href="sobre">
					<h4 id="titulospan">Quem Somos</h4>
					<br>
					<p style="text-align:center;"><img alt="" src="images/info.png" /></p>
				
			</a>
			<a class="quicknavgrid_3 quicknav" href="blog">
					<h4 id="titulospan">Leia nosso blog</h4>
					<br>
					<p style="text-align:center;"><img alt="" src="images/Blog_Artdesigner.lv.png" /></p>
				
			</a>
			<a class="quicknavgrid_3 quicknav" href="https://twitter.com/JB_OpenSorce" target="_blank">
					<h4 id="titulospan">Siga no Twitter</h4>
					<br>
					<p style="text-align:center;"><img alt="" src="images/hungry_bird.png" /></p>
			</a>
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
mysql_free_result($ultprojet);
?>
