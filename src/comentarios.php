<?
require_once("inc/Security.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/ComentarioDAO.inc");

include ("header.php");

$indice = isset($_REQUEST['indice']) ? intval($_REQUEST['indice']) : 0 ;
?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
				<h2><strong>Comentarios</strong></h2>
				<div class="content">
					<img src="images/zakumi.png" class="cpic right" alt="" />
					<p><strong>Aqu&iacute; pod&eacute;is</strong> comentar todo lo que os apetezca. La eurocopa, la paliza que le v&aacute;is a pegar a todos los dem&aacute;s, cu&aacute;ndo volver&aacute; Ra&uacute;l a la selecci&oacute;n (Claro que si habl&aacute;is mal de Ra&uacute;l se os restar&aacute;n 20 puntos por malditos infieles). Tambi&eacute;n pod&eacute;is quejaros, llorar amargamente y lamentar no haberos le&iacute;dos las reglas a tiempo. Para el caso que os vamos a hacer....</p>
					<p style="position:relative;right:20px"><input class="btn" type="button" onclick="document.location.href='comentar.php'" value="hablar"></p>
					<br/>
					<br/>
				</div>
<?
$comentarios = new ComentarioDAO();
$usuarios = new UsuarioDAO();
$lista = $comentarios->getListadoRepos($indice);
foreach ($lista as $comentario) {
  $nick = $usuarios->getById($comentario->getIdUsuario())->getNick();
?>
				<h4 class="date"><?= $comentario->getFecha()->format('d.m.Y H:i:s'); ?></h4>
                                <h3><strong><?= $nick ?>:</strong></h3>
				<div class="content">
					<p><?= $comentario->getTexto() ?></p>
				</div>
<?
}
?>	
	
				<!-- Main End -->
				<p style="position:relative;right:20px"><?
if (count($lista) >= 10) { ?>
<input class="btn" type="button" onclick="siguiente()" value="siguiente">
<?
}
if ($indice > 0) { ?>
<input class="btn" type="button" onclick="anterior()" value="anterior">
<?
}
?></p>
				<div class="foot"></div>				
			</div>
	
		</div>
	
<? include("side.php") ?>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
<script language="javascript" defer>
indice = <?= $indice ?>;

function siguiente() {
  indice = indice + 1;
  navega();
}

function anterior() {
  indice = indice - 1;
  navega();
}

function navega() {
  document.location.href="comentarios.php?indice=" + indice;
}
</script>
</html>
