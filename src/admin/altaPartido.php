<?
require_once("../inc/Admin.inc");
require_once("../inc/UsuarioDAO.inc");
require_once("../inc/PreguntaDAO.inc");
$usuarios = new UsuarioDAO(); 
$jugador = $usuarios->getCurrent();

?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Alta</strong> de partidos</h2>
				<div class="content">

					<form name="addmatch" action="addmatch.php" method="post">
					<img src="../images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Alta de</strong> Partidos. Aqu&iacute; podr&aacute;s dar de alta los partidos de la fases sucesivas de la eurocopa. Recuerda, si est&aacute;s dando de alta partidos mas all&aacute; de cuartos de final, y te parece ver a Espa&ntilde;a en alg&uacute;n lado, es una ilusi&oacute;n &oacute;ptica.</p>
					<div class="mensaje" id="mensaje" ></div>
					<table>
					<tr>
						<td><label for="local">Equipo local:</label></td>
						<td><input id='local' name="local"/></td>
					</tr>
					<tr>
						<td><label for="visitante">Equipo Visitante:</label></td>
						<td><input id='visitante' name="visitante" /></td>
					</tr>
					<tr>
						<td><label for="fechaM">Fecha:</label></td>
<!--						<td><input type="text" id='fechaM' name="fechaM" value="dd/mm/yy"  onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)"/><input type="hidden" id='fecha' name="fecha" /></td>-->
						<td><input type="text" id='fechaM' name="fechaM" /><input type="hidden" id='fecha' name="fecha" /></td>
					</tr>
					<tr>
						<td><label for="puntos">Puntos</label></td>
						<td><input type="text" id='puntos' name="puntos"/></td>
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
    var local = document.getElementById('local').value;
    var visitante = document.getElementById('visitante').value;
    var fecha = document.getElementById('fechaM').value;
    var puntos = document.getElementById('puntos').value;
    if (local == '') {
      marca(document.getElementById('local'));
      informa("Introduce equipo local");
    } else if (visitante == '') {
      marca(document.getElementById('visitante'));
      informa("Introduce equipo visitante");
    } else if (fecha == '') {
      informa("Introduce la fecha del partido");
      marca(document.getElementById('fechaM'));
    } else if (puntos == '') {
      informa("Introduce los puntos, por favor");
      marca(document.getElementById('puntos'));
    } else if (puntos != parseInt(puntos,10)) {
      informa("Por favor, introduce solo numeros en los puntos");
      marca(document.getElementById('puntos'));
    } else {
      var mifecha =  document.getElementById('fechaM').date;
      document.getElementById('fecha').value=fecha;
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
	$("#fechaM").datepicker({
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
