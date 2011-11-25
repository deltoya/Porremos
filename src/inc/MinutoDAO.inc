<?php
require_once("DDBB.inc");
require_once("PartidoDAO.inc");
require_once("Minuto.inc");
require_once("UsuarioDAO.inc");

class MinutoDAO extends DDBB {

  public function save(Minuto $minuto) {
    $datos = array(
           'id_jugador' => $minuto->getIdJugador(),
           'id_partido' => $minuto->getIdPartido(),
           'goleslocal' => $minuto->getGolesLocal(),
           'golesvisitante' => $minuto->getGolesVisitante(),
           'minutos' => $minuto->getMinutos(),
           'fecha' => $minuto->getFecha()->format("Y-m-d H:i:s"),
         );

    if($minuto->getId()) {
      $datos['id'] = $minuto->getId();
      $sql = $this->dbh->prepare("UPDATE minutos SET " .
                                 "id_jugador = :id_jugador, id_partido = :id_partido, " .
                                 "goleslocal = :goleslocal, golesvisitante = :golesvisitante, " .
                                 "minutos = :minutos, fecha = :fecha WHERE id = :id");
    } else {
      $sql = $this->dbh->prepare("INSERT INTO minutos (id_jugador, id_partido, goleslocal, golesvisitante, minutos, fecha) " .
                                 "VALUES (:id_jugador, :id_partido, :goleslocal, :golesvisitante, :minutos, :fecha)");
    }

    $sql->execute($datos);
    $sql->closeCursor();
  }

  public function getMinutosByUserId($id) {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT m.*, p.local, p.visitante FROM minutos m, partidos p where m.id_jugador = ? AND m.id_partido = p.id ORDER BY fecha");
    $sql->execute(array($id));
    foreach ($sql->fetchAll() as $row) {
        $aux = new Minuto();

        $aux->setLocal($row['local']);
        $aux->setVisitante($row['visitante']);
        $aux->setGolesLocal($row['goleslocal']);
        $aux->setGolesVisitante($row['golesvisitante']);
        $aux->setFecha($row['fecha']);
        $aux->setMinutos($row['minutos']);
        $aux->setId($row['id']);
        $aux->setIdJugador($id);
        $aux->setIdPartido($row['id_partido']);

        $retorno[] = $aux;
    }
    return $retorno;
  }
}
?>
