<?
require_once("inc/Security.inc");
require_once("inc/ComentarioDAO.inc");
require_once("inc/UsuarioDAO.inc");
?>	
		<div id="side">
			<!-- Side start -->

			<h3><strong>&uacute;ltimos</strong> <a href="comentarios.php">comentarios</a></h3>
			<div class="content">
<?
$comentarios = new ComentarioDAO();
$usuarios = new UsuarioDAO();
$lista = $comentarios->getCabeceraListado();
foreach ($lista as $comentario) {
  $nick = $usuarios->getById($comentario->getIdUsuario())->getNick();
?>
				<p><a href="comentarios.php"><strong><?= $nick ?></strong> dijo</a>: <?= $comentario->getTexto() ?></p>
				<div class="divider"></div>
<?
}
?>
			</div>
			<iframe id="myiframe" style="width: 294px; height: 300px;" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="auto"></iframe>
			<!-- Side end -->
		</div>
		<div id="xbgB" class="foot"></div>
