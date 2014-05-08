<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/GrupoDAO.inc");
include ("header.php");

$usuarios = new UsuarioDAO();
$jugadoractual = $usuarios->getCurrent();

$grupos = new GrupoDAO();
?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">
<?
foreach($grupos->getByUserId($jugadoractual->getId()) as $grupo) {
?>

				<!-- Main start -->
	
				<h2><a href="<?= $grupo->getNombre() ?>"><strong>Clasificaci&oacute;n</strong> del grupo <?= $grupo->getNombre() ?></a></h2>
				<div class="content">
	
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><?= $grupo->getDescripcion() ?></p>
					<p>A&ntilde;ade a otros amigos a tu grupo, identificandolos por su email, o crea otro diferente!</p>
					<p style="position:relative;right:20px">
						<form id="add<?= $grupo->getId() ?>" method="POST" action="addUserGroup.php">
							<input type="hidden" name="groupid" value='<?= $grupo->getId() ?>'/>
							<input width="40" type="text" value="email" onfocus='if (value == "email") value="";' size="40" name="email"/>
							<input class="btn" type="button" value="a&ntilde;adir" onclick="document.forms.add<?= $grupo->getId() ?>.submit()">
						</form>
					</p>
					<p>
						<form id="comment<?= $grupo->getId() ?>" method="POST" action="commentGroup.php">
							<input type="hidden" name="groupid" value='<?= $grupo->getId() ?>'/>
							<input class="btn" type="button" value="Comentarios" onclick="document.forms.comment<?= $grupo->getId() ?>.submit()">
						</form>
						<form id="delete<?= $grupo->getId() ?>" method="POST" action="leaveGroup.php">
							<input type="hidden" name="groupid" value='<?= $grupo->getId() ?>'/>
							<input class="btn" type="button" value="Abandonar grupo" onclick="document.forms.delete<?= $grupo->getId() ?>.submit()">
						</form>
					</p>
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
<?
}
?>	
				<h2><a href="altaGrupos"><strong>Alta</strong> de un nuevo grupo</a></h2>
				<div class="content">
					<p>Para crear un grupo nuevo, solamente necesitas un nombre jugoso y una descripci&oacute;n atractiva. Despu&eacute;s podr&aacute;s a&ntilde;adir a los primos con los que te quieras apostar una cena, por ejemplo.</p>
					<form id="altaGrupo" action="addGroup.php" method="POST">
						<table border = "0">
						    <tr>
							<td style="font-size:12px" >Nombre</td>
							<td><input width="40" type="text" value="" size="40" name="groupname"/></td>
						    </tr>
						    <tr>
							<td>Descripci&oacute;n</td>
							<td><textarea cols="60" rows="4" size="150" name="description"></textarea></td>
						    </tr>
						    <tr>
							<td colspan="2"><input class="btn" type="submit" value="crear" /></td>
						    </tr>
						</table>
					</form>
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
<script language="javascript" defer >

$(document).ready(function(){

  $("#main_inner").accordion({
    collapsible: true,
    header: 'h2',
    autoHeight: false
  });

});

</script>
</html>
