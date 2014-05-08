<?
require_once("../inc/Admin.inc");
require_once("../inc/Security.inc");
require_once("../inc/PartidoDAO.inc");
require_once("../inc/UsuarioDAO.inc");

$partidos = new PartidoDAO(); 
$usuarios = new UsuarioDAO(); 
$jornadas = new JornadaDAO();
$id= $_REQUEST['id'];
$goles = $_REQUEST['goles'];

$jornada = $jornadas->getById($id);
$jornada->setGoles($goles);
$jornada->save();

$ret = array ('ret'=>true, 'data'=>'Goles actualizados');
echo json_encode($ret);

?>
