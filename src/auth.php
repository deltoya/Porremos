<?
require_once("inc/UsuarioDAO.inc");

$usuarios = new UsuarioDAO();
$user_id = $usuarios->checkUser($_REQUEST['username'], $_REQUEST['password']);
session_start();

if ($user_id) {
  $_SESSION['user_id'] = $user_id;
  error_log ("Usuario logado:" . $_REQUEST['username']);
  header('Location:/index.php');
} else {
  $_SESSION['mensaje'] = "Usuario o password incorrectos";
  error_log ("Password incorrecta para:" . $_REQUEST['username']);
  header('Location:/login.php');
}

?>
