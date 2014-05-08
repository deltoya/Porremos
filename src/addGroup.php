<?
require_once("inc/Security.inc");
require_once("inc/GrupoDAO.inc");
require_once("inc/UsuarioDAO.inc");

$grupo = new Grupo();
$grupos = new GrupoDAO(); 

$usuarios = new UsuarioDAO();
$jugadoractual = $usuarios->getCurrent();

$newname = stripslashes($_REQUEST['groupname']);
$desc = stripslashes($_REQUEST['description']);

$grupo->setNombre($newname);
$grupo->setDescripcion($desc);

$grupos->create($grupo, $jugadoractual->getId());


header('Location:/grupos.php');
?>
