<?php
require_once("DDBB.inc");
require_once("Comentario.inc");

class ComentarioDAO extends DDBB {

  const TAM = 10;
  const HEAD = 5;

  public function getListado() {
    return $this->getListadoLimites(0, ComentarioDAO::TAM);
  }

  public function getCabeceraListado() {
    return $this->getListadoLimites(0, ComentarioDAO::HEAD);
  }

  public function getListadoRepos($pagina) {
    if (is_int($pagina)) {
      return $this->getListadoLimites($pagina * ComentarioDAO::TAM, ComentarioDAO::TAM);
    }
  }

  public function anyade($user_id, $texto) {
    if (strlen($texto)<140) { 
      $sql = $this->dbh->prepare("INSERT INTO comentarios (idusuario, texto) VALUES (:id, :texto)");
      $sql->execute(array(
        "id" => $user_id,
        "texto" => $texto,
        ));
    }
  }

  private function getListadoLimites($desde, $numero) {
    $retorno = array();
    if (!is_int($desde))
      return $retorno;

    $sql = $this->dbh->prepare("SELECT * FROM comentarios ORDER BY id DESC LIMIT " . $desde  . ", " . $numero);
    $sql->execute();
    foreach ($sql->fetchAll() as $row) {
        $aux = new Comentario();

        $aux->setId($row['id']);
        $aux->setIdUsuario($row['idusuario']);
        $aux->setTexto($row['texto']);
        $aux->setFecha($row['fecha']);

        $retorno[] = $aux;
    }
    return $retorno;
  }

}

?>
