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
	<script type="text/javascript"  src="js/contact.js"></script>
    <script type="text/javascript" src="plugins/tiny_mce/jscripts/tiny_mce.js"></script>
	<script type="text/javascript">
    tinyMCE.init({
            // General options
        language : "pt",
            mode : "textareas",
            theme : "advanced",
            width: "425px" ,
    
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
			<li><a href="contato" class="current">Contato</a></li>
			<li><a href="blog">Blog</a></li>
			<li><a href="projetos">Projetos</a></li>
			<li><a href="sobre">Sobre</a></li>
			<li><a href="./">Home</a></li>
		</ul>
			
		<div class="hr grid_12 clearfix">&nbsp;</div>
			
		<!-- Caption Line -->
		<h2 class="grid_12 caption clearfix">D&uacute;vidas, Sugest&otilde;es ou Reclama&ccedil;&otilde;es? Entre em Contato pelo formul&aacute;rio abaixo!</h2>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>

		<!-- Column 1 / Content -->
		<div class="grid_8">
			<form action='./' method='post' id='contact_form'>
			<h3>Formulário de Contato</h3>
				<div class="hr dotted clearfix">&nbsp;</div>
				<ul>						
					<li class="clearfix"> 
						<label for="name">Nome</label>
						<input type='text' name='name' id='name' />
						<div class="clear"></div>
						<p id='name_error' class='error'>Informe seu nome</p>
					</li> 
					<li class="clearfix"> 
						<label for="email">E-mail</label>
						<input type='text' name='email' id='email' />
						<div class="clear"></div>
						<p id='email_error' class='error'>Informe seu E-mail</p>
					</li> 
					<li class="clearfix"> 
						<label for="subject">Assunto</label>
						<input type='text' name='subject' id='subject' />
						<div class="clear"></div>
						<p id='subject_error' class='error'>Insira um assunto. Ex.: Sugestão</p>
					</li> 
					<li class="clearfix"> 
						<label for="message">Mensagem</label>
						<textarea name='message' id='message' rows="30" cols="30"></textarea>
						<div class="clear"></div>
						<p id='message_error' class='error'>Insira sua mensagem</p>
					</li> 
					<li class="clearfix"> 
						
					<p id='mail_success' class='success'>Obrigado por entrar em contato. Logo retornaremos</p>
					<p id='mail_fail' class='error'>Desculpe Houve um erro ao enviar. Por Favor tente novamente em alguns instantes.</p>
					<div id="button">
					<input type='submit' id='send_message' class="button" value='Enviar' />
					</div>
					</li> 
				</ul> 
			</form>  
		</div>
		
		<!-- Column 2 / Sidebar -->
		<div class="grid_4 contact">
		
			<!-- Adress and Phone Details -->
			<!--<h4>Endereço</h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li> 
					<strong>Your Company Name</strong><br /> 
					1458 Sample Road, Redvalley<br /> 
					City (State) H4Q 1J7<br /> 
					Country<br /><br /> 
				</li> 
				<li>USA - (888) 888-8888</li> 
				<li>Worldwide - (888) 888-8888</li> 
			</ul> 
-->			
			<!-- Email Addresses -->
			<h4>E-mails</h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li>Contato - <a href="mailto:gcdesign@kmaill.com.br">gcdesign@kmaill.com.br</a></li> 
				<li>Desenvolvimento - <a href="mailto:developer@kmaill.com.br">developer@kmaill.com.br</a></li> 
			</ul> 
			
			<!-- Social Profile Links -->
			<h4>Redes Sociais</h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li class="float"><a href="https://twitter.com/JB_OpenSorce" target="_blank"><img alt="" src="images/twitter.png" title="Twitter" /></a></li> 
				<li class="float"><a href="http://facebook.com/estudioito" target="_blank"><img alt="" src="images/facebook.png" title="Facebook" /></a></li>
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