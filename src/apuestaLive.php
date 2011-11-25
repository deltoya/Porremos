<?
require_once("inc/Security.inc");
require_once("inc/UsuarioDAO.inc");
require_once("inc/PartidoDAO.inc");

$usuarios = new UsuarioDAO(); 
$jugadoractual = $usuarios->getCurrent();

$id = $_REQUEST['id'];
$local = $_REQUEST['local'];
$visitante = $_REQUEST['visitante'];
$ret = $jugadoractual->setApuestaLive($id, $local, $visitante);

echo json_encode($ret);
?>
