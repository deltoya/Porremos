<?php
require_once("DDBB.inc");
require_once("Comentario.inc");

class ComentarioGrupoDAO extends DDBB {

  const TAM = 10;
  const HEAD = 5;

  public function getListado($groupid) {
    return $this->getListadoLimites($groupid, 0, ComentarioGrupoDAO::TAM);
  }

  public function getCabeceraListado($groupid) {
    return $this->getListadoLimites($groupid, 0, ComentarioGrupoDAO::HEAD);
  }

  public function getListadoRepos($groupid, $pagina) {
    if (is_int($pagina)) {
      return $this->getListadoLimites($groupid, $pagina * ComentarioGrupoDAO::TAM, ComentarioGrupoDAO::TAM);
    }
  }

  public function anyade($groupid, $user_id, $texto) {
    if (strlen($texto)<140) { 
      $sql = $this->dbh->prepare("INSERT INTO comentariosgrupo (idgrupo, idusuario, texto) VALUES (:idGroup, :id, :texto)");
      $sql->execute(array(
        "idGroup" => $groupid,
        "id" => $user_id,
        "texto" => $texto,
        ));
    }
  }

  public function checkValid($groupid, $user_id) {
    $sql = $this->dbh->prepare("SELECT * FROM grupo_jugador WHERE id_grupo=:idgrupo AND id_jugador=:idjugador ");
    $sql->execute(array(
	"idgrupo" => $groupid,
	"idjugador" => $user_id,
        ));
    return $sql->rowCount() == 1;
  }

  private function getListadoLimites($groupid, $desde, $numero) {
    $retorno = array();
    if (!is_int($desde))
      return $retorno;

    $sql = $this->dbh->prepare("SELECT * FROM comentariosgrupo where idgrupo = :id_grupo ORDER BY id DESC LIMIT " . $desde  . ", " . $numero);
    $sql->execute(array(
	"id_grupo" => $groupid
	));
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
