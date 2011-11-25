<?
require_once("../inc/Admin.inc");
require_once("../inc/PartidoDAO.inc");
require_once("../inc/UsuarioDAO.inc");

$partidos = new PartidoDAO(); 
$usuarios = new UsuarioDAO(); 
$partidospendientes = $partidos->getPendientes();

foreach($partidospendientes as $partido) {
    if ($_REQUEST['ap_' . $partido->getId() . '_L'] != '' && $_REQUEST['ap_' . $partido->getId() . '_V'] != '') {
      $partido->setGolesLocal($_REQUEST['ap_' . $partido->getId() . '_L']);
      $partido->setGolesVisitante($_REQUEST['ap_' . $partido->getId() . '_V']);
      $partido->setJugado('1');
      $partido->save();
    }
}

header('Location:../index.php');
?>
