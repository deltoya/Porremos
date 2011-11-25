<?
require_once("inc/Security.inc");
require_once("inc/UsuarioDAO.inc");
$usuarios = new UsuarioDAO(); 
$jugador = $usuarios->getCurrent();

?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Comentarios</strong></h2>
				<div class="content">

					<form name="updateuser" action="comenta.php" method="POST">
					<img src="images/zakumi.png" class="cpic right" alt="" />
					<p><strong>Expl&aacute;yate!</strong></p>
					<div class="mensaje" id="mensaje" ></div>
					<table>
					<tr>
						<td class="second"><label for="texto">Mensaje:</label></td>
						<td class="second"><textarea id='texto' name="texto" cols="50" rows="5"></textarea></td>
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
	
<? include("side.php") ?>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
<script language="javascript" defer>
  function guarda() {
    var texto = document.getElementById('texto').value;
    if (texto.length == 0) {
      informa("Por favor, introduce un mensaje");
      marca(document.getElementById('texto'));
    } else if (texto.length > 140) {
      informa("El limite por mensaje es de 140 caracteres");
      marca(document.getElementById('texto'));
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
</script>
</html>
