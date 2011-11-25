<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/PreguntaDAO.inc");
require_once("inc/MinutoDAO.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/BanderaDAO.inc");
require_once("inc/Comprobador.inc");

  $partidos = new PartidoDAO(); 
  $usuarios = new UsuarioDAO(); 
  $flags = new BanderaDAO();
  $jugadoractual = $usuarios->getCurrent();
  $jugadorvisitado = $usuarios->getById($_REQUEST['id']);

  $mensajes = array();
  $mensajes[] = "Quiz&aacute;s puedas aprender algo de como se debe pronosticar, so mel&oacute;n.";
  $mensajes[] = "Nadie esperaba nada de &eacute;l, y no parece dispuesto a defraudar a sus detractores.";
  $mensajes[] = "Partiendo de la nada ha sido capaz de alcanzar las mas altas cotas de miseria.";
  $mensajes[] = "Para que veas que pensar no tiene absolutamente nada que ver con conseguir un buen resultado.";
  $mensajes[] = "Aunque vistas algunas de sus elecciones, quiz&aacute; deber&iacute;mos decir los puntos obtenidos por el dado del monopoly que ha utilizado...";
  $mensajes[] = "Gran jugador, tocado por los hados de la buena fortuna. Un aut&eacute;ntico jugador de leyenda. Ahora, aparta la pistola, vale?";
  $mensajes[] = "Sin duda, es tan extra&ntilde;o el hecho de que haya conseguido siquiera un punto, que se merece una buena explicaci&oacute;n.";

?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><a href="Puntuacion"><strong>Puntuaci&oacute;n</strong> actual de <?=$jugadorvisitado->getNick() ?></a></h2>
				<div class="content">
	
					<form name="actualizaPartidos" action="/apuesta.php" method="POST">
					<img src="images/zakumi.png" class="cpic right" alt="" />
					<p><strong>A continuaci&oacute;n</strong> intentaremos explicar el detalle de como ha obtenido todos sus puntos <?=$jugadorvisitado->getNick() ?>. <?= $mensajes[rand(0,5)] ?> </p>
					<p>Tambi&eacute;n se incluye una gr&aacute;fica de la evoluci&oacute;n de sus puntos en las distintas jornadas.</p>
					<table>
					<tr>
						<th class="first"><strong>fecha</strong></th>
						<th class="second"><strong>equipo</strong> local</th>
						<th class="second"><strong>equipo</strong> visitante</th>
						<th class="second"><strong>Pro.</strong></th>
						<th class="second"><strong>Real</strong></th>
						<th class="second"><strong>Pts.</strong></th>
					</tr>
<?
  $partidosjugados = $partidos->getJugados();
  $class = "rowB";
  $datosgrafica = array();
  foreach($partidosjugados as $partido) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadorvisitado->getApuesta($partido->getId());
    $resultado = Comprobador::comprueba($apuesta, $partido);

    $ffecha=$partido->getFecha()->format('m-d');
    if(!isset($datosgrafica[$ffecha]))
        $datosgrafica[$ffecha] = $jugadorvisitado->getPuntosenFecha($partido->getFecha());
?>
					<tr class="<?= $class ?>">
						<td class="first"><span class="date"><?= $partido->getFecha()->format('d.m.Y'); ?></span></td>
						<td class="second"><?= $partido->getLocal() ?></td>
						<td class="second"><?= $partido->getVisitante() ?></td>
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
						<td class="second"><?= $resultado->getPuntos() ?></td>
					</tr>
<?
  }
?>
					</table>
					<p></p>
				</div>
				<h2><a href="preguntas"><strong>Puntos</strong> por preguntas</a></h2>
				<div class="content">
				<table>
					<tr>
						<th class="first"><strong>Pregunta</strong></th>
						<th class="second"><strong>Apuesta</strong></th>
						<th class="second"><strong>Soluci&oacute;n</strong></th>
						<th class="second"><strong>Pts.</strong></th>
					</tr>
<?
  $preguntas = new PreguntaDAO(); 
  $listado = $preguntas->getListado(1);
  $class = "rowB";
  foreach($listado as $pregunta) {
    //if ($pregunta->getRespuesta()=='')
      //continue;
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadorvisitado->getRespuesta($pregunta->getId());
    if ($pregunta->getRespuesta() == $apuesta) {
      $estilo = 'style="color: green"';
      $puntosPreg = $pregunta->getValor();
    } else if ($pregunta->getRespuesta()=='') {
      $estilo = '';
      $puntosPreg = 0;
    } else {
      $estilo = 'style="color: red"';
      $puntosPreg = 0;
    }

    $ffecha=$pregunta->getFecha()->format('m-d');
    if(!isset($datosgrafica[$ffecha])) {
	$aux = $jugadorvisitado->getPuntosenFecha($pregunta->getFecha());
        $datosgrafica[$ffecha] = $aux;
    }

?>
					<tr class="<?= $class ?>">
						<td class="first"><?= $pregunta->getTexto() ?></td>
						<td class="second" <?= $estilo ?> ><?= $apuesta ?></td>
						<td class="second"><?= $pregunta->getRespuesta() ?></td>
						<td class="second"><?= $puntosPreg ?></td>
					</tr>
<?
  }
?>
					</table>
					<p></p>
				</div>
<?
    ksort($datosgrafica);
    foreach($datosgrafica as $fecha => $puntos) {
        $leyenda = $leyenda . '|' . substr($fecha,3);
	$datos = $datos . ',' . $puntos;
    }
?>
				<h2><a href="#grafica"><strong>Evoluci&oacute;n</strong> hist&oacute;rica de <?=$jugadorvisitado->getNick() ?></a></h2>
				<div class="content">
					<img src="http://chart.apis.google.com/chart?
					chs=480x200&
					chd=t:0<?= $datos ?>&
					cht=lc&
					chxt=x,y&
					chxl=0:<?= $leyenda ?>|1:||150|300|450|600&
					chds=0,600&
					chf=c,ls,0,CCCCCC,0.1,FFFFFF,0.1&" />
				</div>
                                <h2><a href="#minutos"><strong>Minutos</strong> esp&iacute;a usados</a></h2>
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
  $susminutos = $minutos->getMinutosByUserId($jugadorvisitado->getId());
  $class = "rowB";
  foreach($susminutos as $minuto) {
    $class = ($class=='rowB') ? 'rowA' : 'rowB';
    $apuesta = $jugadorvisitado->getApuesta($partido->getId());
?>
                                        <tr class="<?= $class ?>">
                                                <td class="first"><span class="date"><?= $minuto->getFecha()->format('d.m H:i:s'); ?></span></td>
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
