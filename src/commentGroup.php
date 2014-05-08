<?
require_once("inc/Security.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/ComentarioGrupoDAO.inc");
require_once("inc/GrupoDAO.inc");

if (!isset($_REQUEST['groupid']))
{
  header('Location:/comentarios.php');
  return;
}
$usuarios = new UsuarioDAO();
$jugador = $usuarios->getCurrent();

$indice = isset($_REQUEST['indice']) ? intval($_REQUEST['indice']) : 0 ;
$groupid = intval($_REQUEST['groupid']);
$comentarios = new ComentarioGrupoDAO();

if (!$comentarios->checkValid($groupid, $jugador->getId()))
{
  header('Location:/comentarios.php');
  return;
}
$grupos = new GrupoDAO();
$grupo = $grupos->getById($groupid);

include ("header.php");

?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
				<h2><strong>Chat privado</strong> del grupo <?= $grupo->getNombre() ?></h2>
				<div class="content">
					<p><strong>Esta es</strong> vuestra &aacute;rea privada para hablar y desfasar a gusto. Ni siquiera los administradores entrar&aacute;n aqu&iacute;, siempre que respet&eacute;is a Ra&uacute;l siete de Espa&ntilde;a y cero cuatro de Alemania, por la gracia de Don Santiago</p>
					<form name="updateuser" action="comentaGrupo.php" method="POST">
					<input type="hidden" name="groupid" value="<?=$groupid?>" />
                                        <div class="mensaje" id="mensaje" ></div>
                                        <table>
                                        <tr>
                                                <td class="second"><label for="texto">Mensaje:</label></td>
                                                <td class="second"><textarea id='texto' name="texto" cols="50" rows="5"></textarea></td>
                                        </tr>
                                        </table>
                                        </form>
                                        <p></p>
                                        <p style="position:relative;right:20px"><input class="btn" type="button" onclick="guarda()" value="hablar"></p>
					<br/>
					<br/>
				</div>
<?
$usuarios = new UsuarioDAO();
$lista = $comentarios->getListadoRepos($groupid, $indice);
foreach ($lista as $comentario) {
  $nick = $usuarios->getById($comentario->getIdUsuario())->getNick();
?>
				<h4 class="date"><?= $comentario->getFecha()->format('d.m.Y H:i:s'); ?></h4>
                                <h3><strong><?= $nick ?>:</strong></h3>
				<div class="content">
					<p><?= $comentario->getTexto() ?></p>
				</div>
<?
}
?>	
	
				<!-- Main End -->
				<p style="position:relative;right:20px"><?
if (count($lista) >= 10) { ?>
<input class="btn" type="button" onclick="siguiente()" value="siguiente">
<?
}
if ($indice > 0) { ?>
<input class="btn" type="button" onclick="anterior()" value="anterior">
<?
}
?></p>
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
indice = <?= $indice ?>;

function siguiente() {
  indice = indice + 1;
  navega();
}

function anterior() {
  indice = indice - 1;
  navega();
}

function navega() {
  document.location.href="comentarios.php?indice=" + indice;
}
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
