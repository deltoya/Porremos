<?
require_once("../inc/Admin.inc");
require_once("../inc/UsuarioDAO.inc");
require_once("../inc/PreguntaDAO.inc");
$usuarios = new UsuarioDAO(); 
$jugador = $usuarios->getCurrent();

?>
<? include ("../header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Alta</strong> de jornadas</h2>
				<div class="content">

					<form name="adddate" action="adddate.php" method="post">
					<img src="../images/zakumi.png" class="cpic right" alt="" />
					<p><strong>Alta de</strong> Jornadas. Aqu&iacute; damos de alta las jornadas para adjuntar los minutos a los jugadores seg&uacute;n sus aciertos. Recuerda que s&oacute;lo hay minutos en la fase de grupos, despu&eacute;s no. Adem&aacute;s, los minutos var&iacute;an en funci&oacute;n del n&uacute;mero de partidos de la jornada.</p>
					<div class="mensaje" id="mensaje" ></div>
					<table>
					<tr>
						<td><label for="fecha">Fecha:</label></td>
						<td><input type="text" id='fecha' name="fecha" /></td>
					</tr>
					<tr>
						<td><label for="minutos">Minutos:</label></td>
						<td><input type="text" id='minutos' name="minutos"/></td>
					</tr>
					</table>
					</form>
					<p></p>
					<p style="position:relative;right:20px"><input class="btn" type="button" onclick="guarda()" value="guardar"></p>
				</div>
				<div class="foot"></div>
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

<script language="javascript">
  function guarda() {
    var fecha = document.getElementById('fecha').value;
    var minutos	 = document.getElementById('minutos').value;
    if (fecha == '') {
      informa("Introduce la fecha del partido");
      marca(document.getElementById('fecha'));
    } else if (minutos == '') {
      informa("Introduce los minutos, por favor");
      marca(document.getElementById('minutos'));
    } else if (minutos != parseInt(minutos,10)) {
      informa("Por favor, introduce solo numeros en los minutos");
      marca(document.getElementById('minutos'));
    } else {
      document.forms[0].submit();
    }
  }

  function marca(objeto) {
    objeto.style.backgroundColor = '#DDDDFF';
  }

  function informa(texto) {
    var mensaje = document.getElementById('mensaje');
    mensaje.style.display='inline';
    while (mensaje.firstChild) {
      mensaje.removeChild(mensaje.firstChild);
    };

    mensaje.appendChild(document.createTextNode(texto));
  }

  $(function() {
	$("#fecha").datepicker({
          dateFormat: 'yy-mm-dd',
          minDate: '+0',
          firstday: 1,
          dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'] 
        });
  });

  $(function() {
		var availableTags1 = [
<?
  $preguntas = new PreguntaDAO();
  $pregunta = $preguntas->getById(1);
  foreach($pregunta->getPosibles() as $opcion) {
    echo '"' . $opcion . '",';
  }
?>
		];
		$("#ap_1").autocomplete({
			source: availableTags1,
			minLength: 0,
			delay: 0
		});
	});

</script>
</body>
</html>
