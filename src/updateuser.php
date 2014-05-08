<?
require_once("inc/Security.inc");
require_once("inc/UsuarioDAO.inc");

$usuarios = new UsuarioDAO();
$usuario = $usuarios->getCurrent();

$newpwd = $_REQUEST['password'];
$newpwd2 = $_REQUEST['password2'];
$newnick = $_REQUEST['nick'];
$cambio = false;

if(($newpwd == $newpwd2) && (strlen($newpwd) > 6) && (strlen($newpwd)<26) && ($usuario->getId()!= 265)){
  $cambio = true;
  $usuario->setPassword(crypt($newpwd));
}

if((strlen($newnick) >= 4) && (strlen($newnick) <= 15)) {
  $cambio = true;
  $usuario->setNick($newnick);
}

if ($cambio)
  $usuario->save();

header('Location:/logout.php');

?>
