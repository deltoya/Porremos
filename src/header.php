<?
require_once("inc/UsuarioDAO.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Bookish
Version    : 1.0
Released   : 20080425

-->
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>Porra mundial 2010 Matchmind</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="/css/default.css" />
<link rel="stylesheet" type="text/css" href="/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.js"></script>
<script type="text/javascript" src="/js/tooltip.jquery.js"></script>
</head>
<body>

<div id="outer">
	<div id="header">
		<h1><span>Porra</span><strong>Mundial</strong>2010</h1>
		<div id="menu">
			<ul>
				<li class="first"><a href="/index.php">resultados</a></li>
				<li><a href="/preguntas.php">preguntas</a></li>
				<li><a href="/espia.php">min. esp&iacute;a</a></li>
				<li><a href="/clasifica.php">clasificaci&oacute;n</a></li>
				<li><a href="/normas.php">normas</a></li>
				<li><a href="/usuario.php">usuario</a></li>
<? 
	$usuarios = new UsuarioDAO();
	if ($usuarios->getCurrent() && $usuarios->getCurrent()->isAdmin()) {
?>
				<li><a href="/admin/administracion.php">admin</a></li>
<?
	}
?>
			</ul>
		</div>
	</div>

