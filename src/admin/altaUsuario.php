<?
require_once("../inc/Admin.inc");
require_once("../inc/UsuarioDAO.inc");
$usuarios = new UsuarioDAO(); 
$jugador = $usuarios->getCurrent();

include ("../header.php")
 ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Configuraci&oacute;n</strong> de usuario</h2>
				<div class="content">

					<form name="updateuser" action="createuser.php" method="POST">
					<img src="/images/zakumi.png" class="cpic right" alt="" />
					<p><strong>Alta de</strong> usuarios. Dales ca&ntilde;a, pero resiste tus impulsos de poner alias estilo <em>Segismunda</em> o <em>Espermatozoide</em>. Nunca sabes como responder&aacute; un picha floja cuando le cambias el nick.</p>
					<div class="mensaje" id="mensaje" ></div>
					<table>
					<tr>
						<td><label for="nombre">Login:</label></td>
						<td><input type="text" id='nombre' name="nombre"/></td>
					</tr>
					<tr>
						<td><label for="nick">Alias:</label></td>
						<td><input type="text" id='nick' name="nick" /></td>
					</tr>
					<tr>
						<td><label for="password">Password:</label></td>
						<td><input type="password" id='password' name="password"/></td>
					</tr>
					<tr>
						<td><label for="password2">Confirma password:</label></td>
						<td><input type="password" id='password2' name="password2"/></td>
					</tr>
					<tr>
						<td><label for="email">email:</label></td>
						<td><input type="email" id='email' name="email"/></td>
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
    var nombre = document.getElementById('nombre').value;
    var alias = document.getElementById('nick').value;
    var pwd = document.getElementById('password').value;
    var pwd2 = document.getElementById('password2').value;
    if (alias.length < 4) {
      marca(document.getElementById('nick'));
      informa("El alias es demasiado corto");
    } else if (alias.length > 15) {
      informa("El alias es demasiado largo");
      marca(document.getElementById('nick'));
    } else if (nombre.length < 4) {
      marca(document.getElementById('nombre'));
      informa("El nombre es demasiado corto");
    } else if (nombre.indexOf(' ')!= -1) {
      informa("Jorge, joder, que has vuelto a poner espacios... :P");
      marca(document.getElementById('nombre'));
    } else if (nombre.length > 15) {
      informa("El nombre es demasiado largo");
      marca(document.getElementById('nombre'));
    } else if (nombre.length > 15) {
      informa("El nombre es demasiado largo");
      marca(document.getElementById('nombre'));
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
</body>
</html>
