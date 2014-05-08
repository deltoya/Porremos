<?
require_once("../inc/Admin.inc");
require_once("../inc/UsuarioDAO.inc");
$usuarios = new UsuarioDAO(); 
$jugador = $usuarios->getCurrent();

?>
<? include ("../header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Reseteo</strong> de contrase&ntilde;a</h2>
				<div class="content">

					<form name="updateuser" action="resetPassword.php" method="POST">
					<img src="/images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Ahora vamos</strong> a resetear la password de ese primo que se ha olvidado de ella. Manda narices, verg&uuml;enza. Si total, no va a ser capaz de acertar ni un s&oacute;lo resultado, que mas le da no poder entrar...
					<div class="mensaje" id="mensaje" ></div>
					<table>
					<tr>
						<td class="second"><label for="nombre">Nombre:</label></td>
						<td class="second"><input type="text" id='nombre' name="nombre" /></td>
					</tr>
					<tr>
						<td class="second"><label for="password">Password:</label></td>
						<td class="second"><input type="password" id='password' name="password" value=""/></td>
					</tr>
					<tr>
						<td class="second"><label for="password2">Confirma password:</label></td>
						<td class="second"><input type="password" id='password2' name="password2" value=""/></td>
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

</body>
<script language="javascript" defer >
  function guarda() {
    var pwd = document.getElementById('password').value;
    var pwd2 = document.getElementById('password2').value;
    if (pwd != pwd2) {
      informa("Las passwords no coinciden");
      marca(document.getElementById('pwd'));
      marca(document.getElementById('pwd2'));
    } else if (pwd.length > 0 && pwd.length < 7) {
      informa("Por favor, introduce una password mas larga");
      marca(document.getElementById('pwd'));
      marca(document.getElementById('pwd2'));
    } else if (pwd.length > 25) {
      informa("Por favor, introduce una password mas corta");
      marca(document.getElementById('pwd'));
      marca(document.getElementById('pwd2'));
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
