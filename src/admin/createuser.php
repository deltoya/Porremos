<?
require_once("../inc/Admin.inc");
require_once("../inc/UsuarioDAO.inc");

$usuario = new Usuario();

$newpwd = $_REQUEST['password'];
$newpwd2 = $_REQUEST['password2'];
$newnick = $_REQUEST['nick'];
$newname = $_REQUEST['nombre'];
$newemail = $_REQUEST['email'];

$usuario->setPassword(crypt($newpwd));
$usuario->setNick($newnick);
$usuario->setNombre($newname);
$usuario->setEmail($newemail);
$usuario->setMinutos(20);

$usuarios->create($usuario);

header('Location:../clasifica.php');

?>
