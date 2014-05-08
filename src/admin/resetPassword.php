<?
require_once("../inc/Admin.inc");
require_once("../inc/UsuarioDAO.inc");

$usuarios = new UsuarioDAO();

$newpwd = $_REQUEST['password'];
$newpwd2 = $_REQUEST['password2'];
$name = $_REQUEST['nombre'];
$cambio = false;

if(($newpwd == $newpwd2) && (strlen($newpwd) > 6) && (strlen($newpwd)<26)){
  $cambio = true;
  $usuario = $usuarios->getByName($name);
  $usuario->setPassword(crypt($newpwd));
  $usuario->save();
}

header('Location:/clasifica.php');

?>
