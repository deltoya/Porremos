<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/UsuarioDAO.inc");
include ("header.php")
?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Clasificaci&oacute;n</strong> por puntos</h2>
				<div class="content">
	
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><strong>La clasificaci&oacute;n</strong> ordena a todos los participantes seg&uacute;n los aciertos que hayan tenido hasta el momento. Los criterios de puntuaci&oacute;n est&aacute;n disponibles en las <a href="normas.php">normas</a>. La clasificaci&oacute;n no se considerar&aacute; definitiva hasta la finalizaci&oacute;n de la eurocopa. Adem&aacute;s de los aciertos en los partidos, tambi&eacute;n se tienen en cuenta las apuestas adicionales explicadas en las normas.
					<table>
					<tr>
						<th class="first" width="10%"></th>
						<th class="second"><strong>jugador</strong></th>
						<th class="second" width="10%"><strong>puntos</strong></th>
					</tr>
<?
  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();

  $pagina = $_REQUEST['pagina'];
  if (!$pagina || !is_numeric($pagina))
  {
    $mipos = $jugadoractual->getPosicion();
    $pagina = floor(($mipos - 1) / UsuarioDAO::$UsuariosPorPagina);
  }
  else
  {
    $pagina = $pagina - 1;
  }
  $total = $usuarios->getTotal();
  $paginas = ceil($total/UsuarioDAO::$UsuariosPorPagina);

  $jugadores = $usuarios->getClasificacion($pagina);
  $class = "rowB";
  $base = 1 + $pagina * UsuarioDAO::$UsuariosPorPagina;
  $puntosanterior = -1;
  foreach($jugadores as $i => $jugador) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $pre = ''; $post = '';
    if ($jugadoractual->getId() == $jugador->getId()) {
      $pre = '<strong>';
      $post = '</strong>';
    }
    $puntos= $jugador->getPuntos();
    if ($puntos == $puntosanterior)
      $posicion = '-';
    else
      $posicion = ($i + $base);

    $puntosanterior = $puntos;
?>
					<tr class="<?= $class ?>">
						<td class="first"><?= $pre . $posicion . $post ?></td>
						<td class="second"><a href="resumen.php?id=<?= $jugador->getId() ?>"><?= $pre . $jugador->getNick() . $post ?></a></td>
						<td class="second"><?= $pre . $jugador->getPuntos() . $post ?></td>
					</tr>
<?
  }
?>
					</table>
				</div>
				<div id="paginacion">
				<ul>
<?
  for ($i = 1; $i <= $paginas; $i++)
  {
    if ($i==$pagina+1)
      echo "<li>$i</li>";
    else
      echo "<li><a href='clasifica.php?pagina=$i'>$i</a></li>";
  }
?>
				</ul>
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
