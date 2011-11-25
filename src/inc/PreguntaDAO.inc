<?php
require_once("DDBB.inc");
require_once("Pregunta.inc");

class PreguntaDAO extends DDBB {

  public function getById($id) {
    $retorno = new Pregunta();
    $sql = $this->dbh->prepare("SELECT * FROM preguntas where id = ?");
    $sql->execute(array($id));
    $row = $sql->fetch();
    if (!$row)
      return false;

    $retorno->setId($row['id']);
    $retorno->setTexto($row['texto']);
    $retorno->setRespuesta($row['respuesta']);
    $retorno->setValor($row['puntos']);
    $retorno->setFecha($row['fecha']);

    return $retorno;
  }


  public function getListado($tipo = 1) {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT * FROM preguntas WHERE tipo = :tipo ORDER BY id");
    $sql2 = $this->dbh->prepare("SELECT * FROM respuestas WHERE id = :id ");
    $sql->execute(array('tipo' => $tipo));
    foreach ($sql->fetchAll() as $row) {
        $aux = new Pregunta();

        $aux->setTexto($row['texto']);
        $aux->setRespuesta($row['respuesta']);
        $aux->setValor($row['puntos']);
        $aux->setId($row['id']);
        $aux->setFecha($row['fecha']);

        $sql2->execute(array('id' => $aux->getId()));
        foreach ($sql2->fetchAll() as $resp) {
          $aux->addPosible($resp['respuesta']);
        }

        $retorno[] = $aux;
    }
    return $retorno;
  }

  public function getTodas() {
    $retorno = array();
    $sql = $this->dbh->prepare("SELECT * FROM preguntas");
    $sql2 = $this->dbh->prepare("SELECT * FROM respuestas WHERE id = :id ");
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
        $aux = new Pregunta();

        $aux->setTexto($row['texto']);
        $aux->setRespuesta($row['respuesta']);
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
