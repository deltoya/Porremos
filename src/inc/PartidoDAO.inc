<?php
require_once("DDBB.inc");
require_once("Partido.inc");
require_once("UsuarioDAO.inc");
require_once("Comprobador.inc");

class PartidoDAO extends DDBB {

  public function save(Partido $partido) {
    $datos = array(
           'jugado' => $partido->getJugado(),
           'local' => $partido->getLocal(),
           'visitante' => $partido->getVisitante(),
           'goleslocal' => $partido->getGolesLocal(),
           'golesvisitante' => $partido->getGolesVisitante(),
           'puntos' => $partido->getPuntos(),
           'fecha' => $partido->getFecha()->format("Y-m-d H:i:s"),
         );

    if($partido->getId()) {
      $datos['id'] = $partido->getId();
      $sql = $this->dbh->prepare("UPDATE partidos SET " .
                                 "jugado = :jugado, local = :local, visitante = :visitante, " .
                                 "goleslocal = :goleslocal, golesvisitante = :golesvisitante, " .
                                 "puntos = :puntos, fecha = :fecha WHERE id = :id");
    } else {
      $sql = $this->dbh->prepare("INSERT INTO partidos (jugado, local, visitante, goleslocal, golesvisitante, puntos, fecha) " .
                                 "VALUES (:jugado, :local, :visitante, :goleslocal, :golesvisitante, :puntos, :fecha)");
    }

    $sql->execute($datos);
    $sql->closeCursor();

    if ($partido->getJugado())
      $this->puntua($partido);
  }

  private function puntua(Partido $partido) {
    $sql = $this->dbh->prepare("UPDATE jugadores SET puntos = puntos + :bono " .
                               "WHERE id = :id");
    $usuarios = new UsuarioDAO();
    $lista = $usuarios->getAll();
    foreach($lista as $usuario) {
      $apuesta = $usuario->getApuesta($partido->getId());
      $comprobador = Comprobador::comprueba($apuesta, $partido);

      if ($comprobador->esAcierto()) {
        $sql->execute(array(
           'id' => $usuario->getId(),
           'bono' => $comprobador->getPuntos()
          ));
        $sql->closeCursor();
      }
    }
  }

  public function getJugados() {
    return $this->getJugadoEquals('1');
  }

  public function getPendientes() {
    return $this->getJugadoEquals('0');
  }

  public function getById($id) {
    $retorno = new Partido($this);
    $sql = $this->dbh->prepare("SELECT * FROM partidos where id = ?");
    $sql->execute(array($id));
    $row = $sql->fetch();
    if (!$row)
      return false;

    $retorno->setLocal($row['local']);
    $retorno->setVisitante($row['visitante']);
    $retorno->setGolesLocal($row['goleslocal']);
    $retorno->setGolesVisitante($row['golesvisitante']);
    $retorno->setJugado($row['jugado']);
    $retorno->setFecha($row['fecha']);
    $retorno->setPuntos($row['puntos']);
    $retorno->setId($row['id']);

    return $retorno;
  }

  private function getJugadoEquals($value) {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT * FROM partidos where jugado = ? ORDER BY fecha, local");
    $sql->execute(array($value));
    foreach ($sql->fetchAll() as $row) {
        $aux = new Partido();

        $aux->setLocal($row['local']);
        $aux->setVisitante($row['visitante']);
        $aux->setGolesLocal($row['goleslocal']);
        $aux->setGolesVisitante($row['golesvisitante']);
        $aux->setJugado($row['jugado']);
        $aux->setFecha($row['fecha']);
        $aux->setPuntos($row['puntos']);
        $aux->setId($row['id']);

        $retorno[] = $aux;
    }
    return $retorno;
  }

