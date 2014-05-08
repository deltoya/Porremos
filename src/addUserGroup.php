<?
require_once("inc/Security.inc");
require_once("inc/GrupoDAO.inc");
require_once("inc/UsuarioDAO.inc");


$usuarios = new UsuarioDAO();
$grupos = new GrupoDAO(); 
$jugadoractual = $usuarios->getCurrent();

foreach($grupos->getByUserId($jugadoractual->getId()) as $grupo) {
  if ($grupo->getId() == $_REQUEST['groupid'])
  {
    $grupo->addUser($_REQUEST['email']);
    break;
  }
}

header('Location:/grupos.php');
?>
