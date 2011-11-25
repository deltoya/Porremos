<?
require_once("../inc/Admin.inc");
require_once("../inc/JornadaDAO.inc");

$jornada = new Jornada();

$fecha = $_REQUEST['fecha'];
$minutos = $_REQUEST['minutos'];

$jornada->setFecha($fecha);
$jornada->setMinutos($minutos);

$jornada->save();

header('Location:../index.php');

?>
