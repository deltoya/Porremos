<?php
require_once("DDBB.inc");
require_once("Pregunta.inc");

class PreguntaDAO extends DDBB {

  public function getById($id) {
    $retorno = new Pregunta();
    $sql = $this->dbh->prepare("SELECT p.id, p.texto, p.puntos, s.fecha, s.respuesta FROM preguntas p, preguntas_solucionadas s where p.id = ? and p.id = s.id_pregunta");
    $sql->execute(array($id));
    $row = $sql->fetch();
    if (!$row)
      return false;

    $retorno->setId($row['id']);
    $retorno->setTexto($row['texto']);
    $retorno->addRespuesta($row['respuesta']);
    $retorno->setValor($row['puntos']);
    $retorno->setFecha($row['fecha']);

    return $retorno;
  }


  public function getListado($tipo = 1) {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT * FROM preguntas WHERE tipo = :tipo ORDER BY id");
    $sql2 = $this->dbh->prepare("SELECT * FROM respuestas WHERE id = :id ");
    $sql3 = $this->dbh->prepare("SELECT * FROM preguntas_solucionadas WHERE id_pregunta = :id ");

    $sql->execute(array('tipo' => $tipo));
    foreach ($sql->fetchAll() as $row) {
        $aux = new Pregunta();

        $aux->setTexto($row['texto']);
        $aux->setValor($row['puntos']);
        $aux->setId($row['id']);
        $aux->setFecha($row['fecha']);

        $sql2->execute(array('id' => $aux->getId()));
        foreach ($sql2->fetchAll() as $resp) {
          $aux->addPosible($resp['respuesta']);
        }

	$sql3->execute(array('id' => $aux->getId()));
        foreach ($sql3->fetchAll() as $resp) {
          $aux->addRespuesta($resp['respuesta']);
        }

        $retorno[] = $aux;
    }
    return $retorno;
  }

  public function getTodas() {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT * FROM preguntas");
    $sql2 = $this->dbh->prepare("SELECT * FROM respuestas WHERE id = :id ");
    $sql3 = $this->dbh->prepare("SELECT * FROM preguntas_solucionadas WHERE id = :id ");
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
        $aux = new Pregunta();

        $aux->setTexto($row['texto']);
        $aux->addRespuesta($row['respuesta']);
        $aux->setValor($row['puntos']);
        $aux->setFecha($row['fecha']);
        $aux->setId($row['id']);

        $sql2->execute(array('id' => $aux->getId()));
        foreach ($sql2->fetchAll() as $resp) {
          $aux->addPosible($resp['respuesta']);
        }

        $retorno[] = $aux;
    }
    return $retorno;
  }
}

?>
