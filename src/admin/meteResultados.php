<?
require_once("../inc/Admin.inc");
require_once("../inc/PartidoDAO.inc");
require_once("../inc/UsuarioDAO.inc");
?>
<? include ("../header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Partidos</strong> pendientes</h2>
				<div class="content">
	
					<form name="actualizaPartidos" action="introduceResultados.php" method="POST">
					<img src="/images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Introduce el</strong> resultado de los partidos que ya se hayan jugado. Recuerda que el resultado que tienes que meter es a <strong>90 minutos</strong>. Si te equivocas, tendr&aacute; que corregirlo F&eacute;lix query en mano, y posiblemente tambi&eacute;n aproveche para meterte un palo por el culo. Amablemente, eso si.</p>
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
  $jugadoractual = $usuarios->getCurrent();
  $partidospendientes = $partidos->getPendientes();
  $class = "rowB";
  foreach($partidospendientes as $partido) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
	
?>
					<tr class="<?= $class ?>">
						<td class="first"><span class="date"><?= $partido->getFecha()->format('d.m.Y'); ?></span></td>
						<td><?= $partido->getLocal() ?></td>
						<td><?= $partido->getVisitante() ?></td>
						<td><input type="text" name='<?= "ap_" . $partido->getId() . "_L" ?>' size="1" width="1" /></td>
						<td><input type="text" name='<?= "ap_" . $partido->getId() . "_V" ?>' size="1" width="1" /></td>
					</tr>
<?
  }
?>
					</table>
					</form>
					<p></p>
					<p style="position:relative;right:20px"><input class="btn" type="button" onclick="document.forms.actualizaPartidos.submit()" value="guardar"></p>
				</div>
				<div class="foot"></div>
	
				<div class="divider"></div>
	
				<!-- Main End -->
				<div class="foot"></div>				
			</div>
	
		</div>
	
<? include("../side.php") ?>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
</html>
