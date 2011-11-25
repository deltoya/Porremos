<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/BanderaDAO.inc");
require_once("inc/Comprobador.inc");
?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><a href="pendientes"><strong>Partidos</strong> pendientes</a></h2>
				<div class="content">
	
					<form name="actualizaPartidos" action="/apuesta.php" method="POST">
					<img src="images/zakumi.png" class="cpic right" alt="" />
					<p><strong>Los partidos</strong> que se muestran a continuaci&oacute;n son aquellos que todav&iacute;a no se han jugado, aunque ya hayas seleccionado un resultado. Recuerda que debes seleccionarlo antes de las 12 de la noche del d&iacute;a anterior a su celebraci&oacute;n. Apuesta y disfruta :)</p>
					<table>
					<tr>
						<th class="first"><strong>fecha</strong></th>
						<th class="second"><strong>equipo</strong> local</th>
						<th class="second"><strong>equipo</strong> visitante</th>
						<th class="second"><strong>L</strong></th>
						<th class="second"><strong>V</strong></th>
					</tr>
<?
  $partidos = new PartidoDAO(); 
  $usuarios = new UsuarioDAO(); 
  $flags = new BanderaDAO();
  $jugadoractual = $usuarios->getCurrent();
  $partidospendientes = $partidos->getPendientes();
  $class = "rowB";
  foreach($partidospendientes as $partido) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadoractual->getApuesta($partido->getId());
	
?>
					<tr class="<?= $class ?>">
						<td class="first"><span class="date"><?= $partido->getFecha()->format('d.m.Y H:i'); ?></span></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($partido->getLocal())?>"/>&nbsp;<a href="#" <?= !$partido->esCerrado() ? 'class="tooltip"' : "" ?> id='<?=$partido->getId() ?>' onclick="muestra('<?=$partido->getId() ?>')"><?= $partido->getLocal() ?></a></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($partido->getVisitante())?>"/>&nbsp;<a href="#" <?= !$partido->esCerrado() ? 'class="tooltip"' : "" ?> id='<?=$partido->getId() ?>' onclick="muestra('<?=$partido->getId() ?>')"><?= $partido->getVisitante() ?></a></td>
						<td class="second"><input type="text" id='<?= "ap_" . $partido->getId() . "_L" ?>' name='<?= "ap_" . $partido->getId() . "_L" ?>' size="1" width="1" value="<?= $apuesta->getLocal() ?>" <?= $partido->esCerrado() ? "disabled " : "" ?>/></td>
						<td class="second"><input type="text" id='<?= "ap_" . $partido->getId() . "_V" ?>' name='<?= "ap_" . $partido->getId() . "_V" ?>' size="1" width="1" value="<?= $apuesta->getVisitante() ?>" <?= $partido->esCerrado() ? "disabled " : "" ?>/></td>
					</tr>
<?
  }
?>
					</table>
					</form>
					<p></p>
					<p style="position:relative;right:20px"><input class="btn" type="button" onclick="document.forms.actualizaPartidos.submit()" value="guardar"></p>
				</div>
	
				<h2><a href="jugados"><strong>Partidos</strong> jugados</a></h2>
				<div class="content">
					
					<table>
					<tr>
						<th class="first"><strong>fecha</strong></th>
						<th class="second"><strong>equipo</strong> local</th>
						<th class="second"><strong>equipo</strong> visitante</th>
						<th class="second"><strong>Pro.</strong></th>
						<th class="second"><strong>Real</strong></th>
					</tr>
<?
  $partidosjugados = $partidos->getJugados();
  $class = "rowB";
  foreach($partidosjugados as $partido) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadoractual->getApuesta($partido->getId());
    $resultado = Comprobador::comprueba($apuesta, $partido);
?>
					<tr class="<?= $class ?>">
						<td class="first"><span class="date"><?= $partido->getFecha()->format('d.m.Y'); ?></span></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($partido->getLocal())?>"/>&nbsp;<?= $partido->getLocal() ?></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($partido->getVisitante())?>"/>&nbsp;<?= $partido->getVisitante() ?></td>
<?
	echo '<td class="second" style="color:' . ($resultado->esAcierto() ? 'green' : 'red' ) . '">';
        if ($resultado->esAciertoLocal()) {
          echo '<strong>' . $apuesta->getLocal() . '</strong>';
        } else {
          echo $apuesta->getLocal();
	}
        echo ' - ';
        if ($resultado->esAciertoVisitante()) {
          echo '<strong>' . $apuesta->getVisitante() . '</strong>';
        } else {
          echo $apuesta->getVisitante();
	}
        echo '</td>';
?>
						<td class="second"><?= $partido->getGolesLocal() . ' - ' . $partido->getGolesVisitante() ?></td>
					</tr>
<?
  }
?>
					</table>
	
				</div>
	
				<!-- Main End -->
			</div>
	
		</div>
	
<? include("side.php") ?>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
<script language="javascript" defer >
function muestra(id) {
  document.getElementById('myiframe').src = "partido.php?id=" + id;
  document.getElementById('myiframe').style.display="inline";
}

$(document).ready(function(){
  $('.tooltip').tooltip({
    folderurl : 'partido.php?id='
  });

  $("#main_inner").accordion({
    collapsible: true,
    header: 'h2',
    autoHeight: false
  });

});

</script>
</html>
