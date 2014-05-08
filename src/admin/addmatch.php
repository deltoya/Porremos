<?
require_once("../inc/Admin.inc");
require_once("../inc/PartidoDAO.inc");

$partidos = new PartidoDAO();
$partido = new Partido();

$local = $_REQUEST['local'];
$visitante = $_REQUEST['visitante'];
$fecha = $_REQUEST['fecha'];
$puntos = $_REQUEST['puntos'];

$partido->setLocal($local);
$partido->setVisitante($visitante);
$partido->setFecha($fecha . ' 20:45:00');
$partido->setPuntos($puntos);
$partido->setJugado(0);
$partido->setGolesLocal(0);
$partido->setGolesVisitante(0);

$partido->save();

header('Location:../index.php');

?>