  public function getApuestas($partidoid) {
    $retorno = array();

    $sql1 = $this->dbh->prepare("SELECT count(*) AS uno FROM apuestas WHERE goleslocal>golesvisitante AND id_partido = :idpartido");
    $sql1->execute(array('idpartido' => $partidoid));
    $row = $sql1->fetch();
    $retorno['1'] = $row['uno'];
    $sql1->closeCursor();

    $sqlX = $this->dbh->prepare("SELECT count(*) AS equis FROM apuestas WHERE goleslocal=golesvisitante AND id_partido = :idpartido");
    $sqlX->execute(array('idpartido' => $partidoid));
    $row = $sqlX->fetch();
    $retorno['X'] = $row['equis'];
    $sqlX->closeCursor();

    $sql2 = $this->dbh->prepare("SELECT count(*) AS dos FROM apuestas WHERE goleslocal<golesvisitante AND id_partido = :idpartido");
    $sql2->execute(array('idpartido' => $partidoid));
    $row = $sql2->fetch();
    $retorno['2'] = $row['dos'];
    $sql2->closeCursor();

    return $retorno;
  }

  public function getResultados($partidoid) {
    $retorno = array();

    $sql1 = $this->dbh->prepare("SELECT count(*) AS pleno FROM apuestas a, partidos p WHERE " .
            "a.goleslocal=p.goleslocal and a.golesvisitante=p.golesvisitante " .
            "and a.id_partido = p.id and p.id = :idpartido");
    $sql1->execute(array('idpartido' => $partidoid));
    $row = $sql1->fetch();
    $retorno['Pleno'] = $row['pleno'];
    $sql1->closeCursor();

    $sqlX = $this->dbh->prepare("SELECT count(*) AS parcial FROM apuestas a, partidos p WHERE " .
            "a.id_partido = p.id and p.id=:idpartido and  " .
            "((a.goleslocal > a.golesvisitante and p.goleslocal > p.golesvisitante) or " .
            " (a.goleslocal = a.golesvisitante and p.goleslocal = p.golesvisitante) or " .
            " (a.goleslocal < a.golesvisitante and p.goleslocal < p.golesvisitante)) and " .
            "((a.goleslocal = p.goleslocal and a.golesvisitante != p.golesvisitante) or " .
            " (a.goleslocal != p.goleslocal and a.golesvisitante = p.golesvisitante)) ");
    $sqlX->execute(array('idpartido' => $partidoid));
    $row = $sqlX->fetch();
    $retorno['Parcial'] = $row['parcial'];
    $sqlX->closeCursor();

    $sql2 = $this->dbh->prepare("SELECT count(*) AS signo FROM apuestas a, partidos p WHERE " .
            "a.id_partido = p.id and p.id=:idpartido and  " .
            "((a.goleslocal > a.golesvisitante and p.goleslocal > p.golesvisitante) or " .
            " (a.goleslocal = a.golesvisitante and p.goleslocal = p.golesvisitante) or " .
            " (a.goleslocal < a.golesvisitante and p.goleslocal < p.golesvisitante)) and " .
            "a.goleslocal != p.goleslocal and a.golesvisitante != p.golesvisitante ");

    $sql2->execute(array('idpartido' => $partidoid));
    $row = $sql2->fetch();
    $retorno['Signo'] = $row['signo'];
    $sql2->closeCursor();

    $sqlfallo = $this->dbh->prepare("SELECT count(*) AS fallo FROM apuestas a, partidos p WHERE " .
            "a.id_partido = p.id and p.id=:idpartido and  " .
            "((a.goleslocal > a.golesvisitante and p.goleslocal <= p.golesvisitante) or " .
            " (a.goleslocal = a.golesvisitante and p.goleslocal != p.golesvisitante) or " .
            " (a.goleslocal < a.golesvisitante and p.goleslocal >= p.golesvisitante)) ");
    $sqlfallo->execute(array('idpartido' => $partidoid));
    $row = $sqlfallo->fetch();
    $retorno['Fallo'] = $row['fallo'];
    $sqlfallo->closeCursor();

    return $retorno;
  }

  public function getApostadores($partidoid) {
    $retorno = array();

    $sql = $this->dbh->prepare("SELECT j.nick, a.goleslocal, a.golesvisitante FROM jugadores j, apuestas a " .
                                "WHERE a.id_jugador = j.id AND a.id_partido = :idpartido");
    $sql->execute(array('idpartido' => $partidoid));
    foreach ($sql->fetchAll() as $row) {
        $aux = array();

        $aux['usuario'] = htmlspecialchars($row['nick']);
        $aux['apuesta'] = $row['goleslocal'] . ' - ' . $row['golesvisitante'];

        $retorno[] = $aux;
    }
    $sql->closeCursor();

    return $retorno;
  }

}

?>
