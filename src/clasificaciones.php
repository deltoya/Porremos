<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/Comprobador.inc");
?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Espa&ntilde;ol</strong> caliente</h2>
				<div class="content">
	
					<img src="images/trixflix.png" class="cpic right" alt="" />
					<p><strong>La clasificaci&oacute;n</strong> del Espa&ntilde;ol caliente refleja el grado de ilusi&oacute;n que ten&iacute;amos al principio del torneo (un 43% puso que nos quedabamos en cuartos). S&oacute;lo los mas atrevidos, los mas valientes y los mas cazurros han llegado a ser alguien en esta clasificaci&oacute;n. Con todos ustedes: &iexcl;El Espa&ntilde;ol Caliente!</p>
					<table>
					<tr>
						<th class="first" width="10%"></th>
						<th><strong>jugador</strong></th>
						<th width="10%"><strong>aciertos</strong></th>
						<th width="10%"><strong>puntos</strong></th>
					</tr>
<?
  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();
  $jugadores = $usuarios->getCaliente();
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
						<td class="first"><?= $pre . ($i + 1) . $post ?></td>
						<td><?= $pre . $jugador->getNick() . $post ?></td>
						<td><?= $pre . $jugador->getPuntos() . $post ?></td>
						<td><?= $pre . $jugador->getPuntosEnFecha(new DateTime()) . $post ?></td>
					</tr>
<?
  }
?>
					</table>
					<p></p>
				</div>
				<div class="foot"></div>
	
				<div class="divider"></div>
	
				<h2><strong>Campe&oacute;n</strong> moral</h2>
				<div class="content">
	
					<p><strong>El Campe&oacute;n</strong> moral del torneo es la clasificaci&oacute;n seg&uacute;n los m&eacute;ritos del d&iacute;a a d&iacute;a, contando solamente los resultados de los partidos e ignorando las preguntas. Es el pringao que lleva todo el torneo jugando bien, y pierde en cuartos en la tanda de penalties. Vamos, lo que sol&iacute;a hacer Espa&ntilde;a todos los a&ntilde;os.
					<table>
					<tr>
						<th class="first" width="10%"></th>
						<th><strong>jugador</strong></th>
						<th width="10%"><strong>puntos</strong></th>
					</tr>
<?
  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();
  $jugadores = $usuarios->getMoral();
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
						<td class="first"><?= $pre . ($i + 1) . $post ?></td>
						<td><?= $pre . $jugador->getNick() . $post ?></td>
						<td><?= $pre . $jugador->getPuntos() . $post ?></td>
					</tr>
<?
  }
?>
					</table>
					<p></p>
				</div>
				<div class="foot"></div>
	
	
				<!-- Main End -->
				<div class="foot"></div>				
			</div>
	
		</div>
	
<? include("side.php") ?>
	</div>

</div>
<div id="outer2"></div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
</html>
