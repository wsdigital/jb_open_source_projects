<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
</head>

<body>

<form action="senha.php" method="post">
<?php 
$senha = $_POST['insertsenha'];
?>
<input name="insertsenha" type="text" id="insertsenha" />
<input name="" type="submit" />
</form>

<?php echo sha1($senha); ?>
</body>
</html>