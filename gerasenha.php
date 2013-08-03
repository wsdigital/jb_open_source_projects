<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
</head>
<?php 
$senha = base64_encode($_POST['senha']);
$senhadec = base64_decode($_POST['decoder']);
?>

<body>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input name="senha" type="text" id="senha" />
<input name="" type="submit" />
</form>
<?php echo $senha ?><br />
<br />
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input name="decoder" type="text" id="decoder" />
<input name="" type="submit" />
</form>
<?php echo $senhadec ?>
</body>
</html>