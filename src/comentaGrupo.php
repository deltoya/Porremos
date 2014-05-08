<?
require_once("inc/Security.inc");
require_once("inc/ComentarioGrupoDAO.inc");
require_once("inc/UsuarioDAO.inc");

$comentarios = new ComentarioGrupoDAO();
$usuarios = new UsuarioDAO();
$usuario = $usuarios->getCurrent();

if ($usuario->getId()== 265) {
  header('Location:/comentarios.php');
} else
{

  $texto = $_POST['texto'];
  $group_id = $_POST['groupid'];

  if ($comentarios->checkValid($group_id, $usuario->getId()) )
  {
    if ($texto) {
      $comentarios->anyade($group_id, $usuario->getId(), stripslashes($texto));
    }
    header('Location:/commentGroup.php?groupid=' . $group_id);
  } else {
    header('Location:/comentarios.php');
  }
}


?>
