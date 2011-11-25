<?
require_once("inc/Security.inc");
require_once("inc/PreguntaDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/Comprobador.inc");
?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Preguntas</strong> adicionales</h2>
				<div class="content">
	
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Las preguntas</strong> que se muestran a continuaci&oacute;n son apuestas adicionales a los resultados de los partidos, y como tales conllevan puntos extras. Deben responderse <strong>antes</strong> del comienzo del campeonato. Ten&eacute;is informaci&oacute;n adicional en las <a href="normas.php">normas</a>.</p>
					<table>
					<tr>
						<th class="first"><strong>Pregunta</strong></th>
						<th class="second"><strong>Respuesta</strong></th>
						<th class="second"><strong>Pts</strong></th>
					</tr>
<?
  $preguntas = new PreguntaDAO(); 
  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();
  $listado = $preguntas->getListado();
  $class = "rowB";
  foreach($listado as $pregunta) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadoractual->getRespuesta($pregunta->getId());
	
	
?>
					<tr class="<?= $class ?>">
						<td class="second"><?= $pregunta->getTexto() ?></td>
						<td class="second"><input type="text" name='<?= "ap_" . $pregunta->getId() ?>' size="20" width="20" value="<?=$apuesta?>" disabled /></td>
						<td class="second"><?= $pregunta->getValor() ?></td>
					</tr>
<?
  }
?>
					</table>
					<p></p>
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
