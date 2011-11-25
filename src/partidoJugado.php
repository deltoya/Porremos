<?
require_once("inc/PartidoDAO.inc");
$partidos = new PartidoDAO();

$partidoid = $_REQUEST['id'];
if (!$partidoid)
  $partidoid = '1';

$partido = $partidos->getById($partidoid);

if ($partido->getJugado()) {
  $res = $partidos->getResultados($partidoid);
?>
<img src="http://chart.apis.google.com/chart?
chs=300x100
&chd=t:<?= $res['Pleno'] . ',' . $res['Parcial'] . ',' . $res['Signo'] . ',' . $res['Fallo'] ?>
&cht=p3
&chl=Pleno|Parcial|Signo|Fallo" />
<?
}
?>
