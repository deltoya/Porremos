<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/MinutoDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/BanderaDAO.inc");
require_once("inc/JornadaDAO.inc");
require_once("inc/Comprobador.inc");


  $usuarios = new UsuarioDAO(); 
  $jugadoractual = $usuarios->getCurrent();
?>
<? include ("header.php") ?>
	<style type="text/css">
		label { float: left; width: 55%; }
		input.text { margin-bottom:12px; width:20%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		
	</style>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
				<h2><a href="jugando"><strong>Partidos</strong> en juego</a></h2>
				<div class="content">
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Los partidos</strong> que se muestran a continuaci&oacute;n est&aacute;n en juego, y su resultado puede ser modificado gastando los <strong>minutos esp&iacute;a</strong> seg&uacute;n se explica en las normas.</p>
					<img style="float:left; margin:20px" src="images/reloj.png" />
					<p style="margin:50px; font-size:20px">Tienes un total de <strong id="mins"><?= $jugadoractual->getMinutos() ?></strong> minutos disponibles.</p>
					<p style="margin:50px; font-size:20px">Hora: <strong id="hora"><?= date_create()->format('H:i:s'); ?></strong>&nbsp;&nbsp;<a href="#" onclick="$('strong#hora').load('time.php');return false;"><img src="images/refresh.png" /></a></p>
					<table>
					<tr>
						<th class="first"><strong>hora</strong></th>
						<th class="second"><strong>equipo</strong> local</th>
						<th class="second"><strong>equipo</strong> visitante</th>
						<th class="second"><strong>apuesta</strong></th>
					</tr>
<?
  $partidos = new PartidoDAO(); 
  $flags = new BanderaDAO();
  $partidospendientes = $partidos->getPendientes();
  $class = "rowB";
  foreach($partidospendientes as $partido) {
    if (!$partido->isLive())
      continue;

    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadoractual->getApuesta($partido->getId());
	
?>
					<tr class="<?= $class ?>" onclick="apuesta(<?= $partido->getId() .",'". $partido->getLocal() ."','". $partido->getVisitante() ?>')">
						<td class="first"><span class="date"><?= $partido->getFecha()->format('H:i'); ?></span></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($partido->getLocal())?>"/>&nbsp;<?= $partido->getLocal() ?></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($partido->getVisitante())?>"/>&nbsp;<?= $partido->getVisitante() ?></td>
						<td class="second"><div id="<?= 'ap-' . $partido->getId() ?>"><?= $apuesta->getLocal() ?> - <?= $apuesta->getVisitante() ?></div></td>
					</tr>
<?
  }
?>
					</table>
					<p></p>
				</div>

				<h2><a href="obtener"><strong>Jornadas</strong> con minutos</a></h2>
				<div class="content">
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Estas</strong> jornadas son las que permiten ganar minutos esp&iacute;a, tal y como se explica en las reglas. Se debe apostar, antes de empezar la jornada, cuantos goles se van a marcar entre todos los partidos de dicha jornada.</p>
					<table>
					<tr>
						<th class="first"><strong>fecha</strong></th>
						<th class="second"><strong>goles apostados</strong></th>
						<th class="second"><strong>goles reales</strong></th>
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
    if ($jornada->esCerrado())
    {
?>
							<?= $jugadoractual->getApuestaGolesJornada($jornada->getId()) ?>
<?
    } else {
?>
							<input type="text" width="2" size="2" id="j<?= $jornada->getId() ?>" value="<?= $jugadoractual->getApuestaGolesJornada($jornada->getId()) ?>"/>
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
						<td class="second" style='text-align:center;'><?= $goles ?></td> 
<?
    if ($goles)
    {
      $mins = $jugadoractual->getMinutosEnJornada($jornada->getId());
?>
						<td class="second" style='text-align:center;<?= $mins['style'] ?>'><?= $mins['minutos'] ?></td>
<?
    } else {
?>
						<td class="second" style='text-align:center;'><?= $jornada->getMinutos() ?></td> 
<?
    }
?>
					</tr>
<?
  }
?>
					</table>
				</div>
	
				<h2><a href="minutos"><strong>Minutos</strong> gastados</a></h2>
				<div class="content">
					
					<table>
					<tr>
						<th class="first"><strong>fecha</strong></th>
						<th class="second"><strong>local</strong></th>
						<th class="second"><strong>visitante</strong></th>
						<th class="second"><strong>resultado</strong></th>
						<th class="second"><strong>minutos</strong></th>
					</tr>
