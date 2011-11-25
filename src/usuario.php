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
	
				<h2><strong>Configuraci&oacute;n</strong> de usuario</h2>
				<div class="content">

					<form name="updateuser" action="updateuser.php" method="POST">
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Aqu&iacute; puedes</strong> varias caracter&iacute;sticas de tu usuario. Te pedimos que por favor cambies la password si no lo has hecho ya. La password inicial no es mas que eso, inicial. Tambi&eacute;n puedes cambiar el nombre por el que te ver&aacute;n los dem&aacute;s participantes cuando hagas comentarios, y con el que saldr&aacute;s en la clasificaci&oacute;n. Tu identificador de usuario, en contra, es fijo y no puedes cambiarlo</p>
					<div class="mensaje" id="mensaje" ></div>
					<table>
					<tr>
						<td class="second"><label for="nombre">Nombre:</label></td>
						<td class="second"><input type="text" id='nombre' name="nombre" disabled value="<?=$jugador->getNombre()?>"/></td>
					</tr>
					<tr>
						<td class="second"><label for="nick">Alias:</label></td>
						<td class="second"><input type="text" id='nick' name="nick" value="<?=$jugador->getNick()?>"/></td>
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
	
<? include("side.php") ?>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
<script language="javascript" defer >
  function guarda() {
    var alias = document.getElementById('nick').value;
    var pwd = document.getElementById('password').value;
    var pwd2 = document.getElementById('password2').value;
    if (alias.length < 4) {
      marca(document.getElementById('nick'));
      informa("El alias es demasiado corto");
    } else if (alias.length > 15) {
      informa("El alias es demasiado largo");
      marca(document.getElementById('nick'));
    } else if (pwd != pwd2) {
      informa("Las passwords no coinciden");
      marca(document.getElementById('pwd'));
      marca(document.getElementById('pwd2'));
    } else if (pwd.length > 0 && pwd.length < 7) {
      informa("Por favor, introduce una password mas larga");
      marca(document.getElementById('pwd'));
      marca(document.getElementById('pwd2'));
    } else if (pwd.length > 12) {
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
