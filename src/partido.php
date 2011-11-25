<?
require_once("inc/PartidoDAO.inc");
$partidos = new PartidoDAO();

$partidoid = $_REQUEST['id'];
if (!$partidoid)
  $partidoid = '1';

$partido = $partidos->getById($partidoid);

if (!$partido->esCerrado()) {
  $res = $partidos->getApuestas($partidoid);
?>
<img src="http://chart.apis.google.com/chart?
chs=180x100
&amp;chd=t:<?= $res['1'] . ',' . $res['X'] . ',' . $res['2'] ?>
&amp;cht=p3
&amp;chl=1|X|2" />
<?
} else {
  $lista = $partidos->getApostadores($partidoid);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/default.css" />
</head>
<body> 
  <table>
    <tr>
	<th class="first"><strong>jugador</strong></th>
	<th class="second"><strong>ap.</strong></th>
    </tr>
<?
  $clase = 'rowB';
  foreach ($lista as $dato) {
    $clase = ($clase == 'rowA') ? 'rowB' : 'rowA';
?>
    <tr class="<?= $clase ?>">
	<td class="first"><?= $dato['usuario'] ?></td>
	<td class="second"><?= $dato['apuesta'] ?></td>
    </tr>
<?
  }
?>
  </table>
</body>
</html>
<?
}
?>