<?
  $minutos = new MinutoDAO();
  $misminutos = $minutos->getMinutosByUserId($jugadoractual->getId());
  $class = "rowB";
  foreach($misminutos as $minuto) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
?>
					<tr class="<?= $class ?>">
						<td class="first"><span class="date"><?= $minuto->getFecha()->format('d.m.Y H:i:s'); ?></span></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($minuto->getLocal())?>"/>&nbsp;<?= $minuto->getLocal() ?></td>
						<td class="second"><img src="images/flags/<?=$flags->getFlag($minuto->getVisitante())?>"/>&nbsp;<?= $minuto->getVisitante() ?></td>
						<td class="second"><?= $minuto->getGolesLocal() . ' - ' . $minuto->getGolesVisitante() ?></td> 
						<td class="second"><?= $minuto->getMinutos() ?></td>
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
  $("#main_inner").accordion({
    collapsible: true,
    header: 'h2',
    autoHeight: false
  });

  $dialog = $('<div></div>')
    .html('')
    .dialog({
      autoOpen: false,
      modal: true,
      title: 'Minutos espia',
      buttons: {
        Ok: function() {
          $(this).dialog('close');
        }
      }
    });

    
    local = $("#local");
    visitante = $("#visitante");
    allFields = $([]).add(local).add(visitante);
    tips = $(".validateTips");

    
    $("#dialog-form").dialog({
      autoOpen: false,
      height: 250,
      width: 300,
      modal: true,
      buttons: {
        'Enviar': function() {
          var bValid = true;
          allFields.removeClass('ui-state-error');

          bValid = bValid && checkLength(local,"local",1,2);
          bValid = bValid && checkLength(visitante,"visitante",1,2);

          bValid = bValid && checkRegexp(local,/^([0-9])+$/,"Por favor, introduce un numero");
          bValid = bValid && checkRegexp(visitante,/^([0-9])+$/,"Por favor, introduce un numero");
          
          if (bValid) {
            live(id, local.val(), visitante.val())
            $(this).dialog('close');
          }
        },
        Cancel: function() {
          $(this).dialog('close');
        }
      },
      close: function() {
        allFields.val('').removeClass('ui-state-error');
      }
    });
});

    function updateTips(t) {
      tips
        .text(t)
        .addClass('ui-state-highlight');
      setTimeout(function() {
        tips.removeClass('ui-state-highlight', 1500);
      }, 500);
    }

    function checkLength(o,n,min,max) {

      if ( o.val().length > max || o.val().length < min ) {
        o.addClass('ui-state-error');
        updateTips("La longitud de " + n + " debe estar entre "+min+" y "+max+".");
        return false;
      } else {
        return true;
      }

    }

    function checkRegexp(o,regexp,n) {

      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass('ui-state-error');
        updateTips(n);
        return false;
      } else {
        return true;
      }

    }

function apuesta(idApuesta, equipolocal, equipovisitante) {
  id = idApuesta;
  var locallabel = $("label[for='local']"); 
  var visitantelabel = $("label[for='visitante']");

  locallabel.html(equipolocal);
  visitantelabel.html(equipovisitante);
  $("#dialog-form").dialog('open');
}

function live(id, local, visitante) {
  $.getJSON('apuestaLive.php',
    { id: id, local: local, visitante: visitante },
    function(data) {
      $dialog.html(data.data);
      if (data.ret) {
        $("#ap-" + id).html(local +" - " + visitante);
      }

      if (data.minutos) {
        $("strong#mins").html(data.minutos);
      }
      $dialog.dialog('open');
  });
}

function send(apuesta, id) {
  $.ajax({
    type: 'GET',
    async: false,
    url: "apuestaGolesJornada.php",
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

<div id="dialog-form" title="Cambio de resultado">
  <p class="validateTips">Todos los campos son obligatorios.</p>

  <form>
  <fieldset>
    <label for="local">Resultado local</label>
    <input type="text" name="local" id="local" class="text ui-widget-content ui-corner-all" />
    <label for="visitante">Resultado visitante</label>
    <input type="text" name="visitante" id="visitante" value="" class="text ui-widget-content ui-corner-all" />
  </fieldset>
  </form>
</div>

</html>
