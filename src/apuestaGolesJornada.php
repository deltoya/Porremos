<?
require_once("inc/Security.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/PartidoDAO.inc");

$usuarios = new UsuarioDAO(); 
$jugadoractual = $usuarios->getCurrent();

$id = $_REQUEST['id'];
$goles = $_REQUEST['goles'];
$ret = $jugadoractual->setApuestaGolesJornada($id, $goles);

echo json_encode($ret);
?>
