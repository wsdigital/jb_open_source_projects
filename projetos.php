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

$maxRows_utlprojecad = 9;
$pageNum_utlprojecad = 0;
if (isset($_GET['pageNum_utlprojecad'])) {
  $pageNum_utlprojecad = $_GET['pageNum_utlprojecad'];
}
$startRow_utlprojecad = $pageNum_utlprojecad * $maxRows_utlprojecad;

mysql_select_db($database_conn, $conn);
$query_utlprojecad = "SELECT * FROM projeto ORDER BY id DESC";
$query_limit_utlprojecad = sprintf("%s LIMIT %d, %d", $query_utlprojecad, $startRow_utlprojecad, $maxRows_utlprojecad);
$utlprojecad = mysql_query($query_limit_utlprojecad, $conn) or die(mysql_error());
$row_utlprojecad = mysql_fetch_assoc($utlprojecad);

if (isset($_GET['totalRows_utlprojecad'])) {
  $totalRows_utlprojecad = $_GET['totalRows_utlprojecad'];
} else {
  $all_utlprojecad = mysql_query($query_utlprojecad);
  $totalRows_utlprojecad = mysql_num_rows($all_utlprojecad);
}
$totalPages_utlprojecad = ceil($totalRows_utlprojecad/$maxRows_utlprojecad)-1;

mysql_select_db($database_conn, $conn);
$query_catprojeto = "SELECT * FROM projetos_menu ORDER BY titulo ASC";
$catprojeto = mysql_query($query_catprojeto, $conn) or die(mysql_error());
$row_catprojeto = mysql_fetch_assoc($catprojeto);
$totalRows_catprojeto = mysql_num_rows($catprojeto);
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

<body class="projetos">

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
            <h3>Categorias</h3>
				
                
                <?php do { ?>
                <span class="meta"><a href="projeto-categoria?cp=<?php echo $row_catprojeto['id']; ?>"><?php echo $row_catprojeto['titulo']; ?></a></span><br>
                  <?php } while ($row_catprojeto = mysql_fetch_assoc($catprojeto)); ?>
                  
             
        </div>
			
        <div class="grid_9">
          <h3>Últimos Sistemas</h3>
				
            
              <?php do { ?>
              <a class="projetos_item float alpha" href="projeto?p=<?php echo $row_utlprojecad['id']; ?>"> <span><?php echo $row_utlprojecad['titulo']; ?></span>
                <img class="" src="jb-includes/uploads/images/projetos/<?php echo $row_utlprojecad['capa']; ?>"  alt=""/> </a>
                <?php } while ($row_utlprojecad = mysql_fetch_assoc($utlprojecad)); ?>
              
				
	      </div>
		</div>
      <!-- categoria final-->

<!-- Categoria inicio -->
        <div class="catagory_1 clearfix">
			<!-- Row 1 -->
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
mysql_free_result($utlprojecad);

mysql_free_result($catprojeto);
?>
