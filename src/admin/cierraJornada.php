<?
require_once("../inc/Security.inc");
require_once("../inc/Admin.inc");
require_once("../inc/PartidoDAO.inc");
require_once("../inc/MinutoDAO.inc");
require_once("../inc/UsuarioDAO.inc");
require_once("../inc/BanderaDAO.inc");
require_once("../inc/JornadaDAO.inc");
require_once("../inc/Comprobador.inc");


  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();
?>
<? include ("../header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<h2><strong>Jornadas</strong> con minutos</h2>
				<div class="content">
					<img src="/images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Aqu&iacute;</strong> se cierran las jornadas para que se sumen los minutos esp&iacute;a a los primos que hayan acertados los goles de la jornada.</p>
					<table>
					<tr>
						<th class="first"><strong>fecha</strong></th>
						<th class="second"><strong>goles</strong></th>
						<th class="second"><strong>minutos</strong></th>
					</tr>
<?
  $jornadas = new JornadaDAO();
  $lasjornadas = $jornadas->getAllJornadas();
  $class = "rowB";
  foreach($lasjornadas as $jornada) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $goles = $jornada->getGoles();
    $fechaJornada = $jornada->getFecha();
?>
					<tr class="<?= $class ?>">
						<td class="first"><span class="date"><?= $jornada->getFecha()->format('d.m.Y'); ?></span></td>
						<td class="second" style='text-align:center;'>
<?
    if ($goles)
    {
?>
							<?= $goles ?>
<?
    } else if (!$jornada->esCerrado()) {
?>
							Pte.
<?
    } else {
?>
							<input type="text" width="2" size="2" id="j<?= $jornada->getId() ?>" />
							<input type="button" class="btn" value="Enviar" id="b<?= $jornada->getId() ?>" style='float: none; margin: 0 0 0 20px;' value=""/>
							<script>
								$(function() {
								  $("#b<?= $jornada->getId() ?>").click(function() {
								    send($("#j<?= $jornada->getId() ?>").val(), <?= $jornada->getId() ?>);
								  });
								});
							</script>
<?
    }
?>
						</td>
						<td class="second" style='text-align:center;'><?= $jornada->getMinutos() ?></td> 
					</tr>
<?
  }
?>
					</table>
				</div>
	
				<!-- Main End -->
			</div>
	
		</div>
	
<? include("../side.php") ?>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
<script language="javascript" defer >

$(document).ready(function() {

  $dialog = $('<div></div>')
    .html('')
    .dialog({
      autoOpen: false,
      modal: true,
      title: 'Goles por jornada',
      buttons: {
        Ok: function() {
          $(this).dialog('close');
        }
      }
    });

});

function send(apuesta, id) {
  $.ajax({
    type: 'POST',
    async: false,
    url: "cierraGolesJornada.php",
    data: { goles: apuesta, id: id },
    success: function(data){
        data = $.parseJSON(data);
        $dialog.html(data.data);
        $dialog.dialog('open');
      },
    datatype: 'json'
    });
}
</script>

</html>
