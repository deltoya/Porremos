<?
require_once("inc/Security.inc");
require_once("inc/ComentarioDAO.inc");
require_once("inc/UsuarioDAO.inc");

$comentarios = new ComentarioDAO();
$usuarios = new UsuarioDAO();
$usuario = $usuarios->getCurrent();

if ($usuario->getId()!= 265) {

  $texto = $_POST['texto'];

  if ($texto)
    $comentarios->anyade($usuario->getId(), stripslashes($texto));
}

header('Location:/comentarios.php');

?>
