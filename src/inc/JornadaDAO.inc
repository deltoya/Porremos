<?php
require_once("DDBB.inc");
require_once("Partido.inc");
require_once("Jornada.inc");
require_once("UsuarioDAO.inc");
require_once("Comprobador.inc");

class JornadaDAO extends DDBB {

  public function save(Jornada $jornada) {
    $datos = array(
           'goles' => $jornada->getGoles(),
           'minutos' => $jornada->getMinutos(),
           'fecha' => $jornada->getFecha()->format("Y-m-d"),
         );

    if($jornada->getId()) {
      $datos['id'] = $jornada->getId();
      $sql = $this->dbh->prepare("UPDATE golesjornadas SET " .
                                 "goles = :goles, minutos = :minutos, fecha = :fecha WHERE id = :id");
    } else {
      $sql = $this->dbh->prepare("INSERT INTO golesjornadas (goles, minutos, fecha) " .
                                 "VALUES (:goles, :minutos, :fecha)");
    }

    $sql->execute($datos);
    $sql->closeCursor();

    if ($jornada->getGoles() != 0)
      $this->puntua($jornada);
  }

  private function puntua(Jornada $jornada) {
    $sql = $this->dbh->prepare("UPDATE jugadores SET minutos = minutos + :bono " .
                               "WHERE id = :id");
    $usuarios = new UsuarioDAO();
    $lista = $usuarios->getAll();
    foreach($lista as $usuario) {
      $minutos = $usuario->getMinutosEnJornada($jornada->getId());

      if ($minutos['minutos']) {
        $sql->execute(array(
           'id' => $usuario->getId(),
           'bono' => $minutos['minutos']
          ));
        $sql->closeCursor();
      }
    }
  }

  public function getAllJornadas() {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT * FROM golesjornadas ORDER BY fecha");
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
        $aux = new Jornada();

        $aux->setGoles($row['goles']);
        $aux->setMinutos($row['minutos']);
        $aux->setFecha($row['fecha']);
        $aux->setId($row['id']);

        $retorno[] = $aux;
    }
    $sql->closeCursor();
    return $retorno;
  }

  public function getById($id) {
    $sql = $this->dbh->prepare("SELECT * FROM golesjornadas WHERE id=?");
    $sql->execute(array($id));
    $row = $sql->fetch();
    if(!$row)
        return false;

    $retorno = new Jornada();

    $retorno->setGoles($row['goles']);
    $retorno->setMinutos($row['minutos']);
    $retorno->setFecha($row['fecha']);
    $retorno->setId($row['id']);

    return $retorno;
  }


}

?>
