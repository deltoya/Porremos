<?
require_once("inc/Security.inc");
/*
require_once("inc/PreguntaDAO.inc");
require_once("inc/UsuarioDAO.inc");

$preguntas = new PreguntaDAO(); 
$usuarios = new UsuarioDAO(); 
$jugadoractual = $usuarios->getCurrent();
$lista = $preguntas->getTodas();

foreach($lista as $pregunta) {
    if ($_REQUEST['ap_' . $pregunta->getId()] != '') {
      $jugadoractual->setRespuesta($pregunta->getId(), $_REQUEST['ap_' . $pregunta->getId()]);
    }
}
*/
header('Location:preguntas.php');
?>
