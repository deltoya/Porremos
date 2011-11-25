<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/GrupoDAO.inc");
include ("header.php");

$grupos = new GrupoDAO();
$grupo = $grupos->getById(1);
?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Clasificaci&oacute;n</strong> del grupo <?= $grupo->getNombre() ?></h2>
				<div class="content">
	
					<img src="images/zakumi.png" class="cpic right" alt="" />
					<p><strong>La clasificaci&oacute;n</strong> ordena a todos los participantes seg&uacute;n los aciertos que hayan tenido hasta el momento. Los criterios de puntuaci&oacute;n est&aacute;n disponibles en las <a href="normas.php">normas</a>. Adem&aacute;s de los aciertos en los partidos, tambi&eacute;n se tienen en cuenta las apuestas adicionales explicadas en las normas.</p>
					<p><?= $grupo->getDescripcion() ?>
					<table>
					<tr>
						<th class="first" width="10%"></th>
						<th class="second"><strong>jugador</strong></th>
						<th class="second" width="10%"><strong>puntos</strong></th>
					</tr>
<?
  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();

  $jugadores = $grupo->getClasificacion();
  $class = "rowB";
  foreach($jugadores as $i => $jugador) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $pre = ''; $post = '';
    if ($jugadoractual->getId() == $jugador->getId()) {
      $pre = '<strong>';
      $post = '</strong>';
    }
	
?>
					<tr class="<?= $class ?>">
						<td class="first"><?= $pre . ($i+1) . $post ?></td>
						<td class="second"><a href="resumen.php?id=<?= $jugador->getId() ?>"><?= $pre . $jugador->getNick() . $post ?></a></td>
						<td class="second"><?= $pre . $jugador->getPuntos() . $post ?></td>
					</tr>
<?
  }
?>
					</table>
				</div>
				<!-- Main End -->
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
</html>
