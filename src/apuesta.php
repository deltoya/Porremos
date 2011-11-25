<?
require_once("inc/Security.inc");
require_once("inc/PartidoDAO.inc");
require_once("inc/UsuarioDAO.inc");

$partidos = new PartidoDAO(); 
$usuarios = new UsuarioDAO(); 
$jugadoractual = $usuarios->getCurrent();
$partidospendientes = $partidos->getPendientes();

foreach($partidospendientes as $partido) {
    if ($_REQUEST['ap_' . $partido->getId() . '_L'] != '' && $_REQUEST['ap_' . $partido->getId() . '_V'] != '') {
      $jugadoractual->setApuesta($partido->getId(), $_REQUEST['ap_' . $partido->getId() . '_L'], $_REQUEST['ap_' . $partido->getId() . '_V']);
    }
}

header('Location:/index.php');
?>
